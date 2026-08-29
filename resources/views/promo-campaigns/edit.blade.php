<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Promo Campaign - Dulmar Satellite Store</title>

    <link
        rel="icon"
        type="image/jpeg"
        href="{{ asset('images/logo-dulmar.jpg') }}"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
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
            width: 245px;
            min-height: 100vh;
            display: flex;
            flex-shrink: 0;
            flex-direction: column;
            padding: 35px 25px;
            background: #1f2b3a;
            color: white;
        }

        .sidebar h1 {
            margin: 0 0 55px;
            font-size: 28px;
        }

        .sidebar-menu {
            flex: 1;
        }

        .sidebar-menu a {
            display: block;
            margin-bottom: 30px;
            color: white;
            font-size: 18px;
            text-decoration: none;
        }

        .sidebar-menu a:hover {
            color: #60a5fa;
        }

        .sidebar-menu a.active {
            padding: 12px 14px;
            border-left: 4px solid #60a5fa;
            border-radius: 6px;
            background: rgba(37, 99, 235, 0.3);
            color: #bfdbfe;
            font-weight: bold;
        }

        .button-logout {
            width: 100%;
            padding: 13px 15px;
            border: none;
            border-radius: 7px;
            background: #dc2626;
            color: white;
            font-size: 17px;
            cursor: pointer;
        }

        .button-logout:hover {
            background: #b91c1c;
        }

        .sidebar-toggle,
        .sidebar-overlay {
            display: none;
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN
        |--------------------------------------------------------------------------
        */

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 50px 32px 120px;
            overflow-x: hidden;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h2 {
            margin: 0 0 12px;
            font-size: 36px;
        }

        .page-header p {
            margin: 0;
            color: #4b5563;
            font-size: 18px;
            line-height: 1.6;
        }

        /*
        |--------------------------------------------------------------------------
        | ALERT
        |--------------------------------------------------------------------------
        */

        .alert-error {
            margin-bottom: 25px;
            padding: 15px 20px;
            border: 1px solid #fca5a5;
            border-radius: 7px;
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        /*
        |--------------------------------------------------------------------------
        | FORM CARD
        |--------------------------------------------------------------------------
        */

        .form-card {
            margin-bottom: 25px;
            padding: 26px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .form-card h3 {
            margin: 0 0 22px;
            font-size: 22px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .form-group {
            min-width: 0;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-size: 15px;
            font-weight: bold;
        }

        .required {
            color: #dc2626;
        }

        .form-control {
            width: 100%;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            background: white;
            font-size: 15px;
        }

        textarea.form-control {
            min-height: 115px;
            resize: vertical;
        }

        .form-control:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }

        .checkbox-row input {
            width: 19px;
            height: 19px;
            cursor: pointer;
        }

        .checkbox-row label {
            margin: 0;
            cursor: pointer;
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT SECTION
        |--------------------------------------------------------------------------
        */

        .product-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }

        .product-search {
            flex: 1;
            min-width: 220px;
        }

        .product-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .button-select-all,
        .button-clear {
            padding: 10px 14px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .button-select-all {
            background: #2563eb;
        }

        .button-select-all:hover {
            background: #1d4ed8;
        }

        .button-clear {
            background: #6b7280;
        }

        .button-clear:hover {
            background: #4b5563;
        }

        .selected-counter {
            margin-bottom: 18px;
            padding: 11px 14px;
            border-radius: 7px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 14px;
            font-weight: bold;
        }

        .product-list {
            display: grid;
            gap: 14px;
        }

        .product-item {
            padding: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 9px;
            background: #ffffff;
            transition:
                border-color 0.2s,
                background 0.2s,
                box-shadow 0.2s;
        }

        .product-item.selected {
            border-color: #60a5fa;
            background: #eff6ff;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08);
        }

        .product-header {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .product-check {
            margin-top: 4px;
        }

        .product-check input {
            width: 19px;
            height: 19px;
            cursor: pointer;
        }

        .product-info {
            flex: 1;
            min-width: 0;
        }

        .product-name {
            margin-bottom: 6px;
            color: #111827;
            font-size: 17px;
            font-weight: bold;
        }

        .product-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 14px;
            color: #6b7280;
            font-size: 13px;
        }

        .discount-section {
            display: grid;
            grid-template-columns:
                minmax(160px, 1fr)
                minmax(160px, 1fr)
                minmax(180px, 1fr);
            gap: 14px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #dbeafe;
        }

        .discount-section.disabled {
            opacity: 0.5;
        }

        .input-prefix-wrapper {
            position: relative;
        }

        .input-prefix {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #6b7280;
            font-weight: bold;
            pointer-events: none;
        }

        .input-prefix-wrapper input {
            padding-left: 32px;
        }

        .promo-preview {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 10px 12px;
            border-radius: 7px;
            background: #f8fafc;
        }

        .promo-preview span {
            margin-bottom: 5px;
            color: #6b7280;
            font-size: 12px;
        }

        .promo-preview strong {
            color: #16a34a;
            font-size: 20px;
        }

        .empty-products {
            padding: 30px;
            color: #6b7280;
            text-align: center;
        }

        /*
        |--------------------------------------------------------------------------
        | BOTTOM ACTION
        |--------------------------------------------------------------------------
        */

        .bottom-actions {
            position: fixed;
            right: 0;
            bottom: 0;
            left: 245px;
            z-index: 800;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 32px;
            border-top: 1px solid #d1d5db;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 -3px 12px rgba(0, 0, 0, 0.06);
            backdrop-filter: blur(8px);
        }

        .button-cancel,
        .button-save {
            min-width: 150px;
            padding: 13px 20px;
            border: none;
            border-radius: 7px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .button-cancel {
            background: #6b7280;
            color: white;
        }

        .button-cancel:hover {
            background: #4b5563;
        }

        .button-save {
            background: #2563eb;
            color: white;
        }

        .button-save:hover {
            background: #1d4ed8;
        }

        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: auto;
            }

            .discount-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .container {
                display: block;
            }

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
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 1000;
                display: block;
                visibility: hidden;
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
                transition: 0.25s;
            }

            .sidebar-overlay.overlay-open {
                visibility: visible;
                opacity: 1;
            }

            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1100;
                width: min(82vw, 285px);
                min-height: 100vh;
                padding: 82px 25px 30px;
                overflow-y: auto;
                transform: translateX(-105%);
                transition: transform 0.25s ease;
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .sidebar h1 {
                margin-bottom: 35px;
                font-size: 24px;
            }

            .sidebar-menu a {
                margin-bottom: 10px;
                padding: 12px 10px;
                border-radius: 6px;
                background: rgba(255, 255, 255, 0.06);
                font-size: 16px;
            }

            .main-content {
                width: 100%;
                padding: 85px 14px 130px;
            }

            .page-header h2 {
                font-size: 30px;
            }

            .form-card {
                padding: 20px 14px;
            }

            .product-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .product-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .button-select-all,
            .button-clear {
                width: 100%;
            }

            .bottom-actions {
                left: 0;
                padding: 12px 14px;
            }

            .button-cancel,
            .button-save {
                flex: 1;
                min-width: 0;
            }
        }
    </style>

    <link
        rel="stylesheet"
        href="{{ asset('css/fixed-layout.css') }}"
    >
</head>

<body>

    <button
        type="button"
        id="sidebarToggle"
        class="sidebar-toggle"
        aria-label="Buka menu"
        aria-expanded="false"
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
            <h1>
                Dulmar Satellite Store
            </h1>

            <nav class="sidebar-menu">

                <a
                    href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('products.index') }}"
                    class="{{ request()->routeIs('products.*') ? 'active' : '' }}"
                >
                    Daftar Barang
                </a>

                <a
                    href="{{ route('promo-campaigns.index') }}"
                    class="{{ request()->routeIs('promo-campaigns.*') ? 'active' : '' }}"
                >
                    Promo Campaign
                </a>

                <a
                    href="{{ route('stock-ins.index') }}"
                    class="{{ request()->routeIs('stock-ins.*') ? 'active' : '' }}"
                >
                    Stok Masuk
                </a>

                <a
                    href="{{ route('stock-outs.index') }}"
                    class="{{ request()->routeIs('stock-outs.*') ? 'active' : '' }}"
                >
                    Stok Keluar
                </a>

                <a
                    href="{{ route('tv-vouchers.index') }}"
                    class="{{ request()->routeIs('tv-vouchers.*') ? 'active' : '' }}"
                >
                    TV Voucher
                </a>

                <a
                    href="{{ route('suppliers.index') }}"
                    class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
                >
                    Supplier Barang
                </a>

                <a
                    href="{{ route('customers.index') }}"
                    class="{{ request()->routeIs('customers.*') ? 'active' : '' }}"
                >
                    Pelanggan
                </a>

                <a
                    href="{{ route('reports.index') }}"
                    class="{{ request()->routeIs('reports.*') ? 'active' : '' }}"
                >
                    Laporan
                </a>

            </nav>

            <form
                action="{{ route('logout') }}"
                method="POST"
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
                    Edit Promo Campaign
                </h2>

                <p>
                    Perbarui informasi campaign, periode promo,
                    produk yang ikut promo, dan diskon masing-masing produk.
                </p>

            </div>

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

            @php
                $selectedProducts = $promoCampaign
                    ->products
                    ->keyBy('id');
            @endphp

            <form
                id="campaignForm"
                action="{{ route('promo-campaigns.update', $promoCampaign) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <section class="form-card">

                    <h3>
                        Informasi Campaign
                    </h3>

                    <div class="form-grid">

                        <div class="form-group full-width">

                            <label for="title">
                                Judul Campaign
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="title"
                                name="title"
                                class="form-control"
                                value="{{ old('title', $promoCampaign->title) }}"
                                required
                            >

                        </div>

                        <div class="form-group full-width">

                            <label for="description">
                                Deskripsi
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                class="form-control"
                                placeholder="Masukkan deskripsi promo"
                            >{{ old('description', $promoCampaign->description) }}</textarea>

                        </div>

                        <div class="form-group">

                            <label for="start_date">
                                Tanggal Mulai
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                class="form-control"
                                value="{{ old(
                                    'start_date',
                                    optional($promoCampaign->start_date)->format('Y-m-d')
                                ) }}"
                                required
                            >

                        </div>

                        <div class="form-group">

                            <label for="end_date">
                                Tanggal Selesai
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                class="form-control"
                                value="{{ old(
                                    'end_date',
                                    optional($promoCampaign->end_date)->format('Y-m-d')
                                ) }}"
                                required
                            >

                        </div>

                        <div class="form-group full-width">

                            <input
                                type="hidden"
                                name="is_active"
                                value="0"
                            >

                            <div class="checkbox-row">

                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    {{ old(
                                        'is_active',
                                        $promoCampaign->is_active ? 1 : 0
                                    ) ? 'checked' : '' }}
                                >

                                <label for="is_active">
                                    Campaign Aktif
                                </label>

                            </div>

                        </div>

                    </div>

                </section>

                <section class="form-card">

                    <h3>
                        Produk Promo
                    </h3>

                    <div class="product-toolbar">

                        <div class="product-search">

                            <input
                                type="text"
                                id="productSearch"
                                class="form-control"
                                placeholder="Cari nama produk..."
                            >

                        </div>

                        <div class="product-actions">

                            <button
                                type="button"
                                id="selectAllProducts"
                                class="button-select-all"
                            >
                                Pilih Semua
                            </button>

                            <button
                                type="button"
                                id="clearProducts"
                                class="button-clear"
                            >
                                Hapus Pilihan
                            </button>

                        </div>

                    </div>

                    <div
                        id="selectedCounter"
                        class="selected-counter"
                    >
                        0 Produk Dipilih
                    </div>

                    <div
                        class="product-list"
                        id="productList"
                    >

                        @forelse ($products as $index => $product)

                            @php
                                $existingProduct =
                                    $selectedProducts->get($product->id);

                                $oldProductId =
                                    old(
                                        "products.$index.product_id"
                                    );

                                $selectedFromOld =
                                    old('products') !== null
                                    && (string) $oldProductId
                                        === (string) $product->id;

                                $selected =
                                    old('products') !== null
                                        ? $selectedFromOld
                                        : (bool) $existingProduct;

                                $discountType =
                                    old(
                                        "products.$index.discount_type",
                                        $existingProduct
                                            ? $existingProduct
                                                ->pivot
                                                ->discount_type
                                            : 'fixed'
                                    );

                                $discountValue =
                                    old(
                                        "products.$index.discount_value",
                                        $existingProduct
                                            ? $existingProduct
                                                ->pivot
                                                ->discount_value
                                            : ''
                                    );

                                $normalPrice =
                                    (float) $product->selling_price;
                            @endphp

                            <div
                                class="product-item {{ $selected ? 'selected' : '' }}"
                                data-name="{{ strtolower($product->product_name) }}"
                                data-price="{{ $normalPrice }}"
                            >

                                <div class="product-header">

                                    <div class="product-check">

                                        <input
                                            type="checkbox"
                                            class="product-checkbox"
                                            data-index="{{ $index }}"
                                            {{ $selected ? 'checked' : '' }}
                                        >

                                    </div>

                                    <div class="product-info">

                                        <div class="product-name">
                                            {{ $product->product_name }}
                                        </div>

                                        <div class="product-meta">

                                            <span>
                                                Kategori:
                                                {{ $product->category ?: '-' }}
                                            </span>

                                            <span>
                                                Harga Normal:
                                                ${{ number_format($normalPrice, 2) }}
                                            </span>

                                            <span>
                                                Stok:
                                                {{ $product->stock }} unit
                                            </span>

                                        </div>

                                    </div>

                                </div>

                                <div
                                    class="discount-section {{ $selected ? '' : 'disabled' }}"
                                >

                                    <input
                                        type="hidden"
                                        class="product-id-input"
                                        name="products[{{ $index }}][product_id]"
                                        value="{{ $product->id }}"
                                        {{ $selected ? '' : 'disabled' }}
                                    >

                                    <div class="form-group">

                                        <label>
                                            Jenis Diskon
                                        </label>

                                        <select
                                            name="products[{{ $index }}][discount_type]"
                                            class="form-control discount-type"
                                            {{ $selected ? '' : 'disabled' }}
                                        >
                                            <option
                                                value="fixed"
                                                {{ $discountType === 'fixed' ? 'selected' : '' }}
                                            >
                                                Fixed ($)
                                            </option>

                                            <option
                                                value="percent"
                                                {{ $discountType === 'percent' ? 'selected' : '' }}
                                            >
                                                Percent (%)
                                            </option>
                                        </select>

                                    </div>

                                    <div class="form-group">

                                        <label>
                                            Nilai Diskon
                                        </label>

                                        <div class="input-prefix-wrapper">

                                            <span class="input-prefix">
                                                {{ $discountType === 'percent' ? '%' : '$' }}
                                            </span>

                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0.01"
                                                name="products[{{ $index }}][discount_value]"
                                                class="form-control discount-value"
                                                value="{{ $discountValue }}"
                                                {{ $selected ? '' : 'disabled' }}
                                            >

                                        </div>

                                    </div>

                                    <div class="promo-preview">

                                        <span>
                                            Estimasi Harga Promo
                                        </span>

                                        <strong
                                            class="promo-price"
                                        >
                                            ${{ number_format($normalPrice, 2) }}
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="empty-products">
                                Belum ada produk.
                            </div>

                        @endforelse

                    </div>

                </section>

                <div class="bottom-actions">

                    <a
                        href="{{ route('promo-campaigns.index') }}"
                        class="button-cancel"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="button-save"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </main>

    </div>

    <script>
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

            sidebarToggle.textContent =
                '☰';

            sidebarToggle.setAttribute(
                'aria-expanded',
                'false'
            );

            document.body.classList.remove(
                'menu-open'
            );
        }

        sidebarToggle.addEventListener(
            'click',
            function () {
                const isOpen =
                    sidebar.classList.toggle(
                        'sidebar-open'
                    );

                sidebarOverlay.classList.toggle(
                    'overlay-open',
                    isOpen
                );

                sidebarToggle.textContent =
                    isOpen
                        ? '✕'
                        : '☰';

                sidebarToggle.setAttribute(
                    'aria-expanded',
                    isOpen
                        ? 'true'
                        : 'false'
                );

                document.body.classList.toggle(
                    'menu-open',
                    isOpen
                );
            }
        );

        sidebarOverlay.addEventListener(
            'click',
            closeSidebar
        );

        document
            .querySelectorAll(
                '.sidebar a'
            )
            .forEach(function (link) {
                link.addEventListener(
                    'click',
                    closeSidebar
                );
            });

        const productItems =
            document.querySelectorAll(
                '.product-item'
            );

        const selectedCounter =
            document.getElementById(
                'selectedCounter'
            );

        const productSearch =
            document.getElementById(
                'productSearch'
            );

        const selectAllProducts =
            document.getElementById(
                'selectAllProducts'
            );

        const clearProducts =
            document.getElementById(
                'clearProducts'
            );

        const campaignForm =
            document.getElementById(
                'campaignForm'
            );

        function updateCounter() {
            const selected =
                document.querySelectorAll(
                    '.product-checkbox:checked'
                ).length;

            selectedCounter.textContent =
                selected
                + ' Produk Dipilih';
        }

        function updateProductState(item) {
            const checkbox =
                item.querySelector(
                    '.product-checkbox'
                );

            const discountSection =
                item.querySelector(
                    '.discount-section'
                );

            const productIdInput =
                item.querySelector(
                    '.product-id-input'
                );

            const discountType =
                item.querySelector(
                    '.discount-type'
                );

            const discountValue =
                item.querySelector(
                    '.discount-value'
                );

            const enabled =
                checkbox.checked;

            item.classList.toggle(
                'selected',
                enabled
            );

            discountSection.classList.toggle(
                'disabled',
                !enabled
            );

            productIdInput.disabled =
                !enabled;

            discountType.disabled =
                !enabled;

            discountValue.disabled =
                !enabled;

            updatePromoPreview(item);
            updateCounter();
        }

        function updatePromoPreview(item) {
            const checkbox =
                item.querySelector(
                    '.product-checkbox'
                );

            const type =
                item.querySelector(
                    '.discount-type'
                );

            const valueInput =
                item.querySelector(
                    '.discount-value'
                );

            const prefix =
                item.querySelector(
                    '.input-prefix'
                );

            const preview =
                item.querySelector(
                    '.promo-price'
                );

            const normalPrice =
                Number(
                    item.dataset.price
                    || 0
                );

            const discountValue =
                Number(
                    valueInput.value
                    || 0
                );

            prefix.textContent =
                type.value === 'percent'
                    ? '%'
                    : '$';

            if (!checkbox.checked) {
                preview.textContent =
                    '$'
                    + normalPrice.toFixed(2);

                return;
            }

            let promoPrice =
                normalPrice;

            if (
                type.value === 'percent'
            ) {
                promoPrice =
                    normalPrice
                    - (
                        normalPrice
                        * discountValue
                        / 100
                    );
            } else {
                promoPrice =
                    normalPrice
                    - discountValue;
            }

            if (promoPrice < 0) {
                promoPrice = 0;
            }

            preview.textContent =
                '$'
                + promoPrice.toFixed(2);
        }

        productItems.forEach(
            function (item) {
                const checkbox =
                    item.querySelector(
                        '.product-checkbox'
                    );

                const discountType =
                    item.querySelector(
                        '.discount-type'
                    );

                const discountValue =
                    item.querySelector(
                        '.discount-value'
                    );

                checkbox.addEventListener(
                    'change',
                    function () {
                        updateProductState(
                            item
                        );
                    }
                );

                discountType.addEventListener(
                    'change',
                    function () {
                        updatePromoPreview(
                            item
                        );
                    }
                );

                discountValue.addEventListener(
                    'input',
                    function () {
                        updatePromoPreview(
                            item
                        );
                    }
                );

                updateProductState(
                    item
                );
            }
        );

        productSearch.addEventListener(
            'input',
            function () {
                const keyword =
                    productSearch
                        .value
                        .trim()
                        .toLowerCase();

                productItems.forEach(
                    function (item) {
                        const name =
                            item.dataset.name
                            || '';

                        item.style.display =
                            name.includes(keyword)
                                ? ''
                                : 'none';
                    }
                );
            }
        );

        selectAllProducts.addEventListener(
            'click',
            function () {
                productItems.forEach(
                    function (item) {
                        if (
                            item.style.display
                            === 'none'
                        ) {
                            return;
                        }

                        const checkbox =
                            item.querySelector(
                                '.product-checkbox'
                            );

                        checkbox.checked =
                            true;

                        updateProductState(
                            item
                        );
                    }
                );
            }
        );

        clearProducts.addEventListener(
            'click',
            function () {
                productItems.forEach(
                    function (item) {
                        const checkbox =
                            item.querySelector(
                                '.product-checkbox'
                            );

                        checkbox.checked =
                            false;

                        updateProductState(
                            item
                        );
                    }
                );
            }
        );

        campaignForm.addEventListener(
            'submit',
            function (event) {
                const selected =
                    document.querySelectorAll(
                        '.product-checkbox:checked'
                    );

                if (
                    selected.length === 0
                ) {
                    event.preventDefault();

                    alert(
                        'Pilih minimal 1 produk untuk Promo Campaign.'
                    );

                    return;
                }

                let valid =
                    true;

                selected.forEach(
                    function (checkbox) {
                        const item =
                            checkbox.closest(
                                '.product-item'
                            );

                        const value =
                            Number(
                                item
                                    .querySelector(
                                        '.discount-value'
                                    )
                                    .value
                            );

                        if (
                            !Number.isFinite(value)
                            || value <= 0
                        ) {
                            valid =
                                false;
                        }
                    }
                );

                if (!valid) {
                    event.preventDefault();

                    alert(
                        'Nilai diskon produk yang dipilih harus lebih dari 0.'
                    );
                }
            }
        );

        window.addEventListener(
            'resize',
            function () {
                if (
                    window.innerWidth > 700
                ) {
                    closeSidebar();
                }
            }
        );
    </script>

</body>
</html>