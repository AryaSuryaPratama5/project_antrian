<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Dapur — Rumah Makan A</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #991B1B;
            --brand-light: #FEF2F2;
            --bg-app: #0F172A;
            --bg-card: #1E293B;
            --text-strong: #F8FAFC;
            --text-base: #CBD5E1;
            --text-muted: #64748B;
            --border-color: #334155;
            --menunggu: #F59E0B;
            --diproses: #3B82F6;
            --siap: #8B5CF6;
            --radius-md: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg-app); color: var(--text-base); min-height: 100vh; padding-top: 64px; }

        .topbar { position: fixed; top: 0; left: 0; right: 0; height: 64px; background: var(--bg-card); display: flex; align-items: center; justify-content: space-between; padding: 0 24px; border-bottom: 1px solid var(--border-color); z-index: 100; }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 32px; height: 32px; background: var(--brand-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #fff; }
        .topbar-title { font-weight: 700; color: var(--text-strong); font-size: 16px; }
        .active-badge { background: var(--brand-primary); color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }

        .nav-links { display: flex; gap: 16px; align-items: center; }
        .nav-link { color: var(--text-muted); text-decoration: none; font-size: 13px; font-weight: 600; transition: color 0.2s; }
        .nav-link:hover { color: var(--text-strong); }
        .logout-btn { background: none; border: none; cursor: pointer; font-family: inherit; }

        .main-container { padding: 24px; max-width: 1400px; margin: 0 auto; }
        .orders-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }

        .order-card { background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s; }
        .order-card:hover { transform: translateY(-2px); border-color: var(--text-muted); }
        
        .card-header { padding: 16px 20px; background: rgba(0,0,0,0.2); border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: flex-start; }
        .order-meta { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .customer-name { font-size: 18px; font-weight: 700; color: var(--text-strong); margin-top: 4px; }
        .table-badge { font-size: 12px; font-weight: 700; color: var(--brand-primary); background: var(--brand-light); padding: 2px 8px; border-radius: 4px; }

        .card-body { padding: 20px; flex: 1; }
        .item-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; }
        .item-row { display: flex; gap: 12px; }
        .item-qty { width: 28px; height: 28px; background: var(--border-color); color: var(--text-strong); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0; }
        .item-name { font-size: 15px; font-weight: 600; color: var(--text-strong); }
        .item-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px; }
        .tag { font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 2px 6px; border-radius: 4px; }
        .tag-pedas { background: #FEF3C7; color: #92400E; }
        .tag-hot   { background: #FEE2E2; color: #B91C1C; }
        .tag-catatan { background: #E2E8F0; color: #475569; }

        .timer-strip { display: flex; align-items: center; justify-content: space-between; background: rgba(0,0,0,0.1); padding: 10px 16px; border-radius: 8px; margin-top: 16px; border: 1px solid var(--border-color); }
        .timer-label { font-size: 11px; color: var(--text-muted); font-weight: 600; }
        .timer-val { font-size: 15px; font-weight: 700; color: var(--text-strong); }

        .card-footer { padding: 16px 20px; border-top: 1px solid var(--border-color); }
        .btn-status { width: 100%; padding: 12px; border: none; border-radius: 8px; font-family: inherit; font-size: 13px; font-weight: 700; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: opacity 0.2s; }
        .btn-status:active { opacity: 0.8; }
        
        .btn-menunggu { background: var(--menunggu); color: #000; }
        .btn-diproses { background: var(--diproses); color: #fff; }
        .btn-siap     { background: var(--siap); color: #fff; }
        .btn-selesai  { background: #10B981; color: #fff; }

        .empty-state { text-align: center; padding: 100px 0; grid-column: 1 / -1; }
        .empty-icon { font-size: 48px; margin-bottom: 16px; opacity: 0.5; }
        .empty-text { font-size: 18px; color: var(--text-muted); }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-left">
            <div class="logo-box">🍽️</div>
            <h1 class="topbar-title">Dapur Panel</h1>
            <span class="active-badge">{{ $orders->count() }} Antrian</span>
        </div>
        <nav class="nav-links">
            <span style="font-size: 12px; color: var(--text-muted);">{{ session('staff_nama') }}</span>
            <a href="{{ route('kasir') }}" class="nav-link">Ke Kasir</a>
            <form method="POST" action="{{ route('logout') }}" class="logout-btn">
                @csrf
                <button type="submit" class="nav-link logout-btn">Keluar</button>
            </form>
        </nav>
    </div>

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
                    <div class="card-header">
                        <div>
                            <span class="order-meta">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} · {{ $order->created_at->format('H:i') }}</span>
                            <h2 class="customer-name">{{ $order->nama_pelanggan }}</h2>
                        </div>
                        <span class="table-badge">MEJA {{ $order->nomor_meja }}</span>
                    </div>

                    <div class="card-body">
                        <div class="item-list">
                            @foreach($items as $it)
                                <div class="item-row">
                                    <div class="item-qty">{{ $it['qty'] }}</div>
                                    <div style="flex:1;">
                                        <div class="item-name">{{ $it['nama'] }}</div>
                                        <div class="item-tags">
                                            @if(!empty($it['pedas']) && $it['pedas'] !== '-' && $it['pedas'] !== 'Tidak Pedas')
                                                <span class="tag {{ $it['pedas'] == 'Ekstra Pedas' ? 'tag-hot' : 'tag-pedas' }}">{{ $it['pedas'] }}</span>
                                            @endif
                                            @if(!empty($it['catatan']))
                                                <span class="tag tag-catatan">{{ $it['catatan'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="timer-strip">
                            <span class="timer-label">{{ $isOver ? 'TERLAMBAT' : 'SISA WAKTU' }}</span>
                            <span class="timer-val" style="color: {{ $isOver ? '#EF4444' : '#fff' }}">
                                {{ $isOver ? '+' . ($menitLalu - $order->estimasi_menit) : $sisa }} MENIT
                            </span>
                        </div>
                    </div>

                    @if($next)
                        <div class="card-footer">
                            <form method="POST" action="{{ route('dapur.status') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $order->id }}">
                                <input type="hidden" name="status" value="{{ $next }}">
                                <button type="submit" class="btn-status btn-{{ strtolower($sp) }}">
                                    @if($sp == 'Menunggu') PROSES PESANAN @elseif($sp == 'Diproses') SIAP DISAJIKAN @elseif($sp == 'Siap') SELESAI @endif
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">🍳</div>
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
