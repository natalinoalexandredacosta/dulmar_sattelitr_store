<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>TV Voucher - Dulmar Satellite Store</title>

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
            background: #f4f6f9;
            overflow-x: hidden;
        }

        body.menu-open,
        body.modal-open {
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

            padding: 30px 25px;

            background: #1f2b3a;
            color: white;

            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar h1 {
            margin: 0 0 35px;
            font-size: 27px;
        }

        .sidebar-menu {
            flex: 1;
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
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar-menu a.active {
            padding-left: 14px;

            border-left: 4px solid #60a5fa;

            background: rgba(37, 99, 235, 0.3);
            color: #bfdbfe;

            font-weight: bold;
        }

        .report-submenu {
            padding-left: 25px !important;
            font-size: 15px !important;
        }

        .logout-form {
            margin-top: 20px;
        }

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

            margin-bottom: 30px;
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

        .button-add {
            display: inline-block;

            padding: 15px 21px;

            border-radius: 8px;

            background: #7c3aed;
            color: white;

            font-size: 17px;
            text-decoration: none;
        }

        .button-add:hover {
            background: #6d28d9;
        }

        .alert-success,
        .alert-error {
            margin-bottom: 25px;
            padding: 15px 20px;

            border-radius: 7px;
        }

        .alert-success {
            border: 1px solid #86efac;

            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            border: 1px solid #fca5a5;

            background: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .filter-card {
            margin-bottom: 25px;
            padding: 22px;

            border-radius: 10px;

            background: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);
        }

        .filter-card h3 {
            margin: 0 0 18px;
        }

        .filter-form {
            display: grid;

            grid-template-columns:
                minmax(220px, 2fr)
                repeat(3, minmax(150px, 1fr));

            gap: 13px;
        }

        .filter-dates {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(170px, 1fr))
                auto;

            gap: 13px;

            margin-top: 13px;
        }

        .filter-actions {
            display: flex;
            align-items: flex-end;

            gap: 10px;
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
            height: 44px;

            padding: 10px 12px;

            border: 1px solid #d1d5db;
            border-radius: 6px;

            background: white;

            font-size: 15px;
        }

        .form-control:focus {
            border-color: #2563eb;
            outline: none;
        }

        .button-filter,
        .button-reset {
            height: 44px;

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
            background: #2563eb;
        }

        .button-reset {
            background: #6b7280;
        }

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        .summary-grid {
            display: grid;

            grid-template-columns:
                repeat(5, minmax(170px, 1fr));

            gap: 16px;

            margin-bottom: 16px;
        }

        .summary-payment-grid {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(220px, 1fr));

            gap: 16px;

            margin-bottom: 25px;
        }

        .summary-card {
            padding: 20px;

            border-radius: 10px;

            background: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);
        }

        .summary-card h3 {
            margin: 0 0 10px;

            color: #6b7280;

            font-size: 14px;
        }

        .summary-card strong {
            display: block;
            font-size: 24px;
        }

        .summary-transactions {
            border-left: 5px solid #2563eb;
        }

        .summary-quantity {
            border-left: 5px solid #7c3aed;
        }

        .summary-total {
            border-left: 5px solid #f59e0b;
        }

        .summary-paid {
            border-left: 5px solid #16a34a;
        }

        .summary-unpaid {
            border-left: 5px solid #dc2626;
        }

        .summary-cash {
            border-left: 5px solid #0f766e;
        }

        .summary-bank {
            border-left: 5px solid #0284c7;
        }

        .summary-cash strong {
            color: #0f766e;
        }

        .summary-bank strong {
            color: #0284c7;
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

            background: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);
        }

        table {
            width: 100%;
            min-width: 3050px;

            border-collapse: collapse;

            background: white;
        }

        thead {
            background: #edf2f7;
        }

        th,
        td {
            padding: 13px;

            border-bottom: 1px solid #d1d5db;

            font-size: 13px;

            text-align: left;
            vertical-align: middle;

            white-space: nowrap;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .amount {
            color: #7c3aed;
            font-weight: bold;
        }

        .amount-green {
            color: #15803d;
            font-weight: bold;
        }

        .amount-red {
            color: #dc2626;
            font-weight: bold;
        }

        .filled-by {
            color: #1d4ed8;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT METHOD
        |--------------------------------------------------------------------------
        */

        .payment-method {
            display: inline-block;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 12px;
            font-weight: bold;
        }

        .payment-method-cash {
            background: #ccfbf1;
            color: #115e59;
        }

        .payment-method-bank {
            background: #e0f2fe;
            color: #075985;
        }

        .payment-method-none {
            background: #e5e7eb;
            color: #4b5563;
        }

        .bank-name {
            color: #0369a1;
            font-weight: bold;
        }

        .bank-completed {
            display: inline-block;

            padding: 7px 10px;

            border-radius: 6px;

            background: #e0f2fe;
            color: #075985;

            font-size: 12px;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        .status {
            display: inline-block;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 12px;
            font-weight: bold;
        }

        .status-success,
        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending,
        .status-partial {
            background: #fef3c7;
            color: #92400e;
        }

        .status-failed,
        .status-unpaid {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-no-money {
            background: #e5e7eb;
            color: #4b5563;
        }

        .status-unclassified {
            background: #ffedd5;
            color: #9a3412;
        }

        /*
        |--------------------------------------------------------------------------
        | PROOF
        |--------------------------------------------------------------------------
        */

        .proof-link {
            display: inline-block;

            padding: 7px 10px;

            border-radius: 6px;

            background: #eff6ff;
            color: #1d4ed8;

            font-size: 12px;
            font-weight: bold;

            text-decoration: none;
        }

        .proof-link:hover {
            background: #dbeafe;
        }

        /*
        |--------------------------------------------------------------------------
        | ACTION
        |--------------------------------------------------------------------------
        */

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            align-items: center;

            gap: 7px;

            min-width: 350px;
        }

        .action-buttons form {
            margin: 0;
        }

        .button-payment,
        .button-method,
        .button-deposit,
        .button-edit,
        .button-delete {
            padding: 8px 13px;

            border: none;
            border-radius: 6px;

            color: white;

            font-size: 13px;

            cursor: pointer;

            white-space: nowrap;
        }

        .button-payment {
            background: #2563eb;
        }

        .button-payment:hover {
            background: #1d4ed8;
        }

        .button-method {
            background: #0f766e;
        }

        .button-method:hover {
            background: #115e59;
        }

        .button-deposit {
            background: #16a34a;
        }

        .button-deposit:hover {
            background: #15803d;
        }

        .button-edit {
            display: inline-block;

            background: #f59e0b;

            text-decoration: none;
        }

        .button-edit:hover {
            background: #d97706;
        }

        .button-delete {
            background: #dc2626;
        }

        .button-delete:hover {
            background: #b91c1c;
        }

        .deposit-completed {
            color: #166534;

            font-size: 12px;
            font-weight: bold;
        }

        .deposit-waiting {
            display: inline-block;

            padding: 6px 10px;

            border-radius: 6px;

            background: #fef3c7;
            color: #92400e;

            font-size: 12px;
            font-weight: bold;
        }

        .deposit-no-money {
            color: #6b7280;

            font-size: 12px;
            font-weight: bold;
        }

        .payment-completed {
            color: #166534;

            font-size: 12px;
            font-weight: bold;
        }

        .empty-data {
            padding: 35px;

            color: #6b7280;

            text-align: center;
        }

        /*
        |--------------------------------------------------------------------------
        | MODAL CASH / BANK
        |--------------------------------------------------------------------------
        */

        .payment-modal {
            position: fixed;
            inset: 0;
            z-index: 5000;

            display: none;

            align-items: center;
            justify-content: center;

            padding: 20px;

            background: rgba(15, 23, 42, 0.65);
        }

        .payment-modal.open {
            display: flex;
        }

        .payment-modal-card {
            width: 100%;
            max-width: 520px;

            border-radius: 14px;

            background: white;

            overflow: hidden;

            box-shadow:
                0 20px 50px
                rgba(0, 0, 0, 0.28);
        }

        .payment-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            padding: 20px 22px;

            border-bottom: 1px solid #e5e7eb;
        }

        .payment-modal-header h3 {
            margin: 0;

            color: #111827;

            font-size: 21px;
        }

        .modal-close {
            width: 36px;
            height: 36px;

            border: none;
            border-radius: 50%;

            background: #f3f4f6;
            color: #374151;

            font-size: 22px;

            cursor: pointer;
        }

        .payment-modal-body {
            padding: 22px;
        }

        .transaction-info {
            margin-bottom: 20px;
            padding: 14px 16px;

            border-radius: 8px;

            background: #f8fafc;

            color: #374151;

            line-height: 1.7;
        }

        .transaction-info strong {
            color: #111827;
        }

        .modal-form-group {
            margin-bottom: 18px;
        }

        .modal-form-group label {
            display: block;

            margin-bottom: 8px;

            color: #374151;

            font-size: 14px;
            font-weight: bold;
        }

        .modal-input {
            width: 100%;

            padding: 11px 12px;

            border: 1px solid #d1d5db;
            border-radius: 7px;

            background: white;

            font-size: 15px;
        }

        .modal-input:focus {
            border-color: #2563eb;
            outline: none;

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, 0.10);
        }

        .method-options {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 12px;
        }

        .method-option {
            position: relative;
        }

        .method-option input {
            position: absolute;

            opacity: 0;
            pointer-events: none;
        }

        .method-option label {
            display: flex;

            min-height: 75px;

            align-items: center;
            justify-content: center;

            padding: 14px;

            border: 2px solid #e5e7eb;
            border-radius: 10px;

            background: white;

            color: #374151;

            font-size: 16px;
            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;
        }

        .method-option input:checked + label {
            border-color: #2563eb;

            background: #eff6ff;
            color: #1d4ed8;
        }

        .bank-fields {
            display: none;

            padding-top: 4px;
        }

        .bank-fields.show {
            display: block;
        }

        .proof-help {
            margin-top: 7px;

            color: #6b7280;

            font-size: 12px;
            line-height: 1.5;
        }

        .existing-proof-message {
            margin-bottom: 12px;
            padding: 10px 12px;

            border-radius: 7px;

            background: #ecfdf5;
            color: #166534;

            font-size: 13px;
        }

        .payment-modal-footer {
            display: flex;
            justify-content: flex-end;

            gap: 10px;

            padding: 17px 22px;

            border-top: 1px solid #e5e7eb;

            background: #f8fafc;
        }

        .button-modal-cancel,
        .button-modal-save {
            padding: 10px 18px;

            border: none;
            border-radius: 7px;

            color: white;

            font-size: 14px;
            font-weight: bold;

            cursor: pointer;
        }

        .button-modal-cancel {
            background: #6b7280;
        }

        .button-modal-save {
            background: #2563eb;
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 12px;

            margin-top: 18px;
            padding: 14px 16px;

            border-radius: 8px;

            background: white;
        }

        .pagination a,
        .pagination .disabled {
            padding: 9px 15px;

            border-radius: 6px;

            text-decoration: none;
        }

        .pagination a {
            background: #7c3aed;
            color: white;
        }

        .pagination .disabled {
            background: #e5e7eb;
            color: #9ca3af;
        }

        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        .sidebar-toggle,
        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1250px) {

            .summary-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .filter-form {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
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

                background:
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

                transform: translateX(-105%);

                transition:
                    transform 0.25s ease;
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .main-content {
                width: 100%;

                margin-left: 0;

                padding:
                    85px
                    14px
                    30px;
            }

            .page-header {
                flex-direction: column;
            }

            .button-add {
                width: 100%;
                text-align: center;
            }

            .filter-form,
            .filter-dates,
            .summary-grid,
            .summary-payment-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                display: grid;
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

            .payment-modal {
                padding: 10px;
            }

            .method-options {
                grid-template-columns: 1fr;
            }

            .payment-modal-footer {
                display: grid;
                grid-template-columns: 1fr;
            }

            .button-modal-cancel,
            .button-modal-save {
                width: 100%;
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

        <h1>
            Dulmar Satellite Store
        </h1>

        <nav class="sidebar-menu">

            @can('dashboard.view')

                <a
                    href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >
                    Dashboard
                </a>

            @endcan


            @can('products.view')

                <a
                    href="{{ route('products.index') }}"
                    class="{{ request()->routeIs('products.*') ? 'active' : '' }}"
                >
                    Daftar Barang
                </a>

            @endcan


            @can('promo-campaigns.view')

                <a
                    href="{{ route('promo-campaigns.index') }}"
                    class="{{ request()->routeIs('promo-campaigns.*') ? 'active' : '' }}"
                >
                    Promo Campaign
                </a>

            @endcan


            @can('stock-ins.view')

                <a
                    href="{{ route('stock-ins.index') }}"
                    class="{{ request()->routeIs('stock-ins.*') ? 'active' : '' }}"
                >
                    Stok Masuk
                </a>

            @endcan


            @can('stock-outs.view')

                <a
                    href="{{ route('stock-outs.index') }}"
                    class="{{ request()->routeIs('stock-outs.*') ? 'active' : '' }}"
                >
                    Stok Keluar
                </a>

            @endcan


            @can('tv-vouchers.view')

                <a
                    href="{{ route('tv-vouchers.index') }}"
                    class="{{ request()->routeIs('tv-vouchers.index') ? 'active' : '' }}"
                >
                    TV Voucher
                </a>

                <a
                    href="{{ route('tv-vouchers.report') }}"
                    class="report-submenu {{ request()->routeIs('tv-vouchers.report') ? 'active' : '' }}"
                >
                    ↳ Laporan TV Voucher
                </a>

            @endcan


            @can('suppliers.view')

                <a
                    href="{{ route('suppliers.index') }}"
                    class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
                >
                    Supplier Barang
                </a>

            @endcan


            @can('customers.view')

                <a
                    href="{{ route('customers.index') }}"
                    class="{{ request()->routeIs('customers.*') ? 'active' : '' }}"
                >
                    Pelanggan
                </a>

            @endcan


            @can('reports.view')

                <a
                    href="{{ route('reports.index') }}"
                    class="{{ request()->routeIs('reports.*') ? 'active' : '' }}"
                >
                    Laporan
                </a>

            @endcan


            @can('users.view')

                <a
                    href="{{ route('users.index') }}"
                    class="{{ request()->routeIs('users.*') ? 'active' : '' }}"
                >
                    User Management
                </a>

            @endcan

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

        <div class="page-header">

            <div>

                <h2>
                    TV Voucher
                </h2>

                <p>
                    Kelola pembayaran CASH / BANK,
                    nama bank, bukti pembayaran dan setoran petugas.
                </p>

            </div>


            @can('tv-vouchers.create')

                <a
                    href="{{ route('tv-vouchers.create') }}"
                    class="button-add"
                >
                    + Tambah TV Voucher
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

            <h3>
                Pencarian dan Filter
            </h3>


            <form
                action="{{ route('tv-vouchers.index') }}"
                method="GET"
            >

                <div class="filter-form">

                    <div class="form-group">

                        <label>
                            Cari
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ $search }}"
                            placeholder="Pelanggan, receiver, bank..."
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Provider
                        </label>

                        <select
                            name="provider"
                            class="form-control"
                        >

                            <option value="">
                                Semua Provider
                            </option>

                            <option
                                value="K-Vision"
                                {{ $provider === 'K-Vision' ? 'selected' : '' }}
                            >
                                K-Vision
                            </option>

                            <option
                                value="Nex Parabola"
                                {{ $provider === 'Nex Parabola' ? 'selected' : '' }}
                            >
                                Nex Parabola
                            </option>

                            <option
                                value="Nusantara HD"
                                {{ $provider === 'Nusantara HD' ? 'selected' : '' }}
                            >
                                Nusantara HD
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>
                            Status Isi Ulang
                        </label>

                        <select
                            name="recharge_status"
                            class="form-control"
                        >

                            <option value="">
                                Semua
                            </option>

                            <option
                                value="pending"
                                {{ $rechargeStatus === 'pending' ? 'selected' : '' }}
                            >
                                Menunggu
                            </option>

                            <option
                                value="success"
                                {{ $rechargeStatus === 'success' ? 'selected' : '' }}
                            >
                                Berhasil
                            </option>

                            <option
                                value="failed"
                                {{ $rechargeStatus === 'failed' ? 'selected' : '' }}
                            >
                                Gagal
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>
                            Status Setoran
                        </label>

                        <select
                            name="payment_status"
                            class="form-control"
                        >

                            <option value="">
                                Semua
                            </option>

                            <option
                                value="unpaid"
                                {{ $paymentStatus === 'unpaid' ? 'selected' : '' }}
                            >
                                Belum Lunas
                            </option>

                            <option
                                value="paid"
                                {{ $paymentStatus === 'paid' ? 'selected' : '' }}
                            >
                                Selesai
                            </option>

                        </select>

                    </div>

                </div>


                <div class="filter-dates">

                    <div class="form-group">

                        <label>
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ $startDate }}"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ $endDate }}"
                        >

                    </div>


                    <div class="filter-actions">

                        <button
                            type="submit"
                            class="button-filter"
                        >
                            Terapkan
                        </button>

                        <a
                            href="{{ route('tv-vouchers.index') }}"
                            class="button-reset"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </section>


        <section class="summary-grid">

            <article class="summary-card summary-transactions">
                <h3>Jumlah Transaksi</h3>
                <strong>{{ $totalTransactions }}</strong>
            </article>

            <article class="summary-card summary-quantity">
                <h3>Total Voucher</h3>
                <strong>{{ $totalQuantity }}</strong>
            </article>

            <article class="summary-card summary-total">
                <h3>Total Transaksi</h3>
                <strong>${{ number_format($totalAmount, 2) }}</strong>
            </article>

            <article class="summary-card summary-paid">
                <h3>Cash Sudah Disetor</h3>
                <strong>${{ number_format($totalPaid, 2) }}</strong>
            </article>

            <article class="summary-card summary-unpaid">
                <h3>Cash Belum Disetor</h3>
                <strong>${{ number_format($totalUnpaid, 2) }}</strong>
            </article>

        </section>


        <section class="summary-payment-grid">

            <article class="summary-card summary-cash">

                <h3>
                    💵 Total CASH
                </h3>

                <strong>
                    ${{ number_format($totalCash ?? 0, 2) }}
                </strong>

            </article>


            <article class="summary-card summary-bank">

                <h3>
                    🏦 Total BANK
                </h3>

                <strong>
                    ${{ number_format($totalBank ?? 0, 2) }}
                </strong>

            </article>

        </section>


        <div class="table-card">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Diisi Oleh</th>
                        <th>Provider</th>
                        <th>Receiver</th>
                        <th>Paket</th>

                        <th>Total</th>

                        <th>Customer Bayar</th>
                        <th>Sisa Customer</th>
                        <th>Status Customer</th>

                        <th>Metode</th>
                        <th>Nama Bank</th>

                        <th>Cash Petugas</th>
                        <th>Sudah Setor</th>
                        <th>Belum Setor</th>

                        <th>Status Dana</th>
                        <th>Status Isi Ulang</th>

                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($tvVouchers as $tvVoucher)

                        @php

                            $customerPaid =
                                (float) $tvVoucher
                                    ->customer_paid_amount;

                            $customerBalance =
                                (float) $tvVoucher
                                    ->customer_balance;

                            $staffReceived =
                                (float) $tvVoucher
                                    ->staff_received_amount;

                            $staffDeposited =
                                (float) $tvVoucher
                                    ->staff_deposited_amount;

                            $staffBalance =
                                (float) $tvVoucher
                                    ->staff_balance;

                            $paymentMethod =
                                $tvVoucher
                                    ->payment_method;

                            $isCash =
                                $paymentMethod === 'cash';

                            $isBank =
                                $paymentMethod === 'bank';

                            $customerName =
                                $tvVoucher->customer_name
                                ?: (
                                    $tvVoucher->customer?->customer_name
                                    ?? '-'
                                );

                        @endphp


                        <tr>

                            <td>
                                {{
                                    $tvVouchers->firstItem()
                                    + $loop->index
                                }}
                            </td>


                            <td>
                                {{
                                    \Carbon\Carbon::parse(
                                        $tvVoucher->transaction_date
                                    )->format('d-m-Y')
                                }}
                            </td>


                            <td>
                                {{ $customerName }}
                            </td>


                            <td class="filled-by">
                                {{ $tvVoucher->filled_by ?: '-' }}
                            </td>


                            <td>
                                {{ $tvVoucher->provider }}
                            </td>


                            <td>
                                {{ $tvVoucher->receiver_number }}
                            </td>


                            <td>
                                {{ $tvVoucher->package_name }}
                            </td>


                            <td class="amount">
                                ${{ number_format(
                                    $tvVoucher->total_amount,
                                    2
                                ) }}
                            </td>


                            <td class="amount-green">
                                ${{ number_format(
                                    $customerPaid,
                                    2
                                ) }}
                            </td>


                            <td class="amount-red">
                                ${{ number_format(
                                    $customerBalance,
                                    2
                                ) }}
                            </td>


                            <td>

                                @if (
                                    $tvVoucher->customer_payment_status
                                    === 'paid'
                                )

                                    <span class="status status-paid">
                                        Lunas
                                    </span>

                                @elseif (
                                    $tvVoucher->customer_payment_status
                                    === 'partial'
                                )

                                    <span class="status status-partial">
                                        Bayar Sebagian
                                    </span>

                                @else

                                    <span class="status status-unpaid">
                                        Belum Bayar
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if ($isCash)

                                    <span
                                        class="payment-method payment-method-cash"
                                    >
                                        💵 CASH
                                    </span>

                                @elseif ($isBank)

                                    <span
                                        class="payment-method payment-method-bank"
                                    >
                                        🏦 BANK
                                    </span>

                                @else

                                    <span
                                        class="payment-method payment-method-none"
                                    >
                                        Belum Diatur
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if ($isBank)

                                    <span class="bank-name">
                                        {{ $tvVoucher->bank_name ?: '-' }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            <td>
                                ${{ number_format(
                                    $isBank
                                        ? 0
                                        : $staffReceived,
                                    2
                                ) }}
                            </td>


                            <td class="amount-green">
                                ${{ number_format(
                                    $isBank
                                        ? 0
                                        : $staffDeposited,
                                    2
                                ) }}
                            </td>


                            <td class="amount-red">
                                ${{ number_format(
                                    $isBank
                                        ? 0
                                        : $staffBalance,
                                    2
                                ) }}
                            </td>


                            <td>

                                @if ($isBank && $customerPaid > 0)

                                    <span class="bank-completed">
                                        🏦 Masuk Bank
                                    </span>

                                @elseif ($isCash)

                                    @if ($staffReceived <= 0)

                                        <span class="status status-no-money">
                                            Belum Ada Uang
                                        </span>

                                    @elseif ($staffBalance <= 0)

                                        <span class="status status-paid">
                                            Sudah Setor
                                        </span>

                                    @elseif ($staffDeposited > 0)

                                        <span class="status status-partial">
                                            Setor Sebagian
                                        </span>

                                    @else

                                        <span class="status status-unpaid">
                                            Belum Setor
                                        </span>

                                    @endif

                                @elseif ($customerPaid > 0)

                                    <span class="status status-unclassified">
                                        ⚠ Metode Belum Diatur
                                    </span>

                                @else

                                    <span class="status status-no-money">
                                        Belum Ada Pembayaran
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span
                                    class="status status-{{ $tvVoucher->recharge_status }}"
                                >
                                    {{ $tvVoucher->recharge_status_label }}
                                </span>

                            </td>


                            <td>

                                @if ($tvVoucher->payment_proof)

                                    <a
                                        href="{{ Storage::url(
                                            $tvVoucher->payment_proof
                                        ) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="proof-link"
                                    >
                                        📎 Lihat Bukti
                                    </a>

                                @else

                                    <span style="color:#9ca3af;">
                                        Belum Ada
                                    </span>

                                @endif

                            </td>


                            <td>

                                <div class="action-buttons">


                                    {{-- =====================================================
                                         TRANSAKSI LAMA - ATUR CASH / BANK
                                    ====================================================== --}}

                                    @can('tv-vouchers.confirm-deposit')

                                        @if (
                                            !$isCash
                                            && !$isBank
                                            && $customerPaid > 0
                                        )

                                            <button
                                                type="button"
                                                class="button-method open-payment-modal"

                                                data-action="{{ route(
                                                    'tv-vouchers.payment-method',
                                                    $tvVoucher
                                                ) }}"

                                                data-customer="{{ $customerName }}"

                                                data-paid="{{ $customerPaid }}"

                                                data-has-proof="{{ $tvVoucher->payment_proof ? '1' : '0' }}"
                                            >
                                                Atur CASH / BANK
                                            </button>

                                        @endif

                                    @endcan


                                    {{-- =====================================================
                                         VERIFIKASI CUSTOMER
                                    ====================================================== --}}

                                    @can('tv-vouchers.verify-payment')

                                        @if ($customerBalance > 0)

                                            <form
                                                action="{{ route(
                                                    'tv-vouchers.verify-payment',
                                                    $tvVoucher
                                                ) }}"
                                                method="POST"
                                                class="verify-payment-form"

                                                data-customer="{{ $customerName }}"

                                                data-remaining="{{ $customerBalance }}"
                                            >

                                                @csrf
                                                @method('PATCH')


                                                <input
                                                    type="hidden"
                                                    name="payment_amount"
                                                    value=""
                                                >


                                                <input
                                                    type="hidden"
                                                    name="payment_method"
                                                    value=""
                                                >


                                                <input
                                                    type="hidden"
                                                    name="bank_name"
                                                    value=""
                                                >


                                                <button
                                                    type="submit"
                                                    class="button-payment"
                                                >
                                                    Verifikasi Pembayaran
                                                </button>

                                            </form>

                                        @elseif (
                                            $tvVoucher->customer_payment_status
                                            === 'paid'
                                        )

                                            <span class="payment-completed">
                                                ✓ Customer Lunas
                                            </span>

                                        @endif

                                    @endcan


                                    {{-- =====================================================
                                         CASH
                                    ====================================================== --}}

                                    @if ($isCash)

                                        @can('tv-vouchers.confirm-deposit')

                                            @if (
                                                $staffReceived > 0
                                                && $staffBalance > 0
                                            )

                                                <form
                                                    action="{{ route(
                                                        'tv-vouchers.confirm-deposit',
                                                        $tvVoucher
                                                    ) }}"
                                                    method="POST"
                                                    class="deposit-form"

                                                    data-staff="{{ $tvVoucher->filled_by ?: 'Petugas' }}"

                                                    data-balance="{{ $staffBalance }}"
                                                >

                                                    @csrf
                                                    @method('PATCH')


                                                    <button
                                                        type="submit"
                                                        class="button-deposit"
                                                    >
                                                        Konfirmasi Setoran
                                                    </button>

                                                </form>

                                            @elseif (
                                                $staffReceived > 0
                                                && $staffBalance <= 0
                                            )

                                                <div class="deposit-completed">

                                                    ✓ Sudah Setor

                                                    @if (
                                                        $tvVoucher->staff_deposited_at
                                                    )

                                                        <br>

                                                        {{
                                                            \Carbon\Carbon::parse(
                                                                $tvVoucher->staff_deposited_at
                                                            )->format('d-m-Y H:i')
                                                        }}

                                                    @endif

                                                </div>

                                            @else

                                                <div class="deposit-no-money">
                                                    Belum Ada Cash
                                                </div>

                                            @endif

                                        @else

                                            @if (
                                                $staffReceived > 0
                                                && $staffBalance > 0
                                            )

                                                <span class="deposit-waiting">
                                                    ⏳ Menunggu Konfirmasi Admin
                                                </span>

                                            @elseif (
                                                $staffReceived > 0
                                                && $staffBalance <= 0
                                            )

                                                <span class="deposit-completed">
                                                    ✓ Sudah Setor
                                                </span>

                                            @endif

                                        @endcan


                                    {{-- =====================================================
                                         BANK
                                    ====================================================== --}}

                                    @elseif ($isBank)

                                        <div class="bank-completed">

                                            🏦 Masuk Bank

                                            @if ($tvVoucher->bank_name)

                                                <br>

                                                {{ $tvVoucher->bank_name }}

                                            @endif

                                        </div>

                                    @endif


                                    {{-- =====================================================
                                         EDIT
                                    ====================================================== --}}

                                    @can('tv-vouchers.edit')

                                        <a
                                            href="{{ route(
                                                'tv-vouchers.edit',
                                                $tvVoucher
                                            ) }}"
                                            class="button-edit"
                                        >
                                            Edit
                                        </a>

                                    @endcan


                                    {{-- =====================================================
                                         HAPUS
                                    ====================================================== --}}

                                    @can('tv-vouchers.delete')

                                        @if (
                                            $tvVoucher->recharge_status !== 'success'
                                            && $staffDeposited <= 0
                                            && $customerPaid <= 0
                                        )

                                            <form
                                                action="{{ route(
                                                    'tv-vouchers.destroy',
                                                    $tvVoucher
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm('Hapus transaksi ini?')"
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

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="20"
                                class="empty-data"
                            >
                                Belum ada transaksi TV Voucher.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if ($tvVouchers->hasPages())

            <nav class="pagination">

                @if ($tvVouchers->onFirstPage())

                    <span class="disabled">
                        Sebelumnya
                    </span>

                @else

                    <a href="{{ $tvVouchers->previousPageUrl() }}">
                        Sebelumnya
                    </a>

                @endif


                <span>

                    Halaman
                    {{ $tvVouchers->currentPage() }}
                    dari
                    {{ $tvVouchers->lastPage() }}

                </span>


                @if ($tvVouchers->hasMorePages())

                    <a href="{{ $tvVouchers->nextPageUrl() }}">
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


