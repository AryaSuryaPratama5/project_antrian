<?php
include 'koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit; }

$p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pesanan=$id"));
if (!$p) { header("Location: index.php"); exit; }

$items  = json_decode($p['detail_json'] ?? '[]', true) ?: [];
$status = $p['status_pelayanan'];

// Status config
$steps = ['Menunggu', 'Diproses', 'Siap', 'Selesai'];
$stepIndex = array_search($status, $steps);

$statusConfig = [
    'Menunggu' => ['icon'=>'⏳', 'color'=>'#F5A623', 'bg'=>'#FFF8E1', 'msg'=>'Pesanan Anda sedang menunggu dikonfirmasi dapur.'],
    'Diproses' => ['icon'=>'👨‍🍳', 'color'=>'#2196F3', 'bg'=>'#E3F2FD', 'msg'=>'Dapur sedang menyiapkan pesanan Anda!'],
    'Siap'     => ['icon'=>'🔔', 'color'=>'#9C27B0', 'bg'=>'#F3E5F5', 'msg'=>'Pesanan siap! Kasir akan segera mengantarkan.'],
    'Selesai'  => ['icon'=>'✅', 'color'=>'#34C759', 'bg'=>'#E8F5E9', 'msg'=>'Pesanan telah selesai. Selamat menikmati! 😊'],
];
$cfg = $statusConfig[$status] ?? $statusConfig['Menunggu'];

