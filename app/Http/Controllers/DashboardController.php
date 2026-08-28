<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $totalStockIn = StockIn::sum('quantity');
        $totalStockOut = StockOut::sum('quantity');

        $chartLabels = [];
        $chartStockIn = [];
        $chartStockOut = [];

        /*
         * Mengambil data transaksi selama 7 hari terakhir.
         */
        for ($hari = 6; $hari >= 0; $hari--) {
            $tanggal = Carbon::today()->subDays($hari);

            $chartLabels[] = $tanggal->format('d-m-Y');

            $chartStockIn[] = StockIn::whereDate(
                'transaction_date',
                $tanggal->format('Y-m-d')
            )->sum('quantity');

            $chartStockOut[] = StockOut::whereDate(
                'transaction_date',
                $tanggal->format('Y-m-d')
            )->sum('quantity');
        }

        return view('dashboard', compact(
            'totalProducts',
            'totalStock',
            'totalStockIn',
            'totalStockOut',
            'chartLabels',
            'chartStockIn',
            'chartStockOut'
        ));
    }
}