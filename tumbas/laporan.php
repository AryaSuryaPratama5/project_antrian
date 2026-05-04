<?php
include 'koneksi.php';
include 'auth.php';
requireLogin(['kasir', 'admin']);

// Date filters
$tgl   = $_GET['tgl'] ?? date('Y-m-d');
$tgl_dari = $_GET['dari'] ?? $tgl;
$tgl_sampai = $_GET['sampai'] ?? $tgl;

// Daily summary
$sum = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        COUNT(*) as total_transaksi,
        SUM(total_harga) as total_pendapatan,
        SUM(CASE WHEN metode_bayar='Tunai'    THEN total_harga ELSE 0 END) as tunai,
        SUM(CASE WHEN metode_bayar='QRIS'     THEN total_harga ELSE 0 END) as qris,
        SUM(CASE WHEN metode_bayar='Transfer' THEN total_harga ELSE 0 END) as transfer,
        SUM(CASE WHEN status_bayar='Belum Bayar' THEN total_harga ELSE 0 END) as belum_bayar,
        COUNT(CASE WHEN status_pelayanan='Selesai' THEN 1 END) as selesai,
        AVG(total_harga) as rata2
    FROM pesanan
    WHERE DATE(waktu_pesan) BETWEEN '$tgl_dari' AND '$tgl_sampai'
"));

// Top menu (from JSON parsing – simplified: from detail_pesanan text)
$top_menu = [];
$ord_res = mysqli_query($conn, "SELECT detail_json FROM pesanan WHERE DATE(waktu_pesan) BETWEEN '$tgl_dari' AND '$tgl_sampai'");
while($r = mysqli_fetch_assoc($ord_res)) {
    $items = json_decode($r['detail_json'] ?? '[]', true) ?: [];
    foreach($items as $it) {
        $n = $it['nama'] ?? '';
        if(!isset($top_menu[$n])) $top_menu[$n] = ['qty'=>0,'total'=>0];
        $top_menu[$n]['qty']   += $it['qty'] ?? 1;
        $top_menu[$n]['total'] += $it['subtotal'] ?? 0;
    }
}
arsort($top_menu);
$top_menu = array_slice($top_menu, 0, 8, true);

// Chart: last 7 days revenue
$chart_data = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(total_harga),0) as tot FROM pesanan WHERE DATE(waktu_pesan)='$d'"));
    $chart_data[] = ['label' => date('d/m', strtotime($d)), 'value' => (int)$r['tot']];
}

