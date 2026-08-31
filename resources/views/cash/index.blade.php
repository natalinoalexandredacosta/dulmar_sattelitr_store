<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Kas Inventory - Dulmar Satellite Store</title>

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
            color: #1f2937;
            overflow-x: hidden;
        }

        body.menu-open {
            overflow: hidden;
        }

        .container {
            width: 100%;
            min-height: 100vh;
        }


        /* =========================================================
           SIDEBAR
        ========================================================= */

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

        .brand {
            margin-bottom: 32px;
        }

        .brand-title {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
        }

        .brand-subtitle {
            margin-top: 5px;
            font-size: 12px;
            color: #94a3b8;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 14px;
            border-radius: 8px;
            color: #e2e8f0;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .sidebar-menu a:hover {
            background: #334155;
            color: #ffffff;
        }

        .sidebar-menu a.active {
            background: #e11d2e;
            color: white;
        }

        .sidebar-spacer {
            flex: 1;
        }

        .logout-form {
            margin-top: 25px;
        }

        .logout-button {
            width: 100%;
            padding: 11px 14px;
            border: 0;
            border-radius: 8px;
            background: #dc2626;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .logout-button:hover {
            background: #b91c1c;
        }


        /* =========================================================
           MOBILE HEADER
        ========================================================= */

        .mobile-header {
            display: none;
        }

        .mobile-overlay {
            display: none;
        }


        /* =========================================================
           MAIN
        ========================================================= */

        .main-content {
            margin-left: 245px;
            min-height: 100vh;
            padding: 30px;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .page-title {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            color: #111827;
        }

        .page-subtitle {
            margin-top: 6px;
            margin-bottom: 0;
            color: #64748b;
            font-size: 15px;
        }


        /* =========================================================
           ALERT
        ========================================================= */

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 18px;
        }


        /* =========================================================
           SUMMARY
        ========================================================= */

        .summary-grid {
            display: grid;
            grid-template-columns:
                repeat(5, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 18px;
            box-shadow:
                0 2px 10px rgba(15, 23, 42, 0.06);
            border-left: 5px solid #2563eb;
        }

        .summary-card.income {
            border-left-color: #16a34a;
        }

        .summary-card.expense {
            border-left-color: #dc2626;
        }

        .summary-card.pending-expense {
            border-left-color: #f59e0b;
        }

        .summary-card.pending-income {
            border-left-color: #8b5cf6;
        }

        .summary-label {
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .summary-value {
            margin: 0;
            font-size: 23px;
            font-weight: 800;
            color: #111827;
        }

        .summary-card.income .summary-value {
            color: #15803d;
        }

        .summary-card.expense .summary-value {
            color: #dc2626;
        }

        .summary-card.pending-expense .summary-value {
            color: #d97706;
        }

        .summary-card.pending-income .summary-value {
            color: #7c3aed;
        }

        .summary-note {
            margin-top: 7px;
            font-size: 11px;
            color: #94a3b8;
        }


        /* =========================================================
           CARD
        ========================================================= */

        .card {
            margin-bottom: 22px;
            background: white;
            border-radius: 12px;
            box-shadow:
                0 2px 10px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 17px;
            font-weight: 800;
            color: #111827;
        }

        .card-header-subtitle {
            margin-top: 5px;
            color: #64748b;
            font-size: 12px;
            font-weight: 400;
        }

        .card-body {
            padding: 20px;
        }


        /* =========================================================
           FORM
        ========================================================= */

        .form-grid {
            display: grid;
            grid-template-columns:
                170px
                210px
                190px
                minmax(220px, 1fr)
                140px;
            gap: 14px;
            align-items: end;
        }

        .form-group {
            min-width: 0;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
        }

        .input-group {
            display: flex;
        }

        .input-prefix {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            border: 1px solid #d1d5db;
            border-right: 0;
            border-radius: 7px 0 0 7px;
            background: #f8fafc;
            font-weight: 700;
        }

        .form-control {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            outline: none;
            font-size: 14px;
            background: white;
        }

        textarea.form-control {
            font-family: Arial, sans-serif;
        }

        .input-group .form-control {
            border-radius: 0 7px 7px 0;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow:
                0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .borrower-group {
            display: none;
            margin-top: 16px;
            padding: 16px;
            border-radius: 10px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
        }

        .borrower-group.show {
            display: block;
        }

        .borrower-grid {
            display: grid;
            grid-template-columns:
                minmax(220px, 350px)
                minmax(250px, 1fr);
            gap: 15px;
        }

        .loan-info {
            font-size: 12px;
            color: #9a3412;
            margin-top: 7px;
        }

        .btn-primary {
            width: 100%;
            padding: 11px 16px;
            border: 0;
            border-radius: 7px;
            background: #2563eb;
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }


        /* =========================================================
           TABLE
        ========================================================= */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1500px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 13px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
            font-size: 12px;
        }

        th {
            background: #f8fafc;
            color: #374151;
            font-weight: 800;
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .row-pending {
            background: #fffdf5;
        }

        .row-rejected {
            background: #fff7f7;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: 800;
        }

        .income-amount {
            color: #15803d;
        }

        .expense-amount {
            color: #dc2626;
        }

        .rejection-box {
            margin-top: 7px;
            padding: 8px 9px;
            border: 1px solid #fecaca;
            border-radius: 6px;
            background: #fee2e2;
            color: #991b1b;
            font-size: 11px;
            line-height: 1.45;
        }

        .rejection-title {
            display: block;
            margin-bottom: 3px;
            font-weight: 800;
        }


        /* =========================================================
           BADGE
        ========================================================= */

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .badge-income {
            background: #dcfce7;
            color: #166534;
        }

        .badge-expense {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background: #dcfce7;
            color: #166534;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-historical {
            background: #e0f2fe;
            color: #075985;
            border: 1px solid #bae6fd;
        }

        .row-historical {
            background: #f8fbff;
        }

        .historical-box {
            margin-top: 7px;
            padding: 8px 9px;
            border: 1px solid #bae6fd;
            border-radius: 6px;
            background: #f0f9ff;
            color: #075985;
            font-size: 11px;
            line-height: 1.45;
        }

        .historical-title {
            display: block;
            margin-bottom: 3px;
            font-weight: 800;
        }

        .category-badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 6px;
            background: #eef2ff;
            color: #3730a3;
            font-size: 11px;
            font-weight: 700;
        }

        .loan-badge {
            background: #ffedd5;
            color: #9a3412;
        }

        .repayment-badge {
            background: #ede9fe;
            color: #6d28d9;
        }


        /* =========================================================
           ACTION BUTTONS
        ========================================================= */

        .action-group {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            justify-content: center;
            align-items: center;
        }

        .action-group form {
            margin: 0;
        }

        .btn-edit,
        .btn-delete,
        .btn-approve,
        .btn-reject {
            border: 0;
            color: white;
            padding: 7px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-edit {
            background: #f59e0b;
        }

        .btn-edit:hover {
            background: #d97706;
        }

        .btn-delete {
            background: #64748b;
        }

        .btn-delete:hover {
            background: #475569;
        }

        .btn-approve {
            background: #16a34a;
        }

        .btn-approve:hover {
            background: #15803d;
        }

        .btn-reject {
            background: #dc2626;
        }

        .btn-reject:hover {
            background: #b91c1c;
        }

        .automatic-label {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
        }

        .processed-info {
            margin-top: 5px;
            font-size: 10px;
            color: #64748b;
            line-height: 1.4;
        }

        .empty-row {
            padding: 35px;
            text-align: center;
            color: #64748b;
        }

        .pagination-wrapper {
            padding: 18px 20px;
            border-top: 1px solid #e5e7eb;
        }


        /* =========================================================
           MODAL
        ========================================================= */

        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 2000;
            background: rgba(15, 23, 42, 0.55);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-card {
            width: 100%;
            max-width: 540px;
            max-height: 90vh;
            overflow-y: auto;
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.2);
        }

        .modal-title {
            margin: 0 0 20px;
            font-size: 22px;
            color: #111827;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-cancel,
        .btn-save {
            border: 0;
            color: white;
            padding: 10px 16px;
            border-radius: 7px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-cancel {
            background: #64748b;
        }

        .btn-cancel:hover {
            background: #475569;
        }

        .btn-save {
            background: #2563eb;
        }

        .btn-save:hover {
            background: #1d4ed8;
        }

        .reject-info {
            margin-bottom: 18px;
            padding: 14px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.7;
        }

        .reject-warning {
            margin-top: 15px;
            padding: 10px 12px;
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 7px;
            font-size: 12px;
            line-height: 1.5;
        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 1300px) {

            .summary-grid {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

            .form-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .form-grid .description-group,
            .form-grid .button-group {
                grid-column: span 2;
            }
        }


        @media (max-width: 900px) {

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.25s ease;
            }

            body.menu-open .sidebar {
                transform: translateX(0);
            }

            .mobile-header {
                position: sticky;
                top: 0;
                z-index: 800;
                display: flex;
                align-items: center;
                justify-content: space-between;
                height: 60px;
                padding: 0 16px;
                background: #1f2b3a;
                color: white;
                box-shadow:
                    0 2px 8px rgba(15, 23, 42, 0.15);
            }

            .mobile-menu-button {
                width: 42px;
                height: 42px;
                border: 0;
                border-radius: 7px;
                background: #334155;
                color: white;
                font-size: 20px;
                cursor: pointer;
            }

            .mobile-title {
                font-size: 15px;
                font-weight: 800;
            }

            .mobile-overlay {
                position: fixed;
                inset: 0;
                z-index: 850;
                background: rgba(15, 23, 42, 0.55);
            }

            body.menu-open .mobile-overlay {
                display: block;
            }

            .main-content {
                margin-left: 0;
                padding: 20px 14px;
            }

            .page-title {
                font-size: 27px;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-grid .description-group,
            .form-grid .button-group {
                grid-column: auto;
            }

            .borrower-grid {
                grid-template-columns: 1fr;
            }

            .modal-card {
                padding: 18px;
            }

            .modal-actions {
                flex-direction: column;
            }

            .modal-actions button {
                width: 100%;
            }
        }

    </style>

</head>


<body>

<div class="container">


    <aside
        class="sidebar"
        id="sidebar"
    >

        <div class="brand">

            <h1 class="brand-title">
                Dulmar Satellite Store
            </h1>

            <div class="brand-subtitle">
                Inventory Management
            </div>

        </div>


        <nav class="sidebar-menu">

            <a href="{{ route('dashboard') }}">
                Dashboard
            </a>

            @if(Route::has('products.index'))
                <a href="{{ route('products.index') }}">
                    Daftar Barang
                </a>
            @endif

            @if(Route::has('stock-ins.index'))
                <a href="{{ route('stock-ins.index') }}">
                    Stok Masuk
                </a>
            @endif

            @if(Route::has('stock-outs.index'))
                <a href="{{ route('stock-outs.index') }}">
                    Stok Keluar
                </a>
            @endif

            @if(Route::has('cash.index'))
                <a
                    href="{{ route('cash.index') }}"
                    class="active"
                >
                    Kas Inventory
                </a>
            @endif

            @if(Route::has('suppliers.index'))
                <a href="{{ route('suppliers.index') }}">
                    Supplier
                </a>
            @endif

            @if(Route::has('customers.index'))
                <a href="{{ route('customers.index') }}">
                    Pelanggan
                </a>
            @endif

            @if(Route::has('reports.index'))
                <a href="{{ route('reports.index') }}">
                    Laporan
                </a>
            @endif

            @if(Route::has('tv-vouchers.index'))
                <a href="{{ route('tv-vouchers.index') }}">
                    TV Voucher
                </a>
            @endif

        </nav>


        <div class="sidebar-spacer"></div>


        <form
            method="POST"
            action="{{ route('logout') }}"
            class="logout-form"
        >
            @csrf

            <button
                type="submit"
                class="logout-button"
            >
                Logout
            </button>
        </form>

    </aside>


    <div
        class="mobile-overlay"
        id="mobileOverlay"
    ></div>


    <header class="mobile-header">

        <button
            type="button"
            class="mobile-menu-button"
            id="mobileMenuButton"
        >
            ☰
        </button>

        <div class="mobile-title">
            Kas Inventory
        </div>

        <div style="width:42px;"></div>

    </header>


    <main class="main-content">


        <div class="page-header">

            <div>

                <h1 class="page-title">
                    Kas Inventory
                </h1>

                <p class="page-subtitle">
                    Kelola cash masuk, cash keluar, pinjaman dan persetujuan transaksi kas.
                </p>

            </div>

        </div>


        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        @if($errors->any())
            <div class="alert alert-danger">

                <ul>
                    @foreach($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>

            </div>
        @endif


        <div class="alert alert-info">
            Cash keluar, pinjaman, dan pengembalian pinjaman yang membutuhkan persetujuan
            tidak akan memengaruhi saldo sebelum disetujui Admin.
        </div>


        <section class="summary-grid">


            <div class="summary-card">

                <div class="summary-label">
                    Cash Saat Ini
                </div>

                <h2 class="summary-value">
                    ${{ number_format($cashBalance, 2) }}
                </h2>

                <div class="summary-note">
                    Berdasarkan transaksi yang sudah disetujui
                </div>

            </div>


            <div class="summary-card income">

                <div class="summary-label">
                    Total Cash Masuk
                </div>

                <h2 class="summary-value">
                    ${{ number_format($totalIncome, 2) }}
                </h2>

                <div class="summary-note">
                    Cash masuk yang sudah disetujui
                </div>

            </div>


            <div class="summary-card expense">

                <div class="summary-label">
                    Total Cash Keluar
                </div>

                <h2 class="summary-value">
                    ${{ number_format($totalExpense, 2) }}
                </h2>

                <div class="summary-note">
                    Cash keluar yang sudah disetujui
                </div>

            </div>


            <div class="summary-card pending-expense">

                <div class="summary-label">
                    Menunggu Cash Keluar
                </div>

                <h2 class="summary-value">
                    ${{ number_format($totalPendingExpense ?? 0, 2) }}
                </h2>

                <div class="summary-note">
                    Belum mengurangi saldo
                </div>

            </div>


            <div class="summary-card pending-income">

                <div class="summary-label">
                    Menunggu Cash Masuk
                </div>

                <h2 class="summary-value">
                    ${{ number_format($totalPendingIncome ?? 0, 2) }}
                </h2>

                <div class="summary-note">
                    Belum menambah saldo
                </div>

            </div>

        </section>


        <section class="card">

            <div class="card-header">

                Tambah Transaksi Kas

                <div class="card-header-subtitle">
                    Cash keluar akan masuk status menunggu dan harus disetujui Admin.
                </div>

            </div>


            <div class="card-body">

                <form
                    action="{{ route('cash.store') }}"
                    method="POST"
                >

                    @csrf


                    <div class="form-grid">


                        <div class="form-group">

                            <label class="form-label">
                                Jenis Transaksi
                            </label>

                            <select
                                name="type"
                                id="cashType"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    -- Pilih --
                                </option>

                                <option
                                    value="income"
                                    {{ old('type') === 'income' ? 'selected' : '' }}
                                >
                                    Cash Masuk
                                </option>

                                <option
                                    value="expense"
                                    {{ old('type') === 'expense' ? 'selected' : '' }}
                                >
                                    Cash Keluar
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label class="form-label">
                                Kategori
                            </label>

                            <select
                                name="category"
                                id="cashCategory"
                                class="form-control"
                                required
                            >
                                <option value="">
                                    -- Pilih Kategori --
                                </option>
                            </select>

                        </div>


                        <div class="form-group">

                            <label class="form-label">
                                Jumlah
                            </label>

                            <div class="input-group">

                                <span class="input-prefix">
                                    $
                                </span>

                                <input
                                    type="number"
                                    name="amount"
                                    step="0.01"
                                    min="0.01"
                                    value="{{ old('amount') }}"
                                    class="form-control"
                                    placeholder="0.00"
                                    required
                                >

                            </div>

                        </div>


                        <div class="form-group description-group">

                            <label class="form-label">
                                Keterangan
                            </label>

                            <input
                                type="text"
                                name="description"
                                value="{{ old('description') }}"
                                class="form-control"
                                placeholder="Contoh: Beli perlengkapan toko"
                            >

                        </div>


                        <div class="form-group button-group">

                            <button
                                type="submit"
                                class="btn-primary"
                            >
                                Simpan
                            </button>

                        </div>

                    </div>


                    <div
                        id="borrowerGroup"
                        class="borrower-group"
                    >

                        <div class="borrower-grid">


                            <div class="form-group">

                                <label class="form-label">
                                    Nama Peminjam
                                </label>

                                <input
                                    type="text"
                                    name="borrower_name"
                                    id="borrowerName"
                                    value="{{ old('borrower_name') }}"
                                    class="form-control"
                                    placeholder="Contoh: João"
                                >

                            </div>


                            <div>

                                <div class="form-label">
                                    Informasi Pinjaman
                                </div>

                                <div class="loan-info">

                                    Untuk Pinjaman Keluar, saldo baru berkurang setelah Admin menyetujui.

                                    <br><br>

                                    Untuk Pengembalian Pinjaman, saldo baru bertambah setelah Admin menyetujui pengembalian tersebut.

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </section>


        <section class="card">

            <div class="card-header">

                Riwayat Transaksi Kas

                <div class="card-header-subtitle">
                    Semua transaksi cash, persetujuan, pinjaman, dan pengembalian tercatat di sini.
                </div>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Kategori</th>
                            <th>Peminjam</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Dibuat Oleh</th>
                            <th>Diproses Oleh</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-center">Aksi</th>
                        </tr>

                    </thead>


                    <tbody>

                    @forelse($transactions as $transaction)

                        @php
                            $isHistorical =
                                $transaction->approved_by === 'System - Data Historis';
                        @endphp

                        <tr
                            class="
                                {{ $transaction->approval_status === 'pending' ? 'row-pending' : '' }}

                                {{
                                    $transaction->approval_status === 'rejected'
                                    && !$isHistorical
                                        ? 'row-rejected'
                                        : ''
                                }}

                                {{ $isHistorical ? 'row-historical' : '' }}
                            "
                        >


                            <td>
                                {{
                                    $transactions->firstItem()
                                    +
                                    $loop->index
                                }}
                            </td>


                            <td>
                                {{
                                    optional(
                                        $transaction->transaction_date
                                    )->format('d-m-Y')
                                }}
                            </td>


                            <td>

                                @if($transaction->type === 'income')

                                    <span class="badge badge-income">
                                        Cash Masuk
                                    </span>

                                @else

                                    <span class="badge badge-expense">
                                        Cash Keluar
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if(
                                    $transaction->category
                                    === 'Pinjaman Keluar'
                                )

                                    <span class="category-badge loan-badge">
                                        Pinjaman Keluar
                                    </span>

                                @elseif(
                                    $transaction->category
                                    === 'Pengembalian Pinjaman'
                                )

                                    <span class="category-badge repayment-badge">
                                        Pengembalian Pinjaman
                                    </span>

                                @elseif($transaction->category)

                                    <span class="category-badge">
                                        {{ $transaction->category }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @if($transaction->borrower_name)

                                    <strong>
                                        {{ $transaction->borrower_name }}
                                    </strong>

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @if($isHistorical)

                                    <span class="badge badge-historical">
                                        📦 Historis
                                    </span>

                                @elseif(
                                    $transaction->approval_status
                                    === 'pending'
                                )

                                    <span class="badge badge-pending">
                                        ⏳ Menunggu
                                    </span>

                                @elseif(
                                    $transaction->approval_status
                                    === 'rejected'
                                )

                                    <span class="badge badge-rejected">
                                        ❌ Ditolak
                                    </span>

                                @else

                                    <span class="badge badge-approved">
                                        ✓ Disetujui
                                    </span>

                                @endif

                            </td>


                            <td>

                                <div>
                                    {{ $transaction->description ?? '-' }}
                                </div>


                                @if($isHistorical)

                                    <div class="historical-box">

                                        <span class="historical-title">
                                            📦 Transaksi Historis
                                        </span>

                                        Pembelian stok ini terjadi sebelum sistem
                                        Kas Inventory mulai digunakan.

                                        <br>

                                        Transaksi ini tidak mengurangi saldo
                                        Kas Inventory saat ini.

                                    </div>


                                @elseif(
                                    $transaction->approval_status === 'rejected'
                                    &&
                                    $transaction->rejection_reason
                                )

                                    <div class="rejection-box">

                                        <span class="rejection-title">
                                            Alasan Penolakan:
                                        </span>

                                        {{ $transaction->rejection_reason }}

                                    </div>

                                @endif

                            </td>


                            <td>
                                {{ $transaction->created_by ?? '-' }}
                            </td>


                            <td>

                                @if($transaction->approved_by)

                                    <strong>
                                        {{ $transaction->approved_by }}
                                    </strong>


                                    @if($transaction->approved_at)

                                        <div class="processed-info">

                                            {{
                                                $transaction
                                                    ->approved_at
                                                    ->format('d-m-Y H:i')
                                            }}

                                        </div>

                                    @endif

                                @else

                                    -

                                @endif

                            </td>


                            <td
                                class="
                                    text-right
                                    fw-bold
                                    {{
                                        $transaction->type === 'income'
                                            ? 'income-amount'
                                            : 'expense-amount'
                                    }}
                                "
                            >

                                @if(
                                    $transaction->type
                                    === 'income'
                                )

                                    +${{
                                        number_format(
                                            $transaction->amount,
                                            2
                                        )
                                    }}

                                @else

                                    -${{
                                        number_format(
                                            $transaction->amount,
                                            2
                                        )
                                    }}

                                @endif

                            </td>


                            <td class="text-center">

                                <div class="action-group">


                                    @if(
                                        $transaction->approval_status
                                        === 'pending'
                                    )

                                        <form
                                            action="{{ route('cash.approve', $transaction) }}"
                                            method="POST"
                                            onsubmit="return confirm('Setujui transaksi ini? Saldo kas akan berubah setelah disetujui.')"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn-approve"
                                            >
                                                Setujui
                                            </button>

                                        </form>


                                        <button
                                            type="button"
                                            class="btn-reject"
                                            onclick='openRejectCashModal(
                                                {{ $transaction->id }},
                                                @json($transaction->category),
                                                @json((string) $transaction->amount),
                                                @json($transaction->borrower_name),
                                                @json($transaction->description)
                                            )'
                                        >
                                            Tolak
                                        </button>

                                    @endif


                                    @if($transaction->canEdit())

                                        <button
                                            type="button"
                                            class="btn-edit"
                                            onclick='openEditCashModal(
                                                {{ $transaction->id }},
                                                @json($transaction->type),
                                                @json($transaction->category),
                                                @json($transaction->borrower_name),
                                                @json((string) $transaction->amount),
                                                @json($transaction->description)
                                            )'
                                        >
                                            Edit
                                        </button>

                                    @endif


                                    @if($transaction->canDelete())

                                        <form
                                            action="{{ route('cash.destroy', $transaction) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus transaksi cash ini?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn-delete"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    @endif


                                    @if(
                                        !$transaction->canEdit()
                                        &&
                                        !$transaction->canDelete()
                                        &&
                                        $transaction->approval_status
                                        !== 'pending'
                                    )

                                        <span class="automatic-label">
                                            Selesai
                                        </span>

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty


                        <tr>

                            <td
                                colspan="11"
                                class="empty-row"
                            >
                                Belum ada transaksi kas.
                            </td>

                        </tr>


                    @endforelse

                    </tbody>

                </table>

            </div>


            @if($transactions->hasPages())

                <div class="pagination-wrapper">
                    {{ $transactions->links() }}
                </div>

            @endif

        </section>


    </main>

</div>



<!-- =========================================================
     MODAL EDIT
========================================================= -->

<div
    id="editCashModal"
    class="modal-backdrop"
>

    <div class="modal-card">


        <h2 class="modal-title">
            Edit Transaksi Kas
        </h2>


        <form
            id="editCashForm"
            method="POST"
        >

            @csrf
            @method('PATCH')


            <div class="form-group">

                <label class="form-label">
                    Jenis Transaksi
                </label>

                <select
                    id="editCashType"
                    name="type"
                    class="form-control"
                    required
                >

                    <option value="income">
                        Cash Masuk
                    </option>

                    <option value="expense">
                        Cash Keluar
                    </option>

                </select>

            </div>


            <div
                class="form-group"
                style="margin-top:16px;"
            >

                <label class="form-label">
                    Kategori
                </label>

                <select
                    id="editCashCategory"
                    name="category"
                    class="form-control"
                    required
                >
                </select>

            </div>


            <div
                id="editBorrowerGroup"
                class="form-group"
                style="display:none; margin-top:16px;"
            >

                <label class="form-label">
                    Nama Peminjam
                </label>

                <input
                    id="editBorrowerName"
                    type="text"
                    name="borrower_name"
                    class="form-control"
                    placeholder="Nama peminjam"
                >

            </div>


            <div
                class="form-group"
                style="margin-top:16px;"
            >

                <label class="form-label">
                    Jumlah
                </label>

                <div class="input-group">

                    <span class="input-prefix">
                        $
                    </span>

                    <input
                        id="editCashAmount"
                        type="number"
                        name="amount"
                        step="0.01"
                        min="0.01"
                        class="form-control"
                        required
                    >

                </div>

            </div>


            <div
                class="form-group"
                style="margin-top:16px;"
            >

                <label class="form-label">
                    Keterangan
                </label>

                <input
                    id="editCashDescription"
                    type="text"
                    name="description"
                    class="form-control"
                >

            </div>


            <div class="modal-actions">

                <button
                    type="button"
                    class="btn-cancel"
                    onclick="closeEditCashModal()"
                >
                    Batal
                </button>


                <button
                    type="submit"
                    class="btn-save"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>



<!-- =========================================================
     MODAL TOLAK
========================================================= -->

<div
    id="rejectCashModal"
    class="modal-backdrop"
>

    <div class="modal-card">


        <h2
            class="modal-title"
            style="color:#b91c1c;"
        >
            ❌ Tolak Transaksi Kas
        </h2>


        <div class="reject-info">

            <div>
                <strong>ID:</strong>
                <span id="rejectTransactionIdText">
                    -
                </span>
            </div>


            <div>
                <strong>Kategori:</strong>
                <span id="rejectCategoryText">
                    -
                </span>
            </div>


            <div>
                <strong>Jumlah:</strong>
                <span id="rejectAmountText">
                    $0.00
                </span>
            </div>


            <div
                id="rejectBorrowerRow"
                style="display:none;"
            >

                <strong>Peminjam:</strong>

                <span id="rejectBorrowerText">
                    -
                </span>

            </div>


            <div>

                <strong>Keterangan:</strong>

                <span id="rejectDescriptionText">
                    -
                </span>

            </div>

        </div>


        <form
            id="rejectCashForm"
            method="POST"
        >

            @csrf
            @method('PATCH')


            <div class="form-group">

                <label
                    class="form-label"
                    for="rejectionReason"
                >
                    Alasan Penolakan

                    <span style="color:#dc2626;">
                        *
                    </span>

                </label>


                <textarea
                    id="rejectionReason"
                    name="rejection_reason"
                    class="form-control"
                    rows="5"
                    minlength="3"
                    maxlength="1000"
                    placeholder="Contoh: Pengeluaran belum dapat disetujui karena dokumen pendukung belum lengkap."
                    required
                    style="
                        resize:vertical;
                        min-height:120px;
                    "
                ></textarea>


                <div
                    style="
                        margin-top:7px;
                        color:#64748b;
                        font-size:11px;
                        line-height:1.5;
                    "
                >
                    Alasan wajib diisi. Alasan akan tersimpan
                    di riwayat transaksi dan dikirim ke Telegram.
                </div>

            </div>


            <div class="reject-warning">

                <strong>Perhatian:</strong>

                setelah transaksi ditolak,
                status transaksi akan menjadi

                <strong>Ditolak</strong>

                dan saldo kas tidak akan berubah.

            </div>


            <div class="modal-actions">


                <button
                    type="button"
                    class="btn-cancel"
                    onclick="closeRejectCashModal()"
                >
                    Batal
                </button>


                <button
                    type="submit"
                    class="btn-reject"
                    style="
                        padding:10px 16px;
                        font-size:13px;
                    "
                    onclick="return confirm('Yakin ingin menolak transaksi ini?')"
                >
                    ❌ Konfirmasi Tolak
                </button>

            </div>

        </form>

    </div>

</div>



<script>

    /*
    |--------------------------------------------------------------------------
    | MOBILE MENU
    |--------------------------------------------------------------------------
    */

    const mobileMenuButton =
        document.getElementById(
            'mobileMenuButton'
        );

    const mobileOverlay =
        document.getElementById(
            'mobileOverlay'
        );


    if (mobileMenuButton) {

        mobileMenuButton.addEventListener(
            'click',
            function () {

                document.body.classList.toggle(
                    'menu-open'
                );

            }
        );

    }


    if (mobileOverlay) {

        mobileOverlay.addEventListener(
            'click',
            function () {

                document.body.classList.remove(
                    'menu-open'
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORY CONFIG
    |--------------------------------------------------------------------------
    */

    const incomeCategories = [

        'Saldo Awal',

        'Modal Tambahan',

        'Penjualan Barang',

        'Pengembalian Pinjaman',

        'Pengembalian Dana',

        'Pendapatan Lain'

    ];


    const expenseCategories = [

        'Belanja Stok',

        'Pinjaman Keluar',

        'Biaya Operasional',

        'Transport',

        'Listrik / Internet',

        'Servis / Perbaikan',

        'Pengambilan Pribadi',

        'Keperluan Lain',

        'Koreksi Kas'

    ];


    function fillCategoryOptions(
        selectElement,
        type,
        selectedValue = ''
    ) {

        selectElement.innerHTML = '';


        const emptyOption =
            document.createElement(
                'option'
            );


        emptyOption.value = '';

        emptyOption.textContent =
            '-- Pilih Kategori --';


        selectElement.appendChild(
            emptyOption
        );


        let categories = [];


        if (type === 'income') {
            categories = incomeCategories;
        }


        if (type === 'expense') {
            categories = expenseCategories;
        }


        categories.forEach(
            function (category) {

                const option =
                    document.createElement(
                        'option'
                    );


                option.value =
                    category;

                option.textContent =
                    category;


                if (
                    category
                    === selectedValue
                ) {

                    option.selected =
                        true;

                }


                selectElement.appendChild(
                    option
                );

            }
        );


        if (
            selectedValue
            &&
            !categories.includes(
                selectedValue
            )
        ) {

            const customOption =
                document.createElement(
                    'option'
                );


            customOption.value =
                selectedValue;

            customOption.textContent =
                selectedValue;

            customOption.selected =
                true;


            selectElement.appendChild(
                customOption
            );

        }

    }


    function isLoanCategory(
        category
    ) {

        return (
            category === 'Pinjaman Keluar'
            ||
            category === 'Pengembalian Pinjaman'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH
    |--------------------------------------------------------------------------
    */

    const cashType =
        document.getElementById(
            'cashType'
        );

    const cashCategory =
        document.getElementById(
            'cashCategory'
        );

    const borrowerGroup =
        document.getElementById(
            'borrowerGroup'
        );

    const borrowerName =
        document.getElementById(
            'borrowerName'
        );

    const oldCategory =
        @json(old('category'));


    function updateBorrowerVisibility()
    {

        if (
            cashCategory
            &&
            isLoanCategory(
                cashCategory.value
            )
        ) {

            borrowerGroup
                .classList
                .add(
                    'show'
                );

            borrowerName.required =
                true;

        } else {

            borrowerGroup
                .classList
                .remove(
                    'show'
                );

            borrowerName.required =
                false;

        }

    }


    if (
        cashType
        &&
        cashCategory
    ) {

        fillCategoryOptions(
            cashCategory,
            cashType.value,
            oldCategory
        );


        updateBorrowerVisibility();


        cashType.addEventListener(
            'change',
            function () {

                fillCategoryOptions(
                    cashCategory,
                    this.value
                );


                updateBorrowerVisibility();

            }
        );


        cashCategory.addEventListener(
            'change',
            function () {

                updateBorrowerVisibility();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MODAL EDIT
    |--------------------------------------------------------------------------
    */

    function openEditCashModal(
        id,
        type,
        category,
        borrower,
        amount,
        description
    ) {

        const modal =
            document.getElementById(
                'editCashModal'
            );

        const form =
            document.getElementById(
                'editCashForm'
            );

        const typeInput =
            document.getElementById(
                'editCashType'
            );

        const categoryInput =
            document.getElementById(
                'editCashCategory'
            );

        const borrowerGroupEdit =
            document.getElementById(
                'editBorrowerGroup'
            );

        const borrowerInput =
            document.getElementById(
                'editBorrowerName'
            );

        const amountInput =
            document.getElementById(
                'editCashAmount'
            );

        const descriptionInput =
            document.getElementById(
                'editCashDescription'
            );


        form.action =
            '{{ url('/cash') }}/'
            + id;


        typeInput.value =
            type;


        fillCategoryOptions(
            categoryInput,
            type,
            category || ''
        );


        borrowerInput.value =
            borrower || '';

        amountInput.value =
            amount;

        descriptionInput.value =
            description || '';


        if (
            isLoanCategory(
                category
            )
        ) {

            borrowerGroupEdit.style.display =
                'block';

            borrowerInput.required =
                true;

        } else {

            borrowerGroupEdit.style.display =
                'none';

            borrowerInput.required =
                false;

        }


        modal.style.display =
            'flex';

    }


    const editCashType =
        document.getElementById(
            'editCashType'
        );

    const editCashCategory =
        document.getElementById(
            'editCashCategory'
        );

    const editBorrowerGroup =
        document.getElementById(
            'editBorrowerGroup'
        );

    const editBorrowerName =
        document.getElementById(
            'editBorrowerName'
        );


    function updateEditBorrowerVisibility()
    {

        if (
            isLoanCategory(
                editCashCategory.value
            )
        ) {

            editBorrowerGroup.style.display =
                'block';

            editBorrowerName.required =
                true;

        } else {

            editBorrowerGroup.style.display =
                'none';

            editBorrowerName.required =
                false;

        }

    }


    if (
        editCashType
        &&
        editCashCategory
    ) {

        editCashType.addEventListener(
            'change',
            function () {

                fillCategoryOptions(
                    editCashCategory,
                    this.value
                );


                updateEditBorrowerVisibility();

            }
        );

    }


    if (editCashCategory) {

        editCashCategory.addEventListener(
            'change',
            function () {

                updateEditBorrowerVisibility();

            }
        );

    }


    function closeEditCashModal()
    {

        document
            .getElementById(
                'editCashModal'
            )
            .style
            .display =
            'none';

    }


    const editCashModal =
        document.getElementById(
            'editCashModal'
        );


    if (editCashModal) {

        editCashModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === this
                ) {

                    closeEditCashModal();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MODAL TOLAK
    |--------------------------------------------------------------------------
    */

    function openRejectCashModal(
        id,
        category,
        amount,
        borrower,
        description
    ) {

        const modal =
            document.getElementById(
                'rejectCashModal'
            );

        const form =
            document.getElementById(
                'rejectCashForm'
            );

        const idText =
            document.getElementById(
                'rejectTransactionIdText'
            );

        const categoryText =
            document.getElementById(
                'rejectCategoryText'
            );

        const amountText =
            document.getElementById(
                'rejectAmountText'
            );

        const borrowerRow =
            document.getElementById(
                'rejectBorrowerRow'
            );

        const borrowerText =
            document.getElementById(
                'rejectBorrowerText'
            );

        const descriptionText =
            document.getElementById(
                'rejectDescriptionText'
            );

        const reasonInput =
            document.getElementById(
                'rejectionReason'
            );


        form.action =
            '{{ url('/cash') }}/'
            + id
            + '/reject';


        idText.textContent =
            '#'
            + id;


        categoryText.textContent =
            category || '-';


        amountText.textContent =
            '$'
            + parseFloat(
                amount || 0
            ).toFixed(2);


        descriptionText.textContent =
            description || '-';


        if (
            borrower
            &&
            String(
                borrower
            ).trim() !== ''
        ) {

            borrowerRow.style.display =
                'block';

            borrowerText.textContent =
                borrower;

        } else {

            borrowerRow.style.display =
                'none';

            borrowerText.textContent =
                '-';

        }


        reasonInput.value =
            '';


        modal.style.display =
            'flex';


        setTimeout(
            function () {

                reasonInput.focus();

            },
            100
        );

    }


    function closeRejectCashModal()
    {

        const modal =
            document.getElementById(
                'rejectCashModal'
            );

        const reasonInput =
            document.getElementById(
                'rejectionReason'
            );


        if (reasonInput) {

            reasonInput.value =
                '';

        }


        modal.style.display =
            'none';

    }


    const rejectCashModal =
        document.getElementById(
            'rejectCashModal'
        );


    if (rejectCashModal) {

        rejectCashModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === this
                ) {

                    closeRejectCashModal();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ESC TUTUP MODAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape'
            ) {

                if (
                    editCashModal
                    &&
                    editCashModal.style.display
                    === 'flex'
                ) {

                    closeEditCashModal();

                }


                if (
                    rejectCashModal
                    &&
                    rejectCashModal.style.display
                    === 'flex'
                ) {

                    closeRejectCashModal();

                }

            }

        }
    );

</script>

</body>

</html>