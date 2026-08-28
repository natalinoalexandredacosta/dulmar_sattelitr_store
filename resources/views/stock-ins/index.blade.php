<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Masuk - Dulmar Satellite Store</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-dulmar.jpg') }}">

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f6f9; }
        body.menu-open { overflow: hidden; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 245px; min-height: 100vh; display: flex; flex-shrink: 0; flex-direction: column; padding: 35px 25px; background: #1f2b3a; color: white; }
        .sidebar h1 { margin: 0 0 55px; font-size: 28px; }
        .sidebar-menu { flex: 1; }
        .sidebar-menu a { display: block; margin-bottom: 30px; color: white; font-size: 18px; text-decoration: none; }
        .sidebar-menu a:hover { color: #60a5fa; }
        .sidebar-menu a.active { padding: 12px 14px; border-left: 4px solid #60a5fa; border-radius: 6px; background: rgba(37, 99, 235, .3); color: #bfdbfe; font-weight: bold; }
        .button-logout { width: 100%; padding: 13px 15px; border: none; border-radius: 7px; background: #dc2626; color: white; font-size: 17px; cursor: pointer; }
        .main-content { flex: 1; min-width: 0; padding: 50px 32px; overflow-x: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 35px; }
        .page-header h2 { margin: 0 0 20px; font-size: 36px; }
        .page-header p { margin: 0; color: #4b5563; font-size: 18px; }
        .button-add { display: inline-block; flex-shrink: 0; padding: 16px 22px; border-radius: 8px; background: #16a34a; color: white; font-size: 18px; text-decoration: none; }
        .button-add:hover { background: #15803d; }
        .alert-success, .alert-error { margin-bottom: 25px; padding: 15px 20px; border-radius: 6px; }
        .alert-success { border: 1px solid #86efac; background: #dcfce7; color: #166534; }
        .alert-error { border: 1px solid #fca5a5; background: #fee2e2; color: #991b1b; }
        .alert-error ul { margin: 0; padding-left: 20px; }
        .filter-card { margin-bottom: 25px; padding: 22px; border-radius: 10px; background: white; box-shadow: 0 2px 8px rgba(0, 0, 0, .06); }
        .filter-card h3 { margin: 0 0 18px; font-size: 20px; }
        .filter-form { display: grid; grid-template-columns: minmax(220px, 2fr) repeat(2, minmax(170px, 1fr)) auto auto; align-items: end; gap: 12px; }
        .form-group label { display: block; margin-bottom: 7px; color: #374151; font-size: 14px; font-weight: bold; }
        .form-control { width: 100%; height: 44px; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; background: white; font-size: 15px; }
        .form-control:focus { border-color: #16a34a; outline: none; box-shadow: 0 0 0 3px rgba(22, 163, 74, .12); }
        .button-filter, .button-reset { height: 44px; display: inline-flex; align-items: center; justify-content: center; padding: 0 18px; border: none; border-radius: 6px; color: white; font-size: 15px; text-decoration: none; cursor: pointer; }
        .button-filter { background: #16a34a; }
        .button-reset { background: #6b7280; }
        .filter-info { margin: 15px 0 0; color: #6b7280; font-size: 14px; }
        .summary-grid { display: grid; grid-template-columns: repeat(3, minmax(180px, 1fr)); gap: 18px; margin-bottom: 25px; }
        .summary-card { padding: 22px; border-radius: 10px; background: white; box-shadow: 0 2px 8px rgba(0, 0, 0, .06); }
        .summary-card h3 { margin: 0 0 12px; color: #6b7280; font-size: 15px; }
        .summary-card strong { display: block; font-size: 27px; }
        .summary-transactions { border-left: 5px solid #2563eb; }
        .summary-quantity { border-left: 5px solid #16a34a; }
        .summary-purchase { border-left: 5px solid #f59e0b; }
        .value-transactions { color: #2563eb; }
        .value-quantity { color: #16a34a; }
        .value-purchase { color: #d97706; }
        .table-card { width: 100%; overflow-x: auto; border-radius: 10px; background: white; box-shadow: 0 2px 8px rgba(0, 0, 0, .06); -webkit-overflow-scrolling: touch; }
        table { width: 100%; min-width: 1000px; border-collapse: collapse; }
        thead { background: #edf2f7; }
        th, td { padding: 18px; border-bottom: 1px solid #d1d5db; font-size: 16px; text-align: left; }
        .quantity { color: #15803d; font-weight: bold; }
        .empty-data { padding: 30px; color: #6b7280; text-align: center; }
        .action-buttons { display: flex; align-items: center; gap: 8px; }
        .button-edit, .button-delete { padding: 9px 15px; border: none; border-radius: 6px; color: white; font-size: 14px; cursor: pointer; }
        .button-edit { display: inline-block; background: #f59e0b; text-decoration: none; }
        .button-delete { background: #dc2626; }
        .delete-form { margin: 0; }
        .pagination { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 18px; padding: 14px 16px; border-radius: 8px; background: white; box-shadow: 0 2px 8px rgba(0, 0, 0, .06); }
        .pagination a, .pagination .disabled { padding: 9px 15px; border-radius: 6px; text-decoration: none; }
        .pagination a { background: #16a34a; color: white; }
        .pagination .disabled { background: #e5e7eb; color: #9ca3af; }
        .pagination-info { color: #4b5563; font-size: 14px; text-align: center; }
        .chart-card { margin: 35px 0; padding: 30px; border-radius: 10px; background: white; box-shadow: 0 2px 8px rgba(0, 0, 0, .08); }
        .chart-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 25px; }
        .chart-header h3 { margin: 0 0 8px; font-size: 24px; }
        .chart-header p { margin: 0; color: #6b7280; font-size: 15px; }
        .chart-total { padding: 12px 18px; border-radius: 8px; background: #dcfce7; color: #166534; text-align: center; }
        .chart-total span { display: block; margin-bottom: 4px; font-size: 13px; }
        .chart-total strong { font-size: 24px; }
        .chart-container { position: relative; width: 100%; max-width: 370px; height: 300px; margin: 0 auto; }
        .chart-empty { padding: 50px 20px; color: #6b7280; text-align: center; }
        .sidebar-toggle, .sidebar-overlay { display: none; }

        @media (max-width: 900px) {
            .summary-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 700px) {
            body { overflow-x: hidden; }
            .container { display: block; width: 100%; }

            .sidebar-toggle {
                position: fixed;
                top: 14px;
                left: 14px;
                z-index: 1002;
                width: 48px;
                height: 48px;
                display: flex;
                justify-content: center;
                align-items: center;
                border: none;
                border-radius: 8px;
                background: #1f2b3a;
                color: white;
                font-size: 26px;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 1000;
                display: block;
                visibility: hidden;
                background: rgba(0, 0, 0, .45);
                opacity: 0;
                transition: .3s;
            }

            .sidebar-overlay.overlay-open {
                visibility: visible;
                opacity: 1;
            }

            .sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 1001;
                width: min(82vw, 310px);
                height: 100vh;
                padding: 82px 24px 30px;
                overflow-y: auto;
                transform: translateX(-105%);
                transition: transform .3s ease;
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .sidebar h1 {
                margin-bottom: 32px;
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
                padding: 80px 14px 30px;
                overflow-x: hidden;
            }

            .page-header,
            .chart-header {
                flex-direction: column;
            }

            .page-header h2 {
                font-size: 30px;
            }

            .button-add,
            .chart-total {
                width: 100%;
                text-align: center;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            .button-filter,
            .button-reset {
                width: 100%;
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination-info {
                width: 100%;
                order: -1;
            }

            .chart-card {
                padding: 20px 14px;
            }

            .chart-container {
                max-width: 280px;
                height: 260px;
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
        <aside class="sidebar" id="sidebar">
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
                    <h2>Stok Masuk</h2>
                    <p>
                        Kelola transaksi barang yang masuk ke persediaan.
                    </p>
                </div>

                <a
                    href="{{ route('stock-ins.create') }}"
                    class="button-add"
                >
                    + Tambah Stok Masuk
                </a>
            </div>

            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="filter-card">
                <h3>Pencarian dan Filter Transaksi</h3>

                <form
                    action="{{ route('stock-ins.index') }}"
                    method="GET"
                    class="filter-form"
                >
                    <div class="form-group">
                        <label for="search">
                            Cari produk, supplier, atau catatan
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control"
                            value="{{ $search ?? '' }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="start_date">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="form-control"
                            value="{{ $startDate ?? '' }}"
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
                            value="{{ $endDate ?? '' }}"
                        >
                    </div>

                    <button
                        type="submit"
                        class="button-filter"
                    >
                        Terapkan
                    </button>

                    <a
                        href="{{ route('stock-ins.index') }}"
                        class="button-reset"
                    >
                        Reset
                    </a>
                </form>

                @if (($search ?? '') || ($startDate ?? '') || ($endDate ?? ''))
                    <p class="filter-info">
                        Menampilkan {{ $stockIns->total() }}
                        transaksi sesuai filter.
                    </p>
                @endif
            </section>

            <section class="summary-grid">
                <article class="summary-card summary-transactions">
                    <h3>Jumlah Transaksi</h3>
                    <strong class="value-transactions">
                        {{ $totalTransactions }}
                    </strong>
                </article>

                <article class="summary-card summary-quantity">
                    <h3>Total Barang Masuk</h3>
                    <strong class="value-quantity">
                        {{ $totalStockIn }} unit
                    </strong>
                </article>

                <article class="summary-card summary-purchase">
                    <h3>Estimasi Total Pembelian</h3>
                    <strong class="value-purchase">
                        ${{ number_format($totalPurchase, 2) }}
                    </strong>
                </article>
            </section>

            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Produk</th>
                            <th>Supplier</th>
                            <th>Jumlah</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($stockIns as $stockIn)
                            <tr>
                                <td>
                                    {{ $stockIns->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $stockIn->transaction_date->format('d-m-Y') }}
                                </td>

                                <td>
                                    {{ $stockIn->product?->product_name ?? 'Produk telah dihapus' }}
                                </td>

                                <td>
                                    {{ $stockIn->supplier?->supplier_name ?? '-' }}
                                </td>

                                <td class="quantity">
                                    +{{ $stockIn->quantity }} unit
                                </td>

                                <td>
                                    {{ $stockIn->notes ?: '-' }}
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <a
                                            href="{{ route('stock-ins.edit', $stockIn) }}"
                                            class="button-edit"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('stock-ins.destroy', $stockIn) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Jumlah barang akan dikurangi dari stok.')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="button-delete"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="7"
                                    class="empty-data"
                                >
                                    Tidak ada transaksi stok masuk yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($stockIns->hasPages())
                <nav class="pagination">
                    @if ($stockIns->onFirstPage())
                        <span class="disabled">
                            Sebelumnya
                        </span>
                    @else
                        <a href="{{ $stockIns->previousPageUrl() }}">
                            Sebelumnya
                        </a>
                    @endif

                    <span class="pagination-info">
                        Halaman {{ $stockIns->currentPage() }}
                        dari {{ $stockIns->lastPage() }}
                    </span>

                    @if ($stockIns->hasMorePages())
                        <a href="{{ $stockIns->nextPageUrl() }}">
                            Berikutnya
                        </a>
                    @else
                        <span class="disabled">
                            Berikutnya
                        </span>
                    @endif
                </nav>
            @endif

            <section class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3>Grafik Barang Masuk</h3>
                        <p>
                            Perbandingan jumlah barang masuk berdasarkan produk.
                        </p>
                    </div>

                    <div class="chart-total">
                        <span>Total Barang Masuk</span>
                        <strong>
                            {{ $totalStockIn }} unit
                        </strong>
                    </div>
                </div>

                @if ($chartValues->sum() > 0)
                    <div class="chart-container">
                        <canvas id="stockInPieChart"></canvas>
                    </div>
                @else
                    <div class="chart-empty">
                        Belum ada data barang masuk untuk ditampilkan.
                    </div>
                @endif
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const sidebarToggle =
            document.getElementById('sidebarToggle');

        const sidebar =
            document.getElementById('sidebar');

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
                const open =
                    sidebar.classList.toggle('sidebar-open');

                sidebarOverlay.classList.toggle(
                    'overlay-open',
                    open
                );

                sidebarToggle.textContent =
                    open ? '✕' : '☰';

                sidebarToggle.setAttribute(
                    'aria-expanded',
                    open ? 'true' : 'false'
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

        document
            .querySelectorAll('.sidebar a')
            .forEach(function (link) {
                link.addEventListener(
                    'click',
                    closeSidebar
                );
            });

        window.addEventListener(
            'resize',
            function () {
                if (window.innerWidth > 700) {
                    closeSidebar();
                }
            }
        );

        @if ($chartValues->sum() > 0)
            const chartLabels =
                {{ Illuminate\Support\Js::from($chartLabels) }};

            const chartValues =
                {{ Illuminate\Support\Js::from($chartValues) }};

            const colors = [
                '#16a34a',
                '#2563eb',
                '#f59e0b',
                '#7c3aed',
                '#0891b2',
                '#db2777',
                '#65a30d',
                '#ea580c',
                '#4f46e5',
                '#dc2626'
            ];

            new Chart(
                document.getElementById('stockInPieChart'),
                {
                    type: 'doughnut',

                    data: {
                        labels: chartLabels,

                        datasets: [
                            {
                                data: chartValues,
                                backgroundColor:
                                    chartLabels.map(
                                        (_, i) =>
                                            colors[i % colors.length]
                                    ),
                                borderColor: '#fff',
                                borderWidth: 3
                            }
                        ]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '55%',

                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                }
            );
        @endif
    </script>
</body>
</html>