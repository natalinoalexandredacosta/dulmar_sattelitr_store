<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Kas Admin - Dulmar Satellite Store</title>

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

        /*
        |--------------------------------------------------------------------------
        | MAIN
        |--------------------------------------------------------------------------
        */

        .main-content {
            width: calc(100% - 245px);
            min-height: 100vh;

            margin-left: 245px;
            padding: 45px 32px;
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

            color: #4b5563;

            font-size: 17px;
            line-height: 1.5;
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

            border-radius: 8px;
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

        /*
        |--------------------------------------------------------------------------
        | BALANCE CARDS
        |--------------------------------------------------------------------------
        */

        .balance-grid {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(220px, 1fr));

            gap: 20px;

            margin-bottom: 30px;
        }

        .balance-card {
            position: relative;

            padding: 24px;

            border-radius: 14px;

            background: white;

            box-shadow:
                0 3px 12px
                rgba(0, 0, 0, 0.08);

            overflow: hidden;
        }

        .balance-card::before {
            content: "";

            position: absolute;
            top: 0;
            left: 0;

            width: 6px;
            height: 100%;
        }

        .balance-admin::before {
            background: #0f766e;
        }

        .balance-bank::before {
            background: #0284c7;
        }

        .balance-total::before {
            background: #7c3aed;
        }

        .balance-icon {
            margin-bottom: 12px;

            font-size: 30px;
        }

        .balance-card h3 {
            margin: 0 0 10px;

            color: #6b7280;

            font-size: 15px;
        }

        .balance-value {
            display: block;

            margin-bottom: 18px;

            color: #111827;

            font-size: 34px;
            font-weight: bold;
        }

        .bank-info {
            margin: -7px 0 17px;

            color: #0369a1;

            font-size: 13px;
            font-weight: bold;
        }

        .card-actions {
            display: flex;
            flex-wrap: wrap;

            gap: 8px;
        }

        .action-button {
            padding: 9px 13px;

            border: none;
            border-radius: 7px;

            color: white;

            font-size: 13px;
            font-weight: bold;

            cursor: pointer;
        }

        .button-add {
            background: #2563eb;
        }

        .button-edit {
            background: #f59e0b;
        }

        .button-transfer {
            background: #16a34a;
        }

        /*
        |--------------------------------------------------------------------------
        | HISTORY
        |--------------------------------------------------------------------------
        */

        .history-card {
            padding: 22px;

            border-radius: 12px;

            background: white;

            box-shadow:
                0 3px 12px
                rgba(0, 0, 0, 0.07);
        }

        .history-card h3 {
            margin: 0 0 20px;

            font-size: 21px;
        }

        .table-wrapper {
            width: 100%;

            overflow-x: auto;

            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        table {
            width: 100%;
            min-width: 1250px;

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
            vertical-align: middle;

            white-space: nowrap;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .movement-type {
            font-weight: bold;
        }

        .amount {
            color: #7c3aed;
            font-weight: bold;
        }

        .account-admin {
            color: #0f766e;
            font-weight: bold;
        }

        .account-bank {
            color: #0284c7;
            font-weight: bold;
        }

        .proof-link {
            display: inline-block;

            padding: 7px 10px;

            border-radius: 6px;

            background: #eff6ff;
            color: #2563eb;

            font-weight: bold;
            text-decoration: none;
        }

        .empty-data {
            padding: 30px;

            color: #6b7280;

            text-align: center;
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
        }

        .pagination a,
        .pagination span {
            padding: 9px 14px;

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
        | MODAL
        |--------------------------------------------------------------------------
        */

        .modal {
            position: fixed;
            inset: 0;
            z-index: 5000;

            display: none;

            align-items: center;
            justify-content: center;

            padding: 20px;

            background: rgba(15, 23, 42, 0.65);
        }

        .modal.open {
            display: flex;
        }

        .modal-card {
            width: 100%;
            max-width: 520px;

            border-radius: 14px;

            background: white;

            overflow: hidden;

            box-shadow:
                0 20px 50px
                rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 15px;

            padding: 20px 22px;

            border-bottom: 1px solid #e5e7eb;
        }

        .modal-header h3 {
            margin: 0;

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

        .modal-body {
            padding: 22px;
        }

        .form-group {
            margin-bottom: 17px;
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

            padding: 11px 12px;

            border: 1px solid #d1d5db;
            border-radius: 7px;

            background: white;

            font-size: 15px;
        }

        textarea.form-control {
            min-height: 90px;

            resize: vertical;
        }

        .form-help {
            margin-top: 6px;

            color: #6b7280;

            font-size: 12px;
            line-height: 1.5;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;

            gap: 10px;

            padding: 17px 22px;

            border-top: 1px solid #e5e7eb;

            background: #f8fafc;
        }

        .button-cancel,
        .button-save {
            padding: 10px 18px;

            border: none;
            border-radius: 7px;

            color: white;

            font-size: 14px;
            font-weight: bold;

            cursor: pointer;
        }

        .button-cancel {
            background: #6b7280;
        }

        .button-save {
            background: #2563eb;
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

        @media (max-width: 1050px) {

            .balance-grid {
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

                padding:
                    82px
                    25px
                    30px;

                transform: translateX(-105%);

                transition: transform 0.25s ease;
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

            .page-header h2 {
                font-size: 30px;
            }

            .card-actions {
                display: grid;
                grid-template-columns: 1fr;
            }

            .action-button {
                width: 100%;
            }

            .modal {
                padding: 10px;
            }

            .modal-footer {
                display: grid;
                grid-template-columns: 1fr;
            }

            .button-cancel,
            .button-save {
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


            <a
                href="{{ route('cash-accounts.index') }}"
                class="active"
            >
                Kas Admin
            </a>


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

            <h2>
                Kas Admin
            </h2>

            <p>
                Pantau uang yang saat ini berada di Admin
                dan uang yang sudah berada di rekening Bank.
            </p>

        </header>


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


        {{-- =====================================================
             SALDO
        ====================================================== --}}

        <section class="balance-grid">

            <article class="balance-card balance-admin">

                <div class="balance-icon">
                    💵
                </div>

                <h3>
                    Uang di Admin
                </h3>

                <strong class="balance-value">
                    ${{ number_format(
                        (float) $adminAccount->balance,
                        2
                    ) }}
                </strong>


                <div class="card-actions">

                    <button
                        type="button"
                        class="action-button button-add"
                        data-open-modal="addAdminModal"
                    >
                        + Tambah
                    </button>

                    <button
                        type="button"
                        class="action-button button-edit"
                        data-open-modal="editAdminModal"
                    >
                        Edit
                    </button>

                    <button
                        type="button"
                        class="action-button button-transfer"
                        data-open-modal="transferBankModal"
                    >
                        Setor ke Bank
                    </button>

                </div>

            </article>


            <article class="balance-card balance-bank">

                <div class="balance-icon">
                    🏦
                </div>

                <h3>
                    Uang di Bank
                </h3>

                <strong class="balance-value">
                    ${{ number_format(
                        (float) $bankAccount->balance,
                        2
                    ) }}
                </strong>


                @if ($bankAccount->bank_name)

                    <div class="bank-info">
                        Bank terakhir:
                        {{ $bankAccount->bank_name }}
                    </div>

                @endif


                <div class="card-actions">

                    <button
                        type="button"
                        class="action-button button-add"
                        data-open-modal="addBankModal"
                    >
                        + Tambah
                    </button>

                    <button
                        type="button"
                        class="action-button button-edit"
                        data-open-modal="editBankModal"
                    >
                        Edit
                    </button>

                </div>

            </article>


            <article class="balance-card balance-total">

                <div class="balance-icon">
                    💰
                </div>

                <h3>
                    Total Uang
                </h3>

                <strong class="balance-value">
                    ${{ number_format(
                        (float) $totalMoney,
                        2
                    ) }}
                </strong>

                <p
                    style="
                        margin:0;
                        color:#6b7280;
                        line-height:1.6;
                    "
                >
                    Uang di Admin + Uang di Bank
                </p>

            </article>

        </section>


        {{-- =====================================================
             HISTORY
        ====================================================== --}}

        <section class="history-card">

            <h3>
                Histori Pergerakan Uang
            </h3>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Aktivitas</th>
                            <th>Jumlah</th>
                            <th>Dari</th>
                            <th>Ke</th>
                            <th>Bank</th>
                            <th>Bukti</th>
                            <th>Keterangan</th>
                            <th>Dibuat Oleh</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($movements as $movement)

                            <tr>

                                <td>
                                    {{
                                        $movements->firstItem()
                                        + $loop->index
                                    }}
                                </td>


                                <td>
                                    {{
                                        $movement->created_at
                                            ? $movement
                                                ->created_at
                                                ->format(
                                                    'd-m-Y H:i'
                                                )
                                            : '-'
                                    }}
                                </td>


                                <td class="movement-type">
                                    {{ $movement->movement_label }}
                                </td>


                                <td class="amount">
                                    ${{ number_format(
                                        (float) $movement->amount,
                                        2
                                    ) }}
                                </td>


                                <td>

                                    @if (
                                        $movement->from_account
                                        === 'admin'
                                    )

                                        <span class="account-admin">
                                            Admin
                                        </span>

                                    @elseif (
                                        $movement->from_account
                                        === 'bank'
                                    )

                                        <span class="account-bank">
                                            Bank
                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @if (
                                        $movement->to_account
                                        === 'admin'
                                    )

                                        <span class="account-admin">
                                            Admin
                                        </span>

                                    @elseif (
                                        $movement->to_account
                                        === 'bank'
                                    )

                                        <span class="account-bank">
                                            Bank
                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>
                                    {{ $movement->bank_name ?: '-' }}
                                </td>


                                <td>

                                    @if ($movement->proof)

                                        <a
                                            href="{{ Storage::url(
                                                $movement->proof
                                            ) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="proof-link"
                                        >
                                            Lihat Bukti
                                        </a>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>
                                    {{ $movement->notes ?: '-' }}
                                </td>


                                <td>
                                    {{
                                        $movement->creator?->name
                                        ?? '-'
                                    }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="empty-data"
                                >
                                    Belum ada histori pergerakan uang.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            @if ($movements->hasPages())

                <nav class="pagination">

                    @if ($movements->onFirstPage())

                        <span class="disabled">
                            Sebelumnya
                        </span>

                    @else

                        <a href="{{ $movements->previousPageUrl() }}">
                            Sebelumnya
                        </a>

                    @endif


                    <span>
                        Halaman
                        {{ $movements->currentPage() }}
                        dari
                        {{ $movements->lastPage() }}
                    </span>


                    @if ($movements->hasMorePages())

                        <a href="{{ $movements->nextPageUrl() }}">
                            Berikutnya
                        </a>

                    @else

                        <span class="disabled">
                            Berikutnya
                        </span>

                    @endif

                </nav>

            @endif

        </section>

    </main>

</div>


{{-- ============================================================
     TAMBAH UANG ADMIN
============================================================ --}}

<div
    id="addAdminModal"
    class="modal"
>

    <div class="modal-card">

        <div class="modal-header">

            <h3>
                Tambah Uang di Admin
            </h3>

            <button
                type="button"
                class="modal-close"
                data-close-modal
            >
                ×
            </button>

        </div>


        <form
            action="{{ route('cash-accounts.admin.add') }}"
            method="POST"
        >

            @csrf


            <div class="modal-body">

                <div class="form-group">

                    <label>
                        Jumlah Uang
                    </label>

                    <input
                        type="number"
                        name="amount"
                        class="form-control"
                        min="0.01"
                        step="0.01"
                        required
                        placeholder="Contoh: 100.00"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Keterangan
                    </label>

                    <textarea
                        name="notes"
                        class="form-control"
                        placeholder="Opsional"
                    ></textarea>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="button-cancel"
                    data-close-modal
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="button-save"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ============================================================
     EDIT ADMIN
============================================================ --}}

<div
    id="editAdminModal"
    class="modal"
>

    <div class="modal-card">

        <div class="modal-header">

            <h3>
                Edit Saldo Uang di Admin
            </h3>

            <button
                type="button"
                class="modal-close"
                data-close-modal
            >
                ×
            </button>

        </div>


        <form
            action="{{ route('cash-accounts.admin.update') }}"
            method="POST"
        >

            @csrf
            @method('PATCH')


            <div class="modal-body">

                <div class="form-group">

                    <label>
                        Saldo Baru
                    </label>

                    <input
                        type="number"
                        name="balance"
                        class="form-control"
                        min="0"
                        step="0.01"
                        required
                        value="{{ number_format(
                            (float) $adminAccount->balance,
                            2,
                            '.',
                            ''
                        ) }}"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Alasan Perubahan
                    </label>

                    <textarea
                        name="notes"
                        class="form-control"
                        required
                        placeholder="Contoh: Koreksi hasil hitung cash fisik"
                    ></textarea>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="button-cancel"
                    data-close-modal
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="button-save"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ============================================================
     TAMBAH BANK
============================================================ --}}

<div
    id="addBankModal"
    class="modal"
>

    <div class="modal-card">

        <div class="modal-header">

            <h3>
                Tambah Uang di Bank
            </h3>

            <button
                type="button"
                class="modal-close"
                data-close-modal
            >
                ×
            </button>

        </div>


        <form
            action="{{ route('cash-accounts.bank.add') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            <div class="modal-body">

                <div class="form-group">

                    <label>
                        Jumlah Uang
                    </label>

                    <input
                        type="number"
                        name="amount"
                        class="form-control"
                        min="0.01"
                        step="0.01"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Nama Bank
                    </label>

                    <input
                        type="text"
                        name="bank_name"
                        class="form-control"
                        maxlength="100"
                        required
                        placeholder="Contoh: BNU"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Bukti
                    </label>

                    <input
                        type="file"
                        name="proof"
                        class="form-control"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <div class="form-help">
                        JPG, PNG atau WEBP. Maksimal 5 MB.
                    </div>

                </div>


                <div class="form-group">

                    <label>
                        Keterangan
                    </label>

                    <textarea
                        name="notes"
                        class="form-control"
                    ></textarea>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="button-cancel"
                    data-close-modal
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="button-save"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ============================================================
     EDIT BANK
============================================================ --}}

