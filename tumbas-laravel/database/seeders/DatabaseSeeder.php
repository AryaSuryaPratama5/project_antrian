<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Staff Users ───────────────────────────────────────────────
        DB::table('users')->upsert([
            [
                'name'     => 'Admin',
                'username' => 'admin',
                'nama'     => 'Administrator',
                'role'     => 'admin',
                'email'    => 'admin@resto.com',
                'password' => Hash::make('admin123'),
            ],
            [
                'name'     => 'Kasir',
                'username' => 'kasir',
                'nama'     => 'Staff Kasir',
                'role'     => 'kasir',
                'email'    => 'kasir@resto.com',
                'password' => Hash::make('kasir123'),
            ],
            [
                'name'     => 'Dapur',
                'username' => 'dapur',
                'nama'     => 'Staff Dapur',
                'role'     => 'dapur',
                'email'    => 'dapur@resto.com',
                'password' => Hash::make('dapur123'),
            ],
        ], ['email'], ['name', 'username', 'nama', 'role', 'password']);

        // ── Menu Items ────────────────────────────────────────────────
        $menus = [
            // Makanan
            ['nama_item' => 'Nasi Goreng Spesial',  'deskripsi' => 'Nasi goreng dengan telur, ayam & kerupuk',        'harga' => 18000, 'gambar' => 'nasi_goreng.jpg',           'kategori' => 'Makanan', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Mie Ayam Bakso',        'deskripsi' => 'Mie ayam komplit dengan bakso & pangsit',        'harga' => 16000, 'gambar' => 'bakso_urat_granat.jpg',       'kategori' => 'Makanan', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Ayam Goreng Kremes',    'deskripsi' => 'Ayam goreng crispy dengan kremes gurih',         'harga' => 22000, 'gambar' => 'ayam_bakar_madu.jpg',         'kategori' => 'Makanan', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Soto Ayam',             'deskripsi' => 'Soto ayam kuah bening dengan tauge & bihun',    'harga' => 15000, 'gambar' => 'soto_ayam_lamongan.jpg',      'kategori' => 'Makanan', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Gado-Gado',             'deskripsi' => 'Sayuran segar dengan bumbu kacang spesial',     'harga' => 14000, 'gambar' => 'gado_gado_betawi.jpg',        'kategori' => 'Makanan', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Rendang Sapi',          'deskripsi' => 'Rendang daging sapi bumbu rempah Minang',       'harga' => 28000, 'gambar' => 'rendang_daging.jpg',         'kategori' => 'Makanan', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Pecel Lele',            'deskripsi' => 'Lele goreng crispy + nasi + sambal lalapan',    'harga' => 17000, 'gambar' => 'pempek_kapal_selam.jpg',      'kategori' => 'Makanan', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Bakso Urat Jumbo',      'deskripsi' => 'Bakso urat besar dengan kuah kaldu sapi',       'harga' => 18000, 'gambar' => 'bakso_urat_granat.jpg',       'kategori' => 'Makanan', 'status_tersedia' => 'Tersedia'],
            // Minuman
            ['nama_item' => 'Es Teh Manis',          'deskripsi' => 'Teh manis segar dengan es batu',                'harga' => 5000,  'gambar' => 'es_teh_manis.jpg',          'kategori' => 'Minuman', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Es Jeruk',              'deskripsi' => 'Jeruk peras segar dengan es batu',              'harga' => 7000,  'gambar' => 'es_jeruk_peras.jpg',         'kategori' => 'Minuman', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Jus Alpukat',           'deskripsi' => 'Jus alpukat creamy dengan susu coklat',         'harga' => 12000, 'gambar' => 'jus_alpukat.jpg',          'kategori' => 'Minuman', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Kopi Susu Iced',        'deskripsi' => 'Kopi arabika dengan susu segar & gula aren',    'harga' => 13000, 'gambar' => 'kopi_susu.jpg',            'kategori' => 'Minuman', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Air Mineral',           'deskripsi' => 'Air mineral botol 600ml',                      'harga' => 4000,  'gambar' => 'air_mineral.jpg',          'kategori' => 'Minuman', 'status_tersedia' => 'Tersedia'],
            ['nama_item' => 'Es Campur',             'deskripsi' => 'Es campur buah dengan santan & sirup merah',    'harga' => 10000, 'gambar' => 'es_campur.jpg',            'kategori' => 'Minuman', 'status_tersedia' => 'Tersedia'],
        ];

        foreach ($menus as $menu) {
            DB::table('menus')->updateOrInsert(
                ['nama_item' => $menu['nama_item']],
                array_merge($menu, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
