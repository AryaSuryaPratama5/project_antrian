<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login Staff — Rumah Makan A</title>
    
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
            --shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            --shadow-xl: 0 20px 25px -5px rgba(153, 27, 27, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            
            --radius-md: 12px;
            --radius-lg: 20px;
            
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body { 
            background-color: var(--bg-app); 
            background-image: radial-gradient(circle at 10% 20%, rgba(153, 27, 27, 0.02) 0%, transparent 40%), 
                              radial-gradient(circle at 90% 80%, rgba(153, 27, 27, 0.02) 0%, transparent 40%);
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            padding: 20px; 
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.01em;
        }

        /* === LOGIN CARD === */
        .login-card { 
            background: var(--bg-surface); 
            width: 100%; 
            max-width: 420px; 
            padding: 40px; 
            border-radius: var(--radius-lg); 
            box-shadow: var(--shadow-xl); 
            border: 1px solid rgba(226, 232, 240, 0.8); 
            transition: transform 0.3s ease;
        }

        .brand-header { 
            text-align: center; 
            margin-bottom: 36px; 
        }

        .brand-icon { 
            width: 56px; height: 56px; 
            background: var(--brand-gradient); 
            color: #FFFFFF; 
            border-radius: 16px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0 auto 16px; 
            font-size: 22px;
            box-shadow: 0 8px 16px rgba(153, 27, 27, 0.2);
        }

        .brand-name { 
            font-size: 22px; 
            font-weight: 800; 
            color: var(--text-strong); 
            letter-spacing: -0.5px;
        }

        .brand-sub { 
            font-size: 14px; 
            color: var(--text-muted); 
            margin-top: 6px; 
            font-weight: 500;
        }

        /* === MODERN FORM FIELDS === */
        .form-group { 
            margin-bottom: 24px; 
        }

        .form-group label { 
            display: block; 
            font-size: 13px; 
            font-weight: 700; 
            color: var(--text-strong); 
            margin-bottom: 8px; 
        }

        /* Input Wrapper for Icon Alignment */
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            color: var(--text-placeholder);
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .input-control { 
            width: 100%; 
            padding: 14px 16px 14px 44px; /* Space on left for icon */
            background: var(--bg-input); 
            border: 1px solid var(--border-color); 
            border-radius: var(--radius-md); 
            font-family: inherit; 
            font-size: 14px; 
            font-weight: 500;
            color: var(--text-strong); 
            outline: none; 
            transition: all 0.2s ease; 
        }

        .input-control::placeholder {
            color: var(--text-placeholder);
        }

        .input-control:focus { 
            background: var(--bg-surface); 
            border-color: var(--brand-primary); 
            box-shadow: 0 0 0 4px var(--brand-light); 
        }

        .input-control:focus + i {
            color: var(--brand-primary);
        }

        /* === PREMIUM BUTTON === */
        .btn-login { 
            width: 100%; 
            padding: 14px; 
            background: var(--brand-gradient); 
            color: #FFFFFF; 
            border: none; 
            border-radius: var(--radius-md); 
            font-family: inherit; 
            font-size: 15.5px; 
            font-weight: 700; 
            cursor: pointer; 
            box-shadow: 0 4px 12px rgba(153, 27, 27, 0.25);
            transition: all 0.2s ease; 
            margin-top: 12px; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover { 
            background: var(--brand-hover); 
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(153, 27, 27, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* === SLEEK ERROR BOX === */
        .error-box { 
            padding: 14px 18px; 
            background: #FEF2F2; 
            color: #DC2626; 
            font-size: 13.5px; 
            font-weight: 600;
            border-radius: var(--radius-md); 
            margin-bottom: 24px; 
            border: 1px solid #FCA5A5; 
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .error-box i {
            font-size: 16px;
        }

        /* === FOOTER BACK LINK === */
        .back-link { 
            display: flex; 
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 32px; 
            font-size: 13.5px; 
            font-weight: 600;
            color: var(--text-muted); 
            text-decoration: none; 
            transition: color 0.2s ease; 
        }

        .back-link:hover { 
            color: var(--brand-primary); 
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-header">
        <div class="brand-icon">
            <i class="fas fa-shield-halved"></i>
        </div>
        <h1 class="brand-name">Rumah Makan A</h1>
        <p class="brand-sub">Portal Staff & Administrasi</p>
    </div>

    <!-- Error Alert Box -->
    @if(session('error'))
        <div class="error-box">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        
        <!-- Username Field -->
        <div class="form-group">
            <label>Username</label>
            <div class="input-wrapper">
                <input type="text" name="username" class="input-control" placeholder="Masukkan username Anda" required value="{{ old('username') }}" autocomplete="username">
                <i class="fas fa-user"></i>
            </div>
        </div>
        
        <!-- Password Field -->
        <div class="form-group">
            <label>Password</label>
            <div class="input-wrapper">
                <input type="password" name="password" class="input-control" placeholder="Masukkan password Anda" required autocomplete="current-password">
                <i class="fas fa-lock"></i>
            </div>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="btn-login">
            <span>Masuk ke Sistem</span>
            <i class="fas fa-arrow-right-to-bracket"></i>
        </button>
    </form>

    <!-- Back to Customer View -->
    <a href="{{ route('menu') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Menu Pelanggan</span>
    </a>
</div>

</body>
</html>