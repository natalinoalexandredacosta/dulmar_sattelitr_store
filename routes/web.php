<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TvVoucherTransactionController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEBSITE PELANGGAN - PUBLIC
|--------------------------------------------------------------------------
|
| Halaman ini dapat dibuka tanpa login.
| Customer dapat:
| - Melihat produk
| - Mencari produk
| - Filter berdasarkan kategori
| - Melihat harga jual
| - Melihat foto
| - Melihat status stok
| - Order melalui WhatsApp
|
*/

Route::get('/', function (Request $request) {

    /*
    |--------------------------------------------------------------------------
    | Ambil parameter pencarian dan kategori
    |--------------------------------------------------------------------------
    */

    $search = trim(
        (string) $request->query(
            'search',
            ''
        )
    );

    $category = trim(
        (string) $request->query(
            'category',
            ''
        )
    );

    /*
    |--------------------------------------------------------------------------
    | Ambil daftar kategori untuk dropdown
    |--------------------------------------------------------------------------
    */

    $categories = Product::query()
        ->whereNotNull('category')
        ->where('category', '!=', '')
        ->select('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    /*
    |--------------------------------------------------------------------------
    | Query produk publik
    |--------------------------------------------------------------------------
    */

    $productQuery = Product::query();

    /*
    |--------------------------------------------------------------------------
    | Pencarian nama produk
    |--------------------------------------------------------------------------
    */

    if ($search !== '') {
        $productQuery->where(
            'product_name',
            'like',
            '%' . $search . '%'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Filter kategori
    |--------------------------------------------------------------------------
    */

    if ($category !== '') {
        $productQuery->where(
            'category',
            $category
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil produk
    |--------------------------------------------------------------------------
    */

    $products = $productQuery
        ->orderBy('product_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Kirim data ke halaman website pelanggan
    |--------------------------------------------------------------------------
    */

    return view(
        'store.index',
        compact(
            'products',
            'categories',
            'search',
            'category'
        )
    );

})->name('store.index');

/*
|--------------------------------------------------------------------------
| Login dan Verifikasi OTP
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [
        AuthController::class,
        'showLogin',
    ])->name('login');

    Route::post('/login', [
        AuthController::class,
        'login',
    ])->name('login.process');

    Route::get('/verify-otp', [
        AuthController::class,
        'showOtpForm',
    ])->name('otp.form');

    Route::post('/verify-otp', [
        AuthController::class,
        'verifyOtp',
    ])->name('otp.verify');
});

/*
|--------------------------------------------------------------------------
| Halaman yang hanya dapat diakses setelah login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Admin
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        AuthController::class,
        'logout',
    ])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Daftar Barang
    |--------------------------------------------------------------------------
    */

    Route::get('/products', [
        ProductController::class,
        'index',
    ])->name('products.index');

    Route::get('/products/create', [
        ProductController::class,
        'create',
    ])->name('products.create');

    Route::post('/products', [
        ProductController::class,
        'store',
    ])->name('products.store');

    Route::get('/products/{product}/edit', [
        ProductController::class,
        'edit',
    ])->name('products.edit');

    Route::put('/products/{product}', [
        ProductController::class,
        'update',
    ])->name('products.update');

    Route::delete('/products/{product}', [
        ProductController::class,
        'destroy',
    ])->name('products.destroy');

    /*
    |--------------------------------------------------------------------------
    | Stok Masuk
    |--------------------------------------------------------------------------
    */

    Route::get('/stock-ins', [
        StockInController::class,
        'index',
    ])->name('stock-ins.index');

    Route::get('/stock-ins/create', [
        StockInController::class,
        'create',
    ])->name('stock-ins.create');

    Route::post('/stock-ins', [
        StockInController::class,
        'store',
    ])->name('stock-ins.store');

    Route::get('/stock-ins/{stockIn}/edit', [
        StockInController::class,
        'edit',
    ])->name('stock-ins.edit');

    Route::put('/stock-ins/{stockIn}', [
        StockInController::class,
        'update',
    ])->name('stock-ins.update');

    Route::delete('/stock-ins/{stockIn}', [
        StockInController::class,
        'destroy',
    ])->name('stock-ins.destroy');

    /*
    |--------------------------------------------------------------------------
    | Stok Keluar
    |--------------------------------------------------------------------------
    */

    Route::get('/stock-outs', [
        StockOutController::class,
        'index',
    ])->name('stock-outs.index');

    Route::get('/stock-outs/create', [
        StockOutController::class,
        'create',
    ])->name('stock-outs.create');

    Route::post('/stock-outs', [
        StockOutController::class,
        'store',
    ])->name('stock-outs.store');

    Route::get('/stock-outs/{stockOut}/edit', [
        StockOutController::class,
        'edit',
    ])->name('stock-outs.edit');

    Route::put('/stock-outs/{stockOut}', [
        StockOutController::class,
        'update',
    ])->name('stock-outs.update');

    Route::delete('/stock-outs/{stockOut}', [
        StockOutController::class,
        'destroy',
    ])->name('stock-outs.destroy');

    /*
    |--------------------------------------------------------------------------
    | Supplier Barang
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'suppliers',
        SupplierController::class
    )->except([
        'show',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Pelanggan
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'customers',
        CustomerController::class
    )->except([
        'show',
    ]);

    /*
    |--------------------------------------------------------------------------
    | TV Voucher
    |--------------------------------------------------------------------------
    */

    /*
     * Route laporan harus berada sebelum resource.
     */
    Route::get(
        '/tv-vouchers/report',
        [
            TvVoucherTransactionController::class,
            'report',
        ]
    )->name('tv-vouchers.report');

    /*
     * Verifikasi pembayaran customer.
     */
    Route::patch(
        '/tv-vouchers/{tvVoucher}/verify-payment',
        [
            TvVoucherTransactionController::class,
            'verifyCustomerPayment',
        ]
    )->name('tv-vouchers.verify-payment');

    /*
     * Konfirmasi setoran petugas.
     */
    Route::patch(
        '/tv-vouchers/{tvVoucher}/confirm-deposit',
        [
            TvVoucherTransactionController::class,
            'confirmDeposit',
        ]
    )->name('tv-vouchers.confirm-deposit');

    /*
     * CRUD TV Voucher.
     */
    Route::resource(
        'tv-vouchers',
        TvVoucherTransactionController::class
    )->parameters([
        'tv-vouchers' => 'tvVoucher',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Laporan Inventaris
    |--------------------------------------------------------------------------
    */

    Route::get('/reports', [
        ReportController::class,
        'index',
    ])->name('reports.index');

    Route::get('/reports/export-excel', [
        ReportController::class,
        'exportExcel',
    ])->name('reports.export-excel');
});