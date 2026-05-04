<?php
include 'koneksi.php';

// Validate POST
if (empty($_POST['pilihan']) || empty($_POST['nama']) || empty($_POST['meja'])) {
    header("Location: index.php"); exit;
}

$nama    = htmlspecialchars(trim($_POST['nama']));
$meja    = (int)$_POST['meja'];
$metode  = $_POST['metode_bayar'] ?? 'Tunai';
$jenis   = $_POST['jenis_pesanan'] ?? 'Makan di Tempat';
$pilihan = $_POST['pilihan'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #E8603C; --secondary: #F5A623;
            --dark: #1C1C1E; --text: #3A3A3C; --muted: #8E8E93;
            --surface: #FFFBF5; --border: #F0EDE8; --success: #34C759;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface); min-height: 100vh;
            color: var(--text); padding: 20px 15px 100px;
        }

        .back-btn {
            display: inline-flex; align-items: center; gap: 6px;
            color: var(--muted); font-size: 13px; font-weight: 600;
            text-decoration: none; margin-bottom: 16px;
            padding: 8px 0;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 24px; color: var(--dark); margin-bottom: 20px;
        }

        /* Info Banner */
        .info-banner {
            background: white;
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 16px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        }
        .info-row {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 0; border-bottom: 1px solid var(--border);
        }
        .info-row:last-child { border-bottom: none; }
        .info-icon { font-size: 18px; width: 28px; text-align: center; }
        .info-key { font-size: 12px; color: var(--muted); font-weight: 600; }
        .info-val { font-size: 14px; font-weight: 700; color: var(--dark); }

        /* Order Items */
        .items-card {
            background: white; border-radius: 18px;
            padding: 18px; margin-bottom: 16px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        }
        .card-title {
            font-size: 13px; font-weight: 800; color: var(--dark);
            text-transform: uppercase; letter-spacing: 0.5px;
            margin-bottom: 14px;
        }

        .order-item {
            padding: 12px 0; border-bottom: 1px dashed var(--border);
        }
        .order-item:last-child { border-bottom: none; }
        .item-top {
            display: flex; justify-content: space-between;
            align-items: flex-start; margin-bottom: 4px;
        }
        .item-name { font-weight: 700; font-size: 14px; flex: 1; }
        .item-price { font-weight: 800; color: var(--primary); font-size: 14px; }
        .item-meta { font-size: 12px; color: var(--muted); }
        .item-badge {
            display: inline-block;
            padding: 3px 8px; border-radius: 6px;
            font-size: 10px; font-weight: 700; margin-right: 5px;
        }
        .badge-spice-0 { background: #E8F5E9; color: #2E7D32; }
        .badge-spice-1 { background: #FFF3E0; color: #E65100; }
        .badge-spice-2 { background: #FFEBEE; color: #B71C1C; }
        .badge-notes   { background: #EDE7F6; color: #4527A0; }

        /* Total */
        .total-row {
            display: flex; justify-content: space-between;
            align-items: center; padding: 14px 0 0;
            margin-top: 4px;
        }
        .total-label { font-size: 14px; font-weight: 700; }
        .total-val { font-size: 24px; font-weight: 800; color: var(--primary); }

        /* Sticky Footer */
        .sticky-footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: white; padding: 16px 20px 28px;
            border-radius: 28px 28px 0 0;
            box-shadow: 0 -6px 24px rgba(0,0,0,0.09);
        }
        .btn-confirm {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; border: none; padding: 17px;
            border-radius: 17px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all 0.3s; letter-spacing: 0.3px;
        }
        .btn-confirm:active { transform: scale(0.98); }
        .btn-confirm:disabled { background: #E8E8E8; color: #B0B0B0; }
    </style>
</head>
<body>

<a href="index.php" class="back-btn">← Ubah Pesanan</a>
<div class="page-title">Konfirmasi Pesanan 📋</div>

<!-- Info -->
<div class="info-banner">
    <div class="info-row">
        <span class="info-icon">👤</span>
        <div><div class="info-key">Nama</div><div class="info-val"><?= $nama ?></div></div>
    </div>
    <div class="info-row">
        <span class="info-icon">🪑</span>
        <div><div class="info-key">Nomor Meja</div><div class="info-val">Meja <?= $meja ?></div></div>
    </div>
    <div class="info-row">
        <span class="info-icon"><?= $jenis === 'Bawa Pulang' ? '🛍️' : '🏠' ?></span>
        <div><div class="info-key">Jenis Pesanan</div><div class="info-val"><?= $jenis ?></div></div>
    </div>
    <div class="info-row">
        <span class="info-icon"><?= $metode === 'QRIS' ? '📱' : ($metode === 'Transfer' ? '🏦' : '💵') ?></span>
        <div><div class="info-key">Metode Bayar</div><div class="info-val"><?= $metode ?></div></div>
    </div>
</div>

<!-- Order Items -->
<div class="items-card">
    <div class="card-title">🛒 Rincian Pesanan</div>

    <form action="simpan.php" method="POST" id="finalForm">
        <input type="hidden" name="nama"          value="<?= $nama ?>">
        <input type="hidden" name="meja"          value="<?= $meja ?>">
        <input type="hidden" name="metode_bayar"  value="<?= $metode ?>">
        <input type="hidden" name="jenis_pesanan" value="<?= $jenis ?>">

        <?php
        $total = 0;
        foreach ($pilihan as $id) {
            $id  = (int)$id;
            $d   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM menu WHERE id_menu='$id'"));
            if (!$d) continue;
            $qty    = max(1, (int)($_POST['qty_' . $id] ?? 1));
            $pedas  = htmlspecialchars($_POST['pedas_' . $id] ?? '-');
            $catatan= htmlspecialchars($_POST['catatan_' . $id] ?? '');
            $sub    = $d['harga'] * $qty;
            $total += $sub;

            // Spice badge class
            $spiceClass = 'badge-spice-0';
            if ($pedas === 'Pedas') $spiceClass = 'badge-spice-1';
            elseif ($pedas === 'Ekstra Pedas') $spiceClass = 'badge-spice-2';
        ?>
        <div class="order-item">
            <div class="item-top">
                <div class="item-name"><?= htmlspecialchars($d['nama_item']) ?> <span style="color:#8E8E93;font-weight:600">x<?= $qty ?></span></div>
                <div class="item-price">Rp<?= number_format($sub, 0, ',', '.') ?></div>
            </div>
            <div class="item-meta">
                <?php if($d['kategori'] === 'Makanan' && $pedas !== '-'): ?>
                <span class="item-badge <?= $spiceClass ?>"><?= $pedas === 'Tidak Pedas' ? '🌿' : ($pedas === 'Pedas' ? '🌶️' : '🔥') ?> <?= $pedas ?></span>
                <?php endif; ?>
                <?php if($catatan): ?>
                <span class="item-badge badge-notes">📝 <?= $catatan ?></span>
                <?php endif; ?>
            </div>
            <!-- Pass data forward -->
            <input type="hidden" name="pilihan[]" value="<?= $id ?>">
            <input type="hidden" name="qty_<?= $id ?>" value="<?= $qty ?>">
            <input type="hidden" name="pedas_<?= $id ?>" value="<?= $pedas ?>">
            <input type="hidden" name="catatan_<?= $id ?>" value="<?= htmlspecialchars($_POST['catatan_' . $id] ?? '') ?>">
        </div>
        <?php } ?>

        <div class="total-row">
            <span class="total-label">Total Pembayaran</span>
            <span class="total-val">Rp<?= number_format($total, 0, ',', '.') ?></span>
        </div>

        <div class="sticky-footer">
            <button type="submit" name="proses" class="btn-confirm" id="btnConfirm">
                ✅ Konfirmasi & Kirim Pesanan
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('finalForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnConfirm');
    btn.disabled = true;
    btn.innerText = '⏳ Mengirim pesanan...';
});
</script>
</body>
</html>
