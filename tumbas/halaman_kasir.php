<?php
include 'koneksi.php';
include 'auth.php';
requireLogin(['kasir', 'admin']);

// Handle actions
if (isset($_GET['bayar'])) {
    $id = (int)$_GET['id'];
    $st = ($_GET['bayar'] === 'Belum Bayar') ? 'Sudah Bayar' : 'Belum Bayar';
    mysqli_query($conn, "UPDATE pesanan SET status_bayar='$st' WHERE id_pesanan=$id");
    header("Location: halaman_kasir.php"); exit;
}
if (isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $st = htmlspecialchars($_GET['status']);
    $selesaiTime = ($st === 'Selesai') ? ", waktu_selesai=NOW()" : '';
    mysqli_query($conn, "UPDATE pesanan SET status_pelayanan='$st' $selesaiTime WHERE id_pesanan=$id");
    header("Location: halaman_kasir.php"); exit;
}
if (isset($_GET['stok'])) {
    $id = (int)$_GET['id'];
    $st = ($_GET['stok'] === 'Tersedia') ? 'Habis' : 'Tersedia';
    mysqli_query($conn, "UPDATE menu SET status_tersedia='$st' WHERE id_menu=$id");
    header("Location: halaman_kasir.php"); exit;
}
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['id'];
    mysqli_query($conn, "DELETE FROM pesanan WHERE id_pesanan=$id");
    header("Location: halaman_kasir.php"); exit;
}

// Stats
$stat = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status_pelayanan='Menunggu' THEN 1 ELSE 0 END) as menunggu,
        SUM(CASE WHEN status_pelayanan='Diproses' THEN 1 ELSE 0 END) as diproses,
        SUM(CASE WHEN status_bayar='Belum Bayar' THEN 1 ELSE 0 END) as belum_bayar,
        SUM(CASE WHEN DATE(waktu_pesan)=CURDATE() THEN total_harga ELSE 0 END) as pendapatan_hari
    FROM pesanan
"));

$max_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(id_pesanan) as m FROM pesanan"))['m'] ?? 0;

// Filter params
$filter_status = $_GET['fs'] ?? '';
$filter_bayar  = $_GET['fb'] ?? '';
$search        = $_GET['q']  ?? '';

$where = "WHERE 1=1";
if ($filter_status) $where .= " AND status_pelayanan = '" . mysqli_real_escape_string($conn, $filter_status) . "'";
if ($filter_bayar)  $where .= " AND status_bayar = '"     . mysqli_real_escape_string($conn, $filter_bayar)  . "'";
if ($search) {
    $sq = mysqli_real_escape_string($conn, $search);
    $where .= " AND (nama_pelanggan LIKE '%$sq%' OR nomor_meja LIKE '%$sq%' OR detail_pesanan LIKE '%$sq%')";
}

