<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Laporan TV Voucher - Dulmar Satellite Store</title>

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

        body.menu-open {
            overflow: hidden;
        }

        .container {
            width: 100%;
            min-height: 100vh;
        }

        /*
        |--------------------------------------------------------------------------
        | SIDEBAR
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

            padding: 30px 25px;

            overflow-y: auto;
            overflow-x: hidden;

            background: #1f2b3a;
            color: white;
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
            flex-shrink: 0;
            margin-top: 20px;
        }

        .button-logout {
            width: 100%;

            padding: 13px;

            border: none;
            border-radius: 7px;

            background: #dc2626;
            color: white;

            font-size: 16px;

            cursor: pointer;
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN
        |--------------------------------------------------------------------------
        */

        .main-content {
            width: calc(100% - 245px);
            min-width: 0;
            min-height: 100vh;

            margin-left: 245px;
            padding: 45px 32px;
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

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
            line-height: 1.5;
        }

        .page-actions {
            display: flex;
            flex-wrap: wrap;

            gap: 10px;
        }

        .button-back,
        .button-print {
            display: inline-flex;

            min-height: 45px;

            align-items: center;
            justify-content: center;

            padding: 0 19px;

            border: none;
            border-radius: 7px;

            color: white;

            font-size: 15px;
            text-decoration: none;

            cursor: pointer;
        }

        .button-back {
            background: #7c3aed;
        }

        .button-print {
            background: #374151;
        }

        /*
        |--------------------------------------------------------------------------
        | ALERT
        |--------------------------------------------------------------------------
        */

        .alert-error {
            margin-bottom: 25px;

            padding: 15px 20px;

            border: 1px solid #fca5a5;
            border-radius: 7px;

            background: #fee2e2;
            color: #991b1b;
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER / CARD
        |--------------------------------------------------------------------------
        */

        .filter-card,
        .report-card {
            margin-bottom: 25px;

            padding: 23px;

            border-radius: 10px;

            background: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);
        }

        .filter-card h3,
        .report-card h3 {
            margin: 0 0 20px;
            font-size: 21px;
        }

        .filter-form {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(180px, 1fr));

            gap: 15px;
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

        .filter-actions {
            display: flex;
            align-items: flex-end;

            gap: 10px;
        }

        .button-filter,
        .button-reset {
            display: inline-flex;

            height: 44px;

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

        .period-info {
            margin: 18px 0 0;

            color: #4b5563;

            font-size: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        .summary-grid {
            display: grid;

            grid-template-columns:
                repeat(5, minmax(180px, 1fr));

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

            font-size: 23px;
        }

        .summary-blue {
            border-left: 5px solid #2563eb;
        }

        .summary-purple {
            border-left: 5px solid #7c3aed;
        }

        .summary-orange {
            border-left: 5px solid #f59e0b;
        }

        .summary-green {
            border-left: 5px solid #16a34a;
        }

        .summary-red {
            border-left: 5px solid #dc2626;
        }

        .summary-cyan {
            border-left: 5px solid #0891b2;
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
        | STATUS SUMMARY
        |--------------------------------------------------------------------------
        */

        .status-summary-grid {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(200px, 1fr));

            gap: 16px;

            margin-bottom: 25px;
        }

        .status-summary {
            padding: 18px;

            border-radius: 10px;

            background: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);
        }

        .status-summary h3 {
            margin: 0 0 15px;

            font-size: 17px;
        }

        .status-line {
            display: flex;
            justify-content: space-between;

            gap: 15px;

            padding: 9px 0;

            border-bottom: 1px solid #e5e7eb;
        }

        .status-line:last-child {
            border-bottom: none;
        }

        /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */

        .table-wrapper {
            width: 100%;

            overflow-x: auto;

            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        table {
            width: 100%;

            border-collapse: collapse;
        }

        .filler-table {
            min-width: 1350px;
        }

        .provider-table {
            min-width: 1100px;
        }

        .transaction-table {
            min-width: 3100px;
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
            vertical-align: middle;

            white-space: nowrap;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .filler-name {
            color: #2563eb;
            font-weight: bold;
        }

        .amount {
            color: #7c3aed;
            font-weight: bold;
        }

        .paid-amount,
        .deposited-amount {
            color: #16a34a;
            font-weight: bold;
        }

        .balance-amount,
        .not-deposited-amount {
            color: #dc2626;
            font-weight: bold;
        }

        .cash-amount {
            color: #0f766e;
            font-weight: bold;
        }

        .bank-amount {
            color: #0284c7;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | METHOD / STATUS
        |--------------------------------------------------------------------------
        */

        .payment-method {
            display: inline-block;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 12px;
            font-weight: bold;
        }

        .payment-cash {
            background: #ccfbf1;
            color: #115e59;
        }

        .payment-bank {
            background: #e0f2fe;
            color: #075985;
        }

        .payment-none {
            background: #ffedd5;
            color: #9a3412;
        }

        .bank-name {
            color: #0369a1;
            font-weight: bold;
        }

        .status {
            display: inline-block;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 12px;
            font-weight: bold;
        }

        .status-pending,
        .status-partial {
            background: #fef3c7;
            color: #92400e;
        }

        .status-success,
        .status-paid {
            background: #dcfce7;
            color: #166534;
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

        .status-bank {
            background: #e0f2fe;
            color: #075985;
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
            color: #2563eb;

            font-size: 12px;
            font-weight: bold;

            text-decoration: none;
        }

        .proof-link:hover {
            background: #dbeafe;
        }

        .empty-data {
            padding: 35px;

            color: #6b7280;

            text-align: center;
        }

        .total-row {
            background: #f5f3ff;
        }

        .total-row td {
            font-size: 15px;
            font-weight: bold;
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

            .status-summary-grid {
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

                background: rgba(0, 0, 0, 0.5);

                opacity: 0;
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

            .page-header {
                flex-direction: column;
            }

            .page-actions {
                width: 100%;
            }

            .button-back,
            .button-print {
                width: 100%;
            }

            .filter-form,
            .summary-grid,
            .status-summary-grid {
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

            .filter-card,
            .report-card {
                padding: 18px 14px;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PRINT
        |--------------------------------------------------------------------------
        */

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
            .logout-form {
                display: none !important;
            }

            .main-content {
                width: 100%;

                margin-left: 0;
                padding: 0;
            }

            .summary-grid {
                grid-template-columns:
                    repeat(5, 1fr);

                gap: 6px;
            }

            .status-summary-grid {
                grid-template-columns:
                    repeat(3, 1fr);

                gap: 7px;
            }

            .summary-card,
            .status-summary,
            .filter-card,
            .report-card {
                padding: 9px;

                box-shadow: none;

                border: 1px solid #d1d5db;
            }

            .summary-card h3 {
                font-size: 9px;
            }

            .summary-card strong {
                font-size: 14px;
            }

            .table-wrapper {
                overflow: visible;
                border: none;
            }

            table,
            .filler-table,
            .provider-table,
            .transaction-table {
                width: 100%;
                min-width: 0;
            }

            th,
            td {
                padding: 3px;

                font-size: 6px;

                white-space: normal;
            }

            .status,
            .payment-method,
            .proof-link {
                padding: 2px 4px;

                font-size: 6px;
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

                <a
                    href="{{ route('tv-vouchers.index') }}"
                >
                    TV Voucher
                </a>

                <a
                    href="{{ route('tv-vouchers.report') }}"
                    class="report-submenu active"
                >
                    ↳ Laporan TV Voucher
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
                <a href="{{ route('users.index') }}">
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

        <header class="page-header">

            <div>

                <h2>
                    Laporan TV Voucher
                </h2>

                <p>
                    Laporan transaksi, CASH, BANK,
                    nama bank, bukti pembayaran dan setoran petugas.
                </p>

            </div>


            <div class="page-actions">

                <a
                    href="{{ route('tv-vouchers.index') }}"
                    class="button-back"
                >
                    ← Kembali ke TV Voucher
                </a>

                <button
                    type="button"
                    class="button-print"
                    onclick="window.print()"
                >
                    Cetak Laporan
                </button>

            </div>

        </header>


        @if ($errors->any())

            <div class="alert-error">

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =====================================================
             FILTER
        ====================================================== --}}

        <section class="filter-card">

            <h3>
                Filter Laporan
            </h3>


            <form
                action="{{ route('tv-vouchers.report') }}"
                method="GET"
                class="filter-form"
            >

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
                        Metode Pembayaran
                    </label>

                    <select
                        name="payment_method"
                        class="form-control"
                    >

                        <option value="">
                            Semua Metode
                        </option>

                        <option
                            value="cash"
                            {{ $paymentMethod === 'cash' ? 'selected' : '' }}
                        >
                            CASH
                        </option>

                        <option
                            value="bank"
                            {{ $paymentMethod === 'bank' ? 'selected' : '' }}
                        >
                            BANK
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>
                        Status Customer
                    </label>

                    <select
                        name="customer_payment_status"
                        class="form-control"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="unpaid"
                            {{ $customerPaymentStatus === 'unpaid' ? 'selected' : '' }}
                        >
                            Belum Bayar
                        </option>

                        <option
                            value="partial"
                            {{ $customerPaymentStatus === 'partial' ? 'selected' : '' }}
                        >
                            Bayar Sebagian
                        </option>

                        <option
                            value="paid"
                            {{ $customerPaymentStatus === 'paid' ? 'selected' : '' }}
                        >
                            Lunas
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
                            Semua Setoran
                        </option>

                        <option
                            value="unpaid"
                            {{ $paymentStatus === 'unpaid' ? 'selected' : '' }}
                        >
                            Belum Selesai
                        </option>

                        <option
                            value="paid"
                            {{ $paymentStatus === 'paid' ? 'selected' : '' }}
                        >
                            Selesai
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
                            Semua Status
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
                        Diisi Oleh
                    </label>

                    <input
                        type="text"
                        name="filled_by"
                        class="form-control"
                        value="{{ $filledBy }}"
                        placeholder="Nama petugas"
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
                        href="{{ route('tv-vouchers.report') }}"
                        class="button-reset"
                    >
                        Reset
                    </a>

                </div>

            </form>


            <p class="period-info">

                Periode:

                <strong>
                    {{
                        $startDate
                            ? \Carbon\Carbon::parse($startDate)->format('d-m-Y')
                            : 'Semua tanggal'
                    }}
                </strong>

                sampai

                <strong>
                    {{
                        $endDate
                            ? \Carbon\Carbon::parse($endDate)->format('d-m-Y')
                            : 'Semua tanggal'
                    }}
                </strong>

            </p>

        </section>


        {{-- =====================================================
             SUMMARY UTAMA
        ====================================================== --}}

        <section class="summary-grid">

            <article class="summary-card summary-blue">
                <h3>Jumlah Transaksi</h3>
                <strong>{{ $totalTransactions }}</strong>
            </article>

            <article class="summary-card summary-purple">
                <h3>Total Voucher</h3>
                <strong>{{ $totalQuantity }}</strong>
            </article>

            <article class="summary-card summary-orange">
                <h3>Total Nilai Transaksi</h3>
                <strong>${{ number_format($totalAmount, 2) }}</strong>
            </article>

            <article class="summary-card summary-green">
                <h3>Customer Sudah Bayar</h3>
                <strong>${{ number_format($totalCustomerPaid, 2) }}</strong>
            </article>

            <article class="summary-card summary-red">
                <h3>Piutang Customer</h3>
                <strong>${{ number_format($totalCustomerBalance, 2) }}</strong>
            </article>

            <article class="summary-card summary-cash">
                <h3>💵 Total CASH</h3>
                <strong>${{ number_format($totalCash ?? 0, 2) }}</strong>
            </article>

            <article class="summary-card summary-bank">
                <h3>🏦 Total BANK</h3>
                <strong>${{ number_format($totalBank ?? 0, 2) }}</strong>
            </article>

            <article class="summary-card summary-cyan">
                <h3>Cash Diterima Petugas</h3>
                <strong>${{ number_format($totalStaffReceived, 2) }}</strong>
            </article>

            <article class="summary-card summary-green">
                <h3>Cash Sudah Disetor</h3>
                <strong>${{ number_format($totalDeposited, 2) }}</strong>
            </article>

            <article class="summary-card summary-red">
                <h3>Cash Belum Disetor</h3>
                <strong>${{ number_format($totalNotDeposited, 2) }}</strong>
            </article>

        </section>


        {{-- =====================================================
             STATUS SUMMARY
        ====================================================== --}}

        <section class="status-summary-grid">

            <article class="status-summary">

                <h3>Pembayaran Customer</h3>

                <div class="status-line">
                    <span>Lunas</span>
                    <strong>{{ $totalCustomerPaidTransactions }}</strong>
                </div>

                <div class="status-line">
                    <span>Bayar Sebagian</span>
                    <strong>{{ $totalCustomerPartialTransactions }}</strong>
                </div>

                <div class="status-line">
                    <span>Belum Bayar</span>
                    <strong>{{ $totalCustomerUnpaidTransactions }}</strong>
                </div>

            </article>


            <article class="status-summary">

                <h3>Setoran CASH Petugas</h3>

                <div class="status-line">
                    <span>Sudah Setor</span>
                    <strong>{{ $totalDepositPaidTransactions }}</strong>
                </div>

                <div class="status-line">
                    <span>Menunggu Setoran</span>
                    <strong>{{ $totalDepositPendingTransactions }}</strong>
                </div>

            </article>


            <article class="status-summary">

                <h3>Status Isi Ulang</h3>

                <div class="status-line">
                    <span>Berhasil</span>
                    <strong>{{ $totalRechargeSuccess }}</strong>
                </div>

                <div class="status-line">
                    <span>Menunggu</span>
                    <strong>{{ $totalRechargePending }}</strong>
                </div>

                <div class="status-line">
                    <span>Gagal</span>
                    <strong>{{ $totalRechargeFailed }}</strong>
                </div>

            </article>

        </section>


        {{-- =====================================================
             REKAP PETUGAS
        ====================================================== --}}

        <section class="report-card">

            <h3>
                Rekap per Petugas
            </h3>


            <div class="table-wrapper">

                <table class="filler-table">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Nama Petugas</th>
                            <th>Transaksi</th>
                            <th>Voucher</th>
                            <th>Total</th>
                            <th>Customer Bayar</th>
                            <th>Piutang</th>
                            <th>CASH</th>
                            <th>BANK</th>
                            <th>Cash Petugas</th>
                            <th>Sudah Setor</th>
                            <th>Belum Setor</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($summaryByFiller as $summary)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td class="filler-name">
                                    {{ data_get($summary, 'name', '-') }}
                                </td>

                                <td>
                                    {{ data_get($summary, 'transactions', 0) }}
                                </td>

                                <td>
                                    {{ data_get($summary, 'quantity', 0) }}
                                </td>

                                <td class="amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'total_amount', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td class="paid-amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'customer_paid', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td class="balance-amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'customer_balance', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td class="cash-amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'cash', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td class="bank-amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'bank', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td>
                                    ${{
                                        number_format(
                                            data_get($summary, 'staff_received', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td class="deposited-amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'deposited', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td class="not-deposited-amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'not_deposited', 0),
                                            2
                                        )
                                    }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="12"
                                    class="empty-data"
                                >
                                    Belum ada data petugas.
                                </td>

                            </tr>

                        @endforelse


                        @if ($summaryByFiller->isNotEmpty())

                            <tr class="total-row">

                                <td colspan="2">
                                    TOTAL
                                </td>

                                <td>{{ $totalTransactions }}</td>

                                <td>{{ $totalQuantity }}</td>

                                <td class="amount">
                                    ${{ number_format($totalAmount, 2) }}
                                </td>

                                <td class="paid-amount">
                                    ${{ number_format($totalCustomerPaid, 2) }}
                                </td>

                                <td class="balance-amount">
                                    ${{ number_format($totalCustomerBalance, 2) }}
                                </td>

                                <td class="cash-amount">
                                    ${{ number_format($totalCash ?? 0, 2) }}
                                </td>

                                <td class="bank-amount">
                                    ${{ number_format($totalBank ?? 0, 2) }}
                                </td>

                                <td>
                                    ${{ number_format($totalStaffReceived, 2) }}
                                </td>

                                <td class="deposited-amount">
                                    ${{ number_format($totalDeposited, 2) }}
                                </td>

                                <td class="not-deposited-amount">
                                    ${{ number_format($totalNotDeposited, 2) }}
                                </td>

                            </tr>

                        @endif

                    </tbody>

                </table>

            </div>

        </section>


        {{-- =====================================================
             REKAP PROVIDER
        ====================================================== --}}

        <section class="report-card">

            <h3>
                Rekap per Provider
            </h3>


            <div class="table-wrapper">

                <table class="provider-table">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Provider</th>
                            <th>Transaksi</th>
                            <th>Voucher</th>
                            <th>Total</th>
                            <th>CASH</th>
                            <th>BANK</th>
                            <th>Sudah Setor</th>
                            <th>Belum Setor</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($summaryByProvider as $summary)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td class="filler-name">
                                    {{ data_get($summary, 'name', '-') }}
                                </td>

                                <td>
                                    {{ data_get($summary, 'transactions', 0) }}
                                </td>

                                <td>
                                    {{ data_get($summary, 'quantity', 0) }}
                                </td>

                                <td class="amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'total_amount', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td class="cash-amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'cash', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td class="bank-amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'bank', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td class="deposited-amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'deposited', 0),
                                            2
                                        )
                                    }}
                                </td>

                                <td class="not-deposited-amount">
                                    ${{
                                        number_format(
                                            data_get($summary, 'not_deposited', 0),
                                            2
                                        )
                                    }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="empty-data"
                                >
                                    Belum ada data provider.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        {{-- =====================================================
             DETAIL TRANSAKSI
        ====================================================== --}}

        <section class="report-card">

            <h3>
                Detail Transaksi TV Voucher
            </h3>


            <div class="table-wrapper">

                <table class="transaction-table">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>No HP</th>
                            <th>Tempat Tinggal</th>
                            <th>Diisi Oleh</th>
                            <th>Provider</th>
                            <th>Receiver</th>
                            <th>Paket</th>
                            <th>Masa Aktif</th>
                            <th>Jumlah</th>

                            <th>Total</th>

                            <th>Customer Bayar</th>
                            <th>Sisa Customer</th>
                            <th>Status Customer</th>

                            <th>Metode</th>
                            <th>Nama Bank</th>

                            <th>Cash Petugas</th>
                            <th>Cash Sudah Setor</th>
                            <th>Cash Belum Setor</th>

                            <th>Status Dana</th>

                            <th>Status Isi Ulang</th>
                            <th>Waktu Setor</th>

                            <th>Bukti Pembayaran</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($transactions as $transaction)

                            @php

                                $customerName =
                                    $transaction->customer_name
                                    ?: (
                                        $transaction->customer?->customer_name
                                        ?? '-'
                                    );

                                $customerPhone =
                                    $transaction->customer_phone
                                    ?: (
                                        $transaction->customer?->phone
                                        ?? '-'
                                    );

                                $customerAddress =
                                    $transaction->customer_address
                                    ?: (
                                        $transaction->customer?->address
                                        ?? '-'
                                    );

                                $customerPaid =
                                    (float) $transaction
                                        ->customer_paid_amount;

                                $staffReceived =
                                    (float) $transaction
                                        ->staff_received_amount;

                                $staffDeposited =
                                    (float) $transaction
                                        ->staff_deposited_amount;

                                $staffBalance =
                                    (float) $transaction
                                        ->staff_balance;

                                $method =
                                    $transaction
                                        ->payment_method;

                                $isCash =
                                    $method === 'cash';

                                $isBank =
                                    $method === 'bank';

                            @endphp


                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>
                                    {{
                                        $transaction->transaction_date
                                            ? \Carbon\Carbon::parse(
                                                $transaction->transaction_date
                                            )->format('d-m-Y')
                                            : '-'
                                    }}
                                </td>


                                <td>
                                    {{ $customerName }}
                                </td>


                                <td>
                                    {{ $customerPhone }}
                                </td>


                                <td>
                                    {{ $customerAddress }}
                                </td>


                                <td class="filler-name">
                                    {{ $transaction->filled_by ?: '-' }}
                                </td>


                                <td>
                                    {{ $transaction->provider ?: '-' }}
                                </td>


                                <td>
                                    {{ $transaction->receiver_number ?: '-' }}
                                </td>


                                <td>
                                    {{ $transaction->package_name ?: '-' }}
                                </td>


                                <td>

                                    @if (
                                        (int) $transaction
                                            ->subscription_months === 12
                                    )

                                        1 Tahun

                                    @else

                                        {{
                                            (int) $transaction
                                                ->subscription_months
                                        }}
                                        Bulan

                                    @endif

                                </td>


                                <td>
                                    {{ $transaction->quantity }}
                                </td>


                                <td class="amount">
                                    ${{
                                        number_format(
                                            $transaction->total_amount,
                                            2
                                        )
                                    }}
                                </td>


                                <td class="paid-amount">
                                    ${{
                                        number_format(
                                            $customerPaid,
                                            2
                                        )
                                    }}
                                </td>


                                <td class="balance-amount">
                                    ${{
                                        number_format(
                                            $transaction->customer_balance,
                                            2
                                        )
                                    }}
                                </td>


                                <td>

                                    @if (
                                        $transaction
                                            ->customer_payment_status
                                        === 'paid'
                                    )

                                        <span class="status status-paid">
                                            Lunas
                                        </span>

                                    @elseif (
                                        $transaction
                                            ->customer_payment_status
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


                                {{-- METODE --}}
                                <td>

                                    @if ($isCash)

                                        <span
                                            class="payment-method payment-cash"
                                        >
                                            💵 CASH
                                        </span>

                                    @elseif ($isBank)

                                        <span
                                            class="payment-method payment-bank"
                                        >
                                            🏦 BANK
                                        </span>

                                    @elseif ($customerPaid > 0)

                                        <span
                                            class="payment-method payment-none"
                                        >
                                            Belum Diatur
                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- BANK --}}
                                <td>

                                    @if ($isBank)

                                        <span class="bank-name">
                                            {{
                                                $transaction->bank_name
                                                ?: '-'
                                            }}
                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- CASH PETUGAS --}}
                                <td>

                                    @if ($isBank)

                                        $0.00

                                    @else

                                        ${{
                                            number_format(
                                                $staffReceived,
                                                2
                                            )
                                        }}

                                    @endif

                                </td>


                                {{-- CASH SETOR --}}
                                <td class="deposited-amount">

                                    @if ($isBank)

                                        $0.00

                                    @else

                                        ${{
                                            number_format(
                                                $staffDeposited,
                                                2
                                            )
                                        }}

                                    @endif

                                </td>


                                {{-- CASH BELUM SETOR --}}
                                <td class="not-deposited-amount">

                                    @if ($isBank)

                                        $0.00

                                    @else

                                        ${{
                                            number_format(
                                                $staffBalance,
                                                2
                                            )
                                        }}

                                    @endif

                                </td>


                                {{-- STATUS DANA --}}
                                <td>

                                    @if ($isBank && $customerPaid > 0)

                                        <span class="status status-bank">
                                            🏦 Masuk Bank
                                        </span>

                                    @elseif ($isCash)

                                        @if ($staffReceived <= 0)

                                            <span class="status status-no-money">
                                                Belum Ada Cash
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


                                {{-- STATUS RECHARGE --}}
                                <td>

                                    <span
                                        class="status status-{{ $transaction->recharge_status }}"
                                    >
                                        {{
                                            $transaction
                                                ->recharge_status_label
                                        }}
                                    </span>

                                </td>


                                {{-- WAKTU SETOR --}}
                                <td>

                                    @if (
                                        $isCash
                                        &&
                                        $transaction->staff_deposited_at
                                    )

                                        {{
                                            \Carbon\Carbon::parse(
                                                $transaction
                                                    ->staff_deposited_at
                                            )->format(
                                                'd-m-Y H:i'
                                            )
                                        }}

                                    @elseif ($isBank)

                                        -

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- BUKTI --}}
                                <td>

                                    @if ($transaction->payment_proof)

                                        <a
                                            href="{{ Storage::url(
                                                $transaction->payment_proof
                                            ) }}"
                                            class="proof-link"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            📎 Lihat Bukti
                                        </a>

                                    @else

                                        <span style="color:#9ca3af;">
                                            Belum Ada
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="24"
                                    class="empty-data"
                                >
                                    Tidak ada transaksi pada
                                    filter/periode yang dipilih.
                                </td>

                            </tr>

                        @endforelse


                        @if ($transactions->isNotEmpty())

                            <tr class="total-row">

                                <td colspan="11">
                                    TOTAL KESELURUHAN
                                </td>

                                <td class="amount">
                                    ${{ number_format($totalAmount, 2) }}
                                </td>

                                <td class="paid-amount">
                                    ${{ number_format($totalCustomerPaid, 2) }}
                                </td>

                                <td class="balance-amount">
                                    ${{ number_format($totalCustomerBalance, 2) }}
                                </td>

                                <td></td>

                                <td class="cash-amount">
                                    CASH:
                                    ${{ number_format($totalCash ?? 0, 2) }}
                                </td>

                                <td class="bank-amount">
                                    BANK:
                                    ${{ number_format($totalBank ?? 0, 2) }}
                                </td>

                                <td>
                                    ${{ number_format($totalStaffReceived, 2) }}
                                </td>

                                <td class="deposited-amount">
                                    ${{ number_format($totalDeposited, 2) }}
                                </td>

                                <td class="not-deposited-amount">
                                    ${{ number_format($totalNotDeposited, 2) }}
                                </td>

                                <td colspan="4"></td>

                            </tr>

                        @endif

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>


{{-- IDLE LOGOUT --}}
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
        .forEach(function (link) {

            link.addEventListener(
                'click',
                closeSidebar
            );

        });


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