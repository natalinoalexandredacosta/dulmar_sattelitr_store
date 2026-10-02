<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Histori Pergerakan Uang</title>

    <style>
        @page {
            margin: 18px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 10px;
            color: #555;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: center;
        }

        .summary-title {
            font-size: 9px;
            color: #666;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }

        .history-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        table.history {
            width: 100%;
            border-collapse: collapse;
        }

        table.history th {
            background: #1f2937;
            color: #fff;
            border: 1px solid #9ca3af;
            padding: 6px 4px;
            font-size: 8px;
        }

        table.history td {
            border: 1px solid #d1d5db;
            padding: 5px 4px;
            font-size: 8px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 15px;
            font-size: 8px;
            color: #666;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Histori Pergerakan Uang</h1>
        <p>Dulmar Satellite Store</p>
        <p>
            Dicetak:
            {{ now('Asia/Dili')->format('d-m-Y H:i:s') }}
        </p>
    </div>

    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-title">Uang di Admin</div>
                <div class="summary-value">
                    ${{ number_format((float) $adminAccount->balance, 2) }}
                </div>
            </td>

            <td>
                <div class="summary-title">Uang di Bank</div>
                <div class="summary-value">
                    ${{ number_format((float) $bankAccount->balance, 2) }}
                </div>
            </td>

            <td>
                <div class="summary-title">Saldo Mosan</div>
                <div class="summary-value">
                    ${{ number_format((float) $mosanAccount->balance, 2) }}
                </div>
            </td>

            <td>
                <div class="summary-title">Total Uang</div>
                <div class="summary-value">
                    ${{ number_format((float) $totalMoney, 2) }}
                </div>
            </td>
        </tr>
    </table>

    <div class="history-title">
        Histori Pergerakan Uang
    </div>

    <table class="history">
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
                @php
                    $accountLabel = function ($account) {
                        return match ($account) {
                            'admin' => 'Admin',
                            'bank' => 'Bank',
                            'mosan' => 'Mosan',
                            default => '-',
                        };
                    };
                @endphp

                <tr>
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $movement->created_at?->format('d-m-Y H:i') ?? '-' }}
                    </td>

                    <td>
                        {{ $movement->movement_label ?? '-' }}
                    </td>

                    <td class="text-right">
                        ${{ number_format((float) $movement->amount, 2) }}
                    </td>

                    <td>
                        {{ $accountLabel($movement->from_account) }}
                    </td>

                    <td>
                        {{ $accountLabel($movement->to_account) }}
                    </td>

                    <td>
                        {{ $movement->bank_name ?: '-' }}
                    </td>

                    <td>
                        {{ $movement->proof ? 'Ada' : '-' }}
                    </td>

                    <td>
                        {{ $movement->notes ?: '-' }}
                    </td>

                    <td>
                        {{ $movement->creator?->name ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">
                        Belum ada histori pergerakan uang.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dulmar Satellite Store
    </div>

</body>
</html>