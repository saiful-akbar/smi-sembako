<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    public function midtransCallback(Request $request)
    {
        Log::info('midtrans-notify', $request->all());
        $serverKey = config('midtrans.server_key');
        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signature = (string) $request->input('signature_key');
        $calc = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if (!hash_equals($calc, $signature)) {
            return response()->json(['status' => 'invalid-signature'], 401);
        }

        $sale = Sale::where('midtrans_order_id', $orderId)->first();
        if (!$sale) { return response()->json(['status' => 'sale-not-found'], 404); }

        $paymentType = (string) $request->input('payment_type');
        $transactionStatus = (string) $request->input('transaction_status');
        $transactionId = (string) $request->input('transaction_id');
        $amount = (float) $grossAmount;

        if (in_array($transactionStatus, ['capture','settlement'])) {
            $methodMap = ['qris'=>'qris','gopay'=>'ewallet','shopeepay'=>'ewallet','bank_transfer'=>'bank_transfer','credit_card'=>'credit_card'];
            $method = $methodMap[$paymentType] ?? 'ewallet';
            Payment::create([
                'sale_id' => $sale->id,
                'method' => $method,
                'amount' => $amount,
                'provider' => $paymentType,
                'provider_ref' => $transactionId,
                'meta' => $request->all(),
            ]);
        }

        $paid = (float) $sale->payments()->sum('amount');
        Log::info('midtrans-snap-amount', [
            'sale_id' => $sale->id,
            'grand_total' => (float) $sale->grand_total,
            'paid_sum' => $paid,
            'method' => $request->input('payment_method'),
        ]);
        if ($paid <= 0) { $sale->payment_status = 'unpaid'; }
        elseif ($paid < (float) $sale->grand_total) { $sale->payment_status = 'partial'; }
        else { $sale->payment_status = 'paid'; }
        $sale->save();

        return response()->json(['status' => 'ok']);
    }

    public function createSnap(Request $request, Sale $sale)
    {
        $serverKey = (string) config('midtrans.server_key');
        $isProduction = (bool) config('midtrans.is_production');
        $useMockFlag = config('midtrans.use_mock');

        $orderId = 'ORDER-' . $sale->id . '-' . time();
        $sale->midtrans_order_id = $orderId;
        $sale->save();

        $paid = (float) $sale->payments()->sum('amount');
        $snapAmount = (int) max(0, round(((float)$sale->grand_total) - $paid));
        Log::info('midtrans-snap-amount-calculated', [
            'sale_id' => $sale->id,
            'snap_amount' => $snapAmount,
        ]);
        if ($snapAmount <= 0) {
            return response()->json(['message' => 'Tidak ada tagihan outstanding'], 400);
        }

        // Pakai mock hanya jika server key kosong atau MIDTRANS_USE_MOCK=true
        $useMock = empty(trim($serverKey)) || in_array(strtolower((string) $useMockFlag), ['1','true','yes'], true);
        if ($useMock) {
            $token = 'mock_' . $sale->id . '_' . substr(sha1($orderId), 0, 10);
            $redirect_url = url('/pos/receipt/' . $sale->id);
            $sale->midtrans_token = $token;
            $sale->save();
            return response()->json(['token' => $token, 'redirect_url' => $redirect_url, 'mock' => true]);
        }

        // Real Midtrans request
        try {
            \Midtrans\Config::$serverKey = $serverKey;
            \Midtrans\Config::$isProduction = $isProduction;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $customerEmail = $request->input('customer_email')
                ?? optional($sale->user)->email
                ?? ('guest'.now()->timestamp.'@rifqysaputra.my.id');
            if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
                $customerEmail = 'guest'.now()->timestamp.'@rifqysaputra.my.id';
            }
            $enabledPayments = array_values(array_filter((array) config('midtrans.enabled_payments', [])));
            if (empty($enabledPayments)) {
                $enabledPayments = ['qris','gopay','shopeepay','bca_va','bni_va','bri_va','permata_va','credit_card'];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $snapAmount,
                ],
                'customer_details' => [
                    'first_name' => optional($sale->user)->name ?? 'Customer',
                    'email' => $customerEmail,
                ],
                // Hindari 'other_qris' karena dapat memicu error 116 pada akun tanpa konfigurasi khusus
                'enabled_payments' => $enabledPayments,
            ];

            $snap = \Midtrans\Snap::createTransaction($params);
            $sale->midtrans_token = $snap->token ?? null;
            $sale->save();
            return response()->json(['token' => $snap->token, 'redirect_url' => $snap->redirect_url]);
        } catch (\Throwable $e) {
            $ck = (string) config('midtrans.client_key');
            $skTail = substr((string) $serverKey, -6);
            $ckTail = substr($ck, -6);
            Log::error('midtrans-create-failed', [
                'sale_id' => $sale->id,
                'env' => $isProduction ? 'production' : 'sandbox',
                'server_key_tail' => $skTail,
                'client_key_tail' => $ckTail,
                'message' => $e->getMessage(),
            ]);
            if (in_array(strtolower((string) $useMockFlag), ['1','true','yes'], true)) {
                // Fallback mock hanya jika dipaksa melalui env
                $token = 'mock_' . $sale->id . '_' . substr(sha1($orderId), 0, 10);
                $redirect_url = url('/pos/receipt/' . $sale->id);
                return response()->json(['token' => $token, 'redirect_url' => $redirect_url, 'mock' => true]);
            }
            return response()->json(['message' => 'Midtrans error: ' . $e->getMessage()], 400);
        }
    }
}
