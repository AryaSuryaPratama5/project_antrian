<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kasir — Rumah Makan A</title>
    
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
            --brand-gradient: linear-gradient(135deg, #991B1B 0%, #B91C1C 100%);
            
            --bg-app: #F8FAFC;
            --bg-surface: #FFFFFF;
            --bg-input: #F1F5F9;
            
            --text-strong: #0F172A;
            --text-base: #334155;
            --text-muted: #64748B;
            --text-placeholder: #94A3B8;
            
            --border-color: #E2E8F0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            
            --radius-md: 12px;
            --radius-lg: 16px;
            
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }

        body {
            background-color: var(--bg-app);
            color: var(--text-base);
            padding-top: 80px;
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.01em;
        }

        /* === TOPBAR (Elegant Glassmorphism) === */
        .topbar {
            position: fixed; top: 0; left: 0; right: 0;
            height: 70px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 32px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .topbar-left { display: flex; align-items: center; gap: 14px; }
        
        .logo-box {
            width: 40px; height: 40px;
            background: var(--brand-gradient);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
            box-shadow: 0 4px 10px rgba(153, 27, 27, 0.25);
        }

        .topbar-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-strong);
            letter-spacing: -0.5px;
        }

        .nav-links { display: flex; gap: 8px; align-items: center; }
        
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
            color: var(--brand-primary);
            background-color: var(--brand-light);
        }

        .logout-btn {
            background: none; border: none; cursor: pointer; font-family: inherit;
        }
        
        .logout-btn .nav-link {
            color: #EF4444;
        }
        .logout-btn .nav-link:hover {
            background-color: #FEF2F2;
            color: #DC2626;
        }

        .user-chip {
            background-color: var(--bg-input);
            padding: 6px 14px;
            border-radius: 50rem;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-strong);
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border-color);
            margin-right: 8px;
        }
        
        .user-chip i {
            color: var(--brand-primary);
        }

        /* === MAIN LAYOUT === */
        .main-container {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* === PREMIUM STATS GRID === */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-surface);
            padding: 24px;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(226, 232, 240, 0.8);
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: var(--shadow-md);
            transition: transform 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .stat-icon.primary { background: #E0F2FE; color: #0369A1; }
        .stat-icon.warning { background: #FEF3C7; color: #B45309; }
        .stat-icon.danger  { background: #FEE2E2; color: #991B1B; }
        .stat-icon.success { background: #D1FAE5; color: #065F46; }

        .stat-label {
            font-size: 11.5px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .stat-val {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-strong);
            line-height: 1.2;
        }

        /* === MODERN UNDERLINED TABS === */
        .tabs {
            display: flex;
            gap: 24px;
            margin-bottom: 32px;
            border-bottom: 1px solid var(--border-color);
        }

        .tab {
            padding: 12px 4px;
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            background: none;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .tab:hover {
            color: var(--text-strong);
        }

        .tab.active {
            color: var(--brand-primary);
            border-color: var(--brand-primary);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* === MAIN CARD CONTAINER === */
        .card {
            background: var(--bg-surface);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(226, 232, 240, 0.8);
            padding: 24px;
            box-shadow: var(--shadow-md);
            margin-bottom: 32px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-strong);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-title i {
            color: var(--brand-primary);
        }

        /* === FILTER BAR === */
        .filter-bar {
            margin-bottom: 24px;
        }

        .input-control {
            padding: 12px 16px;
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;
            outline: none;
            color: var(--text-strong);
            transition: all 0.2s ease;
        }
        .input-control:focus {
            background-color: var(--bg-surface);
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px var(--brand-light);
        }

        .btn-cari {
            padding: 12px 24px;
            background: var(--brand-gradient);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(153, 27, 27, 0.2);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-cari:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(153, 27, 27, 0.3);
        }

        /* === ORDER TABLE DESIGN === */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
        }

        .order-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        
        .order-table th {
            background-color: #F8FAFC;
            text-align: left;
            padding: 14px 20px;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }

        .order-table td {
            padding: 18px 20px;
            border-bottom: 1px solid #F1F5F9;
            font-size: 14px;
            vertical-align: middle;
        }

        .order-table tbody tr:hover td {
            background-color: #F8FAFC;
        }

        .order-id {
            font-weight: 800;
            color: var(--text-strong);
            font-size: 14.5px;
        }

        /* Status Badge */
        .status-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 50rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .badge-menunggu { background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }
        .badge-diproses { background: #DBEAFE; color: #1E40AF; border: 1px solid #BFDBFE; }
        .badge-siap { background: #EDE9FE; color: #5B21B6; border: 1px solid #DDD6FE; }
        .badge-selesai { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
        .badge-belum { background: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5; }
        .badge-lunas { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }

        /* Action Buttons */
        .action-btns { display: flex; gap: 8px; flex-wrap: wrap; }
        
        .btn-sm {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid var(--border-color);
            background: var(--bg-surface);
            color: var(--text-base);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-sm:hover {
            border-color: #CBD5E1;
            background-color: #F8FAFC;
        }
        
        .btn-primary {
            background: var(--brand-gradient);
            color: #fff;
            border: none;
            box-shadow: 0 2px 8px rgba(153, 27, 27, 0.15);
        }
        .btn-primary:hover {
            background: var(--brand-hover);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-success {
            background: #10B981;
            color: #fff;
            border: none;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
        }
        .btn-success:hover {
            background: #059669;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-danger {
            color: #EF4444;
            border-color: #FEE2E2;
            background-color: #FEF2F2;
        }
        .btn-danger:hover {
            background-color: #FEE2E2;
            border-color: #FCA5A5;
        }

        /* === STOCKS TAB === */
        .stock-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .stock-card {
            background: var(--bg-input);
            padding: 20px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }
        .stock-card:hover {
            background: var(--bg-surface);
            box-shadow: var(--shadow-sm);
            border-color: #CBD5E1;
        }

        .stock-name { font-weight: 700; font-size: 14.5px; color: var(--text-strong); }
        .stock-price { font-size: 13px; color: var(--text-muted); font-weight: 500; margin-top: 2px; }

        /* === STRUK MODAL OVERLAY === */
        #strukOverlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1000; align-items: center; justify-content: center;
        }

        #strukBox {
            background: #fff;
            padding: 32px;
            border-radius: var(--radius-lg);
            max-width: 420px; width: 90%;
            max-height: 90vh; overflow-y: auto;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
        }

        /* === NOTIFICATION TOAST === */
        .notif-toast {
            position: fixed; top: 90px; right: 24px;
            background: #0F172A; color: #fff;
            padding: 16px 24px; border-radius: 12px;
            transform: translateX(150%); transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            box-shadow: var(--shadow-lg);
            display: flex; align-items: center; gap: 12px;
            font-weight: 600; font-size: 14px;
            border-left: 4px solid var(--brand-primary);
        }
        .notif-toast.show { transform: translateX(0); }
        .notif-toast i { color: #F59E0B; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.15); } 100% { transform: scale(1); } }

        /* === RESPONSIVE MOBILE FIX === */
        @media (max-width: 768px) {
            .main-container { padding: 16px; }
            .topbar { padding: 0 16px; }
            .topbar-badge span { display: none; }
            
            .nav-link { padding: 6px 10px; font-size: 12.5px; }
            .user-chip { display: none; }
            
            .stats-grid { gap: 16px; }
            .tabs { gap: 12px; }
            .tab { font-size: 13px; }
        }
    </style>
</head>
<body>

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <div class="logo-box"><i class="fas fa-cash-register"></i></div>
            <h1 class="topbar-title">Kasir Panel</h1>
        </div>
        <nav class="nav-links">
            <div class="user-chip">
                <i class="fas fa-user-circle"></i>
                <span>{{ session('staff_nama') }}</span>
            </div>
            @if(session('staff_role') == 'admin')
                <a href="{{ route('admin.menus.index') }}" class="nav-link"><i class="fas fa-utensils"></i> <span>Kelola Menu</span></a>
                <a href="{{ route('admin.users.index') }}" class="nav-link"><i class="fas fa-users-cog"></i> <span>Kelola Staff</span></a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link"><i class="fas fa-file-invoice"></i> <span>Kelola Pesanan</span></a>
            @endif
            <a href="{{ route('dapur') }}" class="nav-link"><i class="fas fa-hamburger"></i> <span>Ke Dapur</span></a>
            <form method="POST" action="{{ route('logout') }}" class="logout-btn">
                @csrf
                <button type="submit" class="nav-link logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Keluar</span></button>
            </form>
        </nav>
    </div>

    <div class="main-container">
        
        <!-- Summary Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-receipt"></i>
                </div>
                <div>
                    <div class="stat-label">Total Pesanan</div>
                    <div class="stat-val">{{ $stats['total'] }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="stat-label">Sedang Antre</div>
                    <div class="stat-val">{{ $stats['menunggu'] + $stats['diproses'] }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon danger">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div>
                    <div class="stat-label">Belum Lunas</div>
                    <div class="stat-val">{{ $stats['belum_bayar'] }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div class="stat-label">Omzet Hari Ini</div>
                    <div class="stat-val">Rp {{ number_format($stats['pendapatan_hari'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Tab Nav -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab('pesanan', this)">
                <i class="fas fa-clipboard-list"></i> Kelola Pesanan
            </button>
            <button class="tab" onclick="switchTab('stok', this)">
                <i class="fas fa-boxes"></i> Kelola Stok
            </button>
        </div>

        <!-- Tab 1: Orders -->
        <div id="tab-pesanan" class="tab-content active">
            <div class="card">
                
                <!-- Filter Bar -->
                <div class="filter-bar">
                    <form method="GET" style="display:flex; gap:12px; flex-wrap: wrap;">
                        <input type="text" name="q" class="input-control" placeholder="Cari nama pelanggan atau nomor meja..." value="{{ request('q') }}" style="flex:1; min-width: 250px;">
                        <select name="fs" class="input-control" style="min-width: 160px;">
                            <option value="">Semua Status</option>
                            @foreach(['Menunggu','Diproses','Siap','Selesai'] as $s)
                                <option value="{{ $s }}" {{ request('fs') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-cari"><i class="fas fa-filter"></i> Cari</button>
                    </form>
                </div>

                <!-- Table Content -->
                <div class="table-responsive">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Pesanan</th>
                                <th>Detail Items</th>
                                <th>Total & Pembayaran</th>
                                <th>Status Antrean</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                @php
                                    $nextMap = ['Menunggu'=>'Diproses','Diproses'=>'Siap','Siap'=>'Selesai'];
                                    $next = $nextMap[$order->status_pelayanan] ?? null;
                                    
                                    // Status Pelayanan Icons and Classes for visualization
                                    $serviceIcon = match($order->status_pelayanan) {
                                        'Menunggu' => 'fa-hourglass-start',
                                        'Diproses' => 'fa-spinner fa-spin',
                                        'Siap' => 'fa-bell',
                                        'Selesai' => 'fa-check-circle',
                                        default => 'fa-question'
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <div class="order-id">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        <div style="font-weight: 700; margin-top: 6px; color: var(--text-strong);">{{ $order->nama_pelanggan }}</div>
                                        <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;"><i class="fas fa-chair opacity-75"></i> Meja {{ $order->nomor_meja }}</div>
                                    </td>
                                    <td>
                                        @foreach($order->detail_json as $it)
                                            <div style="margin-bottom: 6px; font-size: 13.5px;">
                                                <span style="font-weight: 600;">{{ $it['nama'] }}</span> 
                                                <span class="text-muted">(x{{ $it['qty'] }})</span>
                                                @if(!empty($it['pedas']) && $it['pedas'] != '-') 
                                                    <span class="badge bg-danger bg-opacity-10 text-danger ms-1" style="text-transform: none; padding: 2px 6px;">{{ $it['pedas'] }}</span> 
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div style="font-weight: 800; color: var(--brand-primary); font-size: 15px;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                                        <span class="status-badge badge-{{ $order->status_bayar == 'Sudah Bayar' ? 'lunas' : 'belum' }}" style="margin-top: 6px;">
                                            <i class="fas {{ $order->status_bayar == 'Sudah Bayar' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            {{ $order->status_bayar }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge badge-{{ strtolower($order->status_pelayanan) }}">
                                            <i class="fas {{ $serviceIcon }}"></i>
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
                                                    <button type="submit" class="btn-sm btn-primary">
                                                        <i class="fas fa-arrow-right"></i> {{ $next }}
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('kasir.bayar') }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $order->id }}">
                                                <button type="submit" class="btn-sm {{ $order->status_bayar == 'Belum Bayar' ? 'btn-success' : '' }}">
                                                    <i class="fas {{ $order->status_bayar == 'Belum Bayar' ? 'fa-check' : 'fa-undo' }}"></i>
                                                    {{ $order->status_bayar == 'Belum Bayar' ? 'Lunas' : 'Batal Lunas' }}
                                                </button>
                                            </form>
                                            <button onclick="printStruk({{ $order->id }}, '{{ $order->nama_pelanggan }}', {{ $order->nomor_meja }}, '{{ $order->total_harga }}', {{ json_encode($order->detail_json) }})" class="btn-sm">
                                                <i class="fas fa-print"></i> Struk
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" style="text-align:center; padding:56px; color:var(--text-muted);"><i class="fas fa-inbox fs-2 mb-3 d-block opacity-50"></i>Tidak ada data pesanan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Stocks -->
        <div id="tab-stok" class="tab-content">
            <div class="card">
                <div class="card-title"><i class="fas fa-store"></i> Ketersediaan Menu</div>
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
                                    <i class="fas {{ $m->status_tersedia == 'Habis' ? 'fa-times-circle' : 'fa-check-circle' }} me-1"></i>
                                    {{ $m->status_tersedia == 'Habis' ? 'Habis' : 'Tersedia' }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Overlay -->
    <div id="strukOverlay">
        <div id="strukBox"></div>
    </div>
    
    <!-- New Order Toast notification -->
    <div class="notif-toast" id="notif">
        <i class="fas fa-bell"></i>
        <span>Pemberitahuan: Pesanan Baru Masuk!</span>
    </div>

    <script>
        function switchTab(id, btn) {
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-'+id).classList.add('active');
            btn.classList.add('active');
        }

        function printStruk(id, nama, meja, total, items) {
            let itemsHtml = items.map(it => `
                <div style="display:flex; justify-content:space-between; margin-bottom:6px; font-size:13px; font-family:'Courier New', Courier, monospace;">
                    <span>${it.nama} (x${it.qty})</span>
                    <span>Rp ${Number(it.subtotal).toLocaleString('id-ID')}</span>
                </div>
            `).join('');

            document.getElementById('strukBox').innerHTML = `
                <div style="text-align:center; margin-bottom:24px; border-bottom: 2px dashed var(--border-color); padding-bottom: 16px;">
                    <h2 style="font-size:20px; font-weight: 800; color: var(--brand-primary); margin-bottom: 4px;">Rumah Makan A</h2>
                    <p style="font-size:12px; color:var(--text-muted); font-weight: 600;">Nota Belanja #${String(id).padStart(4,'0')}</p>
                </div>
                <div style="margin-bottom:16px; font-size:13.5px; color: var(--text-base);">
                    <div style="display:flex; justify-content:space-between; margin-bottom: 4px;">
                        <span>Pelanggan:</span>
                        <strong style="color: var(--text-strong);">${nama}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between;">
                        <span>Nomor Meja:</span>
                        <strong style="color: var(--text-strong);">${meja}</strong>
                    </div>
                </div>
                <div style="border-top: 1px dashed #ccc; padding-top:12px; margin-bottom:12px;">${itemsHtml}</div>
                <div style="border-top: 2px solid #0F172A; padding-top:12px; display:flex; justify-content:space-between; font-weight:800; font-size: 15px; color: var(--text-strong);">
                    <span>TOTAL</span>
                    <span>Rp ${Number(total).toLocaleString('id-ID')}</span>
                </div>
                <div style="text-align:center; margin-top:24px; font-size:11px; color: var(--text-muted); font-weight:600; line-height:1.4;">
                    <p>Terima kasih telah berkunjung!</p>
                    <p>Silakan simpan struk pembayaran ini.</p>
                </div>
                <div style="margin-top:32px; display:flex; gap:8px;">
                    <button onclick="window.print()" style="flex:1; padding:12px; background:var(--brand-gradient); color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer; font-size:13.5px;"><i class="fas fa-print me-1"></i> Cetak</button>
                    <button onclick="document.getElementById('strukOverlay').style.display='none'" style="flex:1; padding:12px; background:#F1F5F9; border:none; border-radius:8px; font-weight:700; color: var(--text-muted); cursor:pointer; font-size:13.5px;">Tutup</button>
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