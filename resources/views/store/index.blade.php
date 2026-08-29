<!DOCTYPE html>
<html lang="tet">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dulmar Satellite Store</title>

    <link
        rel="icon"
        type="image/jpeg"
        href="{{ asset('images/logo-dulmar.jpg') }}"
    >

    <style>
        :root {
            --navy-1: #0b2544;
            --navy-2: #1d4488;
            --blue-link: #2563eb;
            --green: #16a34a;
            --green-dark: #128a3e;
            --amber: #c07a05;
            --red: #dc2626;
            --red-dark: #b91c1c;
            --orange: #f97316;
            --bg: #f3f5f8;
            --card: #ffffff;
            --border: #e3e7ee;
            --text: #1a1f2b;
            --muted: #667085;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
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

        #home,
        #produtu,
        #pagamentu,
        #kontaktu {
            scroll-margin-top: 90px;
        }

        .wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /*
        |--------------------------------------------------------------------------
        | TOPBAR
        |--------------------------------------------------------------------------
        */

        .topbar {
            position: sticky;
            top: 0;
            z-index: 1000;

            background: var(--navy-1);
            color: white;

            box-shadow:
                0 2px 10px
                rgba(0, 0, 0, 0.15);
        }

        .topbar-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 16px 24px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .brand {
            font-size: 17px;
            font-weight: 700;
            white-space: nowrap;
        }

        nav ul {
            display: flex;
            align-items: center;
            gap: 26px;

            list-style: none;
        }

        nav a {
            color: #c9d6ec;
            text-decoration: none;

            font-size: 13.5px;
            font-weight: 500;

            transition: color 0.2s;
        }

        nav a:hover,
        nav a.active {
            color: white;
        }

        .topbar-cta {
            padding: 9px 16px;

            border-radius: 6px;

            background: var(--green);
            color: white;

            text-decoration: none;

            font-size: 13px;
            font-weight: 700;

            white-space: nowrap;

            transition: background 0.2s;
        }

        .topbar-cta:hover {
            background: var(--green-dark);
        }

        /*
        |--------------------------------------------------------------------------
        | HERO
        |--------------------------------------------------------------------------
        */

        .hero {
            padding: 72px 24px 64px;

            background: linear-gradient(
                135deg,
                var(--navy-1),
                var(--navy-2)
            );

            color: white;
            text-align: center;
        }

        .hero h1 {
            margin-bottom: 14px;

            font-size: 34px;
            font-weight: 800;
        }

        .hero p {
            max-width: 680px;
            margin: 0 auto;

            color: #dbe7fa;

            font-size: 14.5px;
            line-height: 1.7;
        }

        /*
        |--------------------------------------------------------------------------
        | TRUST BAR
        |--------------------------------------------------------------------------
        */

        .trust-bar {
            background: var(--navy-2);

            border-top:
                1px solid
                rgba(255, 255, 255, 0.12);
        }

        .trust-bar-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 14px 24px;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 45px;

            flex-wrap: wrap;
        }

        .trust-stat {
            display: flex;
            align-items: center;
            gap: 6px;

            color: white;

            font-size: 13px;
        }

        .trust-stat b {
            font-weight: 700;
        }

        /*
        |--------------------------------------------------------------------------
        | PROMO BANNER
        |--------------------------------------------------------------------------
        */

        .promo-banner-wrap {
            max-width: 1180px;
            margin: 24px auto 0;
            padding: 0 24px;
        }

        .promo-banner {
            position: relative;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;

            overflow: hidden;

            padding: 24px;

            border: 1px solid #fed7aa;
            border-radius: 12px;

            background: linear-gradient(
                135deg,
                #fff7ed,
                #ffedd5
            );

            box-shadow:
                0 4px 14px
                rgba(249, 115, 22, 0.08);
        }

        .promo-banner::after {
            content: "%";

            position: absolute;
            right: 26px;
            top: -35px;

            color:
                rgba(249, 115, 22, 0.08);

            font-size: 135px;
            font-weight: 900;

            pointer-events: none;
        }

        .promo-banner-content {
            position: relative;
            z-index: 2;

            flex: 1;
        }

        .promo-banner-label {
            display: inline-block;

            margin-bottom: 8px;

            color: #c2410c;

            font-size: 12px;
            font-weight: 800;

            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .promo-banner h2 {
            margin-bottom: 8px;

            color: #9a3412;

            font-size: 22px;
            font-weight: 800;
        }

        .promo-banner p {
            max-width: 780px;

            color: #7c2d12;

            font-size: 13px;
            line-height: 1.7;
        }

        .promo-banner p strong {
            font-weight: 800;
        }

        .promo-period {
            display: block;

            margin-top: 8px;

            color: #9a3412;

            font-size: 12px;
            font-weight: 700;
        }

        .promo-banner-button {
            position: relative;
            z-index: 2;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-width: 185px;
            min-height: 42px;

            padding: 10px 16px;

            border-radius: 7px;

            background: var(--red);
            color: white;

            text-decoration: none;

            font-size: 13px;
            font-weight: 700;

            white-space: nowrap;

            transition: background 0.2s;
        }

        .promo-banner-button:hover {
            background: var(--red-dark);
        }

        /*
        |--------------------------------------------------------------------------
        | SECTION
        |--------------------------------------------------------------------------
        */

        section {
            padding: 38px 0;
        }

        #produtu {
            padding-bottom: 48px;
        }

        #pagamentu {
            padding-top: 44px;
        }

        #kontaktu {
            padding-top: 30px;
        }

        .section-title {
            margin-bottom: 5px;

            font-size: 24px;
        }

        .sub {
            margin-bottom: 22px;

            color: var(--muted);

            font-size: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        .search-bar {
            display: grid;

            grid-template-columns:
                minmax(250px, 1fr)
                240px
                auto
                auto;

            gap: 12px;

            align-items: end;

            margin-bottom: 14px;
            padding: 20px;

            border: 1px solid var(--border);
            border-radius: 10px;

            background: var(--card);
        }

        .field label {
            display: block;

            margin-bottom: 6px;

            color: var(--muted);

            font-size: 12px;
            font-weight: 600;
        }

        .field input,
        .field select {
            width: 100%;
            height: 42px;

            padding: 0 12px;

            border: 1px solid var(--border);
            border-radius: 7px;

            background: white;

            font-size: 13px;
        }

        .field input:focus,
        .field select:focus {
            border-color: var(--blue-link);
            outline: none;

            box-shadow:
                0 0 0 2px
                rgba(37, 99, 235, 0.08);
        }

        .btn-primary,
        .btn-ghost {
            height: 42px;

            padding: 0 18px;

            border-radius: 7px;

            font-size: 13px;
            font-weight: 600;

            cursor: pointer;
        }

        .btn-primary {
            border: none;

            background: var(--blue-link);
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-ghost {
            display: flex;
            align-items: center;
            justify-content: center;

            border: 1px solid var(--border);

            background: white;
            color: var(--text);

            text-decoration: none;
        }

        .btn-ghost:hover {
            background: #f8fafc;
        }

        .filter-result {
            margin-top: 6px;
            margin-bottom: 10px;

            color: var(--muted);

            font-size: 13px;
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT SCROLL AREA
        |--------------------------------------------------------------------------
        |
        | Hanya area ini yang mempunyai scrollbar sendiri.
        |
        */

        .product-scroll-area {
            width: 100%;

            height: calc(100vh - 520px);
            min-height: 320px;
            max-height: 520px;

            overflow-y: auto;
            overflow-x: hidden;

            padding:
                8px
                10px
                12px
                0;

            scroll-behavior: smooth;

            overscroll-behavior-y: contain;

            scrollbar-width: thin;
            scrollbar-color:
                #aeb8c6
                #e8edf3;
        }

        /*
        |--------------------------------------------------------------------------
        | SCROLLBAR CHROME / EDGE
        |--------------------------------------------------------------------------
        */

        .product-scroll-area::-webkit-scrollbar {
            width: 9px;
        }

        .product-scroll-area::-webkit-scrollbar-track {
            background: #e8edf3;

            border-radius: 20px;
        }

        .product-scroll-area::-webkit-scrollbar-thumb {
            background: #aeb8c6;

            border-radius: 20px;

            border: 2px solid #e8edf3;
        }

        .product-scroll-area::-webkit-scrollbar-thumb:hover {
            background: #8793a3;
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT GRID
        |--------------------------------------------------------------------------
        */

        .grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 18px;

            align-items: stretch;
        }

        .card {
            display: flex;
            flex-direction: column;

            height: 100%;

            overflow: hidden;

            border: 1px solid var(--border);
            border-radius: 10px;

            background: var(--card);

            box-shadow:
                0 2px 7px
                rgba(0, 0, 0, 0.04);

            transition:
                transform 0.2s,
                box-shadow 0.2s;
        }

        .card.promo-card {
            border-color: #fecaca;
        }

        .card:hover {
            transform: translateY(-3px);

            box-shadow:
                0 7px 18px
                rgba(0, 0, 0, 0.09);
        }

        .thumb-link {
            display: block;

            color: inherit;
            text-decoration: none;
        }

        .thumb {
            position: relative;

            display: flex;

            width: 100%;
            height: 190px;

            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            overflow: hidden;

            background: #e9edf3;
            color: var(--muted);

            font-size: 12px;
        }

        .thumb img {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: cover;

            transition: transform 0.25s;
        }

        .card:hover .thumb img {
            transform: scale(1.025);
        }

        .thumb-tag {
            position: absolute;
            top: 8px;
            left: 8px;
            z-index: 3;

            padding: 4px 7px;

            border-radius: 4px;

            background:
                rgba(0, 0, 0, 0.68);

            color: white;

            font-size: 10px;
            font-weight: 700;
        }

        .promo-tag {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 4;

            padding: 6px 9px;

            border-radius: 5px;

            background: var(--red);
            color: white;

            box-shadow:
                0 2px 6px
                rgba(220, 38, 38, 0.22);

            font-size: 10px;
            font-weight: 800;
        }

        .card-body {
            display: flex;
            flex-direction: column;

            flex: 1;

            padding: 14px;
        }

        .badge {
            display: inline-block;
            align-self: flex-start;

            margin-bottom: 8px;

            padding: 3px 8px;

            border-radius: 5px;

            background: #e8f0fe;
            color: var(--blue-link);

            font-size: 11px;
            font-weight: 600;
        }

        .card-title {
            min-height: 42px;

            margin-bottom: 10px;

            font-size: 14px;
            font-weight: 700;

            line-height: 1.4;
        }

        .card-title a {
            color: var(--text);

            text-decoration: none;
        }

        .card-title a:hover {
            color: var(--blue-link);
        }

        /*
        |--------------------------------------------------------------------------
        | PRICE
        |--------------------------------------------------------------------------
        */

        .price {
            min-height: 82px;

            margin-bottom: 10px;
        }

        .normal-price {
            color: var(--blue-link);

            font-size: 16px;
            font-weight: 700;
        }

        .promo-price-wrapper {
            display: flex;
            flex-direction: column;

            gap: 7px;

            padding: 2px 0;
        }

        .promo-price-row {
            display: grid;

            grid-template-columns:
                105px minmax(0, 1fr);

            align-items: center;

            gap: 8px;
        }

        .old-price-label,
        .promo-price-label,
        .discount-label {
            font-size: 10.5px;
            font-weight: 700;

            line-height: 1.25;
        }

        .old-price-label {
            color: #6b7280;
        }

        .promo-price-label,
        .discount-label {
            color: #991b1b;
        }

        .old-price {
            color: #9ca3af;

            font-size: 12px;
            font-weight: 600;

            text-decoration: line-through;
        }

        .promo-price {
            color: var(--red);

            font-size: 18px;
            font-weight: 800;

            line-height: 1.2;
        }

        .promo-saving {
            color: var(--red-dark);

            font-size: 11px;
            font-weight: 800;
        }

        /*
        |--------------------------------------------------------------------------
        | STOCK
        |--------------------------------------------------------------------------
        */

        .stock-line {
            min-height: 18px;

            margin-bottom: 12px;
        }

        .stock-ok {
            color: var(--green-dark);

            font-size: 12px;
            font-weight: 600;
        }

        .stock-low {
            color: var(--amber);

            font-size: 12px;
            font-weight: 600;
        }

        .stock-out {
            color: var(--red);

            font-size: 12px;
            font-weight: 600;
        }

        /*
        |--------------------------------------------------------------------------
        | CARD ACTION
        |--------------------------------------------------------------------------
        */

        .card-actions {
            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                auto;

            gap: 7px;

            margin-top: auto;
            padding-top: 8px;
        }

        .btn-wa,
        .btn-detail {
            display: flex;
            align-items: center;
            justify-content: center;

            min-height: 40px;

            padding: 9px 12px;

            border-radius: 6px;

            text-align: center;
            text-decoration: none;

            font-size: 12px;
            font-weight: 700;

            transition:
                background 0.2s,
                border-color 0.2s,
                color 0.2s;
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

        .btn-detail {
            min-width: 72px;

            border: 1px solid #cfd6df;

            background: white;
            color: var(--text);
        }

        .btn-detail:hover {
            border-color: var(--blue-link);

            background: #eff6ff;
            color: var(--blue-link);
        }

        /*
        |--------------------------------------------------------------------------
        | EMPTY
        |--------------------------------------------------------------------------
        */

        .empty-state {
            padding: 50px 20px;

            border: 1px solid var(--border);
            border-radius: 10px;

            background: white;

            text-align: center;
        }

        .empty-state h3 {
            margin-bottom: 8px;
        }

        .empty-state p {
            margin-bottom: 18px;

            color: var(--muted);
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT
        |--------------------------------------------------------------------------
        */

        .info-grid {
            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 16px;
        }

        .info-card {
            padding: 20px;

            border: 1px solid var(--border);
            border-radius: 10px;

            background: var(--card);

            box-shadow:
                0 2px 7px
                rgba(0, 0, 0, 0.03);
        }

        .info-card h3 {
            margin-bottom: 12px;

            font-size: 15px;
        }

        .pay-methods {
            display: flex;
            flex-wrap: wrap;

            gap: 8px;
        }

        .pay-chip {
            padding: 7px 11px;

            border-radius: 6px;

            background: #eef1f5;

            font-size: 12px;
            font-weight: 600;
        }

        .step-list {
            list-style: none;

            font-size: 13px;
        }

        .step-list li {
            display: flex;

            gap: 10px;

            padding: 9px 0;

            border-bottom:
                1px solid
                #f0f2f5;

            line-height: 1.5;
        }

        .step-list li:last-child {
            border-bottom: none;
        }

        .step-num {
            display: flex;

            width: 22px;
            height: 22px;

            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            border-radius: 50%;

            background: var(--navy-2);
            color: white;

            font-size: 10px;
            font-weight: 700;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTACT
        |--------------------------------------------------------------------------
        */

        .contact-box {
            padding: 28px;

            border-radius: 10px;

            background: linear-gradient(
                135deg,
                var(--navy-1),
                var(--navy-2)
            );

            color: white;

            text-align: center;
        }

        .contact-box h3 {
            margin-bottom: 8px;

            font-size: 19px;
        }

        .contact-box p {
            max-width: 600px;
            margin: 0 auto 15px;

            color: #dbe7fa;

            font-size: 13px;

            line-height: 1.6;
        }

        .contact-button {
            display: inline-block;

            padding: 10px 18px;

            border-radius: 6px;

            background: var(--green);
            color: white;

            text-decoration: none;

            font-size: 13px;
            font-weight: 700;

            transition: background 0.2s;
        }

        .contact-button:hover {
            background: var(--green-dark);
        }

        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        footer {
            margin-top: 20px;
            padding: 24px;

            background: var(--navy-1);
            color: #c9d6ec;

            text-align: center;

            font-size: 12px;
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1000px) {
            .grid {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

            .search-bar {
                grid-template-columns:
                    1fr 1fr;
            }
        }

        @media (max-width: 760px) {
            .topbar-inner {
                flex-wrap: wrap;
            }

            nav {
                order: 3;

                width: 100%;

                overflow-x: auto;
            }

            nav ul {
                min-width: max-content;

                gap: 18px;

                padding-top: 6px;
            }

            .hero {
                padding: 48px 20px;
            }

            .hero h1 {
                font-size: 29px;
            }

            .trust-bar-inner {
                gap: 18px;

                justify-content: flex-start;
            }

            .promo-banner {
                align-items: flex-start;

                flex-direction: column;
            }

            .promo-banner-button {
                min-width: 0;
            }

            .grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            /*
            |----------------------------------------------------------------------
            | TABLET / HP
            |----------------------------------------------------------------------
            |
            | Di layar kecil kembali ke scroll halaman normal.
            |
            */

            .product-scroll-area {
                height: auto;
                min-height: 0;
                max-height: none;

                overflow: visible;

                padding: 0;

                scrollbar-width: auto;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            .wrap {
                padding: 0 14px;
            }

            .topbar-inner {
                padding: 13px 14px;
            }

            .brand {
                font-size: 15px;
            }

            .topbar-cta {
                padding: 8px 11px;

                font-size: 11px;
            }

            .promo-banner-wrap {
                margin-top: 14px;
                padding: 0 14px;
            }

            .promo-banner {
                padding: 18px;
            }

            .promo-banner h2 {
                font-size: 19px;
            }

            .promo-banner-button {
                width: 100%;
            }

            .search-bar {
                grid-template-columns: 1fr;
            }

            .btn-primary,
            .btn-ghost {
                width: 100%;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .thumb {
                height: 220px;
            }

            .promo-price-row {
                grid-template-columns:
                    110px minmax(0, 1fr);
            }

            .card-actions {
                grid-template-columns:
                    minmax(0, 1fr)
                    90px;
            }

            #home,
            #produtu,
            #pagamentu,
            #kontaktu {
                scroll-margin-top: 125px;
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
    | WHATSAPP STORE
    |--------------------------------------------------------------------------
    */

    $storeWhatsapp =
        '67076732586';

    $generalWhatsappMessage =
        $greeting
        . " Dulmar Satellite Store,\n\n"
        . "Hau hakarak husu informasaun kona-ba produtu sira.";

    $generalWhatsappUrl =
        'https://wa.me/'
        . $storeWhatsapp
        . '?text='
        . urlencode(
            $generalWhatsappMessage
        );


    /*
    |--------------------------------------------------------------------------
    | PROMO CAMPAIGN AKTIF
    |--------------------------------------------------------------------------
    */

    $hasActivePromo =
        isset($activePromoCampaign)
        && $activePromoCampaign !== null
        && isset($campaignPromoProducts)
        && $campaignPromoProducts->isNotEmpty();

    $promoBannerTitle =
        $hasActivePromo
            ? $activePromoCampaign->title
            : null;

    $promoBannerDescription =
        $hasActivePromo
            ? (
                $activePromoCampaign->description
                ?: 'Aproveita presu espesiál ba produtu promosaun sira.'
            )
            : null;

    $promoStartText =
        $hasActivePromo
        && $activePromoCampaign->start_date
            ? $activePromoCampaign
                ->start_date
                ->format('d/m/Y')
            : null;

    $promoEndText =
        $hasActivePromo
        && $activePromoCampaign->end_date
            ? $activePromoCampaign
                ->end_date
                ->format('d/m/Y')
            : null;

    $promoPeriodText = null;

    if (
        $promoStartText
        && $promoEndText
    ) {

        if (
            $promoStartText
            === $promoEndText
        ) {

            $promoPeriodText =
                'Promosaun válidu iha loron '
                . $promoStartText
                . " de'it.";

        } else {

            $promoPeriodText =
                'Promosaun válidu hosi '
                . $promoStartText
                . " to'o "
                . $promoEndText
                . '.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DISKON TERBESAR
    |--------------------------------------------------------------------------
    */

    $maxPromoPercentage = 0;

    if ($hasActivePromo) {

        foreach (
            $campaignPromoProducts
            as $promoProduct
        ) {

            $normalPrice =
                (float)
                $promoProduct->selling_price;

            $discountType =
                $promoProduct
                    ->pivot
                    ->discount_type;

            $discountValue =
                (float)
                $promoProduct
                    ->pivot
                    ->discount_value;

            if (
                $discountType
                === 'percent'
            ) {

                $percentage =
                    $discountValue;

            } elseif (
                $normalPrice > 0
            ) {

                $percentage =
                    (
                        $discountValue
                        / $normalPrice
                    )
                    * 100;

            } else {

                $percentage = 0;
            }

            if (
                $percentage
                > $maxPromoPercentage
            ) {

                $maxPromoPercentage =
                    $percentage;
            }
        }
    }

@endphp


<div class="topbar">

    <div class="topbar-inner">

        <div class="brand">
            Dulmar Satellite Store
        </div>

        <nav>

            <ul>

                <li>
                    <a
                        href="#home"
                        class="active"
                    >
                        Uma
                    </a>
                </li>

                <li>
                    <a href="#produtu">
                        Produtu
                    </a>
                </li>

                <li>
                    <a href="#pagamentu">
                        Pagamentu & Entrega
                    </a>
                </li>

                <li>
                    <a href="#kontaktu">
                        Kontaktu
                    </a>
                </li>

            </ul>

        </nav>

        <a
            class="topbar-cta"
            href="{{ $generalWhatsappUrl }}"
            target="_blank"
            rel="noopener noreferrer"
        >
            WhatsApp
        </a>

    </div>

</div>


<div
    class="hero"
    id="home"
>

    <h1>
        Dulmar Satellite Store
    </h1>

    <p>
        Buka receiver, parabola, kabel, remote,
        TV no sasán eletróniku seluk tan.
        Hili produtu ne'ebé ita hakarak no halo
        pedidu diretamente liuhusi WhatsApp.
    </p>

</div>


<div class="trust-bar">

    <div class="trust-bar-inner">

        <div class="trust-stat">
            ✓
            <b>Produtu Atual</b>
        </div>

        <div class="trust-stat">
            ✓
            <b>Stok Atual</b>
        </div>

        <div class="trust-stat">
            ✓
            <b>Order WhatsApp</b>
        </div>

        <div class="trust-stat">
            ✓
            <b>Servisu 24 Jam</b>
        </div>

    </div>

</div>


@if ($hasActivePromo)

    <div class="promo-banner-wrap">

        <div class="promo-banner">

            <div class="promo-banner-content">

                <div class="promo-banner-label">
                    🔥 Dulmar Online Promo
                </div>

                <h2>
                    {{ $promoBannerTitle }}
                </h2>

                <p>

                    {{ $promoBannerDescription }}

                    @if ($maxPromoPercentage > 0)

                        Aproveita

                        <strong>
                            deskontu to'o
                            {{ number_format(
                                $maxPromoPercentage,
                                0
                            ) }}%
                        </strong>

                        ba produtu promosaun sira.

                    @endif

                    @if ($promoPeriodText)

                        <span class="promo-period">
                            📅 {{ $promoPeriodText }}
                        </span>

                    @endif

                </p>

            </div>


            <a
                href="#produtu"
                class="promo-banner-button"
            >
                Haree Produtu Promosaun
            </a>

        </div>

    </div>

@endif


<div class="wrap">

    <section id="produtu">

        <h2 class="section-title">
            Ami Nia Produtu
        </h2>

        <p class="sub">
            Hili produtu ne'ebé ita presiza
            ho naran ka kategoria.
        </p>


        <form
            action="{{ route('store.index') }}"
            method="GET"
            class="search-bar"
        >

            <div class="field">

                <label for="search">
                    Buka Produtu
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Ex: Receiver, K-Vision, HDMI..."
                >

            </div>


            <div class="field">

                <label for="category">
                    Kategoria
                </label>

                <select
                    id="category"
                    name="category"
                >

                    <option value="">
                        Kategoria hotu
                    </option>

                    @foreach (
                        $categories
                        as $categoryItem
                    )

                        <option
                            value="{{ $categoryItem }}"
                            {{ ($category ?? '') === $categoryItem ? 'selected' : '' }}
                        >
                            {{ $categoryItem }}
                        </option>

                    @endforeach

                </select>

            </div>


            <button
                type="submit"
                class="btn-primary"
            >
                Buka
            </button>


            <a
                href="{{ route('store.index') }}"
                class="btn-ghost"
            >
                Hamoos
            </a>

        </form>


        @if (
            ($search ?? '') !== ''
            || ($category ?? '') !== ''
        )

            <div class="filter-result">

                Rezultadu:

                <strong>
                    {{ $products->count() }}
                </strong>

                produtu.

            </div>

        @endif


        @if ($products->isEmpty())

            <div class="empty-state">

                <h3>
                    Produtu la hetan
                </h3>

                <p>
                    La iha produtu ne'ebé
                    corresponde ho buka ne'e.
                </p>

                <a
                    href="{{ route('store.index') }}"
                    class="btn-primary"
                    style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        text-decoration: none;
                    "
                >
                    Haree Produtu Hotu
                </a>

            </div>

        @else

            {{-- =========================================================
                HANYA AREA INI YANG BISA SCROLL
            ========================================================== --}}

            <div class="product-scroll-area">

                <div class="grid">

                    @foreach (
                        $products
                        as $product
                    )

                        @php

                            $productName =
                                $product->product_name
                                ?? 'Produtu';

                            $productCategory =
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
                            | PROMO PRODUK
                            |--------------------------------------------------------------------------
                            */

                            $campaignProduct =
                                $hasActivePromo
                                    ? $campaignPromoProducts
                                        ->get(
                                            $product->id
                                        )
                                    : null;

                            $promoActive =
                                $campaignProduct
                                !== null;

                            $discountType =
                                $promoActive
                                    ? $campaignProduct
                                        ->pivot
                                        ->discount_type
                                    : null;

                            $discountValue =
                                $promoActive
                                    ? (float)
                                        $campaignProduct
                                            ->pivot
                                            ->discount_value
                                    : 0;


                            /*
                            |--------------------------------------------------------------------------
                            | HITUNG HARGA
                            |--------------------------------------------------------------------------
                            */

                            $finalPrice =
                                $normalPrice;

                            $discountPercentage =
                                0;

                            if ($promoActive) {

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
                            | WHATSAPP PRODUK
                            |--------------------------------------------------------------------------
                            */

                            $productMessage =
                                $greeting
                                . " Dulmar Satellite Store,\n\n"
                                . "Hau hakarak halo pedidu produtu ida-ne'e:\n\n"
                                . "Produtu: {$productName}\n"
                                . "Kategoria: {$productCategory}\n";

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


                        <article
                            class="card {{ $promoActive ? 'promo-card' : '' }}"
                        >

                            <a
                                href="{{ route(
                                    'store.product.show',
                                    $product
                                ) }}"
                                class="thumb-link"
                                aria-label="Haree detallu {{ $productName }}"
                            >

                                <div class="thumb">

                                    @if (
                                        $stock > 0
                                        && $stock <= 5
                                    )

                                        <span class="thumb-tag">
                                            Stok Limitadu
                                        </span>

                                    @endif


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
                                            loading="lazy"
                                        >

                                    @else

                                        Foto la disponivel

                                    @endif

                                </div>

                            </a>


                            <div class="card-body">

                                @if (
                                    !empty(
                                        $product->category
                                    )
                                )

                                    <span class="badge">
                                        {{ $product->category }}
                                    </span>

                                @endif


                                <div class="card-title">

                                    <a
                                        href="{{ route(
                                            'store.product.show',
                                            $product
                                        ) }}"
                                    >
                                        {{ $productName }}
                                    </a>

                                </div>


                                <div class="price">

                                    @if ($promoActive)

                                        <div class="promo-price-wrapper">

                                            <div class="promo-price-row">

                                                <span class="old-price-label">
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

                                                <span class="promo-price-label">
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

                                                <span class="discount-label">
                                                    Deskontu
                                                </span>

                                                <span class="promo-saving">

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


                                <div class="stock-line">

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


                                <div class="card-actions">

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


                                    <a
                                        href="{{ route(
                                            'store.product.show',
                                            $product
                                        ) }}"
                                        class="btn-detail"
                                    >
                                        Detallu
                                    </a>

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            </div>

        @endif

    </section>


    <section id="pagamentu">

        <h2 class="section-title">
            Pagamentu & Entrega
        </h2>

        <p class="sub">
            Informasaun importante antes
            halo pedidu liuhusi WhatsApp.
        </p>


        <div class="info-grid">

            <div class="info-card">

                <h3>
                    Métodu Pagamentu
                </h3>

                <div class="pay-methods">

                    <span class="pay-chip">
                        Dinheiru / Cash
                    </span>

                    <span class="pay-chip">
                        Transferénsia Banku
                    </span>

                </div>

            </div>


            <div class="info-card">

                <h3>
                    Prosesu Order
                </h3>

                <ul class="step-list">

                    <li>

                        <div class="step-num">
                            1
                        </div>

                        <div>
                            Hili produtu no klik
                            "Order via WhatsApp".
                        </div>

                    </li>


                    <li>

                        <div class="step-num">
                            2
                        </div>

                        <div>
                            Konfirma produtu,
                            kuantidade no entrega.
                        </div>

                    </li>


                    <li>

                        <div class="step-num">
                            3
                        </div>

                        <div>
                            Konfirma pagamentu
                            no simu produtu.
                        </div>

                    </li>

                </ul>

            </div>

        </div>

    </section>


    <section id="kontaktu">

        <div class="contact-box">

            <h3>
                Presiza Ajuda?
            </h3>

            <p>
                Kontaktu ami diretamente liuhusi
                WhatsApp atu husu kona-ba produtu,
                presu ka disponibilidade.
            </p>

            <a
                href="{{ $generalWhatsappUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="contact-button"
            >
                Kontaktu via WhatsApp
            </a>

        </div>

    </section>

</div>


<footer>
    © {{ date('Y') }}
    Dulmar Satellite Store
</footer>

</body>

</html>