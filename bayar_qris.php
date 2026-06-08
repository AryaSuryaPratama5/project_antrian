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
        .card { max-width: 400px; width: 90%; margin: auto; background: white; padding: 30px; border-radius: 40px; box-shadow: 0 25px 50px rgba(0,0,0,0.1); text-align: center; }
        .qris-img { width: 100%; max-width: 250px; border-radius: 20px; border: 2px dashed #ff7e5f; padding: 10px; margin: 20px 0; }
        .btn-finish { background: linear-gradient(45deg, #00b09b, #96c93d); color: white; width: 100%; padding: 18px; border: none; border-radius: 20px; font-weight: bold; cursor: pointer; margin-top: 20px; text-decoration: none; display: inline-block; box-sizing: border-box; }
        .total-pay { font-size: 24px; color: #e67e22; font-weight: bold; }
    </style>
</head>
<body>
<div class="card">
    <h2>Scan untuk Membayar 📱</h2>
    <p>Silakan scan QR Code (QRIS) di bawah ini atau transfer ke nomor rekening yang tertera untuk menyelesaikan pembayaran Anda.</p>
    
    <!-- Upload gambar QRIS Anda dan ganti namanya menjadi qris.jpg di dalam folder img -->
    <img src="img/qris.jpg" alt="QRIS" class="qris-img" onerror="this.onerror=null; this.src='https://via.placeholder.com/250?text=Upload+QRIS+ke+img/qris.jpg';">
    
    <p>Total yang harus dibayar:</p>
    <div class="total-pay">Rp<?= number_format($total) ?></div>
    
    <a href="index.php" class="btn-finish" onclick="alert('Terima kasih, pembayaran Anda sedang dicek oleh kasir. Silakan tunggu di meja.');">SAYA SUDAH BAYAR</a>
</div>
</body>
</html>
