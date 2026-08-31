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
            --navy: #111111;
            --navy-dark: #0b0b0b;
            --blue: #ef3340;
            --blue-2: #d91f2b;
            --cyan: #ff5a64;
            --green: #16a34a;
            --green-dark: #15803d;
            --red: #ef3340;
            --orange: #ef3340;
            --bg: #f7f7f7;
            --white: #ffffff;
            --border: #e5e7eb;
            --text: #171717;
            --muted: #6b6b6b;
            --shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
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

        img {
            display: block;
            max-width: 100%;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        #home,
        #produtu,
        #pagamentu,
        #kontaktu {
            scroll-margin-top: 120px;
        }

        .container {
            width: min(1200px, calc(100% - 30px));
            margin: 0 auto;
        }


        /*
        |--------------------------------------------------------------------------
        | TOP RUNNING TEXT
        |--------------------------------------------------------------------------
        */

        .promo-top {
            position: relative;

            display: block !important;

            width: 100%;
            height: 42px;
            min-height: 42px;

            overflow: hidden;

            background: #111111;

            border-bottom: 2px solid #ef2f35;

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

            font-size: 14px;
            font-weight: 800;

            letter-spacing: 1.2px;
        }

        .promo-running-item strong {
            color: #ef2f35;

            font-size: 14px;
            font-weight: 900;
        }

        .promo-running-dot {
            color: #ef2f35;

            font-size: 15px;
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

        .promo-top:hover
        .promo-running-track {
            animation-play-state: paused;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .main-header {
            position: sticky;
            top: 0;
            z-index: 1000;

            background: rgba(255,255,255,0.96);

            border-bottom: 1px solid var(--border);

            backdrop-filter: blur(8px);
        }

        .header-inner {
            min-height: 72px;

            display: grid;

            grid-template-columns:
                245px
                minmax(300px, 1fr)
                auto;

            align-items: center;

            gap: 25px;
        }

        .brand {
            display: flex;
            align-items: center;

            gap: 11px;
        }

        .brand-logo {
            width: 44px;
            height: 44px;

            overflow: hidden;

            border-radius: 50%;

            background: white;

            border: 2px solid #ef3340;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;

            object-fit: cover;
        }

        .brand-text strong {
            display: block;

            color: var(--navy);

            font-size: 16px;
        }

        .brand-text span {
            display: block;

            margin-top: 2px;

            color: var(--muted);

            font-size: 10px;
        }


        /*
        |--------------------------------------------------------------------------
        | SEARCH HEADER
        |--------------------------------------------------------------------------
        */

        .header-search {
            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                170px
                48px;

            height: 44px;

            overflow: hidden;

            border: 1px solid #d8dee8;
            border-radius: 22px;

            background: white;
        }

        .header-search input,
        .header-search select {
            min-width: 0;

            padding: 0 14px;

            border: none;
            outline: none;

            background: transparent;

            font-size: 12px;
        }

        .header-search select {
            border-left: 1px solid var(--border);

            color: #475467;
        }

        .header-search button {
            border: none;

            background: #ef3340;
            color: white;

            font-size: 16px;

            cursor: pointer;
        }

        .whatsapp-header {
            display: inline-flex;

            min-height: 42px;

            align-items: center;
            justify-content: center;

            padding: 0 16px;

            border-radius: 22px;

            background: var(--green);
            color: white;

            font-size: 12px;
            font-weight: 800;
        }

        .whatsapp-header:hover {
            background: var(--green-dark);
        }


        /*
        |--------------------------------------------------------------------------
        | NAV
        |--------------------------------------------------------------------------
        */

        .nav-bar {
            background: #1f2937;
            border-bottom: 3px solid #ef3340;
        }

        .nav-inner {
            min-height: 44px;

            display: flex;
            align-items: center;

            overflow-x: auto;

            scrollbar-width: none;
        }

        .nav-inner::-webkit-scrollbar {
            display: none;
        }

        .nav-inner a {
            flex-shrink: 0;

            display: flex;
            align-items: center;

            min-height: 44px;

            padding: 0 16px;

            color: #202020;

            border-right:
                1px solid
                rgba(255,255,255,0.08);

            font-size: 11px;
            font-weight: 700;
        }

        .nav-inner a:hover {
            background: #374151;
            color: #ffffff;
        }

        .nav-inner a.active {
            background: #d97706;
            color: white;
        }


        /*
        |--------------------------------------------------------------------------
        | HERO
        |--------------------------------------------------------------------------
        */

        .hero {
            position: relative;
            overflow: hidden;
            padding: 26px 0 42px;
            background:
                radial-gradient(circle at top left, rgba(245, 158, 11, 0.12), transparent 28%),
                radial-gradient(circle at bottom right, rgba(245, 158, 11, 0.10), transparent 30%),
                linear-gradient(135deg, #0b1220 0%, #111827 55%, #1f2937 100%);
            color: white;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            left: -110px;
            bottom: -140px;
            border-radius: 50%;
            background: rgba(245, 158, 11, 0.08);
            z-index: 1;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            right: -80px;
            top: -70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
            z-index: 1;
        }

        .hero-inner {
            position: relative;
            z-index: 3;
            min-height: 390px;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(420px, 0.95fr);
            align-items: center;
            gap: 28px;
        }

        .hero-copy {
            padding: 20px 0;
        }

        .hero-copy .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            padding: 8px 14px;
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 999px;
            background: rgba(245, 158, 11, 0.12);
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.4px;
        }

        .hero-copy h1 {
            max-width: 580px;
            margin-bottom: 14px;
            font-size: 42px;
            line-height: 1.15;
            color: #ffffff;
        }

        .hero-copy p {
            max-width: 560px;
            margin-bottom: 24px;
            color: #444444;
            font-size: 14px;
            line-height: 1.75;
        }

        .hero-cta {
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 520px;
            padding: 6px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.10);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.18);
            backdrop-filter: blur(8px);
        }

        .hero-cta span {
            flex: 1;
            padding-left: 16px;
            color: #2a2a2a;
            font-size: 13px;
        }

        .hero-cta a {
            display: inline-flex;
            min-height: 46px;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            border-radius: 999px;
            background: #ef3340;
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
            transition: 0.2s ease;
        }

        .hero-cta a:hover {
            background: #fbbf24;
            transform: translateY(-1px);
        }

        /*
        |--------------------------------------------------------------------------
        | HERO SLIDER
        |--------------------------------------------------------------------------
        */

        .hero-media {
            position: relative;
            height: 380px;
            align-self: center;
            overflow: hidden;
            border-radius: 24px;
            background: #f8f8f8;
            border: 1px solid #eeeeee;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.22);
        }

        .hero-slide {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: translateX(100%);
            will-change: transform, opacity;
        }

        .hero-slide.active {
            opacity: 1;
            transform: translateX(0);
        }

        .hero-slide-image-wrap {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .hero-blur {
            position: absolute;
            inset: 0;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            filter: blur(26px) brightness(0.55);
            transform: scale(1.12);
            opacity: 0.18;
        }

        .hero-slide-image-wrap::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(11,18,32,0.42) 0%, rgba(11,18,32,0.08) 40%, rgba(11,18,32,0.42) 100%);
            z-index: 1;
        }

        .hero-slide img {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 24px 34px;
            filter: drop-shadow(0 8px 14px rgba(0, 0, 0, 0.28));
        }

        .hero-overlay {
            position: absolute;
            left: 18px;
            bottom: 18px;
            z-index: 3;
            max-width: 260px;
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(17, 24, 39, 0.78);
            border: 1px solid rgba(255,255,255,0.10);
            box-shadow: 0 10px 24px rgba(0,0,0,0.22);
            backdrop-filter: blur(10px);
        }

        .hero-overlay h3 {
            margin-bottom: 6px;
            color: #ffffff;
            font-size: 16px;
            font-weight: 800;
            line-height: 1.2;
        }

        .hero-overlay p {
            color: #444444;
            font-size: 12px;
            line-height: 1.55;
        }

        .hero-slider-arrow {
            position: absolute;
            top: 50%;
            z-index: 20;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateY(-50%);
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 50%;
            background: rgba(17, 24, 39, 0.72);
            color: #ffffff;
            font-size: 22px;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .hero-slider-arrow:hover {
            background: rgba(245, 158, 11, 0.92);
            color: #111827;
        }

        .hero-prev { left: 14px; }
        .hero-next { right: 14px; }

        .hero-counter {
            position: absolute;
            top: 14px;
            right: 14px;
            z-index: 21;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.72);
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
        }

        /*
        |--------------------------------------------------------------------------
        | CATEGORY FLOATING BOX
        |--------------------------------------------------------------------------
        */

        .category-floating {
            position: relative;
            z-index: 50;

            margin-top: -34px;
        }

        .category-box {
            display: grid;

            grid-template-columns:
                repeat(6, minmax(0, 1fr));

            gap: 9px;

            padding: 12px;

            border-radius: 18px;

            background: white;

            box-shadow:
                0 12px 30px
                rgba(15,23,42,0.10);
        }

        .category-card {
            display: flex;
            align-items: center;

            gap: 10px;

            min-height: 58px;

            padding: 8px 11px;

            border: 1px solid #dfe5ec;
            border-radius: 11px;

            background: white;

            transition:
                transform 0.2s,
                border-color 0.2s,
                box-shadow 0.2s;
        }

        .category-card:hover {
            transform: translateY(-2px);

            border-color: #f59e0b;

            background: #fffbeb;

            box-shadow:
                0 5px 12px
                rgba(217,119,6,0.10);
        }

        .category-icon {
            flex-shrink: 0;

            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #fff7e6;
            color: #b45309;

            font-size: 15px;
        }

        .category-card strong {
            font-size: 10px;
            line-height: 1.3;
        }


        /*
        |--------------------------------------------------------------------------
        | SECTION
        |--------------------------------------------------------------------------
        */

        .section {
            padding: 32px 0;
        }

        .section-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            margin-bottom: 17px;
        }

        .section-title-left {
            display: flex;
            align-items: center;

            gap: 10px;
        }

        .section-title-left .star {
            color: #facc15;

            font-size: 20px;
        }

        .section-title-left h2 {
            font-size: 22px;
        }

        .section-title-left select {
            min-height: 37px;

            padding: 0 11px;

            border: 1px solid #d8dee8;
            border-radius: 9px;

            background: white;

            font-size: 11px;
        }

        .section-link {
            color: #b45309;

            font-size: 11px;
            font-weight: 800;
        }


        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        .product-grid {
            display: grid;

            grid-template-columns:
                repeat(5, minmax(0, 1fr));

            gap: 12px;
        }

        .product-card {
            position: relative;

            display: flex;
            flex-direction: column;

            overflow: hidden;

            border: 1px solid var(--border);
            border-radius: 12px;

            background: white;

            box-shadow:
                0 4px 13px
                rgba(15,23,42,0.04);

            transition:
                transform 0.2s,
                box-shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 9px 20px
                rgba(15,23,42,0.10);
        }

        .product-card.promo {
            border-color: #fecaca;
        }

        .product-image {
            position: relative;

            height: 180px;

            overflow: hidden;

            background: #f8fafc;
        }

        .product-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;
        }

        .product-stock-label,
        .product-promo-label {
            position: absolute;
            top: 7px;
            z-index: 5;

            padding: 4px 6px;

            border-radius: 4px;

            color: white;

            font-size: 8px;
            font-weight: 800;
        }

        .product-stock-label {
            left: 7px;

            background:
                rgba(15,23,42,0.78);
        }

        .product-promo-label {
            right: 7px;

            background: var(--red);
        }

        .product-body {
            display: flex;
            flex-direction: column;

            flex: 1;

            padding: 11px;
        }

        .product-category {
            display: inline-flex;

            align-self: flex-start;

            margin-bottom: 6px;

            padding: 3px 6px;

            border-radius: 4px;

            background: #fff7e6;
            color: #b45309;

            font-size: 8px;
            font-weight: 800;
        }

        .product-name {
            min-height: 35px;

            margin-bottom: 7px;

            font-size: 12px;
            line-height: 1.45;
        }

        .product-name a:hover {
            color: #b45309;
        }

        .product-price {
            min-height: 50px;

            margin-bottom: 7px;
        }

        .price-normal {
            color: #b45309;

            font-size: 15px;
            font-weight: 900;
        }

        .price-old {
            display: block;

            margin-bottom: 2px;

            color: #98a2b3;

            font-size: 9px;

            text-decoration: line-through;
        }

        .price-promo {
            color: var(--red);

            font-size: 16px;
            font-weight: 900;
        }

        .discount {
            margin-left: 4px;

            color: var(--red);

            font-size: 8px;
            font-weight: 800;
        }

        .product-status {
            min-height: 17px;

            margin-bottom: 8px;

            font-size: 9px;
            font-weight: 700;
        }

        .status-ok {
            color: var(--green);
        }

        .status-low {
            color: var(--orange);
        }

        .status-out {
            color: var(--red);
        }

        .product-actions {
            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                58px;

            gap: 5px;

            margin-top: auto;
        }

        .btn-order,
        .btn-detail {
            min-height: 33px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 5px;

            text-align: center;

            font-size: 8.5px;
            font-weight: 800;
        }

        .btn-order {
            background: var(--green);
            color: white;
        }

        .btn-order.disabled {
            pointer-events: none;

            background: #98a2b3;
        }

        .btn-detail {
            border: 1px solid #d0d5dd;

            background: white;
            color: #344054;
        }


        /*
        |--------------------------------------------------------------------------
        | PROMO SECTION
        |--------------------------------------------------------------------------
        */

        .promo-section {
            padding: 10px 0 30px;
        }

        .promo-grid {
            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 14px;
        }

        .promo-card-large {
            min-height: 140px;

            padding: 23px;

            border-radius: 13px;

            color: white;

            background: linear-gradient(120deg, #111111, #2b2b2b);

            border-left: 6px solid #f59e0b;

            box-shadow: var(--shadow);
        }

        .promo-card-large.orange {
            background:
                linear-gradient(
                    120deg,
                    #f97316,
                    #f59e0b
                );
        }

        .promo-card-large small {
            display: block;

            margin-bottom: 6px;

            font-size: 9px;
            font-weight: 800;
        }

        .promo-card-large h3 {
            margin-bottom: 6px;

            font-size: 20px;
        }

        .promo-card-large p {
            max-width: 410px;

            color: rgba(255,255,255,0.9);

            font-size: 10px;
            line-height: 1.5;
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

            gap: 14px;
        }

        .info-card {
            padding: 20px;

            border: 1px solid var(--border);
            border-radius: 11px;

            background: white;
        }

        .info-card h3 {
            margin-bottom: 10px;

            font-size: 14px;
        }

        .payment-chips {
            display: flex;
            flex-wrap: wrap;

            gap: 7px;
        }

        .payment-chip {
            padding: 7px 10px;

            border-radius: 6px;

            background: #eef2f7;

            font-size: 10px;
            font-weight: 700;
        }

        .steps {
            list-style: none;
        }

        .steps li {
            display: flex;

            gap: 9px;

            padding: 7px 0;

            border-bottom:
                1px solid
                #f0f2f5;

            font-size: 10px;
            line-height: 1.5;
        }

        .steps li:last-child {
            border-bottom: none;
        }

        .step-number {
            flex-shrink: 0;

            width: 21px;
            height: 21px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #ffffff;
            color: white;

            font-size: 8px;
            font-weight: 800;
        }


        /*
        |--------------------------------------------------------------------------
        | CONTACT
        |--------------------------------------------------------------------------
        */

        .contact-section {
            padding: 5px 0 35px;
        }

        .contact-box {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            padding: 23px;

            border-radius: 12px;

            background: linear-gradient(120deg, #111111, #2b2b2b);

            border-left: 5px solid #f59e0b;

            color: white;
        }

        .contact-box h3 {
            margin-bottom: 5px;

            font-size: 17px;
        }

        .contact-box p {
            max-width: 620px;

            color: #dbeafe;

            font-size: 10px;
            line-height: 1.5;
        }

        .contact-button {
            flex-shrink: 0;

            display: inline-flex;

            min-height: 38px;

            align-items: center;
            justify-content: center;

            padding: 0 14px;

            border-radius: 20px;

            background: var(--green);
            color: white;

            font-size: 10px;
            font-weight: 800;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        footer {
            padding: 25px;

            background: var(--navy-dark);
            color: #cbd5e1;

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
                grid-template-columns:
                    210px
                    minmax(250px, 1fr)
                    auto;
            }

            .hero-inner {
                grid-template-columns:
                    1fr 420px;
            }

            .category-box {
                grid-template-columns:
                    repeat(3, 1fr);
            }

            .product-grid {
                grid-template-columns:
                    repeat(4, 1fr);
            }
        }


        @media (max-width: 820px) {

            .promo-top {
                display: none;
            }

            .header-inner {
                grid-template-columns:
                    1fr auto;

                padding: 10px 0;
            }

            .header-search {
                grid-column: 1 / -1;

                order: 3;

                width: 100%;
            }

            .hero-inner {
                grid-template-columns: 1fr;

                padding-bottom: 30px;
            }

            .hero-copy {
                padding-bottom: 5px;
            }

            .hero-media {
                height: 260px;
            }

            .category-floating {
                margin-top: -25px;
            }

            .product-grid {
                grid-template-columns:
                    repeat(3, 1fr);
            }

            .promo-grid,
            .info-grid {
                grid-template-columns: 1fr;
            }
        }


        @media (max-width: 620px) {

            .container {
                width:
                    min(
                        100% - 20px,
                        1200px
                    );
            }

            .brand-text strong {
                font-size: 13px;
            }

            .brand-text span {
                display: none;
            }

            .header-search {
                grid-template-columns:
                    1fr 44px;
            }

            .header-search select {
                display: none;
            }

            .whatsapp-header {
                width: 42px;

                padding: 0;

                font-size: 0;
            }

            .whatsapp-header::after {
                content: "WA";

                font-size: 10px;
            }

            .hero-copy h1 {
                font-size: 25px;
            }

            .hero-copy p {
                font-size: 11px;
            }

            .hero-cta span {
                font-size: 10px;
            }

            .hero-cta a {
                padding: 0 12px;

                font-size: 9px;
            }

            .hero-media {
                height: 220px;
            }

            .hero-slide img {
                padding:
                    4px 28px 0;
            }

            .category-box {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .section-title-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .product-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .contact-box {
                align-items: flex-start;
                flex-direction: column;
            }
        }


        @media (max-width: 430px) {

            .category-box {
                grid-template-columns: 1fr 1fr;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }

            .product-image {
                height: 235px;
            }
        }


        /* HERO FINAL RESPONSIVE OVERRIDES */
        @media (max-width: 991px) {
            .hero { padding: 20px 0 32px; }
            .hero-inner { min-height: auto; grid-template-columns: 1fr; gap: 22px; }
            .hero-copy { padding: 0; }
            .hero-copy h1 { font-size: 30px; }
            .hero-copy p { font-size: 13px; }
            .hero-media { height: 300px; }
        }

        @media (max-width: 767px) {
            .hero-copy h1 { font-size: 26px; }
            .hero-cta { flex-direction: column; align-items: stretch; gap: 8px; border-radius: 18px; padding: 10px; }
            .hero-cta span { padding-left: 4px; text-align: center; }
            .hero-cta a { width: 100%; }
            .hero-media { height: 240px; border-radius: 18px; }
            .hero-slide img { padding: 18px; }
            .hero-overlay { left: 12px; right: 12px; bottom: 12px; max-width: none; padding: 12px 14px; }
            .hero-overlay h3 { font-size: 14px; }
            .hero-overlay p { font-size: 11px; }
            .hero-slider-arrow { width: 36px; height: 36px; font-size: 18px; }
            .hero-prev { left: 8px; }
            .hero-next { right: 8px; }
        }



        /*
        |--------------------------------------------------------------------------
        | FINAL VISUAL MATCH - DULMAR LOGO THEME
        | Target: white + red + black storefront reference
        |--------------------------------------------------------------------------
        */

        :root {
            --brand-red: #ef2f35;
            --brand-red-dark: #d91f2b;
            --brand-black: #121212;
            --brand-green: #0b8f4d;
            --soft-pink: #fde7e8;
            --soft-pink-2: #fbd5d7;
            --soft-gray: #f7f7f7;
            --line: #e8e8e8;
        }

        body {
            background: #ffffff;
            color: #202020;
        }

        .container {
            width: min(1100px, calc(100% - 28px));
        }

        /* Top promo bar */
        .promo-top {
            min-height: 42px;
            background: linear-gradient(90deg, #101010, #1c1c1c);
            border-bottom: none;
        }

        .promo-top-inner {
            gap: 16px;
        }

        .promo-top strong {
            color: var(--brand-red);
            font-size: 14px;
            font-weight: 800;
        }

        .promo-top span {
            color: #f3f3f3;
            font-size: 11px;
        }

        .promo-top a {
            min-height: 30px;
            padding: 0 16px;
            background: var(--brand-red);
            color: #fff;
            border-radius: 999px;
            box-shadow: 0 4px 10px rgba(239,47,53,.24);
        }

        /* Main header */
        .main-header {
            background: #ffffff;
            box-shadow: none;
            border-bottom: 1px solid #eeeeee;
            backdrop-filter: none;
        }

        .header-inner {
            min-height: 96px;
            grid-template-columns: 285px minmax(360px, 1fr) 140px;
            gap: 20px;
        }

        .brand {
            gap: 14px;
        }

        .brand-logo {
            width: 72px;
            height: 72px;
            border: none;
            box-shadow: none;
            background: #fff;
        }

        .brand-text strong {
            color: #1b1b1b;
            font-size: 20px;
            line-height: 1.15;
        }

        .brand-text span {
            color: #777;
            font-size: 11px;
            margin-top: 5px;
        }

        .header-search {
            height: 48px;
            border-radius: 13px;
            border: 1px solid #dddddd;
            box-shadow: none;
        }

        .header-search:focus-within {
            border-color: #ef2f35;
            box-shadow: 0 0 0 3px rgba(239,47,53,.08);
        }

        .header-search input,
        .header-search select {
            font-size: 12px;
            color: #555;
        }

        .header-search button {
            width: 48px;
            background: var(--brand-red);
            color: #fff;
            font-size: 17px;
        }

        .header-search button:hover {
            background: var(--brand-red-dark);
        }

        .whatsapp-header {
            min-height: 44px;
            padding: 0 18px;
            background: var(--brand-green);
            border-radius: 999px;
            box-shadow: none;
            font-size: 12px;
        }

        /* White nav exactly like the reference */
        .nav-bar {
            background: #ffffff;
            border-top: 1px solid #eeeeee;
            border-bottom: 1px solid #e8e8e8;
        }

        .nav-inner {
            min-height: 47px;
            overflow-x: auto;
        }

        .nav-inner a {
            position: relative;
            min-height: 47px;
            padding: 0 18px;
            color: #252525;
            border-right: none;
            font-size: 12px;
            font-weight: 600;
            background: transparent;
        }

        .nav-inner a:hover {
            background: transparent;
            color: var(--brand-red);
        }

        .nav-inner a.active {
            background: transparent;
            color: var(--brand-red);
        }

        .nav-inner a.active::after,
        .nav-inner a:hover::after {
            content: "";
            position: absolute;
            left: 12px;
            right: 12px;
            bottom: 0;
            height: 2px;
            border-radius: 2px;
            background: var(--brand-red);
        }

        /* Hero: white clean canvas with pale red decorative circles */
        .hero {
            position: relative;
            overflow: hidden;
            padding: 22px 0 48px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfbfb 100%);
            color: #202020;
            border-bottom: none;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 310px;
            height: 310px;
            left: -155px;
            bottom: -110px;
            border-radius: 50%;
            background: rgba(239,47,53,.18);
            z-index: 1;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            right: -95px;
            top: -120px;
            border-radius: 50%;
            background: rgba(239,47,53,.14);
            z-index: 1;
        }

        .hero-inner {
            min-height: 330px;
            grid-template-columns: minmax(0, .95fr) minmax(500px, 1.05fr);
            gap: 34px;
            align-items: center;
        }

        .hero-copy {
            padding: 14px 0 32px;
        }

        .hero-copy .eyebrow {
            margin-bottom: 18px;
            padding: 7px 14px;
            border: 1px solid var(--brand-red);
            background: #fff;
            color: var(--brand-red);
            border-radius: 999px;
            font-size: 11px;
            letter-spacing: .15px;
        }

        .hero-copy h1 {
            max-width: 520px;
            margin-bottom: 16px;
            color: #161616;
            font-size: 38px;
            line-height: 1.08;
            font-weight: 800;
        }

        .hero-copy p {
            max-width: 505px;
            margin-bottom: 24px;
            color: #3d3d3d;
            font-size: 13px;
            line-height: 1.7;
        }

        .hero-cta {
            max-width: 455px;
            padding: 5px;
            border: 1px solid #e5e5e5;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 6px 18px rgba(0,0,0,.08);
            backdrop-filter: none;
        }

        .hero-cta span {
            padding-left: 16px;
            color: #666;
            font-size: 12px;
        }

        .hero-cta a {
            min-height: 42px;
            padding: 0 18px;
            background: var(--brand-red);
            color: #fff;
            border-radius: 999px;
            font-size: 11px;
        }

        .hero-cta a:hover {
            background: var(--brand-red-dark);
            transform: none;
        }

        /* Slider card */
        .hero-media {
            height: 320px;
            border-radius: 14px;
            background: #f3f3f3;
            border: 1px solid #eeeeee;
            box-shadow: 0 7px 22px rgba(0,0,0,.07);
        }

        .hero-slide-image-wrap {
            background: #f4f4f4;
        }

        .hero-blur {
            opacity: 0;
            display: none;
        }

        .hero-slide-image-wrap::after {
            display: none;
        }

        .hero-slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 10px 80px 18px;
            filter: none;
        }

        .hero-overlay {
            display: none;
        }

        .hero-slider-arrow {
            width: 38px;
            height: 38px;
            border: 1px solid #e2e2e2;
            background: rgba(255,255,255,.92);
            color: var(--brand-red);
            box-shadow: 0 3px 10px rgba(0,0,0,.08);
            font-size: 21px;
        }

        .hero-slider-arrow:hover {
            background: #fff;
            color: var(--brand-red-dark);
        }

        .hero-prev { left: 14px; }
        .hero-next { right: 14px; }

        .hero-counter {
            top: 14px;
            right: 14px;
            padding: 6px 10px;
            background: rgba(255,255,255,.94);
            color: var(--brand-red);
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            font-size: 10px;
        }

        /* Floating category strip */
        .category-floating {
            margin-top: -38px;
        }

        .category-box {
            gap: 8px;
            padding: 13px;
            border: 1px solid #ededed;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 9px 24px rgba(0,0,0,.08);
        }

        .category-card {
            min-height: 58px;
            gap: 10px;
            padding: 8px 12px;
            border: 1px solid #e8e8e8;
            border-radius: 10px;
            background: #fff;
        }

        .category-card:hover {
            transform: translateY(-1px);
            border-color: #f5b4b7;
            background: #fffafa;
            box-shadow: 0 4px 10px rgba(239,47,53,.06);
        }

        .category-icon {
            width: 34px;
            height: 34px;
            background: #fdeff0;
            color: var(--brand-red);
            font-size: 14px;
        }

        .category-card strong {
            color: #333;
            font-size: 10px;
        }

        /* Featured section */
        .section {
            padding: 30px 0;
            background: #fff;
        }

        .section-title-row {
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #ececec;
        }

        .section-title-left .star {
            color: var(--brand-red);
            font-size: 19px;
        }

        .section-title-left h2 {
            color: #222;
            font-size: 22px;
        }

        .section-link {
            color: var(--brand-red);
            font-size: 11px;
        }

        /* Product cards: 5 across as in reference */
        .product-grid {
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 12px;
        }

        .product-card {
            border: 1px solid #e8e8e8;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,.035);
            background: #fff;
        }

        .product-card:hover {
            border-color: #f3c4c6;
            box-shadow: 0 7px 18px rgba(0,0,0,.07);
        }

        .product-image {
            height: 205px;
            background: #fafafa;
        }

        .product-category {
            background: #fff0f1;
            color: var(--brand-red);
        }

        .price-normal {
            color: var(--brand-red);
            font-size: 17px;
        }

        .price-promo {
            color: var(--brand-red);
            font-size: 18px;
        }

        .discount {
            color: var(--brand-red);
        }

        .product-name a:hover {
            color: var(--brand-red);
        }

        .btn-detail:hover {
            border-color: #ef2f35;
            color: #ef2f35;
            background: #fffafa;
        }

        /* Remove amber remnants */
        .promo-card-large,
        .promo-card-large.orange {
            background: linear-gradient(120deg, #181818, #2a2a2a);
            border-left: 5px solid var(--brand-red);
        }

        .promo-card-large small,
        .promo-card-large.orange small {
            color: #ffb6ba;
        }

        .payment-chip {
            border-color: #f3c4c6;
            background: #fff3f4;
            color: #b71922;
        }

        .step-number {
            background: var(--brand-red);
        }

        .contact-box {
            background: linear-gradient(120deg, #111111, #242424);
            border-left: 5px solid var(--brand-red);
        }

        .contact-box h3 {
            color: #ffb6ba;
        }

        footer {
            border-top: 4px solid var(--brand-red);
        }

        @media (max-width: 1050px) {
            .container {
                width: min(960px, calc(100% - 24px));
            }

            .header-inner {
                grid-template-columns: 220px minmax(280px, 1fr) 120px;
            }

            .brand-logo {
                width: 60px;
                height: 60px;
            }

            .hero-inner {
                grid-template-columns: minmax(0, .9fr) minmax(440px, 1.1fr);
            }

            .product-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (max-width: 820px) {
            .header-inner {
                grid-template-columns: 1fr auto;
                min-height: auto;
            }

            .brand-logo {
                width: 54px;
                height: 54px;
            }

            .hero-inner {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .hero-copy {
                padding-bottom: 4px;
            }

            .hero-media {
                height: 290px;
            }

            .hero-slide img {
                padding: 10px 54px 14px;
            }

            .product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 620px) {
            .hero-copy h1 {
                font-size: 30px;
            }

            .hero-media {
                height: 235px;
            }

            .hero-slide img {
                padding: 8px 34px 12px;
            }

            .category-box {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 430px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    
        /*
        |--------------------------------------------------------------------------
        | HERO PROMO PRODUCT
        |--------------------------------------------------------------------------
        */

        .hero-product-slide {
            background: #f4f4f4;
        }

        .hero-product-link {
            position: relative;
            z-index: 3;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 18px 74px 42px;
            filter: none;
        }

        .hero-product-label {
            position: absolute;
            left: 18px;
            bottom: 16px;
            z-index: 8;
            display: flex;
            align-items: center;
            gap: 9px;
            max-width: calc(100% - 36px);
            padding: 8px 12px;
            border: 1px solid #f2d0d2;
            border-radius: 999px;
            background: rgba(255,255,255,.95);
            box-shadow: 0 4px 12px rgba(0,0,0,.07);
        }

        .hero-product-label .promo-pill {
            flex-shrink: 0;
            padding: 5px 8px;
            border-radius: 999px;
            background: #ef2f35;
            color: #fff;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .2px;
        }

        .hero-product-label strong {
            overflow: hidden;
            color: #222;
            font-size: 11px;
            font-weight: 800;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 820px) {
            .hero-product-image {
                padding: 14px 54px 44px;
            }
        }

        @media (max-width: 620px) {
            .hero-product-image {
                padding: 12px 36px 42px;
            }

            .hero-product-label {
                left: 10px;
                right: 10px;
                bottom: 10px;
                max-width: none;
            }
        }

    
        /*
        |--------------------------------------------------------------------------
        | HERO - HANYA PRODUK PROMO
        |--------------------------------------------------------------------------
        */

        .hero-product-label {
            right: 18px;
            max-width: none;
        }

        .hero-product-discount {
            flex-shrink: 0;
            color: #ef2f35;
            font-size: 10px;
            font-weight: 900;
        }

        .hero-product-price {
            flex-shrink: 0;
            color: #ef2f35;
            font-size: 12px;
            font-weight: 900;
        }

        .hero-promo-empty {
            position: absolute;
            inset: 0;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            gap: 8px;

            padding: 30px;

            background: #f6f6f6;
            color: #555;

            text-align: center;
        }

        .hero-promo-empty-icon {
            width: 48px;
            height: 48px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #fff0f1;
            color: #ef2f35;

            font-size: 22px;
            font-weight: 900;
        }

        .hero-promo-empty strong {
            color: #222;
            font-size: 15px;
        }

        .hero-promo-empty span {
            max-width: 320px;
            color: #777;
            font-size: 11px;
            line-height: 1.55;
        }

        @media (max-width: 620px) {
            .hero-product-label {
                right: 10px;
            }

            .hero-product-label strong {
                max-width: 110px;
            }
        }

    
        /*
        |--------------------------------------------------------------------------
        | FINAL RUNNING TEXT OVERRIDE
        |--------------------------------------------------------------------------
        */

        .promo-top {
            display: block !important;

            position: relative !important;

            width: 100% !important;
            height: 42px !important;
            min-height: 42px !important;

            overflow: hidden !important;

            background: #111111 !important;

            border-bottom: 2px solid #ef2f35 !important;
        }

        .promo-top-inner {
            position: relative !important;

            display: block !important;

            width: 100% !important;
            max-width: none !important;

            height: 42px !important;
            min-height: 42px !important;

            overflow: hidden !important;
        }

        .promo-running-track {
            position: absolute !important;

            top: 0 !important;
            left: 0 !important;

            display: flex !important;
            align-items: center !important;

            width: max-content !important;
            height: 42px !important;

            white-space: nowrap !important;

            animation: dulmarRunningText 18s linear infinite !important;
        }

        @media (max-width: 820px) {

            .promo-top {
                display: block !important;
            }

            .promo-running-track {
                animation-duration: 15s !important;
            }

            .promo-running-item {
                gap: 14px !important;

                padding-right: 56px !important;

                font-size: 12px !important;
            }

            .promo-running-item strong {
                font-size: 12px !important;
            }
        }


    
        /*
        |--------------------------------------------------------------------------
        | HERO PROMO FINAL POLISH
        |--------------------------------------------------------------------------
        */

        .hero-media {
            height: 340px !important;
            border-radius: 16px !important;
            background: #f7f7f7 !important;
            border: 1px solid #ededed !important;
            box-shadow: 0 10px 28px rgba(0,0,0,.08) !important;
        }

        .hero-product-slide {
            background: #f7f7f7 !important;
        }

        .hero-product-link {
            position: relative !important;
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .hero-product-image {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
            padding: 12px 56px 70px !important;
            filter: none !important;
            transition: transform .25s ease !important;
        }

        .hero-product-link:hover .hero-product-image {
            transform: scale(1.015);
        }

        .hero-product-label {
            position: absolute !important;
            left: 16px !important;
            right: 16px !important;
            bottom: 14px !important;
            z-index: 9 !important;

            display: grid !important;
            grid-template-columns: auto minmax(0, 1fr) auto auto !important;
            align-items: center !important;
            gap: 10px !important;

            min-height: 44px !important;
            max-width: none !important;

            padding: 7px 10px !important;

            border: 1px solid #f2c9cc !important;
            border-radius: 12px !important;

            background: rgba(255,255,255,.97) !important;
            box-shadow: 0 5px 16px rgba(0,0,0,.08) !important;
        }

        .hero-product-label .promo-pill {
            padding: 5px 8px !important;
            border-radius: 999px !important;
            background: #ef2f35 !important;
            color: #ffffff !important;
            font-size: 9px !important;
            font-weight: 900 !important;
            line-height: 1 !important;
        }

        .hero-product-label strong {
            min-width: 0 !important;
            overflow: hidden !important;
            color: #1f1f1f !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        .hero-product-discount {
            padding: 4px 7px !important;
            border-radius: 999px !important;
            background: #fff0f1 !important;
            color: #ef2f35 !important;
            font-size: 10px !important;
            font-weight: 900 !important;
            white-space: nowrap !important;
        }

        .hero-product-price {
            color: #ef2f35 !important;
            font-size: 15px !important;
            font-weight: 900 !important;
            white-space: nowrap !important;
        }

        .hero-slider-arrow {
            width: 40px !important;
            height: 40px !important;
            background: rgba(255,255,255,.96) !important;
            border: 1px solid #dfdfdf !important;
            color: #ef2f35 !important;
            box-shadow: 0 4px 12px rgba(0,0,0,.08) !important;
        }

        .hero-slider-arrow:hover {
            background: #ef2f35 !important;
            color: #ffffff !important;
        }

        .hero-prev {
            left: 12px !important;
        }

        .hero-next {
            right: 12px !important;
        }

        .hero-counter {
            top: 12px !important;
            right: 12px !important;
            padding: 6px 10px !important;
            border: 1px solid #f1d5d7 !important;
            border-radius: 999px !important;
            background: rgba(255,255,255,.97) !important;
            color: #ef2f35 !important;
            box-shadow: 0 3px 10px rgba(0,0,0,.06) !important;
            font-size: 10px !important;
            font-weight: 900 !important;
        }

        @media (max-width: 820px) {
            .hero-media {
                height: 300px !important;
            }

            .hero-product-image {
                padding: 10px 48px 68px !important;
            }
        }

        @media (max-width: 620px) {
            .hero-media {
                height: 245px !important;
            }

            .hero-product-image {
                padding: 8px 30px 74px !important;
            }

            .hero-product-label {
                left: 9px !important;
                right: 9px !important;
                bottom: 9px !important;
                grid-template-columns: auto minmax(0,1fr) auto !important;
                gap: 7px !important;
            }

            .hero-product-price {
                grid-column: 2 / 4 !important;
                font-size: 14px !important;
            }

            .hero-product-label strong {
                font-size: 11px !important;
            }
        }

    
        /*
        |--------------------------------------------------------------------------
        | FINAL MOBILE / RESPONSIVE POLISH
        |--------------------------------------------------------------------------
        */

        @media (max-width: 820px) {

            body {
                overflow-x: hidden;
            }

            .container {
                width: min(100% - 18px, 1100px) !important;
            }

            .promo-top {
                height: 36px !important;
                min-height: 36px !important;
            }

            .promo-top-inner,
            .promo-running-track {
                height: 36px !important;
                min-height: 36px !important;
            }

            .promo-running-item {
                gap: 12px !important;
                padding-right: 44px !important;
                font-size: 11px !important;
                letter-spacing: .8px !important;
            }

            .promo-running-item strong {
                font-size: 11px !important;
            }

            .main-header {
                position: sticky;
                top: 0;
            }

            .header-inner {
                grid-template-columns: minmax(0, 1fr) auto !important;
                gap: 10px !important;
                padding: 9px 0 10px !important;
                min-height: auto !important;
            }

            .brand {
                min-width: 0;
                gap: 10px !important;
            }

            .brand-logo {
                width: 48px !important;
                height: 48px !important;
                flex-shrink: 0;
            }

            .brand-text {
                min-width: 0;
            }

            .brand-text strong {
                font-size: 15px !important;
                line-height: 1.1 !important;
            }

            .brand-text span {
                display: block !important;
                font-size: 9px !important;
                margin-top: 3px !important;
            }

            .whatsapp-header {
                min-width: 44px !important;
                min-height: 40px !important;
                padding: 0 12px !important;
                font-size: 10px !important;
            }

            .header-search {
                grid-column: 1 / -1 !important;
                grid-template-columns: minmax(0, 1fr) 130px 44px !important;
                width: 100% !important;
                height: 44px !important;
                border-radius: 11px !important;
            }

            .header-search input,
            .header-search select {
                font-size: 11px !important;
                padding: 0 11px !important;
            }

            .header-search button {
                width: 44px !important;
                font-size: 15px !important;
            }

            .nav-inner {
                width: 100% !important;
                min-height: 44px !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                -webkit-overflow-scrolling: touch;
                scroll-snap-type: x proximity;
                padding: 0 2px !important;
            }

            .nav-inner a {
                min-height: 44px !important;
                padding: 0 14px !important;
                font-size: 11px !important;
                scroll-snap-align: start;
            }

            .hero {
                padding: 18px 0 42px !important;
            }

            .hero-inner {
                grid-template-columns: 1fr !important;
                gap: 18px !important;
                min-height: auto !important;
            }

            .hero-copy {
                padding: 0 !important;
            }

            .hero-copy h1 {
                max-width: none !important;
                margin-bottom: 10px !important;
                font-size: 30px !important;
                line-height: 1.08 !important;
            }

            .hero-copy p {
                max-width: none !important;
                margin-bottom: 16px !important;
                font-size: 12px !important;
                line-height: 1.6 !important;
            }

            .hero-cta {
                max-width: none !important;
                width: 100% !important;
            }

            .hero-media {
                height: 300px !important;
                border-radius: 14px !important;
            }

            .hero-product-image {
                padding: 10px 46px 68px !important;
            }

            .hero-product-label {
                left: 10px !important;
                right: 10px !important;
                bottom: 10px !important;
                grid-template-columns: auto minmax(0,1fr) auto auto !important;
                gap: 8px !important;
                min-height: 42px !important;
                padding: 6px 8px !important;
            }

            .hero-product-label strong {
                font-size: 11px !important;
            }

            .hero-product-price {
                font-size: 14px !important;
            }

            .hero-slider-arrow {
                width: 36px !important;
                height: 36px !important;
                font-size: 18px !important;
            }

            .hero-prev {
                left: 8px !important;
            }

            .hero-next {
                right: 8px !important;
            }

            .category-floating {
                margin-top: -30px !important;
            }

            .category-box {
                grid-template-columns: repeat(3, minmax(135px, 1fr)) !important;
                display: grid !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                gap: 8px !important;
                padding: 10px !important;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .category-box::-webkit-scrollbar {
                display: none;
            }

            .category-card {
                min-width: 135px !important;
                min-height: 54px !important;
                padding: 8px 10px !important;
            }

            .section {
                padding: 26px 0 !important;
            }

            .section-title-row {
                margin-bottom: 14px !important;
                padding-bottom: 10px !important;
            }

            .section-title-left h2 {
                font-size: 20px !important;
            }

            .product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: 10px !important;
            }

            .product-image {
                height: 180px !important;
            }

            .product-body {
                padding: 10px !important;
            }

            .product-name {
                min-height: 36px !important;
            }

            .product-name a {
                font-size: 11px !important;
            }

            .price-normal,
            .price-promo {
                font-size: 16px !important;
            }

            .product-actions {
                grid-template-columns: minmax(0, 1fr) 58px !important;
                gap: 6px !important;
            }

            .btn-order,
            .btn-detail {
                min-height: 34px !important;
                font-size: 8.5px !important;
            }

            .promo-grid,
            .info-grid {
                grid-template-columns: 1fr !important;
            }

            .promo-card-large {
                min-height: 125px !important;
                padding: 18px !important;
            }

            .contact-box {
                gap: 14px !important;
            }
        }

        @media (max-width: 620px) {

            .container {
                width: min(100% - 14px, 1100px) !important;
            }

            #home,
            #produtu,
            #pagamentu,
            #kontaktu {
                scroll-margin-top: 138px !important;
            }

            .promo-top {
                height: 34px !important;
                min-height: 34px !important;
            }

            .promo-top-inner,
            .promo-running-track {
                height: 34px !important;
                min-height: 34px !important;
            }

            .promo-running-item {
                font-size: 10px !important;
                padding-right: 36px !important;
            }

            .promo-running-item strong {
                font-size: 10px !important;
            }

            .header-inner {
                gap: 8px !important;
                padding: 8px 0 9px !important;
            }

            .brand-logo {
                width: 42px !important;
                height: 42px !important;
            }

            .brand-text strong {
                font-size: 13px !important;
            }

            .brand-text span {
                display: none !important;
            }

            .whatsapp-header {
                width: 42px !important;
                min-width: 42px !important;
                height: 38px !important;
                min-height: 38px !important;
                padding: 0 !important;
                font-size: 0 !important;
            }

            .whatsapp-header::after {
                content: "WA";
                font-size: 10px;
                font-weight: 900;
            }

            .header-search {
                grid-template-columns: minmax(0, 1fr) 44px !important;
                height: 42px !important;
            }

            .header-search select {
                display: none !important;
            }

            .header-search input {
                font-size: 11px !important;
            }

            .nav-inner a {
                padding: 0 13px !important;
                font-size: 10.5px !important;
            }

            .hero {
                padding: 16px 0 38px !important;
            }

            .hero-copy h1 {
                font-size: 25px !important;
            }

            .hero-copy p {
                font-size: 11px !important;
            }

            .hero-cta {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 7px !important;
                padding: 8px !important;
                border-radius: 16px !important;
            }

            .hero-cta span {
                padding-left: 0 !important;
                text-align: center !important;
                font-size: 10px !important;
            }

            .hero-cta a {
                width: 100% !important;
                min-height: 40px !important;
                font-size: 10px !important;
            }

            .hero-media {
                height: 245px !important;
            }

            .hero-product-image {
                padding: 8px 28px 76px !important;
            }

            .hero-product-label {
                grid-template-columns: auto minmax(0,1fr) auto !important;
                gap: 6px !important;
                padding: 6px 7px !important;
            }

            .hero-product-label .promo-pill {
                font-size: 8px !important;
                padding: 4px 6px !important;
            }

            .hero-product-label strong {
                font-size: 10px !important;
            }

            .hero-product-discount {
                font-size: 9px !important;
                padding: 3px 5px !important;
            }

            .hero-product-price {
                grid-column: 2 / 4 !important;
                font-size: 13px !important;
            }

            .category-box {
                grid-template-columns: repeat(2, minmax(135px, 1fr)) !important;
            }

            .section-title-row {
                flex-direction: row !important;
                align-items: center !important;
            }

            .section-title-left h2 {
                font-size: 18px !important;
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 9px !important;
            }

            .product-image {
                height: 165px !important;
            }

            .product-body {
                padding: 9px !important;
            }

            .product-stock-label,
            .product-promo-label {
                top: 6px !important;
                min-height: 20px !important;
                font-size: 7px !important;
                padding: 0 5px !important;
            }

            .product-stock-label {
                left: 6px !important;
            }

            .product-promo-label {
                right: 6px !important;
            }

            .product-category {
                min-height: 18px !important;
                font-size: 7.5px !important;
            }

            .product-name {
                min-height: 34px !important;
                margin-bottom: 6px !important;
            }

            .product-name a {
                font-size: 10.5px !important;
                line-height: 1.45 !important;
            }

            .product-price {
                min-height: 48px !important;
            }

            .price-normal,
            .price-promo {
                font-size: 15px !important;
            }

            .product-status {
                margin-bottom: 8px !important;
            }

            .product-actions {
                grid-template-columns: 1fr !important;
                gap: 5px !important;
            }

            .btn-order,
            .btn-detail {
                width: 100% !important;
                min-height: 34px !important;
                font-size: 8.5px !important;
            }

            .promo-card-large {
                padding: 16px !important;
            }

            .promo-card-large h3 {
                font-size: 17px !important;
            }

            .contact-box {
                flex-direction: column !important;
                align-items: flex-start !important;
                padding: 18px !important;
            }

            .contact-button {
                width: 100% !important;
            }
        }

        @media (max-width: 430px) {

            .container {
                width: calc(100% - 12px) !important;
            }

            .brand-text strong {
                max-width: 160px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .nav-inner a {
                padding: 0 12px !important;
            }

            .hero-copy h1 {
                font-size: 23px !important;
            }

            .hero-media {
                height: 225px !important;
            }

            .hero-product-image {
                padding: 8px 22px 76px !important;
            }

            .category-box {
                grid-template-columns: repeat(2, minmax(125px, 1fr)) !important;
            }

            .category-card {
                min-width: 125px !important;
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 7px !important;
            }

            .product-image {
                height: 148px !important;
            }

            .product-body {
                padding: 8px !important;
            }

            .price-normal,
            .price-promo {
                font-size: 14px !important;
            }

            .discount {
                font-size: 8px !important;
            }

            .btn-order,
            .btn-detail {
                min-height: 33px !important;
                font-size: 8px !important;
            }
        }

        @media (max-width: 350px) {

            .product-grid {
                grid-template-columns: 1fr !important;
            }

            .product-image {
                height: 210px !important;
            }
        }

    
        /*
        |--------------------------------------------------------------------------
        | FINAL BUTTON COLOR OVERRIDE
        |--------------------------------------------------------------------------
        */

        .header-search button {
            background: #111111 !important;
            color: #ffffff !important;
        }

        .header-search button:hover {
            background: #ef2f35 !important;
            color: #ffffff !important;
        }

        .hero-cta a {
            background: #0b8f4d !important;
            color: #ffffff !important;
        }

        .hero-cta a:hover {
            background: #087a41 !important;
            color: #ffffff !important;
        }

        .whatsapp-header {
            background: #0b8f4d !important;
            color: #ffffff !important;
        }

        .whatsapp-header:hover {
            background: #087a41 !important;
            color: #ffffff !important;
        }

    
        /*
        |--------------------------------------------------------------------------
        | UNIVERSAL MOBILE RESPONSIVE FINAL
        |--------------------------------------------------------------------------
        | Target:
        | - 320-359px  : mobile sangat kecil
        | - 360-389px  : mobile kecil/menengah
        | - 390-430px  : mobile modern
        | - 431-767px  : mobile besar / landscape kecil
        | - 768-820px  : tablet kecil
        |--------------------------------------------------------------------------
        */

        @media (max-width: 820px) {

            html,
            body {
                max-width: 100%;
                overflow-x: hidden;
            }

            .container {
                width: min(100% - 18px, 1100px) !important;
            }

            .promo-top {
                height: 36px !important;
                min-height: 36px !important;
            }

            .promo-top-inner,
            .promo-running-track {
                height: 36px !important;
                min-height: 36px !important;
            }

            .promo-running-item {
                gap: 12px !important;
                padding-right: 44px !important;
                font-size: 11px !important;
                letter-spacing: .8px !important;
            }

            .promo-running-item strong {
                font-size: 11px !important;
            }

            .main-header {
                position: sticky !important;
                top: 0 !important;
            }

            .header-inner {
                grid-template-columns: minmax(0, 1fr) auto !important;
                gap: 10px !important;
                padding: 9px 0 10px !important;
                min-height: auto !important;
            }

            .brand {
                min-width: 0 !important;
                gap: 10px !important;
            }

            .brand-logo {
                width: 48px !important;
                height: 48px !important;
                flex-shrink: 0 !important;
            }

            .brand-text {
                min-width: 0 !important;
            }

            .brand-text strong {
                font-size: 15px !important;
                line-height: 1.1 !important;
            }

            .brand-text span {
                display: block !important;
                margin-top: 3px !important;
                font-size: 9px !important;
            }

            .whatsapp-header {
                min-width: 44px !important;
                min-height: 40px !important;
                padding: 0 12px !important;
                font-size: 10px !important;
            }

            .header-search {
                grid-column: 1 / -1 !important;
                grid-template-columns: minmax(0, 1fr) 130px 44px !important;
                width: 100% !important;
                height: 44px !important;
                border-radius: 11px !important;
            }

            .header-search input,
            .header-search select {
                min-width: 0 !important;
                padding: 0 11px !important;
                font-size: 11px !important;
            }

            .header-search button {
                width: 44px !important;
                font-size: 15px !important;
            }

            .nav-bar {
                overflow: hidden !important;
            }

            .nav-inner {
                width: 100% !important;
                min-height: 44px !important;
                display: flex !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                white-space: nowrap !important;
                padding: 0 4px !important;
                scroll-behavior: smooth;
                scroll-snap-type: x proximity;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .nav-inner::-webkit-scrollbar {
                display: none !important;
            }

            .nav-inner a {
                flex: 0 0 auto !important;
                min-height: 44px !important;
                padding: 0 14px !important;
                font-size: 11px !important;
                scroll-snap-align: start;
            }

            .hero {
                padding: 20px 0 44px !important;
            }

            .hero-inner {
                grid-template-columns: 1fr !important;
                gap: 18px !important;
                min-height: auto !important;
            }

            .hero-copy {
                padding: 0 !important;
            }

            .hero-copy h1 {
                max-width: none !important;
                margin-bottom: 10px !important;
                font-size: 30px !important;
                line-height: 1.08 !important;
            }

            .hero-copy p {
                max-width: none !important;
                margin-bottom: 16px !important;
                font-size: 12px !important;
                line-height: 1.6 !important;
            }

            .hero-cta {
                max-width: none !important;
                width: 100% !important;
            }

            .hero-media {
                height: 300px !important;
                border-radius: 14px !important;
            }

            .hero-product-image {
                padding: 10px 46px 68px !important;
            }

            .hero-product-label {
                left: 10px !important;
                right: 10px !important;
                bottom: 10px !important;
                grid-template-columns: auto minmax(0,1fr) auto auto !important;
                gap: 8px !important;
                min-height: 42px !important;
                padding: 6px 8px !important;
            }

            .hero-product-label strong {
                font-size: 11px !important;
            }

            .hero-product-price {
                font-size: 14px !important;
            }

            .hero-slider-arrow {
                width: 36px !important;
                height: 36px !important;
                font-size: 18px !important;
            }

            .hero-prev {
                left: 8px !important;
            }

            .hero-next {
                right: 8px !important;
            }

            .category-floating {
                margin-top: -30px !important;
            }

            .category-box {
                display: grid !important;
                grid-template-columns: repeat(3, minmax(135px, 1fr)) !important;
                gap: 8px !important;
                padding: 10px !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .category-box::-webkit-scrollbar {
                display: none !important;
            }

            .category-card {
                min-width: 135px !important;
                min-height: 54px !important;
                padding: 8px 10px !important;
            }

            .section {
                padding: 26px 0 !important;
            }

            .section-title-row {
                margin-bottom: 14px !important;
                padding-bottom: 10px !important;
            }

            .section-title-left h2 {
                font-size: 20px !important;
            }

            .product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: 10px !important;
            }

            .product-image {
                height: 180px !important;
            }

            .product-body {
                padding: 10px !important;
            }

            .product-name {
                min-height: 36px !important;
            }

            .product-name a {
                font-size: 11px !important;
            }

            .price-normal,
            .price-promo {
                font-size: 16px !important;
            }

            .product-actions {
                grid-template-columns: minmax(0, 1fr) 58px !important;
                gap: 6px !important;
            }

            .btn-order,
            .btn-detail {
                min-height: 34px !important;
                font-size: 8.5px !important;
            }

            .promo-grid,
            .info-grid {
                grid-template-columns: 1fr !important;
            }

            .promo-card-large {
                min-height: 125px !important;
                padding: 18px !important;
            }

            .contact-box {
                gap: 14px !important;
            }
        }

        @media (max-width: 767px) {

            .hero {
                padding-top: 18px !important;
            }

            .hero-copy h1 {
                font-size: 27px !important;
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }

            .category-box {
                grid-template-columns: repeat(2, minmax(135px, 1fr)) !important;
            }
        }

        @media (max-width: 620px) {

            .container {
                width: calc(100% - 14px) !important;
            }

            #home,
            #produtu,
            #pagamentu,
            #kontaktu {
                scroll-margin-top: 138px !important;
            }

            .promo-top {
                height: 34px !important;
                min-height: 34px !important;
            }

            .promo-top-inner,
            .promo-running-track {
                height: 34px !important;
                min-height: 34px !important;
            }

            .promo-running-item {
                padding-right: 36px !important;
                font-size: 10px !important;
            }

            .promo-running-item strong {
                font-size: 10px !important;
            }

            .header-inner {
                gap: 8px !important;
                padding: 8px 0 9px !important;
            }

            .brand-logo {
                width: 42px !important;
                height: 42px !important;
            }

            .brand-text strong {
                font-size: 13px !important;
            }

            .brand-text span {
                display: none !important;
            }

            .whatsapp-header {
                width: 42px !important;
                min-width: 42px !important;
                height: 38px !important;
                min-height: 38px !important;
                padding: 0 !important;
                font-size: 0 !important;
            }

            .whatsapp-header::after {
                content: "WA";
                font-size: 10px;
                font-weight: 900;
            }

            .header-search {
                grid-template-columns: minmax(0, 1fr) 44px !important;
                height: 42px !important;
            }

            .header-search select {
                display: none !important;
            }

            .header-search input {
                font-size: 11px !important;
            }

            .nav-inner a {
                padding: 0 13px !important;
                font-size: 10.5px !important;
            }

            .hero {
                padding: 16px 0 38px !important;
            }

            .hero-copy h1 {
                font-size: 24px !important;
            }

            .hero-copy p {
                font-size: 11px !important;
            }

            .hero-cta {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 7px !important;
                padding: 8px !important;
                border-radius: 16px !important;
            }

            .hero-cta span {
                padding-left: 0 !important;
                text-align: center !important;
                font-size: 10px !important;
            }

            .hero-cta a {
                width: 100% !important;
                min-height: 40px !important;
                font-size: 10px !important;
            }

            .hero-media {
                height: 245px !important;
            }

            .hero-product-image {
                padding: 8px 28px 76px !important;
            }

            .hero-product-label {
                grid-template-columns: auto minmax(0,1fr) auto !important;
                gap: 6px !important;
                padding: 6px 7px !important;
            }

            .hero-product-label .promo-pill {
                padding: 4px 6px !important;
                font-size: 8px !important;
            }

            .hero-product-label strong {
                font-size: 10px !important;
            }

            .hero-product-discount {
                padding: 3px 5px !important;
                font-size: 9px !important;
            }

            .hero-product-price {
                grid-column: 2 / 4 !important;
                font-size: 13px !important;
            }

            .category-box {
                grid-template-columns: repeat(2, minmax(135px, 1fr)) !important;
            }

            .section-title-row {
                flex-direction: row !important;
                align-items: center !important;
            }

            .section-title-left h2 {
                font-size: 18px !important;
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 9px !important;
            }

            .product-image {
                height: 165px !important;
            }

            .product-body {
                padding: 9px !important;
            }

            .product-stock-label,
            .product-promo-label {
                top: 6px !important;
                min-height: 20px !important;
                padding: 0 5px !important;
                font-size: 7px !important;
            }

            .product-stock-label {
                left: 6px !important;
            }

            .product-promo-label {
                right: 6px !important;
            }

            .product-category {
                min-height: 18px !important;
                font-size: 7.5px !important;
            }

            .product-name {
                min-height: 34px !important;
                margin-bottom: 6px !important;
            }

            .product-name a {
                font-size: 10.5px !important;
                line-height: 1.45 !important;
            }

            .product-price {
                min-height: 48px !important;
            }

            .price-normal,
            .price-promo {
                font-size: 15px !important;
            }

            .product-status {
                margin-bottom: 8px !important;
            }

            .product-actions {
                grid-template-columns: 1fr !important;
                gap: 5px !important;
            }

            .btn-order,
            .btn-detail {
                width: 100% !important;
                min-height: 34px !important;
                font-size: 8.5px !important;
            }

            .promo-card-large {
                padding: 16px !important;
            }

            .promo-card-large h3 {
                font-size: 17px !important;
            }

            .contact-box {
                flex-direction: column !important;
                align-items: flex-start !important;
                padding: 18px !important;
            }

            .contact-button {
                width: 100% !important;
            }
        }

        @media (max-width: 430px) {

            .container {
                width: calc(100% - 12px) !important;
            }

            .brand-text strong {
                max-width: 160px !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
            }

            .nav-inner a {
                padding: 0 12px !important;
                font-size: 10px !important;
            }

            .hero-copy h1 {
                font-size: 22px !important;
            }

            .hero-media {
                height: 225px !important;
            }

            .hero-product-image {
                padding: 8px 22px 76px !important;
            }

            .category-box {
                grid-template-columns: repeat(2, minmax(125px, 1fr)) !important;
            }

            .category-card {
                min-width: 125px !important;
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 7px !important;
            }

            .product-image {
                height: 148px !important;
            }

            .product-body {
                padding: 8px !important;
            }

            .price-normal,
            .price-promo {
                font-size: 14px !important;
            }

            .discount {
                font-size: 8px !important;
            }

            .btn-order,
            .btn-detail {
                min-height: 33px !important;
                font-size: 8px !important;
            }
        }

        @media (max-width: 389px) {

            .container {
                width: calc(100% - 10px) !important;
            }

            .brand-text strong {
                max-width: 140px !important;
                font-size: 12px !important;
            }

            .nav-inner a {
                padding: 0 11px !important;
                font-size: 9.8px !important;
            }

            .hero-copy h1 {
                font-size: 21px !important;
            }

            .hero-copy p {
                font-size: 10.5px !important;
            }

            .hero-media {
                height: 215px !important;
            }

            .hero-slider-arrow {
                width: 32px !important;
                height: 32px !important;
                font-size: 16px !important;
            }

            .category-box {
                grid-template-columns: repeat(2, minmax(118px, 1fr)) !important;
            }

            .category-card {
                min-width: 118px !important;
            }

            .product-image {
                height: 142px !important;
            }

            .product-name a {
                font-size: 10px !important;
            }

            .price-normal,
            .price-promo {
                font-size: 13.5px !important;
            }
        }

        @media (max-width: 350px) {

            .brand-text strong {
                max-width: 120px !important;
            }

            .category-box {
                grid-template-columns: 1fr !important;
            }

            .category-card {
                min-width: 100% !important;
            }

            .product-grid {
                grid-template-columns: 1fr !important;
            }

            .product-image {
                height: 210px !important;
            }

            .hero-media {
                height: 205px !important;
            }
        }

    
        /*
        |--------------------------------------------------------------------------
        | FINAL COMPACT MOBILE POLISH
        |--------------------------------------------------------------------------
        | Fokus: semua mobile 320px - 820px.
        | Desktop tidak diubah.
        |--------------------------------------------------------------------------
        */

        @media (max-width: 820px) {

            .nav-inner {
                padding-left: 8px !important;
                padding-right: 22px !important;
                column-gap: 0 !important;
            }

            .nav-inner a:last-child {
                margin-right: 12px !important;
            }

            .hero {
                padding-top: 16px !important;
                padding-bottom: 34px !important;
            }

            .hero-inner {
                gap: 14px !important;
            }

            .hero-copy h1 {
                margin-bottom: 8px !important;
            }

            .hero-copy p {
                margin-bottom: 12px !important;
            }

            .hero-cta {
                box-shadow: 0 4px 12px rgba(0,0,0,.06) !important;
            }

            .hero-media {
                height: 280px !important;
            }

            .category-floating {
                margin-top: -22px !important;
                margin-bottom: 8px !important;
            }

            .category-box {
                box-shadow: 0 6px 16px rgba(0,0,0,.06) !important;
            }

            .section {
                padding-top: 22px !important;
                padding-bottom: 22px !important;
            }

            .section-title-row {
                margin-bottom: 12px !important;
            }
        }

        @media (max-width: 767px) {

            .hero-copy h1 {
                font-size: 25px !important;
            }

            .hero-copy p {
                font-size: 11px !important;
            }

            .hero-media {
                height: 255px !important;
            }

            .hero-product-image {
                padding: 8px 40px 72px !important;
            }

            .category-floating {
                margin-top: -18px !important;
            }
        }

        @media (max-width: 620px) {

            .promo-top {
                height: 32px !important;
                min-height: 32px !important;
            }

            .promo-top-inner,
            .promo-running-track {
                height: 32px !important;
                min-height: 32px !important;
            }

            .promo-running-item {
                font-size: 9.5px !important;
                padding-right: 32px !important;
            }

            .promo-running-item strong {
                font-size: 9.5px !important;
            }

            .header-inner {
                padding-top: 7px !important;
                padding-bottom: 8px !important;
            }

            .header-search {
                height: 40px !important;
            }

            .nav-inner {
                min-height: 42px !important;
                padding-left: 6px !important;
                padding-right: 24px !important;
            }

            .nav-inner a {
                min-height: 42px !important;
            }

            .hero {
                padding-top: 12px !important;
                padding-bottom: 30px !important;
            }

            .hero-inner {
                gap: 12px !important;
            }

            .hero-copy h1 {
                font-size: 23px !important;
                margin-bottom: 7px !important;
            }

            .hero-copy p {
                margin-bottom: 10px !important;
                line-height: 1.5 !important;
            }

            .hero-cta {
                gap: 5px !important;
                padding: 6px !important;
                border-radius: 14px !important;
            }

            .hero-cta a {
                min-height: 36px !important;
            }

            .hero-media {
                height: 228px !important;
            }

            .hero-product-image {
                padding: 6px 26px 72px !important;
            }

            .hero-product-label {
                bottom: 7px !important;
            }

            .category-floating {
                margin-top: -14px !important;
                margin-bottom: 10px !important;
            }

            .category-box {
                padding: 8px !important;
                gap: 7px !important;
                border-radius: 14px !important;
            }

            .category-card {
                min-height: 50px !important;
            }

            .section {
                padding-top: 20px !important;
                padding-bottom: 20px !important;
            }

            .section-title-row {
                margin-bottom: 10px !important;
                padding-bottom: 8px !important;
            }
        }

        @media (max-width: 430px) {

            .hero-copy h1 {
                font-size: 21px !important;
            }

            .hero-media {
                height: 212px !important;
            }

            .hero-product-image {
                padding: 6px 20px 70px !important;
            }

            .hero-slider-arrow {
                width: 30px !important;
                height: 30px !important;
                font-size: 15px !important;
            }

            .hero-prev {
                left: 6px !important;
            }

            .hero-next {
                right: 6px !important;
            }

            .hero-counter {
                top: 8px !important;
                right: 8px !important;
                padding: 5px 8px !important;
                font-size: 9px !important;
            }

            .category-floating {
                margin-top: -10px !important;
            }

            .section-title-left h2 {
                font-size: 17px !important;
            }

            .section-link {
                font-size: 9.5px !important;
            }
        }

        @media (max-width: 389px) {

            .hero-media {
                height: 202px !important;
            }

            .hero-product-image {
                padding: 5px 18px 68px !important;
            }

            .hero-product-label {
                left: 7px !important;
                right: 7px !important;
                bottom: 6px !important;
            }

            .section {
                padding-top: 18px !important;
                padding-bottom: 18px !important;
            }
        }

        @media (max-width: 350px) {

            .hero-copy h1 {
                font-size: 20px !important;
            }

            .hero-media {
                height: 196px !important;
            }

            .category-floating {
                margin-top: -8px !important;
            }
        }

    
        /*
        |--------------------------------------------------------------------------
        | FINAL ANCHOR + PRODUCT CARD POLISH
        |--------------------------------------------------------------------------
        */

        #home,
        #produtu,
        #pagamentu,
        #kontaktu,
        .section {
            scroll-margin-top: 160px !important;
        }

        .section {
            position: relative;
        }

        .section-title-row {
            padding-top: 2px;
        }

        @media (min-width: 821px) {
            .product-grid {
                gap: 12px !important;
            }

            .product-card {
                min-height: 0 !important;
            }

            .product-image {
                height: 190px !important;
            }

            .product-body {
                padding: 10px !important;
            }

            .product-category {
                margin-bottom: 5px !important;
            }

            .product-name {
                min-height: 32px !important;
                margin-bottom: 6px !important;
                font-size: 11.5px !important;
            }

            .product-price {
                min-height: 44px !important;
                margin-bottom: 5px !important;
            }

            .price-normal {
                font-size: 16px !important;
            }

            .price-promo {
                font-size: 17px !important;
            }

            .product-status {
                min-height: 16px !important;
                margin-bottom: 7px !important;
            }

            .btn-order,
            .btn-detail {
                min-height: 31px !important;
            }
        }

        @media (max-width: 820px) {
            #home,
            #produtu,
            #pagamentu,
            #kontaktu,
            .section {
                scroll-margin-top: 148px !important;
            }
        }

        @media (max-width: 620px) {
            #home,
            #produtu,
            #pagamentu,
            #kontaktu,
            .section {
                scroll-margin-top: 132px !important;
            }
        }

        @media (max-width: 430px) {
            #home,
            #produtu,
            #pagamentu,
            #kontaktu,
            .section {
                scroll-margin-top: 126px !important;
            }
        }

    
        /*
        |--------------------------------------------------------------------------
        | FINAL NAV ACTIVE + CARD ALIGNMENT POLISH
        |--------------------------------------------------------------------------
        */

        .nav-inner a.js-section-active {
            color: var(--brand-red) !important;
            background: transparent !important;
        }

        .nav-inner a.js-section-active::after {
            content: "";
            position: absolute;
            left: 12px;
            right: 12px;
            bottom: 0;
            height: 2px;
            border-radius: 2px;
            background: var(--brand-red);
        }

        @media (min-width: 821px) {
            .product-card { height: 100% !important; }
            .product-body { min-height: 168px !important; }
            .product-name {
                min-height: 36px !important;
                display: flex !important;
                align-items: flex-start !important;
            }
            .product-price { min-height: 46px !important; }
            .product-status { min-height: 17px !important; }

            .product-stock-label,
            .product-promo-label {
                top: 6px !important;
                min-height: 20px !important;
                display: inline-flex !important;
                align-items: center !important;
                padding: 0 6px !important;
                border-radius: 999px !important;
                font-size: 7.5px !important;
                line-height: 1 !important;
                box-shadow: 0 2px 7px rgba(0,0,0,.08) !important;
            }

            .product-stock-label {
                left: 6px !important;
                max-width: calc(50% - 8px) !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
            }

            .product-promo-label { right: 6px !important; }
        }

        @media (max-width: 620px) {
            .product-stock-label,
            .product-promo-label {
                border-radius: 999px !important;
                box-shadow: 0 2px 6px rgba(0,0,0,.06) !important;
            }
        }

    
        /*
        |--------------------------------------------------------------------------
        | MOBILE SMART HEADER - HIDE DOWN / SHOW UP
        |--------------------------------------------------------------------------
        | Mobile:
        | - scroll turun  -> header sembunyi
        | - scroll naik   -> header muncul kembali
        | - running text tetap normal dan tidak sticky
        | Desktop:
        | - header tetap sticky seperti biasa
        |--------------------------------------------------------------------------
        */

        @media (min-width: 821px) {
            .main-header {
                position: sticky !important;
                top: 0 !important;
                transform: none !important;
            }
        }

        @media (max-width: 820px) {
            .promo-top {
                position: relative !important;
                top: auto !important;
            }

            .main-header {
                position: sticky !important;
                top: 0 !important;
                z-index: 1000 !important;
                transform: translateY(0);
                transition: transform .24s ease, box-shadow .24s ease;
                will-change: transform;
            }

            .main-header.mobile-header-hidden {
                transform: translateY(-100%);
                box-shadow: none !important;
            }

            .main-header.mobile-header-visible {
                transform: translateY(0);
                box-shadow: 0 7px 18px rgba(0,0,0,.08);
            }

            .nav-bar {
                position: relative !important;
                top: auto !important;
            }

            #home,
            #produtu,
            #pagamentu,
            #kontaktu,
            .section {
                scroll-margin-top: 132px !important;
            }
        }

        @media (max-width: 620px) {
            #home,
            #produtu,
            #pagamentu,
            #kontaktu,
            .section {
                scroll-margin-top: 118px !important;
            }
        }

        @media (max-width: 430px) {
            #home,
            #produtu,
            #pagamentu,
            #kontaktu,
            .section {
                scroll-margin-top: 112px !important;
            }
        }

    </style>
</head>


<body>


@php

    /*
    |--------------------------------------------------------------------------
    | TIME / WHATSAPP
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
    | DATA AKTUAL
    |--------------------------------------------------------------------------
    */

    $bannerSlides =
        isset($homepageBanners)
            ? $homepageBanners
            : collect();


    $hasActivePromo =
        isset($activePromoCampaign)
        && $activePromoCampaign !== null
        && isset($campaignPromoProducts)
        && $campaignPromoProducts->isNotEmpty();


    $featuredProducts =
        $products->take(5);


    /*
    |--------------------------------------------------------------------------
    | PRODUK PROMO UNTUK HERO
    |--------------------------------------------------------------------------
    | Area hero HANYA menampilkan produk yang terhubung ke
    | Promo Campaign yang sedang aktif. Homepage banner tidak dipakai
    | di area hero promo ini.
    */

    $heroPromoProducts =
        $hasActivePromo
            ? $activePromoCampaign
                ->products
                ->filter(
                    fn ($item) =>
                        !empty($item->image)
                )
                ->values()
            : collect();


    $campaignTitle =
        $hasActivePromo
            ? trim((string) ($activePromoCampaign->title ?? ''))
            : '';


    $campaignDescription =
        $hasActivePromo
            ? trim((string) ($activePromoCampaign->description ?? ''))
            : '';


    $heroTitle =
        $campaignTitle !== ''
            ? $campaignTitle
            : 'Dulmar Satellite Store';


    $heroDescription =
        $campaignDescription !== ''
            ? $campaignDescription
            : (
                $hasActivePromo
                    ? "Promosaun atual ba produtu sira ne'ebé hili ona. Haree stok no presu atual agora."
                    : "Buka receiver, TV, kabel, speaker, remote no sasán eletróniku seluk ho stok no presu atual."
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

            <div class="brand-logo">

                <img
                    src="{{ asset('images/logo-dulmar.jpg') }}"
                    alt="Dulmar Satellite Store"
                >

            </div>


            <div class="brand-text">

                <strong>
                    Dulmar Satellite Store
                </strong>

                <span>
                    Satellite & Electronics
                </span>

            </div>

        </a>



        <form
            action="{{ route('store.index') }}"
            method="GET"
            class="header-search"
        >

            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Buka receiver, TV, kabel, speaker..."
            >


            <select name="category">

                <option value="">
                    Kategoria hotu
                </option>


                @foreach (
                    $categories
                    as $categoryItem
                )

                    <option
                        value="{{ $categoryItem }}"
                        {{
                            ($category ?? '')
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


            <a
                href="{{ route('store.index') }}"
                class="{{
                    ($category ?? '') === ''
                        ? 'active'
                        : ''
                }}"
            >
                Uma
            </a>


            <a
                href="#produtu"
                id="navProdutu"
            >
                Produtu
            </a>


            @foreach (
                $categories
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
                        ($category ?? '')
                        === $categoryItem
                            ? 'active'
                            : ''
                    }}"
                >
                    {{ $categoryItem }}
                </a>

            @endforeach


            <a
                href="#pagamentu"
                id="navPagamentu"
            >
                Pagamentu
            </a>

            <a
                href="#kontaktu"
                id="navKontaktu"
            >
                Kontaktu
            </a>


        </div>

    </nav>

</header>



{{-- ============================================================
     HERO
============================================================ --}}

<section
    class="hero"
    id="home"
>

    <div class="container hero-inner">


        <div class="hero-copy">

            <h1>
                {{ $heroTitle }}
            </h1>

            <p>
                {{ $heroDescription }}
            </p>

            <div class="hero-cta">

                <span>
                    Presiza ajuda atu hili produtu?
                </span>

                <a
                    href="{{ $generalWhatsappUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Chat WhatsApp →
                </a>

            </div>

        </div>


        <div
            class="hero-media"
            id="heroSlider"
        >

            {{-- ============================================================
                 HANYA PRODUK YANG SEDANG IKUT PROMO CAMPAIGN AKTIF
            ============================================================ --}}

            @if ($heroPromoProducts->isNotEmpty())

                @foreach ($heroPromoProducts as $promoProduct)

                    @php
                        $promoNormalPrice =
                            (float) ($promoProduct->selling_price ?? 0);

                        $promoDiscountType =
                            $promoProduct->pivot->discount_type ?? null;

                        $promoDiscountValue =
                            (float) ($promoProduct->pivot->discount_value ?? 0);

                        $promoFinalPrice =
                            $promoNormalPrice;

                        $promoDiscountPercentage =
                            0;

                        if ($promoDiscountType === 'percent') {

                            $promoDiscountPercentage =
                                $promoDiscountValue;

                            $promoFinalPrice =
                                $promoNormalPrice
                                - (
                                    $promoNormalPrice
                                    * $promoDiscountValue
                                    / 100
                                );

                        } elseif (
                            $promoDiscountType !== null
                            &&
                            $promoDiscountValue > 0
                        ) {

                            $promoFinalPrice =
                                $promoNormalPrice
                                - $promoDiscountValue;

                            if ($promoNormalPrice > 0) {

                                $promoDiscountPercentage =
                                    (
                                        $promoDiscountValue
                                        / $promoNormalPrice
                                    )
                                    * 100;

                            }

                        }

                        if ($promoFinalPrice < 0) {
                            $promoFinalPrice = 0;
                        }
                    @endphp

                    <div
                        class="hero-slide hero-product-slide {{ $loop->first ? 'active' : '' }}"
                    >

                        <div class="hero-slide-image-wrap">

                            <a
                                href="{{ route('store.product.show', $promoProduct) }}"
                                class="hero-product-link"
                                aria-label="Haree detallu {{ $promoProduct->product_name }}"
                            >

                                <img
                                    src="{{ asset('storage/' . $promoProduct->image) }}"
                                    alt="{{ $promoProduct->product_name }}"
                                    class="hero-product-image"
                                >

                                <div class="hero-product-label">

                                    <span class="promo-pill">
                                        PROMO
                                    </span>

                                    <strong>
                                        {{ $promoProduct->product_name }}
                                    </strong>

                                    @if ($promoDiscountPercentage > 0)

                                        <span class="hero-product-discount">
                                            -{{ number_format($promoDiscountPercentage, 0) }}%
                                        </span>

                                    @endif

                                    <span class="hero-product-price">
                                        ${{ number_format($promoFinalPrice, 2) }}
                                    </span>

                                </div>

                            </a>

                        </div>

                    </div>

                @endforeach


                @if ($heroPromoProducts->count() > 1)

                    <button
                        type="button"
                        class="hero-slider-arrow hero-prev"
                        id="heroPrev"
                        aria-label="Produtu promo anterior"
                    >
                        ‹
                    </button>

                    <button
                        type="button"
                        class="hero-slider-arrow hero-next"
                        id="heroNext"
                        aria-label="Produtu promo tuirmai"
                    >
                        ›
                    </button>

                    <div
                        class="hero-counter"
                        id="heroCounter"
                    >
                        1 / {{ $heroPromoProducts->count() }}
                    </div>

                @else

                    <div
                        class="hero-counter"
                        id="heroCounter"
                    >
                        1 / 1
                    </div>

                @endif


            @else

                <div class="hero-promo-empty">

                    <div class="hero-promo-empty-icon">
                        %
                    </div>

                    <strong>
                        La iha produtu promosaun agora
                    </strong>

                    <span>
                        Bainhira Promo Campaign ativu no iha produtu,
                        produtu promosaun sei mosu iha ne'e.
                    </span>

                </div>

            @endif

        </div>

    </div>

</section>


{{-- ============================================================
     CATEGORY FLOATING BOX
============================================================ --}}

<div class="category-floating">

    <div class="container">


        <div class="category-box">


            @foreach (
                $categories->take(5)
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
                    class="category-card"
                >


                    <div class="category-icon">

                        @php

                            $categoryLower =
                                strtolower(
                                    $categoryItem
                                );

                            $categoryIcon =
                                '📦';

                            if (
                                str_contains(
                                    $categoryLower,
                                    'receiver'
                                )
                            ) {
                                $categoryIcon = '📡';
                            } elseif (
                                str_contains(
                                    $categoryLower,
                                    'tv'
                                )
                                ||
                                str_contains(
                                    $categoryLower,
                                    'telvisi'
                                )
                            ) {
                                $categoryIcon = '📺';
                            } elseif (
                                str_contains(
                                    $categoryLower,
                                    'kabel'
                                )
                            ) {
                                $categoryIcon = '🔌';
                            } elseif (
                                str_contains(
                                    $categoryLower,
                                    'speaker'
                                )
                            ) {
                                $categoryIcon = '🔊';
                            } elseif (
                                str_contains(
                                    $categoryLower,
                                    'rca'
                                )
                            ) {
                                $categoryIcon = '🎛️';
                            }

                        @endphp


                        {{ $categoryIcon }}

                    </div>


                    <strong>

                        {{ $categoryItem }}

                    </strong>


                </a>


            @endforeach



            <a
                href="#produtu"
                class="category-card"
            >


                <div class="category-icon">

                    ▦

                </div>


                <strong>

                    Produtu Hotu

                </strong>


            </a>


        </div>


    </div>

</div>



{{-- ============================================================
     FEATURED PRODUCTS
============================================================ --}}

<section class="section">

    <div class="container">


        <div class="section-title-row">


            <div class="section-title-left">

                <span class="star">
                    ★
                </span>

                <h2>
                    Produtu Destaque
                </h2>

            </div>


            <a
                href="#produtu"
                class="section-link"
            >
                Haree Hotu →
            </a>


        </div>



        <div class="product-grid">


            @foreach (
                $featuredProducts
                as $product
            )


                @include(
                    'store.partials.product-card',
                    [
                        'product' =>
                            $product,

                        'hasActivePromo' =>
                            $hasActivePromo,

                        'campaignPromoProducts' =>
                            $campaignPromoProducts,

                        'activePromoCampaign' =>
                            $activePromoCampaign,

                        'greeting' =>
                            $greeting,

                        'storeWhatsapp' =>
                            $storeWhatsapp,
                    ]
                )


            @endforeach


        </div>


    </div>

</section>



{{-- ============================================================
     PROMO BANNERS
============================================================ --}}

<section class="promo-section">

    <div class="container">


        <div class="promo-grid">


            <div class="promo-card-large">

                <small>
                    DULMAR SATELLITE
                </small>

                <h3>
                    Receiver & Accessories
                </h3>

                <p>
                    Buka receiver, kabel,
                    RCA, HDMI, remote no
                    accessories satellite
                    ne'ebé disponivel agora.
                </p>

            </div>



            <div
                class="
                    promo-card-large
                    orange
                "
            >

                <small>
                    DULMAR ELECTRONICS
                </small>

                <h3>
                    TV & Multimedia
                </h3>

                <p>
                    Haree TV, speaker no
                    sasán multimedia seluk
                    ho presu no stok atual.
                </p>

            </div>


        </div>


    </div>

</section>



{{-- ============================================================
     ALL PRODUCTS
============================================================ --}}

<section
    class="section"
    id="produtu"
>

    <div class="container">


        <div class="section-title-row">


            <div class="section-title-left">

                <span class="star">
                    ★
                </span>

                <h2>

                    @if (
                        ($category ?? '')
                        !== ''
                    )

                        {{ $category }}

                    @else

                        Ami Nia Produtu

                    @endif

                </h2>

            </div>


            @if (
                ($search ?? '') !== ''
                ||
                ($category ?? '') !== ''
            )

                <a
                    href="{{ route('store.index') }}"
                    class="section-link"
                >
                    Hamoos Filter
                </a>

            @endif


        </div>



        @if (
            ($search ?? '') !== ''
        )

            <div
                style="
                    margin-bottom:15px;
                    padding:10px 13px;
                    border-radius:8px;
                    background:white;
                    border:1px solid #e5e7eb;
                    font-size:11px;
                    color:#667085;
                "
            >

                Rezultadu buka:

                <strong>
                    {{ $search }}
                </strong>

                —
                {{ $products->count() }}
                produtu.

            </div>

        @endif



        @if (
            $products->isEmpty()
        )

            <div
                style="
                    padding:45px;
                    text-align:center;
                    background:white;
                    border:1px solid #e5e7eb;
                    border-radius:12px;
                "
            >

                <strong>
                    Produtu la hetan.
                </strong>

            </div>

        @else


            <div class="product-grid">


                @foreach (
                    $products
                    as $product
                )


                    @include(
                        'store.partials.product-card',
                        [
                            'product' =>
                                $product,

                            'hasActivePromo' =>
                                $hasActivePromo,

                            'campaignPromoProducts' =>
                                $campaignPromoProducts,

                            'activePromoCampaign' =>
                                $activePromoCampaign,

                            'greeting' =>
                                $greeting,

                            'storeWhatsapp' =>
                                $storeWhatsapp,
                        ]
                    )


                @endforeach


            </div>


        @endif


    </div>

</section>



{{-- ============================================================
     PAYMENT
============================================================ --}}

<section
    class="section"
    id="pagamentu"
>

    <div class="container">


        <div class="section-title-row">

            <div class="section-title-left">

                <span class="star">
                    ★
                </span>

                <h2>
                    Pagamentu & Entrega
                </h2>

            </div>

        </div>



        <div class="info-grid">


            <div class="info-card">

                <h3>
                    Métodu Pagamentu
                </h3>


                <div class="payment-chips">

                    <span class="payment-chip">
                    Cash
                    </span>

                    <span class="payment-chip">
                        Transferénsia Banku
                    </span>

                    <span class="payment-chip">
                        Transferénsia Mosan
                    </span>

                    <span class="payment-chip">
                        Transferénsia T-PAY
                    </span>

                </div>

            </div>



            <div class="info-card">

                <h3>
                    Prosesu Order
                </h3>


                <ul class="steps">


                    <li>

                        <span class="step-number">
                            1
                        </span>

                        Hili produtu no klik
                        Order WhatsApp.

                    </li>


                    <li>

                        <span class="step-number">
                            2
                        </span>

                        Konfirma produtu,
                        kuantidade no entrega.

                    </li>


                    <li>

                        <span class="step-number">
                            3
                        </span>

                        Konfirma pagamentu
                        no simu produtu.

                    </li>


                </ul>


            </div>


        </div>


    </div>

</section>



{{-- ============================================================
     CONTACT
============================================================ --}}

<section
    class="contact-section"
    id="kontaktu"
>

    <div class="container">


        <div class="contact-box">


            <div>

                <h3>
                    Presiza Ajuda?
                </h3>

                <p>
                    Kontaktu Dulmar Satellite
                    Store diretamente liuhusi
                    WhatsApp atu husu kona-ba
                    produtu, presu, stok ka
                    disponibilidade.
                </p>

            </div>


            <a
                href="{{ $generalWhatsappUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="contact-button"
            >
                Kontaktu via WhatsApp
            </a>


        </div>


    </div>

</section>



<footer>

    © {{ date('Y') }}
    Dulmar Satellite Store

</footer>



<script>

    /*
    |--------------------------------------------------------------------------
    | HERO SLIDER
    |--------------------------------------------------------------------------
    */

    const heroSlider =
        document.getElementById(
            'heroSlider'
        );


    if (heroSlider) {


        const slides =
            Array.from(
                heroSlider.querySelectorAll(
                    '.hero-slide'
                )
            );


        const prev =
            document.getElementById(
                'heroPrev'
            );


        const next =
            document.getElementById(
                'heroNext'
            );


        const counter =
            document.getElementById(
                'heroCounter'
            );


        let current = 0;

        let timer = null;

        let animating = false;


        const duration =
            650;


        slides.forEach(
            function (
                slide,
                index
            ) {


                slide.style.transition =
                    'none';


                if (
                    index === 0
                ) {

                    slide.style.transform =
                        'translateX(0)';

                    slide.style.zIndex =
                        '3';

                } else {

                    slide.style.transform =
                        'translateX(100%)';

                    slide.style.zIndex =
                        '1';

                }


            }
        );



        function updateCounter() {

            if (counter) {

                counter.textContent =
                    (current + 1)
                    + ' / '
                    + slides.length;

            }

        }



        function move(
            target,
            direction
        ) {


            if (
                animating
                ||
                slides.length <= 1
            ) {

                return;

            }


            if (
                target >= slides.length
            ) {

                target = 0;

            }


            if (
                target < 0
            ) {

                target =
                    slides.length - 1;

            }


            if (
                target === current
            ) {

                return;

            }


            animating =
                true;


            const oldSlide =
                slides[current];


            const newSlide =
                slides[target];


            oldSlide.style.transition =
                'none';


            newSlide.style.transition =
                'none';


            oldSlide.style.transform =
                'translateX(0)';


            oldSlide.style.zIndex =
                '2';


            newSlide.style.zIndex =
                '3';


            newSlide.style.transform =
                direction === 'next'
                    ? 'translateX(100%)'
                    : 'translateX(-100%)';


            void newSlide.offsetWidth;


            const transition =
                'transform 0.65s cubic-bezier(0.22,1,0.36,1)';


            oldSlide.style.transition =
                transition;


            newSlide.style.transition =
                transition;


            if (
                direction === 'next'
            ) {

                oldSlide.style.transform =
                    'translateX(-100%)';


                newSlide.style.transform =
                    'translateX(0)';

            } else {

                oldSlide.style.transform =
                    'translateX(100%)';


                newSlide.style.transform =
                    'translateX(0)';

            }


            oldSlide.classList.remove(
                'active'
            );


            newSlide.classList.add(
                'active'
            );


            current =
                target;


            updateCounter();


            setTimeout(
                function () {

                    oldSlide.style.transition =
                        'none';


                    oldSlide.style.transform =
                        'translateX(100%)';


                    oldSlide.style.zIndex =
                        '1';


                    animating =
                        false;

                },
                duration
            );


        }



        function nextSlide() {

            move(
                current + 1,
                'next'
            );

        }



        function prevSlide() {

            move(
                current - 1,
                'prev'
            );

        }



        function stopAuto() {

            if (timer) {

                clearInterval(timer);

                timer = null;

            }

        }



        function startAuto() {

            stopAuto();


            if (
                slides.length > 1
            ) {

                timer =
                    setInterval(
                        function () {

                            if (
                                !animating
                            ) {

                                nextSlide();

                            }

                        },
                        4500
                    );

            }

        }



        if (next) {

            next.addEventListener(
                'click',
                function () {

                    nextSlide();
                    startAuto();

                }
            );

        }



        if (prev) {

            prev.addEventListener(
                'click',
                function () {

                    prevSlide();
                    startAuto();

                }
            );

        }



        heroSlider.addEventListener(
            'mouseenter',
            stopAuto
        );


        heroSlider.addEventListener(
            'mouseleave',
            startAuto
        );



        let touchStart =
            0;


        heroSlider.addEventListener(
            'touchstart',
            function (event) {

                touchStart =
                    event
                        .changedTouches[0]
                        .screenX;

                stopAuto();

            },
            {
                passive: true
            }
        );


        heroSlider.addEventListener(
            'touchend',
            function (event) {

                const touchEnd =
                    event
                        .changedTouches[0]
                        .screenX;


                const distance =
                    touchEnd
                    - touchStart;


                if (
                    Math.abs(distance)
                    >= 50
                ) {

                    if (
                        distance < 0
                    ) {

                        nextSlide();

                    } else {

                        prevSlide();

                    }

                }


                startAuto();

            },
            {
                passive: true
            }
        );


        updateCounter();

        startAuto();


    }


    /*
    |--------------------------------------------------------------------------
    | NAVBAR ACTIVE SECTION
    |--------------------------------------------------------------------------
    */

    const navProdutu = document.getElementById('navProdutu');
    const navPagamentu = document.getElementById('navPagamentu');
    const navKontaktu = document.getElementById('navKontaktu');

    const sectionProdutu = document.getElementById('produtu');
    const sectionPagamentu = document.getElementById('pagamentu');
    const sectionKontaktu = document.getElementById('kontaktu');

    function clearSectionNavActive() {
        [navProdutu, navPagamentu, navKontaktu].forEach(function (item) {
            if (item) {
                item.classList.remove('js-section-active');
            }
        });
    }

    function setSectionNavActive(activeLink) {
        clearSectionNavActive();
        if (activeLink) {
            activeLink.classList.add('js-section-active');
        }
    }

    function updateSectionNavByHash() {
        const hash = window.location.hash;

        if (hash === '#produtu') {
            setSectionNavActive(navProdutu);
        } else if (hash === '#pagamentu') {
            setSectionNavActive(navPagamentu);
        } else if (hash === '#kontaktu') {
            setSectionNavActive(navKontaktu);
        } else {
            clearSectionNavActive();
        }
    }

    function updateSectionNavOnScroll() {
        const scrollPosition = window.scrollY + 190;

        if (sectionKontaktu && scrollPosition >= sectionKontaktu.offsetTop) {
            setSectionNavActive(navKontaktu);
            return;
        }

        if (sectionPagamentu && scrollPosition >= sectionPagamentu.offsetTop) {
            setSectionNavActive(navPagamentu);
            return;
        }

        if (sectionProdutu && scrollPosition >= sectionProdutu.offsetTop) {
            setSectionNavActive(navProdutu);
            return;
        }

        clearSectionNavActive();
    }

    window.addEventListener('hashchange', updateSectionNavByHash);
    window.addEventListener('scroll', updateSectionNavOnScroll, { passive: true });

    updateSectionNavByHash();
    updateSectionNavOnScroll();


    /*
    |--------------------------------------------------------------------------
    | MOBILE SMART HEADER
    |--------------------------------------------------------------------------
    | Scroll turun  : header disembunyikan.
    | Scroll naik   : header ditampilkan kembali.
    | Desktop       : tidak dipengaruhi.
    |--------------------------------------------------------------------------
    */

    const mainHeader = document.querySelector('.main-header');
    let lastMobileScrollY = window.scrollY;
    let mobileHeaderTicking = false;
    const mobileHeaderBreakpoint = 820;
    const mobileHeaderThreshold = 8;

    function setMobileHeaderVisible() {
        if (!mainHeader) return;
        mainHeader.classList.remove('mobile-header-hidden');
        mainHeader.classList.add('mobile-header-visible');
    }

    function setMobileHeaderHidden() {
        if (!mainHeader) return;
        mainHeader.classList.remove('mobile-header-visible');
        mainHeader.classList.add('mobile-header-hidden');
    }

    function resetMobileHeader() {
        if (!mainHeader) return;
        mainHeader.classList.remove('mobile-header-hidden', 'mobile-header-visible');
        lastMobileScrollY = window.scrollY;
    }

    function handleMobileHeaderScroll() {
        if (!mainHeader) {
            mobileHeaderTicking = false;
            return;
        }

        if (window.innerWidth > mobileHeaderBreakpoint) {
            resetMobileHeader();
            mobileHeaderTicking = false;
            return;
        }

        const currentScrollY = Math.max(window.scrollY, 0);
        const difference = currentScrollY - lastMobileScrollY;

        if (currentScrollY <= 90) {
            setMobileHeaderVisible();
            lastMobileScrollY = currentScrollY;
            mobileHeaderTicking = false;
            return;
        }

        if (Math.abs(difference) < mobileHeaderThreshold) {
            mobileHeaderTicking = false;
            return;
        }

        if (difference > 0) {
            setMobileHeaderHidden();
        } else {
            setMobileHeaderVisible();
        }

        lastMobileScrollY = currentScrollY;
        mobileHeaderTicking = false;
    }

    window.addEventListener('scroll', function () {
        if (!mobileHeaderTicking) {
            window.requestAnimationFrame(handleMobileHeaderScroll);
            mobileHeaderTicking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', function () {
        if (window.innerWidth > mobileHeaderBreakpoint) {
            resetMobileHeader();
        } else {
            lastMobileScrollY = window.scrollY;
        }
    });

    [navProdutu, navPagamentu, navKontaktu].forEach(function (item) {
        if (!item) return;
        item.addEventListener('click', function () {
            if (window.innerWidth <= mobileHeaderBreakpoint) {
                setMobileHeaderVisible();
            }
        });
    });

    if (window.innerWidth <= mobileHeaderBreakpoint) {
        setMobileHeaderVisible();
    }

</script>


</body>

</html>