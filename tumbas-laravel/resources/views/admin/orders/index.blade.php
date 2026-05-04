<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan — Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --brand-primary: #991B1B; --bg-app: #F3F4F6; --text-strong: #111827; --text-muted: #6B7280; --border-color: #E5E7EB; --radius-md: 10px; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-app); padding: 24px; }
        .container { max-width: 1100px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-outline { border: 1px solid var(--border-color); color: var(--text-muted); background: #fff; }
        .card { background: #fff; border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden; }
        .filter-bar { padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; gap: 12px; }
        .input-control { padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit; font-size: 13px; outline: none; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 20px; background: #F9FAFB; border-bottom: 1px solid var(--border-color); font-size: 11px; text-transform: uppercase; color: var(--text-muted); }
        td { padding: 16px 20px; border-bottom: 1px solid #F3F4F6; font-size: 13px; vertical-align: top; }
        .status-badge { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; }
        .badge-menunggu { background: #FEF3C7; color: #92400E; }
        .badge-diproses { background: #DBEAFE; color: #1E40AF; }
        .badge-siap { background: #EDE9FE; color: #5B21B6; }
        .badge-selesai { background: #D1FAE5; color: #065F46; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1 style="font-size: 20px; font-weight: 700;">Manajemen Pesanan</h1>
            <p style="font-size: 13px; color: var(--text-muted);">Hapus atau kelola riwayat pesanan pelanggan.</p>
        </div>
        <a href="{{ route('kasir') }}" class="btn btn-outline">Kembali ke Kasir</a>
    </div>

    @if(session('success'))
        <div style="padding:12px 20px; background:#D1FAE5; color:#065F46; border:1px solid #A7F3D0; border-radius:8px; margin-bottom:20px; font-size:14px;">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="filter-bar">
            <form method="GET" style="display:flex; gap:10px; flex:1;">
                <input type="text" name="q" class="input-control" placeholder="Cari Nama / ID Pesanan..." value="{{ request('q') }}" style="flex:1;">
                <button type="submit" class="btn btn-outline">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">Reset</a>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Detail Item</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                <tr>
                    <td>#{{ str_pad($o->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div style="font-weight: 600;">{{ $o->nama_pelanggan }}</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Meja {{ $o->nomor_meja }} · {{ $o->created_at->format('d/m H:i') }}</div>
                    </td>
                    <td>
                        @foreach($o->detail_json as $it)
                            <div style="margin-bottom:2px;">• {{ $it['nama'] }} (x{{ $it['qty'] }})</div>
                        @endforeach
                    </td>
                    <td style="font-weight: 700;">Rp {{ number_format($o->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge badge-{{ strtolower($o->status_pelayanan) }}">{{ $o->status_pelayanan }}</span>
                    </td>
                    <td>
                        <form action="{{ route('admin.orders.delete', $o->id) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini secara permanen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline" style="color:#EF4444; border-color:#FEE2E2; padding:6px 12px; font-size:12px;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding: 16px 20px;">
            {{ $orders->links() }}
        </div>
    </div>
</div>
</body>
</html>
