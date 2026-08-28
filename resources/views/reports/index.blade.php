<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris - Dulmar Satellite Store</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-dulmar.jpg') }}">

    <style>
        * { box-sizing: border-box; }

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
            background: rgba(37, 99, 235, .3);
            color: #bfdbfe;
            font-weight: bold;
        }

        .button-logout {
            width: 100%;
            padding: 13px 15px;
            border: 0;
            border-radius: 7px;
            background: #dc2626;
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
            padding: 50px 32px;
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
            margin: 0 0 15px;
            font-size: 36px;
        }

        .page-header p {
            margin: 0;
            color: #4b5563;
            font-size: 18px;
        }

        .page-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .button-print,
        .button-export {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 22px;
            border: 0;
            border-radius: 7px;
            color: white;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .button-print {
            background: #374151;
        }

        .button-export {
            background: #16a34a;
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

        .filter-card,
        .report-card {
            margin-bottom: 30px;
            padding: 25px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        .filter-card h3,
        .report-card h3 {
            margin: 0 0 20px;
        }

        .filter-form {
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-group {
            min-width: 220px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            height: 44px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
        }

        .button-filter,
        .button-reset {
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 19px;
            border: 0;
            border-radius: 6px;
            color: white;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
        }

        .button-filter {
            background: #2563eb;
        }

        .button-reset {
            background: #6b7280;
        }

        .period-info {
            margin: 18px 0 0;
            color: #4b5563;
            font-size: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(170px, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .financial-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(170px, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .summary-card {
            padding: 22px;
            border-radius: 9px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
        }

        .summary-card h3 {
            margin: 0 0 12px;
            color: #6b7280;
            font-size: 15px;
        }

        .summary-value {
            margin: 0;
            font-size: 27px;
            font-weight: bold;
        }

        .blue {
            border-left: 5px solid #2563eb;
        }

        .blue .summary-value {
            color: #2563eb;
        }

        .purple {
            border-left: 5px solid #7c3aed;
        }

        .purple .summary-value {
            color: #7c3aed;
        }

        .green {
            border-left: 5px solid #16a34a;
        }

        .green .summary-value {
            color: #16a34a;
        }

        .red {
            border-left: 5px solid #dc2626;
        }

        .red .summary-value {
            color: #dc2626;
        }

        .orange {
            border-left: 5px solid #f59e0b;
        }

        .orange .summary-value {
            color: #d97706;
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: 360px;
        }

        .chart-empty,
        .empty-data {
            padding: 30px;
            color: #6b7280;
            text-align: center;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            border-radius: 7px;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 1200px;
            border-collapse: collapse;
        }

        .inventory-table {
            min-width: 1050px;
        }

        thead {
            background: #edf2f7;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #d1d5db;
            font-size: 14px;
            text-align: left;
            white-space: nowrap;
        }

        .stock-in,
        .profit {
            color: #15803d;
            font-weight: bold;
        }

        .stock-out {
            color: #dc2626;
            font-weight: bold;
        }

        .current-stock {
            color: #7c3aed;
            font-weight: bold;
        }

        .purchase-price {
            color: #dc2626;
        }

        .selling-price,
        .total-sale {
            color: #2563eb;
            font-weight: bold;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 18px;
            padding: 14px 16px;
            border-radius: 8px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .pagination a,
        .pagination .disabled {
            padding: 9px 15px;
            border-radius: 6px;
            text-decoration: none;
        }

        .pagination a {
            background: #2563eb;
            color: white;
        }

        .pagination .disabled {
            background: #e5e7eb;
            color: #9ca3af;
        }

        .pagination-info {
            color: #4b5563;
            font-size: 14px;
            text-align: center;
        }

        @media (max-width: 1250px) {
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .financial-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {
            body {
                overflow-x: hidden;
            }

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
                border: 0;
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
                background: rgba(0, 0, 0, .5);
                opacity: 0;
                transition: .25s;
            }

            .sidebar-overlay.overlay-open {
                visibility: visible;
                opacity: 1;
            }

            .sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 1100;
                width: min(82vw, 285px);
                padding: 82px 25px 30px;
                overflow-y: auto;
                transform: translateX(-105%);
                transition: transform .25s ease;
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
                background: rgba(255, 255, 255, .06);
                font-size: 16px;
            }

            .main-content {
                width: 100%;
                padding: 85px 14px 30px;
            }

            .page-header {
                flex-direction: column;
            }

            .page-header h2 {
                font-size: 30px;
            }

            .page-actions,
            .button-export,
            .button-print {
                width: 100%;
            }

            .filter-form {
                display: grid;
                grid-template-columns: 1fr;
            }

            .form-group,
            .button-filter,
            .button-reset {
                width: 100%;
                min-width: 0;
            }

            .summary-grid,
            .financial-grid {
                grid-template-columns: 1fr;
            }

            .filter-card,
            .report-card {
                padding: 18px 14px;
            }

            .chart-container {
                height: 300px;
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination-info {
                width: 100%;
                order: -1;
            }
        }

        @media (min-width: 701px) {
            html,
            body {
                width: 100%;
                height: 100%;
                overflow: hidden;
            }

            .container {
                height: 100vh;
                min-height: 0;
                overflow: hidden;
            }

            .sidebar {
                position: sticky;
                top: 0;
                height: 100vh;
                min-height: 0;
                overflow-y: auto;
            }

            .sidebar-menu {
                flex: none;
            }

            .sidebar form {
                margin-top: 4px;
            }

            .main-content {
                height: 100vh;
                padding-top: 0;
                overflow-x: hidden;
                overflow-y: auto;
            }

            .page-header {
                position: sticky;
                top: 0;
                z-index: 100;
                margin: 0 -32px 30px;
                padding: 24px 32px 20px;
                border-bottom: 1px solid #d1d5db;
                background-color: #f4f6f9;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
            }
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 8mm;
            }

            body {
                background: white;
            }

            .sidebar,
            .sidebar-toggle,
            .sidebar-overlay,
            .page-actions,
            .filter-form,
            .pagination {
                display: none !important;
            }

            .main-content {
                width: 100%;
                padding: 0;
            }

            .filter-card,
            .summary-card,
            .report-card {
                box-shadow: none;
                border: 1px solid #d1d5db;
            }

            .summary-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .financial-grid {
                grid-template-columns: repeat(5, 1fr);
            }

            .table-wrapper {
                overflow: visible;
            }

            table {
                width: 100%;
                min-width: 0;
            }

            th,
            td {
                padding: 4px;
                font-size: 8px;
                white-space: normal;
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
            <h1>Dulmar Satellite Store</h1>

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
                <div>
                    <h2>Laporan Inventaris</h2>

                    <p>
                        Ringkasan pergerakan stok, penjualan, dan keuntungan.
                    </p>
                </div>

                <div class="page-actions">
                    <a
                        href="{{ route('reports.export-excel', [
                            'start_date' => $startDate,
                            'end_date' => $endDate
                        ]) }}"
                        class="button-export"
                    >
                        Export Excel
                    </a>

                    <button
                        type="button"
                        class="button-print"
                        onclick="window.print()"
                    >
                        Cetak Laporan
                    </button>
                </div>
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

            <section class="filter-card">
                <h3>Filter Periode Laporan</h3>

                <form
                    action="{{ route('reports.index') }}"
                    method="GET"
                    class="filter-form"
                >
                    <div class="form-group">
                        <label for="start_date">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="form-control"
                            value="{{ $startDate }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="end_date">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            class="form-control"
                            value="{{ $endDate }}"
                        >
                    </div>

                    <button
                        type="submit"
                        class="button-filter"
                    >
                        Terapkan
                    </button>

                    <a
                        href="{{ route('reports.index') }}"
                        class="button-reset"
                    >
                        Reset
                    </a>
                </form>

                <p class="period-info">
                    Periode:
                    <strong>
                        {{ $startDate
                            ? \Carbon\Carbon::parse($startDate)->format('d-m-Y')
                            : 'Semua tanggal'
                        }}
                    </strong>

                    sampai

                    <strong>
                        {{ $endDate
                            ? \Carbon\Carbon::parse($endDate)->format('d-m-Y')
                            : 'Hari ini'
                        }}
                    </strong>
                </p>
            </section>

            <section class="summary-grid">
                <article class="summary-card blue">
                    <h3>Total Produk</h3>
                    <p class="summary-value">
                        {{ $totalProducts }}
                    </p>
                </article>

                <article class="summary-card purple">
                    <h3>Stok Saat Ini</h3>
                    <p class="summary-value">
                        {{ $totalCurrentStock }} unit
                    </p>
                </article>

                <article class="summary-card orange">
                    <h3>Stok Rendah</h3>
                    <p class="summary-value">
                        {{ $lowStockProducts }}
                    </p>
                </article>

                <article class="summary-card red">
                    <h3>Stok Habis</h3>
                    <p class="summary-value">
                        {{ $outOfStockProducts }}
                    </p>
                </article>

                <article class="summary-card green">
                    <h3>Total Stok Masuk</h3>
                    <p class="summary-value">
                        {{ $totalStockIn }} unit
                    </p>
                </article>

                <article class="summary-card red">
                    <h3>Total Stok Keluar</h3>
                    <p class="summary-value">
                        {{ $totalStockOut }} unit
                    </p>
                </article>

                <article class="summary-card blue">
                    <h3>Transaksi Masuk</h3>
                    <p class="summary-value">
                        {{ $totalStockInTransactions }}
                    </p>
                </article>

                <article class="summary-card purple">
                    <h3>Transaksi Keluar</h3>
                    <p class="summary-value">
                        {{ $totalStockOutTransactions }}
                    </p>
                </article>
            </section>

            <section class="financial-grid">
                <article class="summary-card blue">
                    <h3>Total Penjualan</h3>
                    <p class="summary-value">
                        ${{ number_format($totalSales, 2) }}
                    </p>
                </article>

                <article class="summary-card orange">
                    <h3>Total Modal Terjual</h3>
                    <p class="summary-value">
                        ${{ number_format($totalCapital, 2) }}
                    </p>
                </article>

                <article class="summary-card green">
                    <h3>Total Keuntungan</h3>
                    <p class="summary-value">
                        ${{ number_format($totalProfit, 2) }}
                    </p>
                </article>

                <article class="summary-card purple">
                    <h3>Margin Keuntungan</h3>
                    <p class="summary-value">
                        {{ number_format($profitMargin, 1) }}%
                    </p>
                </article>

                <article class="summary-card orange">
                    <h3>Nilai Stok Saat Ini</h3>
                    <p class="summary-value">
                        ${{ number_format($currentInventoryValue, 2) }}
                    </p>
                </article>
            </section>

            <section class="report-card">
                <h3>Grafik Penjualan dan Keuntungan</h3>

                @if ($chartSalesValues->sum() > 0)
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                @else
                    <div class="chart-empty">
                        Belum ada data penjualan pada periode yang dipilih.
                    </div>
                @endif
            </section>

            <section class="report-card">
                <h3>Transaksi Penjualan</h3>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Pelanggan</th>
                                <th>Jumlah</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Total Penjualan</th>
                                <th>Total Modal</th>
                                <th>Keuntungan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($sales as $sale)
                                @php
                                    $saleCapital =
                                        (float) $sale->unit_purchase_price
                                        * (int) $sale->quantity;
                                @endphp

                                <tr>
                                    <td>
                                        {{ $sales->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        {{ $sale->transaction_date->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        {{ $sale->product?->product_name ?? 'Produk telah dihapus' }}
                                    </td>

                                    <td>
                                        {{ $sale->customer?->customer_name ?? '-' }}
                                    </td>

                                    <td class="stock-out">
                                        {{ $sale->quantity }} unit
                                    </td>

                                    <td class="purchase-price">
                                        ${{ number_format($sale->unit_purchase_price, 2) }}
                                    </td>

                                    <td class="selling-price">
                                        ${{ number_format($sale->unit_selling_price, 2) }}
                                    </td>

                                    <td class="total-sale">
                                        ${{ number_format($sale->subtotal, 2) }}
                                    </td>

                                    <td>
                                        ${{ number_format($saleCapital, 2) }}
                                    </td>

                                    <td class="profit">
                                        ${{ number_format($sale->total_profit, 2) }}
                                    </td>

                                    <td>
                                        {{ $sale->notes ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="11"
                                        class="empty-data"
                                    >
                                        Tidak ada transaksi penjualan pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($sales->hasPages())
                    <nav class="pagination">
                        @if ($sales->onFirstPage())
                            <span class="disabled">
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $sales->previousPageUrl() }}">
                                Sebelumnya
                            </a>
                        @endif

                        <span class="pagination-info">
                            Halaman {{ $sales->currentPage() }}
                            dari {{ $sales->lastPage() }}
                            - Total {{ $sales->total() }} transaksi
                        </span>

                        @if ($sales->hasMorePages())
                            <a href="{{ $sales->nextPageUrl() }}">
                                Berikutnya
                            </a>
                        @else
                            <span class="disabled">
                                Berikutnya
                            </span>
                        @endif
                    </nav>
                @endif
            </section>

            <section class="report-card">
                <h3>Ringkasan Inventaris Produk</h3>

                <div class="table-wrapper">
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Stok Masuk</th>
                                <th>Stok Keluar</th>
                                <th>Stok Saat Ini</th>
                                <th>Nilai Stok</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $product->product_name }}
                                    </td>

                                    <td>
                                        {{ $product->category }}
                                    </td>

                                    <td class="purchase-price">
                                        ${{ number_format($product->purchase_price, 2) }}
                                    </td>

                                    <td class="selling-price">
                                        ${{ number_format($product->selling_price, 2) }}
                                    </td>

                                    <td class="stock-in">
                                        +{{ $product->total_stock_in ?? 0 }}
                                    </td>

                                    <td class="stock-out">
                                        -{{ $product->total_stock_out ?? 0 }}
                                    </td>

                                    <td class="current-stock">
                                        {{ $product->stock }} unit
                                    </td>

                                    <td>
                                        ${{ number_format(
                                            $product->stock * $product->purchase_price,
                                            2
                                        ) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="9"
                                        class="empty-data"
                                    >
                                        Belum ada data produk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const sidebar =
            document.getElementById('sidebar');

        const sidebarToggle =
            document.getElementById('sidebarToggle');

        const sidebarOverlay =
            document.getElementById('sidebarOverlay');

        function closeSidebar() {
            sidebar.classList.remove('sidebar-open');
            sidebarOverlay.classList.remove('overlay-open');
            sidebarToggle.textContent = '☰';

            sidebarToggle.setAttribute(
                'aria-expanded',
                'false'
            );

            document.body.classList.remove('menu-open');
        }

        sidebarToggle.addEventListener(
            'click',
            function () {
                const isOpen =
                    sidebar.classList.toggle('sidebar-open');

                sidebarOverlay.classList.toggle(
                    'overlay-open',
                    isOpen
                );

                sidebarToggle.textContent =
                    isOpen ? '✕' : '☰';

                sidebarToggle.setAttribute(
                    'aria-expanded',
                    isOpen ? 'true' : 'false'
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
            .querySelectorAll('.sidebar a')
            .forEach(function (link) {
                link.addEventListener(
                    'click',
                    closeSidebar
                );
            });

        @if ($chartSalesValues->sum() > 0)
            const chartLabels =
                {{ Illuminate\Support\Js::from($chartLabels) }};

            const chartSales =
                {{ Illuminate\Support\Js::from($chartSalesValues) }};

            const chartProfit =
                {{ Illuminate\Support\Js::from($chartProfitValues) }};

            new Chart(
                document.getElementById('salesChart'),
                {
                    type: 'line',

                    data: {
                        labels: chartLabels,

                        datasets: [
                            {
                                label: 'Penjualan',
                                data: chartSales,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37,99,235,.12)',
                                tension: .3,
                                fill: true
                            },
                            {
                                label: 'Keuntungan',
                                data: chartProfit,
                                borderColor: '#16a34a',
                                backgroundColor: 'rgba(22,163,74,.08)',
                                tension: .3,
                                fill: true
                            }
                        ]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },

                        scales: {
                            y: {
                                beginAtZero: true,

                                ticks: {
                                    callback: function (value) {
                                        return '$'
                                            + Number(value).toFixed(2);
                                    }
                                }
                            }
                        }
                    }
                }
            );
        @endif
    </script>
</body>
</html>