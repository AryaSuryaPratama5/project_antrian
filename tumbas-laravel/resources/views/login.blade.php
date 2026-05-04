<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login Staff — Rumah Makan A</title>
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
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);
            --radius-md: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: var(--bg-app); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .login-card { background: var(--bg-surface); width: 100%; max-width: 400px; padding: 32px; border-radius: var(--radius-md); box-shadow: var(--shadow-md); border: 1px solid var(--border-color); }
        .brand-header { text-align: center; margin-bottom: 32px; }
        .brand-icon { width: 48px; height: 48px; background: var(--brand-light); color: var(--brand-primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
        .brand-name { font-size: 20px; font-weight: 700; color: var(--text-strong); }
        .brand-sub { font-size: 14px; color: var(--text-muted); margin-top: 4px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-base); margin-bottom: 8px; }
        .input-control { width: 100%; padding: 12px 14px; background: var(--bg-input); border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; color: var(--text-strong); outline: none; transition: all 0.2s; }
        .input-control:focus { background: var(--bg-surface); border-color: var(--brand-primary); box-shadow: 0 0 0 3px var(--brand-light); }
        .btn-login { width: 100%; padding: 14px; background: var(--brand-primary); color: #FFFFFF; border: none; border-radius: var(--radius-md); font-family: inherit; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s; margin-top: 8px; }
        .btn-login:hover { background: var(--brand-hover); }
        .error-box { padding: 12px; background: #FEE2E2; color: #DC2626; font-size: 13px; border-radius: var(--radius-sm); margin-bottom: 20px; border: 1px solid #FECACA; }
        .back-link { display: block; text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
        .back-link:hover { color: var(--brand-primary); }
    </style>
</head>
<body>
<div class="login-card">
    <div class="brand-header">
        <div class="brand-icon">
            <svg style="width:24px;height:24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
        </div>
        <h1 class="brand-name">Rumah Makan A</h1>
        <p class="brand-sub">Portal Staff & Administrasi</p>
    </div>

    @if(session('error'))
        <div class="error-box">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="input-control" placeholder="Masukkan username" required value="{{ old('username') }}" autocomplete="username">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="input-control" placeholder="Masukkan password" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn-login">Masuk ke Sistem</button>
    </form>

    <a href="{{ route('menu') }}" class="back-link">← Kembali ke Menu Pelanggan</a>
</div>
</body>
</html>
