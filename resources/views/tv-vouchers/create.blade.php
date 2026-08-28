<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah TV Voucher - Dulmar Satellite Store</title>

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
            inset: 0 auto 0 0;
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

        .main-content {
            width: calc(100% - 245px);
            min-width: 0;
            margin-left: 245px;
            padding: 45px 32px;
        }

        .page-header {
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

        .form-card {
            width: 100%;
            max-width: 950px;
            padding: 32px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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

        .information {
            margin-bottom: 25px;
            padding: 15px 18px;
            border: 1px solid #c4b5fd;
            border-radius: 7px;
            background: #f5f3ff;
            color: #5b21b6;
            line-height: 1.5;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0 18px;
        }

        .form-group {
            margin-bottom: 23px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            font-size: 16px;
            font-weight: bold;
        }

        .required {
            color: #dc2626;
        }

        .form-control {
            width: 100%;
            min-height: 46px;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #7c3aed;
            outline: none;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
        }

        .form-control[readonly] {
            background: #f3f4f6;
            color: #4b5563;
        }

        textarea.form-control {
            min-height: 110px;
            resize: vertical;
        }

        .help-text {
            display: block;
            margin-top: 7px;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.4;
        }

        .section-card {
            grid-column: 1 / -1;
            margin-bottom: 25px;
            padding: 23px;
            border-radius: 9px;
        }

        .calculation-card {
            border: 1px solid #c4b5fd;
            background: #f5f3ff;
        }

        .customer-payment-card {
            border: 1px solid #fbbf24;
            background: #fffbeb;
        }

        .staff-deposit-card {
            border: 1px solid #93c5fd;
            background: #eff6ff;
        }

        .section-card h3 {
            margin: 0 0 8px;
        }

        .calculation-card h3 {
            color: #5b21b6;
        }

        .customer-payment-card h3 {
            color: #92400e;
        }

        .staff-deposit-card h3 {
            color: #1d4ed8;
        }

        .section-card > p {
            margin: 0 0 20px;
            line-height: 1.5;
        }

        .customer-payment-card > p {
            color: #78350f;
        }

        .staff-deposit-card > p {
            color: #1e40af;
        }

        .calculation-grid,
        .summary-grid {
            display: grid;
            gap: 14px;
        }

        .calculation-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .summary-grid {
            grid-template-columns: repeat(3, 1fr);
            margin-top: 8px;
        }

        .summary-item,
        .calculation-item {
            padding: 17px;
            border-radius: 7px;
            background: white;
        }

        .summary-item span,
        .calculation-item span {
            display: block;
            margin-bottom: 7px;
            color: #6b7280;
            font-size: 14px;
        }

        .summary-item strong,
        .calculation-item strong {
            font-size: 22px;
        }

        .subtotal-item {
            border-left: 5px solid #2563eb;
        }

        .subtotal-item strong {
            color: #2563eb;
        }

        .total-item {
            border-left: 5px solid #7c3aed;
        }

        .total-item strong {
            color: #7c3aed;
        }

        .summary-total {
            border-left: 5px solid #2563eb;
        }

        .summary-paid {
            border-left: 5px solid #16a34a;
        }

        .summary-balance {
            border-left: 5px solid #dc2626;
        }

        .summary-received {
            border-left: 5px solid #2563eb;
        }

        .summary-deposited {
            border-left: 5px solid #16a34a;
        }

        .summary-status {
            border-left: 5px solid #f59e0b;
        }

        .summary-total strong,
        .summary-received strong {
            color: #2563eb;
        }

        .summary-paid strong,
        .summary-deposited strong {
            color: #16a34a;
        }

        .summary-balance strong {
            color: #dc2626;
        }

        .summary-status strong {
            color: #b45309;
            font-size: 18px;
        }

        .section-fields {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0 18px;
        }

        .proof-preview {
            display: none;
            width: auto;
            max-width: 320px;
            max-height: 260px;
            margin-top: 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
        }

        .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .button {
            display: inline-block;
            padding: 13px 22px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .button-save {
            background: #7c3aed;
        }

        .button-save:hover {
            background: #6d28d9;
        }

        .button-cancel {
            background: #6b7280;
        }

        .button-cancel:hover {
            background: #4b5563;
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
                transition: transform 0.25s;
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .main-content {
                width: 100%;
                margin-left: 0;
                padding: 85px 14px 30px;
            }

            .page-header h2 {
                font-size: 30px;
            }

            .form-card {
                padding: 22px 16px;
            }

            .form-grid,
            .calculation-grid,
            .summary-grid,
            .section-fields {
                grid-template-columns: 1fr;
            }

            .form-group.full-width,
            .section-card {
                grid-column: auto;
            }

            .button {
                width: 100%;
                text-align: center;
            }

            .proof-preview {
                max-width: 100%;
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
                <h2>Tambah TV Voucher</h2>

                <p>
                    Catat transaksi isi ulang receiver pelanggan.
                </p>
            </div>

            <div class="form-card">
                @if ($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="information">
                    Nama pelanggan, nomor HP, dan tempat tinggal dapat diisi manual.
                    Pembayaran customer dan setoran petugas tetap dicatat terpisah.
                </div>

                <form
                    action="{{ route('tv-vouchers.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="transaction_date">
                                Tanggal Transaksi
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                id="transaction_date"
                                name="transaction_date"
                                class="form-control"
                                value="{{ old('transaction_date', date('Y-m-d')) }}"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="customer_name">
                                Nama Pelanggan
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="customer_name"
                                name="customer_name"
                                class="form-control"
                                value="{{ old('customer_name') }}"
                                placeholder="Masukkan nama pelanggan"
                                maxlength="255"
                                required
                            >

                            <span class="help-text">
                                Nama pelanggan diisi manual.
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="filled_by">
                                Diisi Oleh
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="filled_by"
                                name="filled_by"
                                class="form-control"
                                value="{{ old('filled_by') }}"
                                placeholder="Nama petugas/orang yang mengisi saldo"
                                maxlength="255"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="provider">
                                Provider
                                <span class="required">*</span>
                            </label>

                            <select
                                id="provider"
                                name="provider"
                                class="form-control"
                                required
                            >
                                <option value="">
                                    -- Pilih Provider --
                                </option>

                                <option
                                    value="K-Vision"
                                    {{ old('provider') === 'K-Vision' ? 'selected' : '' }}
                                >
                                    K-Vision
                                </option>

                                <option
                                    value="Nex Parabola"
                                    {{ old('provider') === 'Nex Parabola' ? 'selected' : '' }}
                                >
                                    Nex Parabola
                                </option>

                                <option
                                    value="Nusantara HD"
                                    {{ old('provider') === 'Nusantara HD' ? 'selected' : '' }}
                                >
                                    Nusantara HD
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="receiver_number">
                                Nomor Receiver
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="receiver_number"
                                name="receiver_number"
                                class="form-control"
                                value="{{ old('receiver_number') }}"
                                maxlength="100"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="package_name">
                                Nama Paket
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="package_name"
                                name="package_name"
                                class="form-control"
                                value="{{ old('package_name') }}"
                                maxlength="255"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="subscription_months">
                                Masa Aktif Paket
                                <span class="required">*</span>
                            </label>

                            <select
                                id="subscription_months"
                                name="subscription_months"
                                class="form-control"
                                required
                            >
                                <option
                                    value="1"
                                    {{ old('subscription_months', 1) == 1 ? 'selected' : '' }}
                                >
                                    1 Bulan
                                </option>

                                <option
                                    value="3"
                                    {{ old('subscription_months') == 3 ? 'selected' : '' }}
                                >
                                    3 Bulan
                                </option>

                                <option
                                    value="6"
                                    {{ old('subscription_months') == 6 ? 'selected' : '' }}
                                >
                                    6 Bulan
                                </option>

                                <option
                                    value="12"
                                    {{ old('subscription_months') == 12 ? 'selected' : '' }}
                                >
                                    1 Tahun
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="unit_amount">
                                Nominal per Voucher
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                id="unit_amount"
                                name="unit_amount"
                                class="form-control"
                                value="{{ old('unit_amount', 0) }}"
                                min="0"
                                step="0.01"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="quantity">
                                Jumlah
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                class="form-control"
                                value="{{ old('quantity', 1) }}"
                                min="1"
                                step="1"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="additional_fee">
                                Biaya Tambahan
                            </label>

                            <input
                                type="number"
                                id="additional_fee"
                                name="additional_fee"
                                class="form-control"
                                value="{{ old('additional_fee', 0) }}"
                                min="0"
                                step="0.01"
                            >
                        </div>

                        <div class="form-group">
                            <label for="discount">
                                Diskon
                            </label>

                            <input
                                type="number"
                                id="discount"
                                name="discount"
                                class="form-control"
                                value="{{ old('discount', 0) }}"
                                min="0"
                                step="0.01"
                            >
                        </div>

                        <div class="section-card calculation-card">
                            <h3>Perhitungan Transaksi</h3>

                            <div class="calculation-grid">
                                <div class="calculation-item subtotal-item">
                                    <span>Subtotal</span>
                                    <strong id="subtotalDisplay">$0.00</strong>
                                </div>

                                <div class="calculation-item total-item">
                                    <span>Total Isi Saldo</span>
                                    <strong id="totalDisplay">$0.00</strong>
                                </div>
                            </div>
                        </div>

                        <div class="section-card customer-payment-card">
                            <h3>Pembayaran Customer</h3>

                            <p>
                                Catat status pembayaran customer dan informasi
                                yang dibutuhkan untuk penagihan.
                            </p>

                            <div class="section-fields">
                                <div class="form-group">
                                    <label for="customer_payment_status">
                                        Status Pembayaran
                                        <span class="required">*</span>
                                    </label>

                                    <select
                                        id="customer_payment_status"
                                        name="customer_payment_status"
                                        class="form-control"
                                        required
                                    >
                                        <option
                                            value="paid"
                                            {{ old('customer_payment_status', 'paid') === 'paid' ? 'selected' : '' }}
                                        >
                                            Lunas
                                        </option>

                                        <option
                                            value="partial"
                                            {{ old('customer_payment_status') === 'partial' ? 'selected' : '' }}
                                        >
                                            Bayar Sebagian
                                        </option>

                                        <option
                                            value="unpaid"
                                            {{ old('customer_payment_status') === 'unpaid' ? 'selected' : '' }}
                                        >
                                            Belum Bayar
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="customer_paid_amount">
                                        Sudah Bayar
                                    </label>

                                    <input
                                        type="number"
                                        id="customer_paid_amount"
                                        name="customer_paid_amount"
                                        class="form-control"
                                        value="{{ old('customer_paid_amount', 0) }}"
                                        min="0"
                                        step="0.01"
                                    >

                                    <span class="help-text">
                                        Isi nominal jika status Bayar Sebagian.
                                    </span>
                                </div>

                                <div class="form-group">
                                    <label for="customer_phone">
                                        No. HP Customer
                                    </label>

                                    <input
                                        type="text"
                                        id="customer_phone"
                                        name="customer_phone"
                                        class="form-control"
                                        value="{{ old('customer_phone') }}"
                                        placeholder="Contoh: 77234567"
                                        maxlength="50"
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="customer_address">
                                        Tempat Tinggal
                                    </label>

                                    <input
                                        type="text"
                                        id="customer_address"
                                        name="customer_address"
                                        class="form-control"
                                        value="{{ old('customer_address') }}"
                                        placeholder="Contoh: Comoro"
                                        maxlength="255"
                                    >
                                </div>
                            </div>

                            <div class="summary-grid">
                                <div class="summary-item summary-total">
                                    <span>Total Isi Saldo</span>
                                    <strong id="customerTotalDisplay">
                                        $0.00
                                    </strong>
                                </div>

                                <div class="summary-item summary-paid">
                                    <span>Sudah Bayar</span>
                                    <strong id="customerPaidDisplay">
                                        $0.00
                                    </strong>
                                </div>

                                <div class="summary-item summary-balance">
                                    <span>Sisa Customer</span>
                                    <strong id="customerBalanceDisplay">
                                        $0.00
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <div class="section-card staff-deposit-card">
                            <h3>Setoran Petugas</h3>

                            <p>
                                Petugas hanya wajib menyetor uang yang sudah
                                diterima dari customer.
                            </p>

                            <div class="section-fields">
                                <div class="form-group">
                                    <label for="staff_received_amount_display">
                                        Uang Diterima Petugas
                                    </label>

                                    <input
                                        type="text"
                                        id="staff_received_amount_display"
                                        class="form-control"
                                        value="$0.00"
                                        readonly
                                    >

                                    <span class="help-text">
                                        Otomatis mengikuti jumlah yang sudah dibayar customer.
                                    </span>
                                </div>

                                <div class="form-group">
                                    <label for="staff_deposited_amount">
                                        Sudah Disetor Petugas
                                    </label>

                                    <input
                                        type="number"
                                        id="staff_deposited_amount"
                                        name="staff_deposited_amount"
                                        class="form-control"
                                        value="{{ old('staff_deposited_amount', 0) }}"
                                        min="0"
                                        step="0.01"
                                    >

                                    <span class="help-text">
                                        Masukkan jumlah uang yang sudah diserahkan ke toko.
                                    </span>
                                </div>
                            </div>

                            <div class="summary-grid">
                                <div class="summary-item summary-received">
                                    <span>Uang Diterima</span>
                                    <strong id="staffReceivedDisplay">
                                        $0.00
                                    </strong>
                                </div>

                                <div class="summary-item summary-deposited">
                                    <span>Sudah Disetor</span>
                                    <strong id="staffDepositedDisplay">
                                        $0.00
                                    </strong>
                                </div>

                                <div class="summary-item summary-balance">
                                    <span>Belum Disetor</span>
                                    <strong id="staffBalanceDisplay">
                                        $0.00
                                    </strong>
                                </div>

                                <div class="summary-item summary-status">
                                    <span>Status Setoran</span>
                                    <strong id="staffDepositStatusDisplay">
                                        Belum Setor
                                    </strong>
                                </div>
                            </div>

                            <input
                                type="hidden"
                                id="payment_status"
                                name="payment_status"
                                value="{{ old('payment_status', 'unpaid') }}"
                            >
                        </div>

                        <div class="form-group">
                            <label for="recharge_status">
                                Status Isi Ulang
                                <span class="required">*</span>
                            </label>

                            <select
                                id="recharge_status"
                                name="recharge_status"
                                class="form-control"
                                required
                            >
                                <option
                                    value="pending"
                                    {{ old('recharge_status', 'pending') === 'pending' ? 'selected' : '' }}
                                >
                                    Menunggu
                                </option>

                                <option
                                    value="success"
                                    {{ old('recharge_status') === 'success' ? 'selected' : '' }}
                                >
                                    Berhasil
                                </option>

                                <option
                                    value="failed"
                                    {{ old('recharge_status') === 'failed' ? 'selected' : '' }}
                                >
                                    Gagal
                                </option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="payment_proof">
                                Bukti Transaksi

                                <span
                                    id="proofRequired"
                                    class="required"
                                    style="display: none;"
                                >
                                    *
                                </span>
                            </label>

                            <input
                                type="file"
                                id="payment_proof"
                                name="payment_proof"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >

                            <span class="help-text">
                                Bukti wajib diunggah jika status isi ulang Berhasil.
                            </span>

                            <img
                                id="proofPreview"
                                class="proof-preview"
                                alt="Pratinjau bukti transaksi"
                            >
                        </div>

                        <div class="form-group full-width">
                            <label for="notes">
                                Catatan
                            </label>

                            <textarea
                                id="notes"
                                name="notes"
                                class="form-control"
                                maxlength="1000"
                            >{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button
                            type="submit"
                            class="button button-save"
                        >
                            Simpan TV Voucher
                        </button>

                        <a
                            href="{{ route('tv-vouchers.index') }}"
                            class="button button-cancel"
                        >
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const unitAmountInput =
            document.getElementById('unit_amount');

        const quantityInput =
            document.getElementById('quantity');

        const additionalFeeInput =
            document.getElementById('additional_fee');

        const discountInput =
            document.getElementById('discount');

        const subtotalDisplay =
            document.getElementById('subtotalDisplay');

        const totalDisplay =
            document.getElementById('totalDisplay');

        const customerPaymentStatusInput =
            document.getElementById('customer_payment_status');

        const customerPaidAmountInput =
            document.getElementById('customer_paid_amount');

        const customerTotalDisplay =
            document.getElementById('customerTotalDisplay');

        const customerPaidDisplay =
            document.getElementById('customerPaidDisplay');

        const customerBalanceDisplay =
            document.getElementById('customerBalanceDisplay');

        const staffDepositedAmountInput =
            document.getElementById('staff_deposited_amount');

        const staffReceivedInput =
            document.getElementById('staff_received_amount_display');

        const staffReceivedDisplay =
            document.getElementById('staffReceivedDisplay');

        const staffDepositedDisplay =
            document.getElementById('staffDepositedDisplay');

        const staffBalanceDisplay =
            document.getElementById('staffBalanceDisplay');

        const staffDepositStatusDisplay =
            document.getElementById('staffDepositStatusDisplay');

        const paymentStatusInput =
            document.getElementById('payment_status');

        const rechargeStatusInput =
            document.getElementById('recharge_status');

        const paymentProofInput =
            document.getElementById('payment_proof');

        const proofRequired =
            document.getElementById('proofRequired');

        const proofPreview =
            document.getElementById('proofPreview');

        function formatMoney(value) {
            return '$' + Number(value || 0).toFixed(2);
        }

        function getTransactionTotal() {
            const unitAmount =
                Number(unitAmountInput.value || 0);

            const quantity =
                Number(quantityInput.value || 0);

            const additionalFee =
                Number(additionalFeeInput.value || 0);

            const discount =
                Number(discountInput.value || 0);

            return Math.max(
                (unitAmount * quantity)
                + additionalFee
                - discount,
                0
            );
        }

        function getCustomerPaidAmount() {
            const total =
                getTransactionTotal();

            const status =
                customerPaymentStatusInput.value;

            let paid =
                Number(
                    customerPaidAmountInput.value || 0
                );

            if (status === 'paid') {
                paid = total;
            }

            if (status === 'unpaid') {
                paid = 0;
            }

            if (paid < 0) {
                paid = 0;
            }

            if (paid > total) {
                paid = total;
            }

            return paid;
        }

        function calculateTransaction() {
            const unitAmount =
                Number(unitAmountInput.value || 0);

            const quantity =
                Number(quantityInput.value || 0);

            const subtotal =
                unitAmount * quantity;

            const total =
                getTransactionTotal();

            subtotalDisplay.textContent =
                formatMoney(subtotal);

            totalDisplay.textContent =
                formatMoney(total);

            calculateCustomerPayment();
        }

        function calculateCustomerPayment() {
            const total =
                getTransactionTotal();

            const status =
                customerPaymentStatusInput.value;

            const paid =
                getCustomerPaidAmount();

            if (status === 'paid') {
                customerPaidAmountInput.value =
                    total.toFixed(2);

                customerPaidAmountInput.readOnly =
                    true;
            } else if (status === 'unpaid') {
                customerPaidAmountInput.value =
                    '0.00';

                customerPaidAmountInput.readOnly =
                    true;
            } else {
                customerPaidAmountInput.readOnly =
                    false;
            }

            const balance =
                Math.max(
                    total - paid,
                    0
                );

            customerTotalDisplay.textContent =
                formatMoney(total);

            customerPaidDisplay.textContent =
                formatMoney(paid);

            customerBalanceDisplay.textContent =
                formatMoney(balance);

            calculateStaffDeposit();
        }

        function calculateStaffDeposit() {
            const received =
                getCustomerPaidAmount();

            let deposited =
                Number(
                    staffDepositedAmountInput.value || 0
                );

            if (deposited < 0) {
                deposited = 0;
            }

            if (deposited > received) {
                deposited = received;

                staffDepositedAmountInput.value =
                    received.toFixed(2);
            }

            const balance =
                Math.max(
                    received - deposited,
                    0
                );

            let statusText =
                'Belum Setor';

            let paymentStatus =
                'unpaid';

            if (received <= 0) {
                statusText =
                    'Belum Ada Uang untuk Disetor';

                deposited = 0;

                staffDepositedAmountInput.value =
                    '0.00';

                staffDepositedAmountInput.readOnly =
                    true;
            } else {
                staffDepositedAmountInput.readOnly =
                    false;

                if (deposited <= 0) {
                    statusText =
                        'Belum Setor';
                } else if (deposited < received) {
                    statusText =
                        'Setor Sebagian';
                } else {
                    statusText =
                        'Sudah Setor';

                    paymentStatus =
                        'paid';
                }
            }

            staffReceivedInput.value =
                formatMoney(received);

            staffReceivedDisplay.textContent =
                formatMoney(received);

            staffDepositedDisplay.textContent =
                formatMoney(deposited);

            staffBalanceDisplay.textContent =
                formatMoney(balance);

            staffDepositStatusDisplay.textContent =
                statusText;

            paymentStatusInput.value =
                paymentStatus;
        }

        function updateProofRequirement() {
            const isSuccess =
                rechargeStatusInput.value === 'success';

            paymentProofInput.required =
                isSuccess;

            proofRequired.style.display =
                isSuccess
                    ? 'inline'
                    : 'none';
        }

        function previewProof() {
            const file =
                paymentProofInput.files[0];

            if (!file) {
                proofPreview.removeAttribute('src');

                proofPreview.style.display =
                    'none';

                return;
            }

            const reader =
                new FileReader();

            reader.addEventListener(
                'load',
                function () {
                    proofPreview.src =
                        reader.result;

                    proofPreview.style.display =
                        'block';
                }
            );

            reader.readAsDataURL(file);
        }

        [
            unitAmountInput,
            quantityInput,
            additionalFeeInput,
            discountInput
        ].forEach(function (input) {
            input.addEventListener(
                'input',
                calculateTransaction
            );
        });

        customerPaymentStatusInput.addEventListener(
            'change',
            calculateCustomerPayment
        );

        customerPaidAmountInput.addEventListener(
            'input',
            calculateCustomerPayment
        );

        staffDepositedAmountInput.addEventListener(
            'input',
            calculateStaffDeposit
        );

        rechargeStatusInput.addEventListener(
            'change',
            updateProofRequirement
        );

        paymentProofInput.addEventListener(
            'change',
            previewProof
        );

        calculateTransaction();
        calculateCustomerPayment();
        calculateStaffDeposit();
        updateProofRequirement();

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
    </script>
</body>
</html>