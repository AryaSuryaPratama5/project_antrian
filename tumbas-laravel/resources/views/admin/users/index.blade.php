<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Staff — Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --brand-primary: #991B1B; --bg-app: #F3F4F6; --text-strong: #111827; --text-muted: #6B7280; --border-color: #E5E7EB; --radius-md: 10px; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-app); padding: 24px; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: var(--brand-primary); color: #fff; }
        .btn-outline { border: 1px solid var(--border-color); color: var(--text-muted); background: #fff; }
        .card { background: #fff; border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 20px; background: #F9FAFB; border-bottom: 1px solid var(--border-color); font-size: 12px; text-transform: uppercase; color: var(--text-muted); }
        td { padding: 16px 20px; border-bottom: 1px solid #F3F4F6; font-size: 14px; }
        .role-badge { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; }
        .role-admin { background: #E0E7FF; color: #3730A3; }
        .role-kasir { background: #D1FAE5; color: #065F46; }
        .role-dapur { background: #FEF3C7; color: #92400E; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1 style="font-size: 20px; font-weight: 700;">Manajemen Staff</h1>
            <p style="font-size: 13px; color: var(--text-muted);">Kelola akun kasir, dapur, dan administrator.</p>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('kasir') }}" class="btn btn-outline">Kembali ke Kasir</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Tambah Akun</a>
        </div>
    </div>

    @if(session('success'))
        <div style="padding:12px 20px; background:#D1FAE5; color:#065F46; border:1px solid #A7F3D0; border-radius:8px; margin-bottom:20px; font-size:14px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="padding:12px 20px; background:#FEE2E2; color:#B91C1C; border:1px solid #FECACA; border-radius:8px; margin-bottom:20px; font-size:14px;">{{ session('error') }}</div>
    @endif

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Nama Staff</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td><div style="font-weight: 600;">{{ $u->nama }}</div></td>
                    <td><code>{{ $u->username }}</code></td>
                    <td>
                        <span class="role-badge role-{{ $u->role }}">{{ $u->role }}</span>
                    </td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">Edit</a>
                            <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
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
