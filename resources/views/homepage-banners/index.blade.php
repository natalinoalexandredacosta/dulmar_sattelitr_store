<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Homepage Banner - Dulmar Satellite Store</title>

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

        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .page-title h1 {
            margin: 0 0 7px;
            font-size: 28px;
        }

        .page-title p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 10px 16px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-success:hover {
            background: #15803d;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-success {
            border: 1px solid #bbf7d0;
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            border: 1px solid #fecaca;
            background: #fee2e2;
            color: #991b1b;
        }

        .card {
            margin-bottom: 24px;
            padding: 22px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: white;
        }

        .card-title {
            margin: 0 0 18px;
            font-size: 18px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 160px;
            gap: 16px;
            align-items: end;
        }

        .form-group {
            min-width: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 700;
        }

        .form-control {
            width: 100%;
            min-height: 42px;
            padding: 9px 11px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            background: white;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.10);
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
        }

        .checkbox-row input {
            width: 18px;
            height: 18px;
        }

        .checkbox-row label {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
        }

        .upload-note {
            margin-top: 7px;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.5;
        }

        .banner-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .banner-card {
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: white;
        }

        .banner-image {
            position: relative;
            width: 100%;
            height: 190px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .banner-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
        }

        .status-active {
            background: #16a34a;
            color: white;
        }

        .status-inactive {
            background: #6b7280;
            color: white;
        }

        .banner-content {
            padding: 16px;
        }

        .banner-title {
            margin-bottom: 10px;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.4;
            word-break: break-word;
        }

        .banner-meta {
            margin-bottom: 14px;
            color: #6b7280;
            font-size: 12px;
        }

        .banner-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .banner-actions form {
            margin: 0;
        }

        .banner-actions .btn {
            width: 100%;
        }

        .edit-form {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }

        .edit-form-grid {
            display: grid;
            grid-template-columns: 1fr 100px;
            gap: 10px;
        }

        .edit-image {
            margin-top: 10px;
        }

        .edit-buttons {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .empty-state {
            padding: 50px 20px;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            background: #f8fafc;
            text-align: center;
        }

        .empty-state h3 {
            margin: 0 0 8px;
        }

        .empty-state p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }

            .banner-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .page-container {
                padding: 20px 14px;
            }

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .header-actions {
                width: 100%;
            }

            .header-actions .btn {
                flex: 1;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .banner-grid {
                grid-template-columns: 1fr;
            }

            .banner-image {
                height: 220px;
            }

            .edit-form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="page-container">

    <div class="page-header">

        <div class="page-title">
            <h1>Homepage Banner</h1>

            <p>
                Upload dan atur gambar yang akan tampil bergantian
                di halaman utama website.
            </p>
        </div>

        <div class="header-actions">

            <a
                href="{{ route('dashboard') }}"
                class="btn btn-secondary"
            >
                ← Dashboard
            </a>

            <a
                href="{{ route('store.index') }}"
                target="_blank"
                class="btn btn-primary"
            >
                Lihat Homepage
            </a>

        </div>

    </div>


    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if ($errors->any())

        <div class="alert alert-error">

            <strong>Terjadi kesalahan:</strong>

            <ul>
                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach
            </ul>

        </div>

    @endif


    <div class="card">

        <h2 class="card-title">
            Tambah Banner Baru
        </h2>

        <form
            action="{{ route('homepage-banners.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="form-grid">

                <div class="form-group">

                    <label for="title">
                        Judul Banner
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        class="form-control"
                        value="{{ old('title') }}"
                        placeholder="Opsional"
                    >

                </div>


                <div class="form-group">

                    <label for="image">
                        Gambar Banner
                    </label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        required
                    >

                    <div class="upload-note">
                        JPG, JPEG, PNG atau WEBP.
                        Maksimal 5 MB.
                    </div>

                </div>


                <div class="form-group">

                    <label for="sort_order">
                        Urutan
                    </label>

                    <input
                        type="number"
                        id="sort_order"
                        name="sort_order"
                        class="form-control"
                        value="{{ old('sort_order', 0) }}"
                        min="0"
                    >

                </div>

            </div>


            <div class="checkbox-row">

                <input
                    type="checkbox"
                    id="is_active"
                    name="is_active"
                    value="1"
                    checked
                >

                <label for="is_active">
                    Langsung tampil di homepage
                </label>

            </div>


            <div style="margin-top:18px;">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Upload Banner
                </button>

            </div>

        </form>

    </div>


    <div class="card">

        <h2 class="card-title">
            Daftar Banner
        </h2>


        @if ($banners->isEmpty())

            <div class="empty-state">

                <h3>
                    Belum ada banner
                </h3>

                <p>
                    Upload gambar pertama menggunakan form di atas.
                </p>

            </div>

        @else

            <div class="banner-grid">

                @foreach ($banners as $banner)

                    <div class="banner-card">

                        <div class="banner-image">

                            <img
                                src="{{ asset('storage/' . $banner->image) }}"
                                alt="{{ $banner->title ?? 'Homepage Banner' }}"
                            >

                            <span
                                class="
                                    status-badge
                                    {{ $banner->is_active
                                        ? 'status-active'
                                        : 'status-inactive'
                                    }}
                                "
                            >

                                {{ $banner->is_active
                                    ? 'AKTIF'
                                    : 'NONAKTIF'
                                }}

                            </span>

                        </div>


                        <div class="banner-content">

                            <div class="banner-title">

                                {{ $banner->title ?: 'Tanpa Judul' }}

                            </div>


                            <div class="banner-meta">

                                Urutan:
                                <strong>
                                    {{ $banner->sort_order }}
                                </strong>

                                &nbsp; • &nbsp;

                                ID:
                                {{ $banner->id }}

                            </div>


                            <div class="banner-actions">

                                <form
                                    action="{{ route(
                                        'homepage-banners.toggle',
                                        $banner
                                    ) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="
                                            btn
                                            {{ $banner->is_active
                                                ? 'btn-warning'
                                                : 'btn-success'
                                            }}
                                        "
                                    >

                                        {{ $banner->is_active
                                            ? 'Nonaktifkan'
                                            : 'Aktifkan'
                                        }}

                                    </button>

                                </form>


                                <form
                                    action="{{ route(
                                        'homepage-banners.destroy',
                                        $banner
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Yakin ingin menghapus banner ini?'
                                    );"
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

                            </div>


                            <div class="edit-form">

                                <form
                                    action="{{ route(
                                        'homepage-banners.update',
                                        $banner
                                    ) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                >

                                    @csrf
                                    @method('PUT')


                                    <div class="edit-form-grid">

                                        <div class="form-group">

                                            <label>
                                                Judul
                                            </label>

                                            <input
                                                type="text"
                                                name="title"
                                                class="form-control"
                                                value="{{ $banner->title }}"
                                                placeholder="Opsional"
                                            >

                                        </div>


                                        <div class="form-group">

                                            <label>
                                                Urutan
                                            </label>

                                            <input
                                                type="number"
                                                name="sort_order"
                                                class="form-control"
                                                value="{{ $banner->sort_order }}"
                                                min="0"
                                                required
                                            >

                                        </div>

                                    </div>


                                    <div class="form-group edit-image">

                                        <label>
                                            Ganti Gambar
                                        </label>

                                        <input
                                            type="file"
                                            name="image"
                                            class="form-control"
                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                        >

                                        <div class="upload-note">
                                            Kosongkan jika gambar tidak ingin diganti.
                                        </div>

                                    </div>


                                    <input
                                        type="hidden"
                                        name="is_active"
                                        value="{{ $banner->is_active ? 1 : 0 }}"
                                    >


                                    <div class="edit-buttons">

                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                        >
                                            Simpan Perubahan
                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

</div>

</body>

</html>