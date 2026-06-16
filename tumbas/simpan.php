<?php
include 'koneksi.php';

if (!isset($_POST['proses'])) {
    header("Location: index.php"); exit;
}

$meja   = (int)$_POST['meja'];
$nama   = mysqli_real_escape_string($conn, trim($_POST['nama']));
$metode = mysqli_real_escape_string($conn, $_POST['metode_bayar']);
$jenis  = mysqli_real_escape_string($conn, $_POST['jenis_pesanan']);
$ids    = $_POST['pilihan'] ?? [];

if (empty($ids)) {
    header("Location: index.php"); exit;
}

$detail_text  = "";
$detail_items = [];
$total        = 0;

foreach ($ids as $id) {
    $id  = (int)$id;
    $d   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM menu WHERE id_menu=$id"));
    if (!$d) continue;
    $qty    = max(1, (int)($_POST['qty_' . $id] ?? 1));
    $pedas  = $_POST['pedas_' . $id] ?? '-';
    $catatan= trim($_POST['catatan_' . $id] ?? '');
    $sub    = $d['harga'] * $qty;
    $total += $sub;

    // Readable detail
    $pedas_tag = ($pedas !== '-') ? " [$pedas]" : '';
    $cat_tag   = $catatan ? " (Catatan: $catatan)" : '';
    $detail_text .= $d['nama_item'] . " x$qty" . $pedas_tag . $cat_tag . ", ";

    // Structured JSON
    $detail_items[] = [
        'id'      => $id,
        'nama'    => $d['nama_item'],
        'kategori'=> $d['kategori'],
        'qty'     => $qty,
        'harga'   => (int)$d['harga'],
        'subtotal'=> $sub,
        'pedas'   => $pedas,
        'catatan' => $catatan,
    ];
}

$detail_text  = rtrim($detail_text, ', ');
$detail_json  = mysqli_real_escape_string($conn, json_encode($detail_items, JSON_UNESCAPED_UNICODE));
$detail_text  = mysqli_real_escape_string($conn, $detail_text);

// Estimasi waktu: 10 menit dasar + 3 menit per item
$jml_items    = array_sum(array_column($detail_items, 'qty'));
$estimasi     = min(60, 10 + ($jml_items * 3));

// Status bayar
$status_bayar = ($metode === 'Tunai') ? 'Belum Bayar' : 'Sudah Bayar';

$sql = "INSERT INTO pesanan 
        (nomor_meja, nama_pelanggan, jenis_pesanan, detail_pesanan, detail_json,
         total_harga, metode_bayar, status_bayar, status_pelayanan, estimasi_menit, waktu_pesan)
        VALUES
        ('$meja', '$nama', '$jenis', '$detail_text', '$detail_json',
         '$total', '$metode', '$status_bayar', 'Menunggu', '$estimasi', NOW())";

if (mysqli_query($conn, $sql)) {
    $new_id = mysqli_insert_id($conn);
    if ($metode === 'QRIS' || $metode === 'Transfer') {
        header("Location: ./bayar_qris.php?id=$new_id&total=$total");
        exit;
    }
    header("Location: tracking.php?id=$new_id");
    exit;
} else {
    die("Gagal menyimpan pesanan: " . mysqli_error($conn));
}
