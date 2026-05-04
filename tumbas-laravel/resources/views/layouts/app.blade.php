<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sistem Pemesanan — Rumah Makan A</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
            --text-placeholder: #9CA3AF;
            --border-color: #E5E7EB;
            --shadow-sm: 0 1px 2px 0 rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-float: 0 -4px 12px rgba(0,0,0,0.08);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }
        body { background-color: var(--bg-app); color: var(--text-base); -webkit-font-smoothing: antialiased; }
        .app-container { max-width: 480px; margin: 0 auto; background-color: var(--bg-app); min-height: 100vh; position: relative; }
        .icon { width: 18px; height: 18px; flex-shrink: 0; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; fill: none; }

        /* HERO HEADER */
        .header-hero { background: var(--bg-surface); position: relative; border-bottom: 1px solid var(--border-color); }
        .hero-cover { width: 100%; height: 140px; object-fit: cover; background: var(--text-muted); display: block; }
        .hero-content { padding: 16px 20px 20px; position: relative; }
        .brand-avatar { width: 64px; height: 64px; background: var(--bg-surface); border: 2px solid var(--border-color); border-radius: var(--radius-md); position: absolute; top: -32px; display: flex; align-items: center; justify-content: center; color: var(--brand-primary); box-shadow: var(--shadow-sm); }
        .resto-info { margin-top: 36px; }
        .resto-name { font-size: 20px; font-weight: 700; color: var(--text-strong); margin-bottom: 4px; }
        .resto-desc { font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 6px; }
    </style>
    @yield('styles')
</head>
<body>
<div class="app-container">
    <header class="header-hero">
        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80"
             alt="Restaurant" class="hero-cover">
        <div class="hero-content">
            <div class="brand-avatar">
                <svg class="icon" style="width:32px;height:32px;" viewBox="0 0 24 24">
                    <path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/>
                    <line x1="6" y1="17" x2="18" y2="17"/>
                </svg>
            </div>
            <div class="resto-info">
                <h1 class="resto-name">Rumah Makan A</h1>
                <p class="resto-desc">
                    <svg class="icon" style="width:14px;height:14px;" viewBox="0 0 24 24"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>
                    Sistem Pemesanan Mandiri
                </p>
            </div>
        </div>
    </header>
    @yield('content')
</div>
</body>
</html>