// Hitung estimasi
$waktu_pesan = strtotime($p['waktu_pesan']);
$estimasi_detik = ($p['estimasi_menit'] * 60);
$sisa_detik = max(0, ($waktu_pesan + $estimasi_detik) - time());
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Status Pesanan #<?= $id ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #E8603C; --secondary: #F5A623;
            --dark: #1C1C1E; --text: #3A3A3C; --muted: #8E8E93;
            --surface: #FFFBF5; --border: #F0EDE8;
            --status-color: <?= $cfg['color'] ?>;
            --status-bg: <?= $cfg['bg'] ?>;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface); min-height: 100vh;
            color: var(--text);
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #E8603C, #F5A623);
            padding: 50px 20px 60px;
            text-align: center; position: relative; overflow: hidden;
        }
        .header::before {
            content:''; position:absolute; top:-50px; right:-40px;
            width:200px; height:200px; background:rgba(255,255,255,0.1); border-radius:50%;
        }
        .header h2 {
            font-family: 'Playfair Display', serif;
            color: white; font-size: 22px; margin-bottom: 5px;
            position: relative; z-index: 1;
        }
        .header p { color: rgba(255,255,255,0.85); font-size: 13px; position: relative; z-index: 1; }
        .order-num {
            display: inline-block; background: rgba(255,255,255,0.25);
            backdrop-filter: blur(10px); color: white;
            padding: 6px 16px; border-radius: 20px;
            font-size: 13px; font-weight: 700; margin-top: 8px;
            position: relative; z-index: 1;
        }

        /* Content */
        .content { padding: 0 15px 100px; margin-top: -22px; }

        /* Status Card */
        .status-card {
            background: white; border-radius: 22px;
            padding: 24px; margin-bottom: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.09);
            text-align: center;
        }
        .status-icon-wrap {
            width: 80px; height: 80px; border-radius: 50%;
            background: var(--status-bg);
            display: flex; align-items: center; justify-content: center;
            font-size: 36px; margin: 0 auto 14px;
            border: 3px solid var(--status-color);
        }
        .status-title {
            font-size: 20px; font-weight: 800;
            color: var(--status-color); margin-bottom: 6px;
        }
        .status-msg { font-size: 13px; color: var(--muted); line-height: 1.5; }

        /* Progress Bar */
        .progress-wrap { margin: 20px 0 0; }
        .progress-steps {
            display: flex; align-items: center; justify-content: space-between;
            position: relative;
        }
        .progress-line {
            position: absolute; top: 17px; left: 18px; right: 18px;
            height: 3px; background: #F0EDE8; z-index: 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border-radius: 3px;
            transition: width 0.5s ease;
            width: <?= min(100, ($stepIndex / (count($steps)-1)) * 100) ?>%;
        }
        .step {
            display: flex; flex-direction: column;
            align-items: center; z-index: 1; flex: 1;
        }
        .step-dot {
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; transition: all 0.3s;
            border: 3px solid #F0EDE8; background: white; color: #CCC;
            margin-bottom: 6px;
        }
        .step.done .step-dot {
            background: var(--primary); border-color: var(--primary);
            color: white;
        }
        .step.active .step-dot {
            background: var(--status-color); border-color: var(--status-color);
            color: white; transform: scale(1.2);
            box-shadow: 0 0 0 4px <?= $cfg['bg'] ?>;
        }
        .step-label {
            font-size: 10px; font-weight: 700; color: var(--muted);
            text-align: center; line-height: 1.2;
        }
        .step.done .step-label, .step.active .step-label { color: var(--dark); }

        /* Countdown */
        .countdown-card {
            background: white; border-radius: 18px;
            padding: 18px 20px; margin-bottom: 14px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
            display: flex; align-items: center; gap: 14px;
        }
        .countdown-icon { font-size: 28px; flex-shrink: 0; }
        .countdown-label { font-size: 12px; color: var(--muted); font-weight: 600; }
        .countdown-time { font-size: 26px; font-weight: 800; color: var(--primary); }
        .countdown-note { font-size: 11px; color: var(--muted); }

        /* Order Summary */
        .summary-card {
            background: white; border-radius: 18px;
            padding: 18px; margin-bottom: 14px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        }
        .card-title {
            font-size: 12px; font-weight: 800; color: var(--dark);
            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px;
        }
        .item-row {
            display: flex; justify-content: space-between;
            align-items: flex-start; padding: 10px 0;
            border-bottom: 1px dashed var(--border);
        }
        .item-row:last-child { border-bottom: none; }
        .item-left { flex: 1; }
        .item-name { font-weight: 700; font-size: 13px; margin-bottom: 3px; }
        .item-tags { display: flex; flex-wrap: wrap; gap: 4px; }
        .tag {
            padding: 2px 7px; border-radius: 5px;
            font-size: 10px; font-weight: 600;
        }
        .tag-spice0 { background:#E8F5E9; color:#2E7D32; }
        .tag-spice1 { background:#FFF3E0; color:#E65100; }
        .tag-spice2 { background:#FFEBEE; color:#B71C1C; }
        .tag-note   { background:#EDE7F6; color:#4527A0; }
        .item-price { font-weight: 700; font-size: 13px; color: var(--primary); white-space: nowrap; margin-left: 10px; }

        .total-row {
            display: flex; justify-content: space-between;
            padding: 14px 0 0; margin-top: 4px;
            font-weight: 800; font-size: 15px;
        }
        .total-val { color: var(--primary); }

        /* Bottom Button */
        .bottom-btn {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: white; padding: 16px 20px 28px;
            border-radius: 28px 28px 0 0;
            box-shadow: 0 -6px 24px rgba(0,0,0,0.09);
        }
        .btn-new {
            display: block; width: 100%; text-align: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; text-decoration: none;
            padding: 17px; border-radius: 17px;
            font-size: 15px; font-weight: 700; letter-spacing: 0.3px;
        }
        .btn-new:active { opacity: 0.9; }

        /* Refresh indicator */
        .refresh-note {
            text-align: center; font-size: 11px;
            color: var(--muted); padding: 10px 0;
        }
        .pulse { animation: pulse 2s infinite; }
        @keyframes pulse {
            0%,100%{opacity:1} 50%{opacity:0.4}
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Status Pesanan Anda</h2>
    <p><?= htmlspecialchars($p['nama_pelanggan']) ?> — Meja <?= $p['nomor_meja'] ?></p>
    <div class="order-num">Pesanan #<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?></div>
</div>

<div class="content">

    <!-- STATUS CARD -->
    <div class="status-card">
        <div class="status-icon-wrap"><?= $cfg['icon'] ?></div>
        <div class="status-title"><?= $status ?></div>
        <div class="status-msg"><?= $cfg['msg'] ?></div>

        <!-- Progress Steps -->
        <div class="progress-wrap">
            <div class="progress-steps">
                <div class="progress-line">
                    <div class="progress-fill"></div>
                </div>
                <?php foreach ($steps as $i => $s): 
                    $cls = '';
                    if ($i < $stepIndex) $cls = 'done';
                    elseif ($i === $stepIndex) $cls = 'active';
                    $icons = ['⏳','👨‍🍳','🔔','✅'];
                ?>
                <div class="step <?= $cls ?>">
                    <div class="step-dot"><?= $icons[$i] ?></div>
                    <div class="step-label"><?= $s ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- COUNTDOWN TIMER -->
    <?php if ($status !== 'Selesai'): ?>
    <div class="countdown-card">
        <div class="countdown-icon">⏱️</div>
        <div>
            <div class="countdown-label">Estimasi Waktu Tunggu</div>
            <div class="countdown-time" id="timerDisplay">--:--</div>
            <div class="countdown-note">~<?= $p['estimasi_menit'] ?> menit total dari pemesanan</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ORDER SUMMARY -->
    <div class="summary-card">
        <div class="card-title">🧾 Rincian Pesanan</div>

        <?php foreach ($items as $item):
            $spiceCls = 'tag-spice0';
            if (($item['pedas'] ?? '') === 'Pedas') $spiceCls = 'tag-spice1';
            elseif (($item['pedas'] ?? '') === 'Ekstra Pedas') $spiceCls = 'tag-spice2';
        ?>
        <div class="item-row">
            <div class="item-left">
                <div class="item-name"><?= htmlspecialchars($item['nama']) ?> <span style="color:#8E8E93">x<?= $item['qty'] ?></span></div>
                <div class="item-tags">
                    <?php if($item['kategori'] === 'Makanan' && !empty($item['pedas']) && $item['pedas'] !== '-'): ?>
                    <span class="tag <?= $spiceCls ?>">
                        <?= $item['pedas'] === 'Tidak Pedas' ? '🌿' : ($item['pedas'] === 'Pedas' ? '🌶️' : '🔥') ?>
                        <?= htmlspecialchars($item['pedas']) ?>
                    </span>
                    <?php endif; ?>
                    <?php if(!empty($item['catatan'])): ?>
                    <span class="tag tag-note">📝 <?= htmlspecialchars($item['catatan']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="item-price">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></div>
        </div>
        <?php endforeach; ?>

        <div class="total-row">
            <span>Total</span>
            <span class="total-val">Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></span>
        </div>
        <div style="display:flex;gap:10px;margin-top:10px;font-size:12px;color:var(--muted);">
            <span><?= $p['jenis_pesanan'] ?></span>
            <span>·</span>
            <span><?= $p['metode_bayar'] ?></span>
            <span>·</span>
            <span style="color:<?= $p['status_bayar'] === 'Sudah Bayar' ? '#34C759' : '#E8603C' ?>;font-weight:700">
                <?= $p['status_bayar'] ?>
            </span>
        </div>
    </div>

    <div class="refresh-note">
        <span class="pulse">●</span> Halaman diperbarui otomatis setiap 5 detik
    </div>

</div>

<!-- BOTTOM BUTTON -->
<div class="bottom-btn">
    <a href="index.php" class="btn-new">+ Buat Pesanan Baru</a>
</div>

<script>
// Countdown timer
let sisa = <?= $sisa_detik ?>;

function updateTimer() {
    const el = document.getElementById('timerDisplay');
    if (!el) return;
    if (sisa <= 0) {
        el.innerText = 'Segera siap';
        el.style.color = '#34C759';
        return;
    }
    const m = Math.floor(sisa / 60);
    const s = sisa % 60;
    el.innerText = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    sisa--;
}
updateTimer();
setInterval(updateTimer, 1000);

// Auto-refresh page every 5 seconds if not selesai
<?php if ($status !== 'Selesai'): ?>
setTimeout(() => location.reload(), 5000);
<?php endif; ?>
</script>
</body>
</html>
