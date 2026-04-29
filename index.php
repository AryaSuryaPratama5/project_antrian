<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Digital - Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #ff7e5f; --secondary: #feb47b; --accent: #27ae60; --dark: #2d3436; }
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
            margin: 0; padding: 15px; padding-bottom: 160px; overflow-x: hidden;
        }

        /* Efek Bubble Global */
        .bubble {
            position: absolute; background: rgba(255, 255, 255, 0.4);
            transform: translate(-50%, -50%); pointer-events: none;
            border-radius: 50%; animation: animate 0.6s linear forwards; z-index: 9999;
        }
        @keyframes animate {
            0% { width: 0; height: 0; opacity: 0.5; }
            100% { width: 400px; height: 400px; opacity: 0; }
        }

        .container { 
            max-width: 500px; margin: auto; background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px); padding: 25px; border-radius: 30px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.3);
        }
        
        /* Merapikan Kotak Panjang */
        .input-group { margin-bottom: 15px; }
        .input-group label { font-size: 11px; font-weight: bold; color: #7f8c8d; margin-left: 5px; text-transform: uppercase; }
        input, select { 
            width: 100%; padding: 14px; border: 1.5px solid #eee; border-radius: 15px; 
            background: #fff; margin-top: 5px; outline: none; transition: 0.3s; box-sizing: border-box;
        }

        /* Efek Hidup Saat Hover */
        .menu-row { 
            display: flex; align-items: center; padding: 15px; background: white; 
            border-radius: 20px; margin-bottom: 10px; transition: 0.3s; border: 1px solid #f0f2f5;
        }
        .menu-row:hover { transform: scale(1.02); border-color: var(--primary); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .btn-bayar:hover:not(:disabled) { transform: translateY(-3px); filter: brightness(1.1); }

        .menu-img { width: 65px; height: 65px; border-radius: 15px; object-fit: cover; margin-right: 15px; }

        /* Kotak Angka (Quantity) - Sangat Jelas */
        .qty-box { 
            width: 55px; padding: 8px 0; text-align: center; border: 2px solid var(--primary); 
            border-radius: 10px; background: #ffffff !important; color: #333 !important; 
            font-weight: bold; font-size: 15px; margin-left: 10px;
        }
        .qty-box:disabled { background: #f0f0f0 !important; border-color: #ccc; color: #999 !important; }

        .category-title { 
            background: linear-gradient(to right, var(--primary), var(--secondary)); color: white; 
            padding: 12px 20px; border-radius: 15px; margin: 25px 0 12px; font-weight: bold;
        }

        .footer-payment { 
            position: fixed; bottom: 0; left: 0; right: 0; background: rgba(255,255,255,0.95);
            backdrop-filter: blur(15px); padding: 20px 30px; border-radius: 35px 35px 0 0;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.05); z-index: 1000;
        }
        .btn-bayar { 
            width: 100%; background: linear-gradient(to right, #00b09b, #96c93d);
            color: white; border: none; padding: 18px; border-radius: 20px; font-weight: bold; font-size: 16px; cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 style="text-align:center;">✨ Rumah Makan A</h2>
    <form action="konfirmasi.php" method="POST">
        <div class="input-group">
            <label>👤 Identitas Pelanggan</label>
            <input type="number" name="meja" placeholder="Nomor Meja" required>
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
        </div>

        <div class="input-group">
            <label>🥡 Opsi Pesanan</label>
            <select name="jenis_pesanan">
                <option value="Makan di Tempat">🏠 Makan di Tempat</option>
                <option value="Bawa Pulang">🛍️ Bawa Pulang</option>
            </select>
        </div>

        <div class="input-group">
            <label>💳 Cara Bayar</label>
            <select name="metode_bayar" id="metodeBayar" onchange="updateBtn()">
                <option value="Tunai">💵 Bayar Tunai</option>
                <option value="QRIS">📱 QRIS / E-Wallet</option>
                <option value="Transfer">🏦 Transfer Bank</option>
            </select>
        </div>

        <?php
        $cats = ['Makanan' => '🥘 Menu Makanan', 'Minuman' => '🍹 Minuman Segar'];
        foreach ($cats as $key => $label) {
            echo "<div class='category-title'>$label</div>";
            $q = mysqli_query($conn, "SELECT * FROM menu WHERE kategori='$key'");
            while($m = mysqli_fetch_array($q)){
                $isHabis = ($m['status_tersedia'] == 'Habis');
        ?>
            <div class="menu-row" style="<?= $isHabis ? 'opacity:0.4;' : '' ?>">
                <img src="img/<?= $m['gambar'] ?>" class="menu-img">
                <div style="flex-grow:1;">
                    <span style="display:block; font-weight:600;"><?= $m['nama_item'] ?></span>
                    <span style="color:var(--accent); font-weight:bold;">Rp<?= number_format($m['harga']) ?></span>
                </div>
                <?php if(!$isHabis): ?>
                    <input type="checkbox" name="pilihan[]" class="menu-check" data-harga="<?= $m['harga'] ?>" value="<?= $m['id_menu'] ?>" style="width:22px; height:22px;">
                    <input type="number" name="qty_<?= $m['id_menu'] ?>" class="qty-box" value="1" min="1" disabled>
                <?php else: ?>
                    <b style="color:red; font-size:10px;">HABIS</b>
                <?php endif; ?>
            </div>
        <?php } } ?>

        <div class="footer-payment">
            <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-weight:bold;">
                <span>Total:</span><span id="totalText" style="color:var(--accent); font-size:20px;">Rp0</span>
            </div>
            <button type="submit" id="btnProses" class="btn-bayar" disabled>PESAN SEKARANG</button>
        </div>
    </form>
</div>

<script>
    // Efek Bubble Klik & Hover
    document.addEventListener('click', (e) => {
        createBubble(e.pageX, e.pageY, 400);
    });

    const liveObjects = document.querySelectorAll('.menu-row, .btn-bayar, input, select');
    liveObjects.forEach(obj => {
        obj.addEventListener('mouseenter', (e) => {
            createBubble(e.pageX, e.pageY, 30); // Bubble kecil saat menyentuh
        });
    });

    function createBubble(x, y, size) {
        let b = document.createElement('span');
        b.classList.add('bubble');
        b.style.left = x + 'px'; b.style.top = y + 'px';
        if(size < 100) { b.style.width = size + 'px'; b.style.height = size + 'px'; b.style.background = 'rgba(255,126,95,0.2)'; }
        document.body.appendChild(b);
        setTimeout(() => b.remove(), 600);
    }

    function updateBtn() {
        const m = document.getElementById('metodeBayar').value;
        document.getElementById('btnProses').innerText = (m === "Tunai") ? "PESAN SEKARANG" : "PESAN & BAYAR";
    }

    const checks = document.querySelectorAll('.menu-check');
    function hitung() {
        let total = 0; let count = 0;
        checks.forEach(c => {
            if(c.checked) {
                const q = c.parentElement.querySelector('.qty-box').value;
                total += parseInt(c.dataset.harga) * q;
                count++;
            }
        });
        document.getElementById('totalText').innerText = 'Rp' + total.toLocaleString();
        document.getElementById('btnProses').disabled = (count === 0);
    }

    checks.forEach(c => {
        c.addEventListener('change', function() {
            const qInput = this.parentElement.querySelector('.qty-box');
            qInput.disabled = !this.checked;
            hitung();
        });
    });
</script>
</body>
</html>