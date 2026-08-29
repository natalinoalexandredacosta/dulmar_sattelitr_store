<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Produk - Dulmar Satellite Store</title>

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
            background-color: #f4f6f9;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 245px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 35px 25px;
            background-color: #1f2b3a;
            color: white;
        }

        .sidebar h1 {
            margin: 0 0 55px;
            font-size: 28px;
        }

        .sidebar-menu {
            flex: 1;
        }

        .sidebar-menu a {
            display: block;
            margin-bottom: 28px;
            color: white;
            font-size: 18px;
            text-decoration: none;
        }

        .sidebar-menu a:hover {
            color: #60a5fa;
        }

        .button-logout {
            width: 100%;
            padding: 13px 15px;
            border: none;
            border-radius: 7px;
            background-color: #dc2626;
            color: white;
            font-size: 17px;
            cursor: pointer;
        }

        .button-logout:hover {
            background-color: #b91c1c;
        }

        .main-content {
            flex: 1;
            padding: 50px;
        }

        .page-header {
            margin-bottom: 35px;
        }

        .page-header h2 {
            margin: 0 0 15px;
            font-size: 36px;
        }

        .page-header p {
            margin: 0;
            color: #4b5563;
            font-size: 18px;
        }

        .form-card {
            max-width: 850px;
            padding: 35px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .section-divider {
            margin: 35px 0 25px;
            border: none;
            border-top: 1px solid #e5e7eb;
        }

        .section-label {
            margin-bottom: 20px;
        }

        .section-label h3 {
            margin: 0 0 7px;
            color: #1f2937;
            font-size: 21px;
        }

        .section-label p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
        }

        .alert-error {
            margin-bottom: 25px;
            padding: 15px 20px;
            border: 1px solid #fca5a5;
            border-radius: 6px;
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 17px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        textarea.form-control {
            min-height: 130px;
            resize: vertical;
            line-height: 1.5;
        }

        .form-control:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow:
                0 0 0 2px
                rgba(37, 99, 235, 0.08);
        }

        .help-text {
            display: block;
            margin-top: 7px;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.4;
        }

        .optional-label {
            color: #6b7280;
            font-size: 13px;
            font-weight: normal;
        }

        /*
        |--------------------------------------------------------------------------
        | PROMO
        |--------------------------------------------------------------------------
        */

        .promo-toggle-box {
            margin-bottom: 20px;
            padding: 18px;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            background-color: #fffbeb;
        }

        .promo-toggle-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .promo-toggle-label input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .promo-toggle-title {
            color: #92400e;
            font-size: 16px;
            font-weight: bold;
        }

        .promo-fields {
            display: none;
        }

        .promo-fields.active {
            display: block;
        }

        .promo-information-box {
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            background-color: #fffaf5;
        }

        .promo-information-box h4 {
            margin: 0 0 7px;
            color: #9a3412;
            font-size: 17px;
        }

        .promo-information-box > p {
            margin: 0 0 20px;
            color: #7c2d12;
            font-size: 13px;
            line-height: 1.5;
        }

        .promo-preview {
            margin-bottom: 25px;
            padding: 18px;
            border: 1px solid #fdba74;
            border-radius: 8px;
            background-color: #fff7ed;
        }

        .promo-preview-title {
            margin-bottom: 10px;
            color: #9a3412;
            font-size: 14px;
            font-weight: bold;
        }

        .promo-price-normal {
            margin-right: 10px;
            color: #6b7280;
            font-size: 15px;
            text-decoration: line-through;
        }

        .promo-price-final {
            color: #dc2626;
            font-size: 22px;
            font-weight: bold;
        }

        .promo-discount-label {
            display: inline-block;
            margin-left: 10px;
            padding: 4px 8px;
            border-radius: 5px;
            background-color: #dc2626;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        .image-preview-wrapper {
            display: none;
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #f9fafb;
        }

        .image-preview-wrapper.active {
            display: block;
        }

        .image-preview-title {
            margin-bottom: 10px;
            color: #374151;
            font-size: 14px;
            font-weight: bold;
        }

        .image-preview {
            display: block;
            width: 100%;
            max-width: 320px;
            height: 220px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            object-fit: cover;
            background-color: #e5e7eb;
        }

        .image-info {
            margin-top: 10px;
            color: #6b7280;
            font-size: 13px;
        }

        /*
        |--------------------------------------------------------------------------
        | PROFIT
        |--------------------------------------------------------------------------
        */

        .profit-preview {
            margin-bottom: 25px;
            padding: 18px;
            border: 1px solid #86efac;
            border-radius: 7px;
            background-color: #f0fdf4;
        }

        .profit-preview span {
            color: #166534;
            font-size: 17px;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | ACTION
        |--------------------------------------------------------------------------
        */

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            padding: 13px 22px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .button-save {
            background-color: #2563eb;
            color: white;
        }

        .button-save:hover {
            background-color: #1d4ed8;
        }

        .button-cancel {
            background-color: #6b7280;
            color: white;
        }

        .button-cancel:hover {
            background-color: #4b5563;
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 850px) {
            .container {
                display: block;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
                padding: 20px;
            }

            .sidebar h1 {
                margin-bottom: 25px;
            }

            .sidebar-menu a {
                margin-bottom: 15px;
            }

            .main-content {
                padding: 25px 15px;
            }

            .form-card {
                max-width: 100%;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .form-actions {
                flex-direction: column;
            }

            .button {
                width: 100%;
                text-align: center;
            }
        }
    </style>

</head>

<body>

<div class="container">

    <aside class="sidebar">

        <h1>
            Dulmar Satellite Store
        </h1>

        <nav class="sidebar-menu">

            <a href="{{ route('dashboard') }}">
                Dashboard
            </a>

            <a href="{{ route('products.index') }}">
                Daftar Barang
            </a>

            <a href="{{ route('stock-ins.index') }}">
                Stok Masuk
            </a>

            <a href="{{ route('stock-outs.index') }}">
                Stok Keluar
            </a>

            <a href="{{ route('tv-vouchers.index') }}">
                TV Voucher
            </a>

            <a href="{{ route('suppliers.index') }}">
                Supplier Barang
            </a>

            <a href="{{ route('customers.index') }}">
                Pelanggan
            </a>

            <a href="{{ route('reports.index') }}">
                Laporan
            </a>

        </nav>

        <form
            action="{{ route('logout') }}"
            method="POST"
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

            <h2>
                Tambah Produk
            </h2>

            <p>
                Tambahkan produk baru beserta detail,
                spesifikasi, dan promo.
            </p>

        </div>


        <div class="form-card">

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


            <form
                action="{{ route('products.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf


                {{-- ========================================================
                    INFORMASI PRODUK
                ========================================================= --}}

                <div class="section-label">

                    <h3>
                        Informasi Produk
                    </h3>

                    <p>
                        Masukkan informasi utama produk
                        yang akan disimpan.
                    </p>

                </div>


                <div class="form-group">

                    <label for="product_name">
                        Nama Produk
                    </label>

                    <input
                        type="text"
                        id="product_name"
                        name="product_name"
                        class="form-control"
                        value="{{ old('product_name') }}"
                        placeholder="Contoh: K-Vision Receiver Bromo C2000"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="category">
                        Kategori
                    </label>

                    <input
                        type="text"
                        id="category"
                        name="category"
                        class="form-control"
                        value="{{ old('category') }}"
                        placeholder="Contoh: Receiver"
                        required
                    >

                </div>


                {{-- ========================================================
                    HARGA PRODUK
                ========================================================= --}}

                <hr class="section-divider">

                <div class="section-label">

                    <h3>
                        Harga Produk
                    </h3>

                    <p>
                        Masukkan harga beli dan harga jual
                        normal produk.
                    </p>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="purchase_price">
                            Harga Beli
                        </label>

                        <input
                            type="number"
                            id="purchase_price"
                            name="purchase_price"
                            class="form-control"
                            value="{{ old('purchase_price') }}"
                            placeholder="Contoh: 150.00"
                            min="0"
                            step="0.01"
                            required
                            oninput="hitungHarga()"
                        >

                        <span class="help-text">
                            Harga pembelian untuk satu unit produk.
                        </span>

                    </div>


                    <div class="form-group">

                        <label for="selling_price">
                            Harga Jual Normal
                        </label>

                        <input
                            type="number"
                            id="selling_price"
                            name="selling_price"
                            class="form-control"
                            value="{{ old('selling_price') }}"
                            placeholder="Contoh: 175.00"
                            min="0"
                            step="0.01"
                            required
                            oninput="hitungHarga()"
                        >

                        <span class="help-text">
                            Harga normal sebelum promo atau diskon.
                        </span>

                    </div>

                </div>


                <div class="profit-preview">

                    Laba normal per unit:

                    <span id="profitValue">
                        $0.00
                    </span>

                </div>


                {{-- ========================================================
                    PROMO / DISKON
                ========================================================= --}}

                <hr class="section-divider">

                <div class="section-label">

                    <h3>
                        Promo / Diskon
                    </h3>

                    <p>
                        Atur promo produk, judul promo,
                        alasan promo, nilai diskon dan
                        periode berlakunya.
                    </p>

                </div>


                <div class="promo-toggle-box">

                    <label class="promo-toggle-label">

                        <input
                            type="checkbox"
                            id="is_promo"
                            name="is_promo"
                            value="1"
                            {{ old('is_promo') ? 'checked' : '' }}
                            onchange="togglePromo()"
                        >

                        <span class="promo-toggle-title">
                            Aktifkan Promo untuk Produk Ini
                        </span>

                    </label>

                    <span class="help-text">
                        Promo hanya akan tampil pada website
                        apabila status promo aktif dan tanggalnya
                        masih berlaku.
                    </span>

                </div>


                <div
                    id="promoFields"
                    class="promo-fields"
                >

                    {{-- INFORMASI / ALASAN PROMO --}}

                    <div class="promo-information-box">

                        <h4>
                            Informasi Promo
                        </h4>

                        <p>
                            Informasi ini dapat digunakan untuk
                            menjelaskan kepada pelanggan kenapa
                            promo diberikan.
                        </p>


                        <div class="form-group">

                            <label for="promo_title">
                                Judul Promo
                            </label>

                            <input
                                type="text"
                                id="promo_title"
                                name="promo_title"
                                class="form-control"
                                value="{{ old('promo_title') }}"
                                placeholder="Contoh: Promosaun Espesiál ba Loron 30 Agostu"
                            >

                            <span class="help-text">
                                Contoh:
                                Promosaun Espesiál ba Loron 30 Agostu,
                                Promo Natal, Promo Ano Novo, dan lain-lain.
                            </span>

                        </div>


                        <div class="form-group">

                            <label for="promo_description">
                                Keterangan / Alasan Promo
                            </label>

                            <textarea
                                id="promo_description"
                                name="promo_description"
                                class="form-control"
                                placeholder="Contoh: Promosaun ida-ne'e prepara especialmente ba loron 30 Agostu atu fó oportunidade ba kliente sira hetan produtu ho presu espesiál."
                            >{{ old('promo_description') }}</textarea>

                            <span class="help-text">
                                Tuliskan alasan atau informasi
                                promosi yang nantinya dapat
                                ditampilkan pada website pelanggan.
                            </span>

                        </div>

                    </div>


                    {{-- DISKON --}}

                    <div class="form-row">

                        <div class="form-group">

                            <label for="discount_type">
                                Jenis Diskon
                            </label>

                            <select
                                id="discount_type"
                                name="discount_type"
                                class="form-control"
                                onchange="hitungHarga()"
                            >

                                <option value="">
                                    -- Pilih Jenis Diskon --
                                </option>

                                <option
                                    value="percent"
                                    {{ old('discount_type') === 'percent'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Persen (%)
                                </option>

                                <option
                                    value="fixed"
                                    {{ old('discount_type') === 'fixed'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Potongan Nominal ($)
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label for="discount_value">
                                Nilai Diskon
                            </label>

                            <input
                                type="number"
                                id="discount_value"
                                name="discount_value"
                                class="form-control"
                                value="{{ old('discount_value') }}"
                                min="0"
                                step="0.01"
                                placeholder="Contoh: 20"
                                oninput="hitungHarga()"
                            >

                            <span class="help-text">
                                Jika jenis diskon persen,
                                isi 20 untuk diskon 20%.
                                Jika nominal, isi 5 untuk
                                potongan $5.
                            </span>

                        </div>

                    </div>


                    {{-- TANGGAL PROMO --}}

                    <div class="form-row">

                        <div class="form-group">

                            <label for="promo_start">
                                Tanggal Mulai Promo
                            </label>

                            <input
                                type="date"
                                id="promo_start"
                                name="promo_start"
                                class="form-control"
                                value="{{ old('promo_start') }}"
                            >

                            <span class="help-text">
                                Promo akan mulai aktif pada
                                tanggal ini.
                            </span>

                        </div>


                        <div class="form-group">

                            <label for="promo_end">
                                Tanggal Selesai Promo
                            </label>

                            <input
                                type="date"
                                id="promo_end"
                                name="promo_end"
                                class="form-control"
                                value="{{ old('promo_end') }}"
                            >

                            <span class="help-text">
                                Setelah tanggal ini lewat,
                                promo otomatis tidak tampil
                                pada website.
                            </span>

                        </div>

                    </div>


                    {{-- PREVIEW --}}

                    <div class="promo-preview">

                        <div class="promo-preview-title">
                            Preview Harga Promo
                        </div>

                        <span
                            id="normalPricePreview"
                            class="promo-price-normal"
                        >
                            $0.00
                        </span>

                        <span
                            id="promoPricePreview"
                            class="promo-price-final"
                        >
                            $0.00
                        </span>

                        <span
                            id="discountPreview"
                            class="promo-discount-label"
                        >
                            PROMO
                        </span>

                    </div>

                </div>


                {{-- ========================================================
                    DETAIL & SPESIFIKASI
                ========================================================= --}}

                <hr class="section-divider">

                <div class="section-label">

                    <h3>
                        Detail & Spesifikasi Produk
                    </h3>

                    <p>
                        Informasi ini nantinya dapat ditampilkan
                        pada halaman detail produk untuk pelanggan.
                    </p>

                </div>


                <div class="form-group">

                    <label for="description">

                        Deskripsi Produk

                        <span class="optional-label">
                            (Opsional)
                        </span>

                    </label>

                    <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        placeholder="Contoh: Receiver digital dengan kualitas gambar HD, mendukung HDMI dan USB."
                    >{{ old('description') }}</textarea>

                    <span class="help-text">
                        Jelaskan fungsi, keunggulan,
                        atau informasi penting mengenai produk.
                    </span>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="brand">

                            Brand / Merek

                            <span class="optional-label">
                                (Opsional)
                            </span>

                        </label>

                        <input
                            type="text"
                            id="brand"
                            name="brand"
                            class="form-control"
                            value="{{ old('brand') }}"
                            placeholder="Contoh: K-Vision"
                        >

                    </div>


                    <div class="form-group">

                        <label for="model">

                            Model

                            <span class="optional-label">
                                (Opsional)
                            </span>

                        </label>

                        <input
                            type="text"
                            id="model"
                            name="model"
                            class="form-control"
                            value="{{ old('model') }}"
                            placeholder="Contoh: Bromo C2000"
                        >

                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="connectivity">

                            Konektivitas

                            <span class="optional-label">
                                (Opsional)
                            </span>

                        </label>

                        <input
                            type="text"
                            id="connectivity"
                            name="connectivity"
                            class="form-control"
                            value="{{ old('connectivity') }}"
                            placeholder="Contoh: HDMI, USB, AV"
                        >

                        <span class="help-text">
                            Bisa isi lebih dari satu koneksi
                            dipisahkan dengan koma.
                        </span>

                    </div>


                    <div class="form-group">

                        <label for="warranty">

                            Garansi

                            <span class="optional-label">
                                (Opsional)
                            </span>

                        </label>

                        <input
                            type="text"
                            id="warranty"
                            name="warranty"
                            class="form-control"
                            value="{{ old('warranty') }}"
                            placeholder="Contoh: 3 Bulan"
                        >

                    </div>

                </div>


                {{-- ========================================================
                    FOTO PRODUK
                ========================================================= --}}

                <hr class="section-divider">

                <div class="section-label">

                    <h3>
                        Foto Produk
                    </h3>

                    <p>
                        Upload foto yang akan digunakan
                        pada website pelanggan.
                    </p>

                </div>


                <div class="form-group">

                    <label for="image">

                        Foto Produk

                        <span class="optional-label">
                            (Opsional)
                        </span>

                    </label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        onchange="previewImage(event)"
                    >

                    <span class="help-text">
                        Foto akan ditampilkan pada Website Pelanggan.
                        Format JPG, JPEG, PNG atau WEBP.
                        Maksimal 5 MB.
                    </span>


                    <div
                        id="imagePreviewWrapper"
                        class="image-preview-wrapper"
                    >

                        <div class="image-preview-title">
                            Preview Foto
                        </div>

                        <img
                            id="imagePreview"
                            class="image-preview"
                            src=""
                            alt="Preview foto produk"
                        >

                        <div
                            id="imageInfo"
                            class="image-info"
                        ></div>

                    </div>

                </div>


                {{-- ========================================================
                    ACTION
                ========================================================= --}}

                <div class="form-actions">

                    <button
                        type="submit"
                        class="button button-save"
                    >
                        Simpan Produk
                    </button>

                    <a
                        href="{{ route('products.index') }}"
                        class="button button-cancel"
                    >
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </main>

</div>


<script>

    /*
    |--------------------------------------------------------------------------
    | TOGGLE PROMO
    |--------------------------------------------------------------------------
    */

    function togglePromo() {

        const promoCheckbox =
            document.getElementById('is_promo');

        const promoFields =
            document.getElementById('promoFields');

        if (promoCheckbox.checked) {

            promoFields.classList.add('active');

        } else {

            promoFields.classList.remove('active');
        }

        hitungHarga();
    }


    /*
    |--------------------------------------------------------------------------
    | HITUNG HARGA / PROMO
    |--------------------------------------------------------------------------
    */

    function hitungHarga() {

        const hargaBeli =
            parseFloat(
                document.getElementById(
                    'purchase_price'
                ).value
            ) || 0;


        const hargaJual =
            parseFloat(
                document.getElementById(
                    'selling_price'
                ).value
            ) || 0;


        /*
        |--------------------------------------------------------------------------
        | LABA NORMAL
        |--------------------------------------------------------------------------
        */

        const labaNormal =
            hargaJual - hargaBeli;


        document
            .getElementById('profitValue')
            .textContent =
                '$'
                + labaNormal.toFixed(2);


        /*
        |--------------------------------------------------------------------------
        | PROMO
        |--------------------------------------------------------------------------
        */

        const promoCheckbox =
            document.getElementById(
                'is_promo'
            );


        const discountType =
            document.getElementById(
                'discount_type'
            ).value;


        let discountValue =
            parseFloat(
                document.getElementById(
                    'discount_value'
                ).value
            ) || 0;


        let promoPrice =
            hargaJual;


        let discountText =
            'PROMO';


        if (promoCheckbox.checked) {

            /*
            |--------------------------------------------------------------------------
            | DISKON PERSEN
            |--------------------------------------------------------------------------
            */

            if (discountType === 'percent') {

                if (discountValue > 100) {
                    discountValue = 100;
                }

                promoPrice =
                    hargaJual
                    - (
                        hargaJual
                        * discountValue
                        / 100
                    );


                discountText =
                    'PROMO '
                    + discountValue.toFixed(0)
                    + '%';
            }


            /*
            |--------------------------------------------------------------------------
            | DISKON NOMINAL
            |--------------------------------------------------------------------------
            */

            else if (
                discountType === 'fixed'
            ) {

                promoPrice =
                    hargaJual
                    - discountValue;


                discountText =
                    'POTONG $'
                    + discountValue.toFixed(2);
            }
        }


        if (promoPrice < 0) {
            promoPrice = 0;
        }


        document
            .getElementById(
                'normalPricePreview'
            )
            .textContent =
                '$'
                + hargaJual.toFixed(2);


        document
            .getElementById(
                'promoPricePreview'
            )
            .textContent =
                '$'
                + promoPrice.toFixed(2);


        document
            .getElementById(
                'discountPreview'
            )
            .textContent =
                discountText;
    }


    /*
    |--------------------------------------------------------------------------
    | IMAGE PREVIEW
    |--------------------------------------------------------------------------
    */

    function previewImage(event) {

        const input =
            event.target;


        const wrapper =
            document.getElementById(
                'imagePreviewWrapper'
            );


        const preview =
            document.getElementById(
                'imagePreview'
            );


        const info =
            document.getElementById(
                'imageInfo'
            );


        if (
            !input.files
            || !input.files[0]
        ) {

            wrapper.classList.remove(
                'active'
            );

            preview.src = '';

            info.textContent = '';

            return;
        }


        const file =
            input.files[0];


        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];


        if (
            !allowedTypes.includes(
                file.type
            )
        ) {

            alert(
                'Format foto harus JPG, JPEG, PNG atau WEBP.'
            );

            input.value = '';

            wrapper.classList.remove(
                'active'
            );

            preview.src = '';

            info.textContent = '';

            return;
        }


        const maxSize =
            5 * 1024 * 1024;


        if (file.size > maxSize) {

            alert(
                'Ukuran foto maksimal 5 MB.'
            );

            input.value = '';

            wrapper.classList.remove(
                'active'
            );

            preview.src = '';

            info.textContent = '';

            return;
        }


        const reader =
            new FileReader();


        reader.onload =
            function (e) {

                preview.src =
                    e.target.result;


                wrapper.classList.add(
                    'active'
                );


                info.textContent =
                    file.name
                    + ' - '
                    + (
                        file.size
                        / 1024
                        / 1024
                    ).toFixed(2)
                    + ' MB';
            };


        reader.readAsDataURL(
            file
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            togglePromo();

            hitungHarga();
        }
    );

</script>

</body>
</html>