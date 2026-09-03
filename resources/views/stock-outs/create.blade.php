<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Stok Keluar - Dulmar Satellite Store</title>

    <link
        rel="icon"
        type="image/jpeg"
        href="{{ asset('images/logo-dulmar.jpg') }}"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            overflow-x: hidden;
        }

        body.menu-open {
            overflow: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /*
        |--------------------------------------------------------------------------
        | SIDEBAR
        |--------------------------------------------------------------------------
        */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 900;

            width: 245px;
            height: 100vh;

            display: flex;
            flex-direction: column;

            padding: 30px 25px;

            background: #1f2b3a;
            color: white;

            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar h1 {
            margin: 0 0 35px;
            font-size: 27px;
        }

        .sidebar-menu {
            flex: 1;
        }

        .sidebar-menu a {
            display: block;

            margin-bottom: 10px;
            padding: 12px 10px;

            border-radius: 6px;

            color: white;

            font-size: 16px;
            text-decoration: none;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar-menu a.active {
            padding-left: 14px;

            border-left: 4px solid #60a5fa;

            background: rgba(37, 99, 235, 0.3);
            color: #bfdbfe;

            font-weight: bold;
        }

        .report-submenu {
            padding-left: 25px !important;
            font-size: 15px !important;
        }

        .logout-form {
            margin-top: 20px;
        }

        .button-logout {
            width: 100%;

            padding: 13px 15px;

            border: none;
            border-radius: 7px;

            background: #dc2626;
            color: white;

            font-size: 16px;

            cursor: pointer;
        }

        .button-logout:hover {
            background: #b91c1c;
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN
        |--------------------------------------------------------------------------
        */

        .main-content {
            width: calc(100% - 245px);
            min-height: 100vh;

            margin-left: 245px;

            padding: 45px 32px;
        }

        .page-header {
            margin-bottom: 35px;
        }

        .page-header h2 {
            margin: 0 0 15px;
            font-size: 36px;
        }

        .page-header p {
            margin: 0;

            color: #4b5563;

            font-size: 18px;
        }

        /*
        |--------------------------------------------------------------------------
        | FORM
        |--------------------------------------------------------------------------
        */

        .form-card {
            width: 100%;
            max-width: 850px;

            padding: 35px;

            border-radius: 10px;

            background: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.08);
        }

        .alert-error {
            margin-bottom: 25px;

            padding: 15px 20px;

            border: 1px solid #fca5a5;
            border-radius: 6px;

            background: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .stock-warning {
            margin-bottom: 25px;

            padding: 14px 18px;

            border: 1px solid #fcd34d;
            border-radius: 6px;

            background: #fef3c7;
            color: #92400e;

            font-size: 15px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;

            margin-bottom: 10px;

            font-size: 17px;
            font-weight: bold;
        }

        .required {
            color: #dc2626;
        }

        .form-control {
            width: 100%;

            padding: 13px 15px;

            border: 1px solid #d1d5db;
            border-radius: 6px;

            background: white;

            font-size: 16px;
        }

        .form-control:focus {
            border-color: #dc2626;
            outline: none;

            box-shadow:
                0 0 0 3px
                rgba(220, 38, 38, 0.1);
        }

        textarea.form-control {
            min-height: 110px;
            resize: vertical;
        }

        .help-text {
            display: block;

            margin-top: 7px;

            color: #6b7280;

            font-size: 14px;
            line-height: 1.4;
        }

        /*
        |--------------------------------------------------------------------------
        | CALCULATION
        |--------------------------------------------------------------------------
        */

        .calculation-card {
            margin-bottom: 25px;

            padding: 25px;

            border: 1px solid #bfdbfe;
            border-radius: 10px;

            background: #eff6ff;
        }

        .calculation-card h3 {
            margin: 0 0 20px;

            color: #1e3a8a;

            font-size: 20px;
        }

        .calculation-grid {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 15px;
        }

        .calculation-item {
            padding: 15px;

            border-radius: 7px;

            background: white;
        }

        .calculation-item span {
            display: block;

            margin-bottom: 7px;

            color: #6b7280;

            font-size: 14px;
        }

        .calculation-item strong {
            color: #1f2937;
            font-size: 20px;
        }

        .subtotal-item {
            border-left: 5px solid #2563eb;
        }

        .capital-item {
            border-left: 5px solid #f59e0b;
        }

        .profit-item {
            grid-column: 1 / -1;

            border-left: 5px solid #16a34a;
        }

        .profit-item strong {
            color: #16a34a;
            font-size: 24px;
        }


        .discount-card {
            border-color: #c4b5fd;
            background: #f5f3ff;
        }

        .discount-card h3 {
            color: #5b21b6;
        }

        .discount-item {
            border-left: 5px solid #7c3aed;
        }

        .discount-item strong {
            color: #7c3aed;
            font-size: 22px;
        }

        .after-discount-item {
            grid-column: 1 / -1;
            border-left: 5px solid #2563eb;
        }

        .after-discount-item strong {
            color: #1d4ed8;
            font-size: 24px;
        }

        .net-deposit-item {
            grid-column: 1 / -1;
            border-left: 5px solid #ea580c;
        }

        .net-deposit-item strong {
            color: #c2410c;
            font-size: 24px;
        }

        /*
        |--------------------------------------------------------------------------
        | BUTTON
        |--------------------------------------------------------------------------
        */

        .form-actions {
            display: flex;
            flex-wrap: wrap;

            gap: 12px;

            margin-top: 30px;
        }

        .button {
            display: inline-block;

            padding: 13px 22px;

            border: none;
            border-radius: 6px;

            font-size: 16px;
            text-decoration: none;

            cursor: pointer;
        }

        .button-save {
            background: #dc2626;
            color: white;
        }

        .button-save:hover {
            background: #b91c1c;
        }

        .button-cancel {
            background: #6b7280;
            color: white;
        }

        .button-cancel:hover {
            background: #4b5563;
        }

        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        .sidebar-toggle,
        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 700px) {

            .sidebar-toggle {
                position: fixed;

                top: 15px;
                left: 15px;

                z-index: 1200;

                display: flex;

                width: 46px;
                height: 46px;

                align-items: center;
                justify-content: center;

                border: none;
                border-radius: 8px;

                background: #1f2b3a;
                color: white;

                font-size: 25px;

                cursor: pointer;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;

                z-index: 1000;

                display: block;

                visibility: hidden;

                background:
                    rgba(0, 0, 0, 0.5);

                opacity: 0;
            }

            .sidebar-overlay.overlay-open {
                visibility: visible;
                opacity: 1;
            }

            .sidebar {
                z-index: 1100;

                width: min(82vw, 285px);

                padding:
                    82px
                    25px
                    30px;

                transform: translateX(-105%);

                transition:
                    transform 0.25s ease;
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .main-content {
                width: 100%;

                margin-left: 0;

                padding:
                    85px
                    14px
                    30px;
            }

            .page-header h2 {
                font-size: 30px;
            }

            .form-card {
                padding: 25px 18px;
            }

            .calculation-grid {
                grid-template-columns: 1fr;
            }

            .profit-item {
                grid-column: auto;
            }

            .button {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <button
        type="button"
        id="sidebarToggle"
        class="sidebar-toggle"
        aria-label="Buka menu"
    >
        ☰
    </button>

    <div
        id="sidebarOverlay"
        class="sidebar-overlay"
    ></div>


    <div class="container">

        {{-- ==========================================================
             SIDEBAR
        =========================================================== --}}

        <aside
            class="sidebar"
            id="sidebar"
        >

            <h1>
                Dulmar Satellite Store
            </h1>


            <nav class="sidebar-menu">

                @can('dashboard.view')

                    <a
                        href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    >
                        Dashboard
                    </a>

                @endcan


                @can('products.view')

                    <a
                        href="{{ route('products.index') }}"
                        class="{{ request()->routeIs('products.*') ? 'active' : '' }}"
                    >
                        Daftar Barang
                    </a>

                @endcan


                @can('promo-campaigns.view')

                    <a
                        href="{{ route('promo-campaigns.index') }}"
                        class="{{ request()->routeIs('promo-campaigns.*') ? 'active' : '' }}"
                    >
                        Promo Campaign
                    </a>

                @endcan


                @can('stock-ins.view')

                    <a
                        href="{{ route('stock-ins.index') }}"
                        class="{{ request()->routeIs('stock-ins.*') ? 'active' : '' }}"
                    >
                        Stok Masuk
                    </a>

                @endcan


                @can('stock-outs.view')

                    <a
                        href="{{ route('stock-outs.index') }}"
                        class="{{ request()->routeIs('stock-outs.*') ? 'active' : '' }}"
                    >
                        Stok Keluar
                    </a>

                @endcan


                @can('tv-vouchers.view')

                    <a
                        href="{{ route('tv-vouchers.index') }}"
                        class="{{ request()->routeIs('tv-vouchers.index') ? 'active' : '' }}"
                    >
                        TV Voucher
                    </a>

                    <a
                        href="{{ route('tv-vouchers.report') }}"
                        class="report-submenu {{ request()->routeIs('tv-vouchers.report') ? 'active' : '' }}"
                    >
                        ↳ Laporan TV Voucher
                    </a>

                @endcan


                @can('cash-admin.view')

                    <a
                        href="{{ route('cash-accounts.index') }}"
                        class="{{ request()->routeIs('cash-accounts.*') ? 'active' : '' }}"
                    >
                        Kas Admin
                    </a>

                @endcan


                @can('suppliers.view')

                    <a
                        href="{{ route('suppliers.index') }}"
                        class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
                    >
                        Supplier Barang
                    </a>

                @endcan


                @can('customers.view')

                    <a
                        href="{{ route('customers.index') }}"
                        class="{{ request()->routeIs('customers.*') ? 'active' : '' }}"
                    >
                        Pelanggan
                    </a>

                @endcan


                @can('reports.view')

                    <a
                        href="{{ route('reports.index') }}"
                        class="{{ request()->routeIs('reports.*') ? 'active' : '' }}"
                    >
                        Laporan
                    </a>

                @endcan


                @can('users.view')

                    <a
                        href="{{ route('users.index') }}"
                        class="{{ request()->routeIs('users.*') ? 'active' : '' }}"
                    >
                        User Management
                    </a>

                @endcan

            </nav>


            <form
                action="{{ route('logout') }}"
                method="POST"
                class="logout-form"
                onsubmit="return confirm('Apakah Anda yakin ingin keluar?')"
            >

                @csrf

                <button
                    type="submit"
                    class="button-logout"
                >
                    Keluar
                </button>

            </form>

        </aside>


        {{-- ==========================================================
             CONTENT
        =========================================================== --}}

        <main class="main-content">

            <div class="page-header">

                <h2>
                    Tambah Stok Keluar
                </h2>

                <p>
                    Catat transaksi penjualan produk kepada pelanggan.
                </p>

            </div>


            <div class="form-card">

                @if ($errors->any())

                    <div class="alert-error">

                        <ul>

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <div class="stock-warning">
                    Jumlah stok keluar tidak boleh melebihi stok yang tersedia.
                </div>


                <form
                    action="{{ route('stock-outs.store') }}"
                    method="POST"
                >

                    @csrf


                    {{-- ======================================================
                         PRODUK
                    ======================================================= --}}

                    <div class="form-group">

                        <label for="product_id">
                            Produk
                            <span class="required">*</span>
                        </label>

                        <select
                            id="product_id"
                            name="product_id"
                            class="form-control"
                            required
                        >

                            <option
                                value=""
                                data-purchase-price="0"
                                data-selling-price="0"
                                data-stock="0"
                            >
                                -- Pilih Produk --
                            </option>


                            @foreach ($products as $product)

                                <option
                                    value="{{ $product->id }}"

                                    data-purchase-price="{{ $product->purchase_price }}"

                                    data-selling-price="{{ $product->selling_price }}"

                                    data-stock="{{ $product->stock }}"

                                    {{ old('product_id') == $product->id ? 'selected' : '' }}
                                >

                                    {{ $product->product_name }}

                                    - Stok:
                                    {{ $product->stock }}

                                    - Harga jual:
                                    ${{ number_format(
                                        $product->selling_price,
                                        2
                                    ) }}

                                </option>

                            @endforeach

                        </select>


                        <span class="help-text">
                            Pilih produk yang akan dijual.
                        </span>

                    </div>


                    {{-- ======================================================
                         PELANGGAN MANUAL
                    ======================================================= --}}

                    <div class="form-group">

                        <label for="customer_name">
                            Nama Pelanggan
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"

                            id="customer_name"

                            name="customer_name"

                            class="form-control"

                            value="{{ old('customer_name') }}"

                            placeholder="Masukkan nama pelanggan"

                            maxlength="255"

                            required
                        >

                        <span class="help-text">
                            Isi nama pelanggan secara manual.
                            Jika pelanggan belum ada, sistem akan membuat data pelanggan baru otomatis.
                        </span>

                    </div>


                    <div class="form-group">

                        <label for="customer_phone">
                            Nomor Telepon
                        </label>

                        <input
                            type="text"

                            id="customer_phone"

                            name="customer_phone"

                            class="form-control"

                            value="{{ old('customer_phone') }}"

                            placeholder="77234567"

                            maxlength="50"
                        >

                        <span class="help-text">
                            Nomor telepon pelanggan bersifat opsional.
                        </span>

                    </div>


                    <div class="form-group">

                        <label for="customer_address">
                            Alamat
                        </label>

                        <input
                            type="text"

                            id="customer_address"

                            name="customer_address"

                            class="form-control"

                            value="{{ old('customer_address') }}"

                            placeholder="Comoro, Dili"

                            maxlength="255"
                        >

                        <span class="help-text">
                            Isi alamat pelanggan jika tersedia.
                        </span>

                    </div>


                    {{-- customer_id tidak lagi dipilih --}}
                    <input
                        type="hidden"
                        name="customer_id"
                        value=""
                    >


                    {{-- ======================================================
                         QUANTITY
                    ======================================================= --}}

                    <div class="form-group">

                        <label for="quantity">
                            Jumlah yang Dibeli
                            <span class="required">*</span>
                        </label>

                        <input
                            type="number"

                            id="quantity"

                            name="quantity"

                            class="form-control"

                            value="{{ old('quantity', 1) }}"

                            min="1"

                            required
                        >


                        <span
                            class="help-text"
                            id="stockInformation"
                        >
                            Pilih produk untuk melihat stok tersedia.
                        </span>

                    </div>


                    {{-- ======================================================
                         PERHITUNGAN DASAR
                    ======================================================= --}}

                    <div class="calculation-card">

                        <h3>
                            Perhitungan Penjualan
                        </h3>

                        <div class="calculation-grid">

                            <div class="calculation-item">

                                <span>
                                    Harga Beli per Unit
                                </span>

                                <strong id="hargaBeli">
                                    $0.00
                                </strong>

                            </div>


                            <div class="calculation-item">

                                <span>
                                    Harga Jual Normal per Unit
                                </span>

                                <strong id="hargaJual">
                                    $0.00
                                </strong>

                            </div>


                            <div class="calculation-item subtotal-item">

                                <span>
                                    Total Harga Normal
                                </span>

                                <strong id="subtotalNormal">
                                    $0.00
                                </strong>

                            </div>


                            <div class="calculation-item capital-item">

                                <span>
                                    Total Modal
                                </span>

                                <strong id="totalModal">
                                    $0.00
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- ======================================================
                         DISKON PELANGGAN
                    ======================================================= --}}

                    <div class="calculation-card discount-card">

                        <h3>
                            Diskon Pelanggan
                        </h3>

                        <div class="calculation-grid">

                            <div class="form-group" style="margin-bottom:0;">

                                <label for="customer_discount_amount">
                                    Diskon Pelanggan
                                </label>

                                <input
                                    type="number"
                                    id="customer_discount_amount"
                                    name="customer_discount_amount"
                                    class="form-control"
                                    value="{{ old('customer_discount_amount', 0) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                >

                                <span class="help-text">
                                    Masukkan nominal diskon yang diberikan kepada pelanggan.
                                </span>

                            </div>


                            <div class="form-group" style="margin-bottom:0;">

                                <label for="customer_discount_note">
                                    Keterangan Diskon
                                </label>

                                <input
                                    type="text"
                                    id="customer_discount_note"
                                    name="customer_discount_note"
                                    class="form-control"
                                    value="{{ old('customer_discount_note') }}"
                                    maxlength="255"
                                    placeholder="Diskon pelanggan / Promo khusus"
                                >

                                <span class="help-text">
                                    Opsional. Isi alasan atau jenis diskon jika diperlukan.
                                </span>

                            </div>


                            <div class="calculation-item discount-item">

                                <span>
                                    Total Diskon Pelanggan
                                </span>

                                <strong id="customerDiscountPreview">
                                    $0.00
                                </strong>

                            </div>


                            <div class="calculation-item after-discount-item">

                                <span>
                        
                                </span>

                                <strong id="totalAfterDiscount">
                                    $0.00
                                </strong>

                                <span class="help-text" style="margin-top:8px;">
                    
                                </span>

                            </div>


                            <div class="calculation-item profit-item">

                                <span>
                        
                                </span>

                                <strong id="totalKeuntungan">
                                    $0.00
                                </strong>

                                <span class="help-text" style="margin-top:8px;">
                        
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- ======================================================
                         POTONGAN PETUGAS / SETORAN BERSIH
                    ======================================================= --}}

                    <div class="calculation-card" style="background:#fff7ed; border-color:#fdba74;">

                        <h3 style="color:#9a3412;">
    
                        </h3>

                        <div class="calculation-grid">

                            <div class="form-group" style="margin-bottom:0;">

                                <label for="deduction_amount">
                                    Biaya / Potongan Petugas
                                </label>

                                <input
                                    type="number"
                                    id="deduction_amount"
                                    name="deduction_amount"
                                    class="form-control"
                                    value="{{ old('deduction_amount', 0) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                >

                                <span class="help-text">
                    
                                </span>

                            </div>


                            <div class="form-group" style="margin-bottom:0;">

                                <label for="deduction_note">
                            
                                </label>

                                <input
                                    type="text"
                                    id="deduction_note"
                                    name="deduction_note"
                                    class="form-control"
                                    value="{{ old('deduction_note') }}"
                                    maxlength="255"
                                    placeholder="Bensin / Komisi petugas"
                                >

                                <span class="help-text">
                        
                                </span>

                            </div>


                            <div class="calculation-item net-deposit-item">

                                <span>
                                    Setoran Bersih ke Kas
                                </span>

                                <strong id="netDeposit">
                                    $0.00
                                </strong>

                                <span class="help-text" style="margin-top:8px;">
                        
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- ======================================================
                         TANGGAL
                    ======================================================= --}}

                    <div class="form-group">

                        <label for="transaction_date">
                            Tanggal Transaksi
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"

                            id="transaction_date"

                            name="transaction_date"

                            class="form-control"

                            value="{{ old(
                                'transaction_date',
                                date('Y-m-d')
                            ) }}"

                            required
                        >

                    </div>


                    {{-- ======================================================
                         CATATAN
                    ======================================================= --}}

                    <div class="form-group">

                        <label for="notes">
                            Catatan
                        </label>

                        <textarea
                            id="notes"

                            name="notes"

                            class="form-control"

                            maxlength="1000"

                            placeholder="Catatan tambahan jika ada..."
                        >{{ old('notes') }}</textarea>

                    </div>


                    {{-- ======================================================
                         ACTION
                    ======================================================= --}}

                    <div class="form-actions">

                        <button
                            type="submit"
                            class="button button-save"
                        >
                            Simpan Stok Keluar
                        </button>


                        <a
                            href="{{ route('stock-outs.index') }}"
                            class="button button-cancel"
                        >
                            Batal
                        </a>

                    </div>

                </form>

            </div>

        </main>

    </div>


    <script>

        /*
        |--------------------------------------------------------------------------
        | PRODUCT + DISKON + POTONGAN CALCULATION
        |--------------------------------------------------------------------------
        */

        const productInput =
            document.getElementById(
                'product_id'
            );

        const quantityInput =
            document.getElementById(
                'quantity'
            );

        const stockInformation =
            document.getElementById(
                'stockInformation'
            );

        const customerDiscountInput =
            document.getElementById(
                'customer_discount_amount'
            );

        const deductionInput =
            document.getElementById(
                'deduction_amount'
            );

        const deductionNoteInput =
            document.getElementById(
                'deduction_note'
            );


        function formatUang(nilai) {

            return '$'
                + Number(
                    nilai || 0
                ).toFixed(2);
        }


        function updateQuantityMessage() {

            quantityInput.setCustomValidity(
                ''
            );


            if (
                quantityInput.validity.valueMissing
            ) {

                quantityInput.setCustomValidity(
                    'Jumlah barang wajib diisi.'
                );

            } else if (
                quantityInput.validity.rangeUnderflow
            ) {

                quantityInput.setCustomValidity(
                    'Jumlah barang minimal 1 unit.'
                );

            } else if (
                quantityInput.validity.rangeOverflow
            ) {

                quantityInput.setCustomValidity(
                    'Jumlah barang maksimal '
                    + quantityInput.max
                    + ' unit sesuai stok yang tersedia.'
                );
            }
        }


        function hitungPenjualan() {

            const pilihan =
                productInput.options[
                    productInput.selectedIndex
                ];


            const jumlah =
                Number(
                    quantityInput.value
                    || 0
                );


            const hargaBeli =
                Number(
                    pilihan
                        ?.dataset
                        .purchasePrice
                    || 0
                );


            const hargaJualNormal =
                Number(
                    pilihan
                        ?.dataset
                        .sellingPrice
                    || 0
                );


            const stokTersedia =
                Number(
                    pilihan
                        ?.dataset
                        .stock
                    || 0
                );


            const totalHargaNormal =
                hargaJualNormal
                * jumlah;


            const totalModal =
                hargaBeli
                * jumlah;


            const customerDiscount =
                Number(
                    customerDiscountInput
                        ?.value
                    || 0
                );


            const totalAfterDiscount =
                Math.max(
                    totalHargaNormal
                    - customerDiscount,
                    0
                );


            const totalProfit =
                totalAfterDiscount
                - totalModal;


            const deduction =
                Number(
                    deductionInput
                        ?.value
                    || 0
                );


            const netDeposit =
                Math.max(
                    totalAfterDiscount
                    - deduction,
                    0
                );


            document
                .getElementById(
                    'hargaBeli'
                )
                .textContent =
                    formatUang(
                        hargaBeli
                    );


            document
                .getElementById(
                    'hargaJual'
                )
                .textContent =
                    formatUang(
                        hargaJualNormal
                    );


            document
                .getElementById(
                    'subtotalNormal'
                )
                .textContent =
                    formatUang(
                        totalHargaNormal
                    );


            document
                .getElementById(
                    'totalModal'
                )
                .textContent =
                    formatUang(
                        totalModal
                    );


            document
                .getElementById(
                    'customerDiscountPreview'
                )
                .textContent =
                    formatUang(
                        customerDiscount
                    );


            document
                .getElementById(
                    'totalAfterDiscount'
                )
                .textContent =
                    formatUang(
                        totalAfterDiscount
                    );


            document
                .getElementById(
                    'totalKeuntungan'
                )
                .textContent =
                    formatUang(
                        totalProfit
                    );


            document
                .getElementById(
                    'netDeposit'
                )
                .textContent =
                    formatUang(
                        netDeposit
                    );


            if (
                customerDiscountInput
                && customerDiscount
                    > totalHargaNormal
            ) {

                customerDiscountInput
                    .setCustomValidity(
                        
                    );

            } else if (
                customerDiscountInput
            ) {

                customerDiscountInput
                    .setCustomValidity('');
            }


            if (
                deductionInput
                && deduction
                    > totalAfterDiscount
            ) {

                deductionInput
                    .setCustomValidity(
                    
                    );

            } else if (
                deductionInput
            ) {

                deductionInput
                    .setCustomValidity('');
            }


            if (
                deductionInput
                && deductionNoteInput
                && deduction > 0
                && deductionNoteInput.value.trim() === ''
            ) {

                deductionNoteInput
                    .setCustomValidity(
                        'Keterangan potongan petugas wajib diisi.'
                    );

            } else if (
                deductionNoteInput
            ) {

                deductionNoteInput
                    .setCustomValidity('');
            }


            if (
                productInput.value !== ''
            ) {

                quantityInput.max =
                    stokTersedia;


                stockInformation.textContent =
                    'Stok tersedia: '
                    + stokTersedia
                    + ' unit.';

            } else {

                quantityInput.removeAttribute(
                    'max'
                );


                stockInformation.textContent =
                    'Pilih produk untuk melihat stok tersedia.';
            }


            updateQuantityMessage();
        }


        productInput.addEventListener(
            'change',
            hitungPenjualan
        );


        quantityInput.addEventListener(
            'input',
            hitungPenjualan
        );


        quantityInput.addEventListener(
            'invalid',
            updateQuantityMessage
        );


        if (
            customerDiscountInput
        ) {

            customerDiscountInput
                .addEventListener(
                    'input',
                    hitungPenjualan
                );
        }


        if (
            deductionInput
        ) {

            deductionInput
                .addEventListener(
                    'input',
                    hitungPenjualan
                );
        }


        if (
            deductionNoteInput
        ) {

            deductionNoteInput
                .addEventListener(
                    'input',
                    hitungPenjualan
                );
        }


        hitungPenjualan();


        /*
        |--------------------------------------------------------------------------
        | SIDEBAR MOBILE
        |--------------------------------------------------------------------------
        */

        const sidebar =
            document.getElementById(
                'sidebar'
            );


        const sidebarToggle =
            document.getElementById(
                'sidebarToggle'
            );


        const sidebarOverlay =
            document.getElementById(
                'sidebarOverlay'
            );


        function closeSidebar() {

            sidebar.classList.remove(
                'sidebar-open'
            );


            sidebarOverlay.classList.remove(
                'overlay-open'
            );


            document.body.classList.remove(
                'menu-open'
            );
        }


        sidebarToggle.addEventListener(
            'click',
            function () {

                const open =
                    sidebar.classList.toggle(
                        'sidebar-open'
                    );


                sidebarOverlay.classList.toggle(
                    'overlay-open',
                    open
                );


                document.body.classList.toggle(
                    'menu-open',
                    open
                );
            }
        );


        sidebarOverlay.addEventListener(
            'click',
            closeSidebar
        );

    </script>

</body>
</html>