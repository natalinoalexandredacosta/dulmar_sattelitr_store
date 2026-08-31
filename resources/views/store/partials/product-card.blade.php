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


    $campaignProduct =
        $hasActivePromo
            ? $campaignPromoProducts
                ->get(
                    $product->id
                )
            : null;


    $promoActive =
        $campaignProduct !== null;


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


    $productMessage =
        $greeting
        . " Dulmar Satellite Store,\n\n"
        . "Hau hakarak halo pedidu:\n\n"
        . "Produtu: {$productName}\n"
        . "Kategoria: {$productCategory}\n";


    if ($promoActive) {

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
    class="
        product-card
        {{ $promoActive ? 'promo' : '' }}
    "
>


    {{-- ============================================================
         PRODUCT IMAGE
    ============================================================ --}}

    <a
        href="{{ route(
            'store.product.show',
            $product
        ) }}"
        class="product-image-link"
    >

        <div class="product-image">


            @if (
                $stock > 0
                &&
                $stock <= 5
            )

                <span class="product-stock-label">
                    Stok Limitadu
                </span>

            @endif


            @if ($promoActive)

                <span class="product-promo-label">

                    PROMO

                    {{
                        number_format(
                            $discountPercentage,
                            0
                        )
                    }}%

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

                <div class="product-no-image">

                    <span>
                        Foto la disponivel
                    </span>

                </div>

            @endif


        </div>

    </a>



    {{-- ============================================================
         PRODUCT BODY
    ============================================================ --}}

    <div class="product-body">


        {{-- CATEGORY --}}

        @if (
            !empty(
                $product->category
            )
        )

            <span class="product-category">

                {{ $product->category }}

            </span>

        @endif



        {{-- PRODUCT NAME --}}

        <div class="product-name">

            <a
                href="{{ route(
                    'store.product.show',
                    $product
                ) }}"
                title="{{ $productName }}"
            >

                {{ $productName }}

            </a>

        </div>



        {{-- PRICE --}}

        <div class="product-price">


            @if ($promoActive)

                <div class="product-price-old-row">

                    <span class="price-old">

                        ${{
                            number_format(
                                $normalPrice,
                                2
                            )
                        }}

                    </span>

                </div>


                <div class="product-price-main-row">

                    <span class="price-promo">

                        ${{
                            number_format(
                                $finalPrice,
                                2
                            )
                        }}

                    </span>


                    @if (
                        $discountPercentage > 0
                    )

                        <span class="discount">

                            -{{
                                number_format(
                                    $discountPercentage,
                                    0
                                )
                            }}%

                        </span>

                    @endif

                </div>

            @else

                <div class="product-price-main-row">

                    <span class="price-normal">

                        ${{
                            number_format(
                                $normalPrice,
                                2
                            )
                        }}

                    </span>

                </div>

            @endif


        </div>



        {{-- STOCK STATUS --}}

        <div class="product-status">


            @if ($stock > 5)

                <span
                    class="
                        product-status-badge
                        status-ok
                    "
                >

                    <span class="status-dot">
                        ●
                    </span>

                    Disponivel

                </span>

            @elseif ($stock > 0)

                <span
                    class="
                        product-status-badge
                        status-low
                    "
                >

                    <span class="status-dot">
                        ●
                    </span>

                    Stok Limitadu

                </span>

            @else

                <span
                    class="
                        product-status-badge
                        status-out
                    "
                >

                    <span class="status-dot">
                        ●
                    </span>

                    Stok Hotu

                </span>

            @endif


        </div>



        {{-- ACTIONS --}}

        <div class="product-actions">


            <a
                href="{{
                    $stock > 0
                        ? $productWhatsappUrl
                        : '#'
                }}"
                target="_blank"
                rel="noopener noreferrer"
                class="
                    btn-order
                    {{
                        $stock <= 0
                            ? 'disabled'
                            : ''
                    }}
                "
            >

                @if ($stock > 0)

                    <span class="btn-order-icon">
                        💬
                    </span>

                    <span>
                        Order WhatsApp
                    </span>

                @else

                    <span>
                        Stok Hotu
                    </span>

                @endif

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