<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Stok Keluar - Dulmar Satellite Store</title>

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

            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
            line-height: 1.5;
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

        .deduction-card {
            border-color: #fdba74;
            background: #fff7ed;
        }

        .deduction-card h3 {
            color: #9a3412;
        }

        .calculation-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
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

        .profit-item {
            grid-column: 1 / -1;
            border-left: 5px solid #16a34a;
        }

        .profit-item strong {
            color: #16a34a;
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

        .button-update {
            background: #dc2626;
            color: white;
        }

        .button-update:hover {
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

                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
            }

            .sidebar-overlay.overlay-open {
                visibility: visible;
                opacity: 1;
            }

            .sidebar {
                z-index: 1100;

                width: min(82vw, 285px);

                padding: 82px 25px 30px;

                transform: translateX(-105%);
                transition: transform 0.25s ease;
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .main-content {
                width: 100%;

                margin-left: 0;

                padding: 85px 14px 30px;
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

            .profit-item,
            .after-discount-item,
            .net-deposit-item {
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

        <aside
            class="sidebar"
            id="sidebar"
        >

            <h1>Dulmar Satellite Store</h1>


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


        <main class="main-content">

            <div class="page-header">

                <h2>
                    Edit Stok Keluar
                </h2>

                <p>
                    Perbarui transaksi penjualan, diskon pelanggan, data pelanggan, dan potongan petugas.
                </p>

            </div>


            <div class="form-card">

                @if ($errors->any())

                    <div class="alert-error">

                        <ul>

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <div class="stock-warning">
                    Jumlah barang tidak boleh melebihi stok yang tersedia.
                    Stok transaksi lama akan diperhitungkan kembali saat data diperbarui.
                </div>


                <form
                    action="{{ route('stock-outs.update', $stockOut) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')


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

                            <option value="">
                                -- Pilih Produk --
                            </option>


                            @foreach ($products as $product)

                                <option
                                    value="{{ $product->id }}"
                                    data-purchase-price="{{ $product->purchase_price }}"
                                    data-selling-price="{{ $product->selling_price }}"
                                    data-stock="{{ $product->stock }}"
                                    {{ old('product_id', $stockOut->product_id) == $product->id ? 'selected' : '' }}
                                >

                                    {{ $product->product_name }}

                                    - Stok: {{ $product->stock }}

                                    @if ($product->id == $stockOut->product_id)

                                        + {{ $stockOut->quantity }} transaksi lama

                                    @endif

                                </option>

                            @endforeach

                        </select>

                        <span class="help-text">
                            Pilih produk yang dijual.
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
                            value="{{ old('customer_name', $stockOut->customer?->customer_name) }}"
                            maxlength="255"
                            placeholder="Masukkan nama pelanggan"
                            required
                        >

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
                            value="{{ old('customer_phone', $stockOut->customer?->phone) }}"
                            maxlength="50"
                            placeholder="Contoh: 77234567"
                        >

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
                            value="{{ old('customer_address', $stockOut->customer?->address) }}"
                            maxlength="255"
                            placeholder="Contoh: Comoro, Dili"
                        >

                    </div>


                    <input
                        type="hidden"
                        name="customer_id"
                        value="{{ old('customer_id', $stockOut->customer_id) }}"
                    >


                    {{-- ======================================================
                         JUMLAH
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
                            value="{{ old('quantity', $stockOut->quantity) }}"
                            min="1"
                            required
                        >

                        <span
                            class="help-text"
                            id="stockInformation"
                        >
                            Masukkan jumlah barang yang dijual.
                        </span>

                    </div>


                    {{-- ======================================================
                         PERHITUNGAN PENJUALAN
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

                                <strong id="purchasePrice">
                                    $0.00
                                </strong>

                            </div>


                            <div class="calculation-item">

                                <span>
                                    Harga Jual Normal per Unit
                                </span>

                                <strong id="sellingPrice">
                                    $0.00
                                </strong>

                            </div>


                            <div class="calculation-item">

                                <span>
                                    Total Harga Normal
                                </span>

                                <strong id="normalSubtotal">
                                    $0.00
                                </strong>

                            </div>


                            <div class="calculation-item">

                                <span>
                                    Total Modal
                                </span>

                                <strong id="totalCost">
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
                                    value="{{ old('customer_discount_amount', $stockOut->customer_discount_amount ?? 0) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                >

                                <span class="help-text">
                                    Contoh: harga normal $65 dan pelanggan diberi diskon $5, maka isi 5.
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
                                    value="{{ old('customer_discount_note', $stockOut->customer_discount_note) }}"
                                    maxlength="255"
                                    placeholder="Contoh: Diskon pelanggan / Promo khusus"
                                >

                                <span class="help-text">
                                    Opsional. Isi jika ingin mencatat alasan diskon.
                                </span>

                            </div>


                            <div class="calculation-item discount-item">

                                <span>
                                    Total Diskon Pelanggan
                                </span>

                                <strong id="discountPreview">
                                    $0.00
                                </strong>

                            </div>


                            <div class="calculation-item after-discount-item">

                                <span>
                                    Total Setelah Diskon / Tagihan Customer
                                </span>

                                <strong id="subtotal">
                                    $0.00
                                </strong>

                                <span class="help-text">
                                    Total Harga Normal - Diskon Pelanggan.
                                </span>

                            </div>


                            <div class="calculation-item profit-item">

                                <span>
                                    Keuntungan Setelah Diskon
                                </span>

                                <strong id="totalProfit">
                                    $0.00
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- ======================================================
                         POTONGAN PETUGAS / SETORAN BERSIH
                    ======================================================= --}}

                    <div class="calculation-card deduction-card">

                        <h3>
                            Potongan Petugas dan Setoran Bersih
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
                                    value="{{ old('deduction_amount', $stockOut->deduction_amount ?? 0) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                >

                                <span class="help-text">
                                    Contoh: setelah diskon customer harus bayar $60, lalu $3 dipakai untuk bensin/komisi petugas, maka isi 3.
                                    Jika tidak ada potongan petugas, isi 0.
                                </span>

                            </div>


                            <div class="form-group" style="margin-bottom:0;">

                                <label for="deduction_note">
                                    Keterangan Potongan Petugas
                                </label>

                                <input
                                    type="text"
                                    id="deduction_note"
                                    name="deduction_note"
                                    class="form-control"
                                    value="{{ old('deduction_note', $stockOut->deduction_note) }}"
                                    maxlength="255"
                                    placeholder="Contoh: Bensin / Komisi petugas"
                                >

                                <span class="help-text">
                                    Wajib diisi jika potongan petugas lebih dari 0.
                                </span>

                            </div>


                            <div class="calculation-item net-deposit-item">

                                <span>
                                    Setoran Bersih ke Kas
                                </span>

                                <strong id="netDeposit">
                                    $0.00
                                </strong>

                                <span class="help-text">
                                    Total Setelah Diskon - Potongan Petugas.
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
                                $stockOut->transaction_date
                                    ? \Carbon\Carbon::parse($stockOut->transaction_date)->format('Y-m-d')
                                    : date('Y-m-d')
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
                            placeholder="Masukkan catatan jika diperlukan"
                        >{{ old('notes', $stockOut->notes) }}</textarea>

                    </div>


                    <div class="form-actions">

                        <button
                            type="submit"
                            class="button button-update"
                        >
                            Simpan Perubahan
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

        const productInput =
            document.getElementById(
                'product_id'
            );

        const quantityInput =
            document.getElementById(
                'quantity'
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

        const purchasePriceElement =
            document.getElementById(
                'purchasePrice'
            );

        const sellingPriceElement =
            document.getElementById(
                'sellingPrice'
            );

        const normalSubtotalElement =
            document.getElementById(
                'normalSubtotal'
            );

        const discountPreviewElement =
            document.getElementById(
                'discountPreview'
            );

        const subtotalElement =
            document.getElementById(
                'subtotal'
            );

        const totalCostElement =
            document.getElementById(
                'totalCost'
            );

        const totalProfitElement =
            document.getElementById(
                'totalProfit'
            );

        const netDepositElement =
            document.getElementById(
                'netDeposit'
            );

        const stockInformationElement =
            document.getElementById(
                'stockInformation'
            );


        const oldProductId =
            {{ (int) $stockOut->product_id }};

        const oldQuantity =
            {{ (int) $stockOut->quantity }};


        function formatMoney(value) {

            return '$'
                + Number(
                    value || 0
                ).toFixed(2);
        }


        function updateQuantityValidation() {

            quantityInput.setCustomValidity('');

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


        function calculateSale() {

            const selectedOption =
                productInput.options[
                    productInput.selectedIndex
                ];


            const purchasePrice =
                Number(
                    selectedOption
                        ?.dataset
                        .purchasePrice
                    || 0
                );


            const sellingPrice =
                Number(
                    selectedOption
                        ?.dataset
                        .sellingPrice
                    || 0
                );


            const currentStock =
                Number(
                    selectedOption
                        ?.dataset
                        .stock
                    || 0
                );


            const selectedProductId =
                Number(
                    selectedOption?.value
                    || 0
                );


            const quantity =
                Number(
                    quantityInput.value
                    || 0
                );


            const customerDiscount =
                Number(
                    customerDiscountInput.value
                    || 0
                );


            const deduction =
                Number(
                    deductionInput.value
                    || 0
                );


            const availableStock =
                selectedProductId
                === oldProductId
                    ? currentStock
                        + oldQuantity
                    : currentStock;


            const normalSubtotal =
                sellingPrice
                * quantity;


            const subtotal =
                Math.max(
                    normalSubtotal
                    - customerDiscount,
                    0
                );


            const totalCost =
                purchasePrice
                * quantity;


            const totalProfit =
                subtotal
                - totalCost;


            const netDeposit =
                Math.max(
                    subtotal
                    - deduction,
                    0
                );


            purchasePriceElement.textContent =
                formatMoney(
                    purchasePrice
                );


            sellingPriceElement.textContent =
                formatMoney(
                    sellingPrice
                );


            normalSubtotalElement.textContent =
                formatMoney(
                    normalSubtotal
                );


            discountPreviewElement.textContent =
                formatMoney(
                    customerDiscount
                );


            subtotalElement.textContent =
                formatMoney(
                    subtotal
                );


            totalCostElement.textContent =
                formatMoney(
                    totalCost
                );


            totalProfitElement.textContent =
                formatMoney(
                    totalProfit
                );


            netDepositElement.textContent =
                formatMoney(
                    netDeposit
                );


            if (
                customerDiscount
                > normalSubtotal
            ) {

                customerDiscountInput.setCustomValidity(
                    'Diskon pelanggan tidak boleh melebihi total harga normal.'
                );

            } else {

                customerDiscountInput.setCustomValidity('');
            }


            if (
                deduction
                > subtotal
            ) {

                deductionInput.setCustomValidity(
                    'Potongan petugas tidak boleh melebihi total setelah diskon.'
                );

            } else {

                deductionInput.setCustomValidity('');
            }


            if (
                deduction > 0
                && deductionNoteInput.value.trim() === ''
            ) {

                deductionNoteInput.setCustomValidity(
                    'Keterangan potongan petugas wajib diisi.'
                );

            } else {

                deductionNoteInput.setCustomValidity('');
            }


            if (
                selectedProductId > 0
            ) {

                stockInformationElement.textContent =
                    'Stok yang dapat digunakan: '
                    + availableStock
                    + ' unit.';


                quantityInput.max =
                    availableStock;

            } else {

                stockInformationElement.textContent =
                    'Pilih produk terlebih dahulu.';


                quantityInput.removeAttribute(
                    'max'
                );
            }


            updateQuantityValidation();
        }


        productInput.addEventListener(
            'change',
            calculateSale
        );


        quantityInput.addEventListener(
            'input',
            calculateSale
        );


        quantityInput.addEventListener(
            'invalid',
            updateQuantityValidation
        );


        customerDiscountInput.addEventListener(
            'input',
            calculateSale
        );


        deductionInput.addEventListener(
            'input',
            calculateSale
        );


        deductionNoteInput.addEventListener(
            'input',
            calculateSale
        );


        calculateSale();


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
