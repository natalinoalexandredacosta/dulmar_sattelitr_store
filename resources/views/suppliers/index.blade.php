<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Barang - Dulmar Satellite Store</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        body.menu-open { overflow: hidden; }

        .container { display: flex; min-height: 100vh; }

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
            margin-bottom: 45px;
        }

        .page-header h2 {
            margin: 0 0 20px;
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
            background-color: #0891b2;
            color: white;
            font-size: 18px;
            text-decoration: none;
        }

        .button-add:hover {
            background-color: #0e7490;
        }

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
            min-width: 1000px;
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
            color: #6b7280;
            text-align: center;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .button-edit {
            display: inline-block;
            padding: 9px 15px;
            border-radius: 6px;
            background-color: #f59e0b;
            color: white;
            font-size: 14px;
            text-decoration: none;
        }

        .button-edit:hover {
            background-color: #d97706;
        }

        .delete-form {
            margin: 0;
        }

        .button-delete {
            padding: 9px 15px;
            border: none;
            border-radius: 6px;
            background-color: #dc2626;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .button-delete:hover {
            background-color: #b91c1c;
        }

        .email-link {
            color: #2563eb;
            text-decoration: none;
        }

        .email-link:hover {
            text-decoration: underline;
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
                transition: opacity 0.25s, visibility 0.25s;
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

            .table-card {
                border-radius: 7px;
            }

            th,
            td {
                padding: 14px;
                font-size: 14px;
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
                    <h2>Supplier Barang</h2>

                    <p>
                        Kelola data pihak atau perusahaan yang memasok barang.
                    </p>
                </div>

                <a
                    href="{{ route('suppliers.create') }}"
                    class="button-add"
                >
                    + Tambah Supplier
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

            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Supplier</th>
                            <th>Nama Kontak</th>
                            <th>Nomor Telepon</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $supplier->supplier_name }}
                                </td>

                                <td>
                                    {{ $supplier->contact_person ?: '-' }}
                                </td>

                                <td>
                                    {{ $supplier->phone ?: '-' }}
                                </td>

                                <td>
                                    @if ($supplier->email)
                                        <a
                                            href="mailto:{{ $supplier->email }}"
                                            class="email-link"
                                        >
                                            {{ $supplier->email }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $supplier->address ?: '-' }}
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <a
                                            href="{{ route('suppliers.edit', $supplier) }}"
                                            class="button-edit"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('suppliers.destroy', $supplier) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')"
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
                                    Belum ada data supplier barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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