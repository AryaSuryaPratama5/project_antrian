<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'db_restoran');

mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (!@mysqli_select_db($conn, DB_NAME)) {
    $createDbSql = "CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!mysqli_query($conn, $createDbSql)) {
        die("Gagal membuat database: " . mysqli_error($conn));
    }
    if (!@mysqli_select_db($conn, DB_NAME)) {
        die("Gagal memilih database: " . mysqli_error($conn));
    }
}

mysqli_set_charset($conn, "utf8mb4");
create_tables_if_needed($conn);
ensure_default_menu($conn);

function create_tables_if_needed($conn)
{
    $tables = [
        "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(100) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `nama` VARCHAR(200) NOT NULL,
            `role` VARCHAR(50) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY `username_unique` (`username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS `menu` (
            `id_menu` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `nama_item` VARCHAR(200) NOT NULL,
            `deskripsi` TEXT NULL,
            `gambar` VARCHAR(255) NULL,
            `harga` DECIMAL(12,2) NOT NULL DEFAULT 0,
            `status_tersedia` ENUM('Tersedia', 'Habis') NOT NULL DEFAULT 'Tersedia',
            `kategori` VARCHAR(50) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS `pesanan` (
            `id_pesanan` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `nomor_meja` INT NOT NULL,
            `nama_pelanggan` VARCHAR(200) NOT NULL,
            `jenis_pesanan` VARCHAR(100) NOT NULL,
            `detail_pesanan` TEXT NOT NULL,
            `detail_json` TEXT NOT NULL,
            `total_harga` DECIMAL(15,2) NOT NULL DEFAULT 0,
            `metode_bayar` VARCHAR(100) NOT NULL,
            `status_bayar` VARCHAR(100) NOT NULL DEFAULT 'Belum Bayar',
            `status_pelayanan` VARCHAR(100) NOT NULL DEFAULT 'Menunggu',
            `estimasi_menit` INT NOT NULL DEFAULT 15,
            `waktu_pesan` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    ];

    foreach ($tables as $sql) {
        if (!mysqli_query($conn, $sql)) {
            die("Gagal membuat tabel: " . mysqli_error($conn));
        }
    }
}

function ensure_default_menu($conn)
{
    $menuItems = [
        ['nama_item' => 'Nasi Goreng Spesial',   'deskripsi' => 'Nasi goreng dengan telur, ayam & kerupuk.',            'harga' => 18000, 'gambar' => 'nasi_goreng.jpg',         'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Mie Goreng Aceh',        'deskripsi' => 'Mie goreng khas Aceh dengan bumbu pedas manis.',       'harga' => 17000, 'gambar' => 'mie_goreng_aceh.jpg',     'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Bakso Urat Granat',      'deskripsi' => 'Bakso urat besar dengan kuah kaldu sapi kaya rempah.', 'harga' => 18000, 'gambar' => 'bakso_urat_granat.jpg',    'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Ayam Bakar Madu',        'deskripsi' => 'Ayam bakar manis dengan saus madu khas resto.',         'harga' => 22000, 'gambar' => 'ayam_bakar_madu.jpg',      'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Ayam Geprek Level',      'deskripsi' => 'Ayam geprek dengan sambal pedas pilihan.',             'harga' => 21000, 'gambar' => 'ayam_geprek_level.jpg',    'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Capcay Seafood',         'deskripsi' => 'Capcay seafood segar dengan sayuran berwarna.',       'harga' => 20000, 'gambar' => 'capcay_seafood.jpg',       'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Ikan Bakar Nila',        'deskripsi' => 'Ikan nila bakar dengan sambal kecap pedas.',          'harga' => 25000, 'gambar' => 'ikan_bakar_nila.jpg',      'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Kwetiau Daging Sapi',    'deskripsi' => 'Kwetiau lezat dengan daging sapi empuk.',              'harga' => 19000, 'gambar' => 'Kwetiau_Daging_Sapi.jpg',   'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Pecel Lele',             'deskripsi' => 'Lele goreng crispy dengan sambal dan lalapan.',        'harga' => 17000, 'gambar' => 'pecel_lele.jpg',          'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Pempek Kapal Selam',     'deskripsi' => 'Pempek kapal selam legit dengan cuko pedas.',          'harga' => 20000, 'gambar' => 'pempek_kapal_selam.jpg',  'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Rawon Setan',            'deskripsi' => 'Rawon pedas dengan kuah hitam dan empal sapi.',       'harga' => 22000, 'gambar' => 'rawon_setan.jpg',         'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Rendang Daging',         'deskripsi' => 'Rendang sapi empuk dengan santan dan rempah lengkap.', 'harga' => 28000, 'gambar' => 'rendang_daging.jpg',       'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Sate Ayam Madura',       'deskripsi' => 'Sate ayam manis pedas dengan bumbu kacang.',          'harga' => 18000, 'gambar' => 'sate_ayam_madura.jpg',     'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Sop Buntut',             'deskripsi' => 'Sop buntut kaya kaldu dengan sayuran segar.',         'harga' => 26000, 'gambar' => 'sop_buntut.jpg',          'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Soto Ayam Lamongan',     'deskripsi' => 'Soto ayam bening dengan koya dan sambal.',              'harga' => 15000, 'gambar' => 'soto_ayam_lamongan.jpg',  'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Gado-Gado Betawi',       'deskripsi' => 'Gado-gado segar dengan bumbu kacang kental.',         'harga' => 14000, 'gambar' => 'gado_gado_betawi.jpg',     'status_tersedia' => 'Tersedia', 'kategori' => 'Makanan'],
        ['nama_item' => 'Es Campur',              'deskripsi' => 'Es campur buah dengan santan dan sirup merah.',       'harga' => 10000, 'gambar' => 'es_campur.jpg',           'status_tersedia' => 'Tersedia', 'kategori' => 'Minuman'],
        ['nama_item' => 'Es Degan',               'deskripsi' => 'Es degan segar dengan daging kelapa muda.',           'harga' => 12000, 'gambar' => 'es_degan.jpg',            'status_tersedia' => 'Tersedia', 'kategori' => 'Minuman'],
        ['nama_item' => 'Es Jeruk',               'deskripsi' => 'Jeruk peras segar dengan es batu.',                   'harga' => 7000,  'gambar' => 'es_jeruk_peras.jpg',      'status_tersedia' => 'Tersedia', 'kategori' => 'Minuman'],
        ['nama_item' => 'Es Teh Manis',           'deskripsi' => 'Teh manis segar dengan es batu.',                     'harga' => 5000,  'gambar' => 'es_teh_manis.jpg',        'status_tersedia' => 'Tersedia', 'kategori' => 'Minuman'],
        ['nama_item' => 'Jus Alpukat',            'deskripsi' => 'Jus alpukat creamy dengan susu coklat.',             'harga' => 12000, 'gambar' => 'jus_alpukat.jpg',         'status_tersedia' => 'Tersedia', 'kategori' => 'Minuman'],
        ['nama_item' => 'Jus Mangga',             'deskripsi' => 'Jus mangga manis segar dengan tekstur kental.',       'harga' => 12000, 'gambar' => 'jus_mangga.jpg',          'status_tersedia' => 'Tersedia', 'kategori' => 'Minuman'],
        ['nama_item' => 'Kopi Susu',              'deskripsi' => 'Kopi susu dingin dengan gula aren.',                   'harga' => 13000, 'gambar' => 'kopi_susu.jpg',          'status_tersedia' => 'Tersedia', 'kategori' => 'Minuman'],
        ['nama_item' => 'Air Mineral',            'deskripsi' => 'Air mineral botol 600ml.',                            'harga' => 4000,  'gambar' => 'air_mineral.jpg',        'status_tersedia' => 'Tersedia', 'kategori' => 'Minuman'],
        ['nama_item' => 'Soda Gembira',           'deskripsi' => 'Minuman soda segar dengan sirup buah.',               'harga' => 14000, 'gambar' => 'soda_gembira.jpg',       'status_tersedia' => 'Tersedia', 'kategori' => 'Minuman'],
        ['nama_item' => 'Teh Hangat',             'deskripsi' => 'Teh hangat tradisional untuk pelepas dahaga.',        'harga' => 6000,  'gambar' => 'teh_hangat.jpg',         'status_tersedia' => 'Tersedia', 'kategori' => 'Minuman'],
    ];

    foreach ($menuItems as $item) {
        $name = mysqli_real_escape_string($conn, $item['nama_item']);
        $exists = mysqli_query($conn, "SELECT id_menu, gambar FROM menu WHERE nama_item = '$name' LIMIT 1");
        if ($exists && mysqli_num_rows($exists) > 0) {
            $row = mysqli_fetch_assoc($exists);
            if (empty($row['gambar']) && !empty($item['gambar'])) {
                $updateSql = sprintf(
                    "UPDATE menu SET gambar='%s' WHERE id_menu=%d",
                    mysqli_real_escape_string($conn, $item['gambar']),
                    (int)$row['id_menu']
                );
                mysqli_query($conn, $updateSql);
            }
            continue;
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO menu (nama_item, deskripsi, gambar, harga, status_tersedia, kategori) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Gagal menyiapkan data menu: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, 'sssdss', $item['nama_item'], $item['deskripsi'], $item['gambar'], $item['harga'], $item['status_tersedia'], $item['kategori']);
        if (!mysqli_stmt_execute($stmt)) {
            die("Gagal memasukkan data menu: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
    }
}
