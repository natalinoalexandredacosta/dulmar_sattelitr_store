<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Testimoni Pembeli - Dulmar Satellite Store</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Arial,
                sans-serif;
            background: #f5f6f8;
            color: #1f2937;
        }

        a {
            text-decoration: none;
        }

        button {
            font-family: inherit;
        }

        .page {
            width: min(1250px, calc(100% - 30px));
            margin: 0 auto;
            padding: 28px 0 50px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .title h1 {
            margin: 0;
            font-size: 27px;
            color: #111827;
        }

        .title p {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .top-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-dark {
            background: #111827;
            color: white;
        }

        .btn-red {
            background: #ef3340;
            color: white;
        }

        .btn-gray {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-green {
            background: #16a34a;
            color: white;
        }

        .btn-orange {
            background: #f59e0b;
            color: white;
        }

        .btn-outline-red {
            border: 1px solid #fecaca;
            background: white;
            color: #b91c1c;
        }

        .alert {
            margin-bottom: 15px;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
        }

        .alert-success {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #166534;
        }

        .alert-error {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 18px;
        }

        .filter {
            padding: 8px 13px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .filter-all {
            background: #e5e7eb;
            color: #374151;
        }

        .filter-all.active {
            background: #111827;
            color: white;
        }

        .filter-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .filter-pending.active {
            background: #f59e0b;
            color: white;
        }

        .filter-approved {
            background: #dcfce7;
            color: #166534;
        }

        .filter-approved.active {
            background: #16a34a;
            color: white;
        }

        .filter-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .filter-rejected.active {
            background: #dc2626;
            color: white;
        }

        .card {
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            box-shadow: 0 5px 18px rgba(0, 0, 0, .04);
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1100px;
            border-collapse: collapse;
            font-size: 12px;
        }

        thead {
            background: #f9fafb;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
            vertical-align: top;
        }

        th {
            color: #374151;
            font-size: 11px;
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #fafafa;
        }

        .customer-name {
            font-weight: 800;
            color: #111827;
        }

        .testimonial-text {
            max-width: 220px;
            margin-top: 4px;
            color: #6b7280;
            font-size: 11px;
            line-height: 1.45;
        }

        .stars {
            color: #f59e0b;
            font-size: 16px;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        .rating-text {
            margin-top: 2px;
            color: #6b7280;
            font-size: 10px;
        }

        .proof-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .proof-badge {
            width: max-content;
            display: inline-flex;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        .proof-chat {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .proof-video {
            background: #f5f3ff;
            color: #6d28d9;
        }

        .status {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            min-width: 250px;
        }

        .actions form {
            margin: 0;
        }

        .action-btn {
            min-height: 29px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 9px;
            border: none;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
        }

        .empty {
            padding: 45px;
            text-align: center;
            color: #6b7280;
        }

        .pagination {
            margin-top: 18px;
        }

        @media (max-width: 700px) {
            .page {
                width: calc(100% - 16px);
                padding-top: 18px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .top-actions {
                width: 100%;
            }

            .top-actions .btn {
                flex: 1;
            }

            .title h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="topbar">

        <div class="title">

            <h1>
                Testimoni Pembeli
            </h1>

            <p>
                Kelola bukti chat pemesanan, video pelanggan,
                rating dan testimoni pembeli.
            </p>

        </div>

        <div class="top-actions">

            <a
                href="{{ route('dashboard') }}"
                class="btn btn-gray"
            >
                ← Dashboard
            </a>

            <a
                href="{{ route('testimonials.create') }}"
                class="btn btn-red"
            >
                + Tambah Testimoni
            </a>

        </div>

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


    <div class="filters">

        <a
            href="{{ route('testimonials.index') }}"
            class="
                filter
                filter-all
                {{ empty($status) ? 'active' : '' }}
            "
        >
            Semua
        </a>


        <a
            href="{{
                route(
                    'testimonials.index',
                    ['status' => 'pending']
                )
            }}"
            class="
                filter
                filter-pending
                {{ $status === 'pending' ? 'active' : '' }}
            "
        >
            Pending
        </a>


        <a
            href="{{
                route(
                    'testimonials.index',
                    ['status' => 'approved']
                )
            }}"
            class="
                filter
                filter-approved
                {{ $status === 'approved' ? 'active' : '' }}
            "
        >
            Approved
        </a>


        <a
            href="{{
                route(
                    'testimonials.index',
                    ['status' => 'rejected']
                )
            }}"
            class="
                filter
                filter-rejected
                {{ $status === 'rejected' ? 'active' : '' }}
            "
        >
            Rejected
        </a>

    </div>


    <div class="card">

        <div class="table-wrap">

            <table>

                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Pembeli
                        </th>

                        <th>
                            Produk
                        </th>

                        <th>
                            Rating
                        </th>

                        <th>
                            Bukti
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Dibuat Oleh
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse (
                    $testimonials
                    as $testimonial
                )

                    @php

                        $chatCount =
                            $testimonial
                                ->proofs
                                ->where(
                                    'proof_type',
                                    \App\Models\TestimonialProof::TYPE_CHAT
                                )
                                ->count();


                        $videoCount =
                            $testimonial
                                ->proofs
                                ->where(
                                    'proof_type',
                                    \App\Models\TestimonialProof::TYPE_VIDEO
                                )
                                ->count();

                    @endphp


                    <tr>

                        <td>
                            #{{ $testimonial->id }}
                        </td>


                        <td>

                            <div class="customer-name">
                                {{ $testimonial->customer_name }}
                            </div>


                            @if (
                                !empty(
                                    $testimonial->testimonial
                                )
                            )

                                <div class="testimonial-text">

                                    {{
                                        \Illuminate\Support\Str::limit(
                                            $testimonial->testimonial,
                                            80
                                        )
                                    }}

                                </div>

                            @endif

                        </td>


                        <td>

                            @if ($testimonial->product)

                                {{
                                    $testimonial
                                        ->product
                                        ->product_name
                                }}

                            @else

                                <span style="color:#9ca3af;">
                                    Tidak terkait produk
                                </span>

                            @endif

                        </td>


                        <td>

                            <div class="stars">

                                @for (
                                    $i = 1;
                                    $i <= 5;
                                    $i++
                                )

                                    {{
                                        $i <= $testimonial->rating
                                            ? '★'
                                            : '☆'
                                    }}

                                @endfor

                            </div>

                            <div class="rating-text">
                                {{ $testimonial->rating }}/5
                            </div>

                        </td>


                        <td>

                            <div class="proof-group">

                                <span
                                    class="
                                        proof-badge
                                        proof-chat
                                    "
                                >
                                    💬 Chat:
                                    {{ $chatCount }}
                                </span>


                                <span
                                    class="
                                        proof-badge
                                        proof-video
                                    "
                                >
                                    🎥 Video:
                                    {{ $videoCount }}
                                </span>

                            </div>

                        </td>


                        <td>

                            @if (
                                $testimonial->status
                                === 'approved'
                            )

                                <span
                                    class="
                                        status
                                        status-approved
                                    "
                                >
                                    Approved
                                </span>

                            @elseif (
                                $testimonial->status
                                === 'rejected'
                            )

                                <span
                                    class="
                                        status
                                        status-rejected
                                    "
                                >
                                    Rejected
                                </span>

                            @else

                                <span
                                    class="
                                        status
                                        status-pending
                                    "
                                >
                                    Pending
                                </span>

                            @endif

                        </td>


                        <td>

                            {{
                                $testimonial
                                    ->creator
                                    ->name
                                ?? '-'
                            }}

                        </td>


                        <td>

                            {{
                                optional(
                                    $testimonial->created_at
                                )
                                ->format(
                                    'd/m/Y H:i'
                                )
                            }}

                        </td>


                        <td>

                            <div class="actions">


                                <a
                                    href="{{
                                        route(
                                            'testimonials.show',
                                            $testimonial
                                        )
                                    }}"
                                    class="
                                        action-btn
                                        btn-dark
                                    "
                                >
                                    Detail
                                </a>


                                <a
                                    href="{{
                                        route(
                                            'testimonials.edit',
                                            $testimonial
                                        )
                                    }}"
                                    class="
                                        action-btn
                                        btn-gray
                                    "
                                >
                                    Edit
                                </a>


                                @if (
                                    $testimonial->status
                                    !== 'approved'
                                )

                                    <form
                                        method="POST"
                                        action="{{
                                            route(
                                                'testimonials.approve',
                                                $testimonial
                                            )
                                        }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="
                                                action-btn
                                                btn-green
                                            "
                                        >
                                            Approve
                                        </button>

                                    </form>

                                @endif


                                @if (
                                    $testimonial->status
                                    !== 'rejected'
                                )

                                    <form
                                        method="POST"
                                        action="{{
                                            route(
                                                'testimonials.reject',
                                                $testimonial
                                            )
                                        }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="
                                                action-btn
                                                btn-red
                                            "
                                        >
                                            Reject
                                        </button>

                                    </form>

                                @endif


                                @if (
                                    $testimonial->status
                                    !== 'pending'
                                )

                                    <form
                                        method="POST"
                                        action="{{
                                            route(
                                                'testimonials.pending',
                                                $testimonial
                                            )
                                        }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="
                                                action-btn
                                                btn-orange
                                            "
                                        >
                                            Pending
                                        </button>

                                    </form>

                                @endif


                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'testimonials.destroy',
                                            $testimonial
                                        )
                                    }}"
                                    onsubmit="
                                        return confirm(
                                            'Hapus testimoni ini? Bukti chat dan video juga akan dihapus.'
                                        );
                                    "
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="
                                            action-btn
                                            btn-outline-red
                                        "
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>


                @empty


                    <tr>

                        <td
                            colspan="9"
                            class="empty"
                        >
                            Belum ada testimoni pembeli.
                        </td>

                    </tr>


                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    @if ($testimonials->hasPages())

        <div class="pagination">

            {{
                $testimonials
                    ->withQueryString()
                    ->links()
            }}

        </div>

    @endif

</div>

</body>
</html>