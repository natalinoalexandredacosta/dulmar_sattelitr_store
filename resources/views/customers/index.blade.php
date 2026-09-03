<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggan - Dulmar Satellite Store</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-dulmar.jpg') }}">

    <style>
        * { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; overflow-x: hidden; }
        body.menu-open { overflow: hidden; }

        .container { width: 100%; min-height: 100vh; }

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
        }

        .sidebar h1 { margin: 0 0 35px; font-size: 27px; }
        .sidebar-menu { flex: 1; }

        .sidebar-menu a {
            display: block;
            margin-bottom: 10px;
            padding: 12px 10px;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            text-decoration: none;
        }

        .sidebar-menu a:hover { background: rgba(255,255,255,.08); }

        .sidebar-menu a.active {
            padding-left: 14px;
            border-left: 4px solid #60a5fa;
            background: rgba(37,99,235,.3);
            color: #bfdbfe;
            font-weight: bold;
        }

        .report-submenu {
            padding-left: 25px !important;
            font-size: 15px !important;
        }

        .logout-form { margin-top: 20px; }

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

        .main-content {
            width: calc(100% - 245px);
            min-height: 100vh;
            margin-left: 245px;
            padding: 45px 32px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 35px;
        }

        .page-header h2 { margin: 0 0 12px; font-size: 36px; }
        .page-header p { margin: 0; color: #4b5563; font-size: 18px; }

        .button-add {
            display: inline-block;
            padding: 15px 21px;
            border-radius: 8px;
            background: #7c3aed;
            color: white;
            text-decoration: none;
        }

        .alert-success, .alert-error {
            margin-bottom: 25px;
            padding: 15px 20px;
            border-radius: 7px;
        }

        .alert-success { border: 1px solid #86efac; background: #dcfce7; color: #166534; }
        .alert-error { border: 1px solid #fca5a5; background: #fee2e2; color: #991b1b; }

        .table-card {
            width: 100%;
            overflow-x: auto;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
        }

        table {
            width: 100%;
            min-width: 1150px;
            border-collapse: collapse;
            background: white;
        }

        thead { background: #edf2f7; }

        th, td {
            padding: 16px;
            border-bottom: 1px solid #d1d5db;
            font-size: 15px;
            text-align: left;
            vertical-align: top;
        }

        .product-list {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 250px;
        }

        .product-item {
            padding: 7px 10px;
            border-radius: 6px;
            background: #eff6ff;
            color: #1e40af;
            line-height: 1.4;
        }

        .product-name { font-weight: bold; }
        .product-meta { color: #64748b; font-size: 12px; }

        .action-buttons { display: flex; flex-wrap: wrap; gap: 8px; }

        .button-edit, .button-delete {
            display: inline-block;
            padding: 9px 15px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .button-edit { background: #f59e0b; }
        .button-delete { background: #dc2626; }
        .delete-form { margin: 0; }
        .empty-data { padding: 35px; color: #6b7280; text-align: center; }
        .sidebar-toggle, .sidebar-overlay { display: none; }

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
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 1000;
                display: block;
                visibility: hidden;
                background: rgba(0,0,0,.5);
                opacity: 0;
            }

            .sidebar-overlay.overlay-open { visibility: visible; opacity: 1; }

            .sidebar {
                z-index: 1100;
                width: min(82vw,285px);
                padding: 82px 25px 30px;
                transform: translateX(-105%);
                transition: transform .25s ease;
            }

            .sidebar.sidebar-open { transform: translateX(0); }

            .main-content {
                width: 100%;
                margin-left: 0;
                padding: 85px 14px 30px;
            }

            .page-header { flex-direction: column; }
            .button-add { width: 100%; text-align: center; }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/fixed-layout.css') }}">
</head>

<body>

<button type="button" id="sidebarToggle" class="sidebar-toggle">☰</button>
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<div class="container">

    <aside class="sidebar" id="sidebar">

        <h1>Dulmar Satellite Store</h1>

        <nav class="sidebar-menu">

            @can('dashboard.view')
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            @endcan

            @can('products.view')
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                    Daftar Barang
                </a>
            @endcan

            @can('promo-campaigns.view')
                <a href="{{ route('promo-campaigns.index') }}" class="{{ request()->routeIs('promo-campaigns.*') ? 'active' : '' }}">
                    Promo Campaign
                </a>
            @endcan

            @can('stock-ins.view')
                <a href="{{ route('stock-ins.index') }}" class="{{ request()->routeIs('stock-ins.*') ? 'active' : '' }}">
                    Stok Masuk
                </a>
            @endcan

            @can('stock-outs.view')
                <a href="{{ route('stock-outs.index') }}" class="{{ request()->routeIs('stock-outs.*') ? 'active' : '' }}">
                    Stok Keluar
                </a>
            @endcan

            @can('tv-vouchers.view')
                <a href="{{ route('tv-vouchers.index') }}" class="{{ request()->routeIs('tv-vouchers.index') ? 'active' : '' }}">
                    TV Voucher
                </a>

                <a href="{{ route('tv-vouchers.report') }}" class="report-submenu {{ request()->routeIs('tv-vouchers.report') ? 'active' : '' }}">
                    ↳ Laporan TV Voucher
                </a>
            @endcan

            @can('cash-admin.view')
                <a href="{{ route('cash-accounts.index') }}" class="{{ request()->routeIs('cash-accounts.*') ? 'active' : '' }}">
                    Kas Admin
                </a>
            @endcan

            @can('suppliers.view')
                <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    Supplier Barang
                </a>
            @endcan

            @can('customers.view')
                <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    Pelanggan
                </a>
            @endcan

            @can('reports.view')
                <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    Laporan
                </a>
            @endcan

            @can('users.view')
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    User Management
                </a>
            @endcan

        </nav>

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="button-logout">Keluar</button>
        </form>

    </aside>

    <main class="main-content">

        <div class="page-header">
            <div>
                <h2>Pelanggan</h2>
                <p>Data pelanggan yang pernah membeli barang di Dulmar Satellite Store.</p>
            </div>

            @can('customers.create')
                <a href="{{ route('customers.create') }}" class="button-add">
                    + Tambah Pelanggan
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Nomor Telepon</th>
                        <th>Alamat</th>
                        <th>Barang yang Dibeli</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($customers as $customer)

                        @php
                            $purchases = \App\Models\StockOut::query()
                                ->where('customer_id', $customer->id)
                                ->with('product')
                                ->orderByDesc('transaction_date')
                                ->orderByDesc('id')
                                ->get();
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $customer->customer_name }}</td>

                            <td>{{ $customer->phone ?: '-' }}</td>

                            <td>{{ $customer->address ?: '-' }}</td>

                            <td>
                                @if ($purchases->isNotEmpty())
                                    <div class="product-list">
                                        @foreach ($purchases as $purchase)
                                            <div class="product-item">
                                                <div class="product-name">
                                                    {{ $purchase->product?->product_name ?? 'Produk sudah dihapus' }}
                                                </div>

                                                <div class="product-meta">
                                                    Jumlah: {{ (int) $purchase->quantity }}
                                                    |
                                                    Tanggal:
                                                    {{
                                                        $purchase->transaction_date
                                                            ? \Carbon\Carbon::parse($purchase->transaction_date)->format('d-m-Y')
                                                            : '-'
                                                    }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <div class="action-buttons">

                                    @can('customers.edit')
                                        <a href="{{ route('customers.edit', $customer) }}" class="button-edit">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('customers.delete')
                                        <form
                                            action="{{ route('customers.destroy', $customer) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="button-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="empty-data">
                                Belum ada data pelanggan.
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function closeSidebar() {
        sidebar.classList.remove('sidebar-open');
        sidebarOverlay.classList.remove('overlay-open');
        document.body.classList.remove('menu-open');
    }

    sidebarToggle.addEventListener('click', function () {
        const open = sidebar.classList.toggle('sidebar-open');

        sidebarOverlay.classList.toggle('overlay-open', open);
        document.body.classList.toggle('menu-open', open);
    });

    sidebarOverlay.addEventListener('click', closeSidebar);
</script>

</body>
</html>
