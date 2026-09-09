<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Detail Testimoni - {{ $testimonial->customer_name }}
    </title>

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
            color: inherit;
            text-decoration: none;
        }

        button {
            font-family: inherit;
        }

        .page {
            width: min(1100px, calc(100% - 28px));
            margin: 0 auto;
            padding: 28px 0 50px;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;

            gap: 16px;

            margin-bottom: 20px;
        }

        .page-title h1 {
            margin: 0;

            color: #111827;

            font-size: 27px;
            line-height: 1.2;
        }

        .page-title p {
            margin: 6px 0 0;

            color: #6b7280;

            font-size: 12px;
        }

        .header-actions {
            display: flex;
            flex-wrap: wrap;

            gap: 8px;
        }


        /*
        |--------------------------------------------------------------------------
        | BUTTON
        |--------------------------------------------------------------------------
        */

        .btn {
            min-height: 40px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0 14px;

            border: none;
            border-radius: 8px;

            font-size: 11px;
            font-weight: 800;

            cursor: pointer;

            transition: .2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-dark {
            background: #111827;
            color: #ffffff;
        }

        .btn-gray {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-green {
            background: #16a34a;
            color: #ffffff;
        }

        .btn-red {
            background: #dc2626;
            color: #ffffff;
        }

        .btn-orange {
            background: #f59e0b;
            color: #ffffff;
        }

        .btn-outline-red {
            border: 1px solid #fecaca;

            background: #ffffff;
            color: #b91c1c;
        }


        /*
        |--------------------------------------------------------------------------
        | ALERT
        |--------------------------------------------------------------------------
        */

        .alert {
            margin-bottom: 16px;

            padding: 12px 14px;

            border-radius: 8px;

            font-size: 12px;
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


        /*
        |--------------------------------------------------------------------------
        | MAIN CARD
        |--------------------------------------------------------------------------
        */

        .card {
            overflow: hidden;

            border: 1px solid #e5e7eb;
            border-radius: 14px;

            background: #ffffff;

            box-shadow:
                0 6px 20px
                rgba(0, 0, 0, .04);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 12px;

            padding: 16px 18px;

            border-bottom: 1px solid #e5e7eb;

            background: #fafafa;
        }

        .card-header h2 {
            margin: 0;

            color: #111827;

            font-size: 16px;
        }

        .card-body {
            padding: 20px;
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        .status {
            display: inline-flex;
            align-items: center;

            padding: 6px 10px;

            border-radius: 999px;

            font-size: 10px;
            font-weight: 900;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }


        /*
        |--------------------------------------------------------------------------
        | INFO GRID
        |--------------------------------------------------------------------------
        */

        .info-grid {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 14px;
        }

        .info-item {
            padding: 13px;

            border: 1px solid #e5e7eb;
            border-radius: 10px;

            background: #fafafa;
        }

        .info-item.full {
            grid-column: 1 / -1;
        }

        .info-label {
            display: block;

            margin-bottom: 5px;

            color: #6b7280;

            font-size: 9px;
            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .3px;
        }

        .info-value {
            color: #111827;

            font-size: 13px;
            font-weight: 700;

            line-height: 1.5;
        }


        /*
        |--------------------------------------------------------------------------
        | RATING
        |--------------------------------------------------------------------------
        */

        .stars {
            color: #f59e0b;

            font-size: 20px;

            letter-spacing: 2px;
        }

        .rating-number {
            margin-top: 3px;

            color: #6b7280;

            font-size: 10px;
        }


        /*
        |--------------------------------------------------------------------------
        | TESTIMONIAL TEXT
        |--------------------------------------------------------------------------
        */

        .testimonial-box {
            margin-top: 18px;

            padding: 16px;

            border-left: 4px solid #ef3340;
            border-radius: 8px;

            background: #fff7f7;

            color: #374151;

            font-size: 13px;
            line-height: 1.7;
        }


        /*
        |--------------------------------------------------------------------------
        | PROOF SECTION
        |--------------------------------------------------------------------------
        */

        .proof-section {
            margin-top: 22px;
        }

        .proof-section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 10px;

            margin-bottom: 12px;
        }

        .proof-section-title h3 {
            margin: 0;

            color: #111827;

            font-size: 15px;
        }

        .proof-count {
            display: inline-flex;

            padding: 4px 8px;

            border-radius: 999px;

            background: #f3f4f6;
            color: #4b5563;

            font-size: 9px;
            font-weight: 800;
        }


        /*
        |--------------------------------------------------------------------------
        | CHAT PROOF
        |--------------------------------------------------------------------------
        */

        .chat-grid {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 12px;
        }

        .chat-card {
            overflow: hidden;

            border: 1px solid #e5e7eb;
            border-radius: 10px;

            background: #ffffff;
        }

        .chat-image-link {
            display: block;

            background: #f3f4f6;
        }

        .chat-image {
            width: 100%;
            height: 260px;

            display: block;

            object-fit: cover;

            transition: transform .2s ease;
        }

        .chat-image-link:hover
        .chat-image {
            transform: scale(1.02);
        }

        .proof-footer {
            padding: 9px 10px;

            border-top: 1px solid #f1f5f9;
        }

        .proof-file-name {
            overflow: hidden;

            color: #4b5563;

            font-size: 9px;

            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .proof-file-size {
            margin-top: 3px;

            color: #9ca3af;

            font-size: 8px;
        }


        /*
        |--------------------------------------------------------------------------
        | VIDEO PROOF
        |--------------------------------------------------------------------------
        */

        .video-grid {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 14px;
        }

        .video-card {
            overflow: hidden;

            border: 1px solid #e5e7eb;
            border-radius: 10px;

            background: #ffffff;
        }

        .video-player {
            width: 100%;
            height: 300px;

            display: block;

            background: #111827;
        }


        /*
        |--------------------------------------------------------------------------
        | EMPTY PROOF
        |--------------------------------------------------------------------------
        */

        .empty-proof {
            padding: 28px;

            border: 1px dashed #d1d5db;
            border-radius: 10px;

            background: #fafafa;
            color: #6b7280;

            text-align: center;

            font-size: 11px;
        }


        /*
        |--------------------------------------------------------------------------
        | MODERATION ACTION
        |--------------------------------------------------------------------------
        */

        .moderation {
            display: flex;
            flex-wrap: wrap;

            gap: 8px;

            margin-top: 22px;
            padding-top: 18px;

            border-top: 1px solid #e5e7eb;
        }

        .moderation form {
            margin: 0;
        }


        /*
        |--------------------------------------------------------------------------
        | PRIVACY
        |--------------------------------------------------------------------------
        */

        .privacy-note {
            margin-top: 18px;

            padding: 11px 13px;

            border: 1px solid #fed7aa;
            border-radius: 8px;

            background: #fff7ed;
            color: #9a3412;

            font-size: 10px;
            line-height: 1.55;
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 850px) {

            .chat-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .video-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 620px) {

            .page {
                width: calc(100% - 14px);

                padding-top: 18px;
            }

            .page-header {
                flex-direction: column;
            }

            .header-actions {
                width: 100%;
            }

            .header-actions .btn {
                flex: 1;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-item.full {
                grid-column: auto;
            }

            .chat-grid {
                grid-template-columns: 1fr;
            }

            .chat-image {
                height: auto;
                max-height: 520px;

                object-fit: contain;
            }

            .video-player {
                height: 240px;
            }

            .card-body {
                padding: 14px;
            }

            .moderation {
                flex-direction: column;
            }

            .moderation form,
            .moderation .btn {
                width: 100%;
            }

        }

    </style>
</head>


<body>


@php

    /*
    |--------------------------------------------------------------------------
    | PISAHKAN BUKTI CHAT & VIDEO
    |--------------------------------------------------------------------------
    */

    $chatProofs =
        $testimonial
            ->proofs
            ->where(
                'proof_type',
                \App\Models\TestimonialProof::TYPE_CHAT
            );


    $videoProofs =
        $testimonial
            ->proofs
            ->where(
                'proof_type',
                \App\Models\TestimonialProof::TYPE_VIDEO
            );

@endphp



<div class="page">


    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}

    <div class="page-header">

        <div class="page-title">

            <h1>
                Detail Testimoni
            </h1>

            <p>
                Lihat informasi pembeli, rating,
                bukti chat pemesanan dan video pelanggan.
            </p>

        </div>


        <div class="header-actions">

            <a
                href="{{ route('testimonials.index') }}"
                class="btn btn-gray"
            >
                ← Kembali
            </a>


            <a
                href="{{
                    route(
                        'testimonials.edit',
                        $testimonial
                    )
                }}"
                class="btn btn-dark"
            >
                Edit Testimoni
            </a>

        </div>

    </div>



    {{-- ============================================================
         ALERT
    ============================================================ --}}

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



    {{-- ============================================================
         DETAIL CARD
    ============================================================ --}}

    <div class="card">


        <div class="card-header">

            <h2>
                Testimoni #{{ $testimonial->id }}
            </h2>


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

        </div>


        <div class="card-body">


            {{-- ============================================================
                 INFORMASI
            ============================================================ --}}

            <div class="info-grid">


                <div class="info-item">

                    <span class="info-label">
                        Nama Pembeli
                    </span>

                    <div class="info-value">
                        {{ $testimonial->customer_name }}
                    </div>

                </div>


                <div class="info-item">

                    <span class="info-label">
                        Produk
                    </span>

                    <div class="info-value">

                        @if ($testimonial->product)

                            {{
                                $testimonial
                                    ->product
                                    ->product_name
                            }}

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="info-item">

                    <span class="info-label">
                        Rating
                    </span>


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


                    <div class="rating-number">
                        {{ $testimonial->rating }}/5
                    </div>

                </div>


                <div class="info-item">

                    <span class="info-label">
                        Dibuat Oleh
                    </span>

                    <div class="info-value">

                        {{
                            $testimonial
                                ->creator
                                ->name
                            ?? '-'
                        }}

                    </div>

                </div>


                <div class="info-item">

                    <span class="info-label">
                        Tanggal Dibuat
                    </span>

                    <div class="info-value">

                        {{
                            optional(
                                $testimonial->created_at
                            )
                            ->format(
                                'd/m/Y H:i'
                            )
                        }}

                    </div>

                </div>


                <div class="info-item">

                    <span class="info-label">
                        Terakhir Diubah
                    </span>

                    <div class="info-value">

                        {{
                            optional(
                                $testimonial->updated_at
                            )
                            ->format(
                                'd/m/Y H:i'
                            )
                        }}

                    </div>

                </div>


            </div>



            {{-- ============================================================
                 TESTIMONI
            ============================================================ --}}

            @if (
                !empty(
                    $testimonial->testimonial
                )
            )

                <div class="testimonial-box">

                    “{{ $testimonial->testimonial }}”

                </div>

            @endif



            {{-- ============================================================
                 BUKTI CHAT
            ============================================================ --}}

            <div class="proof-section">


                <div class="proof-section-title">

                    <h3>
                        💬 Bukti Chat Pemesanan
                    </h3>

                    <span class="proof-count">
                        {{ $chatProofs->count() }} file
                    </span>

                </div>


                @if ($chatProofs->isNotEmpty())


                    <div class="chat-grid">


                        @foreach (
                            $chatProofs
                            as $proof
                        )


                            <div class="chat-card">


                                <a
                                    href="{{
                                        asset(
                                            'storage/'
                                            . $proof->file_path
                                        )
                                    }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="chat-image-link"
                                >

                                    <img
                                        src="{{
                                            asset(
                                                'storage/'
                                                . $proof->file_path
                                            )
                                        }}"
                                        alt="Bukti chat {{ $testimonial->customer_name }}"
                                        class="chat-image"
                                    >

                                </a>


                                <div class="proof-footer">

                                    <div class="proof-file-name">

                                        {{
                                            $proof->file_name
                                            ?? 'Bukti Chat'
                                        }}

                                    </div>


                                    @if ($proof->file_size)

                                        <div class="proof-file-size">

                                            {{
                                                number_format(
                                                    $proof->file_size
                                                    / 1024,
                                                    1
                                                )
                                            }}
                                            KB

                                        </div>

                                    @endif

                                </div>


                            </div>


                        @endforeach


                    </div>


                @else


                    <div class="empty-proof">
                        Belum ada bukti chat pemesanan.
                    </div>


                @endif


            </div>



            {{-- ============================================================
                 BUKTI VIDEO
            ============================================================ --}}

            <div class="proof-section">


                <div class="proof-section-title">

                    <h3>
                        🎥 Bukti Video
                    </h3>

                    <span class="proof-count">
                        {{ $videoProofs->count() }} file
                    </span>

                </div>


                @if ($videoProofs->isNotEmpty())


                    <div class="video-grid">


                        @foreach (
                            $videoProofs
                            as $proof
                        )


                            <div class="video-card">


                                <video
                                    class="video-player"
                                    controls
                                    preload="metadata"
                                >

                                    <source
                                        src="{{
                                            asset(
                                                'storage/'
                                                . $proof->file_path
                                            )
                                        }}"
                                        type="{{
                                            $proof->mime_type
                                            ?? 'video/mp4'
                                        }}"
                                    >

                                    Browser tidak mendukung video.

                                </video>


                                <div class="proof-footer">

                                    <div class="proof-file-name">

                                        {{
                                            $proof->file_name
                                            ?? 'Video'
                                        }}

                                    </div>


                                    @if ($proof->file_size)

                                        <div class="proof-file-size">

                                            {{
                                                number_format(
                                                    $proof->file_size
                                                    / 1024
                                                    / 1024,
                                                    2
                                                )
                                            }}
                                            MB

                                        </div>

                                    @endif

                                </div>


                            </div>


                        @endforeach


                    </div>


                @else


                    <div class="empty-proof">
                        Belum ada bukti video.
                    </div>


                @endif


            </div>



            {{-- ============================================================
                 PRIVACY NOTE
            ============================================================ --}}

            <div class="privacy-note">

                Sebelum screenshot chat ditampilkan di website publik,
                pastikan nomor telepon, alamat, nomor rekening
                atau informasi pribadi pelanggan yang tidak diperlukan
                sudah disamarkan.

            </div>



            {{-- ============================================================
                 MODERATION
            ============================================================ --}}

            <div class="moderation">


                @if (
                    $testimonial->status
                    !== 'approved'
                )

                    <form
                        action="{{
                            route(
                                'testimonials.approve',
                                $testimonial
                            )
                        }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')


                        <button
                            type="submit"
                            class="btn btn-green"
                        >
                            ✓ Approve
                        </button>

                    </form>

                @endif



                @if (
                    $testimonial->status
                    !== 'rejected'
                )

                    <form
                        action="{{
                            route(
                                'testimonials.reject',
                                $testimonial
                            )
                        }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')


                        <button
                            type="submit"
                            class="btn btn-red"
                        >
                            ✕ Reject
                        </button>

                    </form>

                @endif



                @if (
                    $testimonial->status
                    !== 'pending'
                )

                    <form
                        action="{{
                            route(
                                'testimonials.pending',
                                $testimonial
                            )
                        }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')


                        <button
                            type="submit"
                            class="btn btn-orange"
                        >
                            Pending
                        </button>

                    </form>

                @endif



                <form
                    action="{{
                        route(
                            'testimonials.destroy',
                            $testimonial
                        )
                    }}"
                    method="POST"

                    onsubmit="
                        return confirm(
                            'Yakin ingin menghapus testimoni ini? Bukti chat dan video juga akan dihapus.'
                        );
                    "
                >

                    @csrf
                    @method('DELETE')


                    <button
                        type="submit"
                        class="
                            btn
                            btn-outline-red
                        "
                    >
                        Hapus Testimoni
                    </button>

                </form>


            </div>


        </div>

    </div>


</div>


</body>

</html>