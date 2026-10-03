<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Target Penjualan Bulanan</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        .page {
            max-width: 1500px;
            margin: 0 auto;
            padding: 28px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 22px;
        }

        .page-header h1 {
            margin: 0 0 6px;
            font-size: 28px;
        }

        .page-header p {
            margin: 0;
            color: #6b7280;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: 0 5px 18px rgba(15, 23, 42, 0.05);
        }

        .card h2 {
            margin: 0 0 16px;
            font-size: 18px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .field label {
            font-size: 13px;
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 11px;
            background: #fff;
            font-size: 14px;
        }

        textarea {
            min-height: 80px;
            resize: vertical;
        }

        .actions {
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }

        .btn {
            border: 0;
            border-radius: 8px;
            padding: 10px 14px;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
        }

        .btn-danger {
            background: #dc2626;
            color: #fff;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 13px;
            margin-bottom: 18px;
        }

        .summary-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 17px;
            box-shadow: 0 5px 18px rgba(15, 23, 42, 0.04);
        }

        .summary-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 7px;
        }

        .summary-value {
            font-size: 22px;
            font-weight: 800;
        }

        .summary-small {
            margin-top: 5px;
            font-size: 11px;
            color: #9ca3af;
        }

        .progress-wrap {
            width: 100%;
            height: 12px;
            background: #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
            margin-top: 7px;
        }

        .progress-bar {
            height: 100%;
            border-radius: 999px;
            background: #2563eb;
        }

        .progress-bar-success {
            background: #16a34a;
        }

        .progress-bar-warning {
            background: #f59e0b;
        }

        .progress-bar-danger {
            background: #dc2626;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1650px;
        }

        th,
        td {
            padding: 11px 9px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
            font-size: 12px;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
        }

        .status {
            display: inline-flex;
            border-radius: 999px;
            padding: 5px 9px;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-near {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-progress {
            background: #fef3c7;
            color: #92400e;
        }

        .status-low {
            background: #ffedd5;
            color: #9a3412;
        }

        .status-none {
            background: #fee2e2;
            color: #991b1b;
        }

        .metric {
            white-space: nowrap;
        }

        .muted {
            color: #6b7280;
        }

        .positive {
            color: #166534;
            font-weight: 700;
        }

        .warning {
            color: #b45309;
            font-weight: 700;
        }

        .edit-form {
            display: grid;
            grid-template-columns: 85px 120px 150px auto;
            gap: 7px;
            min-width: 460px;
        }

        .edit-form input {
            padding: 8px;
            font-size: 12px;
        }

        .product-progress {
            min-width: 145px;
        }

        .progress-numbers {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            font-size: 11px;
            margin-bottom: 4px;
        }

        .empty {
            text-align: center;
            padding: 28px;
            color: #6b7280;
        }

        .note {
            padding: 12px 14px;
            background: #eff6ff;
            border-radius: 9px;
            color: #1e40af;
            font-size: 12px;
            line-height: 1.55;
            margin-top: 14px;
        }

        @media (max-width: 1200px) {
            .summary-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {
            .page {
                padding: 15px;
            }

            .summary-grid,
            .grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                display: block;
            }

            .page-header .btn {
                margin-top: 12px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    @php
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    @endphp

    <header class="page-header">
        <div>
            <h1>Target Penjualan Bulanan</h1>
            <p>
                Target, realisasi, persentase pencapaian, omzet dan laba penjualan.
            </p>
        </div>

        <a
            href="{{ route('dashboard') }}"
            class="btn btn-secondary"
        >
            Dashboard
        </a>
    </header>

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

    @if ($errors->any())
        <div class="alert alert-error">
            <strong>Data belum dapat disimpan:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="card">
        <h2>Periode</h2>

        <form
            action="{{ route('sales-targets.index') }}"
            method="GET"
            class="grid"
        >
            <div class="field">
                <label>Bulan</label>

                <select name="month">
                    @foreach ($monthNames as $number => $name)
                        <option
                            value="{{ $number }}"
                            {{ (int) $month === (int) $number ? 'selected' : '' }}
                        >
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Tahun</label>

                <input
                    type="number"
                    name="year"
                    min="2020"
                    max="2100"
                    value="{{ $year }}"
                >
            </div>

            <div class="actions">
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Tampilkan
                </button>
            </div>
        </form>
    </section>

    <section class="summary-grid">

        <article class="summary-card">
            <div class="summary-label">
                Target Qty
            </div>

            <div class="summary-value">
                {{ number_format($totalTargetQty) }}
            </div>

            <div class="summary-small">
                Unit
            </div>
        </article>

        <article class="summary-card">
            <div class="summary-label">
                Terjual
            </div>

            <div class="summary-value">
                {{ number_format($totalSoldQty) }}
            </div>

            <div class="summary-small">
                Pencapaian {{ number_format($overallQtyProgress, 1) }}%
            </div>

            <div class="progress-wrap">
                <div
                    class="progress-bar {{ $overallQtyProgress >= 100 ? 'progress-bar-success' : '' }}"
                    style="width: {{ min(100, $overallQtyProgress) }}%"
                ></div>
            </div>
        </article>

        <article class="summary-card">
            <div class="summary-label">
                Belum Terjual
            </div>

            <div class="summary-value">
                {{ number_format($totalUnsoldQty) }}
            </div>

            <div class="summary-small">
                {{ number_format($overallUnsoldPercentage, 1) }}% dari target
            </div>
        </article>

        <article class="summary-card">
            <div class="summary-label">
                Target Omzet
            </div>

            <div class="summary-value">
                ${{ number_format($totalTargetRevenue, 2) }}
            </div>

            <div class="summary-small">
                Target periode
            </div>
        </article>

        <article class="summary-card">
            <div class="summary-label">
                Omzet Aktual
            </div>

            <div class="summary-value">
                ${{ number_format($totalActualRevenue, 2) }}
            </div>

            <div class="summary-small">
                {{ number_format($overallRevenueProgress, 1) }}% target omzet
            </div>
        </article>

        <article class="summary-card">
            <div class="summary-label">
                Laba Penjualan
            </div>

            <div class="summary-value">
                ${{ number_format($totalSalesProfit, 2) }}
            </div>

            <div class="summary-small">
                Margin {{ number_format($overallProfitMargin, 1) }}%
            </div>
        </article>

    </section>

    <section class="card">
        <h2>
            Tambah Target -
            {{ $monthNames[(int) $month] ?? $month }}
            {{ $year }}
        </h2>

        <form
            action="{{ route('sales-targets.store') }}"
            method="POST"
        >
            @csrf

            <input
                type="hidden"
                name="month"
                value="{{ $month }}"
            >

            <input
                type="hidden"
                name="year"
                value="{{ $year }}"
            >

            <div class="grid">

                <div class="field">
                    <label>Produk</label>

                    <select
                        name="product_id"
                        required
                    >
                        <option value="">
                            -- Pilih Produk --
                        </option>

                        @foreach ($products as $product)
                            <option
                                value="{{ $product->id }}"
                                {{ (string) old('product_id') === (string) $product->id ? 'selected' : '' }}
                            >
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Target Qty</label>

                    <input
                        type="number"
                        name="target_qty"
                        min="1"
                        value="{{ old('target_qty') }}"
                        required
                    >
                </div>

                <div class="field">
                    <label>Target Omzet ($)</label>

                    <input
                        type="number"
                        name="target_revenue"
                        min="0"
                        step="0.01"
                        value="{{ old('target_revenue') }}"
                        required
                    >
                </div>

                <div class="actions">
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        + Simpan Target
                    </button>
                </div>

            </div>

            <div
                class="field"
                style="margin-top: 14px;"
            >
                <label>Catatan</label>

                <textarea
                    name="notes"
                >{{ old('notes') }}</textarea>
            </div>
        </form>
    </section>

    <section class="card">

        <h2>
            Progress
            {{ $monthNames[(int) $month] ?? $month }}
            {{ $year }}
        </h2>

        <div class="table-wrap">

            <table>
                <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>

                    <th>Target Qty</th>
                    <th>Terjual</th>
                    <th>Belum Terjual</th>

                    <th>Pencapaian</th>
                    <th>Tidak Terjual</th>

                    <th>Target Omzet</th>
                    <th>Omzet Aktual</th>
                    <th>Progress Omzet</th>

                    <th>Laba Penjualan</th>
                    <th>Margin</th>

                    <th>Status</th>
                    <th>Edit</th>
                    <th>Hapus</th>
                </tr>
                </thead>

                <tbody>

                @forelse ($targets as $target)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            <strong>
                                {{ $target->product?->product_name ?? 'Produk tidak ditemukan' }}
                            </strong>

                            @if ($target->notes)
                                <div class="muted">
                                    {{ $target->notes }}
                                </div>
                            @endif
                        </td>

                        <td class="metric">
                            {{ number_format($target->target_qty) }}
                        </td>

                        <td class="metric positive">
                            {{ number_format($target->sold_qty) }}
                        </td>

                        <td class="metric">
                            {{ number_format($target->unsold_qty) }}

                            @if ($target->excess_qty > 0)
                                <div class="positive">
                                    +{{ number_format($target->excess_qty) }}
                                    di atas target
                                </div>
                            @endif
                        </td>

                        <td>

                            <div class="product-progress">

                                <div class="progress-numbers">
                                    <strong>
                                        {{ number_format($target->qty_progress, 1) }}%
                                    </strong>

                                    <span>
                                        Target 100%
                                    </span>
                                </div>

                                <div class="progress-wrap">
                                    <div
                                        class="progress-bar
                                        {{ $target->qty_progress >= 100
                                            ? 'progress-bar-success'
                                            : ($target->qty_progress < 50
                                                ? 'progress-bar-danger'
                                                : 'progress-bar-warning') }}"
                                        style="width: {{ min(100, $target->qty_progress) }}%"
                                    ></div>
                                </div>

                            </div>

                        </td>

                        <td class="metric">
                            {{ number_format($target->unsold_percentage, 1) }}%
                        </td>

                        <td class="metric">
                            ${{ number_format($target->target_revenue, 2) }}
                        </td>

                        <td class="metric positive">
                            ${{ number_format($target->actual_revenue, 2) }}
                        </td>

                        <td class="metric">
                            {{ number_format($target->revenue_progress, 1) }}%
                        </td>

                        <td class="metric positive">
                            ${{ number_format($target->sales_profit, 2) }}
                        </td>

                        <td class="metric">
                            {{ number_format($target->profit_margin, 1) }}%
                        </td>

                        <td>
                            <span
                                class="status {{ $target->target_status_class }}"
                            >
                                {{ $target->target_status }}
                            </span>
                        </td>

                        <td>

                            <form
                                action="{{ route('sales-targets.update', $target) }}"
                                method="POST"
                                class="edit-form"
                            >
                                @csrf
                                @method('PUT')

                                <input
                                    type="number"
                                    name="target_qty"
                                    min="1"
                                    value="{{ $target->target_qty }}"
                                    required
                                >

                                <input
                                    type="number"
                                    name="target_revenue"
                                    min="0"
                                    step="0.01"
                                    value="{{ $target->target_revenue }}"
                                    required
                                >

                                <input
                                    type="text"
                                    name="notes"
                                    value="{{ $target->notes }}"
                                    placeholder="Catatan"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-secondary"
                                >
                                    Simpan
                                </button>

                            </form>

                        </td>

                        <td>

                            <form
                                action="{{ route('sales-targets.destroy', $target) }}"
                                method="POST"
                                onsubmit="return confirm('Hapus target ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger"
                                >
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="15"
                            class="empty"
                        >
                            Belum ada target penjualan untuk periode ini.
                        </td>
                    </tr>

                @endforelse

                </tbody>
            </table>

        </div>

        <div class="note">
            <strong>Catatan:</strong>
            Terjual dihitung dari SUM(quantity), omzet aktual dari SUM(subtotal),
            dan laba penjualan dari SUM(total_profit) pada tabel stock_outs sesuai
            bulan yang dipilih. Persentase pencapaian dibatasi maksimum 100%.
            Jika penjualan melewati target, kelebihannya ditampilkan sebagai
            jumlah unit di atas target.
        </div>

    </section>

</div>

</body>
</html>
