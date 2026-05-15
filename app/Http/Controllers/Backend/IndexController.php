<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $salesByMonth = Invoice::query()
            ->selectRaw('CAST(EXTRACT(MONTH FROM created_at) AS INTEGER) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->where('status', 'success')
            ->groupByRaw('CAST(EXTRACT(MONTH FROM created_at) AS INTEGER)')
            ->pluck('total', 'month');

        return view('backend.index', [
            'productCount' => Product::count(),
            'orderCount' => Invoice::count(),
            'userCount' => User::count(),
            'monthlySales' => collect(range(1, 12))
                ->map(fn ($month) => (int) ($salesByMonth[$month] ?? 0))
                ->values(),
        ]);
    }
}
