<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Status Pesanan — Rumah Makan A</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @php
        $steps = ['Menunggu','Diproses','Siap','Selesai'];
        $stepIndex = array_search($order->status_pelayanan, $steps) ?: 0;
        $statusMap = [
            'Menunggu' => ['icon' => '⏳', 'color' => '#F59E0B', 'bg' => '#FFFBEB', 'msg' => 'Pesanan Anda sedang menunggu antrian.'],
            'Diproses' => ['icon' => '👨‍🍳', 'color' => '#3B82F6', 'bg' => '#DBEAFE', 'msg' => 'Koki sedang menyiapkan pesanan Anda.'],
            'Siap'     => ['icon' => '🔔', 'color' => '#8B5CF6', 'bg' => '#EDE9FE', 'msg' => 'Pesanan siap! Silakan ambil atau tunggu pelayan.'],
            'Selesai'  => ['icon' => '✅', 'color' => '#10B981', 'bg' => '#D1FAE5', 'msg' => 'Pesanan telah selesai disajikan.'],
        ];
        $cfg = $statusMap[$order->status_pelayanan] ?? $statusMap['Menunggu'];
        $sisaDtk = max(0, ($order->created_at->timestamp + $order->estimasi_menit * 60) - time());
    @endphp
    <style>
        :root {
            --brand-primary: #991B1B;
            --bg-app: #F3F4F6;
            --bg-surface: #FFFFFF;
            --text-strong: #111827;
            --text-base: #374151;
            --text-muted: #6B7280;
            --border-color: #E5E7EB;
            --radius-lg: 16px;
            --radius-md: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg-app); color: var(--text-base); padding-bottom: 100px; }
        .app-container { max-width: 480px; margin: 0 auto; background: var(--bg-app); min-height: 100vh; }
        .header { background: var(--bg-surface); padding: 24px 20px; border-bottom: 1px solid var(--border-color); text-align: center; }
        .brand-name { font-size: 18px; font-weight: 700; color: var(--brand-primary); }
        .order-id { font-size: 13px; color: var(--text-muted); margin-top: 4px; }
        .status-card { background: var(--bg-surface); margin: 12px; padding: 24px; border-radius: var(--radius-lg); border: 1px solid var(--border-color); text-align: center; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .status-icon { width: 64px; height: 64px; border-radius: 50%; background: {{ $cfg['bg'] }}; color: {{ $cfg['color'] }}; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px; border: 2px solid {{ $cfg['color'] }}; }
        .status-title { font-size: 20px; font-weight: 700; color: {{ $cfg['color'] }}; margin-bottom: 8px; }
        .status-msg { font-size: 14px; color: var(--text-muted); line-height: 1.5; }

        .progress-container { margin-top: 32px; position: relative; padding: 0 10px; }
        .progress-line { position: absolute; top: 12px; left: 30px; right: 30px; height: 2px; background: var(--border-color); z-index: 0; }
        .progress-fill { height: 100%; background: var(--brand-primary); transition: width 0.5s; width: {{ $stepIndex > 0 ? ($stepIndex / 3) * 100 : 0 }}%; }
        .steps { display: flex; justify-content: space-between; position: relative; z-index: 1; }
        .step { display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1; }
        .step-dot { width: 24px; height: 24px; border-radius: 50%; background: var(--bg-surface); border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 10px; transition: all 0.3s; }
        .step.active .step-dot { border-color: var(--brand-primary); background: var(--brand-primary); color: #fff; transform: scale(1.1); box-shadow: 0 0 0 4px var(--brand-light); }
        .step.done .step-dot { border-color: var(--brand-primary); background: var(--brand-primary); color: #fff; }
        .step-label { font-size: 11px; font-weight: 600; color: var(--text-muted); }
        .step.active .step-label, .step.done .step-label { color: var(--text-strong); }

        .timer-card { background: var(--bg-surface); margin: 0 12px 12px; padding: 16px 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 16px; }
        .timer-icon { font-size: 24px; }
        .timer-info { flex: 1; }
        .timer-label { font-size: 12px; color: var(--text-muted); font-weight: 600; }
        .timer-val { font-size: 20px; font-weight: 700; color: var(--brand-primary); }

        .summary-card { background: var(--bg-surface); margin: 0 12px 12px; padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); }
        .summary-title { font-size: 14px; font-weight: 700; color: var(--text-strong); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid var(--bg-app); padding-bottom: 8px; }
        .item-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed var(--bg-app); }
        .item-row:last-of-type { border-bottom: none; }
        .item-name { font-size: 14px; font-weight: 600; }
        .item-qty { color: var(--text-muted); font-size: 13px; }
        .item-meta { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .item-price { font-size: 14px; font-weight: 600; }
        .total-row { display: flex; justify-content: space-between; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--bg-app); }
        .total-label { font-weight: 700; font-size: 15px; }
        .total-val { font-weight: 700; font-size: 18px; color: var(--brand-primary); }

        .footer { position: fixed; bottom: 0; left: 0; right: 0; max-width: 480px; margin: 0 auto; background: var(--bg-surface); padding: 16px 20px 24px; border-top: 1px solid var(--border-color); box-shadow: 0 -4px 12px rgba(0,0,0,0.05); }
        .btn-new { display: block; width: 100%; text-align: center; background: var(--brand-primary); color: #fff; text-decoration: none; padding: 14px; border-radius: var(--radius-md); font-weight: 600; font-size: 15px; }
        .refresh-note { text-align: center; font-size: 11px; color: var(--text-muted); margin-top: 12px; }
    </style>
</head>
<body>
<div class="app-container">
    <header class="header">
        <h1 class="brand-name">Rumah Makan A</h1>
        <p class="order-id">Pesanan #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} &nbsp;·&nbsp; {{ $order->nama_pelanggan }}</p>
    </header>

    <div class="status-card">
        <div class="status-icon">{{ $cfg['icon'] }}</div>
        <h2 class="status-title">{{ $order->status_pelayanan }}</h2>
        <p class="status-msg">{{ $cfg['msg'] }}</p>

        <div class="progress-container">
            <div class="progress-line"><div class="progress-fill"></div></div>
            <div class="steps">
                @foreach($steps as $i => $s)
                    @php $cls = ($i < $stepIndex) ? 'done' : (($i == $stepIndex) ? 'active' : ''); @endphp
                    <div class="step {{ $cls }}">
                        <div class="step-dot">{{ $i < $stepIndex ? '✓' : '' }}</div>
                        <span class="step-label">{{ $s }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if($order->status_pelayanan !== 'Selesai')
    <div class="timer-card">
        <div class="timer-icon">⏱️</div>
        <div class="timer-info">
            <div class="timer-label">Estimasi Waktu Tunggu</div>
            <div class="timer-val" id="timerDisplay">--:--</div>
        </div>
    </div>
    @endif

    <div class="summary-card">
        <h3 class="summary-title">Rincian Pesanan</h3>
        @foreach($order->detail_json as $item)
            <div class="item-row">
                <div>
                    <div class="item-name">{{ $item['nama'] }} <span class="item-qty">×{{ $item['qty'] }}</span></div>
                    @if(!empty($item['pedas']) && $item['pedas'] !== '-' && $item['pedas'] !== 'Tidak Pedas')
                        <div class="item-meta">🌶️ {{ $item['pedas'] }}</div>
                    @endif
                    @if(!empty($item['catatan']))
                        <div class="item-meta">📝 {{ $item['catatan'] }}</div>
                    @endif
                </div>
                <div class="item-price">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
            </div>
        @endforeach

        <div class="total-row">
            <span class="total-label">Total</span>
            <span class="total-val">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
        </div>
        <div style="margin-top: 8px; font-size: 12px; color: var(--text-muted); display: flex; gap: 8px;">
            <span>Meja {{ $order->nomor_meja }}</span>
            <span>·</span>
            <span>{{ $order->jenis_pesanan }}</span>
            <span>·</span>
            <span style="color: {{ $order->status_bayar == 'Sudah Bayar' ? '#10B981' : '#EF4444' }}; font-weight: 600;">{{ $order->status_bayar }}</span>
        </div>
    </div>

    <p class="refresh-note">Halaman ini diperbarui otomatis setiap 5 detik</p>
</div>

<div class="footer">
    <a href="{{ route('menu') }}" class="btn-new">Buat Pesanan Baru</a>
</div>

<script>
    let sisa = {{ $sisaDtk }};
    function updateTimer() {
        const el = document.getElementById('timerDisplay');
        if (!el) return;
        if (sisa <= 0) { el.innerText = 'Segera Siap'; return; }
        const m = Math.floor(sisa / 60), s = sisa % 60;
        el.innerText = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        sisa--;
    }
    updateTimer();
    setInterval(updateTimer, 1000);
    @if($order->status_pelayanan !== 'Selesai')
        setTimeout(() => location.reload(), 5000);
    @endif
</script>
</body>
</html>
