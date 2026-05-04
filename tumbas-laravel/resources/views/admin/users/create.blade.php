<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Akun — Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --brand-primary: #991B1B; --bg-app: #F3F4F6; --text-strong: #111827; --text-muted: #6B7280; --border-color: #E5E7EB; --radius-md: 10px; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-app); padding: 24px; }
        .container { max-width: 500px; margin: 0 auto; }
        .card { background: #fff; border-radius: var(--radius-md); padding: 32px; border: 1px solid var(--border-color); }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text-strong); }
        .input-control { width: 100%; padding: 12px 14px; background: #F9FAFB; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; outline: none; }
        .input-control:focus { border-color: var(--brand-primary); background: #fff; }
        .btn { padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; font-family: inherit; }
        .btn-primary { background: var(--brand-primary); color: #fff; width: 100%; margin-top: 10px; }
        .btn-link { background: none; color: var(--text-muted); text-decoration: none; display: block; text-align: center; margin-top: 20px; font-size: 14px; }
        .error-text { color: #DC2626; font-size: 11px; margin-top: 4px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">Tambah Akun Staff</h1>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 24px;">Buat akses baru untuk sistem.</p>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="input-control" required placeholder="Contoh: Budi Santoso" value="{{ old('nama') }}">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="input-control" required placeholder="Contoh: budikasir" value="{{ old('username') }}">
                @error('username') <p class="error-text">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="input-control" required placeholder="Minimal 4 karakter">
                @error('password') <p class="error-text">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label>Role / Hak Akses</label>
                <select name="role" class="input-control" required>
                    <option value="kasir">Kasir</option>
                    <option value="dapur">Dapur</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Akun</button>
        </form>

        <a href="{{ route('admin.users.index') }}" class="btn-link">Batal dan Kembali</a>
    </div>
</div>
</body>
</html>
