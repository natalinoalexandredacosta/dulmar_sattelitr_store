<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Barang Dibawa Team</title>

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

        .page-wrapper {
            min-height: 100vh;
            padding: 30px;
        }

        .form-card {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        }

        .page-title {
            margin: 0 0 5px;
            font-size: 28px;
            color: #111827;
        }

        .page-subtitle {
            margin: 0 0 25px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .main-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 13px;
            font-weight: 700;
            color: #374151;
        }

        input,
        select,
        textarea {
            width: 100%;
            min-height: 44px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            outline: none;
            background: white;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #2563eb;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        .products-section {
            margin-top: 25px;
        }

        .products-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
        }

        .products-title {
            margin: 0;
            font-size: 19px;
            color: #111827;
        }

        .products-description {
            margin: 5px 0 0;
            color: #64748b;
            font-size: 13px;
        }

        .items-wrapper {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .item-row {
            position: relative;
            display: grid;
            grid-template-columns:
                minmax(220px, 2fr)
                minmax(100px, 0.7fr)
                minmax(100px, 0.7fr)
                minmax(100px, 0.7fr)
                80px;
            gap: 12px;
            align-items: end;

            padding: 16px;

            border: 1px solid #dbe3ef;
            border-radius: 10px;

            background: #f8fafc;
        }

        .item-number {
            position: absolute;
            top: -10px;
            left: 15px;

            padding: 4px 10px;

            border-radius: 20px;

            background: #1f2937;
            color: white;

            font-size: 11px;
            font-weight: bold;
        }

        .qty-input {
            text-align: center;
        }

        .remaining-box {
            min-height: 44px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: 1px solid #bfdbfe;
            border-radius: 8px;

            background: #eff6ff;
            color: #1d4ed8;

            font-size: 18px;
            font-weight: 800;
        }

        .remove-button {
            width: 100%;
            min-height: 44px;

            border: 0;
            border-radius: 8px;

            background: #dc2626;
            color: white;

            font-weight: 700;
            cursor: pointer;
        }

        .remove-button:hover {
            background: #b91c1c;
        }

        .add-product-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 42px;
            padding: 10px 15px;

            border: none;
            border-radius: 8px;

            background: #059669;
            color: white;

            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .add-product-button:hover {
            background: #047857;
        }

        .summary-box {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;

            margin-top: 22px;
            padding: 18px;

            border: 1px solid #bfdbfe;
            border-radius: 10px;

            background: #eff6ff;
        }

        .summary-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .summary-label {
            color: #475569;
            font-size: 12px;
            font-weight: 700;
        }

        .summary-value {
            color: #1d4ed8;
            font-size: 26px;
            font-weight: 800;
        }

        .helper {
            margin-top: 10px;
            color: #64748b;
            font-size: 12px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 44px;

            padding: 10px 18px;

            border: 0;
            border-radius: 8px;

            text-decoration: none;

            cursor: pointer;

            font-size: 14px;
            font-weight: 700;
        }

        .btn-save {
            background: #2563eb;
            color: white;
        }

        .btn-save:hover {
            background: #1d4ed8;
        }

        .btn-back {
            background: #64748b;
            color: white;
        }

        .btn-back:hover {
            background: #475569;
        }

        @media (max-width: 900px) {
            .item-row {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .item-product {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 650px) {
            .page-wrapper {
                padding: 15px;
            }

            .form-card {
                padding: 20px;
            }

            .main-info {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .products-header {
                flex-direction: column;
                align-items: stretch;
            }

            .add-product-button {
                width: 100%;
            }

            .item-row {
                grid-template-columns: 1fr;
                padding-top: 25px;
            }

            .item-product {
                grid-column: auto;
            }

            .summary-box {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .actions .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="page-wrapper">

    <div class="form-card">

        <h1 class="page-title">
            Tambah Barang Dibawa Team
        </h1>

        <p class="page-subtitle">
            Satu team dapat membawa beberapa jenis barang sekaligus.
            Tambahkan semua produk kemudian klik Simpan Semua.
        </p>


        @if ($errors->any())

            <div class="alert alert-error">

                <ul>
                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach
                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('team-product-carries.store') }}"
            id="carryForm"
        >

            @csrf


            <div class="main-info">

                <div class="form-group">

                    <label for="team_name">
                        Nama Team *
                    </label>

                    <input
                        type="text"
                        id="team_name"
                        name="team_name"
                        value="{{ old('team_name') }}"
                        placeholder="Contoh: Xisto"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="taken_at">
                        Tanggal Dibawa *
                    </label>

                    <input
                        type="date"
                        id="taken_at"
                        name="taken_at"
                        value="{{ old('taken_at', now()->toDateString()) }}"
                        required
                    >

                </div>


                <div class="form-group full">

                    <label for="notes">
                        Catatan
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        placeholder="Contoh: Barang dibawa untuk penjualan lapangan..."
                    >{{ old('notes') }}</textarea>

                </div>

            </div>


            <section class="products-section">

                <div class="products-header">

                    <div>
                        <h2 class="products-title">
                            Daftar Barang
                        </h2>

                        <p class="products-description">
                            Tambahkan satu atau beberapa produk yang dibawa oleh team.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="add-product-button"
                        id="addProductButton"
                    >
                        + Tambah Produk
                    </button>

                </div>


                <div
                    class="items-wrapper"
                    id="itemsWrapper"
                >

                    @php
                        $oldItems = old('items');

                        if (!is_array($oldItems) || count($oldItems) === 0) {
                            $oldItems = [
                                [
                                    'product_id' => '',
                                    'quantity_taken' => 1,
                                    'quantity_sold' => 0,
                                    'quantity_returned' => 0,
                                ]
                            ];
                        }
                    @endphp


                    @foreach ($oldItems as $index => $item)

                        <div
                            class="item-row"
                            data-index="{{ $index }}"
                        >

                            <div class="item-number">
                                Barang {{ $loop->iteration }}
                            </div>


                            <div class="form-group item-product">

                                <label>
                                    Produk *
                                </label>

                                <select
                                    name="items[{{ $index }}][product_id]"
                                    class="product-select"
                                    required
                                >

                                    <option value="">
                                        -- Pilih Produk --
                                    </option>

                                    @foreach ($products as $product)

                                        <option
                                            value="{{ $product->id }}"
                                            {{ (string)($item['product_id'] ?? '') === (string)$product->id ? 'selected' : '' }}
                                        >
                                            {{ $product->product_name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="form-group">

                                <label>
                                    Qty Dibawa *
                                </label>

                                <input
                                    type="number"
                                    name="items[{{ $index }}][quantity_taken]"
                                    class="quantity-taken qty-input"
                                    min="1"
                                    value="{{ $item['quantity_taken'] ?? 1 }}"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label>
                                    Qty Jual
                                </label>

                                <input
                                    type="number"
                                    name="items[{{ $index }}][quantity_sold]"
                                    class="quantity-sold qty-input"
                                    min="0"
                                    value="{{ $item['quantity_sold'] ?? 0 }}"
                                >

                            </div>


                            <div class="form-group">

                                <label>
                                    Qty Kembali
                                </label>

                                <input
                                    type="number"
                                    name="items[{{ $index }}][quantity_returned]"
                                    class="quantity-returned qty-input"
                                    min="0"
                                    value="{{ $item['quantity_returned'] ?? 0 }}"
                                >

                            </div>


                            <div class="form-group">

                                <label>
                                    Sisa
                                </label>

                                <div class="remaining-box">
                                    0
                                </div>

                            </div>


                            <div class="form-group">

                                <label>
                                    Aksi
                                </label>

                                <button
                                    type="button"
                                    class="remove-button"
                                >
                                    Hapus
                                </button>

                            </div>

                        </div>

                    @endforeach

                </div>


                <div class="summary-box">

                    <div class="summary-item">

                        <span class="summary-label">
                            Total Jenis Barang
                        </span>

                        <span
                            class="summary-value"
                            id="totalProductTypes"
                        >
                            1
                        </span>

                    </div>


                    <div class="summary-item">

                        <span class="summary-label">
                            Total Barang Masih di Team
                        </span>

                        <span
                            class="summary-value"
                            id="totalRemaining"
                        >
                            0
                        </span>

                    </div>

                </div>


                <div class="helper">
                    Sisa setiap barang = Qty Dibawa - Qty Jual - Qty Kembali.
                </div>

            </section>


            <div class="actions">

                <button
                    type="submit"
                    class="btn btn-save"
                >
                    Simpan Semua
                </button>

                <a
                    href="{{ route('team-product-carries.index') }}"
                    class="btn btn-back"
                >
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>


<template id="productRowTemplate">

    <div
        class="item-row"
        data-index="__INDEX__"
    >

        <div class="item-number">
            Barang
        </div>


        <div class="form-group item-product">

            <label>
                Produk *
            </label>

            <select
                name="items[__INDEX__][product_id]"
                class="product-select"
                required
            >

                <option value="">
                    -- Pilih Produk --
                </option>

                @foreach ($products as $product)

                    <option value="{{ $product->id }}">
                        {{ $product->product_name }}
                    </option>

                @endforeach

            </select>

        </div>


        <div class="form-group">

            <label>
                Qty Dibawa *
            </label>

            <input
                type="number"
                name="items[__INDEX__][quantity_taken]"
                class="quantity-taken qty-input"
                min="1"
                value="1"
                required
            >

        </div>


        <div class="form-group">

            <label>
                Qty Jual
            </label>

            <input
                type="number"
                name="items[__INDEX__][quantity_sold]"
                class="quantity-sold qty-input"
                min="0"
                value="0"
            >

        </div>


        <div class="form-group">

            <label>
                Qty Kembali
            </label>

            <input
                type="number"
                name="items[__INDEX__][quantity_returned]"
                class="quantity-returned qty-input"
                min="0"
                value="0"
            >

        </div>


        <div class="form-group">

            <label>
                Sisa
            </label>

            <div class="remaining-box">
                1
            </div>

        </div>


        <div class="form-group">

            <label>
                Aksi
            </label>

            <button
                type="button"
                class="remove-button"
            >
                Hapus
            </button>

        </div>

    </div>

</template>


<script>
    const itemsWrapper =
        document.getElementById('itemsWrapper');

    const addProductButton =
        document.getElementById('addProductButton');

    const template =
        document.getElementById('productRowTemplate');

    let nextIndex =
        {{ count($oldItems) }};


    function calculateRow(row) {

        const taken =
            parseInt(
                row.querySelector('.quantity-taken').value
            ) || 0;

        const sold =
            parseInt(
                row.querySelector('.quantity-sold').value
            ) || 0;

        const returned =
            parseInt(
                row.querySelector('.quantity-returned').value
            ) || 0;

        const remaining =
            Math.max(
                0,
                taken - sold - returned
            );

        row.querySelector(
            '.remaining-box'
        ).textContent = remaining;
    }


    function updateSummary() {

        const rows =
            itemsWrapper.querySelectorAll('.item-row');

        let totalRemaining = 0;

        rows.forEach(
            function (row, index) {

                const number =
                    row.querySelector('.item-number');

                number.textContent =
                    'Barang ' + (index + 1);

                calculateRow(row);

                totalRemaining +=
                    parseInt(
                        row.querySelector('.remaining-box').textContent
                    ) || 0;
            }
        );

        document.getElementById(
            'totalProductTypes'
        ).textContent = rows.length;

        document.getElementById(
            'totalRemaining'
        ).textContent = totalRemaining;
    }


    function bindRowEvents(row) {

        row.querySelectorAll(
            '.quantity-taken, .quantity-sold, .quantity-returned'
        ).forEach(
            function (input) {

                input.addEventListener(
                    'input',
                    updateSummary
                );
            }
        );


        row.querySelector(
            '.remove-button'
        ).addEventListener(
            'click',
            function () {

                const rows =
                    itemsWrapper.querySelectorAll('.item-row');

                if (rows.length <= 1) {

                    alert(
                        'Minimal harus ada satu produk.'
                    );

                    return;
                }

                row.remove();

                updateSummary();
            }
        );
    }


    addProductButton.addEventListener(
        'click',
        function () {

            const html =
                template.innerHTML.replaceAll(
                    '__INDEX__',
                    nextIndex
                );

            const holder =
                document.createElement('div');

            holder.innerHTML =
                html.trim();

            const newRow =
                holder.firstElementChild;

            itemsWrapper.appendChild(
                newRow
            );

            bindRowEvents(
                newRow
            );

            nextIndex++;

            updateSummary();
        }
    );


    itemsWrapper
        .querySelectorAll('.item-row')
        .forEach(
            function (row) {

                bindRowEvents(
                    row
                );
            }
        );


    document
        .getElementById('carryForm')
        .addEventListener(
            'submit',
            function (event) {

                const rows =
                    itemsWrapper.querySelectorAll('.item-row');

                const selectedProducts =
                    new Set();

                let valid = true;

                rows.forEach(
                    function (row) {

                        const product =
                            row.querySelector(
                                '.product-select'
                            ).value;

                        const taken =
                            parseInt(
                                row.querySelector(
                                    '.quantity-taken'
                                ).value
                            ) || 0;

                        const sold =
                            parseInt(
                                row.querySelector(
                                    '.quantity-sold'
                                ).value
                            ) || 0;

                        const returned =
                            parseInt(
                                row.querySelector(
                                    '.quantity-returned'
                                ).value
                            ) || 0;


                        if (
                            sold + returned >
                            taken
                        ) {

                            alert(
                                'Qty Jual + Qty Kembali tidak boleh lebih besar dari Qty Dibawa.'
                            );

                            valid = false;

                            return;
                        }


                        if (
                            product &&
                            selectedProducts.has(product)
                        ) {

                            alert(
                                'Produk yang sama tidak boleh dipilih dua kali.'
                            );

                            valid = false;

                            return;
                        }


                        if (product) {
                            selectedProducts.add(
                                product
                            );
                        }
                    }
                );


                if (!valid) {
                    event.preventDefault();
                }
            }
        );


    updateSummary();
</script>

</body>
</html>