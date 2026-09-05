<?php
namespace App\Services;

use App\Models\Promotion;
use Illuminate\Support\Carbon;

class PromotionEngine
{
    public static function apply(array $cart, ?string $coupon = null): array
    {
        $now = now();
        $promos = Promotion::query()
            ->where('active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
            })
            ->get();

        $itemDiscounts = [];
        $applied = [];
        $totalDiscount = 0.0;

        foreach ($cart as $idx => $item) {
            $itemDiscounts[$idx] = 0.0;
        }

        foreach ($promos as $promo) {
            $cfg = (array) ($promo->config ?? []);
            // Normalisasi alias config agar lebih fleksibel
            if (isset($cfg['discount']) && !isset($cfg['discount_percent']) && !isset($cfg['discount_amount'])) {
                // Asumsikan 'discount' adalah persen
                $cfg['discount_percent'] = (float) $cfg['discount'];
            }
            if (isset($cfg['amount']) && !isset($cfg['discount_amount'])) {
                $cfg['discount_amount'] = (float) $cfg['amount'];
            }
            $productIds = isset($cfg['product_ids']) && is_array($cfg['product_ids']) ? $cfg['product_ids'] : null;

            if ($promo->type === 'coupon') {
                $input = trim((string) ($coupon ?? ''));
                $code = trim((string) ($cfg['code'] ?? ''));
                $name = trim((string) $promo->name);
                // Izinkan cocok berdasarkan 'code' atau 'name' promo (case-insensitive)
                if ($input === '' || (strcasecmp($input, $code) !== 0 && strcasecmp($input, $name) !== 0)) {
                    continue; // coupon not provided or mismatched
                }
            }

            foreach ($cart as $idx => $item) {
                $pid = (int) ($item['product_id'] ?? 0);
                if ($productIds && !in_array($pid, $productIds)) { continue; }

                $qty = (float) ($item['qty'] ?? 0);
                $price = (float) ($item['price'] ?? 0);
                $line = $qty * $price;

                $lineDisc = 0.0;
                // percent discount
                if (isset($cfg['discount_percent'])) {
                    $p = max(0, min(100, (float) $cfg['discount_percent']));
                    $lineDisc += ($p / 100.0) * $line;
                }
                // flat per line
                if (isset($cfg['discount_amount'])) {
                    $flat = max(0, (float) $cfg['discount_amount']);
                    $lineDisc += min($flat, $line);
                }

                if ($lineDisc > 0) {
                    $itemDiscounts[$idx] += $lineDisc;
                }
            }

            // Mark applied promo name once if any discount produced
            $sumItem = array_sum($itemDiscounts);
            if ($sumItem > $totalDiscount) {
                $applied[] = $promo->name;
            }
            $totalDiscount = $sumItem;
        }

        // Clamp not exceed subtotal (client will also clamp grand_total)
        return [
            'item_discounts' => $itemDiscounts,
            'total_discount' => round($totalDiscount, 2),
            'applied' => $applied,
        ];
    }
}