// All orders in range
$orders = mysqli_query($conn, "
    SELECT * FROM pesanan 
    WHERE DATE(waktu_pesan) BETWEEN '$tgl_dari' AND '$tgl_sampai'
    ORDER BY waktu_pesan DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan — Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <style>
        :root {
            --primary: #E8603C; --secondary: #F5A623;
            --dark: #1C1C1E; --text: #3A3A3C; --muted: #8E8E93;
            --surface: #F8F6F3; --card: #FFFFFF; --border: #EBEBEB;
            --success: #34C759; --danger: #FF3B30;
        }
        * { box-sizing: border-box; margin:0; padding:0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--surface); color: var(--text); }

        .topbar {
            background: white; padding: 14px 20px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            position: sticky; top:0; z-index:10;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .logo-icon {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center; font-size: 20px;
        }
        .topbar-title { font-weight: 800; font-size: 16px; color: var(--dark); }
        .topbar-sub { font-size: 11px; color: var(--muted); }
        .topbar-right { display: flex; gap: 8px; align-items: center; }
        .nav-link {
            color: var(--muted); text-decoration: none; font-size: 13px; font-weight: 600;
            padding: 8px 12px; border-radius: 10px; transition: background 0.2s;
        }
        .nav-link:hover { background: var(--surface); color: var(--dark); }
        .btn-pdf {
            background: var(--dark); color: white; border: none;
            padding: 10px 16px; border-radius: 11px; font-size: 13px; font-weight: 700;
            cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .main { padding: 20px; max-width: 1100px; margin: auto; }

        /* Date Filter */
        .date-filter {
            background: white; border-radius: 16px; padding: 16px 20px;
            margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        }
        .date-form {
            display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
        }
        .date-form label { font-size: 12px; font-weight: 700; color: var(--muted); }
        .date-form input[type="date"] {
            padding: 9px 12px; border: 2px solid var(--border); border-radius: 11px;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; outline: none;
        }
        .date-form input:focus { border-color: var(--primary); }
        .btn-filter {
            background: var(--primary); color: white; border: none;
            padding: 10px 18px; border-radius: 11px; font-size: 13px;
            font-weight: 700; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .quick-btns { display: flex; gap: 6px; margin-left: auto; flex-wrap: wrap; }
        .qbtn {
            padding: 8px 12px; border: 2px solid var(--border);
            background: var(--surface); border-radius: 10px;
            font-size: 12px; font-weight: 600; cursor: pointer; color: var(--text);
            text-decoration: none; transition: all 0.2s;
        }
        .qbtn:hover { border-color: var(--primary); color: var(--primary); }

        /* Stats Grid */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        @media(max-width:768px){ .stats { grid-template-columns: 1fr 1fr; } }
        .stat-card {
            background: white; border-radius: 16px; padding: 18px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        }
        .stat-icon { font-size: 24px; margin-bottom: 10px; }
        .stat-val { font-size: 22px; font-weight: 800; color: var(--dark); margin-bottom: 3px; }
        .stat-label { font-size: 11px; color: var(--muted); font-weight: 600; }
        .stat-sub { font-size: 12px; color: var(--muted); margin-top: 6px; }

        /* Grid 2 col */
        .grid2 { display: grid; grid-template-columns: 1.5fr 1fr; gap: 16px; margin-bottom: 20px; }
        @media(max-width:768px){ .grid2 { grid-template-columns: 1fr; } }

        /* Card */
        .card {
            background: white; border-radius: 18px; padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px;
        }
        .card-title {
            font-size: 15px; font-weight: 800; color: var(--dark);
            margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
        }

        /* Chart */
        .chart-wrap { position: relative; height: 220px; }

        /* Top Menu */
        .menu-rank {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 0; border-bottom: 1px solid var(--border);
        }
        .menu-rank:last-child { border-bottom: none; }
        .rank-num {
            width: 28px; height: 28px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 800; flex-shrink: 0;
            background: var(--surface); color: var(--muted);
        }
        .rank-num.gold   { background: #FFF8E1; color: #F5A623; }
        .rank-num.silver { background: #F5F5F5; color: #9E9E9E; }
        .rank-num.bronze { background: #FBE9E7; color: #E8603C; }
        .rank-name { flex: 1; font-weight: 700; font-size: 14px; }
        .rank-qty  { font-size: 12px; color: var(--muted); }
        .rank-bar-wrap { width: 100%; height: 5px; background: var(--border); border-radius: 3px; margin-top: 5px; }
        .rank-bar { height: 100%; background: linear-gradient(to right, var(--primary), var(--secondary)); border-radius: 3px; }

        /* Orders Table */
        .orders-table {
            width: 100%; border-collapse: collapse;
        }
        .orders-table th {
            text-align: left; padding: 11px 12px;
            background: var(--surface); font-size: 11px;
            font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.4px;
        }
        .orders-table td {
            padding: 12px; border-bottom: 1px solid var(--border);
            font-size: 13px;
        }
        .orders-table tr:last-child td { border-bottom: none; }
        .badge-sm {
            padding: 3px 8px; border-radius: 6px;
            font-size: 10px; font-weight: 700;
        }
        .badge-lunas { background: #E8F5E9; color: #1B5E20; }
        .badge-belum { background: #FFEBEE; color: #B71C1C; }
        .badge-sls { background: #E8F5E9; color: #1B5E20; }
        .badge-men { background: #FFF8E1; color: #E65100; }
        .table-wrap { overflow-x: auto; }

        /* Print Styles */
        @media print {
            .topbar, .date-filter, .no-print { display: none !important; }
            .main { padding: 0; }
            .card { box-shadow: none; border: 1px solid #eee; page-break-inside: avoid; }
            body { background: white; }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <div class="logo-icon">📊</div>
        <div>
            <div class="topbar-title">Laporan Penjualan</div>
            <div class="topbar-sub">Rumah Makan A</div>
        </div>
    </div>
    <div class="topbar-right">
        <a href="halaman_kasir.php" class="nav-link">← Kasir</a>
        <button class="btn-pdf no-print" onclick="window.print()">🖨️ Cetak / PDF</button>
    </div>
</div>

<div class="main">

    <!-- Date Filter -->
    <div class="date-filter no-print">
        <form method="GET" class="date-form">
            <label>Dari:</label>
            <input type="date" name="dari" value="<?= $tgl_dari ?>">
            <label>Sampai:</label>
            <input type="date" name="sampai" value="<?= $tgl_sampai ?>">
            <button type="submit" class="btn-filter">Tampilkan</button>
            <div class="quick-btns">
                <a href="?dari=<?= date('Y-m-d') ?>&sampai=<?= date('Y-m-d') ?>" class="qbtn">Hari Ini</a>
                <a href="?dari=<?= date('Y-m-d', strtotime('-6 days')) ?>&sampai=<?= date('Y-m-d') ?>" class="qbtn">7 Hari</a>
                <a href="?dari=<?= date('Y-m-01') ?>&sampai=<?= date('Y-m-d') ?>" class="qbtn">Bulan Ini</a>
            </div>
        </form>
    </div>

    <!-- Period Header -->
    <div style="margin-bottom:16px;font-weight:700;color:var(--muted);font-size:13px;">
        📅 <?= date('d F Y', strtotime($tgl_dari)) ?>
        <?= ($tgl_dari !== $tgl_sampai) ? ' – ' . date('d F Y', strtotime($tgl_sampai)) : '' ?>
    </div>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-val" style="color:var(--success)">
                Rp<?= number_format($sum['total_pendapatan'] ?? 0, 0, ',', '.') ?>
            </div>
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-sub">⚠️ Belum bayar: Rp<?= number_format($sum['belum_bayar'] ?? 0, 0, ',', '.') ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🧾</div>
            <div class="stat-val"><?= $sum['total_transaksi'] ?></div>
            <div class="stat-label">Jumlah Transaksi</div>
            <div class="stat-sub">✅ Selesai: <?= $sum['selesai'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-val">Rp<?= number_format($sum['rata2'] ?? 0, 0, ',', '.') ?></div>
            <div class="stat-label">Rata-rata Per Transaksi</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💳</div>
            <div class="stat-val" style="font-size:14px;">
                <div>💵 Rp<?= number_format($sum['tunai'] ?? 0, 0, ',', '.') ?></div>
                <div style="margin-top:4px">📱 Rp<?= number_format($sum['qris'] ?? 0, 0, ',', '.') ?></div>
                <div style="margin-top:4px">🏦 Rp<?= number_format($sum['transfer'] ?? 0, 0, ',', '.') ?></div>
            </div>
            <div class="stat-label">Per Metode Bayar</div>
        </div>
    </div>

    <!-- Chart + Top Menu -->
    <div class="grid2">
        <div class="card">
            <div class="card-title">📈 Pendapatan 7 Hari Terakhir</div>
            <div class="chart-wrap">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-title">🏆 Menu Terlaris</div>
            <?php
            $maxQty = !empty($top_menu) ? max(array_column($top_menu, 'qty')) : 1;
            $rank = 0;
            foreach($top_menu as $nama => $d):
                $rank++;
                $rankClass = $rank === 1 ? 'gold' : ($rank === 2 ? 'silver' : ($rank === 3 ? 'bronze' : ''));
                $pct = $maxQty > 0 ? round($d['qty']/$maxQty*100) : 0;
            ?>
            <div class="menu-rank">
                <div class="rank-num <?= $rankClass ?>"><?= $rank ?></div>
                <div style="flex:1">
                    <div class="rank-name"><?= htmlspecialchars($nama) ?></div>
                    <div class="rank-bar-wrap"><div class="rank-bar" style="width:<?= $pct ?>%"></div></div>
                </div>
                <div style="text-align:right">
                    <div style="font-weight:700;font-size:13px"><?= $d['qty'] ?>x</div>
                    <div class="rank-qty">Rp<?= number_format($d['total'],0,',','.') ?></div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($top_menu)): ?>
            <div style="text-align:center;color:var(--muted);padding:20px;">Tidak ada data</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-title">🧾 Daftar Transaksi</div>
        <div class="table-wrap">
            <table class="orders-table">
                <tr>
                    <th>#</th><th>Waktu</th><th>Pelanggan</th><th>Meja</th>
                    <th>Pesanan</th><th>Total</th><th>Bayar</th><th>Status</th>
                </tr>
                <?php
                $no = 0;
                while($r = mysqli_fetch_assoc($orders)):
                    $no++;
                ?>
                <tr>
                    <td style="color:var(--muted);font-size:12px;"><?= str_pad($r['id_pesanan'],4,'0',STR_PAD_LEFT) ?></td>
                    <td style="font-size:12px;color:var(--muted);"><?= date('H:i<br>d/m', strtotime($r['waktu_pesan'])) ?></td>
                    <td><b><?= htmlspecialchars($r['nama_pelanggan']) ?></b></td>
                    <td>Meja <?= $r['nomor_meja'] ?></td>
                    <td style="font-size:12px;max-width:200px;"><?= htmlspecialchars($r['detail_pesanan']) ?></td>
                    <td style="font-weight:700;color:var(--primary);">Rp<?= number_format($r['total_harga'],0,',','.') ?></td>
                    <td>
                        <span class="badge-sm <?= $r['status_bayar']==='Sudah Bayar'?'badge-lunas':'badge-belum' ?>">
                            <?= $r['status_bayar']==='Sudah Bayar'?'✅ Lunas':'❌ Belum' ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge-sm <?= $r['status_pelayanan']==='Selesai'?'badge-sls':'badge-men' ?>">
                            <?= $r['status_pelayanan'] ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if($no === 0): ?>
                <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--muted);">Tidak ada transaksi pada periode ini</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

</div>

<script>
// Chart.js Revenue Chart
const chartData = <?= json_encode(array_values($chart_data)) ?>;
const labels = chartData.map(d => d.label);
const values = chartData.map(d => d.value);
const maxVal = Math.max(...values, 1);

const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: values,
            backgroundColor: values.map((v, i) =>
                i === values.indexOf(Math.max(...values))
                    ? 'rgba(232,96,60,0.9)'
                    : 'rgba(232,96,60,0.3)'
            ),
            borderColor: 'rgba(232,96,60,0.8)',
            borderWidth: 2,
            borderRadius: 10,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => 'Rp' + ctx.parsed.y.toLocaleString('id-ID')
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } },
            y: {
                grid: { color: '#F0EDE8' },
                ticks: {
                    font: { family: 'Plus Jakarta Sans', size: 11 },
                    callback: v => 'Rp' + (v/1000).toLocaleString('id-ID') + 'k'
                }
            }
        }
    }
});
</script>
</body>
</html>