<div
    id="editBankModal"
    class="modal"
>

    <div class="modal-card">

        <div class="modal-header">

            <h3>
                Edit Saldo Uang di Bank
            </h3>

            <button
                type="button"
                class="modal-close"
                data-close-modal
            >
                ×
            </button>

        </div>


        <form
            action="{{ route('cash-accounts.bank.update') }}"
            method="POST"
        >

            @csrf
            @method('PATCH')


            <div class="modal-body">

                <div class="form-group">

                    <label>
                        Saldo Baru
                    </label>

                    <input
                        type="number"
                        name="balance"
                        class="form-control"
                        min="0"
                        step="0.01"
                        required
                        value="{{ number_format(
                            (float) $bankAccount->balance,
                            2,
                            '.',
                            ''
                        ) }}"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Nama Bank
                    </label>

                    <input
                        type="text"
                        name="bank_name"
                        class="form-control"
                        value="{{ $bankAccount->bank_name }}"
                        maxlength="100"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Alasan Perubahan
                    </label>

                    <textarea
                        name="notes"
                        class="form-control"
                        required
                        placeholder="Alasan koreksi saldo"
                    ></textarea>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="button-cancel"
                    data-close-modal
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="button-save"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ============================================================
     SETOR KE BANK
============================================================ --}}

