<?php

namespace App\Http\Controllers;

use App\Models\HomepageBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomepageBannerController extends Controller
{
    /**
     * Tampilkan semua banner homepage.
     */
    public function index()
    {
        $banners = HomepageBanner::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view(
            'homepage-banners.index',
            compact('banners')
        );
    }

    /**
     * Simpan banner baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'nullable',
                'string',
                'max:150',
            ],

            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ], [
            'image.required' =>
                'Gambar banner wajib dipilih.',

            'image.image' =>
                'File harus berupa gambar.',

            'image.mimes' =>
                'Format gambar harus JPG, JPEG, PNG atau WEBP.',

            'image.max' =>
                'Ukuran gambar maksimal 5 MB.',
        ]);

        $imagePath = $request
            ->file('image')
            ->store(
                'homepage-banners',
                'public'
            );

        HomepageBanner::create([
            'title' =>
                $validated['title']
                ?? null,

            'image' =>
                $imagePath,

            'sort_order' =>
                $validated['sort_order']
                ?? 0,

            'is_active' =>
                $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('homepage-banners.index')
            ->with(
                'success',
                'Banner homepage berhasil ditambahkan.'
            );
    }

    /**
     * Update banner.
     */
    public function update(
        Request $request,
        HomepageBanner $homepageBanner
    ) {
        $validated = $request->validate([
            'title' => [
                'nullable',
                'string',
                'max:150',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ], [
            'image.image' =>
                'File harus berupa gambar.',

            'image.mimes' =>
                'Format gambar harus JPG, JPEG, PNG atau WEBP.',

            'image.max' =>
                'Ukuran gambar maksimal 5 MB.',
        ]);

        $imagePath =
            $homepageBanner->image;

        if ($request->hasFile('image')) {
            $newImagePath = $request
                ->file('image')
                ->store(
                    'homepage-banners',
                    'public'
                );

            if (
                $homepageBanner->image
                && Storage::disk('public')
                    ->exists(
                        $homepageBanner->image
                    )
            ) {
                Storage::disk('public')
                    ->delete(
                        $homepageBanner->image
                    );
            }

            $imagePath =
                $newImagePath;
        }

        $homepageBanner->update([
            'title' =>
                $validated['title']
                ?? null,

            'image' =>
                $imagePath,

            'sort_order' =>
                $validated['sort_order'],

            'is_active' =>
                $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('homepage-banners.index')
            ->with(
                'success',
                'Banner homepage berhasil diperbarui.'
            );
    }

    /**
     * Aktif / nonaktifkan banner.
     */
    public function toggle(
        HomepageBanner $homepageBanner
    ) {
        $homepageBanner->update([
            'is_active' =>
                !$homepageBanner->is_active,
        ]);

        return redirect()
            ->route('homepage-banners.index')
            ->with(
                'success',
                $homepageBanner->is_active
                    ? 'Banner berhasil diaktifkan.'
                    : 'Banner berhasil dinonaktifkan.'
            );
    }

    /**
     * Hapus banner.
     */
    public function destroy(
        HomepageBanner $homepageBanner
    ) {
        if (
            $homepageBanner->image
            && Storage::disk('public')
                ->exists(
                    $homepageBanner->image
                )
        ) {
            Storage::disk('public')
                ->delete(
                    $homepageBanner->image
                );
        }

        $homepageBanner->delete();

        return redirect()
            ->route('homepage-banners.index')
            ->with(
                'success',
                'Banner homepage berhasil dihapus.'
            );
    }
}