$orders = mysqli_query($conn, "SELECT * FROM pesanan $where ORDER BY waktu_pesan DESC");
$menus  = mysqli_query($conn, "SELECT * FROM menu ORDER BY kategori, nama_item");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kasir — Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #E8603C; --secondary: #F5A623;
            --dark: #1C1C1E; --text: #3A3A3C; --muted: #8E8E93;
            --surface: #F8F6F3; --card: #FFFFFF; --border: #EBEBEB;
            --success: #34C759; --danger: #FF3B30; --blue: #007AFF;
            --warning: #FF9500;
        }
        * { box-sizing: border-box; margin:0; padding:0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--surface); color: var(--text); min-height:100vh; }

        /* Sidebar/Header */
        .topbar {
            background: white; padding: 16px 20px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06); position: sticky; top:0; z-index:100;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-logo {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center; font-size: 20px;
        }
        .topbar-title { font-weight: 800; font-size: 16px; color: var(--dark); }
        .topbar-sub { font-size: 11px; color: var(--muted); }
        .topbar-nav { display: flex; gap: 8px; align-items: center; }
        .nav-btn {
            padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600;
            text-decoration: none; color: var(--muted); transition: all 0.2s;
            border: 1.5px solid transparent;
        }
        .nav-btn:hover, .nav-btn.active { background: var(--surface); color: var(--dark); border-color: var(--border); }
        .nav-btn.logout { color: var(--danger); }
        .notif-bell {
            position: relative; cursor: pointer; font-size: 20px;
            padding: 8px; border-radius: 10px;
            transition: background 0.2s;
        }
        .notif-bell:hover { background: var(--surface); }
        .notif-dot {
            position: absolute; top: 4px; right: 4px;
            width: 10px; height: 10px; background: var(--danger);
            border-radius: 50%; border: 2px solid white; display: none;
        }

        .main { padding: 20px; max-width: 1200px; margin: auto; }

        /* Stats Row */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
        @media(max-width:768px) { .stats { grid-template-columns: 1fr 1fr; } }
        .stat-card {
            background: white; border-radius: 16px; padding: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        }
        .stat-icon { font-size: 22px; margin-bottom: 8px; }
        .stat-val { font-size: 24px; font-weight: 800; color: var(--dark); margin-bottom: 2px; }
        .stat-label { font-size: 11px; color: var(--muted); font-weight: 600; }

        /* Section Card */
        .section-card {
            background: white; border-radius: 18px;
            padding: 20px; margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px; flex-wrap: wrap; gap: 10px;
        }
        .section-title { font-size: 15px; font-weight: 800; color: var(--dark); }

        /* Filter & Search */
        .filter-bar {
            display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px;
        }
        .filter-bar form { display: flex; gap: 8px; flex-wrap: wrap; width: 100%; }
        .search-field {
            flex: 1; min-width: 180px;
            padding: 10px 14px 10px 36px;
            border: 2px solid var(--border); border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; outline: none; background: var(--surface);
            position: relative;
        }
        .search-wrap { position: relative; flex: 1; }
        .search-wrap::before {
            content: '🔍'; position: absolute; left: 11px; top: 50%;
            transform: translateY(-50%); font-size: 14px;
        }
        .search-wrap input {
            width: 100%; padding: 10px 14px 10px 36px;
            border: 2px solid var(--border); border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; outline: none; background: var(--surface);
        }
        .search-wrap input:focus { border-color: var(--primary); background: white; }
        .f-select {
            padding: 10px 14px; border: 2px solid var(--border); border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px;
            background: var(--surface); outline: none; cursor: pointer;
        }
        .f-select:focus { border-color: var(--primary); }
        .btn-filter {
            padding: 10px 16px; background: var(--primary); color: white;
            border: none; border-radius: 12px; font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; font-weight: 600; cursor: pointer;
        }
        .btn-reset {
            padding: 10px 14px; background: var(--surface); color: var(--muted);
            border: 2px solid var(--border); border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px;
            font-weight: 600; cursor: pointer; text-decoration: none;
        }

        /* Order Cards */
        .order-card {
            border: 2px solid var(--border); border-radius: 16px;
            margin-bottom: 12px; overflow: hidden; transition: border-color 0.2s;
        }
        .order-card:hover { border-color: var(--primary); }
        .order-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 16px; background: var(--surface);
            flex-wrap: wrap; gap: 8px;
        }
        .order-id { font-size: 11px; color: var(--muted); font-weight: 700; }
        .order-customer { font-weight: 800; font-size: 14px; }
        .order-meta { font-size: 12px; color: var(--muted); }
        .badges { display: flex; gap: 6px; flex-wrap: wrap; }
        .badge {
            padding: 4px 10px; border-radius: 8px;
            font-size: 11px; font-weight: 700; white-space: nowrap;
        }
        .badge-menunggu  { background:#FFF8E1; color:#E65100; }
        .badge-diproses  { background:#E3F2FD; color:#1565C0; }
        .badge-siap      { background:#F3E5F5; color:#6A1B9A; }
        .badge-selesai   { background:#E8F5E9; color:#1B5E20; }
        .badge-lunas     { background:#E8F5E9; color:#1B5E20; }
        .badge-belum     { background:#FFEBEE; color:#B71C1C; }
        .badge-jenis     { background:#E3F2FD; color:#0D47A1; }

        .order-body { padding: 12px 16px; }
        .order-detail { font-size: 13px; color: var(--text); margin-bottom: 12px; line-height: 1.5; }

        /* Action Buttons */
        .action-row { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        .btn-action {
            padding: 8px 14px; border-radius: 10px; border: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12px; font-weight: 700; cursor: pointer;
            text-decoration: none; display: inline-block;
            transition: all 0.2s; white-space: nowrap;
        }
        .btn-status   { background: var(--blue);    color: white; }
        .btn-bayar    { background: var(--success);  color: white; }
        .btn-bayar-un { background: var(--surface);  color: var(--muted); border: 2px solid var(--border); }
        .btn-print    { background: var(--dark);     color: white; }
        .btn-hapus    { background: #FFEBEE; color: var(--danger); }
        .btn-action:hover { filter: brightness(0.92); }
        .btn-action:active { transform: scale(0.96); }

        /* Status select inline */
        .status-select {
            padding: 8px 12px; border-radius: 10px;
            border: 2px solid var(--border); background: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12px; font-weight: 600; cursor: pointer; outline: none;
        }

        /* Stock Menu */
        .stock-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        @media(max-width:500px) { .stock-grid { grid-template-columns: 1fr; } }
        .stock-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 14px; border: 2px solid var(--border);
            border-radius: 12px; background: var(--surface);
        }
        .stock-name { font-size: 13px; font-weight: 600; }
        .stock-cat  { font-size: 10px; color: var(--muted); }
        .btn-stok-tersedia { background: #E8F5E9; color: #1B5E20; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer; text-decoration: none; }
        .btn-stok-habis    { background: #FFEBEE; color: var(--danger); border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer; text-decoration: none; }

        /* Nav tabs */
        .tabs { display: flex; gap: 4px; background: var(--surface); border-radius: 12px; padding: 4px; margin-bottom: 20px; }
        .tab {
            flex: 1; text-align: center; padding: 10px 8px;
            border-radius: 9px; font-size: 12px; font-weight: 700;
            cursor: pointer; color: var(--muted); transition: all 0.2s;
            border: none; background: transparent; font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .tab.active { background: white; color: var(--dark); box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Notif Toast */
        .notif-toast {
            position: fixed; top: 80px; right: 20px;
            background: var(--dark); color: white;
            padding: 14px 18px; border-radius: 14px;
            font-size: 14px; font-weight: 600;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            z-index: 9999; transform: translateX(150%);
            transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1);
            max-width: 280px;
        }
        .notif-toast.show { transform: translateX(0); }

        /* Print styles */
        @media print {
            body { background: white; }
            .topbar, .tabs, .filter-bar, .action-row, .stats, .section-card:not(.print-target) { display: none !important; }
            .print-target { box-shadow: none !important; border: none !important; }
        }

        @media(max-width:600px) {
            .stats { grid-template-columns: 1fr 1fr; }
            .topbar-nav .nav-btn:not(.logout) { display: none; }
        }
    </style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
        <div class="topbar-logo">🍽️</div>
        <div>
            <div class="topbar-title">Panel Kasir</div>
            <div class="topbar-sub">Rumah Makan A — <?= htmlspecialchars(userName()) ?></div>
        </div>
    </div>
    <div class="topbar-nav">
        <a href="dapur.php" class="nav-btn">👨‍🍳 Dapur</a>
        <a href="laporan.php" class="nav-btn">📊 Laporan</a>
        <div class="notif-bell" id="notifBell" title="Notifikasi pesanan baru">
            🔔 <span class="notif-dot" id="notifDot"></span>
        </div>
        <a href="logout.php" class="nav-btn logout">Keluar</a>
    </div>
</div>

<div class="main">

    <!-- STATS -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-val"><?= $stat['total'] ?></div>
            <div class="stat-label">Total Pesanan</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-val" style="color:var(--warning)"><?= $stat['menunggu'] + $stat['diproses'] ?></div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💳</div>
            <div class="stat-val" style="color:var(--danger)"><?= $stat['belum_bayar'] ?></div>
            <div class="stat-label">Belum Bayar</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-val" style="font-size:16px;color:var(--success)">Rp<?= number_format($stat['pendapatan_hari'], 0, ',', '.') ?></div>
            <div class="stat-label">Pendapatan Hari Ini</div>
        </div>
    </div>

    <!-- TABS -->
    <div class="tabs">
        <button class="tab active" onclick="switchTab('pesanan', this)">📋 Pesanan</button>
        <button class="tab" onclick="switchTab('stok', this)">🏪 Stok Menu</button>
    </div>

    <!-- TAB: PESANAN -->
    <div id="tab-pesanan" class="tab-content active">
        <div class="section-card">
            <div class="section-header">
                <div class="section-title">Daftar Pesanan</div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;width:100%;">
                    <div class="search-wrap" style="min-width:160px;flex:1;">
                        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama, meja...">
                    </div>
                    <select name="fs" class="f-select">
                        <option value="">Semua Status</option>
                        <option value="Menunggu"  <?= $filter_status==='Menunggu'  ?'selected':'' ?>>⏳ Menunggu</option>
                        <option value="Diproses"  <?= $filter_status==='Diproses'  ?'selected':'' ?>>👨‍🍳 Diproses</option>
                        <option value="Siap"      <?= $filter_status==='Siap'      ?'selected':'' ?>>🔔 Siap</option>
                        <option value="Selesai"   <?= $filter_status==='Selesai'   ?'selected':'' ?>>✅ Selesai</option>
                    </select>
                    <select name="fb" class="f-select">
                        <option value="">Semua Bayar</option>
                        <option value="Belum Bayar" <?= $filter_bayar==='Belum Bayar'?'selected':'' ?>>❌ Belum Bayar</option>
                        <option value="Sudah Bayar" <?= $filter_bayar==='Sudah Bayar'?'selected':'' ?>>✅ Sudah Bayar</option>
                    </select>
                    <button type="submit" class="btn-action btn-status">Cari</button>
                    <a href="halaman_kasir.php" class="btn-reset">Reset</a>
                </form>
            </div>

            <!-- Orders -->
            <?php
            $statusNext = [
                'Menunggu' => 'Diproses',
                'Diproses' => 'Siap',
                'Siap'     => 'Selesai',
                'Selesai'  => null,
            ];
            $statusLabel = [
                'Menunggu' => ['cls'=>'badge-menunggu', 'label'=>'⏳ Menunggu'],
                'Diproses' => ['cls'=>'badge-diproses', 'label'=>'👨‍🍳 Diproses'],
                'Siap'     => ['cls'=>'badge-siap',     'label'=>'🔔 Siap'],
                'Selesai'  => ['cls'=>'badge-selesai',  'label'=>'✅ Selesai'],
            ];
            $count = 0;
            while ($r = mysqli_fetch_assoc($orders)): $count++;
                $sp   = $r['status_pelayanan'];
                $sb   = $r['status_bayar'];
                $next = $statusNext[$sp] ?? null;
                $items = json_decode($r['detail_json'] ?? '[]', true) ?: [];
            ?>
            <div class="order-card" id="order_<?= $r['id_pesanan'] ?>">
                <div class="order-header">
                    <div>
                        <div class="order-id">#<?= str_pad($r['id_pesanan'],4,'0',STR_PAD_LEFT) ?> · <?= date('H:i, d/m', strtotime($r['waktu_pesan'])) ?></div>
                        <div class="order-customer"><?= htmlspecialchars($r['nama_pelanggan']) ?> — Meja <?= $r['nomor_meja'] ?></div>
                    </div>
                    <div class="badges">
                        <span class="badge <?= $statusLabel[$sp]['cls'] ?>"><?= $statusLabel[$sp]['label'] ?></span>
                        <span class="badge badge-jenis"><?= $r['jenis_pesanan'] === 'Bawa Pulang' ? '🛍️' : '🏠' ?> <?= $r['jenis_pesanan'] ?></span>
                        <span class="badge <?= $sb==='Sudah Bayar' ? 'badge-lunas' : 'badge-belum' ?>">
                            <?= $sb==='Sudah Bayar' ? '💰 Lunas' : '❌ Belum Bayar' ?>
                        </span>
                    </div>
                </div>
                <div class="order-body">
                    <div class="order-detail">
                        <?php foreach($items as $it): ?>
                        <div style="padding:3px 0">
                            · <?= htmlspecialchars($it['nama']) ?> x<?= $it['qty'] ?>
                            <?php if(!empty($it['pedas']) && $it['pedas'] !== '-'): ?>
                            <small style="background:#fff3e0;color:#e65100;padding:1px 5px;border-radius:4px;font-size:10px;">
                                <?= htmlspecialchars($it['pedas']) ?>
                            </small>
                            <?php endif; ?>
                            <?php if(!empty($it['catatan'])): ?>
                            <small style="background:#ede7f6;color:#4527a0;padding:1px 5px;border-radius:4px;font-size:10px;">
                                📝 <?= htmlspecialchars($it['catatan']) ?>
                            </small>
                            <?php endif; ?>
                            <span style="color:var(--muted);font-size:12px"> — Rp<?= number_format($it['subtotal'],0,',','.') ?></span>
                        </div>
                        <?php endforeach; ?>
                        <div style="font-weight:800;margin-top:8px;color:var(--primary);">
                            Total: Rp<?= number_format($r['total_harga'],0,',','.') ?>
                            <span style="font-weight:600;color:var(--muted);font-size:12px"> · <?= $r['metode_bayar'] ?></span>
                        </div>
                    </div>

                    <div class="action-row">
                        <!-- Update Status -->
                        <?php if($next): ?>
                        <a href="?status=<?= urlencode($next) ?>&id=<?= $r['id_pesanan'] ?>" class="btn-action btn-status">
                            → <?= $next ?>
                        </a>
                        <?php endif; ?>

                        <!-- Toggle Bayar -->
                        <?php if($sb === 'Belum Bayar'): ?>
                        <a href="?bayar=<?= urlencode($sb) ?>&id=<?= $r['id_pesanan'] ?>" class="btn-action btn-bayar">
                            💰 Tandai Lunas
                        </a>
                        <?php else: ?>
                        <a href="?bayar=<?= urlencode($sb) ?>&id=<?= $r['id_pesanan'] ?>" class="btn-action btn-bayar-un">
                            Batal Lunas
                        </a>
                        <?php endif; ?>

                        <!-- Print -->
                        <button onclick="printStruk(<?= $r['id_pesanan'] ?>, '<?= addslashes($r['nama_pelanggan']) ?>', <?= $r['nomor_meja'] ?>, '<?= addslashes($r['jenis_pesanan']) ?>', '<?= addslashes($r['metode_bayar']) ?>', '<?= addslashes($r['status_bayar']) ?>', <?= $r['total_harga'] ?>, <?= htmlspecialchars(json_encode($items)) ?>)"
                                class="btn-action btn-print">🖨️ Struk</button>

                        <!-- Tracking Link -->
                        <a href="tracking.php?id=<?= $r['id_pesanan'] ?>" target="_blank" class="btn-action" style="background:#E3F2FD;color:#1565C0;">
                            👁️ Tracking
                        </a>

                        <!-- Hapus -->
                        <a href="?hapus=<?= $r['id_pesanan'] ?>" class="btn-action btn-hapus"
                           onclick="return confirm('Hapus pesanan ini?')">🗑️</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>

            <?php if($count === 0): ?>
            <div style="text-align:center;padding:40px;color:var(--muted);">
                <div style="font-size:40px;margin-bottom:10px;">📭</div>
                <div style="font-weight:600">Tidak ada pesanan ditemukan</div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- TAB: STOK MENU -->
    <div id="tab-stok" class="tab-content">
        <div class="section-card">
            <div class="section-title" style="margin-bottom:16px;">🏪 Manajemen Stok Menu</div>
            <div class="stock-grid">
                <?php while($m = mysqli_fetch_assoc($menus)):
                    $isHabis = ($m['status_tersedia'] === 'Habis');
                ?>
                <div class="stock-item">
                    <div>
                        <div class="stock-name"><?= htmlspecialchars($m['nama_item']) ?></div>
                        <div class="stock-cat"><?= $m['kategori'] ?> · Rp<?= number_format($m['harga'],0,',','.') ?></div>
                    </div>
                    <a href="?stok=<?= $m['status_tersedia'] ?>&id=<?= $m['id_menu'] ?>#stok"
                       class="<?= $isHabis ? 'btn-stok-habis' : 'btn-stok-tersedia' ?>">
                        <?= $isHabis ? '✕ Habis' : '✓ Tersedia' ?>
                    </a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

</div>

<!-- PRINT STRUK MODAL (hidden) -->
<div id="strukOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9998;align-items:center;justify-content:center;">
    <div id="strukContent" style="background:white;border-radius:20px;padding:24px;max-width:320px;width:90%;max-height:90vh;overflow-y:auto;"></div>
</div>

<!-- NOTIF TOAST -->
<div class="notif-toast" id="notifToast">🔔 Pesanan baru masuk!</div>

<script>
// ====== TABS ======
function switchTab(name, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

// ====== PRINT STRUK ======
function printStruk(id, nama, meja, jenis, metode, statusBayar, total, items) {
    const overlay = document.getElementById('strukOverlay');
    const content = document.getElementById('strukContent');
    
    const tgl = new Date().toLocaleString('id-ID');
    let itemsHTML = '';
    items.forEach(it => {
        itemsHTML += `
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dashed #eee;font-size:13px;">
            <div>
                <div style="font-weight:600">${it.nama} x${it.qty}</div>
                ${it.pedas && it.pedas !== '-' ? `<small style="color:#e65100">${it.pedas}</small>` : ''}
                ${it.catatan ? `<small style="color:#4527a0"> · ${it.catatan}</small>` : ''}
            </div>
            <div style="font-weight:600;margin-left:10px">Rp${Number(it.subtotal).toLocaleString('id-ID')}</div>
        </div>`;
    });

    content.innerHTML = `
        <div id="printArea" style="font-family:'Plus Jakarta Sans',sans-serif;">
            <div style="text-align:center;margin-bottom:16px;">
                <div style="font-size:24px;margin-bottom:4px;">🍽️</div>
                <div style="font-weight:800;font-size:18px;">Rumah Makan A</div>
                <div style="font-size:12px;color:#8E8E93;">Struk Pesanan #${String(id).padStart(4,'0')}</div>
                <div style="font-size:11px;color:#8E8E93;">${tgl}</div>
            </div>
            <div style="background:#f8f6f3;border-radius:12px;padding:12px;margin-bottom:14px;font-size:13px;">
                <div><b>${nama}</b> — Meja ${meja}</div>
                <div style="color:#8E8E93;">${jenis} · ${metode}</div>
            </div>
            ${itemsHTML}
            <div style="display:flex;justify-content:space-between;padding:14px 0 0;font-weight:800;font-size:16px;">
                <span>TOTAL</span>
                <span style="color:#E8603C">Rp${Number(total).toLocaleString('id-ID')}</span>
            </div>
            <div style="text-align:center;margin-top:12px;padding:10px;background:${statusBayar==='Sudah Bayar'?'#e8f5e9':'#ffebee'};border-radius:10px;font-weight:700;font-size:13px;color:${statusBayar==='Sudah Bayar'?'#1b5e20':'#b71c1c'};">
                ${statusBayar === 'Sudah Bayar' ? '✅ LUNAS' : '⚠️ BELUM DIBAYAR'}
            </div>
            <div style="text-align:center;font-size:11px;color:#8E8E93;margin-top:14px;">
                Terima kasih sudah makan di Rumah Makan A 🙏
            </div>
        </div>
        <div style="display:flex;gap:8px;margin-top:16px;">
            <button onclick="doPrint()" style="flex:1;padding:12px;background:#1C1C1E;color:white;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">🖨️ Cetak</button>
            <button onclick="closeStruk()" style="flex:1;padding:12px;background:#f0ede8;color:#3A3A3C;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Tutup</button>
        </div>`;
    
    overlay.style.display = 'flex';
}
function doPrint() {
    const pa = document.getElementById('printArea').innerHTML;
    const w  = window.open('', '_blank', 'width=400,height=600');
    w.document.write(`<!DOCTYPE html><html><head>
        <meta charset="UTF-8"><title>Struk</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
        <style>body{font-family:'Plus Jakarta Sans',sans-serif;max-width:320px;margin:20px auto;}</style>
    </head><body>${pa}</body></html>`);
    w.document.close();
    w.focus();
    setTimeout(() => { w.print(); w.close(); }, 600);
}
function closeStruk() {
    document.getElementById('strukOverlay').style.display = 'none';
}

// ====== NEW ORDER NOTIFICATION ======
let lastKnownId = <?= (int)$max_id ?>;
let notifEnabled = false;

function playNotifSound() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain); gain.connect(ctx.destination);
        osc.frequency.setValueAtTime(880, ctx.currentTime);
        osc.frequency.setValueAtTime(660, ctx.currentTime + 0.1);
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
        osc.start(); osc.stop(ctx.currentTime + 0.4);
    } catch(e) {}
}

function showToast(msg) {
    const toast = document.getElementById('notifToast');
    toast.innerText = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 4000);
}

function checkNewOrders() {
    fetch(`api_check.php?last_id=${lastKnownId}`)
        .then(r => r.json())
        .then(data => {
            if (data.count > 0) {
                playNotifSound();
                showToast(`🔔 ${data.count} pesanan baru masuk!`);
                document.getElementById('notifDot').style.display = 'block';
                lastKnownId = data.max_id;
                // Auto-reload after 2s
                setTimeout(() => location.reload(), 2000);
            }
        })
        .catch(() => {});
}

// Enable notification on first click (browser policy)
document.addEventListener('click', () => { notifEnabled = true; }, { once: true });

// Poll every 8 seconds
setInterval(checkNewOrders, 8000);

document.getElementById('notifBell').addEventListener('click', () => {
    document.getElementById('notifDot').style.display = 'none';
    location.reload();
});
</script>
</body>
</html>
