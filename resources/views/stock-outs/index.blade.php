<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Stok Keluar - Dulmar Satellite Store</title>

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
            margin: 20px 0 0;
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

        /* =========================================================
           MAIN
        ========================================================= */

        .main-content {
            width: calc(100% - 245px);
            min-width: 0;
            min-height: 100vh;

            margin-left: 245px;
            padding: 45px 32px;

            overflow-x: hidden;
        }

        /* =========================================================
           HEADER
        ========================================================= */

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

        .button-add {
            display: inline-block;
            flex-shrink: 0;

            padding: 15px 21px;

            border-radius: 8px;

            background: #dc2626;
            color: white;

            font-size: 17px;
            text-decoration: none;
        }

        .button-add:hover {
            background: #b91c1c;
        }

        /* =========================================================
           ALERT
        ========================================================= */

        .alert-success,
        .alert-error {
            margin-bottom: 25px;

            padding: 15px 20px;

            border-radius: 7px;

            font-size: 15px;
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

        /* =========================================================
           FILTER
        ========================================================= */

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
            font-size: 20px;
        }

        .filter-form {
            display: grid;

            grid-template-columns:
                minmax(220px, 2fr)
                repeat(2, minmax(170px, 1fr))
                auto
                auto;

            align-items: end;

            gap: 12px;
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

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, 0.12);
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

        .button-filter:hover {
            background: #1d4ed8;
        }

        .button-reset {
            background: #6b7280;
        }

        .button-reset:hover {
            background: #4b5563;
        }

        .filter-info {
            margin: 15px 0 0;

            color: #6b7280;

            font-size: 14px;
        }

        /* =========================================================
           SUMMARY
        ========================================================= */

        .summary-grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(170px, 1fr));

            gap: 16px;

            margin-bottom: 25px;
        }

        .summary-grid-money {
            display: grid;

            grid-template-columns:
                repeat(5, minmax(170px, 1fr));

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

        .summary-transactions {
            border-left: 5px solid #2563eb;
        }

        .summary-quantity {
            border-left: 5px solid #dc2626;
        }

        .summary-sales {
            border-left: 5px solid #7c3aed;
        }

        .summary-profit {
            border-left: 5px solid #16a34a;
        }

        .summary-customer-paid {
            border-left: 5px solid #0d9488;
        }

        .summary-customer-balance {
            border-left: 5px solid #f59e0b;
        }

        .summary-received {
            border-left: 5px solid #2563eb;
        }

        .summary-deposited {
            border-left: 5px solid #16a34a;
        }

        .summary-not-deposited {
            border-left: 5px solid #dc2626;
        }

        .value-transactions {
            color: #2563eb;
        }

        .value-quantity {
            color: #dc2626;
        }

        .value-sales {
            color: #7c3aed;
        }

        .value-profit {
            color: #16a34a;
        }

        .value-green {
            color: #15803d;
        }

        .value-red {
            color: #dc2626;
        }

        .value-orange {
            color: #d97706;
        }

        .value-blue {
            color: #2563eb;
        }

        /* =========================================================
           TABLE
        ========================================================= */

        .table-card {
            width: 100%;

            overflow-x: auto;

            border-radius: 10px;

            background: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);

            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 2850px;

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

        .quantity {
            color: #dc2626;
            font-weight: bold;
        }

        .selling-price {
            color: #2563eb;
            font-weight: bold;
        }

        .subtotal {
            color: #7c3aed;
            font-weight: bold;
        }

        .discount-amount {
            color: #7c3aed;
            font-weight: bold;
        }

        .discount-note {
            display: block;
            margin-top: 4px;
            color: #6b7280;
            font-size: 11px;
            white-space: normal;
            max-width: 220px;
        }

        .profit {
            color: #16a34a;
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

        .amount-orange {
            color: #ea580c;
            font-weight: bold;
        }

        .deduction-note {
            display: block;
            margin-top: 4px;
            color: #6b7280;
            font-size: 11px;
            white-space: normal;
            max-width: 220px;
        }

        .seller {
            color: #1d4ed8;
            font-weight: bold;
        }

        /* =========================================================
           STATUS
        ========================================================= */

        .status {
            display: inline-block;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 12px;
            font-weight: bold;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-partial {
            background: #fef3c7;
            color: #92400e;
        }

        .status-unpaid {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-no-money {
            background: #e5e7eb;
            color: #4b5563;
        }

        .payment-completed {
            color: #166534;

            font-size: 12px;
            font-weight: bold;
        }

        .deposit-completed {
            color: #166534;

            font-size: 12px;
            font-weight: bold;
            line-height: 1.5;
        }

        .deposit-waiting {
            display: inline-block;

            padding: 7px 10px;

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

        .verified-admin {
            display: block;

            margin-top: 4px;

            color: #64748b;

            font-size: 11px;
        }

        /* =========================================================
           ACTION BUTTON
        ========================================================= */

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            align-items: center;

            gap: 7px;

            min-width: 330px;
        }

        .action-buttons form {
            margin: 0;
        }

        .button-payment,
        .button-deposit,
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

        .button-payment {
            background: #2563eb;
        }

        .button-payment:hover {
            background: #1d4ed8;
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

        .empty-data {
            padding: 35px;

            color: #6b7280;

            text-align: center;
        }


        /* =========================================================
           PAYMENT MODAL
        ========================================================= */

        .payment-modal {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.65);
        }

        .payment-modal.show {
            display: flex;
        }

        .payment-modal-card {
            width: 100%;
            max-width: 560px;
            overflow: hidden;
            border-radius: 12px;
            background: white;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }

        .payment-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 20px 22px;
            border-bottom: 1px solid #e5e7eb;
        }

        .payment-modal-header h3 {
            margin: 0;
            font-size: 21px;
        }

        .payment-modal-close {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 7px;
            background: #f3f4f6;
            color: #374151;
            font-size: 22px;
            cursor: pointer;
        }

        .payment-modal-body {
            padding: 22px;
        }

        .payment-customer-info {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 8px;
            background: #eff6ff;
            color: #1e3a8a;
            line-height: 1.6;
        }

        .payment-field {
            margin-bottom: 17px;
        }

        .payment-field label {
            display: block;
            margin-bottom: 7px;
            color: #374151;
            font-size: 14px;
            font-weight: bold;
        }

        .payment-field input {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 15px;
        }

        .payment-field input:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .payment-net-box {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .payment-net-item {
            padding: 13px;
            border-radius: 8px;
            background: #f8fafc;
        }

        .payment-net-item span {
            display: block;
            margin-bottom: 5px;
            color: #64748b;
            font-size: 12px;
        }

        .payment-net-item strong {
            font-size: 18px;
        }

        .payment-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 22px;
        }

        .payment-modal-actions button {
            padding: 11px 18px;
            border: none;
            border-radius: 7px;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .payment-cancel {
            background: #6b7280;
        }

        .payment-submit {
            background: #2563eb;
        }

        .payment-error {
            display: none;
            margin-bottom: 15px;
            padding: 11px 13px;
            border: 1px solid #fca5a5;
            border-radius: 7px;
            background: #fee2e2;
            color: #991b1b;
            font-size: 13px;
        }

        .payment-error.show {
            display: block;
        }

        @media (max-width: 700px) {
            .payment-net-box {
                grid-template-columns: 1fr;
            }

            .payment-modal-actions {
                flex-direction: column;
            }

            .payment-modal-actions button {
                width: 100%;
            }
        }

        /* =========================================================
           PAGINATION
        ========================================================= */

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 12px;

            margin-top: 18px;
            padding: 14px 16px;

            border-radius: 8px;

            background: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);
        }

        .pagination a,
        .pagination .disabled {
            padding: 9px 15px;

            border-radius: 6px;

            text-decoration: none;
        }

        .pagination a {
            background: #2563eb;
            color: white;
        }

        .pagination a:hover {
            background: #1d4ed8;
        }

        .pagination .disabled {
            background: #e5e7eb;
            color: #9ca3af;
        }

        .pagination-info {
            color: #4b5563;

            font-size: 14px;

            text-align: center;
        }

        /* =========================================================
           CHART
        ========================================================= */

        .chart-card {
            margin: 35px 0;

            padding: 30px;

            border-radius: 10px;

            background: white;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.08);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;

            gap: 20px;

            margin-bottom: 25px;
        }

        .chart-header h3 {
            margin: 0 0 8px;

            font-size: 24px;
        }

        .chart-header p {
            margin: 0;

            color: #6b7280;

            font-size: 15px;
        }

        .chart-total {
            flex-shrink: 0;

            padding: 12px 18px;

            border-radius: 8px;

            background: #fee2e2;
            color: #991b1b;

            text-align: center;
        }

        .chart-total span {
            display: block;

            margin-bottom: 4px;

            font-size: 13px;
        }

        .chart-total strong {
            font-size: 24px;
        }

        .chart-container {
            position: relative;

            width: 100%;
            max-width: 370px;
            height: 300px;

            margin: 0 auto;
        }

        .chart-empty {
            padding: 50px 20px;

            color: #6b7280;

            text-align: center;
        }

        /* =========================================================
           MOBILE
        ========================================================= */

        .sidebar-toggle,
        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1250px) {

            .summary-grid,
            .summary-grid-money {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .filter-form {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 700px) {

            body {
                overflow-x: hidden;
            }

            .container {
                display: block;
                width: 100%;
            }

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

                background: #1f2b3a;
                color: white;

                font-size: 25px;

                cursor: pointer;

                box-shadow:
                    0 3px 10px
                    rgba(0, 0, 0, 0.25);
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

                transition:
                    opacity 0.25s,
                    visibility 0.25s;
            }

            .sidebar-overlay.overlay-open {
                visibility: visible;
                opacity: 1;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 1100;

                width: min(82vw, 285px);
                height: 100vh;

                padding:
                    82px
                    25px
                    30px;

                overflow-y: auto;

                transform:
                    translateX(-105%);

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
                margin-bottom: 35px;

                font-size: 25px;
            }

            .main-content {
                width: 100%;

                margin-left: 0;

                padding:
                    85px
                    14px
                    30px;

                overflow-x: hidden;
            }

            .page-header,
            .chart-header {
                flex-direction: column;
            }

            .page-header h2 {
                font-size: 30px;
            }

            .page-header p {
                font-size: 16px;
            }

            .button-add,
            .chart-total {
                width: 100%;

                text-align: center;
            }

            .filter-form,
            .summary-grid,
            .summary-grid-money {
                grid-template-columns: 1fr;
            }

            .button-filter,
            .button-reset {
                width: 100%;
            }

            .table-card {
                max-width: 100%;

                border-radius: 7px;
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination-info {
                width: 100%;
                order: -1;
            }

            .chart-card {
                padding: 20px 14px;
            }

            .chart-container {
                max-width: 280px;
                height: 260px;
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

            @can('cash-admin.view')
                <a
                    href="{{ route('cash-accounts.index') }}"
                    class="{{ request()->routeIs('cash-accounts.*') ? 'active' : '' }}"
                >
                    Kas Admin
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
                    Stok Keluar
                </h2>

                <p>
                    Kelola transaksi penjualan,
                    pembayaran customer dan setoran petugas.
                </p>

            </div>

            @can('stock-outs.create')

                <a
                    href="{{ route('stock-outs.create') }}"
                    class="button-add"
                >
                    + Tambah Stok Keluar
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
                Pencarian dan Filter Transaksi
            </h3>

            <form
                action="{{ route('stock-outs.index') }}"
                method="GET"
                class="filter-form"
            >

                <div class="form-group">

                    <label for="search">
                        Cari produk, pelanggan, petugas, atau catatan
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        value="{{ $search ?? '' }}"
                        placeholder="Masukkan kata pencarian"
                    >

                </div>

                <div class="form-group">

                    <label for="start_date">
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        class="form-control"
                        value="{{ $startDate ?? '' }}"
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
                        value="{{ $endDate ?? '' }}"
                    >

                </div>

                <button
                    type="submit"
                    class="button-filter"
                >
                    Terapkan
                </button>

                <a
                    href="{{ route('stock-outs.index') }}"
                    class="button-reset"
                >
                    Reset
                </a>

            </form>

            @if (
                ($search ?? '')
                || ($startDate ?? '')
                || ($endDate ?? '')
            )

                <p class="filter-info">
                    Menampilkan
                    {{ $stockOuts->total() }}
                    transaksi sesuai filter.
                </p>

            @endif

        </section>


        <section class="summary-grid">

            <article class="summary-card summary-transactions">

                <h3>
                    Jumlah Transaksi
                </h3>

                <strong class="value-transactions">
                    {{ $totalTransactions }}
                </strong>

            </article>

            <article class="summary-card summary-quantity">

                <h3>
                    Total Barang Keluar
                </h3>

                <strong class="value-quantity">
                    {{ $totalStockOut }} unit
                </strong>

            </article>

            <article class="summary-card summary-sales">

                <h3>
                    Total Penjualan
                </h3>

                <strong class="value-sales">
                    ${{ number_format($totalSales, 2) }}
                </strong>

            </article>

            <article class="summary-card summary-profit">

                <h3>
                    Total Keuntungan
                </h3>

                <strong class="value-profit">
                    ${{ number_format($totalProfit, 2) }}
                </strong>

            </article>

        </section>


        <section class="summary-grid-money">

            <article class="summary-card summary-customer-paid">

                <h3>
                    Customer Sudah Bayar
                </h3>

                <strong class="value-green">
                    ${{ number_format($totalCustomerPaid ?? 0, 2) }}
                </strong>

            </article>

            <article class="summary-card summary-customer-balance">

                <h3>
                    Sisa Tagihan Customer
                </h3>

                <strong class="value-orange">
                    ${{ number_format($totalCustomerBalance ?? 0, 2) }}
                </strong>

            </article>

            <article class="summary-card summary-received">

                <h3>
                    Uang Diterima Petugas
                </h3>

                <strong class="value-blue">
                    ${{ number_format($totalStaffReceived ?? 0, 2) }}
                </strong>

            </article>

            <article class="summary-card summary-deposited">

                <h3>
                    Sudah Disetor
                </h3>

                <strong class="value-green">
                    ${{ number_format($totalDeposited ?? 0, 2) }}
                </strong>

            </article>

            <article class="summary-card summary-not-deposited">

                <h3>
                    Belum Disetor
                </h3>

                <strong class="value-red">
                    ${{ number_format($totalNotDeposited ?? 0, 2) }}
                </strong>

            </article>

        </section>


        <div class="table-card">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Pelanggan</th>
                        <th>Petugas Jual</th>
                        <th>Jumlah</th>
                        <th>Harga Normal/Unit</th>
                        <th>Total Harga Normal</th>
                        <th>Diskon Pelanggan</th>
                        <th>Total Setelah Diskon</th>
                        <th>Keuntungan</th>

                        <th>Customer Bayar</th>
                        <th>Sisa Customer</th>
                        <th>Status Customer</th>

                        <th>Petugas Terima</th>
                        <th>Potongan Petugas</th>
                        <th>Setoran Bersih</th>
                        <th>Sudah Setor</th>
                        <th>Belum Setor</th>
                        <th>Status Setoran</th>

                        <th>Dikonfirmasi Oleh</th>
                        <th>Waktu Setoran</th>

                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($stockOuts as $stockOut)

                        @php
                            $normalSubtotal =
                                (float) $stockOut->unit_selling_price
                                * (int) $stockOut->quantity;

                            $customerDiscountAmount =
                                (float) (
                                    $stockOut->customer_discount_amount
                                    ?? 0
                                );

                            $subtotal =
                                (float) $stockOut->subtotal;

                            $customerPaid =
                                (float) $stockOut->customer_paid_amount;

                            $customerBalance =
                                (float) $stockOut->customer_balance;

                            $staffReceived =
                                (float) $stockOut->staff_received_amount;

                            $staffDeposited =
                                (float) $stockOut->staff_deposited_amount;

                            $staffBalance =
                                (float) $stockOut->staff_balance;

                            $deductionAmount =
                                (float) (
                                    $stockOut->deduction_amount
                                    ?? 0
                                );

                            $netDeposit =
                                max(
                                    $staffReceived
                                    - $deductionAmount,
                                    0
                                );

                            $sellerName =
                                $stockOut->sold_by
                                ?: '-';

                            $customerName =
                                $stockOut->customer?->customer_name
                                ?? '-';
                        @endphp

                        <tr>

                            <td>
                                {{
                                    $stockOuts->firstItem()
                                    + $loop->index
                                }}
                            </td>

                            <td>
                                {{
                                    \Carbon\Carbon::parse(
                                        $stockOut->transaction_date
                                    )->format('d-m-Y')
                                }}
                            </td>

                            <td>
                                {{
                                    $stockOut
                                        ->product
                                        ?->product_name
                                    ?? 'Produk telah dihapus'
                                }}
                            </td>

                            <td>
                                {{ $customerName }}
                            </td>

                            <td class="seller">
                                {{ $sellerName }}
                            </td>

                            <td class="quantity">
                                -{{ $stockOut->quantity }} unit
                            </td>

                            <td class="selling-price">
                                ${{ number_format(
                                    $stockOut->unit_selling_price,
                                    2
                                ) }}
                            </td>

                            <td class="selling-price">
                                ${{ number_format(
                                    $normalSubtotal,
                                    2
                                ) }}
                            </td>

                            <td class="discount-amount">

                                ${{ number_format(
                                    $customerDiscountAmount,
                                    2
                                ) }}

                                @if ($stockOut->customer_discount_note)

                                    <span class="discount-note">
                                        {{ $stockOut->customer_discount_note }}
                                    </span>

                                @endif

                            </td>

                            <td class="subtotal">
                                ${{ number_format(
                                    $subtotal,
                                    2
                                ) }}
                            </td>

                            <td class="profit">
                                ${{ number_format(
                                    $stockOut->total_profit,
                                    2
                                ) }}
                            </td>


                            {{-- CUSTOMER BAYAR --}}
                            <td class="amount-green">
                                ${{ number_format(
                                    $customerPaid,
                                    2
                                ) }}
                            </td>


                            {{-- SISA CUSTOMER --}}
                            <td class="amount-red">
                                ${{ number_format(
                                    $customerBalance,
                                    2
                                ) }}
                            </td>


                            {{-- STATUS CUSTOMER --}}
                            <td>

                                @if (
                                    $stockOut->customer_payment_status
                                    === 'paid'
                                )

                                    <span class="status status-paid">
                                        Lunas
                                    </span>

                                @elseif (
                                    $stockOut->customer_payment_status
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


                            {{-- PETUGAS TERIMA --}}
                            <td class="amount-green">
                                ${{ number_format(
                                    $staffReceived,
                                    2
                                ) }}
                            </td>


                            {{-- BIAYA / POTONGAN --}}
                            <td class="amount-orange">

                                ${{ number_format(
                                    $deductionAmount,
                                    2
                                ) }}

                                @if ($stockOut->deduction_note)

                                    <span class="deduction-note">
                                        {{ $stockOut->deduction_note }}
                                    </span>

                                @endif

                            </td>


                            {{-- SETORAN BERSIH --}}
                            <td class="amount-green">
                                ${{ number_format(
                                    $netDeposit,
                                    2
                                ) }}
                            </td>


                            {{-- SUDAH SETOR --}}
                            <td class="amount-green">
                                ${{ number_format(
                                    $staffDeposited,
                                    2
                                ) }}
                            </td>


                            {{-- BELUM SETOR --}}
                            <td class="amount-red">
                                ${{ number_format(
                                    $staffBalance,
                                    2
                                ) }}
                            </td>


                            {{-- STATUS SETORAN --}}
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


                            {{-- ADMIN VERIFIKASI --}}
                            <td>

                                {{
                                    $stockOut->deposit_verified_by
                                    ?: '-'
                                }}

                            </td>


                            {{-- WAKTU SETOR --}}
                            <td>

                                @if ($stockOut->staff_deposited_at)

                                    {{
                                        \Carbon\Carbon::parse(
                                            $stockOut->staff_deposited_at
                                        )->format('d-m-Y H:i')
                                    }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>
                                {{ $stockOut->notes ?: '-' }}
                            </td>


                            <td>

                                <div class="action-buttons">

                                    {{-- ====================================
                                         VERIFIKASI PEMBAYARAN CUSTOMER
                                    ===================================== --}}

                                    @can('stock-outs.verify-payment')

                                        @if ($customerBalance > 0)

                                            <form
                                                action="{{ route(
                                                    'stock-outs.verify-payment',
                                                    $stockOut
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
                                                    name="deduction_amount"
                                                    value=""
                                                >

                                                <input
                                                    type="hidden"
                                                    name="deduction_note"
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
                                            $stockOut->customer_payment_status
                                            === 'paid'
                                        )

                                            <span class="payment-completed">
                                                ✓ Customer Lunas
                                            </span>

                                        @endif

                                    @else

                                        @if (
                                            $stockOut->customer_payment_status
                                            === 'paid'
                                        )

                                            <span class="payment-completed">
                                                ✓ Customer Lunas
                                            </span>

                                        @endif

                                    @endcan


                                    {{-- ====================================
                                         KONFIRMASI SETORAN ADMIN
                                    ===================================== --}}

                                    @can('stock-outs.confirm-deposit')

                                        @if (
                                            $staffReceived > 0
                                            && $staffBalance > 0
                                        )

                                            <form
                                                action="{{ route(
                                                    'stock-outs.confirm-deposit',
                                                    $stockOut
                                                ) }}"
                                                method="POST"
                                                class="deposit-form"
                                                data-staff="{{ $sellerName }}"
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

                                                @if ($stockOut->staff_deposited_at)

                                                    <br>

                                                    {{
                                                        \Carbon\Carbon::parse(
                                                            $stockOut->staff_deposited_at
                                                        )->format('d-m-Y H:i')
                                                    }}

                                                @endif

                                                @if ($stockOut->deposit_verified_by)

                                                    <span class="verified-admin">
                                                        Oleh:
                                                        {{ $stockOut->deposit_verified_by }}
                                                    </span>

                                                @endif

                                            </div>

                                        @else

                                            <div class="deposit-no-money">
                                                Belum Ada Uang
                                            </div>

                                        @endif

                                    @else

                                        @if (
                                            $staffReceived > 0
                                            && $staffBalance > 0
                                        )

                                            <div class="deposit-waiting">
                                                ⏳ Menunggu Konfirmasi Admin
                                            </div>

                                        @elseif (
                                            $staffReceived > 0
                                            && $staffBalance <= 0
                                        )

                                            <div class="deposit-completed">

                                                ✓ Sudah Setor

                                                @if ($stockOut->staff_deposited_at)

                                                    <br>

                                                    {{
                                                        \Carbon\Carbon::parse(
                                                            $stockOut->staff_deposited_at
                                                        )->format('d-m-Y H:i')
                                                    }}

                                                @endif

                                            </div>

                                        @else

                                            <div class="deposit-no-money">
                                                Belum Ada Uang
                                            </div>

                                        @endif

                                    @endcan


                                    {{-- ====================================
                                         EDIT
                                    ===================================== --}}

                                    @can('stock-outs.edit')

                                        <a
                                            href="{{ route(
                                                'stock-outs.edit',
                                                $stockOut
                                            ) }}"
                                            class="button-edit"
                                        >
                                            Edit
                                        </a>

                                    @endcan


                                    {{-- ====================================
                                         HAPUS
                                    ===================================== --}}

                                    @can('stock-outs.delete')

                                        @if (
                                            $customerPaid <= 0
                                            && $staffDeposited <= 0
                                        )

                                            <form
                                                action="{{ route(
                                                    'stock-outs.destroy',
                                                    $stockOut
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok barang akan dikembalikan.')"
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
                                colspan="24"
                                class="empty-data"
                            >
                                Belum ada transaksi stok keluar.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if ($stockOuts->hasPages())

            <nav
                class="pagination"
                aria-label="Navigasi halaman transaksi"
            >

                @if ($stockOuts->onFirstPage())

                    <span class="disabled">
                        Sebelumnya
                    </span>

                @else

                    <a href="{{ $stockOuts->previousPageUrl() }}">
                        Sebelumnya
                    </a>

                @endif

                <span class="pagination-info">

                    Halaman
                    {{ $stockOuts->currentPage() }}
                    dari
                    {{ $stockOuts->lastPage() }}

                </span>

                @if ($stockOuts->hasMorePages())

                    <a href="{{ $stockOuts->nextPageUrl() }}">
                        Berikutnya
                    </a>

                @else

                    <span class="disabled">
                        Berikutnya
                    </span>

                @endif

            </nav>

        @endif


        <section class="chart-card">

            <div class="chart-header">

                <div>

                    <h3>
                        Grafik Barang Keluar
                    </h3>

                    <p>
                        Perbandingan jumlah barang yang terjual berdasarkan produk.
                    </p>

                </div>

                <div class="chart-total">

                    <span>
                        Total Barang Keluar
                    </span>

                    <strong>
                        {{ $totalStockOut }} unit
                    </strong>

                </div>

            </div>

            @if ($chartValues->sum() > 0)

                <div class="chart-container">

                    <canvas
                        id="stockOutPieChart"
                        role="img"
                        aria-label="Grafik barang keluar berdasarkan produk"
                    ></canvas>

                </div>

            @else

                <div class="chart-empty">
                    Belum ada data barang keluar untuk ditampilkan.
                </div>

            @endif

        </section>

    </main>

