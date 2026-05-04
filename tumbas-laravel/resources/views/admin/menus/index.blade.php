<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Menu — Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #991B1B;
            --bg-app: #F3F4F6;
            --bg-surface: #FFFFFF;
            --text-strong: #111827;
            --text-muted: #6B7280;
            --border-color: #E5E7EB;
            --radius-md: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg-app); color: var(--text-base); padding: 24px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: var(--brand-primary); color: #fff; }
        .btn-outline { border: 1px solid var(--border-color); color: var(--text-muted); background: #fff; }
        .card { background: #fff; border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 20px; background: #F9FAFB; border-bottom: 1px solid var(--border-color); font-size: 12px; text-transform: uppercase; color: var(--text-muted); }
        td { padding: 16px 20px; border-bottom: 1px solid #F3F4F6; font-size: 14px; }
        .menu-img { width: 48px; height: 48px; border-radius: 6px; object-fit: cover; }
        .badge { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; }
        .badge-tersedia { background: #D1FAE5; color: #065F46; }
        .badge-habis { background: #FEE2E2; color: #B91C1C; }
        .actions { display: flex; gap: 8px; }
        .alert { padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1 style="font-size: 20px; font-weight: 700;">Manajemen Menu</h1>
            <p style="font-size: 13px; color: var(--text-muted);">Kelola daftar hidangan makanan dan minuman.</p>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('kasir') }}" class="btn btn-outline">Kembali ke Kasir</a>
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">+ Tambah Menu</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menus as $m)
                <tr>
                    <td style="display:flex; align-items:center; gap:12px;">
                        <img src="{{ asset('img/'.$m->gambar) }}" class="menu-img" onerror="this.src='https://via.placeholder.com/48'">
                        <div>
                            <div style="font-weight: 600;">{{ $m->nama_item }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ Str::limit($m->deskripsi, 40) }}</div>
                        </div>
                    </td>
                    <td>{{ $m->kategori }}</td>
                    <td style="font-weight: 700;">Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ $m->status_tersedia == 'Tersedia' ? 'tersedia' : 'habis' }}">
                            {{ $m->status_tersedia }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('admin.menus.edit', $m->id) }}" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">Edit</a>
                            <form action="{{ route('admin.menus.delete', $m->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px; color: #EF4444; border-color: #FEE2E2;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
