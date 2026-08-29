<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'stock_status' => [
                'nullable',
                'in:available,low,out',
            ],
        ], [
            'search.max' =>
                'Kata pencarian maksimal 100 karakter.',

            'category.string' =>
                'Kategori yang dipilih tidak valid.',

            'category.max' =>
                'Kategori maksimal 255 karakter.',

            'stock_status.in' =>
                'Status stok yang dipilih tidak valid.',
        ]);

        $search = trim($validated['search'] ?? '');
        $category = $validated['category'] ?? '';
        $stockStatus = $validated['stock_status'] ?? '';

        /*
        |--------------------------------------------------------------------------
        | Daftar kategori untuk pilihan filter
        |--------------------------------------------------------------------------
        */

        $categories = Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        /*
        |--------------------------------------------------------------------------
        | Query produk berdasarkan pencarian dan filter
        |--------------------------------------------------------------------------
        */

        $productQuery = Product::query();

        $this->applyProductFilters(
            $productQuery,
            $search,
            $category,
            $stockStatus
        );

        /*
        |--------------------------------------------------------------------------
        | Ringkasan berdasarkan hasil filter
        |--------------------------------------------------------------------------
        */

        $totalProducts =
            (clone $productQuery)->count();

        $totalStock =
            (int) (clone $productQuery)->sum('stock');

        $outOfStockProducts =
            (clone $productQuery)
                ->where('stock', '<=', 0)
                ->count();

        $lowStockProducts =
            (clone $productQuery)
                ->whereBetween('stock', [1, 5])
                ->count();

        $availableProducts =
            (clone $productQuery)
                ->where('stock', '>', 5)
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Pagination daftar produk
        |--------------------------------------------------------------------------
        */

        $products = $productQuery
            ->orderBy('product_name')
            ->paginate(10);

        /*
         * Mempertahankan nilai pencarian dan filter
         * ketika pengguna berpindah halaman pagination.
         */
        $products->appends($request->query());

        return view('products.index', compact(
            'products',
            'categories',
            'totalProducts',
            'totalStock',
            'outOfStockProducts',
            'lowStockProducts',
            'availableProducts',
            'search',
            'category',
            'stockStatus'
        ));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        /*
        |--------------------------------------------------------------------------
        | Upload foto produk
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request
                ->file('image')
                ->store(
                    'products',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan produk
        |--------------------------------------------------------------------------
        */

        Product::create([
            'product_name' =>
                $data['product_name'],

            'category' =>
                $data['category'],

            /*
             * Produk baru mempunyai stok nol.
             * Penambahan stok dilakukan melalui menu Stok Masuk.
             */
            'stock' => 0,

            'purchase_price' =>
                $data['purchase_price'],

            'selling_price' =>
                $data['selling_price'],

            /*
             * Kolom price lama mengikuti harga jual.
             */
            'price' =>
                $data['selling_price'],

            /*
             * Foto produk.
             */
            'image' =>
                $imagePath,

            /*
             * Detail / spesifikasi produk.
             */
            'description' =>
                $data['description'] ?? null,

            'brand' =>
                $data['brand'] ?? null,

            'model' =>
                $data['model'] ?? null,

            'connectivity' =>
                $data['connectivity'] ?? null,

            'warranty' =>
                $data['warranty'] ?? null,
        ]);

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Produk berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan form edit produk.
     */
    public function edit(Product $product)
    {
        return view(
            'products.edit',
            compact('product')
        );
    }

    /**
     * Memperbarui produk.
     */
    public function update(
        Request $request,
        Product $product
    ) {
        $data = $this->validateProduct($request);

        /*
        |--------------------------------------------------------------------------
        | Pertahankan foto lama
        |--------------------------------------------------------------------------
        */

        $imagePath = $product->image;

        /*
        |--------------------------------------------------------------------------
        | Jika admin upload foto baru
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            /*
             * Hapus foto lama jika ada.
             */
            if (
                !empty($product->image)
                && Storage::disk('public')
                    ->exists($product->image)
            ) {
                Storage::disk('public')
                    ->delete($product->image);
            }

            /*
             * Simpan foto baru.
             */
            $imagePath = $request
                ->file('image')
                ->store(
                    'products',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Update produk
        |--------------------------------------------------------------------------
        */

        $product->update([
            'product_name' =>
                $data['product_name'],

            'category' =>
                $data['category'],

            'purchase_price' =>
                $data['purchase_price'],

            'selling_price' =>
                $data['selling_price'],

            /*
             * Kolom price lama mengikuti harga jual.
             */
            'price' =>
                $data['selling_price'],

            /*
             * Foto produk.
             */
            'image' =>
                $imagePath,

            /*
             * Detail / spesifikasi produk.
             */
            'description' =>
                $data['description'] ?? null,

            'brand' =>
                $data['brand'] ?? null,

            'model' =>
                $data['model'] ?? null,

            'connectivity' =>
                $data['connectivity'] ?? null,

            'warranty' =>
                $data['warranty'] ?? null,

            /*
             * Stok tidak diubah melalui Edit Produk.
             * Stok dikelola melalui Stok Masuk/Keluar.
             */
        ]);

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Produk berhasil diperbarui.'
            );
    }

    /**
     * Menghapus produk.
     */
    public function destroy(Product $product)
    {
        /*
        |--------------------------------------------------------------------------
        | Periksa riwayat transaksi
        |--------------------------------------------------------------------------
        */

        $hasStockIn = $product
            ->stockIns()
            ->exists();

        $hasStockOut = $product
            ->stockOuts()
            ->exists();

        /*
         * Produk yang memiliki transaksi
         * tidak boleh dihapus.
         */
        if ($hasStockIn || $hasStockOut) {
            return redirect()
                ->route('products.index')
                ->with(
                    'error',
                    'Produk "'
                    . $product->product_name
                    . '" tidak dapat dihapus karena sudah '
                    . 'memiliki transaksi stok masuk atau '
                    . 'stok keluar.'
                );
        }

        /*
         * Produk dengan stok yang masih tersedia
         * tidak boleh dihapus.
         */
        if ((int) $product->stock > 0) {
            return redirect()
                ->route('products.index')
                ->with(
                    'error',
                    'Produk "'
                    . $product->product_name
                    . '" tidak dapat dihapus karena stok '
                    . 'masih tersedia sebanyak '
                    . $product->stock
                    . ' unit.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus foto produk dari storage
        |--------------------------------------------------------------------------
        */

        if (
            !empty($product->image)
            && Storage::disk('public')
                ->exists($product->image)
        ) {
            Storage::disk('public')
                ->delete($product->image);
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus produk
        |--------------------------------------------------------------------------
        */

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Produk berhasil dihapus.'
            );
    }

    /**
     * Menerapkan pencarian dan filter produk.
     */
    private function applyProductFilters(
        $query,
        string $search,
        string $category,
        string $stockStatus
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Pencarian nama produk
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(
                'product_name',
                'like',
                '%' . $search . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter kategori
        |--------------------------------------------------------------------------
        */

        if ($category !== '') {
            $query->where(
                'category',
                $category
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter stok tersedia
        |--------------------------------------------------------------------------
        */

        if ($stockStatus === 'available') {
            $query->where(
                'stock',
                '>',
                5
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter stok menipis
        |--------------------------------------------------------------------------
        */

        if ($stockStatus === 'low') {
            $query->whereBetween(
                'stock',
                [1, 5]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter stok habis
        |--------------------------------------------------------------------------
        */

        if ($stockStatus === 'out') {
            $query->where(
                'stock',
                '<=',
                0
            );
        }
    }

    /**
     * Validasi tambah dan edit produk.
     */
    private function validateProduct(
        Request $request
    ): array {
        return $request->validate([
            'product_name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'required',
                'string',
                'max:255',
            ],

            'purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0',
                'gte:purchase_price',
            ],

            /*
             * Detail / spesifikasi produk.
             */
            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'brand' => [
                'nullable',
                'string',
                'max:255',
            ],

            'model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'connectivity' => [
                'nullable',
                'string',
                'max:255',
            ],

            'warranty' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
             * Foto tidak wajib.
             *
             * Format:
             * JPG
             * JPEG
             * PNG
             * WEBP
             *
             * Maksimal 5 MB.
             */
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'product_name.required' =>
                'Nama produk wajib diisi.',

            'product_name.string' =>
                'Nama produk harus berupa teks.',

            'product_name.max' =>
                'Nama produk maksimal 255 karakter.',


            'category.required' =>
                'Kategori wajib diisi.',

            'category.string' =>
                'Kategori harus berupa teks.',

            'category.max' =>
                'Kategori maksimal 255 karakter.',


            'purchase_price.required' =>
                'Harga beli wajib diisi.',

            'purchase_price.numeric' =>
                'Harga beli harus berupa angka.',

            'purchase_price.min' =>
                'Harga beli tidak boleh kurang dari nol.',


            'selling_price.required' =>
                'Harga jual wajib diisi.',

            'selling_price.numeric' =>
                'Harga jual harus berupa angka.',

            'selling_price.min' =>
                'Harga jual tidak boleh kurang dari nol.',

            'selling_price.gte' =>
                'Harga jual harus sama dengan atau lebih besar dari harga beli.',


            'description.string' =>
                'Deskripsi produk harus berupa teks.',

            'description.max' =>
                'Deskripsi produk maksimal 5000 karakter.',


            'brand.string' =>
                'Brand produk harus berupa teks.',

            'brand.max' =>
                'Brand produk maksimal 255 karakter.',


            'model.string' =>
                'Model produk harus berupa teks.',

            'model.max' =>
                'Model produk maksimal 255 karakter.',


            'connectivity.string' =>
                'Konektivitas produk harus berupa teks.',

            'connectivity.max' =>
                'Konektivitas produk maksimal 255 karakter.',


            'warranty.string' =>
                'Garansi produk harus berupa teks.',

            'warranty.max' =>
                'Garansi produk maksimal 255 karakter.',


            'image.image' =>
                'File foto produk harus berupa gambar.',

            'image.mimes' =>
                'Foto produk harus berformat JPG, JPEG, PNG, atau WEBP.',

            'image.max' =>
                'Ukuran foto produk maksimal 5 MB.',
        ]);
    }
}