<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>User Management - Dulmar Satellite Store</title>

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

        .logout-form {
            flex-shrink: 0;
            margin-top: 20px;
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

        .main-content {
            width: calc(100% - 245px);
            min-height: 100vh;

            margin-left: 245px;

            padding: 50px 32px;
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

        .alert-success,
        .alert-error {
            margin-bottom: 25px;

            padding: 15px 20px;

            border-radius: 6px;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
        }

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
            min-width: 850px;

            border-collapse: collapse;
        }

        thead {
            background-color: #edf2f7;
        }

        th,
        td {
            padding: 18px;

            border-bottom: 1px solid #d1d5db;

            text-align: left;

            font-size: 16px;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        .role-badge {
            display: inline-block;

            padding: 7px 12px;

            border-radius: 20px;

            background-color: #dbeafe;
            color: #1d4ed8;

            font-size: 13px;
            font-weight: bold;
        }

        .user-badge {
            display: inline-block;

            padding: 7px 12px;

            border-radius: 20px;

            background-color: #f3f4f6;
            color: #4b5563;

            font-size: 13px;
            font-weight: bold;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;

            gap: 8px;
        }

        .button-edit {
            display: inline-block;

            padding: 9px 15px;

            border-radius: 6px;

            background-color: #f59e0b;
            color: white;

            text-decoration: none;
        }

        .button-edit:hover {
            background-color: #d97706;
        }

        .button-delete {
            padding: 9px 15px;

            border: none;
            border-radius: 6px;

            background-color: #dc2626;
            color: white;

            cursor: pointer;
        }

        .button-delete:hover {
            background-color: #b91c1c;
        }

        .no-action {
            color: #9ca3af;

            font-size: 14px;
        }

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

                background-color:
                    rgba(0, 0, 0, 0.5);

                opacity: 0;
            }

            .sidebar-overlay.overlay-open {
                visibility: visible;
                opacity: 1;
            }

            .sidebar {
                z-index: 1100;

                width: min(82vw, 285px);

                padding:
                    82px
                    25px
                    30px;

                transform:
                    translateX(-105%);

                transition:
                    transform 0.25s ease;
            }

            .sidebar.sidebar-open {
                transform:
                    translateX(0);
            }

            .main-content {
                width: 100%;

                margin-left: 0;

                padding:
                    85px
                    15px
                    30px;
            }

            .page-header {
                flex-direction: column;
            }

            .page-header h2 {
                font-size: 29px;
            }

            .button-add {
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

        <h1>
            Dulmar Satellite Store
        </h1>


        <nav class="sidebar-menu">

            @can('dashboard.view')

                <a
                    href="{{ route('dashboard') }}"
                >
                    Dashboard
                </a>

            @endcan


            @can('products.view')

                <a
                    href="{{ route('products.index') }}"
                >
                    Daftar Barang
                </a>

            @endcan


            @can('promo-campaigns.view')

                <a
                    href="{{ route('promo-campaigns.index') }}"
                >
                    Promo Campaign
                </a>

            @endcan


            @can('stock-ins.view')

                <a
                    href="{{ route('stock-ins.index') }}"
                >
                    Stok Masuk
                </a>

            @endcan


            @can('stock-outs.view')

                <a
                    href="{{ route('stock-outs.index') }}"
                >
                    Stok Keluar
                </a>

            @endcan


            @can('tv-vouchers.view')

                <a
                    href="{{ route('tv-vouchers.index') }}"
                >
                    TV Voucher
                </a>

            @endcan


            @can('suppliers.view')

                <a
                    href="{{ route('suppliers.index') }}"
                >
                    Supplier Barang
                </a>

            @endcan


            @can('customers.view')

                <a
                    href="{{ route('customers.index') }}"
                >
                    Pelanggan
                </a>

            @endcan


            @can('reports.view')

                <a
                    href="{{ route('reports.index') }}"
                >
                    Laporan
                </a>

            @endcan


            @can('users.view')

                <a
                    href="{{ route('users.index') }}"
                    class="active"
                >
                    User Management
                </a>

            @endcan

        </nav>


        <form
            action="{{ route('logout') }}"
            method="POST"
            class="logout-form"
            onsubmit="
                return confirm(
                    'Apakah Anda yakin ingin keluar?'
                )
            "
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
                    User Management
                </h2>

                <p>
                    Kelola akun dan hak akses pengguna sistem.
                </p>

            </div>


            @can('users.create')

                <a
                    href="{{ route('users.create') }}"
                    class="button-add"
                >
                    + Buat Akun
                </a>

            @endcan

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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($users as $user)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>
                                {{ $user->name }}
                            </td>


                            <td>
                                {{ $user->email }}
                            </td>


                            <td>

                                @if (
                                    $user->hasRole(
                                        'Administrator'
                                    )
                                )

                                    <span class="role-badge">
                                        Administrator
                                    </span>

                                @else

                                    <span class="user-badge">
                                        User
                                    </span>

                                @endif

                            </td>


                            <td>

                                <div class="action-buttons">

                                    @can('users.edit')

                                        <a
                                            href="{{ route(
                                                'users.edit',
                                                $user
                                            ) }}"
                                            class="button-edit"
                                        >
                                            Edit
                                        </a>

                                    @endcan


                                    @can('users.delete')

                                        @if (
                                            auth()->id() !== $user->id
                                            &&
                                            !$user->hasRole(
                                                'Administrator'
                                            )
                                        )

                                            <form
                                                action="{{ route(
                                                    'users.destroy',
                                                    $user
                                                ) }}"
                                                method="POST"
                                                onsubmit="
                                                    return confirm(
                                                        'Apakah Anda yakin ingin menghapus akun ini?'
                                                    )
                                                "
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

                                        @endif

                                    @endcan


                                    @cannot('users.edit')

                                        @cannot('users.delete')

                                            <span class="no-action">
                                                Tidak ada akses
                                            </span>

                                        @endcannot

                                    @endcannot

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">
                                Belum ada user.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </main>

</div>


<!--
|--------------------------------------------------------------------------
| LOGOUT OTOMATIS SETELAH IDLE 10 MENIT
|--------------------------------------------------------------------------
-->

<form
    id="idleLogoutForm"
    action="{{ route('logout') }}"
    method="POST"
    style="display: none;"
>
    @csrf
</form>


<script
    src="{{ asset('js/idle-timeout.js') }}"
></script>


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