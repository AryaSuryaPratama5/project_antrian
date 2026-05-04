<?php
include 'koneksi.php';

// Buat user default
$users = [
    ['kasir',  password_hash('kasir123',  PASSWORD_DEFAULT), 'Kasir Utama',  'kasir'],
    ['dapur',  password_hash('dapur123',  PASSWORD_DEFAULT), 'Tim Dapur',    'dapur'],
    ['admin',  password_hash('admin123',  PASSWORD_DEFAULT), 'Administrator','admin'],
];

$ok = 0;
foreach ($users as $u) {
    $stmt = $conn->prepare("INSERT IGNORE INTO users (username, password, nama, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $u[0], $u[1], $u[2], $u[3]);
    if ($stmt->execute() && $stmt->affected_rows > 0) $ok++;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>body{font-family:sans-serif;max-width:500px;margin:50px auto;padding:20px;background:#f5f5f5;}
.card{background:white;padding:30px;border-radius:15px;box-shadow:0 5px 20px rgba(0,0,0,.1);}
h2{color:#E8603C;} table{width:100%;border-collapse:collapse;margin:15px 0;}
th,td{padding:10px;border:1px solid #eee;} th{background:#f9f9f9;}
.warn{background:#fff3e0;border:1px solid #F5A623;padding:12px;border-radius:8px;font-size:13px;margin-top:15px;}
a{display:inline-block;margin-top:15px;background:#E8603C;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;}
</style></head>
<body>
<div class="card">
    <h2>✅ Setup Berhasil</h2>
    <p><?= $ok ?> akun baru dibuat (akun yang sudah ada dilewati).</p>
    <table>
        <tr><th>Role</th><th>Username</th><th>Password</th></tr>
        <tr><td>Kasir</td><td>kasir</td><td>kasir123</td></tr>
        <tr><td>Dapur</td><td>dapur</td><td>dapur123</td></tr>
        <tr><td>Admin</td><td>admin</td><td>admin123</td></tr>
    </table>
    <div class="warn">⚠️ <strong>Penting:</strong> Hapus atau rename file <code>setup.php</code> setelah setup selesai untuk keamanan!</div>
    <a href="login.php">→ Ke Halaman Login Kasir</a>
</div>
</body></html>
