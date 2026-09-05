<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $period = $request->input('period'); // weekly|monthly|yearly|custom
        $ref = $request->input('date');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $today = Carbon::today();
        if ($period) {
            $base = $ref ? Carbon::parse($ref) : $today;
            switch (strtolower($period)) {
                case 'weekly':
                    $startDate = $base->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
                    $endDate = $base->copy()->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                    break;
                case 'monthly':
                    $startDate = $base->copy()->startOfMonth()->format('Y-m-d');
                    $endDate = $base->copy()->endOfMonth()->format('Y-m-d');
                    break;
                case 'yearly':
                    $startDate = $base->copy()->startOfYear()->format('Y-m-d');
                    $endDate = $base->copy()->endOfYear()->format('Y-m-d');
                    break;
                default:
                    // fallthrough to custom
                    break;
            }
        }

        $startDate = $startDate ?: $today->format('Y-m-d');
        $endDate = $endDate ?: $today->format('Y-m-d');

        $sales = Sale::with(['user', 'saleItems.product'])
            ->whereDate('date_time', '>=', $startDate)
            ->whereDate('date_time', '<=', $endDate)
            ->orderBy('date_time', 'desc')
            ->get();

        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('grand_total');
        $totalDiscount = $sales->sum('discount');

        if ($request->input('export') === 'pdf') {
            // Generate inline PDF using dompdf/dompdf
            $html = view('reports.sales_pdf', [
                'sales' => $sales,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'totalSales' => $totalSales,
                'totalRevenue' => $totalRevenue,
                'totalDiscount' => $totalDiscount,
            ])->render();

            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $file = sprintf('laporan-penjualan_%s_%s.pdf', $startDate, $endDate);
            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$file.'"'
            ]);
        }

        return view('reports.sales', compact(
            'sales',
            'startDate',
            'endDate',
            'totalSales',
            'totalRevenue',
            'totalDiscount'
        ) + ['period' => $period, 'date' => $ref]);
    }

    public function daily(Request $request)
    {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');
        
        $sales = Sale::with(['user', 'saleItems.product'])
            ->whereDate('date_time', $date)
            ->orderBy('date_time', 'desc')
            ->get();
            
        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('grand_total');
        $totalDiscount = $sales->sum('discount');
        
        return view('reports.daily', compact(
            'sales',
            'date',
            'totalSales',
            'totalRevenue',
            'totalDiscount'
        ));
    }
}
