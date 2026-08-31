<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashAccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomepageBannerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromoCampaignController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TvVoucherTransactionController;
use App\Http\Controllers\UserManagementController;

use App\Models\HomepageBanner;
use App\Models\Product;
use App\Models\PromoCampaign;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| WEBSITE PELANGGAN - PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function (Request $request) {

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
    | HOMEPAGE BANNER
    |--------------------------------------------------------------------------
    */

    $homepageBanners = HomepageBanner::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | KATEGORI PRODUK
    |--------------------------------------------------------------------------
    |
    | Urutan kategori utama:
    | 1. Receiver
    | 2. TV
    | 3. Kabel
    | 4. RCA
    | 5. Speaker
    |
    | Kategori baru tetap muncul otomatis setelah kategori utama.
    |
    */

    $categories = Product::query()
        ->whereNotNull('category')
        ->where('category', '!=', '')
        ->select('category')
        ->distinct()
        ->pluck('category');


    $categoryPriority = [
        'Receiver' => 1,
        'TV' => 2,
        'Kabel' => 3,
        'RCA' => 4,
        'Speaker' => 5,
    ];


    $categories = $categories
        ->sortBy(function ($category) use ($categoryPriority) {

            if (
                isset(
                    $categoryPriority[
                        $category
                    ]
                )
            ) {
                return sprintf(
                    '%03d-%s',
                    $categoryPriority[
                        $category
                    ],
                    $category
                );
            }

            return
                '999-'
                . mb_strtolower(
                    $category,
                    'UTF-8'
                );
        })
        ->values();


    /*
    |--------------------------------------------------------------------------
    | PRODUK
    |--------------------------------------------------------------------------
    */

    $productQuery = Product::query();

    if ($search !== '') {
        $productQuery->where(
            'product_name',
            'like',
            '%' . $search . '%'
        );
    }

    if ($category !== '') {
        $productQuery->where(
            'category',
            $category
        );
    }

    $products = $productQuery
        ->orderBy('product_name')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | PROMO CAMPAIGN AKTIF
    |--------------------------------------------------------------------------
    */

    $activePromoCampaign = PromoCampaign::query()
        ->with([
            'products' => function ($query) {
                $query->orderBy('product_name');
            },
        ])
        ->where('is_active', true)
        ->whereDate(
            'start_date',
            '<=',
            now()->toDateString()
        )
        ->whereDate(
            'end_date',
            '>=',
            now()->toDateString()
        )
        ->orderByDesc('start_date')
        ->orderByDesc('id')
        ->first();

    $campaignPromoProducts = collect();

    if ($activePromoCampaign) {
        $campaignPromoProducts =
            $activePromoCampaign
                ->products
                ->keyBy('id');
    }


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN HOMEPAGE
    |--------------------------------------------------------------------------
    */

    return view(
        'store.index',
        compact(
            'products',
            'categories',
            'search',
            'category',
            'homepageBanners',
            'activePromoCampaign',
            'campaignPromoProducts'
        )
    );

})->name('store.index');


/*
|--------------------------------------------------------------------------
| DETAIL PRODUK PUBLIK
|--------------------------------------------------------------------------
*/

Route::get(
    '/produtu/{product}',
    function (Product $product) {

        $activePromoCampaign = PromoCampaign::query()
            ->where('is_active', true)
            ->whereDate(
                'start_date',
                '<=',
                now()->toDateString()
            )
            ->whereDate(
                'end_date',
                '>=',
                now()->toDateString()
            )
            ->whereHas(
                'products',
                function ($query) use ($product) {
                    $query->where(
                        'products.id',
                        $product->id
                    );
                }
            )
            ->with([
                'products' => function ($query) use ($product) {
                    $query->where(
                        'products.id',
                        $product->id
                    );
                },
            ])
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->first();

        $campaignPromoProduct = null;

        if ($activePromoCampaign) {
            $campaignPromoProduct =
                $activePromoCampaign
                    ->products
                    ->first();
        }

        return view(
            'store.show',
            compact(
                'product',
                'activePromoCampaign',
                'campaignPromoProduct'
            )
        );

    }
)->name('store.product.show');


