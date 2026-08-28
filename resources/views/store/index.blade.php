<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dulmar Satellite Store</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-dulmar.jpg') }}">

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

        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            padding: 18px 7%;
            background: #1f2b3a;
            color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
        }

        .hero {
            padding: 70px 7%;
            text-align: center;
            background: linear-gradient(
                135deg,
                #1f2b3a,
                #2563eb
            );
            color: white;
        }

        .hero h1 {
            margin: 0 0 15px;
            font-size: 44px;
        }

        .hero p {
            margin: 0 auto;
            max-width: 700px;
            font-size: 19px;
            line-height: 1.6;
        }

        .content {
            padding: 45px 7%;
        }

        .section-header {
            margin-bottom: 25px;
        }

        .section-header h2 {
            margin: 0 0 8px;
            font-size: 31px;
        }

        .section-header p {
            margin: 0;
            color: #6b7280;
        }

        .filter-card {
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .filter-form {
            display: grid;
            grid-template-columns:
                minmax(250px, 1fr)
                250px
                auto
                auto;
            gap: 12px;
            align-items: end;
        }

        .filter-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: bold;
            color: #374151;
        }

        .filter-control {
            width: 100%;
            height: 44px;
            padding: 0 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            background: white;
            font-size: 15px;
        }

        .filter-control:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow:
                0 0 0 2px
                rgba(37, 99, 235, 0.12);
        }

        .filter-button {
            height: 44px;
            padding: 0 20px;
            border: none;
            border-radius: 7px;
            background: #2563eb;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .filter-button:hover {
            background: #1d4ed8;
        }

        .reset-button {
            display: flex;
            height: 44px;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            border-radius: 7px;
            background: #6b7280;
            color: white;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
        }

        .reset-button:hover {
            background: #4b5563;
        }

        .filter-result {
            margin-top: 14px;
            color: #6b7280;
            font-size: 14px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(
                auto-fill,
                minmax(250px, 1fr)
            );
            gap: 22px;
        }

        .product-card {
            overflow: hidden;
            border-radius: 12px;
            background: white;
            box-shadow:
                0 2px 10px
                rgba(0, 0, 0, 0.08);
            transition:
                transform 0.2s,
                box-shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow:
                0 6px 18px
                rgba(0, 0, 0, 0.12);
        }

        .product-image {
            width: 100%;
            height: 210px;
            overflow: hidden;
            background: #e5e7eb;
        }

        .product-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            display: flex;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 14px;
        }

        .product-body {
            padding: 18px;
        }

        .category {
            display: inline-block;
            margin-bottom: 10px;
            padding: 5px 9px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: bold;
        }

        .product-name {
            margin: 0 0 12px;
            font-size: 21px;
        }

        .price {
            margin-bottom: 10px;
            color: #2563eb;
            font-size: 23px;
            font-weight: bold;
        }

        .stock {
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: bold;
        }

        .stock.available {
            color: #16a34a;
        }

        .stock.low {
            color: #d97706;
        }

        .stock.empty {
            color: #dc2626;
        }

        .whatsapp-button {
            display: block;
            width: 100%;
            padding: 12px;
            border-radius: 7px;
            background: #16a34a;
            color: white;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }

        .whatsapp-button:hover {
            background: #15803d;
        }

        .whatsapp-button.disabled {
            pointer-events: none;
            background: #9ca3af;
            cursor: not-allowed;
        }

        .empty-state {
            padding: 45px;
            border-radius: 10px;
            background: white;
            color: #6b7280;
            text-align: center;
            box-shadow:
                0 2px 10px
                rgba(0, 0, 0, 0.06);
        }

        .empty-state h3 {
            margin: 0 0 10px;
            color: #1f2937;
        }

        .empty-state p {
            margin: 0 0 20px;
        }

        footer {
            margin-top: 40px;
            padding: 25px 7%;
            background: #1f2b3a;
            color: white;
            text-align: center;
        }

        @media (max-width: 950px) {
            .filter-form {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 700px) {
            .navbar {
                padding: 14px 18px;
            }

            .brand {
                font-size: 18px;
            }

            .hero {
                padding: 50px 18px;
            }

            .hero h1 {
                font-size: 32px;
            }

            .hero p {
                font-size: 16px;
            }

            .content {
                padding: 30px 16px;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            .filter-button,
            .reset-button {
                width: 100%;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="brand">
        Dulmar Satellite Store
    </div>
</nav>

<section class="hero">

    <h1>
        Dulmar Satellite Store
    </h1>

    <p>
     Buka receiver, parabola, kabel, remote,
    no TV No sasan cleseluk tan.
    Hili sasán ne'ebé ita hakarak no halo pedidu diretamente liuhusi WhatsApp.

    </p>

</section>

<main class="content">

    <div class="section-header">

        <h2>
            Ami Nia Produtu
        </h2>

        <p>
            Hili produtu ne'ebé ita presiza ho naran ka kategoria.
        </p>

    </div>

    <div class="filter-card">

        <form
            action="{{ route('store.index') }}"
            method="GET"
            class="filter-form"
        >

            <div class="filter-group">

                <label for="search">
                    Cari Produk
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    class="filter-control"
                    value="{{ $search }}"
                    placeholder="Ex: Receiver, K-Vision, HDMI..."
                >

            </div>

            <div class="filter-group">

                <label for="category">
                    Kategoria
                </label>

                <select
                    id="category"
                    name="category"
                    class="filter-control"
                >

                    <option value="">
                        Kategoria sira
                    </option>

                    @foreach ($categories as $categoryItem)

                        <option
                            value="{{ $categoryItem }}"
                            {{ $category === $categoryItem
                                ? 'selected'
                                : ''
                            }}
                        >
                            {{ $categoryItem }}
                        </option>

                    @endforeach

                </select>

            </div>

            <button
                type="submit"
                class="filter-button"
            >
                buka
            </button>

            <a
                href="{{ route('store.index') }}"
                class="reset-button"
            >
                Hamoos
            </a>

        </form>

        @if ($search !== '' || $category !== '')

            <div class="filter-result">

                Ditemukan
                <strong>
                    {{ $products->count() }}
                </strong>
                produtu

                @if ($search !== '')
                    dengan pencarian
                    "<strong>{{ $search }}</strong>"
                @endif

                @if ($category !== '')
                    pada kategori
                    "<strong>{{ $category }}</strong>"
                @endif

                .

            </div>

        @endif

    </div>

    @if ($products->isEmpty())

        <div class="empty-state">

            <h3>
                Produk tidak ditemukan
            </h3>

            <p>
                Tidak ada produk yang sesuai dengan
                pencarian atau kategori yang dipilih.
            </p>

            <a
                href="{{ route('store.index') }}"
                class="reset-button"
                style="display: inline-flex;"
            >
                hamosu Produtu hotu
            </a>

        </div>

    @else

        <div class="product-grid">

            @foreach ($products as $product)

                @php

                    $productName =
                        $product->product_name ?? 'Produk';

                    $productCategory =
                        $product->category ?? '-';

                    $price =
                        (float) (
                            $product->selling_price
                            ?? 0
                        );

                    $stock =
                        (int) (
                            $product->stock
                            ?? 0
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | WhatsApp Dulmar Satellite Store
                    |--------------------------------------------------------------------------
                    */

                    $whatsappNumber =
                        '67076732586';

                    $whatsappMessage =
                        "Halo Dulmar Satellite Store,\n\n"
                        . "Saya tertarik dengan produk berikut:\n\n"
                        . "Produk: {$productName}\n"
                        . "Kategori: {$productCategory}\n"
                        . "Harga: $"
                        . number_format($price, 2)
                        . "\n"
                        . "Jumlah yang ingin dipesan: 1\n\n"
                        . "Mohon konfirmasi ketersediaan produk "
                        . "dan total pembayaran.\n\n"
                        . "Terima kasih.";

                    $whatsappUrl =
                        'https://wa.me/'
                        . $whatsappNumber
                        . '?text='
                        . urlencode(
                            $whatsappMessage
                        );

                @endphp

                <article class="product-card">

                    <div class="product-image">

                        @if (!empty($product->image))

                            <img
                                src="{{ asset(
                                    'storage/'
                                    . $product->image
                                ) }}"
                                alt="{{ $productName }}"
                            >

                        @else

                            <div class="no-image">
                                Foto belum tersedia
                            </div>

                        @endif

                    </div>

                    <div class="product-body">

                        @if (!empty($product->category))

                            <span class="category">
                                {{ $product->category }}
                            </span>

                        @endif

                        <h3 class="product-name">
                            {{ $productName }}
                        </h3>

                        <div class="price">
                            ${{ number_format(
                                $price,
                                2
                            ) }}
                        </div>

                        @if ($stock > 5)

                            <div class="stock available">
                                ✓ Tersedia
                            </div>

                        @elseif ($stock > 0)

                            <div class="stock low">
                                ⚠ Stok Terbatas
                            </div>

                        @else

                            <div class="stock empty">
                                Stok Habis
                            </div>

                        @endif

                        <a
                            href="{{ $stock > 0
                                ? $whatsappUrl
                                : '#'
                            }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="whatsapp-button
                                {{ $stock <= 0
                                    ? 'disabled'
                                    : ''
                                }}"
                        >
                            {{ $stock > 0
                                ? 'Order via WhatsApp'
                                : 'Stok Habis'
                            }}
                        </a>

                    </div>

                </article>

            @endforeach

        </div>

    @endif

</main>

<footer>
    © {{ date('Y') }}
    Dulmar Satellite Store
</footer>

</body>
</html>