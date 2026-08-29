<!DOCTYPE html>
<html lang="tet">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $product->product_name }} - Dulmar Satellite Store
    </title>

    <link
        rel="icon"
        type="image/jpeg"
        href="{{ asset('images/logo-dulmar.jpg') }}"
    >

    <style>
        :root {
            --navy-1: #0b2544;
            --navy-2: #1d4488;
            --blue: #2563eb;
            --green: #16a34a;
            --green-dark: #128a3e;
            --red: #dc2626;
            --amber: #c07a05;
            --bg: #f3f5f8;
            --card: #ffffff;
            --border: #dfe5ec;
            --text: #172033;
            --muted: #667085;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background: var(--bg);
            color: var(--text);
        }

        .topbar {
            background: var(--navy-1);
            color: white;
        }

        .topbar-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 16px 24px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            color: white;
            text-decoration: none;
            font-size: 17px;
            font-weight: 700;
        }

        .back-link {
            padding: 9px 14px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 6px;

            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .back-link:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 34px 24px 50px;
        }

        .detail-card {
            display: grid;
            grid-template-columns: 380px minmax(0, 1fr);
            gap: 36px;

            padding: 28px;

            border: 1px solid var(--border);
            border-radius: 12px;

            background: var(--card);

            box-shadow:
                0 2px 10px
                rgba(0, 0, 0, 0.04);
        }

        .product-image-box {
            width: 100%;
            height: 360px;

            display: flex;
            align-items: center;
            justify-content: center;

            overflow: hidden;

            border-radius: 10px;
            background: #eef1f5;
        }

        .product-image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            color: var(--muted);
            font-size: 14px;
        }

        .category {
            display: inline-block;

            margin-bottom: 16px;
            padding: 5px 10px;

            border-radius: 5px;

            background: #e8f0fe;
            color: var(--blue);

            font-size: 12px;
            font-weight: 600;
        }

        .product-title {
            margin-bottom: 10px;

            font-size: 28px;
            font-weight: 800;
            line-height: 1.25;
        }

        .price {
            margin-bottom: 12px;

            color: var(--blue);

            font-size: 28px;
            font-weight: 800;
        }

        .stock {
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .stock-ok {
            color: var(--green-dark);
        }

        .stock-low {
            color: var(--amber);
        }

        .stock-out {
            color: var(--red);
        }

        .description {
            margin-bottom: 22px;

            color: #4b5563;

            font-size: 14px;
            line-height: 1.7;
        }

        .spec-table {
            margin-bottom: 20px;

            border-top: 1px solid var(--border);
        }

        .spec-row {
            display: grid;
            grid-template-columns: 190px 1fr;

            gap: 16px;

            padding: 12px 0;

            border-bottom: 1px solid var(--border);

            font-size: 13px;
        }

        .spec-label {
            color: var(--muted);
        }

        .spec-value {
            color: var(--text);
            font-weight: 500;
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr auto;

            gap: 10px;

            margin-top: 20px;
        }

        .btn-wa,
        .btn-share {
            min-height: 46px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0 18px;

            border-radius: 7px;

            text-decoration: none;
            font-size: 13px;
            font-weight: 700;

            cursor: pointer;
        }

        .btn-wa {
            border: none;
            background: var(--green);
            color: white;
        }

        .btn-wa:hover {
            background: var(--green-dark);
        }

        .btn-wa.disabled {
            pointer-events: none;
            background: #9ca3af;
        }

        .btn-share {
            border: 1px solid var(--border);
            background: white;
            color: var(--text);
        }

        .btn-share:hover {
            background: #f8fafc;
        }

        .footer-note {
            margin-top: 20px;

            color: var(--muted);

            text-align: center;
            font-size: 12px;
        }

        @media (max-width: 850px) {
            .detail-card {
                grid-template-columns: 1fr;
            }

            .product-image-box {
                height: 320px;
            }
        }

        @media (max-width: 560px) {
            .wrap {
                padding: 20px 14px 35px;
            }

            .topbar-inner {
                padding: 13px 14px;
            }

            .detail-card {
                padding: 18px;
                gap: 22px;
            }

            .product-image-box {
                height: 270px;
            }

            .product-title {
                font-size: 22px;
            }

            .price {
                font-size: 23px;
            }

            .spec-row {
                grid-template-columns: 120px 1fr;
                gap: 10px;
            }

            .actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

@php
    $storeWhatsapp = '67076732586';

    $productName =
        $product->product_name
        ?? 'Produtu';

    $category =
        $product->category
        ?? '-';

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

    $productMessage =
        "Bondia Dulmar Satellite Store,\n\n"
        . "Hau hakarak halo pedidu produtu ida-ne'e:\n\n"
        . "Produtu: {$productName}\n"
        . "Kategoria: {$category}\n"
        . "Presu: $"
        . number_format($price, 2)
        . "\n"
        . "Kuantidade: 1\n\n"
        . "Favor konfirma disponibilidade no total pagamentu.\n\n"
        . "Obrigadu.";

    $productWhatsappUrl =
        'https://wa.me/'
        . $storeWhatsapp
        . '?text='
        . urlencode($productMessage);
@endphp


<div class="topbar">

    <div class="topbar-inner">

        <a
            href="{{ route('store.index') }}"
            class="brand"
        >
            Dulmar Satellite Store
        </a>

        <a
            href="{{ route('store.index') }}"
            class="back-link"
        >
            ← Fila ba Produtu
        </a>

    </div>

</div>


<div class="wrap">

    <div class="detail-card">


        {{-- FOTO --}}

        <div>

            <div class="product-image-box">

                @if (!empty($product->image))

                    <img
                        src="{{ asset(
                            'storage/' . $product->image
                        ) }}"
                        alt="{{ $productName }}"
                    >

                @else

                    <div class="no-image">
                        Foto la disponivel
                    </div>

                @endif

            </div>

        </div>


        {{-- DETAIL --}}

        <div>

            @if (!empty($product->category))

                <span class="category">
                    {{ $product->category }}
                </span>

            @endif


            <h1 class="product-title">
                {{ $productName }}
            </h1>


            <div class="price">
                ${{ number_format($price, 2) }}
            </div>


            <div class="stock">

                @if ($stock > 5)

                    <span class="stock-ok">
                        ✓ Disponivel
                    </span>

                @elseif ($stock > 0)

                    <span class="stock-low">
                        ⚠ Stok Limitadu
                    </span>

                @else

                    <span class="stock-out">
                        Stok Hotu
                    </span>

                @endif

            </div>


            @if (!empty($product->description))

                <div class="description">
                    {{ $product->description }}
                </div>

            @endif


            <div class="spec-table">

                @if (!empty($product->brand))

                    <div class="spec-row">

                        <div class="spec-label">
                            Marka
                        </div>

                        <div class="spec-value">
                            {{ $product->brand }}
                        </div>

                    </div>

                @endif


                @if (!empty($product->model))

                    <div class="spec-row">

                        <div class="spec-label">
                            Modelo
                        </div>

                        <div class="spec-value">
                            {{ $product->model }}
                        </div>

                    </div>

                @endif


                @if (!empty($product->connectivity))

                    <div class="spec-row">

                        <div class="spec-label">
                            Konektividade
                        </div>

                        <div class="spec-value">
                            {{ $product->connectivity }}
                        </div>

                    </div>

                @endif


                @if (!empty($product->warranty))

                    <div class="spec-row">

                        <div class="spec-label">
                            Garantia
                        </div>

                        <div class="spec-value">
                            {{ $product->warranty }}
                        </div>

                    </div>

                @endif

            </div>


            <div class="actions">

                <a
                    href="{{ $stock > 0
                        ? $productWhatsappUrl
                        : '#'
                    }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn-wa
                        {{ $stock <= 0
                            ? 'disabled'
                            : ''
                        }}"
                >
                    {{ $stock > 0
                        ? 'Order via WhatsApp'
                        : 'Stok Hotu'
                    }}
                </a>


                <button
                    type="button"
                    class="btn-share"
                    onclick="shareProduct()"
                >
                    Fahe Produtu
                </button>

            </div>

        </div>

    </div>


    <div class="footer-note">
        © {{ date('Y') }} Dulmar Satellite Store
    </div>

</div>


<script>
    function shareProduct() {
        const shareData = {
            title: @json($productName),
            text: @json(
                'Haree produtu '
                . $productName
                . ' iha Dulmar Satellite Store.'
            ),
            url: window.location.href
        };

        if (navigator.share) {
            navigator
                .share(shareData)
                .catch(function () {});
        } else {
            navigator.clipboard
                .writeText(
                    window.location.href
                )
                .then(function () {
                    alert(
                        'Link produtu kopia ona.'
                    );
                })
                .catch(function () {
                    alert(
                        window.location.href
                    );
                });
        }
    }
</script>

</body>
</html>