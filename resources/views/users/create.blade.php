<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Buat Akun - Dulmar Satellite Store</title>

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

        .main-content {
            width: calc(100% - 245px);
            min-height: 100vh;

            margin-left: 245px;
            padding: 50px 32px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h2 {
            margin: 0 0 10px;
            font-size: 36px;
        }

        .page-header p {
            margin: 0;
            color: #6b7280;
            font-size: 17px;
        }

        .form-card {
            width: 100%;
            max-width: 1000px;

            padding: 30px;

            border-radius: 12px;
            background-color: white;

            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group > label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 13px 14px;

            border: 1px solid #d1d5db;
            border-radius: 7px;

            font-size: 16px;
        }

        .form-control:focus {
            border-color: #2563eb;
            outline: none;
        }

        .error-text {
            margin-top: 6px;
            color: #dc2626;
            font-size: 14px;
        }

        .info-box {
            margin-bottom: 25px;
            padding: 15px 18px;

            border-left: 4px solid #2563eb;
            border-radius: 6px;

            background-color: #eff6ff;
            color: #1e3a8a;

            line-height: 1.6;
        }

        .security-info {
            margin-top: 15px;
            padding: 14px 16px;

            border-left: 4px solid #dc2626;
            border-radius: 6px;

            background-color: #fef2f2;
            color: #991b1b;

            font-size: 14px;
            line-height: 1.6;
        }

        .permission-title {
            margin-top: 35px;
            margin-bottom: 8px;
            font-size: 22px;
            color: #111827;
        }

        .permission-description {
            margin-top: 0;
            margin-bottom: 20px;
            color: #6b7280;
            line-height: 1.6;
        }

        .permission-section {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 18px;
            margin-top: 15px;
        }

        .permission-group {
            padding: 20px;

            border: 1px solid #dbe1e8;
            border-radius: 10px;

            background-color: #f8fafc;
        }

        .permission-group h3 {
            margin: 0 0 15px;
            padding-bottom: 10px;

            border-bottom: 1px solid #e5e7eb;

            color: #1f2937;
            font-size: 18px;
        }

        .permission-item {
            display: flex;
            align-items: flex-start;
            gap: 9px;

            margin-bottom: 11px;

            color: #374151;
            font-weight: normal;

            cursor: pointer;
        }

        .permission-item:last-child {
            margin-bottom: 0;
        }

        .permission-item input {
            width: 18px;
            height: 18px;
            margin: 1px 0 0;
            flex-shrink: 0;
            cursor: pointer;
        }

        .permission-group-dashboard {
            border-left: 4px solid #2563eb;
        }

        .permission-group-stock-out {
            border-left: 4px solid #f97316;
        }

        .permission-group-voucher {
            border-left: 4px solid #16a34a;
        }

        .permission-group-users {
            border-left: 4px solid #dc2626;
        }

        .permission-divider {
            margin: 15px 0;
            border: 0;
            border-top: 1px dashed #cbd5e1;
        }

        .permission-special {
            padding: 12px;

            border-radius: 7px;
            background-color: #fff7ed;
        }

        .permission-special-admin {
            padding: 12px;

            border-radius: 7px;
            background-color: #fef2f2;
        }

        .permission-note {
            display: block;
            margin-top: 4px;

            color: #6b7280;

            font-size: 12px;
            line-height: 1.45;
        }

        .permission-note-danger {
            color: #b91c1c;
            font-weight: bold;
        }

        .button-area {
            display: flex;
            gap: 12px;
            margin-top: 35px;
        }

        .button-save,
        .button-back {
            display: inline-block;

            padding: 13px 20px;

            border: none;
            border-radius: 7px;

            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .button-save {
            background-color: #2563eb;
            color: white;
        }

        .button-save:hover {
            background-color: #1d4ed8;
        }

        .button-back {
            background-color: #6b7280;
            color: white;
        }

        .button-back:hover {
            background-color: #4b5563;
        }

        .sidebar-toggle,
        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 900px) {
            .permission-section {
                grid-template-columns: 1fr;
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

                border: none;
                border-radius: 8px;

                background-color: #1f2b3a;
                color: white;

                font-size: 25px;
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
                z-index: 1100;

                width: min(82vw, 285px);

                padding: 82px 25px 30px;

                transform: translateX(-105%);
                transition: transform 0.25s ease;
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .main-content {
                width: 100%;
                margin-left: 0;
                padding: 85px 15px 30px;
            }

            .form-card {
                padding: 22px 18px;
            }

            .page-header h2 {
                font-size: 29px;
            }

            .permission-section {
                grid-template-columns: 1fr;
            }

            .button-area {
                flex-direction: column;
            }

            .button-save,
            .button-back {
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
                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>
            @endcan

            @can('products.view')
                <a href="{{ route('products.index') }}">
                    Daftar Barang
                </a>
            @endcan

            @can('promo-campaigns.view')
                <a href="{{ route('promo-campaigns.index') }}">
                    Promo Campaign
                </a>
            @endcan

            @can('stock-ins.view')
                <a href="{{ route('stock-ins.index') }}">
                    Stok Masuk
                </a>
            @endcan

            @can('stock-outs.view')
                <a href="{{ route('stock-outs.index') }}">
                    Stok Keluar
                </a>
            @endcan

            @can('tv-vouchers.view')
                <a href="{{ route('tv-vouchers.index') }}">
                    TV Voucher
                </a>
            @endcan

            @can('suppliers.view')
                <a href="{{ route('suppliers.index') }}">
                    Supplier Barang
                </a>
            @endcan

            @can('customers.view')
                <a href="{{ route('customers.index') }}">
                    Pelanggan
                </a>
            @endcan

            @can('reports.view')
                <a href="{{ route('reports.index') }}">
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
                Buat Akun Baru
            </h2>

            <p>
                Buat akun user dan tentukan menu serta fitur yang boleh diakses.
            </p>

        </div>


        <div class="form-card">

            <div class="info-box">
                Administrator menentukan email dan password awal untuk user.
                Hak akses diberikan satu per satu sesuai pekerjaan user.
            </div>

            <div class="security-info">
                <strong>Perhatian:</strong>
                permission <strong>Verifikasi Pembayaran</strong>
                digunakan oleh petugas yang menerima uang dari customer.
                Permission <strong>Verifikasi Setoran</strong>
                hanya diberikan kepada Administrator atau petugas yang
                bertanggung jawab memegang dan memeriksa uang setoran.
            </div>


            <form
                action="{{ route('users.store') }}"
                method="POST"
            >

                @csrf


                <div class="form-group">

                    <label for="name">
                        Nama
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        required
                    >

                    @error('name')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="form-group">

                    <label for="email">
                        Email Login
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                    >

                    @error('email')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="form-group">

                    <label for="password">
                        Password Awal
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        required
                    >

                    @error('password')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="form-group">

                    <label for="password_confirmation">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        required
                    >

                </div>


                <h3 class="permission-title">
                    Hak Akses User
                </h3>

                <p class="permission-description">
                    Centang hanya menu dan aksi yang boleh digunakan oleh user.
                    Jika tidak dicentang, user tidak mendapatkan permission tersebut.
                </p>


                <div class="permission-section">

                    {{-- DASHBOARD --}}
                    <div class="permission-group permission-group-dashboard">

                        <h3>
                            Dashboard
                        </h3>

                        <label class="permission-item">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="dashboard.view"
                                {{ in_array(
                                    'dashboard.view',
                                    old('permissions', [])
                                ) ? 'checked' : '' }}
                            >

                            <span>
                                Lihat Dashboard
                            </span>
                        </label>

                    </div>


                    {{-- PRODUK --}}
                    <div class="permission-group">

                        <h3>
                            Daftar Barang
                        </h3>

                        @foreach ([
                            'products.view' => 'Lihat Produk',
                            'products.create' => 'Tambah Produk',
                            'products.edit' => 'Edit Produk',
                            'products.delete' => 'Hapus Produk',
                        ] as $permission => $label)

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission }}"
                                    {{ in_array(
                                        $permission,
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    {{ $label }}
                                </span>

                            </label>

                        @endforeach

                    </div>


                    {{-- PROMO --}}
                    <div class="permission-group">

                        <h3>
                            Promo Campaign
                        </h3>

                        @foreach ([
                            'promo-campaigns.view' => 'Lihat Promo Campaign',
                            'promo-campaigns.create' => 'Tambah Promo Campaign',
                            'promo-campaigns.edit' => 'Edit Promo Campaign',
                            'promo-campaigns.delete' => 'Hapus Promo Campaign',
                        ] as $permission => $label)

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission }}"
                                    {{ in_array(
                                        $permission,
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    {{ $label }}
                                </span>

                            </label>

                        @endforeach

                    </div>


                    {{-- STOK MASUK --}}
                    <div class="permission-group">

                        <h3>
                            Stok Masuk
                        </h3>

                        @foreach ([
                            'stock-ins.view' => 'Lihat Stok Masuk',
                            'stock-ins.create' => 'Tambah Stok Masuk',
                            'stock-ins.edit' => 'Edit Stok Masuk',
                            'stock-ins.delete' => 'Hapus Stok Masuk',
                        ] as $permission => $label)

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission }}"
                                    {{ in_array(
                                        $permission,
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    {{ $label }}
                                </span>

                            </label>

                        @endforeach

                    </div>


                    {{-- STOK KELUAR --}}
                    <div class="permission-group permission-group-stock-out">

                        <h3>
                            Stok Keluar
                        </h3>

                        @foreach ([
                            'stock-outs.view' => 'Lihat Stok Keluar',
                            'stock-outs.create' => 'Tambah / Jual Stok Keluar',
                            'stock-outs.edit' => 'Edit Stok Keluar',
                            'stock-outs.delete' => 'Hapus Stok Keluar',
                        ] as $permission => $label)

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission }}"
                                    {{ in_array(
                                        $permission,
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    {{ $label }}
                                </span>

                            </label>

                        @endforeach


                        <hr class="permission-divider">


                        <div class="permission-special">

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="stock-outs.verify-payment"
                                    {{ in_array(
                                        'stock-outs.verify-payment',
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    <strong>
                                        Verifikasi Pembayaran Customer
                                    </strong>

                                    <small class="permission-note">
                                        Petugas mengonfirmasi bahwa uang dari customer
                                        sudah diterima.
                                    </small>
                                </span>

                            </label>

                        </div>


                        <div
                            class="permission-special-admin"
                            style="margin-top: 10px;"
                        >

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="stock-outs.confirm-deposit"
                                    {{ in_array(
                                        'stock-outs.confirm-deposit',
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    <strong>
                                        Verifikasi Setoran
                                    </strong>

                                    <small
                                        class="permission-note permission-note-danger"
                                    >
                                        Khusus Admin / Penanggung Jawab Uang.
                                    </small>
                                </span>

                            </label>

                        </div>

                    </div>


                    {{-- TV VOUCHER --}}
                    <div class="permission-group permission-group-voucher">

                        <h3>
                            TV Voucher
                        </h3>

                        @foreach ([
                            'tv-vouchers.view' => 'Lihat TV Voucher',
                            'tv-vouchers.create' => 'Tambah / Isi Paket TV Voucher',
                            'tv-vouchers.edit' => 'Edit TV Voucher',
                            'tv-vouchers.delete' => 'Hapus TV Voucher',
                        ] as $permission => $label)

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission }}"
                                    {{ in_array(
                                        $permission,
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    {{ $label }}
                                </span>

                            </label>

                        @endforeach


                        <hr class="permission-divider">


                        <div class="permission-special">

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="tv-vouchers.verify-payment"
                                    {{ in_array(
                                        'tv-vouchers.verify-payment',
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    <strong>
                                        Verifikasi Pembayaran Customer
                                    </strong>

                                    <small class="permission-note">
                                        Petugas yang isi paket mengonfirmasi
                                        uang customer sudah diterima.
                                    </small>
                                </span>

                            </label>

                        </div>


                        <div
                            class="permission-special-admin"
                            style="margin-top: 10px;"
                        >

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="tv-vouchers.confirm-deposit"
                                    {{ in_array(
                                        'tv-vouchers.confirm-deposit',
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    <strong>
                                        Verifikasi Setoran
                                    </strong>

                                    <small
                                        class="permission-note permission-note-danger"
                                    >
                                        Khusus Admin / Penanggung Jawab Uang.
                                    </small>
                                </span>

                            </label>

                        </div>

                    </div>


                    {{-- SUPPLIER --}}
                    <div class="permission-group">

                        <h3>
                            Supplier Barang
                        </h3>

                        @foreach ([
                            'suppliers.view' => 'Lihat Supplier',
                            'suppliers.create' => 'Tambah Supplier',
                            'suppliers.edit' => 'Edit Supplier',
                            'suppliers.delete' => 'Hapus Supplier',
                        ] as $permission => $label)

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission }}"
                                    {{ in_array(
                                        $permission,
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    {{ $label }}
                                </span>

                            </label>

                        @endforeach

                    </div>


                    {{-- CUSTOMER --}}
                    <div class="permission-group">

                        <h3>
                            Pelanggan
                        </h3>

                        @foreach ([
                            'customers.view' => 'Lihat Pelanggan',
                            'customers.create' => 'Tambah Pelanggan',
                            'customers.edit' => 'Edit Pelanggan',
                            'customers.delete' => 'Hapus Pelanggan',
                        ] as $permission => $label)

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission }}"
                                    {{ in_array(
                                        $permission,
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    {{ $label }}
                                </span>

                            </label>

                        @endforeach

                    </div>


                    {{-- REPORT --}}
                    <div class="permission-group">

                        <h3>
                            Laporan
                        </h3>

                        <label class="permission-item">

                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="reports.view"
                                {{ in_array(
                                    'reports.view',
                                    old('permissions', [])
                                ) ? 'checked' : '' }}
                            >

                            <span>
                                Lihat Laporan
                            </span>

                        </label>

                    </div>


                    {{-- USER MANAGEMENT --}}
                    <div class="permission-group permission-group-users">

                        <h3>
                            User Management
                        </h3>

                        @foreach ([
                            'users.view' => 'Lihat User',
                            'users.create' => 'Tambah User',
                            'users.edit' => 'Edit User',
                            'users.delete' => 'Hapus User',
                        ] as $permission => $label)

                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission }}"
                                    {{ in_array(
                                        $permission,
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >

                                <span>
                                    {{ $label }}
                                </span>

                            </label>

                        @endforeach

                    </div>

                </div>


                @error('permissions')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror


                <div class="button-area">

                    <button
                        type="submit"
                        class="button-save"
                    >
                        Simpan Akun
                    </button>

                    <a
                        href="{{ route('users.index') }}"
                        class="button-back"
                    >
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </main>

</div>


<form
    id="idleLogoutForm"
    action="{{ route('logout') }}"
    method="POST"
    style="display: none;"
>
    @csrf
</form>


<script src="{{ asset('js/idle-timeout.js') }}"></script>


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