<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimonial;
use App\Models\TestimonialProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TestimonialController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN - LIST TESTIMONI
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $status = $request->get('status');

        $query = Testimonial::query()
            ->with([
                'product',
                'proofs',
                'creator',
            ])
            ->latest();

        if (
            in_array(
                $status,
                [
                    'pending',
                    'approved',
                    'rejected',
                ],
                true
            )
        ) {
            $query->where('status', $status);
        }

        $testimonials = $query->paginate(15);

        return view(
            'testimonials.index',
            compact(
                'testimonials',
                'status'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH TESTIMONI
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $products = Product::query()
            ->orderBy('product_name')
            ->get();

        return view(
            'testimonials.create',
            compact('products')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN TESTIMONI
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => [
                'nullable',
                'exists:products,id',
            ],

            'customer_name' => [
                'required',
                'string',
                'max:150',
            ],

            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'testimonial' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'chat_proofs' => [
                'nullable',
                'array',
                'max:10',
            ],

            'chat_proofs.*' => [
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],

            'video_proofs' => [
                'nullable',
                'array',
                'max:5',
            ],

            'video_proofs.*' => [
                'file',
                'mimes:mp4,mov,webm',
                'max:102400',
            ],
        ]);

        DB::beginTransaction();

        try {

            $testimonial = Testimonial::create([
                'product_id' =>
                    $validated['product_id']
                    ?? null,

                'customer_name' =>
                    $validated['customer_name'],

                'rating' =>
                    $validated['rating'],

                'testimonial' =>
                    $validated['testimonial']
                    ?? null,

                'status' =>
                    'pending',

                'created_by' =>
                    auth()->id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | UPLOAD BUKTI CHAT
            |--------------------------------------------------------------------------
            */

            if (
                $request->hasFile(
                    'chat_proofs'
                )
            ) {

                foreach (
                    $request->file('chat_proofs')
                    as $file
                ) {

                    $path =
                        $file->store(
                            'testimonials/chat',
                            'public'
                        );

                    TestimonialProof::create([
                        'testimonial_id' =>
                            $testimonial->id,

                        'proof_type' =>
                            TestimonialProof::TYPE_CHAT,

                        'file_path' =>
                            $path,

                        'file_name' =>
                            $file->getClientOriginalName(),

                        'mime_type' =>
                            $file->getMimeType(),

                        'file_size' =>
                            $file->getSize(),
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | UPLOAD BUKTI VIDEO
            |--------------------------------------------------------------------------
            */

            if (
                $request->hasFile(
                    'video_proofs'
                )
            ) {

                foreach (
                    $request->file('video_proofs')
                    as $file
                ) {

                    $path =
                        $file->store(
                            'testimonials/video',
                            'public'
                        );

                    TestimonialProof::create([
                        'testimonial_id' =>
                            $testimonial->id,

                        'proof_type' =>
                            TestimonialProof::TYPE_VIDEO,

                        'file_path' =>
                            $path,

                        'file_name' =>
                            $file->getClientOriginalName(),

                        'mime_type' =>
                            $file->getMimeType(),

                        'file_size' =>
                            $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('testimonials.index')
                ->with(
                    'success',
                    'Testimoni berhasil ditambahkan dan menunggu approval.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Testimoni gagal disimpan.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL TESTIMONI
    |--------------------------------------------------------------------------
    */

    public function show(
        Testimonial $testimonial
    ) {
        $testimonial->load([
            'product',
            'proofs',
            'creator',
        ]);

        return view(
            'testimonials.show',
            compact('testimonial')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT TESTIMONI
    |--------------------------------------------------------------------------
    */

    public function edit(
        Testimonial $testimonial
    ) {
        $testimonial->load('proofs');

        $products = Product::query()
            ->orderBy('product_name')
            ->get();

        return view(
            'testimonials.edit',
            compact(
                'testimonial',
                'products'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE TESTIMONI
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Testimonial $testimonial
    ) {
        $validated = $request->validate([
            'product_id' => [
                'nullable',
                'exists:products,id',
            ],

            'customer_name' => [
                'required',
                'string',
                'max:150',
            ],

            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'testimonial' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                ]),
            ],

            'chat_proofs' => [
                'nullable',
                'array',
                'max:10',
            ],

            'chat_proofs.*' => [
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],

            'video_proofs' => [
                'nullable',
                'array',
                'max:5',
            ],

            'video_proofs.*' => [
                'file',
                'mimes:mp4,mov,webm',
                'max:102400',
            ],
        ]);

        DB::beginTransaction();

        try {

            $testimonial->update([
                'product_id' =>
                    $validated['product_id']
                    ?? null,

                'customer_name' =>
                    $validated['customer_name'],

                'rating' =>
                    $validated['rating'],

                'testimonial' =>
                    $validated['testimonial']
                    ?? null,

                'status' =>
                    $validated['status'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | TAMBAH BUKTI CHAT BARU
            |--------------------------------------------------------------------------
            */

            if (
                $request->hasFile(
                    'chat_proofs'
                )
            ) {

                foreach (
                    $request->file('chat_proofs')
                    as $file
                ) {

                    $path =
                        $file->store(
                            'testimonials/chat',
                            'public'
                        );

                    TestimonialProof::create([
                        'testimonial_id' =>
                            $testimonial->id,

                        'proof_type' =>
                            TestimonialProof::TYPE_CHAT,

                        'file_path' =>
                            $path,

                        'file_name' =>
                            $file->getClientOriginalName(),

                        'mime_type' =>
                            $file->getMimeType(),

                        'file_size' =>
                            $file->getSize(),
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | TAMBAH VIDEO BARU
            |--------------------------------------------------------------------------
            */

            if (
                $request->hasFile(
                    'video_proofs'
                )
            ) {

                foreach (
                    $request->file('video_proofs')
                    as $file
                ) {

                    $path =
                        $file->store(
                            'testimonials/video',
                            'public'
                        );

                    TestimonialProof::create([
                        'testimonial_id' =>
                            $testimonial->id,

                        'proof_type' =>
                            TestimonialProof::TYPE_VIDEO,

                        'file_path' =>
                            $path,

                        'file_name' =>
                            $file->getClientOriginalName(),

                        'mime_type' =>
                            $file->getMimeType(),

                        'file_size' =>
                            $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('testimonials.show', $testimonial)
                ->with(
                    'success',
                    'Testimoni berhasil diperbarui.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Testimoni gagal diperbarui.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Testimonial $testimonial
    ) {
        $testimonial->update([
            'status' => 'approved',
        ]);

        return back()->with(
            'success',
            'Testimoni berhasil di-approve.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Testimonial $testimonial
    ) {
        $testimonial->update([
            'status' => 'rejected',
        ]);

        return back()->with(
            'success',
            'Testimoni berhasil ditolak.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | KEMBALIKAN KE PENDING
    |--------------------------------------------------------------------------
    */

    public function pending(
        Testimonial $testimonial
    ) {
        $testimonial->update([
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Status testimoni dikembalikan ke Pending.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS SATU BUKTI
    |--------------------------------------------------------------------------
    */

    public function destroyProof(
        TestimonialProof $proof
    ) {
        $testimonialId =
            $proof->testimonial_id;

        if (
            !empty($proof->file_path)
            &&
            Storage::disk('public')
                ->exists($proof->file_path)
        ) {
            Storage::disk('public')
                ->delete($proof->file_path);
        }

        $proof->delete();

        return redirect()
            ->route(
                'testimonials.edit',
                $testimonialId
            )
            ->with(
                'success',
                'Bukti berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS TESTIMONI
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Testimonial $testimonial
    ) {
        DB::beginTransaction();

        try {

            $testimonial->load('proofs');

            foreach (
                $testimonial->proofs
                as $proof
            ) {

                if (
                    !empty($proof->file_path)
                    &&
                    Storage::disk('public')
                        ->exists(
                            $proof->file_path
                        )
                ) {

                    Storage::disk('public')
                        ->delete(
                            $proof->file_path
                        );
                }
            }

            $testimonial->delete();

            DB::commit();

            return redirect()
                ->route('testimonials.index')
                ->with(
                    'success',
                    'Testimoni berhasil dihapus.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()->with(
                'error',
                'Testimoni gagal dihapus.'
            );
        }
    }
}