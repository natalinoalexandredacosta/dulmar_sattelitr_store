<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Produk - Dulmar Satellite Store</title>

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
            max-width: 750px;
            padding: 35px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #2563eb;
            outline: none;
        }

        .help-text {
            display: block;
            margin-top: 7px;
            color: #6b7280;
            font-size: 14px;
        }

        .stock-info {
            margin-bottom: 25px;
            padding: 15px 20px;
            border: 1px solid #93c5fd;
            border-radius: 6px;
            background-color: #eff6ff;
            color: #1e40af;
        }

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

        .current-image-box {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #f9fafb;
        }

        .current-image-title {
            margin-bottom: 10px;
            color: #374151;
            font-size: 14px;
            font-weight: bold;
        }

        .current-image {
            display: block;
            width: 100%;
            max-width: 320px;
            height: 220px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            object-fit: cover;
            background-color: #e5e7eb;
        }

        .no-current-image {
            display: flex;
            width: 100%;
            max-width: 320px;
            height: 180px;
            align-items: center;
            justify-content: center;
            border: 1px dashed #9ca3af;
            border-radius: 8px;
            background-color: #f3f4f6;
            color: #6b7280;
            font-size: 14px;
        }

        .image-preview-wrapper {
            display: none;
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #93c5fd;
            border-radius: 8px;
            background-color: #eff6ff;
        }

        .image-preview-wrapper.active {
            display: block;
        }

        .image-preview-title {
            margin-bottom: 10px;
            color: #1e40af;
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

        .button-update {
            background-color: #2563eb;
            color: white;
        }

        .button-update:hover {
            background-color: #1d4ed8;
        }

        .button-cancel {
            background-color: #6b7280;
            color: white;
        }

        .button-cancel:hover {
            background-color: #4b5563;
        }

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

            <h1>Dulmar Satellite Store</h1>

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

                <h2>Edit Produk</h2>

                <p>
                    Perbarui informasi produk yang dipilih.
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

                <div class="stock-info">

                    Stok saat ini:

                    <strong>
                        {{ $product->stock }} unit
                    </strong>.

                    Perubahan stok hanya dilakukan melalui
                    menu Stok Masuk dan Stok Keluar.

                </div>

                <form
                    action="{{ route('products.update', $product) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf
                    @method('PUT')

                    <div class="form-group">

                        <label for="product_name">
                            Nama Produk
                        </label>

                        <input
                            type="text"
                            id="product_name"
                            name="product_name"
                            class="form-control"
                            value="{{ old(
                                'product_name',
                                $product->product_name
                            ) }}"
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
                            value="{{ old(
                                'category',
                                $product->category
                            ) }}"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="purchase_price">
                            Harga Beli
                        </label>

                        <input
                            type="number"
                            id="purchase_price"
                            name="purchase_price"
                            class="form-control"
                            value="{{ old(
                                'purchase_price',
                                $product->purchase_price
                            ) }}"
                            min="0"
                            step="0.01"
                            required
                            oninput="hitungLaba()"
                        >

                        <span class="help-text">
                            Harga pembelian untuk satu unit produk.
                        </span>

                    </div>

                    <div class="form-group">

                        <label for="selling_price">
                            Harga Jual
                        </label>

                        <input
                            type="number"
                            id="selling_price"
                            name="selling_price"
                            class="form-control"
                            value="{{ old(
                                'selling_price',
                                $product->selling_price
                            ) }}"
                            min="0"
                            step="0.01"
                            required
                            oninput="hitungLaba()"
                        >

                        <span class="help-text">
                            Harga penjualan untuk satu unit produk.
                        </span>

                    </div>

                    <div class="profit-preview">

                        Laba per unit:

                        <span id="profitValue">
                            $0.00
                        </span>

                    </div>

                    <div class="form-group">

                        <label>
                            Foto Produk Saat Ini
                        </label>

                        <div class="current-image-box">

                            @if (!empty($product->image))

                                <div class="current-image-title">
                                    Foto yang sedang digunakan
                                </div>

                                <img
                                    src="{{ asset(
                                        'storage/' . $product->image
                                    ) }}"
                                    alt="{{ $product->product_name }}"
                                    class="current-image"
                                >

                            @else

                                <div class="no-current-image">
                                    Produk ini belum memiliki foto.
                                </div>

                            @endif

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="image">
                            Ganti Foto Produk
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
                            Kosongkan jika tidak ingin mengganti foto.
                            Format JPG, JPEG, PNG atau WEBP.
                            Maksimal 5 MB.
                        </span>

                        <div
                            id="imagePreviewWrapper"
                            class="image-preview-wrapper"
                        >

                            <div class="image-preview-title">
                                Preview Foto Baru
                            </div>

                            <img
                                id="imagePreview"
                                class="image-preview"
                                src=""
                                alt="Preview foto baru"
                            >

                            <div
                                id="imageInfo"
                                class="image-info"
                            ></div>

                        </div>

                    </div>

                    <div class="form-actions">

                        <button
                            type="submit"
                            class="button button-update"
                        >
                            Perbarui Produk
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
        function hitungLaba() {
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

            const laba =
                hargaJual - hargaBeli;

            document
                .getElementById('profitValue')
                .textContent =
                    '$' + laba.toFixed(2);
        }

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

        hitungLaba();
    </script>
</body>
</html>