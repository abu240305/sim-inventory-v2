<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryMenuSeeder extends Seeder
{
    public function run(): void
    {
        $roleIds = DB::table('roles')->pluck('id', 'slug');

        $menus = [
            ['name' => 'Data Master', 'slug' => 'master', 'path' => null, 'icon' => 'ri-database-2-line', 'order_no' => 10],
            ['parent' => 'Data Master', 'name' => 'Kategori', 'slug' => 'kategori.index', 'path' => '/kategori', 'icon' => 'ri-price-tag-3-line', 'order_no' => 1],
            ['parent' => 'Data Master', 'name' => 'Barang', 'slug' => 'barang.index', 'path' => '/barang', 'icon' => 'ri-archive-line', 'order_no' => 2],
            ['name' => 'Transaksi', 'slug' => 'transaksi', 'path' => null, 'icon' => 'ri-exchange-box-line', 'order_no' => 11],
            ['parent' => 'Transaksi', 'name' => 'Barang Masuk', 'slug' => 'transaksi-masuk.index', 'path' => '/transaksi-masuk', 'icon' => 'ri-arrow-right-down-line', 'order_no' => 1],
            ['parent' => 'Transaksi', 'name' => 'Barang Keluar', 'slug' => 'transaksi-keluar.index', 'path' => '/transaksi-keluar', 'icon' => 'ri-arrow-right-up-line', 'order_no' => 2],
            ['name' => 'Laporan', 'slug' => 'laporan.index', 'path' => '/laporan', 'icon' => 'ri-file-chart-line', 'order_no' => 12],
        ];

        $menuIdMap = DB::table('menus')->pluck('id', 'name')->toArray();

        foreach ($menus as $m) {
            $parentId = isset($m['parent']) ? ($menuIdMap[$m['parent']] ?? null) : null;
            
            DB::table('menus')->updateOrInsert(
                ['slug' => $m['slug']],
                [
                    'parent_id' => $parentId,
                    'name' => $m['name'],
                    'path' => $m['path'],
                    'icon' => $m['icon'],
                    'order_no' => $m['order_no'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            
            $dbMenu = DB::table('menus')->where('slug', $m['slug'])->first();
            $menuIdMap[$m['name']] = $dbMenu->id;

            // Assign to Super Admin and Admin
            foreach (['super-admin', 'admin'] as $roleSlug) {
                if (isset($roleIds[$roleSlug])) {
                    DB::table('role_menu')->updateOrInsert(
                        ['role_id' => $roleIds[$roleSlug], 'menu_id' => $dbMenu->id],
                        [
                            'can_create' => true,
                            'can_read' => true,
                            'can_update' => true,
                            'can_delete' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            // Assign Laporan to Visitor
            if ($m['slug'] == 'laporan.index' && isset($roleIds['visitor'])) {
                DB::table('role_menu')->updateOrInsert(
                    ['role_id' => $roleIds['visitor'], 'menu_id' => $dbMenu->id],
                    [
                        'can_create' => false,
                        'can_read' => true,
                        'can_update' => false,
                        'can_delete' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
