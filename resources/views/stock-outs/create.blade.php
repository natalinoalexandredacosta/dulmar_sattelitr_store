<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Stok Keluar - Dulmar Satellite Store</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-dulmar.jpg') }}">

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f6f9; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 245px; min-height: 100vh; display: flex; flex-shrink: 0; flex-direction: column; padding: 35px 25px; background: #1f2b3a; color: white; }
        .sidebar h1 { margin: 0 0 55px; font-size: 28px; }
        .sidebar-menu { flex: 1; }
        .sidebar-menu a { display: block; margin-bottom: 30px; color: white; font-size: 18px; text-decoration: none; }
        .sidebar-menu a:hover { color: #60a5fa; }
        .sidebar-menu a.active { padding: 12px 14px; border-left: 4px solid #60a5fa; border-radius: 6px; background: rgba(37, 99, 235, .3); color: #bfdbfe; font-weight: bold; }
        .button-logout { width: 100%; padding: 13px 15px; border: none; border-radius: 7px; background: #dc2626; color: white; font-size: 17px; cursor: pointer; }
        .button-logout:hover { background: #b91c1c; }
        .main-content { flex: 1; min-width: 0; padding: 50px; }
        .page-header { margin-bottom: 35px; }
        .page-header h2 { margin: 0 0 15px; font-size: 36px; }
        .page-header p { margin: 0; color: #4b5563; font-size: 18px; }
        .form-card { width: 100%; max-width: 850px; padding: 35px; border-radius: 10px; background: white; box-shadow: 0 2px 8px rgba(0, 0, 0, .08); }
        .alert-error { margin-bottom: 25px; padding: 15px 20px; border: 1px solid #fca5a5; border-radius: 6px; background: #fee2e2; color: #991b1b; }
        .alert-error ul { margin: 0; padding-left: 20px; }
        .stock-warning { margin-bottom: 25px; padding: 14px 18px; border: 1px solid #fcd34d; border-radius: 6px; background: #fef3c7; color: #92400e; font-size: 15px; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 10px; font-size: 17px; font-weight: bold; }
        .form-control { width: 100%; padding: 13px 15px; border: 1px solid #d1d5db; border-radius: 6px; background: white; font-size: 16px; }
        .form-control:focus { border-color: #dc2626; outline: none; box-shadow: 0 0 0 3px rgba(220, 38, 38, .1); }
        textarea.form-control { min-height: 110px; resize: vertical; }
        .help-text { display: block; margin-top: 7px; color: #6b7280; font-size: 14px; }
        .calculation-card { margin-bottom: 25px; padding: 25px; border: 1px solid #bfdbfe; border-radius: 10px; background: #eff6ff; }
        .calculation-card h3 { margin: 0 0 20px; color: #1e3a8a; font-size: 20px; }
        .calculation-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .calculation-item { padding: 15px; border-radius: 7px; background: white; }
        .calculation-item span { display: block; margin-bottom: 7px; color: #6b7280; font-size: 14px; }
        .calculation-item strong { color: #1f2937; font-size: 20px; }
        .subtotal-item { border-left: 5px solid #2563eb; }
        .capital-item { border-left: 5px solid #f59e0b; }
        .profit-item { grid-column: 1 / -1; border-left: 5px solid #16a34a; }
        .profit-item strong { color: #16a34a; font-size: 24px; }
        .form-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 30px; }
        .button { display: inline-block; padding: 13px 22px; border: none; border-radius: 6px; font-size: 16px; text-decoration: none; cursor: pointer; }
        .button-save { background: #dc2626; color: white; }
        .button-save:hover { background: #b91c1c; }
        .button-cancel { background: #6b7280; color: white; }
        .button-cancel:hover { background: #4b5563; }

        @media (max-width: 700px) {
            .container { display: block; }
            .sidebar { width: 100%; min-height: auto; }
            .sidebar-menu { margin-bottom: 25px; }
            .main-content { padding: 30px 15px; }
            .page-header h2 { font-size: 30px; }
            .form-card { padding: 25px 18px; }
            .calculation-grid { grid-template-columns: 1fr; }
            .profit-item { grid-column: auto; }
            .button { width: 100%; text-align: center; }
        }
    </style>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <h1>Dulmar Satellite Store</h1>
            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('products.index') }}">Daftar Barang</a>
                <a href="{{ route('stock-ins.index') }}">Stok Masuk</a>
                <a href="{{ route('stock-outs.index') }}" class="active">Stok Keluar</a>
                <a href="{{ route('suppliers.index') }}">Supplier Barang</a>
                <a href="{{ route('customers.index') }}">Pelanggan</a>
                <a href="{{ route('reports.index') }}">Laporan</a>
            </nav>
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                @csrf
                <button type="submit" class="button-logout">Keluar</button>
            </form>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Tambah Stok Keluar</h2>
                <p>Catat transaksi penjualan produk kepada pelanggan.</p>
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

                <div class="stock-warning">Jumlah stok keluar tidak boleh melebihi stok yang tersedia.</div>

                <form action="{{ route('stock-outs.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="product_id">Produk</label>
                        <select id="product_id" name="product_id" class="form-control" required>
                            <option value="" data-purchase-price="0" data-selling-price="0" data-stock="0">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    data-purchase-price="{{ $product->purchase_price }}"
                                    data-selling-price="{{ $product->selling_price }}"
                                    data-stock="{{ $product->stock }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}
                                >
                                    {{ $product->product_name }} - Stok: {{ $product->stock }} - Harga jual: ${{ number_format($product->selling_price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <span class="help-text">Pilih produk yang akan dijual.</span>
                    </div>

                    <div class="form-group">
                        <label for="customer_id">Pelanggan</label>
                        <select id="customer_id" name="customer_id" class="form-control">
                            <option value="">-- Pilih Pelanggan (Opsional) --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->customer_name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="help-text">Pilih pelanggan yang membeli produk.</span>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Jumlah yang Dibeli</label>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            class="form-control"
                            value="{{ old('quantity', 1) }}"
                            min="1"
                            required
                        >
                        <span class="help-text" id="stockInformation">Pilih produk untuk melihat stok tersedia.</span>
                    </div>

                    <div class="calculation-card">
                        <h3>Perhitungan Penjualan</h3>
                        <div class="calculation-grid">
                            <div class="calculation-item"><span>Harga Beli per Unit</span><strong id="hargaBeli">$0.00</strong></div>
                            <div class="calculation-item"><span>Harga Jual per Unit</span><strong id="hargaJual">$0.00</strong></div>
                            <div class="calculation-item subtotal-item"><span>Subtotal Penjualan</span><strong id="subtotal">$0.00</strong></div>
                            <div class="calculation-item capital-item"><span>Total Modal</span><strong id="totalModal">$0.00</strong></div>
                            <div class="calculation-item profit-item"><span>Total Keuntungan</span><strong id="totalKeuntungan">$0.00</strong></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="transaction_date">Tanggal Transaksi</label>
                        <input type="date" id="transaction_date" name="transaction_date" class="form-control" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan</label>
                        <textarea id="notes" name="notes" class="form-control">{{ old('notes') }}</textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="button button-save">Simpan Stok Keluar</button>
                        <a href="{{ route('stock-outs.index') }}" class="button button-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const productInput = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const stockInformation = document.getElementById('stockInformation');

        function formatUang(nilai) {
            return '$' + Number(nilai).toFixed(2);
        }

        function updateQuantityMessage() {
            quantityInput.setCustomValidity('');

            if (quantityInput.validity.valueMissing) {
                quantityInput.setCustomValidity('Jumlah barang wajib diisi.');
            } else if (quantityInput.validity.rangeUnderflow) {
                quantityInput.setCustomValidity('Jumlah barang minimal 1 unit.');
            } else if (quantityInput.validity.rangeOverflow) {
                quantityInput.setCustomValidity(
                    'Jumlah barang maksimal ' + quantityInput.max +
                    ' unit sesuai stok yang tersedia.'
                );
            }
        }

        function hitungPenjualan() {
            const pilihan = productInput.options[productInput.selectedIndex];
            const jumlah = Number(quantityInput.value || 0);
            const hargaBeli = Number(pilihan?.dataset.purchasePrice || 0);
            const hargaJual = Number(pilihan?.dataset.sellingPrice || 0);
            const stokTersedia = Number(pilihan?.dataset.stock || 0);

            document.getElementById('hargaBeli').textContent = formatUang(hargaBeli);
            document.getElementById('hargaJual').textContent = formatUang(hargaJual);
            document.getElementById('subtotal').textContent = formatUang(hargaJual * jumlah);
            document.getElementById('totalModal').textContent = formatUang(hargaBeli * jumlah);
            document.getElementById('totalKeuntungan').textContent = formatUang((hargaJual - hargaBeli) * jumlah);

            if (productInput.value !== '') {
                quantityInput.max = stokTersedia;
                stockInformation.textContent = 'Stok tersedia: ' + stokTersedia + ' unit.';
            } else {
                quantityInput.removeAttribute('max');
                stockInformation.textContent = 'Pilih produk untuk melihat stok tersedia.';
            }

            updateQuantityMessage();
        }

        productInput.addEventListener('change', hitungPenjualan);
        quantityInput.addEventListener('input', hitungPenjualan);
        quantityInput.addEventListener('invalid', updateQuantityMessage);
        hitungPenjualan();
    </script>
</body>
</html>