<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Stok Keluar - Dulmar Satellite Store</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 245px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 35px 25px;
            background-color: #1f2b3a;
            color: white;
        }

        .sidebar h1 {
            margin: 0 0 55px;
            font-size: 28px;
        }

        .sidebar-menu {
            flex: 1;
        }

        .sidebar-menu a {
            display: block;
            margin-bottom: 30px;
            color: white;
            font-size: 18px;
            text-decoration: none;
        }

        .sidebar-menu a:hover {
            color: #60a5fa;
        }

        .button-logout {
            width: 100%;
            padding: 13px 15px;
            border: none;
            border-radius: 7px;
            background-color: #dc2626;
            color: white;
            font-size: 17px;
            cursor: pointer;
        }

        .button-logout:hover {
            background-color: #b91c1c;
        }

        .main-content {
            flex: 1;
            padding: 50px;
        }

        .page-header {
            margin-bottom: 35px;
        }

        .page-header h2 {
            margin: 0 0 15px;
            font-size: 36px;
        }

        .page-header p {
            margin: 0;
            color: #4b5563;
            font-size: 18px;
        }

        .form-card {
            max-width: 800px;
            padding: 35px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .alert-error {
            margin-bottom: 25px;
            padding: 15px 20px;
            border: 1px solid #fca5a5;
            border-radius: 6px;
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .stock-warning {
            margin-bottom: 25px;
            padding: 14px 18px;
            border: 1px solid #fcd34d;
            border-radius: 6px;
            background-color: #fef3c7;
            color: #92400e;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 17px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #dc2626;
            outline: none;
        }

        textarea.form-control {
            min-height: 110px;
            resize: vertical;
        }

        .help-text {
            display: block;
            margin-top: 7px;
            color: #6b7280;
            font-size: 14px;
        }

        .calculation-card {
            margin-top: 15px;
            margin-bottom: 25px;
            padding: 25px;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            background-color: #eff6ff;
        }

        .calculation-card h3 {
            margin: 0 0 20px;
            color: #1e3a8a;
            font-size: 21px;
        }

        .calculation-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .calculation-item {
            padding: 16px;
            border-radius: 7px;
            background-color: white;
        }

        .calculation-item span {
            display: block;
            margin-bottom: 7px;
            color: #6b7280;
            font-size: 14px;
        }

        .calculation-item strong {
            font-size: 21px;
        }

        .profit-item {
            grid-column: 1 / -1;
            border-left: 5px solid #16a34a;
        }

        .profit-value {
            color: #16a34a;
        }

        .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            padding: 13px 22px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .button-update {
            background-color: #dc2626;
            color: white;
        }

        .button-update:hover {
            background-color: #b91c1c;
        }

        .button-cancel {
            background-color: #6b7280;
            color: white;
        }

        .button-cancel:hover {
            background-color: #4b5563;
        }

        @media (max-width: 700px) {
            .container {
                display: block;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
            }

            .sidebar-menu {
                margin-bottom: 25px;
            }

            .main-content {
                padding: 30px 20px;
            }

            .form-card {
                padding: 25px 20px;
            }

            .calculation-grid {
                grid-template-columns: 1fr;
            }

            .profit-item {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <aside class="sidebar">
            <h1>Dulmar Satellite Store</h1>

            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}">Dashboard</a>

                <a href="{{ route('products.index') }}">
                    Daftar Barang
                </a>

                <a href="{{ route('stock-ins.index') }}">
                    Stok Masuk
                </a>

                <a href="{{ route('stock-outs.index') }}">
                    Stok Keluar
                </a>

                <a href="{{ route('suppliers.index') }}">
                    Supplier Barang
                </a>

                <a href="{{ route('customers.index') }}">
                    Pelanggan
                </a>

                <a href="{{ route('reports.index') }}">
                    Laporan
                </a>
            </nav>

            <form
                action="{{ route('logout') }}"
                method="POST"
                onsubmit="return confirm('Apakah Anda yakin ingin keluar?')"
            >
                @csrf

                <button type="submit" class="button-logout">
                    Keluar
                </button>
            </form>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Edit Stok Keluar</h2>

                <p>
                    Perbarui transaksi penjualan atau barang keluar.
                </p>
            </div>

            <div class="form-card">

                @if ($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="stock-warning">
                    Jumlah barang tidak boleh melebihi stok yang tersedia.
                    Stok transaksi lama akan diperhitungkan kembali saat data diperbarui.
                </div>

                <form
                    action="{{ route('stock-outs.update', $stockOut) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="product_id">
                            Produk
                        </label>

                        <select
                            id="product_id"
                            name="product_id"
                            class="form-control"
                            required
                        >
                            <option value="">
                                -- Pilih Produk --
                            </option>

                            @foreach ($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    data-purchase-price="{{ $product->purchase_price }}"
                                    data-selling-price="{{ $product->selling_price }}"
                                    data-stock="{{ $product->stock }}"
                                    {{ old('product_id', $stockOut->product_id) == $product->id ? 'selected' : '' }}
                                >
                                    {{ $product->product_name }}
                                    - Stok: {{ $product->stock }}

                                    @if ($product->id == $stockOut->product_id)
                                        + {{ $stockOut->quantity }} transaksi lama
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        <span class="help-text">
                            Pilih produk yang dijual.
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="customer_id">
                            Pelanggan
                        </label>

                        <select
                            id="customer_id"
                            name="customer_id"
                            class="form-control"
                        >
                            <option value="">
                                -- Pilih Pelanggan (Opsional) --
                            </option>

                            @foreach ($customers as $customer)
                                <option
                                    value="{{ $customer->id }}"
                                    {{ old('customer_id', $stockOut->customer_id) == $customer->id ? 'selected' : '' }}
                                >
                                    {{ $customer->customer_name }}
                                </option>
                            @endforeach
                        </select>

                        <span class="help-text">
                            Pilih pelanggan yang membeli barang.
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="quantity">
                            Jumlah yang Dibeli
                        </label>

                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            class="form-control"
                            value="{{ old('quantity', $stockOut->quantity) }}"
                            min="1"
                            required
                            <input
    type="number"
    id="quantity"
    name="quantity"
    min="1"
    value="{{ old('quantity', $stockOut->quantity) }}"
    oninvalid="
        if (this.validity.valueMissing) {
            this.setCustomValidity('Jumlah barang wajib diisi.');
        } else if (this.validity.rangeUnderflow) {
            this.setCustomValidity('Jumlah barang minimal 1 unit.');
        } else if (this.validity.rangeOverflow) {
            this.setCustomValidity(
                'Jumlah barang maksimal ' + this.max +
                ' unit sesuai stok yang tersedia.'
            );
        } else {
            this.setCustomValidity('Masukkan jumlah barang yang valid.');
        }
    "
    oninput="this.setCustomValidity('')"
    required
>
                        >

                        <span class="help-text" id="stockInformation">
                            Masukkan jumlah barang yang dijual.
                        </span>
                    </div>

                    <div class="calculation-card">
                        <h3>Perhitungan Penjualan</h3>

                        <div class="calculation-grid">
                            <div class="calculation-item">
                                <span>Harga Beli per Unit</span>

                                <strong id="purchasePrice">
                                    $0.00
                                </strong>
                            </div>

                            <div class="calculation-item">
                                <span>Harga Jual per Unit</span>

                                <strong id="sellingPrice">
                                    $0.00
                                </strong>
                            </div>

                            <div class="calculation-item">
                                <span>Total Penjualan</span>

                                <strong id="subtotal">
                                    $0.00
                                </strong>
                            </div>

                            <div class="calculation-item">
                                <span>Total Modal</span>

                                <strong id="totalCost">
                                    $0.00
                                </strong>
                            </div>

                            <div class="calculation-item profit-item">
                                <span>Total Keuntungan</span>

                                <strong
                                    id="totalProfit"
                                    class="profit-value"
                                >
                                    $0.00
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="transaction_date">
                            Tanggal Transaksi
                        </label>

                        <input
                            type="date"
                            id="transaction_date"
                            name="transaction_date"
                            class="form-control"
                            value="{{ old(
                                'transaction_date',
                                optional($stockOut->transaction_date)->format('Y-m-d')
                            ) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="notes">
                            Catatan
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            class="form-control"
                            placeholder="Masukkan catatan jika diperlukan"
                        >{{ old('notes', $stockOut->notes) }}</textarea>
                    </div>

                    <div class="form-actions">
                        <button
                            type="submit"
                            class="button button-update"
                        >
                            Simpan Perubahan
                        </button>

                        <a
                            href="{{ route('stock-outs.index') }}"
                            class="button button-cancel"
                        >
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>

    </div>

    <script>
        const productInput =
            document.getElementById('product_id');

        const quantityInput =
            document.getElementById('quantity');

        const purchasePriceElement =
            document.getElementById('purchasePrice');

        const sellingPriceElement =
            document.getElementById('sellingPrice');

        const subtotalElement =
            document.getElementById('subtotal');

        const totalCostElement =
            document.getElementById('totalCost');

        const totalProfitElement =
            document.getElementById('totalProfit');

        const stockInformationElement =
            document.getElementById('stockInformation');

        const oldProductId =
            {{ (int) $stockOut->product_id }};

        const oldQuantity =
            {{ (int) $stockOut->quantity }};

        function formatMoney(value) {
            return '$' + Number(value).toFixed(2);
        }

        function calculateSale() {
            const selectedOption =
                productInput.options[productInput.selectedIndex];

            const purchasePrice = Number(
                selectedOption?.dataset.purchasePrice || 0
            );

            const sellingPrice = Number(
                selectedOption?.dataset.sellingPrice || 0
            );

            const currentStock = Number(
                selectedOption?.dataset.stock || 0
            );

            const selectedProductId = Number(
                selectedOption?.value || 0
            );

            const quantity = Number(quantityInput.value || 0);

            const availableStock =
                selectedProductId === oldProductId
                    ? currentStock + oldQuantity
                    : currentStock;

            const subtotal = sellingPrice * quantity;
            const totalCost = purchasePrice * quantity;

            const totalProfit =
                (sellingPrice - purchasePrice) * quantity;

            purchasePriceElement.textContent =
                formatMoney(purchasePrice);

            sellingPriceElement.textContent =
                formatMoney(sellingPrice);

            subtotalElement.textContent =
                formatMoney(subtotal);

            totalCostElement.textContent =
                formatMoney(totalCost);

            totalProfitElement.textContent =
                formatMoney(totalProfit);

            if (selectedProductId > 0) {
                stockInformationElement.textContent =
                    'Stok yang dapat digunakan: '
                    + availableStock
                    + ' unit.';

                quantityInput.max = availableStock;
            } else {
                stockInformationElement.textContent =
                    'Pilih produk terlebih dahulu.';

                quantityInput.removeAttribute('max');
            }
        }

        productInput.addEventListener(
            'change',
            calculateSale
        );

        quantityInput.addEventListener(
            'input',
            calculateSale
        );

        calculateSale();
    </script>
</body>
</html>