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

        $search = trim(
            $validated['search'] ?? ''
        );

        $category =
            $validated['category'] ?? '';

        $stockStatus =
            $validated['stock_status'] ?? '';

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

        $productQuery =
            Product::query();

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
            (clone $productQuery)
                ->count();

        $totalStock =
            (int)
            (clone $productQuery)
                ->sum('stock');

        $outOfStockProducts =
            (clone $productQuery)
                ->where(
                    'stock',
                    '<=',
                    0
                )
                ->count();

        $lowStockProducts =
            (clone $productQuery)
                ->whereBetween(
                    'stock',
                    [1, 5]
                )
                ->count();

        $availableProducts =
            (clone $productQuery)
                ->where(
                    'stock',
                    '>',
                    5
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Pagination daftar produk
        |--------------------------------------------------------------------------
        */

        $products =
            $productQuery
                ->orderBy(
                    'product_name'
                )
                ->paginate(10);

        $products->appends(
            $request->query()
        );

        return view(
            'products.index',
            compact(
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
            )
        );
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create()
    {
        return view(
            'products.create'
        );
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(
        Request $request
    ) {
        $data =
            $this->validateProduct(
                $request
            );

        /*
        |--------------------------------------------------------------------------
        | Upload foto produk
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if (
            $request->hasFile(
                'image'
            )
        ) {
            $imagePath =
                $request
                    ->file('image')
                    ->store(
                        'products',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Status promo
        |--------------------------------------------------------------------------
        */

        $isPromo =
            $request->boolean(
                'is_promo'
            );

        /*
        |--------------------------------------------------------------------------
        | Simpan produk
        |--------------------------------------------------------------------------
        */

        Product::create([
            'product_name' =>
                $data['product_name'],

            /*
            |--------------------------------------------------------------------------
            | Normalisasi kategori
            |--------------------------------------------------------------------------
            |
            | Contoh:
            | tv / Tv / telvisi / televisi -> TV
            | rca -> RCA
            | receiver -> Receiver
            |
            */

            'category' =>
                $this->normalizeCategory(
                    $data['category']
                ),

            /*
             * Produk baru mempunyai stok nol.
             * Penambahan stok dilakukan
             * melalui menu Stok Masuk.
             */
            'stock' => 0,

            'purchase_price' =>
                $data[
                    'purchase_price'
                ],

            'selling_price' =>
                $data[
                    'selling_price'
                ],

            /*
             * Kolom price lama
             * mengikuti harga jual.
             */
            'price' =>
                $data[
                    'selling_price'
                ],

            /*
             * Foto produk.
             */
            'image' =>
                $imagePath,

            /*
             * Detail / spesifikasi produk.
             */
            'description' =>
                $data[
                    'description'
                ] ?? null,

            'brand' =>
                $data[
                    'brand'
                ] ?? null,

            'model' =>
                $data[
                    'model'
                ] ?? null,

            'connectivity' =>
                $data[
                    'connectivity'
                ] ?? null,

            'warranty' =>
                $data[
                    'warranty'
                ] ?? null,

            /*
             * Promo / diskon.
             */
            'is_promo' =>
                $isPromo,

            'discount_type' =>
                $isPromo
                    ? (
                        $data[
                            'discount_type'
                        ] ?? null
                    )
                    : null,

            'discount_value' =>
                $isPromo
                    ? (
                        $data[
                            'discount_value'
                        ] ?? null
                    )
                    : null,

            'promo_start' =>
                $isPromo
                    ? (
                        $data[
                            'promo_start'
                        ] ?? null
                    )
                    : null,

            'promo_end' =>
                $isPromo
                    ? (
                        $data[
                            'promo_end'
                        ] ?? null
                    )
                    : null,

            /*
             * Informasi promo.
             */
            'promo_title' =>
                $isPromo
                    ? (
                        $data[
                            'promo_title'
                        ] ?? null
                    )
                    : null,

            'promo_description' =>
                $isPromo
                    ? (
                        $data[
                            'promo_description'
                        ] ?? null
                    )
                    : null,
        ]);

        return redirect()
            ->route(
                'products.index'
            )
            ->with(
                'success',
                'Produk berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan form edit produk.
     */
    public function edit(
        Product $product
    ) {
        return view(
            'products.edit',
            compact(
                'product'
            )
        );
    }

    /**
     * Memperbarui produk.
     */
    public function update(
        Request $request,
        Product $product
    ) {
        $data =
            $this->validateProduct(
                $request
            );

        /*
        |--------------------------------------------------------------------------
        | Pertahankan foto lama
        |--------------------------------------------------------------------------
        */

        $imagePath =
            $product->image;

        /*
        |--------------------------------------------------------------------------
        | Jika admin upload foto baru
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'image'
            )
        ) {

            /*
             * Hapus foto lama jika ada.
             */

            if (
                !empty(
                    $product->image
                )
                &&
                Storage::disk(
                    'public'
                )->exists(
                    $product->image
                )
            ) {
                Storage::disk(
                    'public'
                )->delete(
                    $product->image
                );
            }

            /*
             * Simpan foto baru.
             */

            $imagePath =
                $request
                    ->file('image')
                    ->store(
                        'products',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Status promo
        |--------------------------------------------------------------------------
        */

        $isPromo =
            $request->boolean(
                'is_promo'
            );

        /*
        |--------------------------------------------------------------------------
        | Update produk
        |--------------------------------------------------------------------------
        */

        $product->update([
            'product_name' =>
                $data[
                    'product_name'
                ],

            /*
            |--------------------------------------------------------------------------
            | Normalisasi kategori
            |--------------------------------------------------------------------------
            */

            'category' =>
                $this->normalizeCategory(
                    $data['category']
                ),

            'purchase_price' =>
                $data[
                    'purchase_price'
                ],

            'selling_price' =>
                $data[
                    'selling_price'
                ],

            /*
             * Kolom price lama
             * mengikuti harga jual.
             */
            'price' =>
                $data[
                    'selling_price'
                ],

            /*
             * Foto produk.
             */
            'image' =>
                $imagePath,

            /*
             * Detail / spesifikasi produk.
             */
            'description' =>
                $data[
                    'description'
                ] ?? null,

            'brand' =>
                $data[
                    'brand'
                ] ?? null,

            'model' =>
                $data[
                    'model'
                ] ?? null,

            'connectivity' =>
                $data[
                    'connectivity'
                ] ?? null,

            'warranty' =>
                $data[
                    'warranty'
                ] ?? null,

            /*
             * Promo / diskon.
             */
            'is_promo' =>
                $isPromo,

            'discount_type' =>
                $isPromo
                    ? (
                        $data[
                            'discount_type'
                        ] ?? null
                    )
                    : null,

            'discount_value' =>
                $isPromo
                    ? (
                        $data[
                            'discount_value'
                        ] ?? null
                    )
                    : null,

            'promo_start' =>
                $isPromo
                    ? (
                        $data[
                            'promo_start'
                        ] ?? null
                    )
                    : null,

            'promo_end' =>
                $isPromo
                    ? (
                        $data[
                            'promo_end'
                        ] ?? null
                    )
                    : null,

            /*
             * Informasi promo.
             */
            'promo_title' =>
                $isPromo
                    ? (
                        $data[
                            'promo_title'
                        ] ?? null
                    )
                    : null,

            'promo_description' =>
                $isPromo
                    ? (
                        $data[
                            'promo_description'
                        ] ?? null
                    )
                    : null,

            /*
             * Stok tidak diubah melalui
             * Edit Produk.
             *
             * Stok dikelola melalui
             * Stok Masuk / Stok Keluar.
             */
        ]);

        return redirect()
            ->route(
                'products.index'
            )
            ->with(
                'success',
                'Produk berhasil diperbarui.'
            );
    }

    /**
     * Menghapus produk.
     */
    public function destroy(
        Product $product
    ) {
        /*
        |--------------------------------------------------------------------------
        | Periksa riwayat transaksi
        |--------------------------------------------------------------------------
        */

        $hasStockIn =
            $product
                ->stockIns()
                ->exists();

        $hasStockOut =
            $product
                ->stockOuts()
                ->exists();

        /*
         * Produk yang memiliki transaksi
         * tidak boleh dihapus.
         */

        if (
            $hasStockIn
            ||
            $hasStockOut
        ) {
            return redirect()
                ->route(
                    'products.index'
                )
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
         * Produk dengan stok yang masih
         * tersedia tidak boleh dihapus.
         */

        if (
            (int) $product->stock > 0
        ) {
            return redirect()
                ->route(
                    'products.index'
                )
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
            !empty(
                $product->image
            )
            &&
            Storage::disk(
                'public'
            )->exists(
                $product->image
            )
        ) {
            Storage::disk(
                'public'
            )->delete(
                $product->image
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus produk
        |--------------------------------------------------------------------------
        */

        $product->delete();

        return redirect()
            ->route(
                'products.index'
            )
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

        if (
            $search !== ''
        ) {
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

        if (
            $category !== ''
        ) {
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

        if (
            $stockStatus
            === 'available'
        ) {
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

        if (
            $stockStatus
            === 'low'
        ) {
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

        if (
            $stockStatus
            === 'out'
        ) {
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
            /*
            |--------------------------------------------------------------------------
            | Produk
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Harga
            |--------------------------------------------------------------------------
            */

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
            |--------------------------------------------------------------------------
            | Detail / spesifikasi produk
            |--------------------------------------------------------------------------
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
            |--------------------------------------------------------------------------
            | Promo
            |--------------------------------------------------------------------------
            */

            'is_promo' => [
                'nullable',
                'boolean',
            ],

            'promo_title' => [
                'nullable',
                'string',
                'max:255',
                'required_if:is_promo,1',
            ],

            'promo_description' => [
                'nullable',
                'string',
                'max:2000',
                'required_if:is_promo,1',
            ],

            'discount_type' => [
                'nullable',
                'in:percent,fixed',
                'required_if:is_promo,1',
            ],

            'discount_value' => [
                'nullable',
                'numeric',
                'min:0.01',
                'required_if:is_promo,1',
            ],

            'promo_start' => [
                'nullable',
                'date',
                'required_if:is_promo,1',
            ],

            'promo_end' => [
                'nullable',
                'date',
                'required_if:is_promo,1',
                'after_or_equal:promo_start',
            ],

            /*
            |--------------------------------------------------------------------------
            | Foto
            |--------------------------------------------------------------------------
            */

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            /*
            |--------------------------------------------------------------------------
            | Produk
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Harga
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Spesifikasi
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Promo
            |--------------------------------------------------------------------------
            */

            'is_promo.boolean' =>
                'Status promo tidak valid.',

            'promo_title.required_if' =>
                'Judul promo wajib diisi jika promo diaktifkan.',

            'promo_title.string' =>
                'Judul promo harus berupa teks.',

            'promo_title.max' =>
                'Judul promo maksimal 255 karakter.',

            'promo_description.required_if' =>
                'Keterangan atau alasan promo wajib diisi jika promo diaktifkan.',

            'promo_description.string' =>
                'Keterangan promo harus berupa teks.',

            'promo_description.max' =>
                'Keterangan promo maksimal 2000 karakter.',

            'discount_type.required_if' =>
                'Jenis diskon wajib dipilih jika promo diaktifkan.',

            'discount_type.in' =>
                'Jenis diskon harus persen atau nominal.',

            'discount_value.required_if' =>
                'Nilai diskon wajib diisi jika promo diaktifkan.',

            'discount_value.numeric' =>
                'Nilai diskon harus berupa angka.',

            'discount_value.min' =>
                'Nilai diskon harus lebih besar dari nol.',

            'promo_start.required_if' =>
                'Tanggal mulai promo wajib diisi jika promo diaktifkan.',

            'promo_start.date' =>
                'Tanggal mulai promo tidak valid.',

            'promo_end.required_if' =>
                'Tanggal selesai promo wajib diisi jika promo diaktifkan.',

            'promo_end.date' =>
                'Tanggal selesai promo tidak valid.',

            'promo_end.after_or_equal' =>
                'Tanggal selesai promo tidak boleh lebih awal dari tanggal mulai.',

            /*
            |--------------------------------------------------------------------------
            | Foto
            |--------------------------------------------------------------------------
            */

            'image.image' =>
                'File foto produk harus berupa gambar.',

            'image.mimes' =>
                'Foto produk harus berformat JPG, JPEG, PNG atau WEBP.',

            'image.max' =>
                'Ukuran foto produk maksimal 5 MB.',
        ]);
    }

    /**
     * Menstandarkan penulisan kategori produk.
     *
     * Tujuan:
     * - Mencegah kategori duplikat karena perbedaan huruf.
     * - Tetap mengizinkan kategori baru dibuat otomatis.
     *
     * Contoh:
     * tv / Tv / TV / telvisi / televisi -> TV
     * rca / Rca -> RCA
     * receiver -> Receiver
     * remote control -> Remote Control
     */
    private function normalizeCategory(
        string $category
    ): string {
        /*
         * Hilangkan spasi di awal
         * dan akhir input.
         */
        $category =
            trim($category);

        /*
         * Jika input kosong,
         * kembalikan string kosong.
         *
         * Secara normal kondisi ini
         * sudah ditolak oleh validation.
         */
        if (
            $category === ''
        ) {
            return '';
        }

        /*
         * Buat key lowercase
         * untuk proses pencocokan.
         */
        $key =
            mb_strtolower(
                $category,
                'UTF-8'
            );

        /*
         * Standarisasi kategori
         * yang mempunyai penulisan khusus.
         */
        return match ($key) {

            /*
            |--------------------------------------------------------------------------
            | TV
            |--------------------------------------------------------------------------
            */

            'tv',
            'telvisi',
            'televisi',
            'television'
                => 'TV',

            /*
            |--------------------------------------------------------------------------
            | RCA
            |--------------------------------------------------------------------------
            */

            'rca'
                => 'RCA',

            /*
            |--------------------------------------------------------------------------
            | HDMI
            |--------------------------------------------------------------------------
            */

            'hdmi'
                => 'HDMI',

            /*
            |--------------------------------------------------------------------------
            | USB
            |--------------------------------------------------------------------------
            */

            'usb'
                => 'USB',

            /*
            |--------------------------------------------------------------------------
            | LED
            |--------------------------------------------------------------------------
            */

            'led'
                => 'LED',

            /*
            |--------------------------------------------------------------------------
            | Receiver
            |--------------------------------------------------------------------------
            */

            'receiver'
                => 'Receiver',

            /*
            |--------------------------------------------------------------------------
            | Kabel
            |--------------------------------------------------------------------------
            */

            'kabel',
            'cable'
                => 'Kabel',

            /*
            |--------------------------------------------------------------------------
            | Speaker
            |--------------------------------------------------------------------------
            */

            'speaker'
                => 'Speaker',

            /*
            |--------------------------------------------------------------------------
            | Remote
            |--------------------------------------------------------------------------
            */

            'remote'
                => 'Remote',

            /*
            |--------------------------------------------------------------------------
            | Kategori baru
            |--------------------------------------------------------------------------
            |
            | Jika belum ada pada mapping di atas,
            | tetap boleh disimpan sebagai kategori baru.
            |
            | Contoh:
            | "remote control"
            | menjadi
            | "Remote Control"
            |
            */

            default =>
                mb_convert_case(
                    $category,
                    MB_CASE_TITLE,
                    'UTF-8'
                ),
        };
    }
}