<?php
include 'koneksi.php';
if (isset($_SESSION['user_id'])) {
    header("Location: halaman_kasir.php"); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($username && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_nama'] = $user['nama'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: halaman_kasir.php"); exit;
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Mohon isi username dan password.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panel — Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #E8603C;
            --secondary: #F5A623;
            --dark: #1C1C1E;
            --text: #3A3A3C;
            --surface: #FFFBF5;
            --border: #F0EDE8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #E8603C 0%, #F5A623 50%, #E8603C 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 28px;
            padding: 36px 28px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
        }
        .logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .logo-icon {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 22px;
            display: flex; align-items: center; justify-content: center;
            font-size: 34px;
            margin: 0 auto 14px;
            box-shadow: 0 8px 20px rgba(232,96,60,0.35);
        }
        .logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: var(--dark);
        }
        .logo p { font-size: 13px; color: #8E8E93; margin-top: 4px; }
        
        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #8E8E93;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 7px;
        }
        .field input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            color: var(--dark);
            outline: none;
            transition: border-color 0.2s;
            background: var(--surface);
        }
        .field input:focus { border-color: var(--primary); background: white; }
        
        .error-box {
            background: #FFF0EE;
            border: 1px solid #FFCCC7;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 13px;
            color: #E8603C;
            margin-bottom: 16px;
            font-weight: 500;
        }
        
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 16px;
            border-radius: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: all 0.3s;
            margin-top: 4px;
        }
        .btn-login:hover { filter: brightness(1.08); transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }
        
        .divider { text-align: center; font-size: 12px; color: #C0C0C0; margin: 20px 0 16px; }
        .back-link {
            display: block;
            text-align: center;
            font-size: 13px;
            color: #8E8E93;
            text-decoration: none;
            padding: 10px;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .back-link:hover { background: var(--surface); color: var(--primary); }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <div class="logo-icon">🍽️</div>
        <h1>Rumah Makan A</h1>
        <p>Panel Kasir & Dapur</p>
    </div>
    
    <?php if ($error): ?>
    <div class="error-box">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['err']) && $_GET['err'] === 'access'): ?>
    <div class="error-box">⛔ Anda tidak punya akses ke halaman tersebut.</div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="field">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" 
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" autocomplete="username" required>
        </div>
        <div class="field">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn-login">Masuk ke Panel</button>
    </form>
    
    <div class="divider">atau</div>
    <a href="index.php" class="back-link">← Kembali ke Menu Pelanggan</a>
</div>
</body>
</html>
