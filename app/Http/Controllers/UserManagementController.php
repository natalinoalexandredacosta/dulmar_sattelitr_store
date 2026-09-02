<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST USER
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $users = User::with([
            'roles',
            'permissions',
        ])
            ->orderBy('name')
            ->get();

        return view(
            'users.index',
            compact('users')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM CREATE USER
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $permissions = Permission::query()
            ->orderBy('name')
            ->get();

        return view(
            'users.create',
            compact('permissions')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN USER BARU
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'string',
                'exists:permissions,name',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | BUAT USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'password' =>
                Hash::make(
                    $validated['password']
                ),
        ]);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DIRECT PERMISSION
        |--------------------------------------------------------------------------
        |
        | Termasuk permission baru:
        |
        | cash-admin.view
        |
        | selama permission tersebut sudah ada di tabel permissions.
        |
        */

        $permissions =
            $validated['permissions']
            ?? [];

        $user->syncPermissions(
            $permissions
        );


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Akun berhasil dibuat.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT USER
    |--------------------------------------------------------------------------
    */

    public function edit(
        User $user
    ) {
        $permissions = Permission::query()
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AMBIL DIRECT PERMISSION USER
        |--------------------------------------------------------------------------
        |
        | Permission dari checkbox User Management disimpan sebagai
        | direct permission.
        |
        */

        $selectedPermissions =
            $user
                ->getDirectPermissions()
                ->pluck('name')
                ->toArray();


        return view(
            'users.edit',
            compact(
                'user',
                'permissions',
                'selectedPermissions'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    |
    | Password tidak diubah dari User Management.
    |
    | Administrator:
    | - Nama dapat diubah
    | - Email dapat diubah
    | - Permission tidak dikurangi dari halaman ini
    |
    | User biasa:
    | - Nama dapat diubah
    | - Email dapat diubah
    | - Permission dapat ditambah / dicabut
    |
    */

    public function update(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | NAMA
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | EMAIL
            |--------------------------------------------------------------------------
            */

            'email' => [
                'required',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                    $user->id
                ),
            ],


            /*
            |--------------------------------------------------------------------------
            | PERMISSION
            |--------------------------------------------------------------------------
            */

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'string',
                'exists:permissions,name',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | CEK ADMINISTRATOR
        |--------------------------------------------------------------------------
        */

        $isAdministrator =
            $user->hasRole(
                'Administrator'
            );


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA DASAR USER
        |--------------------------------------------------------------------------
        */

        $user->name =
            $validated['name'];

        $user->email =
            $validated['email'];

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | UPDATE PERMISSION USER BIASA
        |--------------------------------------------------------------------------
        |
        | Semua permission checkbox yang dicentang akan disimpan.
        |
        | Contoh:
        | dashboard.view
        | products.view
        | stock-ins.view
        | stock-outs.view
        | tv-vouchers.view
        | cash-admin.view
        | suppliers.view
        | customers.view
        | reports.view
        | users.view
        |
        | Permission yang tidak dicentang akan dicabut.
        |
        */

        if (!$isAdministrator) {

            $permissions =
                $validated['permissions']
                ?? [];

            $user->syncPermissions(
                $permissions
            );

        }


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                $isAdministrator
                    ? 'Data Administrator berhasil diperbarui.'
                    : 'Data dan hak akses user berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS USER
    |--------------------------------------------------------------------------
    */

    public function destroy(
        User $user
    ) {
        /*
        |--------------------------------------------------------------------------
        | TIDAK BOLEH HAPUS AKUN SENDIRI
        |--------------------------------------------------------------------------
        */

        if (
            auth()->id() ===
            $user->id
        ) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'Anda tidak dapat menghapus akun yang sedang digunakan.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATOR TIDAK BOLEH DIHAPUS
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole(
                'Administrator'
            )
        ) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'Akun Administrator tidak dapat dihapus.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CABUT PERMISSION SEBELUM HAPUS
        |--------------------------------------------------------------------------
        */

        $user->syncPermissions([]);


        /*
        |--------------------------------------------------------------------------
        | HAPUS USER
        |--------------------------------------------------------------------------
        */

        $user->delete();


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Akun berhasil dihapus.'
            );
    }
}