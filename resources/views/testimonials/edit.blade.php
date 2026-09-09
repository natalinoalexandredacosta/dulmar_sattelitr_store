<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Edit Testimoni - {{ $testimonial->customer_name }}
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
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }

        .page {
            width: min(1000px, calc(100% - 24px));
            margin: 0 auto;
            padding: 28px 0 50px;
        }

        .topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 18px;
        }

        .title h1 {
            margin: 0;
            color: #111827;
            font-size: 26px;
        }

        .title p {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 12px;
        }

        .top-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

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
        }

        .btn-dark {
            background: #111827;
            color: #ffffff;
        }

        .btn-gray {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-red {
            background: #ef3340;
            color: #ffffff;
        }

        .btn-danger-outline {
            border: 1px solid #fecaca;
            background: #ffffff;
            color: #b91c1c;
        }

        .alert {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 12px;
        }

        .alert-error {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
        }

        .alert-success {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #166534;
        }

        .card {
            padding: 22px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 5px 18px rgba(0,0,0,.04);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #374151;
            font-size: 12px;
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            background: #ffffff;
            color: #111827;
            font-size: 12px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #ef3340;
            box-shadow: 0 0 0 3px rgba(239,51,64,.08);
        }

        .hint {
            margin-top: 5px;
            color: #6b7280;
            font-size: 10px;
            line-height: 1.5;
        }

        .required {
            color: #dc2626;
        }

        .upload-box {
            padding: 14px;
            border: 1px dashed #d1d5db;
            border-radius: 10px;
            background: #fafafa;
        }

        .upload-title {
            margin-bottom: 5px;
            color: #111827;
            font-size: 12px;
            font-weight: 800;
        }

        .upload-subtitle {
            margin-bottom: 10px;
            color: #6b7280;
            font-size: 10px;
            line-height: 1.5;
        }

        .existing-title {
            margin: 4px 0 10px;
            color: #111827;
            font-size: 13px;
            font-weight: 800;
        }

        .proof-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .proof-card {
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #ffffff;
        }

        .proof-card img {
            width: 100%;
            height: 180px;
            display: block;
            object-fit: cover;
            background: #f3f4f6;
        }

        .proof-card video {
            width: 100%;
            height: 180px;
            display: block;
            background: #111827;
        }

        .proof-footer {
            padding: 9px;
        }

        .proof-name {
            overflow: hidden;
            color: #4b5563;
            font-size: 9px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .proof-type {
            margin-top: 4px;
            color: #6b7280;
            font-size: 9px;
        }

        .proof-delete {
            margin-top: 8px;
        }

        .proof-delete button {
            width: 100%;
            min-height: 31px;
            border: 1px solid #fecaca;
            border-radius: 6px;
            background: #ffffff;
            color: #b91c1c;
            font-size: 9px;
            font-weight: 800;
            cursor: pointer;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .preview-item {
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #ffffff;
        }

        .preview-item img,
        .preview-item video {
            width: 100%;
            height: 130px;
            display: block;
            object-fit: cover;
        }

        .preview-item video {
            background: #111827;
        }

        .preview-name {
            padding: 7px;
            overflow: hidden;
            color: #6b7280;
            font-size: 9px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .privacy-note {
            margin-top: 14px;
            padding: 10px 12px;
            border-radius: 8px;
            background: #fff7ed;
            color: #9a3412;
            font-size: 10px;
            line-height: 1.55;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 760px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .proof-grid,
            .preview-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .topbar {
                flex-direction: column;
            }
        }

        @media (max-width: 500px) {
            .proof-grid,
            .preview-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions .btn {
                width: 100%;
            }

            .top-actions {
                width: 100%;
            }

            .top-actions .btn {
                flex: 1;
            }
        }
    </style>
</head>

<body>

@php
    $chatProofs = $testimonial
        ->proofs
        ->where(
            'proof_type',
            \App\Models\TestimonialProof::TYPE_CHAT
        );

    $videoProofs = $testimonial
        ->proofs
        ->where(
            'proof_type',
            \App\Models\TestimonialProof::TYPE_VIDEO
        );
@endphp

<div class="page">

    <div class="topbar">

        <div class="title">

            <h1>
                Edit Testimoni
            </h1>

            <p>
                Ubah informasi testimoni, status,
                bukti chat dan video pelanggan.
            </p>

        </div>

        <div class="top-actions">

            <a
                href="{{ route('testimonials.show', $testimonial) }}"
                class="btn btn-dark"
            >
                Detail
            </a>

            <a
                href="{{ route('testimonials.index') }}"
                class="btn btn-gray"
            >
                ← Kembali
            </a>

        </div>

    </div>


    @if ($errors->any())

        <div class="alert alert-error">

            <strong>
                Data belum dapat disimpan:
            </strong>

            <ul style="margin:8px 0 0;padding-left:18px;">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @if (session('error'))

        <div class="alert alert-error">
            {{ session('error') }}
        </div>

    @endif


    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <form
        action="{{ route('testimonials.update', $testimonial) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        <div class="card">

            <div class="form-grid">


                <div class="form-group">

                    <label for="customer_name">
                        Nama Pembeli
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="customer_name"
                        name="customer_name"
                        value="{{ old('customer_name', $testimonial->customer_name) }}"
                        maxlength="150"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="rating">
                        Rating
                        <span class="required">*</span>
                    </label>

                    <select
                        id="rating"
                        name="rating"
                        required
                    >

                        @for ($rating = 5; $rating >= 1; $rating--)

                            <option
                                value="{{ $rating }}"
                                {{
                                    (int) old(
                                        'rating',
                                        $testimonial->rating
                                    ) === $rating
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                @for ($star = 1; $star <= 5; $star++)
                                    {{ $star <= $rating ? '★' : '☆' }}
                                @endfor

                                - {{ $rating }}
                            </option>

                        @endfor

                    </select>

                </div>


                <div class="form-group">

                    <label for="product_id">
                        Produk
                    </label>

                    <select
                        id="product_id"
                        name="product_id"
                    >

                        <option value="">
                            -- Tidak terkait produk tertentu --
                        </option>

                        @foreach ($products as $product)

                            <option
                                value="{{ $product->id }}"
                                {{
                                    (string) old(
                                        'product_id',
                                        $testimonial->product_id
                                    )
                                    ===
                                    (string) $product->id
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                {{ $product->product_name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="form-group">

                    <label for="status">
                        Status
                        <span class="required">*</span>
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                    >

                        <option
                            value="pending"
                            {{
                                old(
                                    'status',
                                    $testimonial->status
                                ) === 'pending'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            Pending
                        </option>

                        <option
                            value="approved"
                            {{
                                old(
                                    'status',
                                    $testimonial->status
                                ) === 'approved'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            Approved
                        </option>

                        <option
                            value="rejected"
                            {{
                                old(
                                    'status',
                                    $testimonial->status
                                ) === 'rejected'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            Rejected
                        </option>

                    </select>

                </div>


                <div class="form-group full">

                    <label for="testimonial">
                        Testimoni / Komentar
                    </label>

                    <textarea
                        id="testimonial"
                        name="testimonial"
                        maxlength="3000"
                    >{{ old('testimonial', $testimonial->testimonial) }}</textarea>

                </div>


                <div class="form-group full">

                    <div class="existing-title">
                        Bukti yang Sudah Ada
                    </div>

                    @if ($testimonial->proofs->isNotEmpty())

                        <div class="proof-grid">

                            @foreach ($testimonial->proofs as $proof)

                                <div class="proof-card">

                                    @if (
                                        $proof->proof_type
                                        ===
                                        \App\Models\TestimonialProof::TYPE_CHAT
                                    )

                                        <a
                                            href="{{ asset('storage/' . $proof->file_path) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            <img
                                                src="{{ asset('storage/' . $proof->file_path) }}"
                                                alt="Bukti chat"
                                            >
                                        </a>

                                    @elseif (
                                        $proof->proof_type
                                        ===
                                        \App\Models\TestimonialProof::TYPE_VIDEO
                                    )

                                        <video
                                            controls
                                            preload="metadata"
                                        >
                                            <source
                                                src="{{ asset('storage/' . $proof->file_path) }}"
                                                type="{{ $proof->mime_type ?? 'video/mp4' }}"
                                            >
                                        </video>

                                    @endif


                                    <div class="proof-footer">

                                        <div class="proof-name">
                                            {{ $proof->file_name ?? '-' }}
                                        </div>

                                        <div class="proof-type">

                                            @if (
                                                $proof->proof_type
                                                ===
                                                \App\Models\TestimonialProof::TYPE_CHAT
                                            )
                                                💬 Bukti Chat
                                            @else
                                                🎥 Bukti Video
                                            @endif

                                        </div>

                                        <div class="proof-delete">

                                            <button
                                                type="submit"
                                                form="delete-proof-{{ $proof->id }}"
                                            >
                                                Hapus Bukti
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div
                            style="
                                padding:20px;
                                border:1px dashed #d1d5db;
                                border-radius:8px;
                                color:#6b7280;
                                font-size:11px;
                                text-align:center;
                            "
                        >
                            Belum ada bukti.
                        </div>

                    @endif

                </div>


                <div class="form-group full">

                    <div class="upload-box">

                        <div class="upload-title">
                            💬 Tambah Bukti Chat Baru
                        </div>

                        <div class="upload-subtitle">
                            Maksimal 10 gambar.
                            JPG, JPEG, PNG atau WEBP.
                            Maksimal 10 MB per file.
                        </div>

                        <input
                            type="file"
                            id="chat_proofs"
                            name="chat_proofs[]"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            multiple
                        >

                        <div
                            id="chatPreview"
                            class="preview-grid"
                        ></div>

                    </div>

                </div>


                <div class="form-group full">

                    <div class="upload-box">

                        <div class="upload-title">
                            🎥 Tambah Video Baru
                        </div>

                        <div class="upload-subtitle">
                            Maksimal 5 video.
                            MP4, MOV atau WEBM.
                            Maksimal 100 MB per file.
                        </div>

                        <input
                            type="file"
                            id="video_proofs"
                            name="video_proofs[]"
                            accept=".mp4,.mov,.webm,video/mp4,video/quicktime,video/webm"
                            multiple
                        >

                        <div
                            id="videoPreview"
                            class="preview-grid"
                        ></div>

                    </div>

                </div>


            </div>


            <div class="privacy-note">
                Pastikan screenshot chat yang tampil ke publik
                tidak memperlihatkan nomor telepon, alamat,
                nomor rekening atau data pribadi yang tidak diperlukan.
            </div>


            <div class="form-actions">

                <a
                    href="{{ route('testimonials.index') }}"
                    class="btn btn-gray"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-red"
                >
                    Simpan Perubahan
                </button>

            </div>

        </div>

    </form>


    @foreach ($testimonial->proofs as $proof)

        <form
            id="delete-proof-{{ $proof->id }}"
            action="{{ route('testimonials.proofs.destroy', $proof) }}"
            method="POST"
            style="display:none;"
            onsubmit="
                return confirm(
                    'Yakin ingin menghapus bukti ini?'
                );
            "
        >
            @csrf
            @method('DELETE')
        </form>

    @endforeach

</div>


<script>

    const chatInput =
        document.getElementById(
            'chat_proofs'
        );

    const chatPreview =
        document.getElementById(
            'chatPreview'
        );


    if (chatInput && chatPreview) {

        chatInput.addEventListener(
            'change',
            function () {

                chatPreview.innerHTML = '';

                Array.from(this.files)
                    .forEach(function (file) {

                        const url =
                            URL.createObjectURL(file);

                        const item =
                            document.createElement('div');

                        item.className =
                            'preview-item';

                        const image =
                            document.createElement('img');

                        image.src = url;
                        image.alt = file.name;

                        const name =
                            document.createElement('div');

                        name.className =
                            'preview-name';

                        name.textContent =
                            file.name;

                        item.appendChild(image);
                        item.appendChild(name);

                        chatPreview.appendChild(item);

                    });

            }
        );

    }


    const videoInput =
        document.getElementById(
            'video_proofs'
        );

    const videoPreview =
        document.getElementById(
            'videoPreview'
        );


    if (videoInput && videoPreview) {

        videoInput.addEventListener(
            'change',
            function () {

                videoPreview.innerHTML = '';

                Array.from(this.files)
                    .forEach(function (file) {

                        const url =
                            URL.createObjectURL(file);

                        const item =
                            document.createElement('div');

                        item.className =
                            'preview-item';

                        const video =
                            document.createElement('video');

                        video.src = url;
                        video.controls = true;
                        video.preload = 'metadata';

                        const name =
                            document.createElement('div');

                        name.className =
                            'preview-name';

                        name.textContent =
                            file.name;

                        item.appendChild(video);
                        item.appendChild(name);

                        videoPreview.appendChild(item);

                    });

            }
        );

    }

</script>

</body>

</html>