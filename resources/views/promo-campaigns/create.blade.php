<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Promo Campaign - Dulmar Satellite Store</title>

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
            background-color: #f4f6f9;
        }

        body.menu-open {
            overflow: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 245px;
            min-height: 100vh;
            display: flex;
            flex-shrink: 0;
            flex-direction: column;
            padding: 35px 25px;
            background-color: #1f2b3a;
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
            background-color: rgba(37, 99, 235, 0.3);
            color: #bfdbfe;
            font-weight: bold;
        }

        .button-logout {
            width: 100%;
            padding: 13px 15px;
            border: none;
            border-radius: 7px;
            background-color: #dc2626;
            color: white;
            font-size: 17px;
            cursor: pointer;
        }

        .sidebar-toggle,
        .sidebar-overlay {
            display: none;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 50px 32px 120px;
            overflow-x: hidden;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
        }

        .page-header h2 {
            margin: 0 0 12px;
            font-size: 34px;
        }

        .page-header p {
            margin: 0;
            color: #4b5563;
            font-size: 17px;
        }

        .button-back {
            display: inline-block;
            padding: 13px 18px;
            border-radius: 7px;
            background-color: #6b7280;
            color: white;
            text-decoration: none;
        }

        .form-card {
            padding: 28px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .section-title {
            margin: 0 0 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 21px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-size: 14px;
            font-weight: bold;
        }

        .required {
            color: #dc2626;
        }

        .form-control {
            width: 100%;
            min-height: 45px;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            font-size: 15px;
        }

        textarea.form-control {
            min-height: 110px;
            resize: vertical;
        }

        .form-control:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .switch-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .products-section {
            margin-top: 20px;
        }

        .product-toolbar {
            display: grid;
            grid-template-columns: minmax(220px, 1fr) auto auto auto;
            gap: 10px;
            align-items: center;
            margin-bottom: 18px;
        }

        .toolbar-button {
            min-height: 44px;
            padding: 0 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }

        .button-select-all {
            background-color: #2563eb;
            color: white;
        }

        .button-clear {
            background-color: #6b7280;
            color: white;
        }

        .selected-counter {
            min-width: 145px;
            padding: 11px 14px;
            border-radius: 6px;
            background-color: #ede9fe;
            color: #5b21b6;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        .product-list {
            display: grid;
            gap: 12px;
        }

        .product-card {
            display: grid;
            grid-template-columns: 42px minmax(220px, 1.4fr) minmax(150px, 0.8fr) minmax(170px, 0.8fr) minmax(160px, 0.8fr);
            align-items: center;
            gap: 15px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 9px;
            background-color: #f9fafb;
            transition: 0.2s ease;
        }

        .product-card:hover {
            border-color: #cbd5e1;
            background-color: #ffffff;
        }

        .product-card.selected {
            border: 2px solid #2563eb;
            background-color: #eff6ff;
        }

        .product-card.hidden-product {
            display: none;
        }

        .product-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .product-name {
            margin-bottom: 4px;
            font-size: 16px;
            font-weight: bold;
        }

        .product-meta {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
        }

        .discount-field {
            display: none;
        }

        .product-card.selected .discount-field {
            display: block;
        }

        .price-preview {
            display: none;
            padding: 10px 12px;
            border-radius: 7px;
            background-color: #ecfdf5;
            color: #166534;
            font-size: 13px;
            line-height: 1.5;
        }

        .product-card.selected .price-preview {
            display: block;
        }

        .price-normal {
            color: #6b7280;
            text-decoration: line-through;
        }

        .price-promo {
            color: #16a34a;
            font-size: 16px;
            font-weight: bold;
        }

        .discount-input-wrap {
            position: relative;
        }

        .discount-prefix {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #6b7280;
            font-weight: bold;
        }

        .discount-value-input {
            padding-left: 30px;
        }

        .alert-error {
            margin-bottom: 25px;
            padding: 15px 20px;
            border: 1px solid #fca5a5;
            border-radius: 6px;
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .empty-search {
            display: none;
            padding: 25px;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            color: #6b7280;
            text-align: center;
        }

        .sticky-actions {
            position: fixed;
            right: 0;
            bottom: 0;
            left: 245px;
            z-index: 900;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 32px;
            border-top: 1px solid #e5e7eb;
            background-color: rgba(255, 255, 255, 0.96);
            box-shadow: 0 -3px 12px rgba(0, 0, 0, 0.08);
        }

        .button-cancel,
        .button-save {
            min-width: 150px;
            padding: 13px 22px;
            border: none;
            border-radius: 7px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .button-cancel {
            background-color: #6b7280;
            color: white;
        }

        .button-save {
            background-color: #2563eb;
            color: white;
        }

        .button-save:hover {
            background-color: #1d4ed8;
        }

        @media (max-width: 1150px) {
            .product-card {
                grid-template-columns: 42px minmax(220px, 1fr) minmax(150px, 1fr);
            }

            .discount-field,
            .price-preview {
                grid-column: 2 / -1;
            }

            .product-toolbar {
                grid-template-columns: 1fr 1fr;
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
                background-color: #1f2b3a;
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
                background-color: rgba(0, 0, 0, 0.5);
                opacity: 0;
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
                padding: 82px 25px 30px;
                overflow-y: auto;
                transform: translateX(-105%);
                transition: transform 0.25s ease;
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .main-content {
                width: 100%;
                padding: 85px 15px 120px;
            }

            .page-header {
                flex-direction: column;
            }

            .button-back {
                width: 100%;
                text-align: center;
            }

            .form-card {
                padding: 20px 15px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .product-toolbar {
                grid-template-columns: 1fr;
            }

            .product-card {
                grid-template-columns: 35px 1fr;
            }

            .discount-field,
            .price-preview {
                grid-column: 2;
            }

            .sticky-actions {
                left: 0;
                padding: 12px 15px;
            }

            .button-cancel,
            .button-save {
                min-width: 0;
                flex: 1;
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

            <a href="{{ route('dashboard') }}">
                Dashboard
            </a>

            <a href="{{ route('products.index') }}">
                Daftar Barang
            </a>

            <a
                href="{{ route('promo-campaigns.index') }}"
                class="active"
            >
                Promo Campaign
            </a>

            <a href="{{ route('stock-ins.index') }}">
                Stok Masuk
            </a>

            <a href="{{ route('stock-outs.index') }}">
                Stok Keluar
            </a>

            <a href="{{ route('tv-vouchers.index') }}">
                TV Voucher
            </a>

            <a href="{{ route('suppliers.index') }}">
                Supplier Barang
            </a>

            <a href="{{ route('customers.index') }}">
                Pelanggan
            </a>

            <a href="{{ route('reports.index') }}">
                Laporan
            </a>

        </nav>

        <form
            action="{{ route('logout') }}"
            method="POST"
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

            <div>
                <h2>Tambah Promo Campaign</h2>

                <p>
                    Buat satu campaign dan tentukan diskon
                    untuk produk yang dipilih.
                </p>
            </div>

            <a
                href="{{ route('promo-campaigns.index') }}"
                class="button-back"
            >
                ← Kembali
            </a>

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

        <form
            id="campaignForm"
            action="{{ route('promo-campaigns.store') }}"
            method="POST"
        >
            @csrf

            <div class="form-card">

                <h3 class="section-title">
                    Informasi Campaign
                </h3>

                <div class="form-grid">

                    <div class="form-group full">

                        <label for="title">
                            Judul Promo
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-control"
                            value="{{ old('title') }}"
                            placeholder="Contoh: Promo Akhir Bulan"
                            required
                        >

                    </div>

                    <div class="form-group full">

                        <label for="description">
                            Keterangan / Alasan Promo
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            placeholder="Contoh: Promo khusus akhir bulan untuk meningkatkan penjualan."
                        >{{ old('description') }}</textarea>

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
                            value="{{ old('start_date') }}"
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
                            value="{{ old('end_date') }}"
                            required
                        >

                    </div>

                </div>

                <div class="switch-row">

                    <input
                        type="hidden"
                        name="is_active"
                        value="0"
                    >

                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                    >

                    <label
                        for="is_active"
                        style="margin: 0;"
                    >
                        Campaign Aktif
                    </label>

                </div>

                <div class="products-section">

                    <h3 class="section-title">
                        Pilih Produk Promo
                    </h3>

                    <div class="product-toolbar">

                        <input
                            type="text"
                            id="productSearch"
                            class="form-control"
                            placeholder="Cari produk..."
                        >

                        <button
                            type="button"
                            id="selectAllButton"
                            class="toolbar-button button-select-all"
                        >
                            Pilih Semua
                        </button>

                        <button
                            type="button"
                            id="clearAllButton"
                            class="toolbar-button button-clear"
                        >
                            Hapus Pilihan
                        </button>

                        <div
                            id="selectedCounter"
                            class="selected-counter"
                        >
                            0 Produk Dipilih
                        </div>

                    </div>

                    <div
                        id="productList"
                        class="product-list"
                    >

                        @forelse ($products as $index => $product)

                            @php
                                $oldProductId =
                                    old("products.$index.product_id");

                                $selected =
                                    (string) $oldProductId
                                    === (string) $product->id;

                                $normalPrice =
                                    (float) $product->selling_price;
                            @endphp

                            <div
                                class="product-card {{ $selected ? 'selected' : '' }}"
                                data-product-row
                                data-product-name="{{ strtolower($product->product_name) }}"
                                data-normal-price="{{ $normalPrice }}"
                            >

                                <div>
                                    <input
                                        type="checkbox"
                                        class="product-checkbox"
                                        data-product-checkbox
                                        {{ $selected ? 'checked' : '' }}
                                    >
                                </div>

                                <div>

                                    <div class="product-name">
                                        {{ $product->product_name }}
                                    </div>

                                    <div class="product-meta">
                                        {{ $product->category }}
                                        |
                                        Harga Normal:
                                        ${{ number_format($normalPrice, 2) }}
                                    </div>

                                </div>

                                <div class="discount-field">

                                    <label>
                                        Jenis Diskon
                                    </label>

                                    <select
                                        name="products[{{ $index }}][discount_type]"
                                        class="form-control"
                                        data-discount-type
                                        {{ $selected ? '' : 'disabled' }}
                                    >
                                        <option
                                            value="fixed"
                                            {{
                                                old(
                                                    "products.$index.discount_type",
                                                    'fixed'
                                                ) === 'fixed'
                                                    ? 'selected'
                                                    : ''
                                            }}
                                        >
                                            Nominal / Fixed
                                        </option>

                                        <option
                                            value="percent"
                                            {{
                                                old(
                                                    "products.$index.discount_type"
                                                ) === 'percent'
                                                    ? 'selected'
                                                    : ''
                                            }}
                                        >
                                            Persen (%)
                                        </option>
                                    </select>

                                </div>

                                <div class="discount-field">

                                    <label>
                                        Nilai Diskon
                                    </label>

                                    <div class="discount-input-wrap">

                                        <span
                                            class="discount-prefix"
                                            data-discount-prefix
                                        >
                                            $
                                        </span>

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            name="products[{{ $index }}][discount_value]"
                                            class="form-control discount-value-input"
                                            value="{{ old("products.$index.discount_value") }}"
                                            placeholder="0.00"
                                            data-discount-value
                                            {{ $selected ? '' : 'disabled' }}
                                        >

                                    </div>

                                    <input
                                        type="hidden"
                                        name="products[{{ $index }}][product_id]"
                                        value="{{ $product->id }}"
                                        data-product-id
                                        {{ $selected ? '' : 'disabled' }}
                                    >

                                </div>

                                <div class="price-preview">

                                    <div>
                                        Harga:
                                        <span class="price-normal">
                                            ${{ number_format($normalPrice, 2) }}
                                        </span>
                                    </div>

                                    <div>
                                        Promo:
                                        <span
                                            class="price-promo"
                                            data-promo-price
                                        >
                                            ${{ number_format($normalPrice, 2) }}
                                        </span>
                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="empty-search">
                                Belum ada produk tersedia.
                            </div>

                        @endforelse

                    </div>

                    <div
                        id="emptySearch"
                        class="empty-search"
                    >
                        Produk tidak ditemukan.
                    </div>

                </div>

            </div>

            <div class="sticky-actions">

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
                    Simpan Campaign
                </button>

            </div>

        </form>

    </main>

</div>

<script>
    const productRows =
        Array.from(
            document.querySelectorAll(
                '[data-product-row]'
            )
        );

    const productSearch =
        document.getElementById(
            'productSearch'
        );

    const selectedCounter =
        document.getElementById(
            'selectedCounter'
        );

    const selectAllButton =
        document.getElementById(
            'selectAllButton'
        );

    const clearAllButton =
        document.getElementById(
            'clearAllButton'
        );

    const emptySearch =
        document.getElementById(
            'emptySearch'
        );

    function updateSelectedCounter() {
        const selectedCount =
            productRows.filter(
                function (row) {
                    return row
                        .querySelector(
                            '[data-product-checkbox]'
                        )
                        .checked;
                }
            ).length;

        selectedCounter.textContent =
            selectedCount
            + ' Produk Dipilih';
    }

    function updatePricePreview(row) {
        const discountType =
            row.querySelector(
                '[data-discount-type]'
            );

        const discountValue =
            row.querySelector(
                '[data-discount-value]'
            );

        const promoPrice =
            row.querySelector(
                '[data-promo-price]'
            );

        const prefix =
            row.querySelector(
                '[data-discount-prefix]'
            );

        const normalPrice =
            parseFloat(
                row.dataset.normalPrice
            ) || 0;

        const value =
            parseFloat(
                discountValue.value
            ) || 0;

        let finalPrice =
            normalPrice;

        if (
            discountType.value
            === 'percent'
        ) {
            finalPrice =
                normalPrice
                - (
                    normalPrice
                    * value
                    / 100
                );

            prefix.textContent =
                '%';
        } else {
            finalPrice =
                normalPrice
                - value;

            prefix.textContent =
                '$';
        }

        if (finalPrice < 0) {
            finalPrice = 0;
        }

        promoPrice.textContent =
            '$'
            + finalPrice.toFixed(2);
    }

    function updateProductRow(row) {
        const checkbox =
            row.querySelector(
                '[data-product-checkbox]'
            );

        const productId =
            row.querySelector(
                '[data-product-id]'
            );

        const discountType =
            row.querySelector(
                '[data-discount-type]'
            );

        const discountValue =
            row.querySelector(
                '[data-discount-value]'
            );

        const selected =
            checkbox.checked;

        row.classList.toggle(
            'selected',
            selected
        );

        productId.disabled =
            !selected;

        discountType.disabled =
            !selected;

        discountValue.disabled =
            !selected;

        discountValue.required =
            selected;

        updatePricePreview(row);
        updateSelectedCounter();
    }

    productRows.forEach(
        function (row) {
            const checkbox =
                row.querySelector(
                    '[data-product-checkbox]'
                );

            const discountType =
                row.querySelector(
                    '[data-discount-type]'
                );

            const discountValue =
                row.querySelector(
                    '[data-discount-value]'
                );

            checkbox.addEventListener(
                'change',
                function () {
                    updateProductRow(row);
                }
            );

            discountType.addEventListener(
                'change',
                function () {
                    updatePricePreview(row);
                }
            );

            discountValue.addEventListener(
                'input',
                function () {
                    updatePricePreview(row);
                }
            );

            updateProductRow(row);
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

            let visibleCount = 0;

            productRows.forEach(
                function (row) {
                    const productName =
                        row.dataset.productName;

                    const visible =
                        productName.includes(
                            keyword
                        );

                    row.classList.toggle(
                        'hidden-product',
                        !visible
                    );

                    if (visible) {
                        visibleCount++;
                    }
                }
            );

            emptySearch.style.display =
                visibleCount === 0
                    ? 'block'
                    : 'none';
        }
    );

    selectAllButton.addEventListener(
        'click',
        function () {
            productRows.forEach(
                function (row) {
                    if (
                        row.classList.contains(
                            'hidden-product'
                        )
                    ) {
                        return;
                    }

                    const checkbox =
                        row.querySelector(
                            '[data-product-checkbox]'
                        );

                    checkbox.checked =
                        true;

                    updateProductRow(row);
                }
            );
        }
    );

    clearAllButton.addEventListener(
        'click',
        function () {
            productRows.forEach(
                function (row) {
                    const checkbox =
                        row.querySelector(
                            '[data-product-checkbox]'
                        );

                    checkbox.checked =
                        false;

                    updateProductRow(row);
                }
            );
        }
    );

    const campaignForm =
        document.getElementById(
            'campaignForm'
        );

    campaignForm.addEventListener(
        'submit',
        function (event) {
            const selectedCount =
                productRows.filter(
                    function (row) {
                        return row
                            .querySelector(
                                '[data-product-checkbox]'
                            )
                            .checked;
                    }
                ).length;

            if (selectedCount === 0) {
                event.preventDefault();

                alert(
                    'Pilih minimal satu produk untuk Promo Campaign.'
                );
            }
        }
    );


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
</script>

</body>
</html>