<div
    id="transferBankModal"
    class="modal"
>

    <div class="modal-card">

        <div class="modal-header">

            <h3>
                Setor Uang Admin ke Bank
            </h3>

            <button
                type="button"
                class="modal-close"
                data-close-modal
            >
                ×
            </button>

        </div>


        <form
            action="{{ route(
                'cash-accounts.transfer-to-bank'
            ) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            <div class="modal-body">

                <div
                    style="
                        margin-bottom:18px;
                        padding:14px;
                        border-radius:8px;
                        background:#f0fdf4;
                        color:#166534;
                        line-height:1.6;
                    "
                >
                    Saldo Admin saat ini:

                    <strong>
                        ${{ number_format(
                            (float) $adminAccount->balance,
                            2
                        ) }}
                    </strong>
                </div>


                <div class="form-group">

                    <label>
                        Jumlah Setoran
                    </label>

                    <input
                        type="number"
                        name="amount"
                        class="form-control"
                        min="0.01"
                        step="0.01"
                        max="{{ (float) $adminAccount->balance }}"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Bank Tujuan
                    </label>

                    <input
                        type="text"
                        name="bank_name"
                        class="form-control"
                        maxlength="100"
                        required
                        value="{{ $bankAccount->bank_name }}"
                        placeholder="Contoh: BNU"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Bukti Setoran Bank
                    </label>

                    <input
                        type="file"
                        name="proof"
                        class="form-control"
                        accept="image/jpeg,image/png,image/webp"
                        required
                    >

                    <div class="form-help">
                        Bukti wajib diunggah.
                        Format JPG, PNG atau WEBP.
                        Maksimal 5 MB.
                    </div>

                </div>


                <div class="form-group">

                    <label>
                        Keterangan
                    </label>

                    <textarea
                        name="notes"
                        class="form-control"
                        placeholder="Contoh: Setoran cash penjualan hari ini"
                    ></textarea>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="button-cancel"
                    data-close-modal
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="button-save"
                >
                    Setor ke Bank
                </button>

            </div>

        </form>

    </div>

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
    | MODAL
    |--------------------------------------------------------------------------
    */

    const modals =
        document.querySelectorAll(
            '.modal'
        );


    function closeAllModals() {

        modals.forEach(
            function (modal) {

                modal.classList.remove(
                    'open'
                );
            }
        );

        document.body.classList.remove(
            'modal-open'
        );
    }


    document
        .querySelectorAll(
            '[data-open-modal]'
        )
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const modalId =
                        button.dataset.openModal;


                    const modal =
                        document.getElementById(
                            modalId
                        );


                    if (!modal) {
                        return;
                    }


                    closeAllModals();


                    modal.classList.add(
                        'open'
                    );


                    document.body.classList.add(
                        'modal-open'
                    );
                }
            );
        });


    document
        .querySelectorAll(
            '[data-close-modal]'
        )
        .forEach(function (button) {

            button.addEventListener(
                'click',
                closeAllModals
            );
        });


    modals.forEach(
        function (modal) {

            modal.addEventListener(
                'click',
                function (event) {

                    if (
                        event.target
                        === modal
                    ) {

                        closeAllModals();
                    }
                }
            );
        }
    );


    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape'
            ) {

                closeAllModals();
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI SETOR KE BANK
    |--------------------------------------------------------------------------
    */

    const transferForm =
        document.querySelector(
            '#transferBankModal form'
        );


    if (transferForm) {

        transferForm.addEventListener(
            'submit',
            function (event) {

                const amountInput =
                    transferForm.querySelector(
                        'input[name="amount"]'
                    );


                const bankInput =
                    transferForm.querySelector(
                        'input[name="bank_name"]'
                    );


                const amount =
                    Number(
                        amountInput.value
                        || 0
                    );


                const bank =
                    bankInput.value.trim();


                if (
                    !confirm(
                        'Setor $'
                        + amount.toFixed(2)
                        + ' dari Uang di Admin ke Bank '
                        + bank
                        + '?\n\n'
                        + 'Uang di Admin akan otomatis berkurang dan Uang di Bank akan bertambah.'
                    )
                ) {

                    event.preventDefault();
                }
            }
        );
    }
</script>

</body>
</html>