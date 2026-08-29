<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Bersihkan Cache Permission
        |--------------------------------------------------------------------------
        */

        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();


        /*
        |--------------------------------------------------------------------------
        | Daftar Permission
        |--------------------------------------------------------------------------
        */

        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            'dashboard.view',


            /*
            |--------------------------------------------------------------------------
            | Produk
            |--------------------------------------------------------------------------
            */

            'products.view',
            'products.create',
            'products.edit',
            'products.delete',


            /*
            |--------------------------------------------------------------------------
            | Promo Campaign
            |--------------------------------------------------------------------------
            */

            'promo-campaigns.view',
            'promo-campaigns.create',
            'promo-campaigns.edit',
            'promo-campaigns.delete',


            /*
            |--------------------------------------------------------------------------
            | Stok Masuk
            |--------------------------------------------------------------------------
            */

            'stock-ins.view',
            'stock-ins.create',
            'stock-ins.edit',
            'stock-ins.delete',


            /*
            |--------------------------------------------------------------------------
            | Stok Keluar
            |--------------------------------------------------------------------------
            */

            'stock-outs.view',
            'stock-outs.create',
            'stock-outs.edit',
            'stock-outs.delete',

            /*
            | Petugas penjual memastikan uang customer sudah diterima
            */
            'stock-outs.verify-payment',

            /*
            | Admin / pemegang uang memverifikasi setoran
            */
            'stock-outs.confirm-deposit',


            /*
            |--------------------------------------------------------------------------
            | TV Voucher
            |--------------------------------------------------------------------------
            */

            'tv-vouchers.view',
            'tv-vouchers.create',
            'tv-vouchers.edit',
            'tv-vouchers.delete',

            /*
            | Petugas yang menjual / isi paket
            | memastikan uang customer sudah diterima
            */
            'tv-vouchers.verify-payment',

            /*
            | Admin / pemegang uang
            | memverifikasi uang sudah disetor
            */
            'tv-vouchers.confirm-deposit',

            /*
            | Hak akses melihat laporan TV Voucher
            */
            'tv-vouchers.report',


            /*
            |--------------------------------------------------------------------------
            | Supplier
            |--------------------------------------------------------------------------
            */

            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.delete',


            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.delete',


            /*
            |--------------------------------------------------------------------------
            | Report
            |--------------------------------------------------------------------------
            */

            'reports.view',


            /*
            |--------------------------------------------------------------------------
            | User Management
            |--------------------------------------------------------------------------
            */

            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
        ];


        /*
        |--------------------------------------------------------------------------
        | Buat Permission
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Hanya Role Administrator
        |--------------------------------------------------------------------------
        */

        $administrator = Role::firstOrCreate([
            'name' => 'Administrator',
            'guard_name' => 'web',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Administrator Mendapat Semua Permission
        |--------------------------------------------------------------------------
        */

        $administrator->syncPermissions(
            Permission::where(
                'guard_name',
                'web'
            )->get()
        );


        /*
        |--------------------------------------------------------------------------
        | Hapus Role Lama yang Tidak Dipakai
        |--------------------------------------------------------------------------
        |
        | User biasa memakai direct permission, bukan role staff.
        |
        */

        Role::whereIn('name', [
            'Staff Gudang',
            'Staff Voucher',
            'Viewer',
        ])
            ->where(
                'guard_name',
                'web'
            )
            ->delete();


        /*
        |--------------------------------------------------------------------------
        | Bersihkan Cache Lagi
        |--------------------------------------------------------------------------
        */

        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();
    }
}