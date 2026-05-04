<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu — Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --brand-primary: #991B1B; --bg-app: #F3F4F6; --text-strong: #111827; --text-muted: #6B7280; --border-color: #E5E7EB; --radius-md: 10px; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-app); padding: 24px; }
        .container { max-width: 600px; margin: 0 auto; }
        .card { background: #fff; border-radius: var(--radius-md); padding: 32px; border: 1px solid var(--border-color); }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text-strong); }
        .input-control { width: 100%; padding: 12px 14px; background: #F9FAFB; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; outline: none; }
        .input-control:focus { border-color: var(--brand-primary); background: #fff; }
        .btn { padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; font-family: inherit; }
        .btn-primary { background: var(--brand-primary); color: #fff; width: 100%; margin-top: 10px; }
        .btn-link { background: none; color: var(--text-muted); text-decoration: none; display: block; text-align: center; margin-top: 20px; font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">Tambah Menu Baru</h1>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 24px;">Masukkan detail hidangan baru.</p>

        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nama Item</label>
                <input type="text" name="nama_item" class="input-control" required placeholder="Contoh: Nasi Goreng Gila">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" class="input-control" required>
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                    <option value="Snack">Snack</option>
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="input-control" required placeholder="25000">
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="input-control" style="height: 100px;" placeholder="Penjelasan singkat hidangan..."></textarea>
            </div>
            <div class="form-group">
                <label>Gambar Menu</label>
                <input type="file" name="gambar" class="input-control" accept="image/*">
                <p style="font-size: 11px; color: var(--text-muted); mt: 4px;">Opsional. Max 2MB (JPG/PNG).</p>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Menu</button>
        </form>

        <a href="{{ route('admin.menus.index') }}" class="btn-link">Batal dan Kembali</a>
    </div>
</div>
</body>
</html>