/*
|--------------------------------------------------------------------------
| LOGIN, OTP, DAN LUPA PASSWORD
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


    Route::get('/forgot-password', [
        AuthController::class,
        'showForgotPassword',
    ])->name('password.request');

    Route::post('/forgot-password', [
        AuthController::class,
        'sendResetOtp',
    ])->name('password.email');

    Route::get('/forgot-password/verify-otp', [
        AuthController::class,
        'showResetOtpForm',
    ])->name('password.otp.form');

    Route::post('/forgot-password/verify-otp', [
        AuthController::class,
        'verifyResetOtp',
    ])->name('password.otp.verify');

    Route::get('/reset-password', [
        AuthController::class,
        'showResetPasswordForm',
    ])->name('password.reset.form');

    Route::post('/reset-password', [
        AuthController::class,
        'resetPassword',
    ])->name('password.update');
});


/*
|--------------------------------------------------------------------------
| AREA LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'idle.timeout',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        AuthController::class,
        'logout',
    ])->name('logout');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | HOMEPAGE BANNER
    |--------------------------------------------------------------------------
    */

    Route::get('/homepage-banners', [
        HomepageBannerController::class,
        'index',
    ])
        ->name('homepage-banners.index');


    Route::post('/homepage-banners', [
        HomepageBannerController::class,
        'store',
    ])
        ->name('homepage-banners.store');


    Route::put('/homepage-banners/{homepageBanner}', [
        HomepageBannerController::class,
        'update',
    ])
        ->name('homepage-banners.update');


    Route::patch('/homepage-banners/{homepageBanner}/toggle', [
        HomepageBannerController::class,
        'toggle',
    ])
        ->name('homepage-banners.toggle');


    Route::delete('/homepage-banners/{homepageBanner}', [
        HomepageBannerController::class,
        'destroy',
    ])
        ->name('homepage-banners.destroy');


    /*
    |--------------------------------------------------------------------------
    | KAS ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/cash-accounts', [
        CashAccountController::class,
        'index',
    ])
        ->name('cash-accounts.index');


    /*
    |--------------------------------------------------------------------------
    | TAMBAH UANG DI ADMIN
    |--------------------------------------------------------------------------
    */

    Route::post('/cash-accounts/admin/add', [
        CashAccountController::class,
        'addAdmin',
    ])
        ->name('cash-accounts.admin.add');


    /*
    |--------------------------------------------------------------------------
    | TAMBAH UANG LANGSUNG KE BANK
    |--------------------------------------------------------------------------
    */

    Route::post('/cash-accounts/bank/add', [
        CashAccountController::class,
        'addBank',
    ])
        ->name('cash-accounts.bank.add');


    /*
    |--------------------------------------------------------------------------
    | SETOR UANG ADMIN KE BANK
    |--------------------------------------------------------------------------
    */

    Route::post('/cash-accounts/transfer-to-bank', [
        CashAccountController::class,
        'transferToBank',
    ])
        ->name('cash-accounts.transfer-to-bank');


    /*
    |--------------------------------------------------------------------------
    | EDIT SALDO UANG DI ADMIN
    |--------------------------------------------------------------------------
    */

    Route::patch('/cash-accounts/admin', [
        CashAccountController::class,
        'updateAdmin',
    ])
        ->name('cash-accounts.admin.update');


    /*
    |--------------------------------------------------------------------------
    | EDIT SALDO UANG DI BANK
    |--------------------------------------------------------------------------
    */

    Route::patch('/cash-accounts/bank', [
        CashAccountController::class,
        'updateBank',
    ])
        ->name('cash-accounts.bank.update');


    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [
        UserManagementController::class,
        'index',
    ])
        ->middleware('permission:users.view')
        ->name('users.index');

    Route::get('/users/create', [
        UserManagementController::class,
        'create',
    ])
        ->middleware('permission:users.create')
        ->name('users.create');

    Route::post('/users', [
        UserManagementController::class,
        'store',
    ])
        ->middleware('permission:users.create')
        ->name('users.store');

    Route::get('/users/{user}/edit', [
        UserManagementController::class,
        'edit',
    ])
        ->middleware('permission:users.edit')
        ->name('users.edit');

    Route::put('/users/{user}', [
        UserManagementController::class,
        'update',
    ])
        ->middleware('permission:users.edit')
        ->name('users.update');

    Route::patch('/users/{user}', [
        UserManagementController::class,
        'update',
    ])
        ->middleware('permission:users.edit');

    Route::delete('/users/{user}', [
        UserManagementController::class,
        'destroy',
    ])
        ->middleware('permission:users.delete')
        ->name('users.destroy');


    /*
    |--------------------------------------------------------------------------
    | DAFTAR BARANG
    |--------------------------------------------------------------------------
    */

    Route::get('/products', [
        ProductController::class,
        'index',
    ])
        ->middleware('permission:products.view')
        ->name('products.index');

    Route::get('/products/create', [
        ProductController::class,
        'create',
    ])
        ->middleware('permission:products.create')
        ->name('products.create');

    Route::post('/products', [
        ProductController::class,
        'store',
    ])
        ->middleware('permission:products.create')
        ->name('products.store');

    Route::get('/products/{product}/edit', [
        ProductController::class,
        'edit',
    ])
        ->middleware('permission:products.edit')
        ->name('products.edit');

    Route::put('/products/{product}', [
        ProductController::class,
        'update',
    ])
        ->middleware('permission:products.edit')
        ->name('products.update');

    Route::delete('/products/{product}', [
        ProductController::class,
        'destroy',
    ])
        ->middleware('permission:products.delete')
        ->name('products.destroy');


    /*
    |--------------------------------------------------------------------------
    | PROMO CAMPAIGN
    |--------------------------------------------------------------------------
    */

    Route::get('/promo-campaigns', [
        PromoCampaignController::class,
        'index',
    ])
        ->middleware('permission:promo-campaigns.view')
        ->name('promo-campaigns.index');

    Route::get('/promo-campaigns/create', [
        PromoCampaignController::class,
        'create',
    ])
        ->middleware('permission:promo-campaigns.create')
        ->name('promo-campaigns.create');

    Route::post('/promo-campaigns', [
        PromoCampaignController::class,
        'store',
    ])
        ->middleware('permission:promo-campaigns.create')
        ->name('promo-campaigns.store');

    Route::get('/promo-campaigns/{promoCampaign}/edit', [
        PromoCampaignController::class,
        'edit',
    ])
        ->middleware('permission:promo-campaigns.edit')
        ->name('promo-campaigns.edit');

    Route::put('/promo-campaigns/{promoCampaign}', [
        PromoCampaignController::class,
        'update',
    ])
        ->middleware('permission:promo-campaigns.edit')
        ->name('promo-campaigns.update');

    Route::delete('/promo-campaigns/{promoCampaign}', [
        PromoCampaignController::class,
        'destroy',
    ])
        ->middleware('permission:promo-campaigns.delete')
        ->name('promo-campaigns.destroy');


    /*
    |--------------------------------------------------------------------------
    | STOK MASUK
    |--------------------------------------------------------------------------
    */

    Route::get('/stock-ins', [
        StockInController::class,
        'index',
    ])
        ->middleware('permission:stock-ins.view')
        ->name('stock-ins.index');

    Route::get('/stock-ins/create', [
        StockInController::class,
        'create',
    ])
        ->middleware('permission:stock-ins.create')
        ->name('stock-ins.create');

    Route::post('/stock-ins', [
        StockInController::class,
        'store',
    ])
        ->middleware('permission:stock-ins.create')
        ->name('stock-ins.store');

    Route::get('/stock-ins/{stockIn}/edit', [
        StockInController::class,
        'edit',
    ])
        ->middleware('permission:stock-ins.edit')
        ->name('stock-ins.edit');

    Route::put('/stock-ins/{stockIn}', [
        StockInController::class,
        'update',
    ])
        ->middleware('permission:stock-ins.edit')
        ->name('stock-ins.update');

    Route::delete('/stock-ins/{stockIn}', [
        StockInController::class,
        'destroy',
    ])
        ->middleware('permission:stock-ins.delete')
        ->name('stock-ins.destroy');


    /*
    |--------------------------------------------------------------------------
    | STOK KELUAR
    |--------------------------------------------------------------------------
    */

    Route::get('/stock-outs', [
        StockOutController::class,
        'index',
    ])
        ->middleware('permission:stock-outs.view')
        ->name('stock-outs.index');

    Route::get('/stock-outs/create', [
        StockOutController::class,
        'create',
    ])
        ->middleware('permission:stock-outs.create')
        ->name('stock-outs.create');

    Route::post('/stock-outs', [
        StockOutController::class,
        'store',
    ])
        ->middleware('permission:stock-outs.create')
        ->name('stock-outs.store');

    Route::get('/stock-outs/{stockOut}/edit', [
        StockOutController::class,
        'edit',
    ])
        ->middleware('permission:stock-outs.edit')
        ->name('stock-outs.edit');

    Route::put('/stock-outs/{stockOut}', [
        StockOutController::class,
        'update',
    ])
        ->middleware('permission:stock-outs.edit')
        ->name('stock-outs.update');

    Route::patch(
        '/stock-outs/{stockOut}/verify-payment',
        [
            StockOutController::class,
            'verifyCustomerPayment',
        ]
    )
        ->middleware(
            'permission:stock-outs.verify-payment'
        )
        ->name(
            'stock-outs.verify-payment'
        );

    Route::patch(
        '/stock-outs/{stockOut}/confirm-deposit',
        [
            StockOutController::class,
            'confirmDeposit',
        ]
    )
        ->middleware(
            'permission:stock-outs.confirm-deposit'
        )
        ->name(
            'stock-outs.confirm-deposit'
        );

    Route::delete('/stock-outs/{stockOut}', [
        StockOutController::class,
        'destroy',
    ])
        ->middleware('permission:stock-outs.delete')
        ->name('stock-outs.destroy');


    /*
    |--------------------------------------------------------------------------
    | SUPPLIER
    |--------------------------------------------------------------------------
    */

    Route::get('/suppliers', [
        SupplierController::class,
        'index',
    ])
        ->middleware('permission:suppliers.view')
        ->name('suppliers.index');

    Route::get('/suppliers/create', [
        SupplierController::class,
        'create',
    ])
        ->middleware('permission:suppliers.create')
        ->name('suppliers.create');

    Route::post('/suppliers', [
        SupplierController::class,
        'store',
    ])
        ->middleware('permission:suppliers.create')
        ->name('suppliers.store');

    Route::get('/suppliers/{supplier}/edit', [
        SupplierController::class,
        'edit',
    ])
        ->middleware('permission:suppliers.edit')
        ->name('suppliers.edit');

    Route::put('/suppliers/{supplier}', [
        SupplierController::class,
        'update',
    ])
        ->middleware('permission:suppliers.edit')
        ->name('suppliers.update');

    Route::delete('/suppliers/{supplier}', [
        SupplierController::class,
        'destroy',
    ])
        ->middleware('permission:suppliers.delete')
        ->name('suppliers.destroy');


    /*
    |--------------------------------------------------------------------------
    | PELANGGAN
    |--------------------------------------------------------------------------
    */

    Route::get('/customers', [
        CustomerController::class,
        'index',
    ])
        ->middleware('permission:customers.view')
        ->name('customers.index');

    Route::get('/customers/create', [
        CustomerController::class,
        'create',
    ])
        ->middleware('permission:customers.create')
        ->name('customers.create');

    Route::post('/customers', [
        CustomerController::class,
        'store',
    ])
        ->middleware('permission:customers.create')
        ->name('customers.store');

    Route::get('/customers/{customer}/edit', [
        CustomerController::class,
        'edit',
    ])
        ->middleware('permission:customers.edit')
        ->name('customers.edit');

    Route::put('/customers/{customer}', [
        CustomerController::class,
        'update',
    ])
        ->middleware('permission:customers.edit')
        ->name('customers.update');

    Route::delete('/customers/{customer}', [
        CustomerController::class,
        'destroy',
    ])
        ->middleware('permission:customers.delete')
        ->name('customers.destroy');


    /*
    |--------------------------------------------------------------------------
    | TV VOUCHER
    |--------------------------------------------------------------------------
    */

    Route::get('/tv-vouchers', [
        TvVoucherTransactionController::class,
        'index',
    ])
        ->middleware('permission:tv-vouchers.view')
        ->name('tv-vouchers.index');


    /*
    |--------------------------------------------------------------------------
    | LAPORAN TV VOUCHER
    |--------------------------------------------------------------------------
    */

    Route::get('/tv-vouchers/report', [
        TvVoucherTransactionController::class,
        'report',
    ])
        ->middleware('permission:tv-vouchers.view')
        ->name('tv-vouchers.report');


    Route::get('/tv-vouchers/create', [
        TvVoucherTransactionController::class,
        'create',
    ])
        ->middleware('permission:tv-vouchers.create')
        ->name('tv-vouchers.create');


    Route::post('/tv-vouchers', [
        TvVoucherTransactionController::class,
        'store',
    ])
        ->middleware('permission:tv-vouchers.create')
        ->name('tv-vouchers.store');


    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI PEMBAYARAN CUSTOMER - TV VOUCHER
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/tv-vouchers/{tvVoucher}/verify-payment',
        [
            TvVoucherTransactionController::class,
            'verifyCustomerPayment',
        ]
    )
        ->middleware(
            'permission:tv-vouchers.verify-payment'
        )
        ->name(
            'tv-vouchers.verify-payment'
        );


    /*
    |--------------------------------------------------------------------------
    | ATUR METODE CASH / BANK - TRANSAKSI LAMA
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/tv-vouchers/{tvVoucher}/payment-method',
        [
            TvVoucherTransactionController::class,
            'setPaymentMethod',
        ]
    )
        ->middleware(
            'permission:tv-vouchers.confirm-deposit'
        )
        ->name(
            'tv-vouchers.payment-method'
        );


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI SETORAN CASH - TV VOUCHER
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/tv-vouchers/{tvVoucher}/confirm-deposit',
        [
            TvVoucherTransactionController::class,
            'confirmDeposit',
        ]
    )
        ->middleware(
            'permission:tv-vouchers.confirm-deposit'
        )
        ->name(
            'tv-vouchers.confirm-deposit'
        );


    /*
    |--------------------------------------------------------------------------
    | ROUTE DINAMIS TV VOUCHER
    |--------------------------------------------------------------------------
    */

    Route::get('/tv-vouchers/{tvVoucher}', [
        TvVoucherTransactionController::class,
        'show',
    ])
        ->middleware('permission:tv-vouchers.view')
        ->name('tv-vouchers.show');


    Route::get('/tv-vouchers/{tvVoucher}/edit', [
        TvVoucherTransactionController::class,
        'edit',
    ])
        ->middleware('permission:tv-vouchers.edit')
        ->name('tv-vouchers.edit');


    Route::put('/tv-vouchers/{tvVoucher}', [
        TvVoucherTransactionController::class,
        'update',
    ])
        ->middleware('permission:tv-vouchers.edit')
        ->name('tv-vouchers.update');


    Route::patch('/tv-vouchers/{tvVoucher}', [
        TvVoucherTransactionController::class,
        'update',
    ])
        ->middleware('permission:tv-vouchers.edit');


    Route::delete('/tv-vouchers/{tvVoucher}', [
        TvVoucherTransactionController::class,
        'destroy',
    ])
        ->middleware('permission:tv-vouchers.delete')
        ->name('tv-vouchers.destroy');


    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */

    Route::get('/reports', [
        ReportController::class,
        'index',
    ])
        ->middleware('permission:reports.view')
        ->name('reports.index');


    Route::get('/reports/export-excel', [
        ReportController::class,
        'exportExcel',
    ])
        ->middleware('permission:reports.view')
        ->name('reports.export-excel');
});
