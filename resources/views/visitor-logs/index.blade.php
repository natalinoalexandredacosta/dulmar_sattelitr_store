<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Visitor Log - Dulmar Satellite Store</title>

    <link
        rel="icon"
        type="image/jpeg"
        href="{{ asset('images/logo-dulmar.jpg') }}"
    >

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

        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 35px 25px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 32px;
        }

        .header p {
            margin: 0;
            color: #6b7280;
        }

        .back-button {
            padding: 11px 16px;
            border-radius: 7px;
            background: #1f2937;
            color: white;
            text-decoration: none;
        }

        .back-button:hover {
            background: #111827;
        }

        .summary-grid {
            display: grid;
            grid-template-columns:
                repeat(3, minmax(180px, 1fr));
            gap: 15px;
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

        .summary-card span {
            display: block;
            margin-bottom: 8px;
            color: #6b7280;
            font-size: 14px;
        }

        .summary-card strong {
            font-size: 26px;
        }

        .summary-total {
            border-left: 5px solid #2563eb;
        }

        .summary-today {
            border-left: 5px solid #16a34a;
        }

        .summary-unique {
            border-left: 5px solid #7c3aed;
        }

        .filter-card {
            margin-bottom: 20px;
            padding: 18px;
            border-radius: 10px;
            background: white;
            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);
        }

        .filter-form {
            display: flex;
            gap: 10px;
        }

        .filter-form input {
            flex: 1;
            padding: 11px 13px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .filter-form input:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, 0.12);
        }

        .filter-form button,
        .filter-form a {
            padding: 11px 16px;
            border: none;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .filter-form button {
            background: #2563eb;
        }

        .filter-form button:hover {
            background: #1d4ed8;
        }

        .filter-form a {
            background: #6b7280;
        }

        .filter-form a:hover {
            background: #4b5563;
        }

        .table-card {
            overflow-x: auto;
            border-radius: 10px;
            background: white;
            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);
        }

        table {
            width: 100%;
            min-width: 1450px;
            border-collapse: collapse;
        }

        thead {
            background: #e5e7eb;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            text-align: left;
            vertical-align: top;
        }

        th {
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 15px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: bold;
        }

        .method-badge {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 15px;
            background: #ecfdf5;
            color: #047857;
            font-size: 12px;
            font-weight: bold;
        }

        .url {
            max-width: 320px;
            white-space: normal;
            word-break: break-all;
        }

        .user-agent {
            max-width: 350px;
            white-space: normal;
            word-break: break-word;
            color: #6b7280;
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

        .empty-data {
            padding: 35px;
            color: #6b7280;
            text-align: center;
        }

        @media (max-width: 700px) {

            .container {
                padding: 20px 12px;
            }

            .header {
                flex-direction: column;
            }

            .back-button {
                width: 100%;
                text-align: center;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .filter-form {
                flex-direction: column;
            }

            .filter-form button,
            .filter-form a {
                width: 100%;
                text-align: center;
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination-info {
                width: 100%;
                order: -1;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <div>

            <h1>
                Visitor Log
            </h1>

            <p>
                Riwayat akses pengunjung website Dulmar Satellite Store.
            </p>

        </div>

        <a
            href="{{ route('dashboard') }}"
            class="back-button"
        >
            Kembali ke Dashboard
        </a>

    </div>


    <section class="summary-grid">

        <div class="summary-card summary-total">

            <span>
                Total Request Tercatat
            </span>

            <strong>
                {{ number_format($totalVisitors ?? 0) }}
            </strong>

        </div>


        <div class="summary-card summary-today">

            <span>
                Request Hari Ini
            </span>

            <strong>
                {{ number_format($todayVisitors ?? 0) }}
            </strong>

        </div>


        <div class="summary-card summary-unique">

            <span>
                IP Unik Hari Ini
            </span>

            <strong>
                {{ number_format($uniqueIpToday ?? 0) }}
            </strong>

        </div>

    </section>


    <section class="filter-card">

        <form
            action="{{ route('visitor-logs.index') }}"
            method="GET"
            class="filter-form"
        >

            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Cari IP, URL, browser, device atau platform"
            >

            <button type="submit">
                Cari
            </button>

            <a href="{{ route('visitor-logs.index') }}">
                Reset
            </a>

        </form>

    </section>


    <div class="table-card">

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>IP Address</th>
                    <th>User</th>
                    <th>Method</th>
                    <th>URL</th>
                    <th>Route</th>
                    <th>Browser</th>
                    <th>Device</th>
                    <th>Platform</th>
                    <th>Referer</th>
                    <th>User Agent</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($visitorLogs as $visitor)

                    <tr>

                        <td>
                            {{
                                $visitorLogs->firstItem()
                                + $loop->index
                            }}
                        </td>


                        <td>
                            {{
                                $visitor->visited_at
                                    ? $visitor->visited_at
                                        ->format(
                                            'd-m-Y H:i:s'
                                        )
                                    : '-'
                            }}
                        </td>


                        <td>
                            <span class="badge">
                                {{ $visitor->ip_address ?: '-' }}
                            </span>
                        </td>


                        <td>
                            {{
                                $visitor->user?->name
                                ?? 'Guest'
                            }}
                        </td>


                        <td>
                            <span class="method-badge">
                                {{ $visitor->method ?: '-' }}
                            </span>
                        </td>


                        <td class="url">
                            {{ $visitor->url ?: '-' }}
                        </td>


                        <td>
                            {{ $visitor->route_name ?: '-' }}
                        </td>


                        <td>
                            {{ $visitor->browser ?: '-' }}
                        </td>


                        <td>
                            {{ $visitor->device ?: '-' }}
                        </td>


                        <td>
                            {{ $visitor->platform ?: '-' }}
                        </td>


                        <td class="url">
                            {{ $visitor->referer ?: '-' }}
                        </td>


                        <td class="user-agent">
                            {{ $visitor->user_agent ?: '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="12"
                            class="empty-data"
                        >
                            Belum ada data visitor.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    @if ($visitorLogs->hasPages())

        <nav
            class="pagination"
            aria-label="Navigasi halaman visitor"
        >

            @if ($visitorLogs->onFirstPage())

                <span class="disabled">
                    Sebelumnya
                </span>

            @else

                <a href="{{ $visitorLogs->previousPageUrl() }}">
                    Sebelumnya
                </a>

            @endif


            <span class="pagination-info">

                Halaman
                {{ $visitorLogs->currentPage() }}
                dari
                {{ $visitorLogs->lastPage() }}

            </span>


            @if ($visitorLogs->hasMorePages())

                <a href="{{ $visitorLogs->nextPageUrl() }}">
                    Berikutnya
                </a>

            @else

                <span class="disabled">
                    Berikutnya
                </span>

            @endif

        </nav>

    @endif

</div>

</body>
</html>