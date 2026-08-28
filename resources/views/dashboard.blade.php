<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard - Dulmar Satellite Store</title>

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
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 900;
            width: 245px;
            display: flex;
            flex-direction: column;
            padding: 30px 25px;
            overflow-y: auto;
            background-color: #1f2b3a;
            color: white;
        }

        .sidebar h1 {
            margin: 0 0 35px;
            font-size: 27px;
        }

        .sidebar-menu {
            flex: 0 0 auto;
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
            color: #bfdbfe;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .sidebar-menu a.active {
            padding-left: 14px;
            border-left: 4px solid #60a5fa;
            background-color: rgba(37, 99, 235, 0.3);
            color: #bfdbfe;
            font-weight: bold;
        }

        .logout-form {
            margin-top: 20px;
        }

        .button-logout {
            width: 100%;
            padding: 13px 15px;
            border: none;
            border-radius: 7px;
            background-color: #dc2626;
            color: white;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
        }

        .button-logout:hover {
            background-color: #b91c1c;
        }

        .main-content {
            width: calc(100% - 245px);
            min-width: 0;
            margin-left: 245px;
            padding: 45px 32px;
        }

        .page-header {
            margin-bottom: 35px;
        }

        .page-header h2 {
            margin: 0 0 12px;
            font-size: 36px;
        }

        .page-header p {
            margin: 0;
            color: #4b5563;
            font-size: 18px;
        }

        .alert-success {
            margin-bottom: 30px;
            padding: 15px 20px;
            border: 1px solid #86efac;
            border-radius: 7px;
            background-color: #dcfce7;
            color: #166534;
            font-size: 16px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(180px, 1fr));
            gap: 22px;
            margin-bottom: 35px;
        }

        .summary-card {
            padding: 25px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .summary-card h3 {
            margin: 0 0 18px;
            color: #4b5563;
            font-size: 17px;
        }

        .summary-card .value {
            margin: 0;
            font-size: 36px;
            font-weight: bold;
        }

        .card-products {
            border-left: 6px solid #2563eb;
        }

        .card-stock {
            border-left: 6px solid #7c3aed;
        }

        .card-stock-in {
            border-left: 6px solid #16a34a;
        }

        .card-stock-out {
            border-left: 6px solid #dc2626;
        }

        .value-products {
            color: #2563eb;
        }

        .value-stock {
            color: #7c3aed;
        }

        .value-stock-in {
            color: #16a34a;
        }

        .value-stock-out {
            color: #dc2626;
        }

        .chart-card {
            margin-bottom: 35px;
            padding: 28px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .chart-card h3 {
            margin: 0 0 8px;
            font-size: 23px;
        }

        .chart-card p {
            margin: 0 0 25px;
            color: #6b7280;
            font-size: 16px;
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: 350px;
        }

        .quick-actions {
            padding: 28px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .quick-actions h3 {
            margin: 0 0 23px;
            font-size: 23px;
        }

        .action-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .action-button {
            display: inline-flex;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border-radius: 7px;
            color: white;
            font-size: 15px;
            text-decoration: none;
        }

        .action-product {
            background-color: #2563eb;
        }

        .action-product:hover {
            background-color: #1d4ed8;
        }

        .action-stock-in {
            background-color: #16a34a;
        }

        .action-stock-in:hover {
            background-color: #15803d;
        }

        .action-stock-out {
            background-color: #dc2626;
        }

        .action-stock-out:hover {
            background-color: #b91c1c;
        }

        .action-voucher {
            background-color: #7c3aed;
        }

        .action-voucher:hover {
            background-color: #6d28d9;
        }

        .action-report {
            background-color: #374151;
        }

        .action-report:hover {
            background-color: #1f2937;
        }

        .sidebar-toggle,
        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1100px) {
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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
                padding: 0;
                border: none;
                border-radius: 8px;
                background-color: #1f2b3a;
                color: white;
                font-size: 25px;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 1000;
                display: block;
                visibility: hidden;
                background-color: rgba(0, 0, 0, 0.5);
                opacity: 0;
                transition:
                    opacity 0.25s,
                    visibility 0.25s;
            }

            .sidebar-overlay.overlay-open {
                visibility: visible;
                opacity: 1;
            }

            .sidebar {
                z-index: 1100;
                width: min(82vw, 285px);
                padding-top: 82px;
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

            .page-header p {
                font-size: 16px;
                line-height: 1.5;
            }

            .summary-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .summary-card {
                padding: 22px;
            }

            .chart-card,
            .quick-actions {
                padding: 20px 15px;
            }

            .chart-container {
                height: 300px;
            }

            .action-list {
                display: grid;
                grid-template-columns: 1fr;
            }

            .action-button {
                width: 100%;
                text-align: center;
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
            <header class="page-header">
                <h2>Dashboard</h2>

                <p>
                    Ringkasan inventori Dulmar Satellite Store.
                </p>
            </header>

            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <section class="summary-grid">
                <article class="summary-card card-products">
                    <h3>Total Produk</h3>

                    <p class="value value-products">
                        {{ $totalProducts }}
                    </p>
                </article>

                <article class="summary-card card-stock">
                    <h3>Total Stok Tersedia</h3>

                    <p class="value value-stock">
                        {{ $totalStock }}
                    </p>
                </article>

                <article class="summary-card card-stock-in">
                    <h3>Total Stok Masuk</h3>

                    <p class="value value-stock-in">
                        {{ $totalStockIn }}
                    </p>
                </article>

                <article class="summary-card card-stock-out">
                    <h3>Total Stok Keluar</h3>

                    <p class="value value-stock-out">
                        {{ $totalStockOut }}
                    </p>
                </article>
            </section>

            <section class="chart-card">
                <h3>Grafik Pergerakan Stok</h3>

                <p>
                    Perbandingan stok masuk dan stok keluar
                    selama 7 hari terakhir.
                </p>

                <div class="chart-container">
                    <canvas
                        id="stockChart"
                        role="img"
                        aria-label="Grafik stok masuk dan stok keluar selama tujuh hari terakhir"
                    ></canvas>
                </div>
            </section>

            <section class="quick-actions">
                <h3>Aksi Cepat</h3>

                <div class="action-list">
                    <a
                        href="{{ route('products.create') }}"
                        class="action-button action-product"
                    >
                        + Tambah Produk
                    </a>

                    <a
                        href="{{ route('stock-ins.create') }}"
                        class="action-button action-stock-in"
                    >
                        + Tambah Stok Masuk
                    </a>

                    <a
                        href="{{ route('stock-outs.create') }}"
                        class="action-button action-stock-out"
                    >
                        + Tambah Stok Keluar
                    </a>

                    <a
                        href="{{ route('tv-vouchers.create') }}"
                        class="action-button action-voucher"
                    >
                        + Tambah TV Voucher
                    </a>

                    <a
                        href="{{ route('tv-vouchers.report') }}"
                        class="action-button action-report"
                    >
                        Laporan TV Voucher
                    </a>
                </div>
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

        function toggleSidebar() {
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
            toggleSidebar
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

        window.addEventListener('resize', function () {
            if (window.innerWidth > 700) {
                closeSidebar();
            }
        });

        const chartElement =
            document.getElementById('stockChart');

        if (chartElement) {
            new Chart(chartElement, {
                type: 'bar',

                data: {
                    labels: @json($chartLabels),

                    datasets: [
                        {
                            label: 'Stok Masuk',
                            data: @json($chartStockIn),
                            backgroundColor:
                                'rgba(22, 163, 74, 0.75)',
                            borderColor: '#16a34a',
                            borderWidth: 1,
                            borderRadius: 5
                        },
                        {
                            label: 'Stok Keluar',
                            data: @json($chartStockOut),
                            backgroundColor:
                                'rgba(220, 38, 38, 0.75)',
                            borderColor: '#dc2626',
                            borderWidth: 1,
                            borderRadius: 5
                        }
                    ]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            position: 'top'
                        },

                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label
                                        + ': '
                                        + context.raw
                                        + ' unit';
                                }
                            }
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,

                            ticks: {
                                precision: 0
                            },

                            title: {
                                display: true,
                                text: 'Jumlah Barang'
                            }
                        },

                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>