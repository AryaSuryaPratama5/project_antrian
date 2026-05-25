<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Dapur — Rumah Makan A</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* === PROFESSIONAL UI VARIABLES === */
        :root {
            --brand-primary: #991B1B; /* Deep Crimson */
            --brand-hover: #7F1D1D;
            --brand-light: #FEF2F2;
            
            --bg-app: #0B0F19;        /* Deep Space Black */
            --bg-card: #151D30;       /* Navy Slate */
            --border-color: #24314F;   /* Soft Border */
            
            --text-strong: #F8FAFC;
            --text-base: #CBD5E1;
            --text-muted: #64748B;
            
            --menunggu: #F59E0B;
            --diproses: #3B82F6;
            --siap: #8B5CF6;
            --selesai: #10B981;
            
            --radius-md: 12px;
            --radius-lg: 20px;
            
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body { 
            background: var(--bg-app); 
            color: var(--text-base); 
            min-height: 100vh; 
            padding-top: 80px; 
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.01em;
        }

        /* === TOPBAR (Elegant Dark Glassmorphism) === */
        .topbar { 
            position: fixed; top: 0; left: 0; right: 0; 
            height: 70px; 
            background: rgba(21, 29, 48, 0.85);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            display: flex; align-items: center; justify-content: space-between; 
            padding: 0 32px; 
            border-bottom: 1px solid var(--border-color); 
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .topbar-left { display: flex; align-items: center; gap: 14px; }
        
        .logo-box { 
            width: 40px; height: 40px; 
            background: linear-gradient(135deg, var(--brand-primary) 0%, #B91C1C 100%); 
            border-radius: 10px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 20px; color: #fff; 
            box-shadow: 0 4px 12px rgba(153, 27, 27, 0.3);
        }

        .topbar-title { 
            font-weight: 800; 
            color: var(--text-strong); 
            font-size: 18px; 
            letter-spacing: -0.5px;
        }

        .active-badge { 
            background: rgba(153, 27, 27, 0.15); 
            color: #EF4444; 
            font-size: 12px; 
            font-weight: 700; 
            padding: 6px 14px; 
            border-radius: 50rem; 
            border: 1px solid rgba(239, 68, 68, 0.2);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .active-badge i {
            animation: pulse-dot 1.5s infinite;
        }
        @keyframes pulse-dot {
            0% { opacity: 0.4; }
            50% { opacity: 1; }
            100% { opacity: 0.4; }
        }

        .nav-links { display: flex; gap: 12px; align-items: center; }
        
        .nav-link { 
            color: var(--text-muted); 
            text-decoration: none; 
            font-size: 13.5px; 
            font-weight: 600; 
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease; 
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .nav-link:hover { 
            color: var(--text-strong); 
            background: rgba(255,255,255,0.05);
        }

        .logout-btn { background: none; border: none; cursor: pointer; font-family: inherit; }
        .logout-btn .nav-link { color: #F87171; }
        .logout-btn .nav-link:hover { background: rgba(239, 68, 68, 0.1); color: #F87171; }

        /* === MAIN CONTAINER === */
        .main-container { padding: 32px; max-width: 1600px; margin: 0 auto; }
        
        /* === KITCHEN GRID === */
        .orders-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); 
            gap: 24px; 
        }

        /* === PREMIUM KITCHEN CARD === */
        .order-card { 
            background: var(--bg-card); 
            border-radius: var(--radius-lg); 
            border: 1px solid var(--border-color); 
            overflow: hidden; 
            display: flex; 
            flex-direction: column; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }
        .order-card:hover { 
            transform: translateY(-4px); 
            border-color: #475569; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
        }
        
        .card-header { 
            padding: 20px 24px; 
            background: rgba(0,0,0,0.15); 
            border-bottom: 1px solid var(--border-color); 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
        }
        
        .order-meta { 
            font-size: 11px; 
            font-weight: 700; 
            color: var(--text-muted); 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .customer-name { 
            font-size: 19px; 
            font-weight: 800; 
            color: var(--text-strong); 
            margin-top: 6px; 
            letter-spacing: -0.5px;
        }
        
        .table-badge { 
            font-size: 13px; 
            font-weight: 800; 
            color: #FFFFFF; 
            background: var(--brand-gradient); 
            padding: 4px 12px; 
            border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(153, 27, 27, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .card-body { padding: 24px; flex: 1; }
        
        .item-list { 
            display: flex; 
            flex-direction: column; 
            gap: 14px; 
            margin-bottom: 20px; 
        }
        
        .item-row { display: flex; gap: 14px; align-items: flex-start; }
        
        .item-qty { 
            width: 32px; height: 32px; 
            background: rgba(255,255,255,0.06); 
            color: #FFFFFF; 
            border-radius: 8px; 
            display: flex; align-items: center; justify-content: center; 
            font-weight: 800; font-size: 15px; 
            flex-shrink: 0; 
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .item-name { font-size: 15.5px; font-weight: 700; color: var(--text-strong); }
        
        .item-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
        
        .tag { 
            font-size: 10px; 
            font-weight: 700; 
            text-transform: uppercase; 
            padding: 4px 8px; 
            border-radius: 6px; 
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .tag-pedas { background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.2); }
        .tag-hot   { background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.2); }
        .tag-catatan { background: rgba(148, 163, 184, 0.15); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.2); }

        /* === TIMER AREA === */
        .timer-strip { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            background: rgba(0,0,0,0.25); 
            padding: 12px 18px; 
            border-radius: var(--radius-md); 
            margin-top: 20px; 
            border: 1px solid var(--border-color); 
        }
        
        .timer-label { font-size: 11px; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em; }
        .timer-val { font-size: 15.5px; font-weight: 800; color: var(--text-strong); }

        /* Overdue Red Glow */
        .pulse-red {
            border-color: rgba(239, 68, 68, 0.4);
            background: rgba(239, 68, 68, 0.04);
            animation: red-glow 1.5s infinite alternate;
        }
        @keyframes red-glow {
            from { box-shadow: 0 0 4px rgba(239, 68, 68, 0.1); }
            to { box-shadow: 0 0 12px rgba(239, 68, 68, 0.3); }
        }

        /* === FOOTER BUTTON === */
        .card-footer { padding: 16px 24px; border-top: 1px solid var(--border-color); background: rgba(0,0,0,0.1); }
        
        .btn-status { 
            width: 100%; 
            padding: 14px; 
            border: none; 
            border-radius: 10px; 
            font-family: inherit; 
            font-size: 13px; 
            font-weight: 800; 
            cursor: pointer; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            transition: all 0.2s ease; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-status:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
        }
        .btn-status:active { 
            transform: translateY(0);
        }
        
        .btn-menunggu { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: #000; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25); }
        .btn-diproses { background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); }
        .btn-siap     { background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); color: #fff; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.25); }
        .btn-selesai  { background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: #fff; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); }

        /* === EMPTY STATE === */
        .empty-state { 
            text-align: center; 
            padding: 120px 0; 
            grid-column: 1 / -1; 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .empty-icon { 
            font-size: 64px; 
            margin-bottom: 20px; 
            color: var(--text-muted);
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .empty-text { font-size: 18px; color: var(--text-muted); font-weight: 600; }
        
        /* === MOBILE RESPONSIVENESS === */
        @media (max-width: 768px) {
            .main-container { padding: 16px; }
            .topbar { padding: 0 16px; }
            .nav-link span { display: none; }
            .orders-grid { gap: 16px; }
        }
    </style>
</head>
<body>
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <div class="logo-box"><i class="fas fa-fire-burner"></i></div>
            <h1 class="topbar-title">Dapur Panel</h1>
            <span class="active-badge">
                <i class="fas fa-circle text-danger" style="font-size: 8px;"></i>
                <span>{{ $orders->count() }} Antrian</span>
            </span>
        </div>
        <nav class="nav-links">
            <span style="font-size: 13px; color: var(--text-muted); font-weight: 700; margin-right: 8px;"><i class="fas fa-user-circle me-1"></i>{{ session('staff_nama') }}</span>
            <a href="{{ route('kasir') }}" class="nav-link"><i class="fas fa-cash-register"></i> <span>Ke Kasir</span></a>
            <form method="POST" action="{{ route('logout') }}" class="logout-btn">
                @csrf
                <button type="submit" class="nav-link logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Keluar</span></button>
            </form>
        </nav>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <div class="orders-grid">
            @forelse($orders as $order)
                @php
                    $sp = $order->status_pelayanan;
                    $items = $order->detail_json ?? [];
                    $menitLalu = round((time() - $order->created_at->timestamp) / 60);
                    $sisa = max(0, $order->estimasi_menit - $menitLalu);
                    $isOver = $menitLalu > $order->estimasi_menit;
                    $nextMap = ['Menunggu'=>'Diproses','Diproses'=>'Siap','Siap'=>'Selesai'];
                    $next = $nextMap[$sp] ?? null;
                @endphp
                <div class="order-card">
                    <!-- Header -->
                    <div class="card-header">
                        <div>
                            <span class="order-meta"><i class="far fa-clock"></i> #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} · {{ $order->created_at->format('H:i') }}</span>
                            <h2 class="customer-name">{{ $order->nama_pelanggan }}</h2>
                        </div>
                        <span class="table-badge"><i class="fas fa-chair"></i> MEJA {{ $order->nomor_meja }}</span>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <div class="item-list">
                            @foreach($items as $it)
                                <div class="item-row">
                                    <div class="item-qty">{{ $it['qty'] }}</div>
                                    <div style="flex:1;">
                                        <div class="item-name">{{ $it['nama'] }}</div>
                                        <div class="item-tags">
                                            @if(!empty($it['pedas']) && $it['pedas'] !== '-' && $it['pedas'] !== 'Tidak Pedas')
                                                <span class="tag {{ $it['pedas'] == 'Ekstra Pedas' ? 'tag-hot' : 'tag-pedas' }}">
                                                    <i class="fas fa-pepper-hot"></i> {{ $it['pedas'] }}
                                                </span>
                                            @endif
                                            @if(!empty($it['catatan']))
                                                <span class="tag tag-catatan">
                                                    <i class="fas fa-note-sticky"></i> {{ $it['catatan'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Timer strip with adaptive pulsing colors -->
                        <div class="timer-strip {{ $isOver ? 'pulse-red' : '' }}">
                            <span class="timer-label"><i class="fas fa-hourglass-half"></i> {{ $isOver ? 'TERLAMBAT' : 'SISA WAKTU' }}</span>
                            <span class="timer-val" style="color: {{ $isOver ? '#EF4444' : '#10B981' }}">
                                {{ $isOver ? '+' . ($menitLalu - $order->estimasi_menit) : $sisa }} MENIT
                            </span>
                        </div>
                    </div>

                    <!-- Footer Action Button -->
                    @if($next)
                        <div class="card-footer">
                            <form method="POST" action="{{ route('dapur.status') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $order->id }}">
                                <input type="hidden" name="status" value="{{ $next }}">
                                <button type="submit" class="btn-status btn-{{ strtolower($sp) }}">
                                    @if($sp == 'Menunggu') 
                                        <i class="fas fa-play"></i> PROSES PESANAN 
                                    @elseif($sp == 'Diproses') 
                                        <i class="fas fa-bell"></i> SIAP DISAJIKAN 
                                    @elseif($sp == 'Siap') 
                                        <i class="fas fa-check"></i> SELESAI 
                                    @endif
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-hat-chef text-muted"></i></div>
                    <p class="empty-text">Dapur Sedang Kosong. Tidak Ada Antrian.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        setTimeout(() => location.reload(), 10000);
    </script>
</body>
</html>