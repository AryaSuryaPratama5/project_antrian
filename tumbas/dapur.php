<?php
include 'koneksi.php';
include 'auth.php';
requireLogin(['kasir', 'dapur', 'admin']);

// Update status dari dapur
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $st = htmlspecialchars($_GET['status']);
    $allowed = ['Diproses', 'Siap', 'Selesai'];
    if (in_array($st, $allowed)) {
        $selesai = ($st === 'Selesai') ? ', waktu_selesai=NOW()' : '';
        mysqli_query($conn, "UPDATE pesanan SET status_pelayanan='$st' $selesai WHERE id_pesanan=$id");
    }
    header("Location: dapur.php"); exit;
}

// Hanya tampilkan yang aktif (Menunggu + Diproses + Siap)
$orders = mysqli_query($conn, "
    SELECT * FROM pesanan 
    WHERE status_pelayanan IN ('Menunggu','Diproses','Siap')
    ORDER BY 
        FIELD(status_pelayanan,'Menunggu','Diproses','Siap'),
        waktu_pesan ASC
");

$total_aktif = mysqli_num_rows($orders);
mysqli_data_seek($orders, 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Dapur — Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark: #1C1C1E; --surface: #0F0F12;
            --card: #1C1C24; --border: #2A2A35;
            --primary: #FF6B3D; --secondary: #F5A623;
            --menunggu: #FF9500; --diproses: #007AFF; --siap: #BF5AF2;
        }
        * { box-sizing: border-box; margin:0; padding:0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface); color: #EBEBF5;
            min-height: 100vh;
        }

        .topbar {
            background: var(--card); padding: 14px 20px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 10;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .logo-icon {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center; font-size: 20px;
        }
        .topbar-title { font-weight: 800; font-size: 16px; }
        .topbar-sub { font-size: 11px; color: #8E8E93; }
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .active-count {
            background: var(--primary); color: white;
            padding: 6px 14px; border-radius: 20px;
            font-size: 13px; font-weight: 700;
        }
        .nav-link {
            color: #8E8E93; text-decoration: none;
            font-size: 13px; font-weight: 600;
            padding: 8px 12px; border-radius: 10px;
            transition: background 0.2s;
        }
        .nav-link:hover { background: var(--border); color: white; }
        .refresh-badge {
            font-size: 12px; color: #8E8E93;
            padding: 4px 8px; border-radius: 8px;
            background: var(--border);
        }

        .main { padding: 16px; }

        /* Legend */
        .legend {
            display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;
        }
        .leg-item {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px; color: #8E8E93; font-weight: 600;
        }
        .leg-dot {
            width: 10px; height: 10px; border-radius: 50%;
        }

        /* Grid */
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 14px;
        }
        @media(max-width:600px) { .orders-grid { grid-template-columns: 1fr; } }

        /* Order Card */
        .order-card {
            background: var(--card);
            border-radius: 18px;
            border: 2px solid var(--border);
            overflow: hidden;
            transition: transform 0.2s;
        }
        .order-card:hover { transform: translateY(-2px); }
        .order-card.menunggu { border-top: 4px solid var(--menunggu); }
        .order-card.diproses { border-top: 4px solid var(--diproses); }
        .order-card.siap     { border-top: 4px solid var(--siap); }

        .card-header {
            padding: 14px 16px;
            background: rgba(255,255,255,0.03);
            display: flex; justify-content: space-between; align-items: flex-start;
        }
        .order-num { font-size: 11px; color: #8E8E93; font-weight: 700; margin-bottom: 3px; }
        .order-name { font-weight: 800; font-size: 17px; }
        .order-meta { font-size: 12px; color: #8E8E93; margin-top: 2px; }
        .status-badge {
            padding: 5px 12px; border-radius: 8px;
            font-size: 11px; font-weight: 700; white-space: nowrap;
        }
        .badge-menunggu { background: rgba(255,149,0,0.15); color: var(--menunggu); border: 1px solid rgba(255,149,0,0.3); }
        .badge-diproses { background: rgba(0,122,255,0.15); color: var(--diproses); border: 1px solid rgba(0,122,255,0.3); }
        .badge-siap     { background: rgba(191,90,242,0.15); color: var(--siap);     border: 1px solid rgba(191,90,242,0.3); }

        .card-body { padding: 14px 16px; }

        /* Timer */
        .timer {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 14px; padding: 10px 12px;
            background: rgba(255,255,255,0.04);
            border-radius: 10px;
        }
        .timer-icon { font-size: 16px; }
        .timer-label { font-size: 11px; color: #8E8E93; }
        .timer-val { font-size: 16px; font-weight: 800; color: white; }

        /* Items */
        .item-list { margin-bottom: 14px; }
        .item-row {
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
        }
        .item-row:last-child { border-bottom: none; }
        .item-top { display: flex; align-items: center; gap: 8px; }
        .item-qty {
            background: var(--primary); color: white;
            width: 26px; height: 26px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 800; flex-shrink: 0;
        }
        .item-name { font-weight: 700; font-size: 15px; }
        .item-tags { display: flex; gap: 5px; margin-top: 5px; flex-wrap: wrap; }
        .tag {
            padding: 3px 8px; border-radius: 5px;
            font-size: 11px; font-weight: 600;
        }
        .tag-spice0 { background: rgba(76,175,80,0.15); color: #4CAF50; }
        .tag-spice1 { background: rgba(255,152,0,0.15); color: #FF9800; }
        .tag-spice2 { background: rgba(244,67,54,0.15); color: #F44336; }
        .tag-note   { background: rgba(156,39,176,0.15); color: #CE93D8; }

        /* Action buttons */
        .card-footer { padding: 12px 16px; display: flex; gap: 8px; }
        .btn-act {
            flex: 1; padding: 11px;
            border-radius: 12px; border: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; font-weight: 700;
            cursor: pointer; text-align: center;
            text-decoration: none; display: block;
            transition: all 0.2s;
        }
        .btn-proses { background: var(--diproses); color: white; }
        .btn-siap   { background: var(--siap);     color: white; }
        .btn-selesai{ background: rgba(52,199,89,0.15); color: #34C759; border: 1px solid #34C759; }
        .btn-act:active { transform: scale(0.96); }

        /* Empty State */
        .empty-state {
            grid-column: 1/-1; text-align: center;
            padding: 60px 20px; color: #8E8E93;
        }
        .empty-icon { font-size: 60px; margin-bottom: 16px; }
        .empty-title { font-size: 20px; font-weight: 800; color: white; margin-bottom: 8px; }
        .empty-sub { font-size: 14px; }

        /* Refresh countdown */
        .refresh-ring {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: white;
            background: conic-gradient(var(--primary) 0%, transparent 0%);
            position: relative;
        }
        .refresh-ring-num {
            position: absolute; font-size: 11px; font-weight: 800;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <div class="logo-icon">👨‍🍳</div>
        <div>
            <div class="topbar-title">Panel Dapur</div>
            <div class="topbar-sub">Rumah Makan A</div>
        </div>
    </div>
    <div class="topbar-right">
        <span class="active-count"><?= $total_aktif ?> Aktif</span>
        <span class="refresh-badge" id="refreshBadge">⟳ 10s</span>
        <a href="halaman_kasir.php" class="nav-link">Kasir</a>
        <a href="logout.php" class="nav-link">Keluar</a>
    </div>
</div>

<div class="main">
    <!-- Legend -->
    <div class="legend">
        <div class="leg-item"><div class="leg-dot" style="background:var(--menunggu)"></div> Menunggu</div>
        <div class="leg-item"><div class="leg-dot" style="background:var(--diproses)"></div> Sedang Diproses</div>
        <div class="leg-item"><div class="leg-dot" style="background:var(--siap)"></div> Siap Disajikan</div>
    </div>

    <div class="orders-grid">
    <?php if ($total_aktif === 0): ?>
        <div class="empty-state">
            <div class="empty-icon">😴</div>
            <div class="empty-title">Tidak Ada Pesanan Aktif</div>
            <div class="empty-sub">Semua pesanan sudah diselesaikan</div>
        </div>
    <?php else:
        while ($r = mysqli_fetch_assoc($orders)):
            $sp    = $r['status_pelayanan'];
            $items = json_decode($r['detail_json'] ?? '[]', true) ?: [];
            $menit_lalu = round((time() - strtotime($r['waktu_pesan'])) / 60);
            $estimasi   = $r['estimasi_menit'];
            $sisa       = max(0, $estimasi - $menit_lalu);
            $over_time  = ($menit_lalu > $estimasi);

            $nextStatus = ['Menunggu' => 'Diproses', 'Diproses' => 'Siap', 'Siap' => 'Selesai'];
            $next = $nextStatus[$sp] ?? null;
    ?>
        <div class="order-card <?= strtolower($sp) ?>">
            <div class="card-header">
                <div>
                    <div class="order-num">#<?= str_pad($r['id_pesanan'],4,'0',STR_PAD_LEFT) ?> · <?= date('H:i', strtotime($r['waktu_pesan'])) ?></div>
                    <div class="order-name"><?= htmlspecialchars($r['nama_pelanggan']) ?></div>
                    <div class="order-meta">Meja <?= $r['nomor_meja'] ?> · <?= $r['jenis_pesanan'] === 'Bawa Pulang' ? '🛍️ Bawa Pulang' : '🏠 Makan di Sini' ?></div>
                </div>
                <span class="status-badge badge-<?= strtolower($sp) ?>"><?= $sp ?></span>
            </div>

            <div class="card-body">
                <!-- Timer -->
                <div class="timer">
                    <span class="timer-icon"><?= $over_time ? '⚠️' : '⏱️' ?></span>
                    <div>
                        <div class="timer-label"><?= $over_time ? 'Melebihi estimasi' : 'Sisa waktu' ?></div>
                        <div class="timer-val" style="color:<?= $over_time ? '#FF3B30' : 'white' ?>"
                             data-pesan="<?= strtotime($r['waktu_pesan']) ?>"
                             data-estimasi="<?= $estimasi ?>">
                            <?= $over_time ? '+'.($menit_lalu - $estimasi).' mnt' : $sisa.' mnt' ?>
                        </div>
                    </div>
                    <div style="margin-left:auto;font-size:11px;color:#8E8E93;"><?= $menit_lalu ?> mnt lalu</div>
                </div>

                <!-- Items -->
                <div class="item-list">
                    <?php foreach($items as $it):
                        $spiceCls = 'tag-spice0';
                        $spiceEmoji = '';
                        if(($it['pedas']??'') === 'Pedas') { $spiceCls='tag-spice1'; $spiceEmoji='🌶️ '; }
                        elseif(($it['pedas']??'') === 'Ekstra Pedas') { $spiceCls='tag-spice2'; $spiceEmoji='🔥 '; }
                    ?>
                    <div class="item-row">
                        <div class="item-top">
                            <div class="item-qty"><?= $it['qty'] ?></div>
                            <div class="item-name"><?= htmlspecialchars($it['nama']) ?></div>
                        </div>
                        <div class="item-tags">
                            <?php if(!empty($it['pedas']) && $it['pedas'] !== '-' && $it['pedas'] !== 'Tidak Pedas'): ?>
                            <span class="tag <?= $spiceCls ?>"><?= $spiceEmoji . htmlspecialchars($it['pedas']) ?></span>
                            <?php endif; ?>
                            <?php if(!empty($it['catatan'])): ?>
                            <span class="tag tag-note">📝 <?= htmlspecialchars($it['catatan']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card-footer">
                <?php if($sp === 'Menunggu'): ?>
                    <a href="?status=Diproses&id=<?= $r['id_pesanan'] ?>" class="btn-act btn-proses">👨‍🍳 Mulai Proses</a>
                <?php elseif($sp === 'Diproses'): ?>
                    <a href="?status=Siap&id=<?= $r['id_pesanan'] ?>" class="btn-act btn-siap">🔔 Tandai Siap</a>
                <?php elseif($sp === 'Siap'): ?>
                    <a href="?status=Selesai&id=<?= $r['id_pesanan'] ?>" class="btn-act btn-selesai">✅ Selesai Disajikan</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; endif; ?>
    </div>
</div>

<script>
// Auto-refresh countdown
let countdown = 10;
function tick() {
    countdown--;
    document.getElementById('refreshBadge').innerText = `⟳ ${countdown}s`;
    if (countdown <= 0) { location.reload(); }
}
setInterval(tick, 1000);

// Live timer updates
function updateTimers() {
    document.querySelectorAll('[data-pesan]').forEach(el => {
        const pesan = parseInt(el.dataset.pesan);
        const est   = parseInt(el.dataset.estimasi);
        const now   = Math.floor(Date.now() / 1000);
        const menit_lalu = Math.round((now - pesan) / 60);
        const sisa = est - menit_lalu;
        
        if (sisa < 0) {
            el.innerText = '+' + Math.abs(sisa) + ' mnt';
            el.style.color = '#FF3B30';
        } else {
            el.innerText = sisa + ' mnt';
            el.style.color = 'white';
        }
    });
}
setInterval(updateTimers, 30000);
</script>
</body>
</html>
