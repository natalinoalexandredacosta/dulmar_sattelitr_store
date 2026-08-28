<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stok Masuk - Dulmar Satellite Store</title>

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
        .main-content { flex: 1; min-width: 0; padding: 50px; }
        .page-header { margin-bottom: 35px; }
        .page-header h2 { margin: 0 0 15px; font-size: 36px; }
        .page-header p { margin: 0; color: #4b5563; font-size: 18px; }
        .form-card { width: 100%; max-width: 800px; padding: 35px; border-radius: 10px; background: white; box-shadow: 0 2px 8px rgba(0, 0, 0, .08); }
        .alert-error { margin-bottom: 25px; padding: 15px 20px; border: 1px solid #fca5a5; border-radius: 6px; background: #fee2e2; color: #991b1b; }
        .alert-error ul { margin: 0; padding-left: 20px; }
        .stock-warning { margin-bottom: 25px; padding: 14px 18px; border: 1px solid #fcd34d; border-radius: 6px; background: #fef3c7; color: #92400e; font-size: 15px; line-height: 1.5; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 10px; font-size: 17px; font-weight: bold; }
        .form-control { width: 100%; padding: 13px 15px; border: 1px solid #d1d5db; border-radius: 6px; background: white; font-size: 16px; }
        .form-control:focus { border-color: #16a34a; outline: none; box-shadow: 0 0 0 3px rgba(22, 163, 74, .12); }
        textarea.form-control { min-height: 110px; resize: vertical; }
        .help-text { display: block; margin-top: 7px; color: #6b7280; font-size: 14px; }
        .calculation-card { margin-bottom: 25px; padding: 25px; border: 1px solid #bbf7d0; border-radius: 10px; background: #f0fdf4; }
        .calculation-card h3 { margin: 0 0 20px; color: #166534; font-size: 20px; }
        .calculation-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .calculation-item { padding: 16px; border-radius: 7px; background: white; }
        .calculation-item span { display: block; margin-bottom: 7px; color: #6b7280; font-size: 14px; }
        .calculation-item strong { font-size: 21px; }
        .new-stock { border-left: 5px solid #16a34a; }
        .new-stock strong { color: #16a34a; }
        .estimated-cost { border-left: 5px solid #f59e0b; }
        .estimated-cost strong { color: #d97706; }
        .form-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 30px; }
        .button { display: inline-block; padding: 13px 22px; border: none; border-radius: 6px; font-size: 16px; text-decoration: none; cursor: pointer; }
        .button-update { background: #16a34a; color: white; }
        .button-update:hover { background: #15803d; }
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
                <a href="{{ route('stock-ins.index') }}" class="active">Stok Masuk</a>
                <a href="{{ route('stock-outs.index') }}">Stok Keluar</a>
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
                <h2>Edit Stok Masuk</h2>
                <p>Perbarui informasi transaksi barang masuk.</p>
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
                    Jika jumlah dikurangi atau produk diganti, sistem akan memeriksa apakah stok dari transaksi lama masih tersedia.
                </div>

                <form action="{{ route('stock-ins.update', $stockIn) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="product_id">Produk</label>
                        <select id="product_id" name="product_id" class="form-control" required>
                            <option value="" data-stock="0" data-purchase-price="0">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    data-stock="{{ $product->stock }}"
                                    data-purchase-price="{{ $product->purchase_price }}"
                                    {{ old('product_id', $stockIn->product_id) == $product->id ? 'selected' : '' }}
                                >
                                    {{ $product->product_name }} - Stok saat ini: {{ $product->stock }} - Harga beli: ${{ number_format($product->purchase_price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <span class="help-text">Pilih produk yang menerima stok.</span>
                    </div>

                    <div class="form-group">
                        <label for="supplier_id">Supplier Barang</label>
                        <select id="supplier_id" name="supplier_id" class="form-control">
                            <option value="">-- Pilih Supplier (Opsional) --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $stockIn->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->supplier_name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="help-text">Pilih supplier yang mengirim barang.</span>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Jumlah Barang</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" value="{{ old('quantity', $stockIn->quantity) }}" min="1" required>
                        <span class="help-text">Perubahan jumlah akan otomatis menyesuaikan stok produk.</span>
                    </div>

                    <div class="calculation-card">
                        <h3>Perhitungan Perubahan Stok</h3>
                        <div class="calculation-grid">
                            <div class="calculation-item"><span>Stok Saat Ini</span><strong id="currentStock">0 unit</strong></div>
                            <div class="calculation-item new-stock"><span>Perkiraan Stok Setelah Diubah</span><strong id="newStock">0 unit</strong></div>
                            <div class="calculation-item estimated-cost"><span>Estimasi Nilai Pembelian Baru</span><strong id="estimatedCost">$0.00</strong></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="transaction_date">Tanggal Transaksi</label>
                        <input type="date" id="transaction_date" name="transaction_date" class="form-control" value="{{ old('transaction_date', optional($stockIn->transaction_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan</label>
                        <textarea id="notes" name="notes" class="form-control">{{ old('notes', $stockIn->notes) }}</textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="button button-update">Simpan Perubahan</button>
                        <a href="{{ route('stock-ins.index') }}" class="button button-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const productInput = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const currentStockElement = document.getElementById('currentStock');
        const newStockElement = document.getElementById('newStock');
        const estimatedCostElement = document.getElementById('estimatedCost');
        const oldProductId = {{ (int) $stockIn->product_id }};
        const oldQuantity = {{ (int) $stockIn->quantity }};

        function calculateStockChange() {
            const selected = productInput.options[productInput.selectedIndex];
            const selectedProductId = Number(selected?.value || 0);
            const currentStock = Number(selected?.dataset.stock || 0);
            const purchasePrice = Number(selected?.dataset.purchasePrice || 0);
            const newQuantity = Number(quantityInput.value || 0);
            const estimatedStock = selectedProductId === oldProductId
                ? currentStock - oldQuantity + newQuantity
                : currentStock + newQuantity;

            currentStockElement.textContent = currentStock + ' unit';
            newStockElement.textContent = estimatedStock + ' unit';
            estimatedCostElement.textContent = '$' + (purchasePrice * newQuantity).toFixed(2);

            if (estimatedStock < 0) {
                newStockElement.style.color = '#dc2626';
                quantityInput.setCustomValidity('Jumlah tidak dapat dikurangi karena sebagian stok sudah terjual.');
            } else {
                newStockElement.style.color = '#16a34a';
                quantityInput.setCustomValidity('');
            }
        }

        productInput.addEventListener('change', calculateStockChange);
        quantityInput.addEventListener('input', calculateStockChange);
        calculateStockChange();
    </script>
</body>
</html>