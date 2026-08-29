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
        $permissions = Permission::orderBy(
            'name'
        )->get();

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

            /*
            |--------------------------------------------------------------------------
            | Password hanya dibutuhkan saat membuat akun
            |--------------------------------------------------------------------------
            */

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
        | Buat User
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
        | Simpan Direct Permission
        |--------------------------------------------------------------------------
        */

        $user->syncPermissions(
            $validated['permissions'] ?? []
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
        $permissions = Permission::orderBy(
            'name'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Ambil hanya permission langsung milik user
        |--------------------------------------------------------------------------
        */

        $selectedPermissions = $user
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
    | Tidak ada perubahan password dari User Management.
    |
    | Administrator hanya dapat mengubah:
    | - Nama
    | - Email
    | - Hak akses
    |
    | Password diubah sendiri oleh user melalui Reset Password.
    |
    */

    public function update(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Nama
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Email
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
            | Permission
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
        | Proteksi Administrator
        |--------------------------------------------------------------------------
        |
        | Permission Administrator tidak boleh dikurangi.
        |
        */

        $isAdministrator =
            $user->hasRole(
                'Administrator'
            );


        /*
        |--------------------------------------------------------------------------
        | Update Data Dasar User
        |--------------------------------------------------------------------------
        |
        | PASSWORD TIDAK DISENTUH.
        |
        */

        $user->name =
            $validated['name'];

        $user->email =
            $validated['email'];

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | Update Permission User Biasa
        |--------------------------------------------------------------------------
        */

        if (!$isAdministrator) {

            $user->syncPermissions(
                $validated['permissions']
                    ?? []
            );

        }


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Data dan hak akses user berhasil diperbarui.'
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
        | Tidak boleh hapus akun sendiri
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
        | Administrator Tidak Boleh Dihapus
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
        | Hapus User
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