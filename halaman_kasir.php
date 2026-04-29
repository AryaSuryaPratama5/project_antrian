<?php include 'koneksi.php'; 
// Proses Update Otomatis lewat URL
if(isset($_GET['bayar'])){
    $st = ($_GET['bayar'] == 'Belum Bayar') ? 'Sudah Bayar' : 'Belum Bayar';
    mysqli_query($conn, "UPDATE pesanan SET status_bayar='$st' WHERE id_pesanan='$_GET[id]'");
    header("Location: halaman_kasir.php");
}
if(isset($_GET['layani'])){
    $st = ($_GET['layani'] == 'Menunggu') ? 'Sudah Dilayani' : 'Menunggu';
    mysqli_query($conn, "UPDATE pesanan SET status_pelayanan='$st' WHERE id_pesanan='$_GET[id]'");
    header("Location: halaman_kasir.php");
}
if(isset($_GET['stok'])){
    $st = ($_GET['stok'] == 'Tersedia') ? 'Habis' : 'Tersedia';
    mysqli_query($conn, "UPDATE menu SET status_tersedia='$st' WHERE id_menu='$_GET[id]'");
    header("Location: halaman_kasir.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Panel Kasir RM A</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .card { background: white; padding: 20px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #34495e; color: white; padding: 12px; font-size: 11px; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 13px; }
        .badge { padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: bold; text-decoration: none; color: white; display: inline-block; }
        .red { background: #e74c3c; } .green { background: #2ecc71; } .blue { background: #3498db; }
    </style>
</head>
<body>
    <h2>👨‍💻 Control Panel Kasir</h2>
    <div class="card">
        <table>
            <tr><th>Meja</th><th>Pelanggan</th><th>Jenis</th><th>Pesanan</th><th>Pelayanan</th><th>Pembayaran</th></tr>
            <?php $res = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY waktu_pesan DESC");
            while($r = mysqli_fetch_array($res)){ ?>
            <tr>
                <td><b>#<?= $r['nomor_meja'] ?></b></td>
                <td><?= $r['nama_pelanggan'] ?></td>
                <td><span class="badge blue"><?= $r['jenis_pesanan'] ?></span></td>
                <td><small><?= $r['detail_pesanan'] ?></small></td>
                <td><a href="?layani=<?= $r['status_pelayanan'] ?>&id=<?= $r['id_pesanan'] ?>" class="badge <?= ($r['status_pelayanan']=='Menunggu')?'red':'green' ?>">
                    <?= ($r['status_pelayanan']=='Menunggu')?'⏳ Menunggu':'✅ Selesai' ?></a>
                </td>
                <td><a href="?bayar=<?= $r['status_bayar'] ?>&id=<?= $r['id_pesanan'] ?>" class="badge <?= ($r['status_bayar']=='Belum Bayar')?'red':'green' ?>">
                    <?= ($r['status_bayar']=='Belum Bayar')?'❌ Belum':'💰 Lunas' ?></a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <div class="card" style="width: 400px;">
        <h3>🛠️ Stok Menu</h3>
        <table>
            <?php $men = mysqli_query($conn, "SELECT * FROM menu");
            while($m = mysqli_fetch_array($men)){ ?>
            <tr><td><?= $m['nama_item'] ?></td><td align="right"><a href="?stok=<?= $m['status_tersedia'] ?>&id=<?= $m['id_menu'] ?>" class="badge <?= ($m['status_tersedia']=='Habis')?'red':'green' ?>"><?= $m['status_tersedia'] ?></a></td></tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>