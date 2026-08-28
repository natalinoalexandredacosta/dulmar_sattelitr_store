<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>TV Voucher - Dulmar Satellite Store</title>
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

        .button-logout:hover {
            background: #b91c1c;
        }

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

        .button-add {
            display: inline-block;
            flex-shrink: 0;
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

        .filter-card {
            margin-bottom: 25px;
            padding: 22px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .filter-card h3 {
            margin: 0 0 18px;
            font-size: 20px;
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

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(170px, 1fr));
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

        .summary-paid {
            border-left: 5px solid #16a34a;
        }

        .summary-unpaid {
            border-left: 5px solid #dc2626;
        }

        .table-card {
            width: 100%;
            overflow-x: auto;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 2700px;
            border-collapse: collapse;
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
            white-space: nowrap;
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

        .proof-link {
            color: #2563eb;
            font-weight: bold;
            text-decoration: none;
        }

        .proof-link:hover {
            text-decoration: underline;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 7px;
            min-width: 300px;
        }

        .action-buttons form {
            margin: 0;
        }

        .button-payment {
            padding: 8px 13px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            font-size: 13px;
            white-space: nowrap;
            cursor: pointer;
        }

        .button-payment:hover {
            background: #1d4ed8;
        }

        .button-deposit {
            padding: 8px 13px;
            border: none;
            border-radius: 6px;
            background: #16a34a;
            color: white;
            font-size: 13px;
            white-space: nowrap;
            cursor: pointer;
        }

        .button-deposit:hover {
            background: #15803d;
        }

        .button-edit,
        .button-delete {
            padding: 8px 13px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 13px;
            white-space: nowrap;
            cursor: pointer;
        }

        .button-edit {
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
            line-height: 1.5;
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

            .filter-form,
            .filter-dates,
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                display: grid;
                grid-template-columns: 1fr;
            }

            .button-filter,
            .button-reset,
            .button-add {
                width: 100%;
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
            <div class="page-header">
                <div>
                    <h2>TV Voucher</h2>

                    <p>
                        Kelola pengisian paket receiver,
                        pembayaran customer dan setoran petugas.
                    </p>
                </div>

                <a
                    href="{{ route('tv-vouchers.create') }}"
                    class="button-add"
                >
                    + Tambah TV Voucher
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
                <h3>Pencarian dan Filter Transaksi</h3>

                <form
                    action="{{ route('tv-vouchers.index') }}"
                    method="GET"
                >
                    <div class="filter-form">
                        <div class="form-group">
                            <label for="search">
                                Cari pelanggan, receiver, paket, atau pengisi
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                class="form-control"
                                value="{{ $search }}"
                                placeholder="Masukkan kata pencarian"
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
                            <label for="recharge_status">
                                Status Isi Ulang
                            </label>

                            <select
                                id="recharge_status"
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
                                    Belum Lunas
                                </option>

                                <option
                                    value="paid"
                                    {{ $paymentStatus === 'paid' ? 'selected' : '' }}
                                >
                                    Sudah Setor
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-dates">
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
                    <h3>Total Uang</h3>
                    <strong>
                        ${{ number_format($totalAmount, 2) }}
                    </strong>
                </article>

                <article class="summary-card summary-paid">
                    <h3>Sudah Disetor</h3>
                    <strong>
                        ${{ number_format($totalPaid, 2) }}
                    </strong>
                </article>

                <article class="summary-card summary-unpaid">
                    <h3>Belum Disetor</h3>
                    <strong>
                        ${{ number_format($totalUnpaid, 2) }}
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
                            <th>No. HP</th>
                            <th>Tempat Tinggal</th>

                            <th>Diisi Oleh</th>
                            <th>Provider</th>
                            <th>Nomor Receiver</th>
                            <th>Paket</th>
                            <th>Total</th>

                            <th>Customer Bayar</th>
                            <th>Sisa Customer</th>
                            <th>Status Customer</th>

                            <th>Petugas Terima</th>
                            <th>Sudah Setor</th>
                            <th>Belum Setor</th>
                            <th>Status Setoran</th>

                            <th>Status Isi Ulang</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($tvVouchers as $tvVoucher)
                            @php
                                $staffReceived =
                                    (float) $tvVoucher->staff_received_amount;

                                $staffDeposited =
                                    (float) $tvVoucher->staff_deposited_amount;

                                $staffBalance =
                                    (float) $tvVoucher->staff_balance;

                                $customerPaid =
                                    (float) $tvVoucher->customer_paid_amount;

                                $customerBalance =
                                    (float) $tvVoucher->customer_balance;

                                $customerName =
                                    $tvVoucher->customer?->customer_name
                                    ?? '-';

                                $customerPhone =
                                    $tvVoucher->customer_phone
                                    ?: (
                                        $tvVoucher->customer?->phone
                                        ?? '-'
                                    );

                                $customerAddress =
                                    $tvVoucher->customer_address
                                    ?: (
                                        $tvVoucher->customer?->address
                                        ?? '-'
                                    );
                            @endphp

                            <tr>
                                <td>
                                    {{ $tvVouchers->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($tvVoucher->transaction_date)->format('d-m-Y') }}
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
                                    ${{ number_format($tvVoucher->total_amount, 2) }}
                                </td>

                                <td class="amount-green">
                                    ${{ number_format($customerPaid, 2) }}
                                </td>

                                <td class="amount-red">
                                    ${{ number_format($customerBalance, 2) }}
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
                                    ${{ number_format($staffReceived, 2) }}
                                </td>

                                <td class="amount-green">
                                    ${{ number_format($staffDeposited, 2) }}
                                </td>

                                <td class="amount-red">
                                    ${{ number_format($staffBalance, 2) }}
                                </td>

                                <td>
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
                                            href="{{ Storage::url($tvVoucher->payment_proof) }}"
                                            class="proof-link"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            Lihat Bukti
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        {{-- VERIFIKASI PEMBAYARAN CUSTOMER --}}
                                        @if ($customerBalance > 0)
                                            <form
                                                action="{{ route('tv-vouchers.verify-payment', $tvVoucher) }}"
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

                                        {{-- KONFIRMASI SETORAN PETUGAS --}}
                                        @if (
                                            $staffReceived > 0
                                            && $staffBalance > 0
                                        )
                                            <form
                                                action="{{ route('tv-vouchers.confirm-deposit', $tvVoucher) }}"
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

                                                @if ($tvVoucher->staff_deposited_at)
                                                    <br>

                                                    {{ \Carbon\Carbon::parse($tvVoucher->staff_deposited_at)->format('d-m-Y H:i') }}
                                                @endif
                                            </div>

                                        @else
                                            <div class="deposit-no-money">
                                                Belum Ada Uang
                                            </div>
                                        @endif

                                        <a
                                            href="{{ route('tv-vouchers.edit', $tvVoucher) }}"
                                            class="button-edit"
                                        >
                                            Edit
                                        </a>

                                        @if (
                                            $tvVoucher->recharge_status !== 'success'
                                            && $staffDeposited <= 0
                                        )
                                            <form
                                                action="{{ route('tv-vouchers.destroy', $tvVoucher) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')"
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
                        Halaman {{ $tvVouchers->currentPage() }}
                        dari {{ $tvVouchers->lastPage() }}
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

    <script>
        /*
         * Sidebar mobile.
         */
        const sidebar =
            document.getElementById('sidebar');

        const sidebarToggle =
            document.getElementById('sidebarToggle');

        const sidebarOverlay =
            document.getElementById('sidebarOverlay');

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

        /*
         * Verifikasi pembayaran customer.
         */
        document
            .querySelectorAll('.verify-payment-form')
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

                        const input =
                            form.querySelector(
                                'input[name="payment_amount"]'
                            );

                        const answer =
                            prompt(
                                'Customer: '
                                + customer
                                + '\n'
                                + 'Sisa tagihan: $'
                                + remaining.toFixed(2)
                                + '\n\n'
                                + 'Masukkan jumlah yang dibayar:'
                            );

                        if (answer === null) {
                            return;
                        }

                        const payment =
                            Number(answer);

                        if (
                            !Number.isFinite(payment)
                            || payment <= 0
                        ) {
                            alert(
                                'Jumlah pembayaran tidak valid.'
                            );

                            return;
                        }

                        if (payment > remaining) {
                            alert(
                                'Jumlah pembayaran melebihi sisa tagihan $'
                                + remaining.toFixed(2)
                                + '.'
                            );

                            return;
                        }

                        const confirmed =
                            confirm(
                                'Verifikasi pembayaran '
                                + customer
                                + ' sebesar $'
                                + payment.toFixed(2)
                                + '?'
                            );

                        if (!confirmed) {
                            return;
                        }

                        input.value =
                            payment.toFixed(2);

                        form.submit();
                    }
                );
            });

        /*
         * Konfirmasi setoran petugas.
         */
        document
            .querySelectorAll('.deposit-form')
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

                        const confirmed =
                            confirm(
                                'Konfirmasi bahwa '
                                + staff
                                + ' sudah menyetor seluruh sisa sebesar $'
                                + balance.toFixed(2)
                                + '?'
                            );

                        if (!confirmed) {
                            return;
                        }

                        form.submit();
                    }
                );
            });
    </script>
</body>
</html>