<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TeamProductCarry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TeamProductCarryController extends Controller
{
    /**
     * Daftar barang yang dibawa team.
     */
    public function index(Request $request)
    {
        $query = TeamProductCarry::query()
            ->with('product')
            ->orderByDesc('taken_at')
            ->orderByDesc('id');

        if ($request->filled('search')) {

            $search =
                trim(
                    (string) $request->search
                );

            $query->where(
                function ($q) use ($search) {

                    $q->where(
                        'team_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhereHas(
                        'product',
                        function ($productQuery) use ($search) {

                            $productQuery->where(
                                'product_name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );
                }
            );
        }

        $carries =
            $query
                ->paginate(20)
                ->withQueryString();

        $totalTaken =
            TeamProductCarry::sum(
                'quantity_taken'
            );

        $totalSold =
            TeamProductCarry::sum(
                'quantity_sold'
            );

        $totalReturned =
            TeamProductCarry::sum(
                'quantity_returned'
            );

        $totalRemaining =
            TeamProductCarry::query()
                ->get()
                ->sum(
                    function ($carry) {

                        return
                            $carry->remaining_quantity;
                    }
                );

        return view(
            'team-product-carries.index',
            compact(
                'carries',
                'totalTaken',
                'totalSold',
                'totalReturned',
                'totalRemaining'
            )
        );
    }


    /**
     * Form tambah.
     */
    public function create()
    {
        $products =
            Product::query()
                ->orderBy('product_name')
                ->get();

        return view(
            'team-product-carries.create',
            compact('products')
        );
    }


    /**
     * Simpan beberapa produk sekaligus.
     */
    public function store(Request $request)
    {
        $validated =
            $request->validate([
                'team_name' => [
                    'required',
                    'string',
                    'max:150',
                ],

                'taken_at' => [
                    'required',
                    'date',
                ],

                'notes' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],

                'items' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'items.*.product_id' => [
                    'required',
                    'distinct',
                    'exists:products,id',
                ],

                'items.*.quantity_taken' => [
                    'required',
                    'integer',
                    'min:1',
                ],

                'items.*.quantity_sold' => [
                    'nullable',
                    'integer',
                    'min:0',
                ],

                'items.*.quantity_returned' => [
                    'nullable',
                    'integer',
                    'min:0',
                ],
            ], [
                'team_name.required' =>
                    'Nama team wajib diisi.',

                'taken_at.required' =>
                    'Tanggal dibawa wajib diisi.',

                'items.required' =>
                    'Minimal satu barang harus ditambahkan.',

                'items.min' =>
                    'Minimal satu barang harus ditambahkan.',

                'items.*.product_id.required' =>
                    'Produk wajib dipilih.',

                'items.*.product_id.distinct' =>
                    'Produk yang sama tidak boleh dipilih lebih dari satu kali.',

                'items.*.product_id.exists' =>
                    'Produk yang dipilih tidak ditemukan.',

                'items.*.quantity_taken.required' =>
                    'Qty dibawa wajib diisi.',

                'items.*.quantity_taken.min' =>
                    'Qty dibawa minimal 1.',
            ]);


        foreach (
            $validated['items']
            as $index => $item
        ) {

            $taken =
                (int) $item['quantity_taken'];

            $sold =
                (int) (
                    $item['quantity_sold']
                    ?? 0
                );

            $returned =
                (int) (
                    $item['quantity_returned']
                    ?? 0
                );


            if (
                ($sold + $returned)
                >
                $taken
            ) {

                throw ValidationException::withMessages([
                    "items.$index.quantity_sold" =>
                        'Qty Jual + Qty Kembali tidak boleh lebih besar dari Qty Dibawa.',
                ]);
            }
        }


        DB::transaction(
            function () use ($validated) {

                foreach (
                    $validated['items']
                    as $item
                ) {

                    TeamProductCarry::create([
                        'team_name' =>
                            trim(
                                $validated['team_name']
                            ),

                        'product_id' =>
                            $item['product_id'],

                        'quantity_taken' =>
                            (int) $item['quantity_taken'],

                        'quantity_sold' =>
                            (int) (
                                $item['quantity_sold']
                                ?? 0
                            ),

                        'quantity_returned' =>
                            (int) (
                                $item['quantity_returned']
                                ?? 0
                            ),

                        'taken_at' =>
                            $validated['taken_at'],

                        'notes' =>
                            $validated['notes']
                            ?? null,

                        'created_by' =>
                            auth()->id(),
                    ]);
                }
            }
        );


        return redirect()
            ->route(
                'team-product-carries.index'
            )
            ->with(
                'success',
                'Semua barang yang dibawa team berhasil disimpan.'
            );
    }


    /**
     * Form edit satu produk.
     */
    public function edit(
        TeamProductCarry $teamProductCarry
    ) {
        $products =
            Product::query()
                ->orderBy('product_name')
                ->get();

        return view(
            'team-product-carries.edit',
            compact(
                'teamProductCarry',
                'products'
            )
        );
    }


    /**
     * Update satu produk.
     */
    public function update(
        Request $request,
        TeamProductCarry $teamProductCarry
    ) {
        $validated =
            $request->validate([
                'team_name' => [
                    'required',
                    'string',
                    'max:150',
                ],

                'product_id' => [
                    'required',
                    'exists:products,id',
                ],

                'quantity_taken' => [
                    'required',
                    'integer',
                    'min:1',
                ],

                'quantity_sold' => [
                    'required',
                    'integer',
                    'min:0',
                ],

                'quantity_returned' => [
                    'required',
                    'integer',
                    'min:0',
                ],

                'taken_at' => [
                    'required',
                    'date',
                ],

                'notes' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ]);


        $quantityTaken =
            (int) $validated['quantity_taken'];

        $quantitySold =
            (int) $validated['quantity_sold'];

        $quantityReturned =
            (int) $validated['quantity_returned'];


        if (
            ($quantitySold + $quantityReturned)
            >
            $quantityTaken
        ) {

            throw ValidationException::withMessages([
                'quantity_sold' =>
                    'Qty Jual + Qty Kembali tidak boleh lebih besar dari Qty Dibawa.',
            ]);
        }


        $teamProductCarry->update([
            'team_name' =>
                trim(
                    $validated['team_name']
                ),

            'product_id' =>
                $validated['product_id'],

            'quantity_taken' =>
                $quantityTaken,

            'quantity_sold' =>
                $quantitySold,

            'quantity_returned' =>
                $quantityReturned,

            'taken_at' =>
                $validated['taken_at'],

            'notes' =>
                $validated['notes']
                ?? null,
        ]);


        return redirect()
            ->route(
                'team-product-carries.index'
            )
            ->with(
                'success',
                'Data barang dibawa team berhasil diperbarui.'
            );
    }


    /**
     * Hapus data.
     */
    public function destroy(
        TeamProductCarry $teamProductCarry
    ) {
        $teamProductCarry->delete();

        return redirect()
            ->route(
                'team-product-carries.index'
            )
            ->with(
                'success',
                'Data barang dibawa team berhasil dihapus.'
            );
    }
}