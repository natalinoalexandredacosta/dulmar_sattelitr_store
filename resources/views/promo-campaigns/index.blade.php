<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Promo Campaign - Dulmar Satellite Store</title>

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
            background-color: #f4f6f9;
            overflow-x: hidden;
        }

        body.menu-open {
            overflow: hidden;
        }

        .container {
            width: 100%;
            min-height: 100vh;
        }

        /*
        |--------------------------------------------------------------------------
        | SIDEBAR FIXED
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

            padding: 35px 25px;

            background-color: #1f2b3a;
            color: white;

            overflow-y: auto;
            overflow-x: hidden;

            scrollbar-width: thin;
            scrollbar-color: #475569 #1f2b3a;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #1f2b3a;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 10px;
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

        /*
        |--------------------------------------------------------------------------
        | MAIN CONTENT
        |--------------------------------------------------------------------------
        */

        .main-content {
            width: calc(100% - 245px);
            min-width: 0;
            min-height: 100vh;

            margin-left: 245px;

            padding: 50px 32px;

            overflow-x: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | PAGE HEADER
        |--------------------------------------------------------------------------
        */

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
            line-height: 1.6;
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

        /*
        |--------------------------------------------------------------------------
        | ALERT
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        .summary-grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(170px, 1fr));

            gap: 15px;

            margin-bottom: 25px;
        }

        .summary-card {
            padding: 20px;

            border-radius: 10px;

            background-color: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);
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

        .summary-active {
            border-left: 5px solid #16a34a;
        }

        .summary-upcoming {
            border-left: 5px solid #f59e0b;
        }

        .summary-products {
            border-left: 5px solid #7c3aed;
        }

        /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */

        .table-card {
            width: 100%;

            overflow-x: auto;

            border-radius: 10px;

            background-color: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);

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
            vertical-align: middle;
        }

        th {
            white-space: nowrap;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        .campaign-title {
            font-weight: bold;
        }

        .campaign-description {
            max-width: 320px;

            margin-top: 5px;

            color: #6b7280;

            font-size: 14px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        .status-badge {
            display: inline-block;

            min-width: 100px;

            padding: 7px 10px;

            border-radius: 20px;

            font-size: 13px;
            font-weight: bold;

            text-align: center;

            white-space: nowrap;
        }

        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background-color: #e5e7eb;
            color: #4b5563;
        }

        .status-upcoming {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-ended {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .product-badge {
            display: inline-block;

            padding: 7px 12px;

            border-radius: 20px;

            background-color: #ede9fe;
            color: #5b21b6;

            font-size: 13px;
            font-weight: bold;

            white-space: nowrap;
        }

        /*
        |--------------------------------------------------------------------------
        | ACTION
        |--------------------------------------------------------------------------
        */

        .action-buttons {
            display: flex;
            align-items: center;

            gap: 8px;

            white-space: nowrap;
        }

        .action-buttons form {
            margin: 0;
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

        .empty-data {
            padding: 40px;

            color: #6b7280;

            text-align: center;
        }

        /*
        |--------------------------------------------------------------------------
        | TABLET
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1100px) {
            .summary-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }
        }

        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 700px) {

            .container {
                display: block;
                width: 100%;
            }

            /*
            |--------------------------------------------------------------------------
            | MENU BUTTON
            |--------------------------------------------------------------------------
            */

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

                box-shadow:
                    0 3px 10px
                    rgba(0, 0, 0, 0.25);
            }

            /*
            |--------------------------------------------------------------------------
            | OVERLAY
            |--------------------------------------------------------------------------
            */

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 1000;

                display: block;

                visibility: hidden;

                background-color:
                    rgba(0, 0, 0, 0.5);

                opacity: 0;

                transition:
                    opacity 0.25s,
                    visibility 0.25s;
            }

            .sidebar-overlay.overlay-open {
                visibility: visible;
                opacity: 1;
            }

            /*
            |--------------------------------------------------------------------------
            | MOBILE SIDEBAR
            |--------------------------------------------------------------------------
            */

            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1100;

                width: min(82vw, 285px);
                height: 100vh;

                padding: 82px 25px 30px;

                overflow-y: auto;

                transform: translateX(-105%);

                transition:
                    transform 0.25s ease;

                box-shadow:
                    4px 0 15px
                    rgba(0, 0, 0, 0.25);
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

                background-color:
                    rgba(255, 255, 255, 0.06);

                font-size: 16px;
            }

            /*
            |--------------------------------------------------------------------------
            | MOBILE CONTENT
            |--------------------------------------------------------------------------
            */

            .main-content {
                width: 100%;

                margin-left: 0;

                padding:
                    85px
                    15px
                    30px;

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
            }

            .button-add {
                width: 100%;

                text-align: center;
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

                <div>

                    <h2>
                        Promo Campaign
                    </h2>

                    <p>
                        Kelola satu promosi untuk beberapa produk
                        dengan diskon berbeda.
                    </p>

                </div>

                <a
                    href="{{ route('promo-campaigns.create') }}"
                    class="button-add"
                >
                    + Tambah Promo Campaign
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

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif

            @php
                $totalCampaigns =
                    $campaigns->count();

                $activeCampaigns =
                    $campaigns
                        ->filter(function ($campaign) {
                            return $campaign->currently_active;
                        })
                        ->count();

                $upcomingCampaigns =
                    $campaigns
                        ->filter(function ($campaign) {
                            return $campaign->is_active
                                && $campaign->start_date
                                && now()
                                    ->startOfDay()
                                    ->lt($campaign->start_date);
                        })
                        ->count();

                $totalPromoProducts =
                    $campaigns->sum(
                        'products_count'
                    );
            @endphp

            <section
                class="summary-grid"
                aria-label="Ringkasan Promo Campaign"
            >

                <article
                    class="summary-card summary-total"
                >

                    <h3>
                        Total Campaign
                    </h3>

                    <strong>
                        {{ $totalCampaigns }}
                    </strong>

                </article>

                <article
                    class="summary-card summary-active"
                >

                    <h3>
                        Campaign Aktif
                    </h3>

                    <strong>
                        {{ $activeCampaigns }}
                    </strong>

                </article>

                <article
                    class="summary-card summary-upcoming"
                >

                    <h3>
                        Belum Mulai
                    </h3>

                    <strong>
                        {{ $upcomingCampaigns }}
                    </strong>

                </article>

                <article
                    class="summary-card summary-products"
                >

                    <h3>
                        Produk Promo
                    </h3>

                    <strong>
                        {{ $totalPromoProducts }}
                    </strong>

                </article>

            </section>

            <div class="table-card">

                <table>

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Campaign</th>
                            <th>Periode</th>
                            <th>Produk</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($campaigns as $campaign)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <div class="campaign-title">
                                        {{ $campaign->title }}
                                    </div>

                                    @if ($campaign->description)

                                        <div class="campaign-description">

                                            {{
                                                \Illuminate\Support\Str::limit(
                                                    $campaign->description,
                                                    100
                                                )
                                            }}

                                        </div>

                                    @endif

                                </td>

                                <td>

                                    {{
                                        $campaign->start_date
                                            ? $campaign->start_date->format('d/m/Y')
                                            : '-'
                                    }}

                                    <br>

                                    sampai

                                    <br>

                                    {{
                                        $campaign->end_date
                                            ? $campaign->end_date->format('d/m/Y')
                                            : '-'
                                    }}

                                </td>

                                <td>

                                    <span class="product-badge">

                                        {{ $campaign->products_count }}
                                        Produk

                                    </span>

                                </td>

                                <td>

                                    @if ($campaign->currently_active)

                                        <span
                                            class="status-badge status-active"
                                        >
                                            Aktif
                                        </span>

                                    @elseif (!$campaign->is_active)

                                        <span
                                            class="status-badge status-inactive"
                                        >
                                            Nonaktif
                                        </span>

                                    @elseif (
                                        $campaign->start_date
                                        &&
                                        now()
                                            ->startOfDay()
                                            ->lt($campaign->start_date)
                                    )

                                        <span
                                            class="status-badge status-upcoming"
                                        >
                                            Belum Mulai
                                        </span>

                                    @else

                                        <span
                                            class="status-badge status-ended"
                                        >
                                            Berakhir
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="{{ route(
                                                'promo-campaigns.edit',
                                                $campaign
                                            ) }}"
                                            class="button-edit"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route(
                                                'promo-campaigns.destroy',
                                                $campaign
                                            ) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus Promo Campaign ini?')"
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
                                    colspan="6"
                                    class="empty-data"
                                >
                                    Belum ada Promo Campaign.
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
            .forEach(
                function (link) {

                    link.addEventListener(
                        'click',
                        closeSidebar
                    );

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