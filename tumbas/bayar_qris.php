<?php
$id = $_GET['id'] ?? '';
$total = $_GET['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS & Transfer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f4f8; display: flex; align-items: center; min-height: 100vh; margin: 0; }
        .card { max-width: 420px; width: 94%; margin: auto; background: white; padding: 32px 26px; border-radius: 32px; box-shadow: 0 25px 50px rgba(0,0,0,0.1); text-align: center; }
        .title { font-size: 22px; font-weight: 700; margin-bottom: 8px; }
        .subtitle { color: #4B5563; margin-bottom: 22px; line-height: 1.6; }
        .qris-img { width: 100%; max-width: 280px; border-radius: 22px; border: 2px dashed #ff7e5f; padding: 10px; margin: 18px auto; display: block; }
        .total-pay { font-size: 24px; color: #E8603C; font-weight: 800; margin: 18px 0 10px; }
        .hint { font-size: 14px; color: #6B7280; margin-bottom: 22px; }
        .btn-finish { background: linear-gradient(135deg, #E8603C, #F5A623); color: white; width: 100%; padding: 16px 18px; border: none; border-radius: 18px; font-weight: 700; font-size: 15px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-finish:hover { opacity: 0.96; }
        .note { margin-top: 18px; font-size: 13px; color: #9CA3AF; line-height: 1.5; }
    </style>
</head>
<body>
<div class="card">
    <div class="title">Pembayaran QRIS</div>
    <div class="subtitle">Silakan scan QR Code di bawah ini menggunakan aplikasi e-wallet atau m-banking Anda.</div>
    <?php $qrisFile = '../img/qris.jpg'; ?>
    <img src="<?= file_exists($qrisFile) ? $qrisFile : 'https://via.placeholder.com/280x280?text=QRIS+tidak+tersedia' ?>"
         alt="QRIS" class="qris-img"
         onerror="this.onerror=null; this.src='https://via.placeholder.com/280x280?text=Upload+QRIS+di+../img/qris.jpg';">
    <?php if (!file_exists($qrisFile)): ?>
        <div class="note" style="color:#B91C1C;">Gambar QRIS belum ditemukan di <code>img/qris.jpg</code>. Tambahkan file tersebut, lalu muat ulang halaman.</div>
    <?php endif; ?>
    <div class="hint">Total yang harus dibayar:</div>
    <div class="total-pay">Rp<?= number_format((int)$total, 0, ',', '.') ?></div>
    <a href="index.php" class="btn-finish" onclick="alert('Terima kasih! Setelah bayar, kasir akan memproses pesanan Anda.');">SAYA SUDAH BAYAR</a>
    <div class="note">Pastikan file <strong>qris.jpg</strong> ada di folder <code>project_antrian/img</code> agar QR Code tampil.</div>
</div>
</body>
</html>
