<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Produk - Dulmar Satellite Store</title>

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

        /* Sidebar */

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

        .button-logout:hover {
            background-color: #b91c1c;
        }

        .sidebar-toggle,
        .sidebar-overlay {
            display: none;
        }

        /* Konten utama */

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

        .button-add {
            display: inline-block;
            flex-shrink: 0;
            padding: 16px 22px;
            border-radius: 8px;
            background-color: #2563eb;
            color: white;
            font-size: 18px;
            text-decoration: none;
        }

        .button-add:hover {
            background-color: #1d4ed8;
        }

        /* Pesan */

        .alert-success,
        .alert-error {
            margin-bottom: 25px;
            padding: 15px 20px;
            border-radius: 6px;
            font-size: 16px;
        }

        .alert-success {
            border: 1px solid #86efac;
            background-color: #dcfce7;
            color: #166534;
        }

        .alert-error {
            border: 1px solid #fca5a5;
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        /* Pencarian dan filter */

        .filter-card {
            margin-bottom: 25px;
            padding: 22px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .filter-card h3 {
            margin: 0 0 18px;
            font-size: 21px;
        }

        .filter-form {
            display: grid;
            grid-template-columns:
                minmax(220px, 2fr)
                minmax(180px, 1fr)
                minmax(180px, 1fr)
                auto
                auto;
            align-items: end;
            gap: 12px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            color: #374151;
            font-size: 14px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            height: 45px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .button-filter,
        .button-reset {
            height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
        }

        .button-filter {
            background-color: #2563eb;
        }

        .button-filter:hover {
            background-color: #1d4ed8;
        }

        .button-reset {
            background-color: #6b7280;
        }

        .button-reset:hover {
            background-color: #4b5563;
        }

        .filter-info {
            margin: 15px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        /* Ringkasan */

        .summary-grid {
            display: grid;
            grid-template-columns:
                repeat(5, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .summary-card {
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .summary-card h3 {
            margin: 0 0 12px;
            color: #6b7280;
            font-size: 14px;
        }

        .summary-card strong {
            display: block;
            font-size: 26px;
        }

        .summary-total {
            border-left: 5px solid #2563eb;
        }

        .summary-stock {
            border-left: 5px solid #7c3aed;
        }

        .summary-available {
            border-left: 5px solid #16a34a;
        }

        .summary-low {
            border-left: 5px solid #f59e0b;
        }

        .summary-out {
            border-left: 5px solid #dc2626;
        }

        .value-total {
            color: #2563eb;
        }

        .value-stock {
            color: #7c3aed;
        }

        .value-available {
            color: #16a34a;
        }

        .value-low {
            color: #d97706;
        }

        .value-out {
            color: #dc2626;
        }

        /* Tabel */

        .table-card {
            width: 100%;
            overflow-x: auto;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 1150px;
            border-collapse: collapse;
            background-color: white;
        }

        thead {
            background-color: #edf2f7;
        }

        th,
        td {
            padding: 18px;
            border-bottom: 1px solid #d1d5db;
            font-size: 16px;
            text-align: left;
            white-space: nowrap;
        }

        th {
            font-weight: bold;
        }

        .empty-data {
            padding: 30px;
            color: #6b7280;
            text-align: center;
        }

        .purchase-price {
            color: #dc2626;
        }

        .selling-price {
            color: #2563eb;
        }

        .profit {
            color: #16a34a;
            font-weight: bold;
        }

        /* Status stok */

        .stock-badge {
            display: inline-block;
            min-width: 100px;
            padding: 7px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            text-align: center;
        }

        .stock-available {
            background-color: #dcfce7;
            color: #166534;
        }

        .stock-low {
            background-color: #fef3c7;
            color: #92400e;
        }

        .stock-out {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Tombol aksi */

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .button-edit,
        .button-delete {
            padding: 9px 15px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .button-edit {
            display: inline-block;
            background-color: #f59e0b;
            text-decoration: none;
        }

        .button-edit:hover {
            background-color: #d97706;
        }

        .button-delete {
            background-color: #dc2626;
        }

        .button-delete:hover {
            background-color: #b91c1c;
        }

        .delete-form {
            margin: 0;
        }

        /* Pagination */

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 18px;
            padding: 14px 16px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .pagination a,
        .pagination .disabled {
            padding: 9px 15px;
            border-radius: 6px;
            text-decoration: none;
        }

        .pagination a {
            background-color: #2563eb;
            color: white;
        }

        .pagination a:hover {
            background-color: #1d4ed8;
        }

        .pagination .disabled {
            background-color: #e5e7eb;
            color: #9ca3af;
        }

        .pagination-info {
            color: #4b5563;
            font-size: 14px;
            text-align: center;
        }

        @media (max-width: 1250px) {
            .summary-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .filter-form {
                grid-template-columns: repeat(2, 1fr);
            }

            .button-filter,
            .button-reset {
                width: 100%;
            }
        }

        /* Tampilan HP */

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
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
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
                box-shadow: 4px 0 15px rgba(0, 0, 0, 0.25);
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .sidebar h1 {
                margin-bottom: 40px;
                font-size: 25px;
            }

            .sidebar-menu {
                margin-bottom: 25px;
            }

            .sidebar-menu a {
                margin-bottom: 10px;
                padding: 12px 10px;
                border-radius: 6px;
                background-color: rgba(255, 255, 255, 0.06);
                font-size: 16px;
            }

            .main-content {
                width: 100%;
                padding: 85px 15px 30px;
                overflow-x: hidden;
            }

            .page-header {
                flex-direction: column;
                margin-bottom: 30px;
            }

            .page-header h2 {
                font-size: 30px;
            }

            .page-header p {
                font-size: 16px;
                line-height: 1.5;
            }

            .button-add {
                width: 100%;
                text-align: center;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .table-card {
                border-radius: 7px;
            }

            th,
            td {
                padding: 14px;
                font-size: 14px;
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
                    <h2>Produk</h2>

                    <p>
                        Kelola produk dan pantau kondisi stok barang.
                    </p>
                </div>

                <a
                    href="{{ route('products.create') }}"
                    class="button-add"
                >
                    + Tambah Produk
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
                <h3>Pencarian dan Filter Produk</h3>

                <form
                    action="{{ route('products.index') }}"
                    method="GET"
                    class="filter-form"
                >
                    <div class="form-group">
                        <label for="search">
                            Cari Nama Produk
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control"
                            value="{{ $search ?? '' }}"
                            placeholder="Contoh: K-Vision atau Receiver"
                        >
                    </div>

                    <div class="form-group">
                        <label for="category">
                            Kategori
                        </label>

                        <select
                            id="category"
                            name="category"
                            class="form-control"
                        >
                            <option value="">
                                Semua Kategori
                            </option>

                            @foreach ($categories as $categoryOption)
                                <option
                                    value="{{ $categoryOption }}"
                                    {{ ($category ?? '') === $categoryOption ? 'selected' : '' }}
                                >
                                    {{ $categoryOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="stock_status">
                            Status Stok
                        </label>

                        <select
                            id="stock_status"
                            name="stock_status"
                            class="form-control"
                        >
                            <option value="">
                                Semua Status
                            </option>

                            <option
                                value="available"
                                {{ ($stockStatus ?? '') === 'available' ? 'selected' : '' }}
                            >
                                Tersedia (lebih dari 5)
                            </option>

                            <option
                                value="low"
                                {{ ($stockStatus ?? '') === 'low' ? 'selected' : '' }}
                            >
                                Stok Rendah (1 sampai 5)
                            </option>

                            <option
                                value="out"
                                {{ ($stockStatus ?? '') === 'out' ? 'selected' : '' }}
                            >
                                Stok Habis
                            </option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="button-filter"
                    >
                        Terapkan
                    </button>

                    <a
                        href="{{ route('products.index') }}"
                        class="button-reset"
                    >
                        Reset
                    </a>
                </form>

                @if (
                    ($search ?? '') !== '' ||
                    ($category ?? '') !== '' ||
                    ($stockStatus ?? '') !== ''
                )
                    <p class="filter-info">
                        Menampilkan {{ $products->total() }}
                        produk sesuai pencarian dan filter.
                    </p>
                @endif
            </section>

            <section
                class="summary-grid"
                aria-label="Ringkasan produk"
            >
                <article class="summary-card summary-total">
                    <h3>Jumlah Produk</h3>
                    <strong class="value-total">
                        {{ $totalProducts }}
                    </strong>
                </article>

                <article class="summary-card summary-stock">
                    <h3>Total Unit Stok</h3>
                    <strong class="value-stock">
                        {{ $totalStock }} unit
                    </strong>
                </article>

                <article class="summary-card summary-available">
                    <h3>Produk Tersedia</h3>
                    <strong class="value-available">
                        {{ $availableProducts }}
                    </strong>
                </article>

                <article class="summary-card summary-low">
                    <h3>Stok Rendah</h3>
                    <strong class="value-low">
                        {{ $lowStockProducts }}
                    </strong>
                </article>

                <article class="summary-card summary-out">
                    <h3>Stok Habis</h3>
                    <strong class="value-out">
                        {{ $outOfStockProducts }}
                    </strong>
                </article>
            </section>

            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Laba/Unit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            @php
                                $stock = (int) $product->stock;

                                $profit =
                                    (float) $product->selling_price
                                    - (float) $product->purchase_price;
                            @endphp

                            <tr>
                                <td>
                                    {{ $products->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $product->product_name }}
                                </td>

                                <td>
                                    {{ $product->category }}
                                </td>

                                <td>
                                    {{ $stock }} unit
                                </td>

                                <td>
                                    @if ($stock <= 0)
                                        <span class="stock-badge stock-out">
                                            Stok Habis
                                        </span>
                                    @elseif ($stock <= 5)
                                        <span class="stock-badge stock-low">
                                            Stok Rendah
                                        </span>
                                    @else
                                        <span class="stock-badge stock-available">
                                            Tersedia
                                        </span>
                                    @endif
                                </td>

                                <td class="purchase-price">
                                    ${{ number_format($product->purchase_price, 2) }}
                                </td>

                                <td class="selling-price">
                                    ${{ number_format($product->selling_price, 2) }}
                                </td>

                                <td class="profit">
                                    ${{ number_format($profit, 2) }}
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <a
                                            href="{{ route('products.edit', $product) }}"
                                            class="button-edit"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('products.destroy', $product) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Produk yang memiliki transaksi atau stok tidak dapat dihapus.')"
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
                                    colspan="9"
                                    class="empty-data"
                                >
                                    Tidak ada produk yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <nav
                    class="pagination"
                    aria-label="Navigasi halaman produk"
                >
                    @if ($products->onFirstPage())
                        <span class="disabled">
                            Sebelumnya
                        </span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}">
                            Sebelumnya
                        </a>
                    @endif

                    <span class="pagination-info">
                        Halaman {{ $products->currentPage() }}
                        dari {{ $products->lastPage() }}
                        — Total {{ $products->total() }} produk
                    </span>

                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}">
                            Berikutnya
                        </a>
                    @else
                        <span class="disabled">
                            Berikutnya
                        </span>
                    @endif
                </nav>
            @endif
        </main>
    </div>

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

        window.addEventListener(
            'resize',
            function () {
                if (window.innerWidth > 700) {
                    closeSidebar();
                }
            }
        );
    </script>
</body>
</html>