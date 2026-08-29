<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PromoCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoCampaignController extends Controller
{
    /**
     * Daftar semua campaign.
     */
    public function index()
    {
        $campaigns = PromoCampaign::withCount('products')
            ->orderByDesc('id')
            ->get();

        return view(
            'promo-campaigns.index',
            compact('campaigns')
        );
    }

    /**
     * Form tambah campaign.
     */
    public function create()
    {
        $products = Product::orderBy('product_name')
            ->get();

        return view(
            'promo-campaigns.create',
            compact('products')
        );
    }

    /**
     * Simpan campaign.
     */
    public function store(Request $request)
    {
        $data = $this->validateCampaign($request);

        DB::transaction(function () use ($data) {
            $campaign = PromoCampaign::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => $data['is_active'] ?? false,
            ]);

            $syncData = [];

            foreach ($data['products'] as $product) {
                $syncData[$product['product_id']] = [
                    'discount_type' => $product['discount_type'],
                    'discount_value' => $product['discount_value'],
                ];
            }

            $campaign->products()->sync($syncData);
        });

        return redirect()
            ->route('promo-campaigns.index')
            ->with(
                'success',
                'Promo Campaign berhasil dibuat.'
            );
    }

    /**
     * Form edit campaign.
     */
    public function edit(PromoCampaign $promoCampaign)
    {
        $promoCampaign->load('products');

        $products = Product::orderBy('product_name')
            ->get();

        return view(
            'promo-campaigns.edit',
            compact(
                'promoCampaign',
                'products'
            )
        );
    }

    /**
     * Update campaign.
     */
    public function update(
        Request $request,
        PromoCampaign $promoCampaign
    ) {
        $data = $this->validateCampaign($request);

        DB::transaction(
            function () use ($data, $promoCampaign) {
                $promoCampaign->update([
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'is_active' => $data['is_active'] ?? false,
                ]);

                $syncData = [];

                foreach ($data['products'] as $product) {
                    $syncData[$product['product_id']] = [
                        'discount_type' => $product['discount_type'],
                        'discount_value' => $product['discount_value'],
                    ];
                }

                $promoCampaign
                    ->products()
                    ->sync($syncData);
            }
        );

        return redirect()
            ->route('promo-campaigns.index')
            ->with(
                'success',
                'Promo Campaign berhasil diperbarui.'
            );
    }

    /**
     * Hapus campaign.
     */
    public function destroy(
        PromoCampaign $promoCampaign
    ) {
        $promoCampaign->delete();

        return redirect()
            ->route('promo-campaigns.index')
            ->with(
                'success',
                'Promo Campaign berhasil dihapus.'
            );
    }

    /**
     * Validasi campaign.
     */
    private function validateCampaign(
        Request $request
    ): array {
        return $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'products' => [
                'required',
                'array',
                'min:1',
            ],

            'products.*.product_id' => [
                'required',
                'integer',
                'exists:products,id',
                'distinct',
            ],

            'products.*.discount_type' => [
                'required',
                'in:fixed,percent',
            ],

            'products.*.discount_value' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ]);
    }
}