{{-- ============================================================
     MODAL ATUR CASH / BANK
============================================================ --}}

<div
    id="paymentMethodModal"
    class="payment-modal"
>

    <div class="payment-modal-card">

        <div class="payment-modal-header">

            <h3>
                Atur Metode Pembayaran
            </h3>

            <button
                type="button"
                id="closePaymentModal"
                class="modal-close"
            >
                ×
            </button>

        </div>


        <form
            id="paymentMethodForm"
            action=""
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PATCH')


            <div class="payment-modal-body">

                <div class="transaction-info">

                    <div>
                        Customer:
                        <strong id="modalCustomer">
                            -
                        </strong>
                    </div>

                    <div>
                        Jumlah pembayaran:
                        <strong id="modalPaidAmount">
                            $0.00
                        </strong>
                    </div>

                </div>


                <div class="modal-form-group">

                    <label>
                        Metode Pembayaran
                    </label>

                    <div class="method-options">

                        <div class="method-option">

                            <input
                                type="radio"
                                id="methodCash"
                                name="payment_method"
                                value="cash"
                            >

                            <label for="methodCash">
                                💵 CASH
                            </label>

                        </div>


                        <div class="method-option">

                            <input
                                type="radio"
                                id="methodBank"
                                name="payment_method"
                                value="bank"
                            >

                            <label for="methodBank">
                                🏦 BANK
                            </label>

                        </div>

                    </div>

                </div>


                <div
                    id="bankFields"
                    class="bank-fields"
                >

                    <div class="modal-form-group">

                        <label for="bankName">
                            Nama Bank
                        </label>

                        <input
                            type="text"
                            id="bankName"
                            name="bank_name"
                            class="modal-input"
                            maxlength="100"
                            placeholder="Contoh: BNU, BNCTL, Mandiri, BRI"
                        >

                    </div>


                    <div
                        id="existingProofMessage"
                        class="existing-proof-message"
                        style="display:none;"
                    >
                        ✓ Transaksi ini sudah memiliki bukti pembayaran.
                        Anda boleh menggunakan bukti yang sudah ada atau
                        memilih bukti baru.
                    </div>


                    <div class="modal-form-group">

                        <label for="paymentProof">
                            Bukti Transfer
                        </label>

                        <input
                            type="file"
                            id="paymentProof"
                            name="payment_proof"
                            class="modal-input"
                            accept="image/jpeg,image/png,image/webp"
                        >

                        <div class="proof-help">
                            Format JPG, JPEG, PNG atau WEBP.
                            Maksimal 5 MB.
                        </div>

                    </div>

                </div>

            </div>


            <div class="payment-modal-footer">

                <button
                    type="button"
                    id="cancelPaymentModal"
                    class="button-modal-cancel"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="button-modal-save"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>


