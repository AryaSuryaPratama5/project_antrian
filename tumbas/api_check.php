<?php
include 'koneksi.php';
include 'auth.php';

// Only allow logged-in users
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$last_id = (int)($_GET['last_id'] ?? 0);

$res = mysqli_query($conn, "SELECT id_pesanan, nama_pelanggan, nomor_meja, waktu_pesan 
                             FROM pesanan 
                             WHERE id_pesanan > $last_id 
                             ORDER BY id_pesanan DESC 
                             LIMIT 5");

$new_orders = [];
while ($r = mysqli_fetch_assoc($res)) {
    $new_orders[] = $r;
}

$max = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(id_pesanan) as max_id FROM pesanan"));

echo json_encode([
    'new_orders' => $new_orders,
    'count'      => count($new_orders),
    'max_id'     => (int)($max['max_id'] ?? 0),
]);
