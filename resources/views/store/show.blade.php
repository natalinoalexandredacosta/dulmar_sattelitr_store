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
            --red-dark: #b91c1c;
            --amber: #c07a05;
            --orange: #f97316;
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

        /*
        |--------------------------------------------------------------------------
        | TOPBAR
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        .product-image-box {
            position: relative;
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

        .promo-tag {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 4;
            padding: 8px 11px;
            border-radius: 6px;
            background: var(--red);
            color: white;
            box-shadow:
                0 2px 8px
                rgba(220, 38, 38, 0.25);
            font-size: 12px;
            font-weight: 800;
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT INFO
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | PROMO INFO
        |--------------------------------------------------------------------------
        */

        .promo-box {
            margin: 18px 0;
            padding: 16px;
            border: 1px solid #fed7aa;
            border-radius: 9px;
            background: #fff7ed;
        }

        .promo-box-label {
            margin-bottom: 6px;
            color: #c2410c;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .promo-box-title {
            margin-bottom: 6px;
            color: #9a3412;
            font-size: 17px;
            font-weight: 800;
        }

        .promo-box-description {
            color: #7c2d12;
            font-size: 13px;
            line-height: 1.6;
        }

        .promo-period {
            display: block;
            margin-top: 8px;
            color: #9a3412;
            font-size: 12px;
            font-weight: 700;
        }

        /*
        |--------------------------------------------------------------------------
        | PRICE
        |--------------------------------------------------------------------------
        */

        .price {
            margin-bottom: 16px;
        }

        .normal-price {
            color: var(--blue);
            font-size: 28px;
            font-weight: 800;
        }

        .promo-price-wrapper {
            display: flex;
            flex-direction: column;
            gap: 9px;
            padding: 8px 0;
        }

        .promo-price-row {
            display: grid;
            grid-template-columns: 130px minmax(0, 1fr);
            gap: 12px;
            align-items: center;
        }

        .price-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
        }

        .old-price {
            color: #9ca3af;
            font-size: 16px;
            font-weight: 600;
            text-decoration: line-through;
        }

        .promo-price {
            color: var(--red);
            font-size: 29px;
            font-weight: 800;
        }

        .discount-value {
            color: var(--red-dark);
            font-size: 15px;
            font-weight: 800;
        }

        /*
        |--------------------------------------------------------------------------
        | STOCK
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | DESCRIPTION / SPEC
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | ACTION
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

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

            .normal-price,
            .promo-price {
                font-size: 23px;
            }

            .promo-price-row {
                grid-template-columns: 115px 1fr;
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
    /*
    |--------------------------------------------------------------------------
    | SAPAAN OTOMATIS SESUAI WAKTU TIMOR-LESTE
    |--------------------------------------------------------------------------
    */

    $currentHour =
        now('Asia/Dili')->hour;

    if (
        $currentHour >= 5
        && $currentHour < 12
    ) {
        $greeting = 'Bondia';

    } elseif (
        $currentHour >= 12
        && $currentHour < 18
    ) {
        $greeting = 'Botarde';

    } else {
        $greeting = 'Bonoite';
    }


    /*
    |--------------------------------------------------------------------------
    | DATA PRODUK
    |--------------------------------------------------------------------------
    */

    $storeWhatsapp =
        '67076732586';

    $productName =
        $product->product_name
        ?? 'Produtu';

    $category =
        $product->category
        ?? '-';

    $normalPrice =
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
    | PROMO CAMPAIGN
    |--------------------------------------------------------------------------
    */

    $promoActive =
        isset($activePromoCampaign)
        && $activePromoCampaign !== null
        && isset($campaignPromoProduct)
        && $campaignPromoProduct !== null;

    $discountType = null;
    $discountValue = 0;
    $discountPercentage = 0;

    $finalPrice =
        $normalPrice;

    if ($promoActive) {

        $discountType =
            $campaignPromoProduct
                ->pivot
                ->discount_type;

        $discountValue =
            (float) $campaignPromoProduct
                ->pivot
                ->discount_value;

        if (
            $discountType
            === 'percent'
        ) {

            $discountPercentage =
                $discountValue;

            $finalPrice =
                $normalPrice
                - (
                    $normalPrice
                    * $discountValue
                    / 100
                );

        } else {

            $finalPrice =
                $normalPrice
                - $discountValue;

            if (
                $normalPrice > 0
            ) {
                $discountPercentage =
                    (
                        $discountValue
                        / $normalPrice
                    )
                    * 100;
            }
        }

        if (
            $finalPrice < 0
        ) {
            $finalPrice = 0;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PERIODE PROMO
    |--------------------------------------------------------------------------
    */

    $promoPeriodText = null;

    if ($promoActive) {

        $promoStart =
            $activePromoCampaign->start_date
                ? $activePromoCampaign
                    ->start_date
                    ->format('d/m/Y')
                : null;

        $promoEnd =
            $activePromoCampaign->end_date
                ? $activePromoCampaign
                    ->end_date
                    ->format('d/m/Y')
                : null;

        if (
            $promoStart
            && $promoEnd
        ) {

            if (
                $promoStart
                === $promoEnd
            ) {

                $promoPeriodText =
                    'Promosaun válidu iha loron '
                    . $promoStart
                    . " de'it.";

            } else {

                $promoPeriodText =
                    'Promosaun válidu hosi '
                    . $promoStart
                    . " to'o "
                    . $promoEnd
                    . '.';
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | WHATSAPP MESSAGE
    |--------------------------------------------------------------------------
    */

    $productMessage =
        $greeting
        . " Dulmar Satellite Store,\n\n"
        . "Hau hakarak halo pedidu produtu ida-ne'e:\n\n"
        . "Produtu: {$productName}\n"
        . "Kategoria: {$category}\n";

    if ($promoActive) {

        $productMessage .=
            "Promosaun: "
            . $activePromoCampaign->title
            . "\n";

        $productMessage .=
            "Presu Normal: $"
            . number_format(
                $normalPrice,
                2
            )
            . "\n";

        $productMessage .=
            "Presu Promosaun: $"
            . number_format(
                $finalPrice,
                2
            )
            . "\n";

        if (
            $discountType
            === 'percent'
        ) {

            $productMessage .=
                "Deskontu: "
                . number_format(
                    $discountValue,
                    0
                )
                . "%\n";

        } else {

            $productMessage .=
                "Deskontu: $"
                . number_format(
                    $discountValue,
                    2
                )
                . "\n";
        }

        if (
            $activePromoCampaign->start_date
            && $activePromoCampaign->end_date
        ) {

            $productMessage .=
                "Períodu Promosaun: "
                . $activePromoCampaign
                    ->start_date
                    ->format('d/m/Y')
                . " - "
                . $activePromoCampaign
                    ->end_date
                    ->format('d/m/Y')
                . "\n";
        }

    } else {

        $productMessage .=
            "Presu: $"
            . number_format(
                $normalPrice,
                2
            )
            . "\n";
    }

    $productMessage .=
        "Kuantidade: 1\n\n"
        . "Favor konfirma disponibilidade "
        . "no total pagamentu.\n\n"
        . "Obrigadu.";

    $productWhatsappUrl =
        'https://wa.me/'
        . $storeWhatsapp
        . '?text='
        . urlencode(
            $productMessage
        );
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

        {{-- FOTO PRODUK --}}

        <div>

            <div class="product-image-box">

                @if ($promoActive)

                    <span class="promo-tag">

                        🔥 PROMO

                        @if (
                            $discountType
                            === 'percent'
                        )

                            {{ number_format(
                                $discountValue,
                                0
                            ) }}%

                        @else

                            {{ number_format(
                                $discountPercentage,
                                0
                            ) }}%

                        @endif

                    </span>

                @endif


                @if (
                    !empty(
                        $product->image
                    )
                )

                    <img
                        src="{{ asset(
                            'storage/'
                            . $product->image
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


        {{-- DETAIL PRODUK --}}

        <div>

            @if (
                !empty(
                    $product->category
                )
            )

                <span class="category">
                    {{ $product->category }}
                </span>

            @endif


            <h1 class="product-title">
                {{ $productName }}
            </h1>


            {{-- PROMO CAMPAIGN --}}

            @if ($promoActive)

                <div class="promo-box">

                    <div class="promo-box-label">
                        🔥 Dulmar Online Promo
                    </div>

                    <div class="promo-box-title">
                        {{ $activePromoCampaign->title }}
                    </div>

                    @if (
                        $activePromoCampaign
                            ->description
                    )

                        <div class="promo-box-description">
                            {{ $activePromoCampaign->description }}
                        </div>

                    @endif

                    @if ($promoPeriodText)

                        <span class="promo-period">
                            📅 {{ $promoPeriodText }}
                        </span>

                    @endif

                </div>

            @endif


            {{-- HARGA --}}

            <div class="price">

                @if ($promoActive)

                    <div class="promo-price-wrapper">

                        <div class="promo-price-row">

                            <span class="price-label">
                                Presu Normal
                            </span>

                            <span class="old-price">
                                ${{ number_format(
                                    $normalPrice,
                                    2
                                ) }}
                            </span>

                        </div>


                        <div class="promo-price-row">

                            <span class="price-label">
                                Presu Promosaun
                            </span>

                            <span class="promo-price">
                                ${{ number_format(
                                    $finalPrice,
                                    2
                                ) }}
                            </span>

                        </div>


                        <div class="promo-price-row">

                            <span class="price-label">
                                Deskontu
                            </span>

                            <span class="discount-value">

                                @if (
                                    $discountType
                                    === 'percent'
                                )

                                    {{ number_format(
                                        $discountValue,
                                        0
                                    ) }}%

                                @else

                                    ${{ number_format(
                                        $discountValue,
                                        2
                                    ) }}

                                @endif

                            </span>

                        </div>

                    </div>

                @else

                    <span class="normal-price">
                        ${{ number_format(
                            $normalPrice,
                            2
                        ) }}
                    </span>

                @endif

            </div>


            {{-- STOCK --}}

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


            {{-- DESCRIPTION --}}

            @if (
                !empty(
                    $product->description
                )
            )

                <div class="description">
                    {{ $product->description }}
                </div>

            @endif


            {{-- SPECIFICATION --}}

            <div class="spec-table">

                @if (
                    !empty(
                        $product->brand
                    )
                )

                    <div class="spec-row">

                        <div class="spec-label">
                            Marka
                        </div>

                        <div class="spec-value">
                            {{ $product->brand }}
                        </div>

                    </div>

                @endif


                @if (
                    !empty(
                        $product->model
                    )
                )

                    <div class="spec-row">

                        <div class="spec-label">
                            Modelo
                        </div>

                        <div class="spec-value">
                            {{ $product->model }}
                        </div>

                    </div>

                @endif


                @if (
                    !empty(
                        $product->connectivity
                    )
                )

                    <div class="spec-row">

                        <div class="spec-label">
                            Konektividade
                        </div>

                        <div class="spec-value">
                            {{ $product->connectivity }}
                        </div>

                    </div>

                @endif


                @if (
                    !empty(
                        $product->warranty
                    )
                )

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


            {{-- ACTION --}}

            <div class="actions">

                <a
                    href="{{ $stock > 0
                        ? $productWhatsappUrl
                        : '#'
                    }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn-wa {{ $stock <= 0 ? 'disabled' : '' }}"
                >
                    {{
                        $stock > 0
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
        © {{ date('Y') }}
        Dulmar Satellite Store
    </div>

</div>


<script>
    function shareProduct() {
        const shareData = {
            title:
                @json($productName),

            text:
                @json(
                    'Haree produtu '
                    . $productName
                    . ' iha Dulmar Satellite Store.'
                ),

            url:
                window.location.href
        };

        if (navigator.share) {

            navigator
                .share(shareData)
                .catch(function () {});

        } else if (navigator.clipboard) {

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

        } else {

            alert(
                window.location.href
            );
        }
    }
</script>

</body>
</html>