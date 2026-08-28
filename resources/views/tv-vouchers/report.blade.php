<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Laporan TV Voucher - Dulmar Satellite Store</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-dulmar.jpg') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
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
            background: #1f2b3a;
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
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar-menu a.active {
            padding-left: 14px;
            border-left: 4px solid #60a5fa;
            background: rgba(37, 99, 235, 0.3);
            color: #bfdbfe;
            font-weight: bold;
        }

        .logout-form {
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

        .button-logout:hover {
            background: #b91c1c;
        }

        /* Konten */
        .main-content {
            width: calc(100% - 245px);
            min-width: 0;
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

        .button-back:hover {
            background: #6d28d9;
        }

        .button-print {
            background: #374151;
        }

        .button-print:hover {
            background: #1f2937;
        }

        .alert-error {
            margin-bottom: 25px;
            padding: 15px 20px;
            border: 1px solid #fca5a5;
            border-radius: 7px;
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        /* Filter */
        .filter-card,
        .report-card {
            margin-bottom: 25px;
            padding: 23px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .filter-card h3,
        .report-card h3 {
            margin: 0 0 20px;
            font-size: 21px;
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(3, minmax(180px, 1fr));
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

        .form-control:focus {
            border-color: #7c3aed;
            outline: none;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
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

        .button-filter:hover {
            background: #1d4ed8;
        }

        .button-reset {
            background: #6b7280;
        }

        .button-reset:hover {
            background: #4b5563;
        }

        .period-info {
            margin: 18px 0 0;
            color: #4b5563;
            font-size: 14px;
        }

        /* Ringkasan */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(160px, 1fr));
            gap: 16px;
            margin-bottom: 25px;
        }

        .summary-card {
            padding: 20px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
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

        .summary-deposited {
            border-left: 5px solid #16a34a;
        }

        .summary-not-deposited {
            border-left: 5px solid #dc2626;
        }

        .summary-transactions strong {
            color: #2563eb;
        }

        .summary-quantity strong {
            color: #7c3aed;
        }

        .summary-total strong {
            color: #d97706;
        }

        .summary-deposited strong {
            color: #16a34a;
        }

        .summary-not-deposited strong {
            color: #dc2626;
        }

        /* Tabel */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .filler-table {
            min-width: 900px;
        }

        .transaction-table {
            min-width: 1650px;
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
            white-space: nowrap;
        }

        th {
            color: #1f2937;
            font-weight: bold;
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

        .deposited-amount {
            color: #16a34a;
            font-weight: bold;
        }

        .not-deposited-amount {
            color: #dc2626;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
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

        .proof-link {
            color: #2563eb;
            font-weight: bold;
            text-decoration: none;
        }

        .proof-link:hover {
            text-decoration: underline;
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

        .sidebar-toggle,
        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1250px) {
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-form {
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
                transition: 0.25s;
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

            .page-header h2 {
                font-size: 30px;
            }

            .page-actions,
            .button-back,
            .button-print {
                width: 100%;
            }

            .filter-form,
            .summary-grid {
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

            .page-header {
                margin-bottom: 15px;
            }

            .page-header h2 {
                font-size: 25px;
            }

            .summary-grid {
                grid-template-columns: repeat(5, 1fr);
                gap: 7px;
            }

            .summary-card,
            .filter-card,
            .report-card {
                padding: 10px;
                box-shadow: none;
                border: 1px solid #d1d5db;
            }

            .summary-card h3 {
                font-size: 10px;
            }

            .summary-card strong {
                font-size: 16px;
            }

            .table-wrapper {
                overflow: visible;
                border: none;
            }

            table,
            .filler-table,
            .transaction-table {
                width: 100%;
                min-width: 0;
            }

            th,
            td {
                padding: 4px;
                font-size: 8px;
                white-space: normal;
            }

            .status {
                padding: 3px 5px;
                font-size: 7px;
            }

            .report-card {
                break-inside: avoid;
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
                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>

                <a href="{{ route('products.index') }}">
                    Daftar Barang
                </a>

                <a href="{{ route('stock-ins.index') }}">
                    Stok Masuk
                </a>

                <a href="{{ route('stock-outs.index') }}">
                    Stok Keluar
                </a>

                <a
                    href="{{ route('tv-vouchers.index') }}"
                    class="active"
                >
                    TV Voucher
                </a>

                <a href="{{ route('suppliers.index') }}">
                    Supplier Barang
                </a>

                <a href="{{ route('customers.index') }}">
                    Pelanggan
                </a>

                <a href="{{ route('reports.index') }}">
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
                <div>
                    <h2>Laporan TV Voucher</h2>

                    <p>
                        Laporan transaksi isi ulang dan setoran setiap petugas.
                    </p>
                </div>

                <div class="page-actions">
                    <a
                        href="{{ route('tv-vouchers.index') }}"
                        class="button-back"
                    >
                        Kembali ke TV Voucher
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

            <section class="filter-card">
                <h3>Filter Laporan</h3>

                <form
                    action="{{ route('tv-vouchers.report') }}"
                    method="GET"
                    class="filter-form"
                >
                    <div class="form-group">
                        <label for="start_date">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="form-control"
                            value="{{ $startDate }}"
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
                            value="{{ $endDate }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="provider">
                            Provider
                        </label>

                        <select
                            id="provider"
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
                        <label for="payment_status">
                            Status Setoran
                        </label>

                        <select
                            id="payment_status"
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
                                Belum Setor
                            </option>

                            <option
                                value="paid"
                                {{ $paymentStatus === 'paid' ? 'selected' : '' }}
                            >
                                Sudah Setor
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="filled_by">
                            Diisi Oleh
                        </label>

                        <input
                            type="text"
                            id="filled_by"
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
                    Periode laporan:

                    <strong>
                        {{ $startDate
                            ? \Carbon\Carbon::parse($startDate)->format('d-m-Y')
                            : 'Semua tanggal'
                        }}
                    </strong>

                    sampai

                    <strong>
                        {{ $endDate
                            ? \Carbon\Carbon::parse($endDate)->format('d-m-Y')
                            : 'Hari ini'
                        }}
                    </strong>
                </p>
            </section>

            <section class="summary-grid">
                <article class="summary-card summary-transactions">
                    <h3>Jumlah Transaksi</h3>

                    <strong>
                        {{ $totalTransactions }}
                    </strong>
                </article>

                <article class="summary-card summary-quantity">
                    <h3>Total Voucher</h3>

                    <strong>
                        {{ $totalQuantity }}
                    </strong>
                </article>

                <article class="summary-card summary-total">
                    <h3>Total Uang</h3>

                    <strong>
                        ${{ number_format($totalAmount, 2) }}
                    </strong>
                </article>

                <article class="summary-card summary-deposited">
                    <h3>Sudah Disetor</h3>

                    <strong>
                        ${{ number_format($totalDeposited, 2) }}
                    </strong>
                </article>

                <article class="summary-card summary-not-deposited">
                    <h3>Belum Disetor</h3>

                    <strong>
                        ${{ number_format($totalNotDeposited, 2) }}
                    </strong>
                </article>
            </section>

            <section class="report-card">
                <h3>Rekap Setoran per Petugas</h3>

                <div class="table-wrapper">
                    <table class="filler-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Petugas</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total Voucher</th>
                                <th>Total Uang</th>
                                <th>Sudah Disetor</th>
                                <th>Belum Disetor</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($summaryByFiller as $summary)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="filler-name">
                                        {{ data_get($summary, 'filled_by', '-') }}
                                    </td>

                                    <td>
                                        {{ data_get($summary, 'transactions', 0) }}
                                    </td>

                                    <td>
                                        {{ data_get($summary, 'quantity', 0) }}
                                    </td>

                                    <td class="amount">
                                        ${{ number_format(
                                            data_get($summary, 'total_amount', 0),
                                            2
                                        ) }}
                                    </td>

                                    <td class="deposited-amount">
                                        ${{ number_format(
                                            data_get($summary, 'deposited', 0),
                                            2
                                        ) }}
                                    </td>

                                    <td class="not-deposited-amount">
                                        ${{ number_format(
                                            data_get($summary, 'not_deposited', 0),
                                            2
                                        ) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="7"
                                        class="empty-data"
                                    >
                                        Belum ada data setoran petugas.
                                    </td>
                                </tr>
                            @endforelse

                            @if ($summaryByFiller->isNotEmpty())
                                <tr class="total-row">
                                    <td colspan="2">
                                        TOTAL
                                    </td>

                                    <td>
                                        {{ $totalTransactions }}
                                    </td>

                                    <td>
                                        {{ $totalQuantity }}
                                    </td>

                                    <td class="amount">
                                        ${{ number_format($totalAmount, 2) }}
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

            <section class="report-card">
                <h3>Detail Transaksi TV Voucher</h3>

                <div class="table-wrapper">
                    <table class="transaction-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Diisi Oleh</th>
                                <th>Provider</th>
                                <th>Nomor Receiver</th>
                                <th>Paket</th>
                                <th>Masa Aktif</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Total</th>
                                <th>Status Isi Ulang</th>
                                <th>Status Setoran</th>
                                <th>Waktu Setoran</th>
                                <th>Bukti</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $transaction->transaction_date->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        {{ $transaction->customer?->customer_name ?? '-' }}
                                    </td>

                                    <td class="filler-name">
                                        {{ $transaction->filled_by ?: '-' }}
                                    </td>

                                    <td>
                                        {{ $transaction->provider }}
                                    </td>

                                    <td>
                                        {{ $transaction->receiver_number }}
                                    </td>

                                    <td>
                                        {{ $transaction->package_name }}
                                    </td>

                                    <td>
                                        {{ $transaction->subscription_months_label }}
                                    </td>

                                    <td>
                                        {{ $transaction->quantity }}
                                    </td>

                                    <td class="amount">
                                        ${{ number_format(
                                            $transaction->subtotal,
                                            2
                                        ) }}
                                    </td>

                                    <td class="amount">
                                        ${{ number_format(
                                            $transaction->total_amount,
                                            2
                                        ) }}
                                    </td>

                                    <td>
                                        <span
                                            class="status status-{{ $transaction->recharge_status }}"
                                        >
                                            {{ $transaction->recharge_status_label }}
                                        </span>
                                    </td>

                                    <td>
                                        <span
                                            class="status status-{{ $transaction->payment_status }}"
                                        >
                                            {{ $transaction->payment_status_label }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $transaction->paid_at
                                            ? $transaction->paid_at->format('d-m-Y H:i')
                                            : '-'
                                        }}
                                    </td>

                                    <td>
                                        @if ($transaction->payment_proof)
                                            <a
                                                href="{{ Storage::url($transaction->payment_proof) }}"
                                                class="proof-link"
                                                target="_blank"
                                            >
                                                Lihat Bukti
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="15"
                                        class="empty-data"
                                    >
                                        Tidak ada transaksi pada periode yang dipilih.
                                    </td>
                                </tr>
                            @endforelse

                            @if ($transactions->isNotEmpty())
                                <tr class="total-row">
                                    <td colspan="9">
                                        TOTAL KESELURUHAN
                                    </td>

                                    <td>-</td>

                                    <td class="amount">
                                        ${{ number_format($totalAmount, 2) }}
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

        sidebarToggle.addEventListener('click', function () {
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
        });

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
    </script>
</body>
</html>