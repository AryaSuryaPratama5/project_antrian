<?php include 'koneksi.php'; 
$nama = $_POST['nama']; $meja = $_POST['meja']; $metode = $_POST['metode_bayar']; 
$jenis = $_POST['jenis_pesanan']; $pilihan = $_POST['pilihan'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f4f8; display: flex; align-items: center; min-height: 100vh; margin: 0; overflow-x:hidden; }
        .bubble { position: absolute; background: rgba(0, 0, 0, 0.05); border-radius: 50%; pointer-events: none; animation: animate 0.6s linear forwards; }
        @keyframes animate { 0% { width: 0; height: 0; opacity: 0.5; } 100% { width: 400px; height: 400px; opacity: 0; } }
        .card { max-width: 400px; width: 90%; margin: auto; background: white; padding: 30px; border-radius: 40px; box-shadow: 0 25px 50px rgba(0,0,0,0.1); text-align: center; }
        .badge-info { background: #fff3e0; color: #e67e22; padding: 10px; border-radius: 15px; margin: 15px 0; font-size: 13px; border-left: 5px solid #ff7e5f; text-align: left; }
        .item-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #eee; font-size: 14px; }
        .btn-final { background: linear-gradient(45deg, #ff7e5f, #feb47b); color: white; width: 100%; padding: 18px; border: none; border-radius: 20px; font-weight: bold; cursor: pointer; margin-top: 20px; box-shadow: 0 10px 20px rgba(255,126,95,0.3); }
    </style>
</head>
<body>
<div class="card">
    <h2 style="margin-top:0;">Konfirmasi 📝</h2>
    <div class="badge-info">
        <strong><?= $nama ?> (Meja <?= $meja ?>)</strong><br>
        <span>Status: <?= $jenis ?></span><br>
        <span>Bayar via: <?= $metode ?></span>
    </div>
    <form action="simpan.php" method="POST">
        <input type="hidden" name="nama" value="<?= $nama ?>"><input type="hidden" name="meja" value="<?= $meja ?>">
        <input type="hidden" name="metode_bayar" value="<?= $metode ?>"><input type="hidden" name="jenis_pesanan" value="<?= $jenis ?>">
        <?php $total = 0; foreach($pilihan as $id){
            $d = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM menu WHERE id_menu='$id'"));
            $qty = $_POST['qty_'.$id]; $sub = $d['harga'] * $qty; $total += $sub;
            echo "<div class='item-row'><span>$d[nama_item] (x$qty)</span><b>Rp".number_format($sub)."</b>
                  <input type='hidden' name='pilihan[]' value='$id'><input type='hidden' name='qty_$id' value='$qty'></div>";
        } ?>
        <h2 style="color: #27ae60; text-align: right; margin-top: 20px;">Total Rp<?= number_format($total) ?></h2>
        <button type="submit" name="proses" class="btn-final">YUK, PESAN SEKARANG!</button>
        <a href="index.php" style="display:block; margin-top:15px; color:#aaa; text-decoration:none; font-size:12px;">Batal & Ubah</a>
    </form>
</div>
<script>
    document.addEventListener('click', (e) => {
        let b = document.createElement('span'); b.classList.add('bubble');
        b.style.left = e.pageX + 'px'; b.style.top = e.pageY + 'px';
        document.body.appendChild(b); setTimeout(() => b.remove(), 600);
    });
</script>
</body>
</html>