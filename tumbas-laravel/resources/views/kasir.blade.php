<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kasir — Rumah Makan A</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #991B1B;
            --brand-hover: #7F1D1D;
            --brand-light: #FEF2F2;
            --bg-app: #F3F4F6;
            --bg-surface: #FFFFFF;
            --bg-input: #F9FAFB;
            --text-strong: #111827;
            --text-base: #374151;
            --text-muted: #6B7280;
            --border-color: #E5E7EB;
            --radius-md: 10px;
            --radius-lg: 16px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg-app); color: var(--text-base); min-height: 100vh; padding-top: 64px; }

        .topbar { position: fixed; top: 0; left: 0; right: 0; height: 64px; background: var(--bg-surface); display: flex; align-items: center; justify-content: space-between; padding: 0 24px; border-bottom: 1px solid var(--border-color); z-index: 100; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 32px; height: 32px; background: var(--brand-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #fff; }
        .topbar-title { font-weight: 700; color: var(--text-strong); font-size: 16px; }

        .nav-links { display: flex; gap: 16px; align-items: center; }
        .nav-link { color: var(--text-muted); text-decoration: none; font-size: 13px; font-weight: 600; transition: color 0.2s; }
        .nav-link:hover { color: var(--text-strong); }
        .logout-btn { background: none; border: none; cursor: pointer; font-family: inherit; }

        .main-container { padding: 24px; max-width: 1200px; margin: 0 auto; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--bg-surface); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; background: var(--bg-app); display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .stat-val { font-size: 20px; font-weight: 700; color: var(--text-strong); }

        .tabs { display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; }
        .tab { padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; color: var(--text-muted); cursor: pointer; transition: all 0.2s; border: none; background: none; }
        .tab.active { background: var(--brand-primary); color: #fff; }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        .card { background: var(--bg-surface); border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 24px; margin-bottom: 24px; }
        .card-title { font-size: 16px; font-weight: 700; color: var(--text-strong); margin-bottom: 20px; }

        .filter-bar { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
        .input-control { padding: 10px 14px; background: var(--bg-input); border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 13px; outline: none; }
        .btn-cari { padding: 10px 20px; background: var(--brand-primary); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }

        .order-table { width: 100%; border-collapse: collapse; }
        .order-table th { text-align: left; padding: 12px; font-size: 12px; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border-color); }
        .order-table td { padding: 16px 12px; border-bottom: 1px solid var(--bg-app); font-size: 14px; vertical-align: top; }
        .order-id { font-weight: 700; color: var(--text-strong); }
        .status-badge { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
        .badge-menunggu { background: #FEF3C7; color: #92400E; }
        .badge-diproses { background: #DBEAFE; color: #1E40AF; }
        .badge-siap { background: #EDE9FE; color: #5B21B6; }
        .badge-selesai { background: #D1FAE5; color: #065F46; }
        .badge-belum { background: #FEE2E2; color: #B91C1C; }
        .badge-lunas { background: #D1FAE5; color: #065F46; }

        .action-btns { display: flex; gap: 8px; flex-wrap: wrap; }
        .btn-sm { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; border: 1px solid var(--border-color); background: var(--bg-surface); color: var(--text-base); }
        .btn-primary { background: var(--brand-primary); color: #fff; border: none; }
        .btn-success { background: #10B981; color: #fff; border: none; }
        .btn-danger { color: #EF4444; border-color: #FEE2E2; }

        .stock-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; }
        .stock-card { background: var(--bg-input); padding: 16px; border-radius: 8px; border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
        .stock-name { font-weight: 600; font-size: 14px; }
        .stock-price { font-size: 12px; color: var(--text-muted); }

        #strukOverlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        #strukBox { background: #fff; padding: 32px; border-radius: var(--radius-md); max-width: 400px; width: 90%; max-height: 90vh; overflow-y: auto; }
        .notif-toast { position: fixed; top: 80px; right: 24px; background: var(--text-strong); color: #fff; padding: 14px 20px; border-radius: 8px; transform: translateX(150%); transition: transform 0.4s; z-index: 1000; }
        .notif-toast.show { transform: translateX(0); }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-left">
            <div class="logo-box">💳</div>
            <h1 class="topbar-title">Kasir Panel</h1>
        </div>
        <nav class="nav-links">
            <span style="font-size: 12px; color: var(--text-muted);">{{ session('staff_nama') }}</span>
            @if(session('staff_role') == 'admin')
                <a href="{{ route('admin.menus.index') }}" class="nav-link">Kelola Menu</a>
                <a href="{{ route('admin.users.index') }}" class="nav-link">Kelola Staff</a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link">Kelola Pesanan</a>
            @endif
            <a href="{{ route('dapur') }}" class="nav-link">Ke Dapur</a>
            <form method="POST" action="{{ route('logout') }}" class="logout-btn">
                @csrf
                <button type="submit" class="nav-link logout-btn">Keluar</button>
            </form>
        </nav>
    </div>

    <div class="main-container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div>
                    <div class="stat-label">Total Pesanan</div>
                    <div class="stat-val">{{ $stats['total'] }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #F59E0B">⏳</div>
                <div>
                    <div class="stat-label">Sedang Antre</div>
                    <div class="stat-val">{{ $stats['menunggu'] + $stats['diproses'] }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #EF4444">💳</div>
                <div>
                    <div class="stat-label">Belum Lunas</div>
                    <div class="stat-val">{{ $stats['belum_bayar'] }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #10B981">💰</div>
                <div>
                    <div class="stat-label">Omzet Hari Ini</div>
                    <div class="stat-val">Rp {{ number_format($stats['pendapatan_hari'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab active" onclick="switchTab('pesanan', this)">Kelola Pesanan</button>
            <button class="tab" onclick="switchTab('stok', this)">Kelola Stok</button>
        </div>

        <div id="tab-pesanan" class="tab-content active">
            <div class="card">
                <div class="filter-bar">
                    <form method="GET" style="display:flex; gap:12px; flex:1;">
                        <input type="text" name="q" class="input-control" placeholder="Cari nama atau meja..." value="{{ request('q') }}" style="flex:1;">
                        <select name="fs" class="input-control">
                            <option value="">Semua Status</option>
                            @foreach(['Menunggu','Diproses','Siap','Selesai'] as $s)
                                <option value="{{ $s }}" {{ request('fs') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-cari">Cari</button>
                    </form>
                </div>

                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Pesanan</th>
                            <th>Detail Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $nextMap = ['Menunggu'=>'Diproses','Diproses'=>'Siap','Siap'=>'Selesai'];
                                $next = $nextMap[$order->status_pelayanan] ?? null;
                            @endphp
                            <tr>
                                <td>
                                    <div class="order-id">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    <div style="font-weight: 600; margin-top: 4px;">{{ $order->nama_pelanggan }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted);">Meja {{ $order->nomor_meja }}</div>
                                </td>
                                <td>
                                    @foreach($order->detail_json as $it)
                                        <div style="margin-bottom: 4px;">
                                            • {{ $it['nama'] }} (x{{ $it['qty'] }})
                                            @if(!empty($it['pedas']) && $it['pedas'] != '-') <small style="color:var(--brand-primary)">[{{ $it['pedas'] }}]</small> @endif
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <div style="font-weight: 700;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                                    <span class="status-badge badge-{{ $order->status_bayar == 'Sudah Bayar' ? 'lunas' : 'belum' }}" style="margin-top: 4px; display: inline-block;">
                                        {{ $order->status_bayar }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge badge-{{ strtolower($order->status_pelayanan) }}">
                                        {{ $order->status_pelayanan }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        @if($next)
                                            <form method="POST" action="{{ route('kasir.status') }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $order->id }}">
                                                <input type="hidden" name="status" value="{{ $next }}">
                                                <button type="submit" class="btn-sm btn-primary">→ {{ $next }}</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('kasir.bayar') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $order->id }}">
                                            <button type="submit" class="btn-sm {{ $order->status_bayar == 'Belum Bayar' ? 'btn-success' : '' }}">
                                                {{ $order->status_bayar == 'Belum Bayar' ? 'Lunas' : 'Batal Lunas' }}
                                            </button>
                                        </form>
                                        <button onclick="printStruk({{ $order->id }}, '{{ $order->nama_pelanggan }}', {{ $order->nomor_meja }}, '{{ $order->total_harga }}', {{ json_encode($order->detail_json) }})" class="btn-sm">Struk</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center; padding:48px; color:var(--text-muted);">Tidak ada pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab-stok" class="tab-content">
            <div class="card">
                <div class="card-title">Ketersediaan Menu</div>
                <div class="stock-grid">
                    @foreach($menus as $m)
                        <div class="stock-card">
                            <div>
                                <div class="stock-name">{{ $m->nama_item }}</div>
                                <div class="stock-price">Rp {{ number_format($m->harga, 0, ',', '.') }}</div>
                            </div>
                            <form method="POST" action="{{ route('kasir.stok') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $m->id }}">
                                <button type="submit" class="btn-sm {{ $m->status_tersedia == 'Habis' ? 'btn-danger' : 'btn-success' }}">
                                    {{ $m->status_tersedia == 'Habis' ? 'Habis' : 'Tersedia' }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="strukOverlay">
        <div id="strukBox"></div>
    </div>
    <div class="notif-toast" id="notif">Pemberitahuan: Pesanan Baru!</div>

    <script>
        function switchTab(id, btn) {
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-'+id).classList.add('active');
            btn.classList.add('active');
        }

        function printStruk(id, nama, meja, total, items) {
            let itemsHtml = items.map(it => `
                <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:13px;">
                    <span>${it.nama} (x${it.qty})</span>
                    <span>${Number(it.subtotal).toLocaleString('id-ID')}</span>
                </div>
            `).join('');

            document.getElementById('strukBox').innerHTML = `
                <div style="text-align:center; margin-bottom:24px;">
                    <h2 style="font-size:18px;">Rumah Makan A</h2>
                    <p style="font-size:12px; color:var(--text-muted);">Nota Pesanan #${String(id).padStart(4,'0')}</p>
                </div>
                <div style="margin-bottom:16px; font-size:14px;">
                    <div>Tamu: <strong>${nama}</strong></div>
                    <div>Meja: <strong>${meja}</strong></div>
                </div>
                <div style="border-top: 1px dashed #ccc; padding-top:12px; margin-bottom:12px;">${itemsHtml}</div>
                <div style="border-top: 1px solid #000; padding-top:12px; display:flex; justify-content:space-between; font-weight:700;">
                    <span>TOTAL</span>
                    <span>Rp ${Number(total).toLocaleString('id-ID')}</span>
                </div>
                <div style="margin-top:32px; display:flex; gap:8px;">
                    <button onclick="window.print()" style="flex:1; padding:10px; background:#000; color:#fff; border:none; border-radius:6px; font-weight:600;">Cetak</button>
                    <button onclick="document.getElementById('strukOverlay').style.display='none'" style="flex:1; padding:10px; background:#eee; border:none; border-radius:6px;">Tutup</button>
                </div>
            `;
            document.getElementById('strukOverlay').style.display = 'flex';
        }

        let lastId = {{ $maxId }};
        function checkNotif() {
            fetch(`{{ route('api.check') }}?last_id=${lastId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.count > 0) {
                        const n = document.getElementById('notif');
                        n.classList.add('show');
                        lastId = data.max_id;
                        setTimeout(() => location.reload(), 3000);
                    }
                });
        }
        setInterval(checkNotif, 8000);
    </script>
</body>
</html>