<form
    id="idleLogoutForm"
    action="{{ route('logout') }}"
    method="POST"
    style="display:none;"
>
    @csrf
</form>


<script src="{{ asset('js/idle-timeout.js') }}"></script>


<script>
    /*
    |--------------------------------------------------------------------------
    | SIDEBAR
    |--------------------------------------------------------------------------
    */

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

        document.body.classList.remove(
            'menu-open'
        );
    }


    sidebarToggle.addEventListener(
        'click',
        function () {

            const open =
                sidebar.classList.toggle(
                    'sidebar-open'
                );

            sidebarOverlay.classList.toggle(
                'overlay-open',
                open
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


    /*
    |--------------------------------------------------------------------------
    | MODAL CASH / BANK
    |--------------------------------------------------------------------------
    */

    const paymentModal =
        document.getElementById(
            'paymentMethodModal'
        );

    const paymentMethodForm =
        document.getElementById(
            'paymentMethodForm'
        );

    const modalCustomer =
        document.getElementById(
            'modalCustomer'
        );

    const modalPaidAmount =
        document.getElementById(
            'modalPaidAmount'
        );

    const methodCash =
        document.getElementById(
            'methodCash'
        );

    const methodBank =
        document.getElementById(
            'methodBank'
        );

    const bankFields =
        document.getElementById(
            'bankFields'
        );

    const bankName =
        document.getElementById(
            'bankName'
        );

    const paymentProof =
        document.getElementById(
            'paymentProof'
        );

    const existingProofMessage =
        document.getElementById(
            'existingProofMessage'
        );

    const closePaymentModal =
        document.getElementById(
            'closePaymentModal'
        );

    const cancelPaymentModal =
        document.getElementById(
            'cancelPaymentModal'
        );


    let currentHasProof =
        false;


    function resetPaymentModal() {

        paymentMethodForm.reset();

        paymentMethodForm.action =
            '';

        modalCustomer.textContent =
            '-';

        modalPaidAmount.textContent =
            '$0.00';

        bankFields.classList.remove(
            'show'
        );

        existingProofMessage.style.display =
            'none';

        currentHasProof =
            false;
    }


    function openModal(
        action,
        customer,
        paid,
        hasProof
    ) {

        resetPaymentModal();


        paymentMethodForm.action =
            action;


        modalCustomer.textContent =
            customer;


        modalPaidAmount.textContent =
            '$'
            + Number(
                paid
                || 0
            ).toFixed(2);


        currentHasProof =
            hasProof === '1';


        if (currentHasProof) {

            existingProofMessage.style.display =
                'block';
        }


        paymentModal.classList.add(
            'open'
        );


        document.body.classList.add(
            'modal-open'
        );
    }


    function closeModal() {

        paymentModal.classList.remove(
            'open'
        );

        document.body.classList.remove(
            'modal-open'
        );

        resetPaymentModal();
    }


    document
        .querySelectorAll(
            '.open-payment-modal'
        )
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    openModal(
                        button.dataset.action,
                        button.dataset.customer,
                        button.dataset.paid,
                        button.dataset.hasProof
                    );
                }
            );
        });


    methodCash.addEventListener(
        'change',
        function () {

            if (methodCash.checked) {

                bankFields.classList.remove(
                    'show'
                );

                bankName.value =
                    '';

                paymentProof.value =
                    '';
            }
        }
    );


    methodBank.addEventListener(
        'change',
        function () {

            if (methodBank.checked) {

                bankFields.classList.add(
                    'show'
                );
            }
        }
    );


    closePaymentModal.addEventListener(
        'click',
        closeModal
    );


    cancelPaymentModal.addEventListener(
        'click',
        closeModal
    );


    paymentModal.addEventListener(
        'click',
        function (event) {

            if (
                event.target
                === paymentModal
            ) {

                closeModal();
            }
        }
    );


    paymentMethodForm.addEventListener(
        'submit',
        function (event) {

            const selectedMethod =
                paymentMethodForm.querySelector(
                    'input[name="payment_method"]:checked'
                );


            if (!selectedMethod) {

                event.preventDefault();

                alert(
                    'Pilih metode pembayaran CASH atau BANK.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | CASH
            |--------------------------------------------------------------------------
            */

            if (
                selectedMethod.value
                === 'cash'
            ) {

                const confirmed =
                    confirm(
                        'Pembayaran akan dicatat sebagai CASH.\n\n'
                        + 'Uang akan dianggap diterima oleh petugas.\n\n'
                        + 'Lanjutkan?'
                    );


                if (!confirmed) {

                    event.preventDefault();
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | BANK
            |--------------------------------------------------------------------------
            */

            const cleanBankName =
                bankName.value.trim();


            if (
                cleanBankName === ''
            ) {

                event.preventDefault();

                alert(
                    'Nama Bank wajib diisi.'
                );

                bankName.focus();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | BUKTI BANK
            |--------------------------------------------------------------------------
            |
            | Jika transaksi belum pernah punya bukti,
            | bukti baru wajib dipilih.
            |
            */

            if (
                !currentHasProof
                &&
                paymentProof.files.length === 0
            ) {

                event.preventDefault();

                alert(
                    'Bukti transfer wajib dipilih untuk pembayaran BANK.'
                );

                paymentProof.focus();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI FILE
            |--------------------------------------------------------------------------
            */

            if (
                paymentProof.files.length > 0
            ) {

                const file =
                    paymentProof.files[0];


                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                ];


                if (
                    !allowedTypes.includes(
                        file.type
                    )
                ) {

                    event.preventDefault();

                    alert(
                        'Bukti harus berupa JPG, JPEG, PNG atau WEBP.'
                    );

                    return;
                }


                const maxSize =
                    5
                    * 1024
                    * 1024;


                if (
                    file.size > maxSize
                ) {

                    event.preventDefault();

                    alert(
                        'Ukuran bukti maksimal 5 MB.'
                    );

                    return;
                }
            }


            const confirmed =
                confirm(
                    'Metode: BANK\n'
                    + 'Bank: '
                    + cleanBankName
                    + '\n\n'
                    + 'Simpan pembayaran ini sebagai BANK?'
                );


            if (!confirmed) {

                event.preventDefault();
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI PEMBAYARAN BARU
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.verify-payment-form'
        )
        .forEach(function (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    event.preventDefault();


                    const customer =
                        form.dataset.customer
                        || 'Customer';


                    const remaining =
                        Number(
                            form.dataset.remaining
                            || 0
                        );


                    const answer =
                        prompt(
                            'Customer: '
                            + customer
                            + '\n'
                            + 'Sisa tagihan: $'
                            + remaining.toFixed(2)
                            + '\n\n'
                            + 'Masukkan pembayaran:'
                        );


                    if (answer === null) {
                        return;
                    }


                    const payment =
                        Number(answer);


                    if (
                        !Number.isFinite(payment)
                        ||
                        payment <= 0
                        ||
                        payment > remaining
                    ) {

                        alert(
                            'Jumlah pembayaran tidak valid.'
                        );

                        return;
                    }


                    let method =
                        prompt(
                            'Ketik CASH atau BANK:'
                        );


                    if (method === null) {
                        return;
                    }


                    method =
                        method
                            .trim()
                            .toLowerCase();


                    if (
                        method !== 'cash'
                        &&
                        method !== 'bank'
                    ) {

                        alert(
                            'Metode harus CASH atau BANK.'
                        );

                        return;
                    }


                    let bankNameValue =
                        '';


                    if (
                        method === 'bank'
                    ) {

                        const bank =
                            prompt(
                                'Masukkan nama Bank:'
                            );


                        if (bank === null) {
                            return;
                        }


                        bankNameValue =
                            bank.trim();


                        if (
                            bankNameValue === ''
                        ) {

                            alert(
                                'Nama Bank wajib diisi.'
                            );

                            return;
                        }
                    }


                    let message =
                        'Customer: '
                        + customer
                        + '\n'
                        + 'Pembayaran: $'
                        + payment.toFixed(2)
                        + '\n'
                        + 'Metode: '
                        + method.toUpperCase();


                    if (
                        method === 'bank'
                    ) {

                        message +=
                            '\nBank: '
                            + bankNameValue;
                    }


                    if (
                        !confirm(
                            message
                            + '\n\nVerifikasi pembayaran?'
                        )
                    ) {

                        return;
                    }


                    form.querySelector(
                        'input[name="payment_amount"]'
                    ).value =
                        payment.toFixed(2);


                    form.querySelector(
                        'input[name="payment_method"]'
                    ).value =
                        method;


                    form.querySelector(
                        'input[name="bank_name"]'
                    ).value =
                        bankNameValue;


                    form.submit();
                }
            );
        });


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI SETORAN CASH
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.deposit-form'
        )
        .forEach(function (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    event.preventDefault();


                    const staff =
                        form.dataset.staff
                        || 'Petugas';


                    const balance =
                        Number(
                            form.dataset.balance
                            || 0
                        );


                    if (
                        !confirm(
                            'Konfirmasi bahwa '
                            + staff
                            + ' sudah menyerahkan Cash sebesar $'
                            + balance.toFixed(2)
                            + '?'
                        )
                    ) {

                        return;
                    }


                    form.submit();
                }
            );
        });


    /*
    |--------------------------------------------------------------------------
    | ESC CLOSE MODAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape'
                &&
                paymentModal.classList.contains(
                    'open'
                )
            ) {

                closeModal();
            }
        }
    );

</script>

</body>
</html>