</div>



<div
    id="paymentModal"
    class="payment-modal"
    aria-hidden="true"
>
    <div class="payment-modal-card">

        <div class="payment-modal-header">

            <h3>
                Verifikasi Pembayaran Customer
            </h3>

            <button
                type="button"
                id="paymentModalClose"
                class="payment-modal-close"
                aria-label="Tutup"
            >
                ×
            </button>

        </div>

        <div class="payment-modal-body">

            <div
                id="paymentModalError"
                class="payment-error"
            ></div>

            <div class="payment-customer-info">

                <div>
                    <strong>Customer:</strong>
                    <span id="paymentCustomerName">-</span>
                </div>

                <div>
                    <strong>Sisa Tagihan:</strong>
                    $<span id="paymentRemaining">0.00</span>
                </div>

            </div>

            <div class="payment-field">

                <label for="modalPaymentAmount">
                    Jumlah Dibayar Customer
                </label>

                <input
                    type="number"
                    id="modalPaymentAmount"
                    min="0.01"
                    step="0.01"
                    placeholder="Contoh: 175.00"
                >

            </div>

            <div class="payment-field">

                <label for="modalDeductionAmount">
                    Biaya / Potongan Petugas
                </label>

                <input
                    type="number"
                    id="modalDeductionAmount"
                    min="0"
                    step="0.01"
                    value="0"
                    placeholder="Contoh: 25.00"
                >

            </div>

            <div class="payment-field">

                <label for="modalDeductionNote">
                    Keterangan Potongan Petugas
                </label>

                <input
                    type="text"
                    id="modalDeductionNote"
                    maxlength="255"
                    placeholder="Contoh: Bensin / Komisi petugas"
                >

            </div>

            <div class="payment-net-box">

                <div class="payment-net-item">
                    <span>Customer Bayar</span>
                    <strong>
                        $<span id="modalPaymentPreview">0.00</span>
                    </strong>
                </div>

                <div class="payment-net-item">
                    <span>Potongan Petugas</span>
                    <strong style="color:#ea580c;">
                        $<span id="modalDeductionPreview">0.00</span>
                    </strong>
                </div>

                <div class="payment-net-item">
                    <span>Setoran Bersih</span>
                    <strong style="color:#15803d;">
                        $<span id="modalNetPreview">0.00</span>
                    </strong>
                </div>

            </div>

            <div class="payment-modal-actions">

                <button
                    type="button"
                    id="paymentModalCancel"
                    class="payment-cancel"
                >
                    Batal
                </button>

                <button
                    type="button"
                    id="paymentModalSubmit"
                    class="payment-submit"
                >
                    Simpan Verifikasi
                </button>

            </div>

        </div>

    </div>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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


    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI PEMBAYARAN CUSTOMER - MODAL
    |--------------------------------------------------------------------------
    */

    const paymentModal =
        document.getElementById(
            'paymentModal'
        );

    const paymentModalClose =
        document.getElementById(
            'paymentModalClose'
        );

    const paymentModalCancel =
        document.getElementById(
            'paymentModalCancel'
        );

    const paymentModalSubmit =
        document.getElementById(
            'paymentModalSubmit'
        );

    const paymentModalError =
        document.getElementById(
            'paymentModalError'
        );

    const paymentCustomerName =
        document.getElementById(
            'paymentCustomerName'
        );

    const paymentRemaining =
        document.getElementById(
            'paymentRemaining'
        );

    const modalPaymentAmount =
        document.getElementById(
            'modalPaymentAmount'
        );

    const modalDeductionAmount =
        document.getElementById(
            'modalDeductionAmount'
        );

    const modalDeductionNote =
        document.getElementById(
            'modalDeductionNote'
        );

    const modalPaymentPreview =
        document.getElementById(
            'modalPaymentPreview'
        );

    const modalDeductionPreview =
        document.getElementById(
            'modalDeductionPreview'
        );

    const modalNetPreview =
        document.getElementById(
            'modalNetPreview'
        );


    let activePaymentForm =
        null;

    let activePaymentRemaining =
        0;


    function updatePaymentPreview() {

        const payment =
            Number(
                modalPaymentAmount.value
                || 0
            );

        const deduction =
            Number(
                modalDeductionAmount.value
                || 0
            );

        const net =
            Math.max(
                payment
                - deduction,
                0
            );

        modalPaymentPreview.textContent =
            payment.toFixed(2);

        modalDeductionPreview.textContent =
            deduction.toFixed(2);

        modalNetPreview.textContent =
            net.toFixed(2);
    }


    function showPaymentError(
        message
    ) {

        paymentModalError.textContent =
            message;

        paymentModalError.classList.add(
            'show'
        );
    }


    function clearPaymentError() {

        paymentModalError.textContent =
            '';

        paymentModalError.classList.remove(
            'show'
        );
    }


    function openPaymentModal(
        form
    ) {

        activePaymentForm =
            form;

        activePaymentRemaining =
            Number(
                form.dataset.remaining
                || 0
            );

        const customer =
            form.dataset.customer
            || 'Customer';

        paymentCustomerName.textContent =
            customer;

        paymentRemaining.textContent =
            activePaymentRemaining.toFixed(2);

        modalPaymentAmount.value =
            activePaymentRemaining.toFixed(2);

        modalDeductionAmount.value =
            '0';

        modalDeductionNote.value =
            '';

        clearPaymentError();

        updatePaymentPreview();

        paymentModal.classList.add(
            'show'
        );

        paymentModal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'menu-open'
        );

        setTimeout(
            function () {
                modalPaymentAmount.focus();
                modalPaymentAmount.select();
            },
            50
        );
    }


    function closePaymentModal() {

        paymentModal.classList.remove(
            'show'
        );

        paymentModal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'menu-open'
        );

        activePaymentForm =
            null;

        clearPaymentError();
    }


    document
        .querySelectorAll(
            '.verify-payment-form'
        )
        .forEach(function (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    event.preventDefault();

                    openPaymentModal(
                        form
                    );
                }
            );

        });


    modalPaymentAmount.addEventListener(
        'input',
        updatePaymentPreview
    );

    modalDeductionAmount.addEventListener(
        'input',
        updatePaymentPreview
    );


    paymentModalClose.addEventListener(
        'click',
        closePaymentModal
    );

    paymentModalCancel.addEventListener(
        'click',
        closePaymentModal
    );


    paymentModal.addEventListener(
        'click',
        function (event) {

            if (
                event.target
                === paymentModal
            ) {
                closePaymentModal();
            }
        }
    );


    paymentModalSubmit.addEventListener(
        'click',
        function () {

            if (
                !activePaymentForm
            ) {
                return;
            }


            clearPaymentError();


            const payment =
                Number(
                    modalPaymentAmount.value
                    || 0
                );


            const deduction =
                Number(
                    modalDeductionAmount.value
                    || 0
                );


            const deductionNote =
                modalDeductionNote.value
                    .trim();


            if (
                !Number.isFinite(payment)
                || payment <= 0
            ) {

                showPaymentError(
                    'Jumlah pembayaran customer wajib diisi dan harus lebih dari 0.'
                );

                return;
            }


            if (
                payment
                > activePaymentRemaining
            ) {

                showPaymentError(
                    'Jumlah pembayaran tidak boleh melebihi sisa tagihan $'
                    + activePaymentRemaining.toFixed(2)
                    + '.'
                );

                return;
            }


            if (
                !Number.isFinite(deduction)
                || deduction < 0
            ) {

                showPaymentError(
                    'Biaya/potongan petugas tidak valid.'
                );

                return;
            }


            if (
                deduction > payment
            ) {

                showPaymentError(
                    'Biaya/potongan petugas tidak boleh melebihi pembayaran customer.'
                );

                return;
            }


            if (
                deduction > 0
                && deductionNote === ''
            ) {

                showPaymentError(
                    'Keterangan potongan petugas wajib diisi jika ada biaya/potongan.'
                );

                return;
            }


            const paymentInput =
                activePaymentForm
                    .querySelector(
                        'input[name="payment_amount"]'
                    );

            const deductionInput =
                activePaymentForm
                    .querySelector(
                        'input[name="deduction_amount"]'
                    );

            const deductionNoteInput =
                activePaymentForm
                    .querySelector(
                        'input[name="deduction_note"]'
                    );


            paymentInput.value =
                payment.toFixed(2);

            deductionInput.value =
                deduction.toFixed(2);

            deductionNoteInput.value =
                deductionNote;


            const formToSubmit =
                activePaymentForm;


            paymentModal.classList.remove(
                'show'
            );

            paymentModal.setAttribute(
                'aria-hidden',
                'true'
            );

            document.body.classList.remove(
                'menu-open'
            );


            formToSubmit.submit();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI SETORAN ADMIN
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

                    const confirmed =
                        confirm(
                            'Konfirmasi bahwa '
                            + staff
                            + ' sudah menyerahkan setoran bersih sebesar $'
                            + balance.toFixed(2)
                            + ' kepada Admin?'
                        );

                    if (
                        !confirmed
                    ) {
                        return;
                    }

                    form.submit();
                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | CHART
    |--------------------------------------------------------------------------
    */

    @if ($chartValues->sum() > 0)

        const chartLabels =
            {{ Illuminate\Support\Js::from($chartLabels) }};

        const chartValues =
            {{ Illuminate\Support\Js::from($chartValues) }};

        const chartColors = [
            '#2563eb',
            '#dc2626',
            '#16a34a',
            '#f59e0b',
            '#7c3aed',
            '#0891b2',
            '#db2777',
            '#65a30d',
            '#ea580c',
            '#4f46e5'
        ];


        new Chart(
            document.getElementById(
                'stockOutPieChart'
            ),
            {
                type: 'doughnut',

                data: {

                    labels:
                        chartLabels,

                    datasets: [
                        {
                            label:
                                'Jumlah barang keluar',

                            data:
                                chartValues,

                            backgroundColor:
                                chartLabels.map(
                                    function (_, index) {

                                        return chartColors[
                                            index
                                            % chartColors.length
                                        ];
                                    }
                                ),

                            borderColor:
                                '#ffffff',

                            borderWidth:
                                3,

                            hoverOffset:
                                12
                        }
                    ]
                },

                options: {

                    responsive:
                        true,

                    maintainAspectRatio:
                        false,

                    cutout:
                        '55%',

                    plugins: {

                        legend: {

                            position:
                                'bottom',

                            labels: {

                                padding:
                                    18,

                                font: {
                                    size:
                                        14
                                }
                            }
                        },

                        tooltip: {

                            callbacks: {

                                label:
                                    function (
                                        context
                                    ) {

                                        const total =
                                            context
                                                .dataset
                                                .data
                                                .reduce(
                                                    function (
                                                        result,
                                                        value
                                                    ) {

                                                        return result
                                                            + Number(
                                                                value
                                                            );
                                                    },
                                                    0
                                                );

                                        const value =
                                            Number(
                                                context.raw
                                            );

                                        const percentage =
                                            total > 0
                                                ? (
                                                    (
                                                        value
                                                        / total
                                                    )
                                                    * 100
                                                ).toFixed(1)
                                                : 0;

                                        return context.label
                                            + ': '
                                            + value
                                            + ' unit ('
                                            + percentage
                                            + '%)';
                                    }
                            }
                        }
                    }
                }
            }
        );

    @endif
</script>

</body>
</html>