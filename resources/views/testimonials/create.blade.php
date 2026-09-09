<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Testimoni - Dulmar Satellite Store</title>

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

        .page {
            width: min(900px, calc(100% - 24px));
            margin: 0 auto;
            padding: 28px 0 50px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 18px;
        }

        .title h1 {
            margin: 0;
            font-size: 26px;
            color: #111827;
        }

        .title p {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 13px;
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

        .btn-gray {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-red {
            background: #ef3340;
            color: #ffffff;
        }

        .card {
            padding: 22px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 5px 18px rgba(0, 0, 0, .04);
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
            box-shadow: 0 0 0 3px rgba(239, 51, 64, .08);
        }

        .hint {
            margin-top: 5px;
            color: #6b7280;
            font-size: 10px;
            line-height: 1.5;
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
            background: white;
        }

        .preview-item img {
            display: block;
            width: 100%;
            height: 130px;
            object-fit: cover;
        }

        .preview-item video {
            display: block;
            width: 100%;
            height: 130px;
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

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #e5e7eb;
        }

        .required {
            color: #dc2626;
        }

        .privacy-note {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            background: #fff7ed;
            color: #9a3412;
            font-size: 10px;
            line-height: 1.55;
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .preview-grid {
                grid-template-columns: 1fr 1fr;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions .btn {
                width: 100%;
            }
        }

        @media (max-width: 430px) {
            .preview-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="topbar">

        <div class="title">

            <h1>
                Tambah Testimoni
            </h1>

            <p>
                Tambahkan testimoni pembeli beserta bukti chat pemesanan dan video.
            </p>

        </div>

        <a
            href="{{ route('testimonials.index') }}"
            class="btn btn-gray"
        >
            ← Kembali
        </a>

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


    <form
        action="{{ route('testimonials.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


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
                        value="{{ old('customer_name') }}"
                        maxlength="150"
                        required
                        placeholder="Contoh: Joao Manuel"
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

                        <option
                            value="5"
                            {{ old('rating', 5) == 5 ? 'selected' : '' }}
                        >
                            ★★★★★ - 5
                        </option>

                        <option
                            value="4"
                            {{ old('rating') == 4 ? 'selected' : '' }}
                        >
                            ★★★★☆ - 4
                        </option>

                        <option
                            value="3"
                            {{ old('rating') == 3 ? 'selected' : '' }}
                        >
                            ★★★☆☆ - 3
                        </option>

                        <option
                            value="2"
                            {{ old('rating') == 2 ? 'selected' : '' }}
                        >
                            ★★☆☆☆ - 2
                        </option>

                        <option
                            value="1"
                            {{ old('rating') == 1 ? 'selected' : '' }}
                        >
                            ★☆☆☆☆ - 1
                        </option>

                    </select>

                </div>


                <div class="form-group full">

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
                                    (string) old('product_id')
                                    === (string) $product->id
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                {{ $product->product_name }}
                            </option>

                        @endforeach

                    </select>

                    <div class="hint">
                        Pilih produk yang dibeli pelanggan.
                    </div>

                </div>


                <div class="form-group full">

                    <label for="testimonial">
                        Testimoni / Komentar Pembeli
                    </label>

                    <textarea
                        id="testimonial"
                        name="testimonial"
                        maxlength="3000"
                        placeholder="Contoh: Barang sudah diterima, kualitas bagus dan pelayanan cepat."
                    >{{ old('testimonial') }}</textarea>

                </div>


                <div class="form-group full">

                    <div class="upload-box">

                        <div class="upload-title">
                            💬 Bukti Chat Pemesanan
                        </div>

                        <div class="upload-subtitle">
                            Upload screenshot chat WhatsApp atau chat pemesanan pelanggan.
                            Maksimal 10 file. Format JPG, JPEG, PNG atau WEBP.
                            Maksimal 10 MB per gambar.
                        </div>

                        <input
                            type="file"
                            id="chat_proofs"
                            name="chat_proofs[]"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            multiple
                        >

                        <div
                            class="preview-grid"
                            id="chatPreview"
                        ></div>

                    </div>

                </div>


                <div class="form-group full">

                    <div class="upload-box">

                        <div class="upload-title">
                            🎥 Bukti Video
                        </div>

                        <div class="upload-subtitle">
                            Upload video pelanggan, unboxing, pemasangan,
                            atau bukti barang sudah diterima.
                            Maksimal 5 video.
                            Format MP4, MOV atau WEBM.
                            Maksimal 100 MB per video.
                        </div>

                        <input
                            type="file"
                            id="video_proofs"
                            name="video_proofs[]"
                            accept=".mp4,.mov,.webm,video/mp4,video/quicktime,video/webm"
                            multiple
                        >

                        <div
                            class="preview-grid"
                            id="videoPreview"
                        ></div>

                    </div>

                </div>


            </div>


            <div class="privacy-note">
                Sebelum bukti chat ditampilkan ke website publik,
                pastikan nomor telepon, alamat, nomor rekening,
                atau data pribadi pelanggan yang tidak perlu sudah disamarkan.
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
                    Simpan Testimoni
                </button>

            </div>

        </div>

    </form>

</div>


<script>

    /*
    |--------------------------------------------------------------------------
    | PREVIEW BUKTI CHAT
    |--------------------------------------------------------------------------
    */

    const chatInput =
        document.getElementById(
            'chat_proofs'
        );

    const chatPreview =
        document.getElementById(
            'chatPreview'
        );


    if (
        chatInput
        &&
        chatPreview
    ) {

        chatInput.addEventListener(
            'change',
            function () {

                chatPreview.innerHTML = '';

                const files =
                    Array.from(
                        this.files
                    );

                files.forEach(
                    function (file) {

                        const url =
                            URL.createObjectURL(
                                file
                            );

                        const item =
                            document.createElement(
                                'div'
                            );

                        item.className =
                            'preview-item';

                        const image =
                            document.createElement(
                                'img'
                            );

                        image.src = url;

                        image.alt =
                            file.name;

                        const name =
                            document.createElement(
                                'div'
                            );

                        name.className =
                            'preview-name';

                        name.textContent =
                            file.name;

                        item.appendChild(
                            image
                        );

                        item.appendChild(
                            name
                        );

                        chatPreview.appendChild(
                            item
                        );

                    }
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PREVIEW VIDEO
    |--------------------------------------------------------------------------
    */

    const videoInput =
        document.getElementById(
            'video_proofs'
        );

    const videoPreview =
        document.getElementById(
            'videoPreview'
        );


    if (
        videoInput
        &&
        videoPreview
    ) {

        videoInput.addEventListener(
            'change',
            function () {

                videoPreview.innerHTML = '';

                const files =
                    Array.from(
                        this.files
                    );

                files.forEach(
                    function (file) {

                        const url =
                            URL.createObjectURL(
                                file
                            );

                        const item =
                            document.createElement(
                                'div'
                            );

                        item.className =
                            'preview-item';

                        const video =
                            document.createElement(
                                'video'
                            );

                        video.src = url;

                        video.controls = true;

                        video.preload =
                            'metadata';

                        const name =
                            document.createElement(
                                'div'
                            );

                        name.className =
                            'preview-name';

                        name.textContent =
                            file.name;

                        item.appendChild(
                            video
                        );

                        item.appendChild(
                            name
                        );

                        videoPreview.appendChild(
                            item
                        );

                    }
                );

            }
        );

    }

</script>

</body>
</html>