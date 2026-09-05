<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Sale, SaleItem, Product, User};
use Illuminate\Support\Carbon;

class AnalyticsController extends Controller
{
  public function index()
  {
    $from = now()->subDays(30);
    $salesQ = Sale::where('date_time', '>=', $from);
    $metrics = [
      'count' => (clone $salesQ)->count(),
      'revenue' => (clone $salesQ)->sum('grand_total'),
    ];
    $daily = Sale::selectRaw('DATE(date_time) d, SUM(grand_total) s, COUNT(*) c')->where('date_time', '>=', now()->subDays(7))->groupBy('d')->orderBy('d')->get();
    $top = SaleItem::selectRaw('product_id, SUM(qty) qty, SUM(line_total) revenue')->groupBy('product_id')->orderByRaw('SUM(qty) DESC')->limit(5)->with('product')->get();
    $hours = Sale::selectRaw('HOUR(date_time) h, COUNT(*) c, SUM(grand_total) s')->groupBy('h')->orderBy('h')->get();
    $cashiers = Sale::selectRaw('created_by, COUNT(*) c, SUM(grand_total) s')->groupBy('created_by')->orderByRaw('SUM(grand_total) DESC')->with('user')->limit(5)->get();
    return view('analytics.index', compact('metrics', 'daily', 'top', 'hours', 'cashiers'));
  }
}
