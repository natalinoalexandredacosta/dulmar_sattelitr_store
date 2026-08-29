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

        /*
        |--------------------------------------------------------------------------
        | ANCHOR OFFSET
        |--------------------------------------------------------------------------
        */

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
            justify-content: center;
            align-items: center;

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

            margin-bottom: 28px;
            padding: 20px;

            border:
                1px solid
                var(--border);

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

            border:
                1px solid
                var(--border);

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

            border:
                1px solid
                var(--border);

            background: white;
            color: var(--text);

            text-decoration: none;
        }

        .btn-ghost:hover {
            background: #f8fafc;
        }

        .filter-result {
            margin-top: -12px;
            margin-bottom: 22px;

            color: var(--muted);
            font-size: 13px;
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

            border:
                1px solid
                var(--border);

            border-radius: 10px;
            background: var(--card);

            box-shadow:
                0 2px 7px
                rgba(0, 0, 0, 0.04);

            transition:
                transform 0.2s,
                box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);

            box-shadow:
                0 7px 18px
                rgba(0, 0, 0, 0.09);
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
        }

        .thumb-tag {
            position: absolute;

            top: 8px;
            left: 8px;
            z-index: 2;

            padding: 4px 7px;

            border-radius: 4px;

            background:
                rgba(0, 0, 0, 0.62);

            color: white;

            font-size: 10px;
            font-weight: 700;
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

            margin-bottom: 7px;

            font-size: 14px;
            font-weight: 700;

            line-height: 1.4;
        }

        .price {
            margin-bottom: 7px;

            color: var(--blue-link);

            font-size: 16px;
            font-weight: 700;
        }

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

        .card-actions {
            display: flex;

            gap: 7px;

            margin-top: auto;
            padding-top: 8px;
        }

        .btn-wa {
            display: flex;

            flex: 1;

            align-items: center;
            justify-content: center;

            min-height: 40px;
            padding: 10px;

            border: none;
            border-radius: 6px;

            background: var(--green);
            color: white;

            text-align: center;
            text-decoration: none;

            font-size: 12px;
            font-weight: 700;

            transition: background 0.2s;
        }

        .btn-wa:hover {
            background: var(--green-dark);
        }

        .btn-wa.disabled {
            pointer-events: none;
            background: #9ca3af;
        }

        /*
        |--------------------------------------------------------------------------
        | EMPTY STATE
        |--------------------------------------------------------------------------
        */

        .empty-state {
            padding: 50px 20px;

            border:
                1px solid
                var(--border);

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
        | PAYMENT / DELIVERY
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

            border:
                1px solid
                var(--border);

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
        | RESPONSIVE - TABLET
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

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE - MOBILE
        |--------------------------------------------------------------------------
        */

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

            .hero p {
                font-size: 14px;
            }

            .trust-bar-inner {
                gap: 18px;
                justify-content: flex-start;
            }

            .grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE - SMALL MOBILE
        |--------------------------------------------------------------------------
        */

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
    $storeWhatsapp = '67076732586';

    $generalWhatsappMessage =
        "Bondia Dulmar Satellite Store,\n\n"
        . "Hau hakarak husu informasaun kona-ba "
        . "produtu sira.";

    $generalWhatsappUrl =
        'https://wa.me/'
        . $storeWhatsapp
        . '?text='
        . urlencode($generalWhatsappMessage);
@endphp


{{-- ============================================================
    TOPBAR
============================================================ --}}

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


{{-- ============================================================
    HERO
============================================================ --}}

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


{{-- ============================================================
    TRUST BAR
============================================================ --}}

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


<div class="wrap">


    {{-- ========================================================
        PRODUCT SECTION
    ========================================================= --}}

    <section id="produtu">

        <h2 class="section-title">
            Ami Nia Produtu
        </h2>

        <p class="sub">
            Hili produtu ne'ebé ita presiza
            ho naran ka kategoria.
        </p>


        {{-- SEARCH --}}

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

                    @foreach ($categories as $categoryItem)

                        <option
                            value="{{ $categoryItem }}"
                            {{ ($category ?? '') === $categoryItem
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


        @if (($search ?? '') !== '' || ($category ?? '') !== '')

            <div class="filter-result">

                Rezultadu:

                <strong>
                    {{ $products->count() }}
                </strong>

                produtu.

            </div>

        @endif


        {{-- ====================================================
            PRODUCTS
        ===================================================== --}}

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


            <div class="grid">

                @foreach ($products as $product)

                    @php
                        $productName =
                            $product->product_name
                            ?? 'Produtu';

                        $productCategory =
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
                            . "Hau hakarak halo pedidu "
                            . "produtu ida-ne'e:\n\n"

                            . "Produtu: {$productName}\n"

                            . "Kategoria: {$productCategory}\n"

                            . "Presu: $"
                            . number_format(
                                $price,
                                2
                            )
                            . "\n"

                            . "Kuantidade: 1\n\n"

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


                    <article class="card">


                        {{-- FOTO PRODUK --}}

                        <div class="thumb">

                            @if ($stock > 0 && $stock <= 5)

                                <span class="thumb-tag">
                                    Stok Limitadu
                                </span>

                            @endif


                            @if (!empty($product->image))

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


                        {{-- INFORMASI PRODUK --}}

                        <div class="card-body">


                            @if (!empty($product->category))

                                <span class="badge">
                                    {{ $product->category }}
                                </span>

                            @endif


                            <div class="card-title">
                                {{ $productName }}
                            </div>


                            <div class="price">

                                ${{ number_format(
                                    $price,
                                    2
                                ) }}

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

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>

        @endif

    </section>


    {{-- ========================================================
        PAYMENT & DELIVERY
    ========================================================= --}}

    <section id="pagamentu">

        <h2 class="section-title">
            Pagamentu & Entrega
        </h2>

        <p class="sub">
            Informasaun importante antes
            halo pedidu liuhusi WhatsApp.
        </p>


        <div class="info-grid">


            {{-- PAYMENT --}}

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


            {{-- ORDER PROCESS --}}

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


    {{-- ========================================================
        CONTACT
    ========================================================= --}}

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


{{-- ============================================================
    FOOTER
============================================================ --}}

<footer>

    © {{ date('Y') }}
    Dulmar Satellite Store

</footer>

</body>
</html>