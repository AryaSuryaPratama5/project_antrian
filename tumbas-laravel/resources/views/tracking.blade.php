<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Status Pesanan — Rumah Makan A</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @php
        $steps = ['Menunggu', 'Diproses', 'Siap', 'Selesai'];
        $stepIndex = array_search($order->status_pelayanan, $steps) ?: 0;
        
        // Premium Icons & Color Configurations mapped to FontAwesome
        $statusMap = [
            'Menunggu' => [
                'icon' => 'fa-hourglass-start fa-spin-pulse', 
                'color' => '#F59E0B', 
                'bg' => '#FFFBEB', 
                'border' => '#FDE68A',
                'msg' => 'Pesanan Anda telah diterima dan masuk dalam antrean dapur.'
            ],
            'Diproses' => [
                'icon' => 'fa-fire-burner fa-bounce', 
                'color' => '#3B82F6', 
                'bg' => '#EFF6FF', 
                'border' => '#BFDBFE',
                'msg' => 'Koki andalan kami sedang menyiapkan hidangan lezat Anda.'
            ],
            'Siap'     => [
                'icon' => 'fa-bell fa-shake', 
                'color' => '#8B5CF6', 
                'bg' => '#F5F3FF', 
                'border' => '#DDD6FE',
                'msg' => 'Pesanan sudah matang! Pelayan akan segera mengantarkannya ke meja Anda.'
            ],
            'Selesai'  => [
                'icon' => 'fa-circle-check', 
                'color' => '#10B981', 
                'bg' => '#ECFDF5', 
                'border' => '#A7F3D0',
                'msg' => 'Selamat menikmati! Seluruh pesanan Anda telah selesai disajikan.'
            ],
        ];
        
        $cfg = $statusMap[$order->status_pelayanan] ?? $statusMap['Menunggu'];
        $sisaDtk = max(0, ($order->created_at->timestamp + $order->estimasi_menit * 60) - time());
    @endphp
    
    <style>
        /* === PREMIUM DESIGN TOKENS === */
        :root {
            --brand-primary: #991B1B; /* Deep Crimson */
            --brand-hover: #7F1D1D;
            --brand-light: #FEF2F2;
            --brand-gradient: linear-gradient(135deg, #991B1B 0%, #B91C1C 100%);
            
            --bg-app: #F4F7F9;
            --bg-surface: #FFFFFF;
            --bg-input: #F8FAFC;
            
            --text-strong: #0F172A;
            --text-base: #334155;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
            
            --radius-md: 12px;
            --radius-lg: 24px;
            
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.02);
            --shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            --shadow-float: 0 -8px 24px rgba(0,0,0,0.06);
            
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }

        body { 
            background: var(--bg-app); 
            color: var(--text-base); 
            padding-bottom: 120px; 
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.01em;
        }

        .app-container { 
            max-width: 480px; 
            margin: 0 auto; 
            background: var(--bg-app); 
            min-height: 100vh; 
            padding: 16px;
        }

        /* === ELEGANT HEADER === */
        .header { 
            background: var(--bg-surface); 
            padding: 24px 20px; 
            border-radius: var(--radius-lg);
            border: 1px solid rgba(226, 232, 240, 0.8); 
            text-align: center; 
            box-shadow: var(--shadow-sm);
            margin-bottom: 16px;
        }
        
        .brand-name { 
            font-size: 20px; 
            font-weight: 800; 
            color: var(--brand-primary); 
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .order-id { 
            font-size: 13.5px; 
            color: var(--text-muted); 
            margin-top: 6px; 
            font-weight: 600;
        }

        /* === CENTRAL PAYMENTS CARD === */
        .status-card { 
            background: var(--bg-surface); 
            padding: 32px 24px; 
            border-radius: var(--radius-lg); 
            border: 1px solid rgba(226, 232, 240, 0.8); 
            text-align: center; 
            box-shadow: var(--shadow-md); 
            margin-bottom: 16px;
        }

        .status-icon { 
            width: 72px; height: 72px; 
            border-radius: 50%; 
            background: {{ $cfg['bg'] }}; 
            color: {{ $cfg['color'] }}; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 28px; margin: 0 auto 20px; 
            border: 2px solid {{ $cfg['border'] }};
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
        }

        .status-title { 
            font-size: 22px; 
            font-weight: 800; 
            color: {{ $cfg['color'] }}; 
            margin-bottom: 8px; 
            letter-spacing: -0.5px;
        }

        .status-msg { 
            font-size: 14px; 
            color: var(--text-muted); 
            line-height: 1.6; 
            font-weight: 500;
        }

        /* === PROGRESS STEP BAR === */
        .progress-container { margin-top: 36px; position: relative; padding: 0; }
        
        .progress-line { 
            position: absolute; 
            top: 14px; left: 35px; right: 35px; 
            height: 3px; 
            background: var(--border-color); 
            z-index: 0; 
            border-radius: 10px;
        }
        
        .progress-fill { 
            height: 100%; 
            background: var(--brand-gradient); 
            transition: width 0.5s ease; 
            width: {{ $stepIndex > 0 ? ($stepIndex / 3) * 100 : 0 }}%; 
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(153, 27, 27, 0.4);
        }
        
        .steps { display: flex; justify-content: space-between; position: relative; z-index: 1; }
        
        .step { display: flex; flex-direction: column; align-items: center; gap: 10px; flex: 1; }
        
        .step-dot { 
            width: 30px; height: 30px; 
            border-radius: 50%; 
            background: var(--bg-surface); 
            border: 2px solid var(--border-color); 
            display: flex; align-items: center; justify-content: center; 
            font-size: 11px; font-weight: 800;
            color: var(--text-muted);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        
        .step.active .step-dot { 
            border-color: var(--brand-primary); 
            background: var(--brand-gradient); 
            color: #fff; 
            transform: scale(1.15); 
            box-shadow: 0 0 0 4px var(--brand-light); 
        }
        
        .step.done .step-dot { 
            border-color: var(--brand-primary); 
            background: var(--brand-primary); 
            color: #fff; 
        }
        
        .step-label { font-size: 11.5px; font-weight: 700; color: var(--text-muted); }
        .step.active .step-label, .step.done .step-label { color: var(--text-strong); }

        /* === TIMER STOPWATCH CARD === */
        .timer-card { 
            background: var(--bg-surface); 
            margin: 0 0 16px; 
            padding: 18px 24px; 
            border-radius: var(--radius-lg); 
            border: 1px solid rgba(226, 232, 240, 0.8); 
            display: flex; 
            align-items: center; 
            gap: 20px; 
            box-shadow: var(--shadow-sm);
        }
        
        .timer-icon { 
            font-size: 24px; 
            color: var(--brand-primary);
            background: var(--brand-light);
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center; justify-content: center;
            animation: soft-pulse 2s infinite ease-in-out;
        }

        @keyframes soft-pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        .timer-info { flex: 1; }
        .timer-label { font-size: 12.5px; color: var(--text-muted); font-weight: 700; }
        .timer-val { font-size: 22px; font-weight: 800; color: var(--brand-primary); letter-spacing: -0.5px; }

        /* === ORDER SUMMARY CARD === */
        .summary-card { 
            background: var(--bg-surface); 
            margin: 0 0 16px; 
            padding: 24px; 
            border-radius: var(--radius-lg); 
            border: 1px solid rgba(226, 232, 240, 0.8); 
            box-shadow: var(--shadow-sm);
        }
        
        .summary-title { 
            font-size: 15px; 
            font-weight: 800; 
            color: var(--text-strong); 
            margin-bottom: 20px; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            border-bottom: 1px solid var(--border-color); 
            padding-bottom: 12px; 
        }
        .summary-title i {
            color: var(--brand-primary);
        }

        .item-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 12px 0; 
            border-bottom: 1px dashed var(--border-color); 
        }
        .item-row:last-of-type { border-bottom: none; }
        
        .item-name { font-size: 14.5px; font-weight: 700; color: var(--text-strong); }
        .item-qty { color: var(--text-muted); font-size: 13px; font-weight: 600; margin-left: 4px; }
        
        .item-meta { 
            font-size: 12px; 
            color: var(--text-muted); 
            margin-top: 4px; 
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }
        .item-meta i { font-size: 10px; }
        .item-meta-pedas i { color: #EF4444; }
        
        .item-price { font-size: 14.5px; font-weight: 700; color: var(--text-strong); }
        
        .total-row { 
            display: flex; 
            justify-content: space-between; 
            margin-top: 20px; 
            padding-top: 20px; 
            border-top: 1px solid var(--border-color); 
        }
        
        .total-label { font-weight: 800; font-size: 15px; color: var(--text-strong); }
        .total-val { font-weight: 800; font-size: 19px; color: var(--brand-primary); }

        .metadata-pill-box {
            margin-top: 16px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .meta-pill {
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-base);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* === FLOATING FOOTER ACTION === */
        .footer { 
            position: fixed; bottom: 0; left: 0; right: 0; 
            max-width: 480px; margin: 0 auto; 
            background: var(--bg-surface); 
            padding: 16px 20px 24px; 
            border-top: 1px solid var(--border-color); 
            box-shadow: var(--shadow-float); 
            z-index: 100;
            border-radius: 20px 20px 0 0;
        }
        
        .btn-new { 
            display: flex; 
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%; 
            background: var(--brand-gradient); 
            color: #fff; 
            text-decoration: none; 
            padding: 16px; 
            border-radius: 14px; 
            font-weight: 700; 
            font-size: 15.5px; 
            box-shadow: 0 4px 15px rgba(153, 27, 27, 0.3);
            transition: all 0.2s ease;
        }
        
        .btn-new:active {
            transform: scale(0.98);
            box-shadow: 0 2px 8px rgba(153, 27, 27, 0.2);
        }

        .refresh-note { 
            text-align: center; 
            font-size: 11.5px; 
            color: var(--text-muted); 
            margin-top: 16px; 
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
    </style>
</head>
<body>
<div class="app-container">
    
    <!-- Branding Header -->
    <header class="header">
        <h1 class="brand-name">
            <i class="fas fa-utensils"></i>
            Rumah Makan A
        </h1>
        <p class="order-id">
            <i class="fas fa-receipt me-1 opacity-75"></i> Pesanan #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} &nbsp;·&nbsp; <strong class="text-dark">{{ $order->nama_pelanggan }}</strong>
        </p>
    </header>

    <!-- Interactive Status Box Card -->
    <div class="status-card">
        <div class="status-icon">
            <i class="fas {{ $cfg['icon'] }}"></i>
        </div>
        <h2 class="status-title">{{ $order->status_pelayanan }}</h2>
        <p class="status-msg">{{ $cfg['msg'] }}</p>

        <!-- Dynamic Timeline Steps -->
        <div class="progress-container">
            <div class="progress-line">
                <div class="progress-fill"></div>
            </div>
            <div class="steps">
                @foreach($steps as $i => $s)
                    @php 
                        $cls = ($i < $stepIndex) ? 'done' : (($i == $stepIndex) ? 'active' : ''); 
                    @endphp
                    <div class="step {{ $cls }}">
                        <div class="step-dot">
                            @if($i < $stepIndex)
                                <i class="fas fa-check"></i>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <span class="step-label">{{ $s }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Wait Countdown Card -->
    @if($order->status_pelayanan !== 'Selesai')
    <div class="timer-card">
        <div class="timer-icon">
            <i class="fas fa-stopwatch"></i>
        </div>
        <div class="timer-info">
            <div class="timer-label">Estimasi Sisa Waktu Tunggu</div>
            <div class="timer-val" id="timerDisplay">--:--</div>
        </div>
    </div>
    @endif

    <!-- Ordered Food items list Summary -->
    <div class="summary-card">
        <h3 class="summary-title">
            <i class="fas fa-list-check"></i>
            Rincian Pesanan Anda
        </h3>
        
        @foreach($order->detail_json as $item)
            <div class="item-row">
                <div>
                    <div class="item-name">{{ $item['nama'] }} <span class="item-qty">×{{ $item['qty'] }}</span></div>
                    @if(!empty($item['pedas']) && $item['pedas'] !== '-' && $item['pedas'] !== 'Tidak Pedas')
                        <div class="item-meta item-meta-pedas">
                            <i class="fas fa-pepper-hot"></i>
                            <span>{{ $item['pedas'] }}</span>
                        </div>
                    @endif
                    @if(!empty($item['catatan']))
                        <div class="item-meta">
                            <i class="fas fa-note-sticky"></i>
                            <span>{{ $item['catatan'] }}</span>
                        </div>
                    @endif
                </div>
                <div class="item-price">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
            </div>
        @endforeach

        <!-- Cart Summary Footer (Order Info Pills) -->
        <div class="total-row">
            <span class="total-label">Total Pembayaran</span>
            <span class="total-val">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
        </div>
        
        <div class="metadata-pill-box">
            <div class="meta-pill">
                <i class="fas fa-chair"></i>
                <span>Meja {{ $order->nomor_meja }}</span>
            </div>
            <div class="meta-pill">
                <i class="fas fa-concierge-bell"></i>
                <span>{{ $order->jenis_pesanan }}</span>
            </div>
            <div class="meta-pill" style="color: {{ $order->status_bayar == 'Sudah Bayar' ? '#10B981' : '#EF4444' }}; background-color: {{ $order->status_bayar == 'Sudah Bayar' ? '#ECFDF5' : '#FEF2F2' }}; border-color: {{ $order->status_bayar == 'Sudah Bayar' ? '#A7F3D0' : '#FCA5A5' }};">
                <i class="fas {{ $order->status_bayar == 'Sudah Bayar' ? 'fa-credit-card' : 'fa-circle-exclamation' }}"></i>
                <span>{{ $order->status_bayar }}</span>
            </div>
        </div>
    </div>

    <!-- Live Auto-refresh label text indicator -->
    <p class="refresh-note">
        <i class="fas fa-rotate fa-spin text-muted" style="animation-duration: 4s;"></i>
        <span>Halaman ini diperbarui otomatis setiap 5 detik</span>
    </p>
</div>

<!-- Floating Action Button -->
<div class="footer">
    <a href="{{ route('menu') }}" class="btn-new">
        <i class="fas fa-cart-plus"></i>
        <span>Buat Pesanan Baru</span>
    </a>
</div>

<script>
    let sisa = {{ $sisaDtk }};
    function updateTimer() {
        const el = document.getElementById('timerDisplay');
        if (!el) return;
        if (sisa <= 0) { 
            el.innerText = 'Segera Siap'; 
            el.style.color = '#10B981';
            return; 
        }
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