<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Barang Dibawa Team - Dulmar Satellite Store</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        .page-wrapper {
            min-height: 100vh;
            padding: 30px;
        }

        .page-container {
            max-width: 1500px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 25px;
        }

        .page-title {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
            color: #111827;
        }

        .page-subtitle {
            margin-top: 7px;
            color: #64748b;
            font-size: 14px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 10px 16px;
            border: 0;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .btn-edit {
            background: #f59e0b;
            color: white;
        }

        .btn-edit:hover {
            background: #d97706;
        }

        .btn-delete {
            background: #dc2626;
            color: white;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 25px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
        }

        .summary-label {
            color: #64748b;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .summary-value {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: #111827;
        }

        .filter-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 20px;
        }

        .filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-form input {
            min-width: 260px;
            flex: 1;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        .filter-form input:focus {
            border-color: #2563eb;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
        }

        table {
            width: 100%;
            min-width: 1100px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            color: #334155;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        td {
            color: #374151;
        }

        .qty {
            text-align: center;
            font-weight: 700;
        }

        .remaining {
            color: #2563eb;
            font-weight: 800;
        }

        .remaining-zero {
            color: #16a34a;
        }

        .action-group {
            display: flex;
            gap: 7px;
            align-items: center;
        }

        .action-group .btn {
            min-height: 36px;
            padding: 8px 12px;
            font-size: 12px;
        }

        .empty-state {
            padding: 45px 20px;
            text-align: center;
            color: #64748b;
        }

        .pagination-wrapper {
            margin-top: 20px;
        }

        .back-wrapper {
            margin-top: 20px;
        }

        @media (max-width: 900px) {
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .page-wrapper {
                padding: 16px;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-header .btn {
                width: 100%;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-form input,
            .filter-form .btn {
                width: 100%;
                min-width: 0;
            }

            .action-group {
                flex-direction: column;
                align-items: stretch;
            }

            .action-group .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="page-wrapper">

    <div class="page-container">

        <div class="page-header">

            <div>
                <h1 class="page-title">
                    Barang Dibawa Team
                </h1>

                <div class="page-subtitle">
                    Catatan barang yang masih dibawa oleh masing-masing team.
                </div>
            </div>

            <a
                href="{{ route('team-product-carries.create') }}"
                class="btn btn-primary"
            >
                + Tambah Barang Dibawa
            </a>

        </div>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif


        <div class="summary-grid">

            <div class="summary-card">
                <div class="summary-label">
                    Total Qty Dibawa
                </div>

                <h2 class="summary-value">
                    {{ number_format($totalTaken ?? 0) }}
                </h2>
            </div>


            <div class="summary-card">
                <div class="summary-label">
                    Total Qty Jual
                </div>

                <h2 class="summary-value">
                    {{ number_format($totalSold ?? 0) }}
                </h2>
            </div>


            <div class="summary-card">
                <div class="summary-label">
                    Total Qty Kembali
                </div>

                <h2 class="summary-value">
                    {{ number_format($totalReturned ?? 0) }}
                </h2>
            </div>


            <div class="summary-card">
                <div class="summary-label">
                    Total Masih di Team
                </div>

                <h2 class="summary-value">
                    {{ number_format($totalRemaining ?? 0) }}
                </h2>
            </div>

        </div>


        <div class="filter-card">

            <form
                method="GET"
                action="{{ route('team-product-carries.index') }}"
                class="filter-form"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama team atau produk..."
                >

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Cari
                </button>

                @if (request('search'))
                    <a
                        href="{{ route('team-product-carries.index') }}"
                        class="btn btn-secondary"
                    >
                        Reset
                    </a>
                @endif

            </form>

        </div>


        <div class="table-card">

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Team</th>
                        <th>Produk</th>
                        <th>Qty Dibawa</th>
                        <th>Qty Jual</th>
                        <th>Qty Kembali</th>
                        <th>Sisa di Team</th>
                        <th>Tanggal Dibawa</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                @forelse ($carries as $carry)

                    <tr>

                        <td>
                            {{ $carries->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <strong>
                                {{ $carry->team_name }}
                            </strong>
                        </td>

                        <td>
                            {{ $carry->product?->product_name ?? '-' }}
                        </td>

                        <td class="qty">
                            {{ $carry->quantity_taken }}
                        </td>

                        <td class="qty">
                            {{ $carry->quantity_sold }}
                        </td>

                        <td class="qty">
                            {{ $carry->quantity_returned }}
                        </td>

                        <td class="qty">

                            @if ($carry->remaining_quantity > 0)

                                <span class="remaining">
                                    {{ $carry->remaining_quantity }}
                                </span>

                            @else

                                <span class="remaining remaining-zero">
                                    0
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $carry->taken_at?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td>
                            {{ $carry->notes ?: '-' }}
                        </td>

                        <td>

                            <div class="action-group">

                                <a
                                    href="{{ route('team-product-carries.edit', $carry) }}"
                                    class="btn btn-edit"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('team-product-carries.destroy', $carry) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-delete"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                Belum ada data barang yang dibawa team.
                            </div>
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if ($carries->hasPages())

            <div class="pagination-wrapper">
                {{ $carries->links() }}
            </div>

        @endif


        <div class="back-wrapper">

            <a
                href="{{ route('dashboard') }}"
                class="btn btn-secondary"
            >
                Kembali ke Dashboard
            </a>

        </div>

    </div>

</div>

</body>
</html>