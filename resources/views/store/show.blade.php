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
            --brand-red: #ef2f35;
            --brand-red-dark: #d91f2b;
            --brand-black: #111111;
            --brand-green: #0b8f4d;
            --brand-green-dark: #087a41;
            --soft-red: #fff0f1;
            --soft-gray: #f7f7f7;
            --border: #e8e8e8;
            --text: #202020;
            --muted: #6b7280;
            --white: #ffffff;
            --shadow: 0 10px 28px rgba(0, 0, 0, 0.07);
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
            background: #ffffff;
            color: var(--text);
            overflow-x: hidden;
        }

        img {
            display: block;
            max-width: 100%;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select {
            font: inherit;
        }

        .container {
            width: min(1100px, calc(100% - 28px));
            margin: 0 auto;
        }

        /*
        |--------------------------------------------------------------------------
        | TOP RUNNING TEXT
        |--------------------------------------------------------------------------
        */

        .promo-top {
            position: relative;
            width: 100%;
            height: 42px;
            min-height: 42px;
            overflow: hidden;
            background: #111111;
            border-bottom: 2px solid var(--brand-red);
            color: #ffffff;
        }

        .promo-top-inner {
            position: relative;
            width: 100%;
            height: 42px;
            overflow: hidden;
        }

        .promo-running-track {
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            width: max-content;
            height: 42px;
            white-space: nowrap;
            animation: dulmarRunningText 18s linear infinite;
            will-change: transform;
        }

        .promo-running-item {
            display: inline-flex;
            align-items: center;
            gap: 18px;
            padding-right: 80px;
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .promo-running-item strong,
        .promo-running-dot {
            color: var(--brand-red);
            font-weight: 900;
        }

        @keyframes dulmarRunningText {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .promo-top:hover .promo-running-track {
            animation-play-state: paused;
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN HEADER
        |--------------------------------------------------------------------------
        */

        .main-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #ffffff;
            border-bottom: 1px solid #eeeeee;
        }

        .header-inner {
            min-height: 96px;
            display: grid;
            grid-template-columns: 285px minmax(360px, 1fr) 140px;
            align-items: center;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .brand-logo {
            width: 72px;
            height: 72px;
            flex-shrink: 0;
            overflow: hidden;
            border-radius: 50%;
            background: #ffffff;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-text {
            min-width: 0;
        }

        .brand-text strong {
            display: block;
            color: #1b1b1b;
            font-size: 20px;
            line-height: 1.15;
        }

        .brand-text span {
            display: block;
            margin-top: 5px;
            color: #777777;
            font-size: 11px;
        }

        .header-search {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 170px 48px;
            height: 48px;
            overflow: hidden;
            border: 1px solid #dddddd;
            border-radius: 13px;
            background: #ffffff;
        }

        .header-search:focus-within {
            border-color: var(--brand-red);
            box-shadow: 0 0 0 3px rgba(239, 47, 53, 0.08);
        }

        .header-search input,
        .header-search select {
            min-width: 0;
            padding: 0 14px;
            border: none;
            outline: none;
            background: transparent;
            color: #555555;
            font-size: 12px;
        }

        .header-search select {
            border-left: 1px solid var(--border);
        }

        .header-search button {
            width: 48px;
            border: none;
            background: var(--brand-black);
            color: #ffffff;
            font-size: 17px;
            cursor: pointer;
        }

        .header-search button:hover {
            background: var(--brand-red);
        }

        .whatsapp-header {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border-radius: 999px;
            background: var(--brand-green);
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
        }

        .whatsapp-header:hover {
            background: var(--brand-green-dark);
        }

        /*
        |--------------------------------------------------------------------------
        | NAV
        |--------------------------------------------------------------------------
        */

        .nav-bar {
            background: #ffffff;
            border-top: 1px solid #eeeeee;
            border-bottom: 1px solid #e8e8e8;
        }

        .nav-inner {
            min-height: 47px;
            display: flex;
            align-items: center;
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .nav-inner::-webkit-scrollbar {
            display: none;
        }

        .nav-inner a {
            position: relative;
            flex: 0 0 auto;
            min-height: 47px;
            display: flex;
            align-items: center;
            padding: 0 18px;
            color: #252525;
            font-size: 12px;
            font-weight: 600;
        }

        .nav-inner a:hover,
        .nav-inner a.active {
            color: var(--brand-red);
        }

        .nav-inner a:hover::after,
        .nav-inner a.active::after {
            content: "";
            position: absolute;
            left: 12px;
            right: 12px;
            bottom: 0;
            height: 2px;
            border-radius: 2px;
            background: var(--brand-red);
        }

        /*
        |--------------------------------------------------------------------------
        | DETAIL PAGE HEADER / BREADCRUMB
        |--------------------------------------------------------------------------
        */

        .detail-heading {
            background:
                radial-gradient(
                    circle at right top,
                    rgba(239, 47, 53, 0.12),
                    transparent 28%
                ),
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #fbfbfb 100%
                );
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-heading-inner {
            min-height: 92px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .detail-heading-text small {
            display: block;
            margin-bottom: 4px;
            color: var(--brand-red);
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .detail-heading-text h2 {
            color: #181818;
            font-size: 22px;
            line-height: 1.2;
        }

        .back-link {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-shrink: 0;
            padding: 0 14px;
            border: 1px solid #dedede;
            border-radius: 999px;
            background: #ffffff;
            color: #333333;
            font-size: 11px;
            font-weight: 800;
            transition: .2s ease;
        }

        .back-link:hover {
            border-color: var(--brand-red);
            color: var(--brand-red);
            background: #fffafa;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */

        .wrap {
            width: min(1100px, calc(100% - 28px));
            margin: 0 auto;
            padding: 30px 0 48px;
        }

        .detail-card {
            display: grid;
            grid-template-columns: minmax(380px, 0.98fr) minmax(0, 1.02fr);
            gap: 30px;
            padding: 24px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: #ffffff;
            box-shadow: var(--shadow);
        }

        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        .product-image-box {
            position: relative;
            width: 100%;
            min-height: 430px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid #eeeeee;
            border-radius: 16px;
            background: #f8f8f8;
        }

        .product-image-box img {
            width: 100%;
            height: 100%;
            max-height: 430px;
            object-fit: contain;
            padding: 8px;
        }

        .no-image {
            color: var(--muted);
            font-size: 12px;
        }

        .promo-tag {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 4;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 10px;
            border-radius: 999px;
            background: var(--brand-red);
            color: #ffffff;
            box-shadow: 0 5px 14px rgba(239, 47, 53, 0.22);
            font-size: 9px;
            font-weight: 900;
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT INFO
        |--------------------------------------------------------------------------
        */

        .product-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .category {
            display: inline-flex;
            align-self: flex-start;
            margin-bottom: 10px;
            padding: 5px 9px;
            border-radius: 6px;
            background: var(--soft-red);
            color: var(--brand-red);
            font-size: 9px;
            font-weight: 800;
        }

        .product-title {
            margin-bottom: 8px;
            color: #171717;
            font-size: 30px;
            font-weight: 850;
            line-height: 1.16;
        }

        /*
        |--------------------------------------------------------------------------
        | PROMO INFO
        |--------------------------------------------------------------------------
        */

        .promo-box {
            margin: 12px 0 14px;
            padding: 12px 14px;
            border: 1px solid #f4c5c8;
            border-radius: 12px;
            background: #fff7f7;
        }

        .promo-box-label {
            margin-bottom: 4px;
            color: var(--brand-red);
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .promo-box-title {
            margin-bottom: 4px;
            color: #242424;
            font-size: 15px;
            font-weight: 850;
        }

        .promo-box-description {
            color: #555555;
            font-size: 10.5px;
            line-height: 1.55;
        }

        .promo-period {
            display: inline-flex;
            margin-top: 7px;
            color: #8f2027;
            font-size: 9.5px;
            font-weight: 700;
        }

        /*
        |--------------------------------------------------------------------------
        | PRICE
        |--------------------------------------------------------------------------
        */

        .price {
            margin-bottom: 12px;
        }

        .normal-price {
            color: var(--brand-red);
            font-size: 30px;
            font-weight: 900;
        }

        .promo-price-wrapper {
            display: grid;
            gap: 7px;
            padding: 2px 0;
        }

        .promo-price-row {
            display: grid;
            grid-template-columns: 120px minmax(0, 1fr);
            gap: 10px;
            align-items: center;
        }

        .price-label {
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
        }

        .old-price {
            color: #9ca3af;
            font-size: 13px;
            font-weight: 600;
            text-decoration: line-through;
        }

        .promo-price {
            color: var(--brand-red);
            font-size: 31px;
            font-weight: 900;
        }

        .discount-value {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            min-height: 26px;
            padding: 0 9px;
            border-radius: 999px;
            background: var(--soft-red);
            color: var(--brand-red-dark);
            font-size: 11px;
            font-weight: 900;
        }

        /*
        |--------------------------------------------------------------------------
        | STOCK
        |--------------------------------------------------------------------------
        */

        .stock {
            margin-bottom: 16px;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
        }

        .stock-ok {
            background: #edf9f2;
            color: var(--brand-green);
        }

        .stock-low {
            background: #fff7ed;
            color: #d97706;
        }

        .stock-out {
            background: #fff0f1;
            color: var(--brand-red);
        }

        /*
        |--------------------------------------------------------------------------
        | DESCRIPTION / SPEC
        |--------------------------------------------------------------------------
        */

        .description {
            margin-bottom: 16px;
            padding: 12px 13px;
            border-radius: 10px;
            background: #fafafa;
            color: #4b5563;
            font-size: 11.5px;
            line-height: 1.65;
        }

        .spec-title {
            margin-bottom: 7px;
            color: #262626;
            font-size: 12px;
            font-weight: 850;
        }

        .spec-table {
            margin-bottom: 16px;
            border-top: 1px solid var(--border);
        }

        .spec-row {
            display: grid;
            grid-template-columns: 145px 1fr;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 11px;
        }

        .spec-label {
            color: var(--muted);
        }

        .spec-value {
            color: #262626;
            font-weight: 650;
        }

        /*
        |--------------------------------------------------------------------------
        | ACTION
        |--------------------------------------------------------------------------
        */

        .actions {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 132px;
            gap: 9px;
            margin-top: auto;
            padding-top: 8px;
        }

        .btn-wa,
        .btn-share {
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 16px;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 850;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-wa {
            border: none;
            background: var(--brand-green);
            color: #ffffff;
            box-shadow: 0 5px 14px rgba(11, 143, 77, 0.16);
        }

        .btn-wa:hover {
            background: var(--brand-green-dark);
            transform: translateY(-1px);
        }

        .btn-wa.disabled {
            pointer-events: none;
            background: #a3a3a3;
            box-shadow: none;
        }

        .btn-share {
            border: 1px solid #dddddd;
            background: #ffffff;
            color: #333333;
        }

        .btn-share:hover {
            border-color: var(--brand-red);
            color: var(--brand-red);
            background: #fffafa;
        }

        .action-icon {
            font-size: 12px;
            line-height: 1;
        }

        .footer-note {
            margin-top: 20px;
            color: var(--muted);
            text-align: center;
            font-size: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1050px) {
            .header-inner {
                grid-template-columns: 220px minmax(280px, 1fr) 120px;
            }

            .brand-logo {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 900px) {
            .detail-card {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .product-image-box {
                min-height: 360px;
            }

            .product-image-box img {
                max-height: 360px;
                padding: 8px;
            }
        }

        @media (max-width: 820px) {
            .promo-top {
                height: 36px;
                min-height: 36px;
            }

            .promo-top-inner,
            .promo-running-track {
                height: 36px;
                min-height: 36px;
            }

            .promo-running-item {
                gap: 12px;
                padding-right: 44px;
                font-size: 11px;
            }

            .header-inner {
                grid-template-columns: minmax(0, 1fr) auto;
                gap: 10px;
                padding: 9px 0 10px;
                min-height: auto;
            }

            .brand-logo {
                width: 48px;
                height: 48px;
            }

            .brand-text strong {
                font-size: 15px;
            }

            .brand-text span {
                font-size: 9px;
            }

            .whatsapp-header {
                min-width: 44px;
                min-height: 40px;
                padding: 0 12px;
                font-size: 10px;
            }

            .header-search {
                grid-column: 1 / -1;
                grid-template-columns: minmax(0, 1fr) 130px 44px;
                width: 100%;
                height: 44px;
                border-radius: 11px;
            }

            .header-search input,
            .header-search select {
                padding: 0 11px;
                font-size: 11px;
            }

            .header-search button {
                width: 44px;
            }

            .nav-inner {
                padding-left: 4px;
                padding-right: 20px;
            }

            .nav-inner a {
                min-height: 44px;
                padding: 0 14px;
                font-size: 11px;
            }

            .detail-heading-inner {
                min-height: 78px;
            }

            .detail-heading-text h2 {
                font-size: 19px;
            }
        }

        @media (max-width: 620px) {
            .container,
            .wrap {
                width: calc(100% - 14px);
            }

            .promo-top {
                height: 32px;
                min-height: 32px;
            }

            .promo-top-inner,
            .promo-running-track {
                height: 32px;
                min-height: 32px;
            }

            .promo-running-item {
                padding-right: 32px;
                font-size: 9.5px;
            }

            .brand-logo {
                width: 42px;
                height: 42px;
            }

            .brand-text strong {
                font-size: 13px;
            }

            .brand-text span {
                display: none;
            }

            .whatsapp-header {
                width: 42px;
                min-width: 42px;
                height: 38px;
                min-height: 38px;
                padding: 0;
                font-size: 0;
            }

            .whatsapp-header::after {
                content: "WA";
                font-size: 10px;
                font-weight: 900;
            }

            .header-search {
                grid-template-columns: minmax(0, 1fr) 44px;
                height: 40px;
            }

            .header-search select {
                display: none;
            }

            .nav-inner a {
                min-height: 42px;
                padding: 0 13px;
                font-size: 10px;
            }

            .detail-heading-inner {
                min-height: 72px;
            }

            .detail-heading-text h2 {
                font-size: 17px;
            }

            .back-link {
                min-height: 34px;
                padding: 0 10px;
                font-size: 9px;
            }

            .wrap {
                padding: 18px 0 30px;
            }

            .detail-card {
                gap: 16px;
                padding: 13px;
                border-radius: 14px;
            }

            .product-image-box {
                min-height: 280px;
                border-radius: 12px;
            }

            .product-image-box img {
                max-height: 280px;
                padding: 6px;
            }

            .promo-tag {
                top: 8px;
                right: 8px;
                padding: 6px 8px;
                font-size: 8px;
            }

            .category {
                margin-bottom: 8px;
                font-size: 8px;
            }

            .product-title {
                font-size: 23px;
            }

            .promo-box {
                margin: 10px 0 12px;
                padding: 11px;
            }

            .promo-box-title {
                font-size: 14px;
            }

            .promo-box-description,
            .description {
                font-size: 10.5px;
            }

            .normal-price,
            .promo-price {
                font-size: 24px;
            }

            .promo-price-row {
                grid-template-columns: 105px 1fr;
                gap: 8px;
            }

            .spec-row {
                grid-template-columns: 105px 1fr;
                gap: 8px;
                font-size: 10px;
            }

            .actions {
                grid-template-columns: 1fr;
            }

            .btn-wa,
            .btn-share {
                width: 100%;
                min-height: 42px;
            }
        }

        @media (max-width: 430px) {
            .container,
            .wrap {
                width: calc(100% - 12px);
            }

            .brand-text strong {
                max-width: 155px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .detail-heading-inner {
                align-items: flex-start;
                flex-direction: column;
                justify-content: center;
                gap: 8px;
                padding: 12px 0;
            }

            .back-link {
                align-self: flex-start;
            }

            .product-image-box {
                min-height: 245px;
            }

            .product-image-box img {
                max-height: 245px;
            }

            .product-title {
                font-size: 21px;
            }

            .promo-price-row {
                grid-template-columns: 95px 1fr;
            }

            .spec-row {
                grid-template-columns: 90px 1fr;
            }
        }

        @media (max-width: 350px) {
            .brand-text strong {
                max-width: 125px;
                font-size: 12px;
            }

            .product-image-box {
                min-height: 220px;
            }

            .product-image-box img {
                max-height: 220px;
            }

            .product-title {
                font-size: 19px;
            }

            .promo-price-row,
            .spec-row {
                grid-template-columns: 1fr;
                gap: 4px;
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
    | KATEGORI UNTUK HEADER DETAIL
    |--------------------------------------------------------------------------
    */

    $detailCategories =
        \App\Models\Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->pluck('category');

    $categoryPriority = [
        'Receiver' => 1,
        'TV' => 2,
        'Kabel' => 3,
        'RCA' => 4,
        'Speaker' => 5,
    ];

    $detailCategories =
        $detailCategories
            ->sortBy(function ($item) use ($categoryPriority) {

                if (
                    isset(
                        $categoryPriority[$item]
                    )
                ) {
                    return sprintf(
                        '%03d-%s',
                        $categoryPriority[$item],
                        $item
                    );
                }

                return
                    '999-'
                    . mb_strtolower(
                        $item,
                        'UTF-8'
                    );
            })
            ->values();


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
    | WHATSAPP
    |--------------------------------------------------------------------------
    */

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


{{-- ============================================================
     TOP RUNNING TEXT
============================================================ --}}

<div class="promo-top">

    <div class="promo-top-inner">

        <div class="promo-running-track">

            <div class="promo-running-item">

                <strong>
                    DULMAR SATELLITE STORE
                </strong>

                <span class="promo-running-dot">
                    •
                </span>

                <span>
                    DULMAR SATELLITE STORE
                </span>

                <span class="promo-running-dot">
                    •
                </span>

                <strong>
                    DULMAR SATELLITE STORE
                </strong>

                <span class="promo-running-dot">
                    •
                </span>

                <span>
                    DULMAR SATELLITE STORE
                </span>

                <span class="promo-running-dot">
                    •
                </span>

            </div>

            <div class="promo-running-item">

                <strong>
                    DULMAR SATELLITE STORE
                </strong>

                <span class="promo-running-dot">
                    •
                </span>

                <span>
                    DULMAR SATELLITE STORE
                </span>

                <span class="promo-running-dot">
                    •
                </span>

                <strong>
                    DULMAR SATELLITE STORE
                </strong>

                <span class="promo-running-dot">
                    •
                </span>

                <span>
                    DULMAR SATELLITE STORE
                </span>

                <span class="promo-running-dot">
                    •
                </span>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
     HEADER
============================================================ --}}

<header class="main-header">

    <div class="container header-inner">

        <a
            href="{{ route('store.index') }}"
            class="brand"
        >
            <span class="brand-logo">

                <img
                    src="{{ asset('images/logo-dulmar.jpg') }}"
                    alt="Dulmar Satellite Store"
                >

            </span>

            <span class="brand-text">

                <strong>
                    Dulmar Satellite Store
                </strong>

                <span>
                    Satellite & Electronics
                </span>

            </span>
        </a>


        <form
            action="{{ route('store.index') }}"
            method="GET"
            class="header-search"
        >

            <input
                type="text"
                name="search"
                placeholder="Buka receiver, TV, kabel, speaker..."
            >

            <select name="category">

                <option value="">
                    Kategoria hotu
                </option>

                @foreach (
                    $detailCategories
                    as $categoryItem
                )

                    <option
                        value="{{ $categoryItem }}"
                        {{
                            $category
                            === $categoryItem
                                ? 'selected'
                                : ''
                        }}
                    >
                        {{ $categoryItem }}
                    </option>

                @endforeach

            </select>

            <button type="submit">
                🔍
            </button>

        </form>


        <a
            href="{{ $generalWhatsappUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="whatsapp-header"
        >
            WhatsApp
        </a>

    </div>


    <nav class="nav-bar">

        <div class="container nav-inner">

            <a href="{{ route('store.index') }}">
                Uma
            </a>

            <a href="{{ route('store.index') }}#produtu">
                Produtu
            </a>

            @foreach (
                $detailCategories
                as $categoryItem
            )

                <a
                    href="{{ route(
                        'store.index',
                        [
                            'category' =>
                                $categoryItem
                        ]
                    ) }}"
                    class="{{
                        $category
                        === $categoryItem
                            ? 'active'
                            : ''
                    }}"
                >
                    {{ $categoryItem }}
                </a>

            @endforeach

            <a href="{{ route('store.index') }}#pagamentu">
                Pagamentu
            </a>

            <a href="{{ route('store.index') }}#kontaktu">
                Kontaktu
            </a>

        </div>

    </nav>

</header>


{{-- ============================================================
     DETAIL HEADING
============================================================ --}}

<section class="detail-heading">

    <div class="container detail-heading-inner">

        <div class="detail-heading-text">

            <small>
                Detallu Produtu
            </small>

            <h2>
                {{ $productName }}
            </h2>

        </div>

        <a
            href="{{ route('store.index') }}#produtu"
            class="back-link"
        >
            ← Fila ba Produtu
        </a>

    </div>

</section>


{{-- ============================================================
     PRODUCT DETAIL
============================================================ --}}

<div class="wrap">

    <div class="detail-card">

        {{-- FOTO PRODUK --}}

        <div>

            <div class="product-image-box">

                @if ($promoActive)

                    <span class="promo-tag">

                        PROMO

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

        <div class="product-info">

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
                        Dulmar Online Promo
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
                            {{ $promoPeriodText }}
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

                    <span class="stock-badge stock-ok">
                        ● Disponivel
                    </span>

                @elseif ($stock > 0)

                    <span class="stock-badge stock-low">
                        ● Stok Limitadu
                    </span>

                @else

                    <span class="stock-badge stock-out">
                        ● Stok Hotu
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

            @if (
                !empty($product->brand)
                || !empty($product->model)
                || !empty($product->connectivity)
                || !empty($product->warranty)
            )

                <div class="spec-title">
                    Detallu Produtu
                </div>

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

            @endif


            {{-- ACTION --}}

            <div class="actions">

                <a
                    href="{{
                        $stock > 0
                            ? $productWhatsappUrl
                            : '#'
                    }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn-wa {{ $stock <= 0 ? 'disabled' : '' }}"
                >
                    <span class="action-icon">
                        💬
                    </span>

                    <span>
                        {{
                            $stock > 0
                                ? 'Order via WhatsApp'
                                : 'Stok Hotu'
                        }}
                    </span>
                </a>


                <button
                    type="button"
                    class="btn-share"
                    onclick="shareProduct()"
                >
                    <span class="action-icon">
                        ↗
                    </span>

                    <span>
                        Fahe Produtu
                    </span>
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
