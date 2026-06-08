<?php
include 'koneksi.php';
if(isset($_POST['proses'])){
    $meja = $_POST['meja']; $nama = $_POST['nama']; $metode = $_POST['metode_bayar']; 
    $jenis = $_POST['jenis_pesanan']; $ids = $_POST['pilihan']; $detail = ""; $total = 0;

    foreach($ids as $id){
        $d = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM menu WHERE id_menu='$id'"));
        $q = $_POST['qty_'.$id]; $total += ($d['harga'] * $q);
        $detail .= $d['nama_item'] . " (" . $q . "x), ";
    }
    $detail = rtrim($detail, ", ");
    $status_bayar = ($metode == 'Tunai') ? 'Belum Bayar' : 'Sudah Bayar';
    $status_pelayanan = 'Menunggu';

    $sql = "INSERT INTO pesanan (nomor_meja, nama_pelanggan, jenis_pesanan, detail_pesanan, total_harga, metode_bayar, status_bayar, status_pelayanan, waktu_pesan) 
            VALUES ('$meja', '$nama', '$jenis', '$detail', '$total', '$metode', '$status_bayar', '$status_pelayanan', NOW())";
    
    if(mysqli_query($conn, $sql)){
        $id_pesanan = mysqli_insert_id($conn);
        if ($metode == 'Tunai') {
            echo "<script>alert('Pesanan Terkirim! Silakan tunggu di meja Anda.'); window.location.href='index.php';</script>";
        } else {
            // Arahkan ke halaman QRIS untuk metode QRIS maupun Transfer
            echo "<script>window.location.href='bayar_qris.php?id=$id_pesanan&total=$total';</script>";
        }
    }
}
?>