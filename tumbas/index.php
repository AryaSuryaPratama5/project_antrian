<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Menu Digital — Rumah Makan A</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #E8603C;
            --primary-light: #FF8A6E;
            --secondary: #F5A623;
            --dark: #1C1C1E;
            --text: #3A3A3C;
            --muted: #8E8E93;
            --surface: #FFFBF5;
            --card: #FFFFFF;
            --success: #34C759;
            --border: #F0EDE8;
            --shadow: rgba(232,96,60,0.15);
        }
        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface);
            color: var(--text);
            padding-bottom: 190px;
            overflow-x: hidden;
        }

        /* === HEADER === */
        .header {
            background: linear-gradient(135deg, #E8603C 0%, #F5A623 100%);
            padding: 52px 20px 50px;
            position: relative; overflow: hidden;
        }
        .header::before {
            content: ''; position: absolute;
            top: -60px; right: -40px;
            width: 220px; height: 220px;
            background: rgba(255,255,255,0.12);
            border-radius: 50%;
        }
        .header::after {
            content: ''; position: absolute;
            bottom: -40px; left: -30px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .header-inner { position: relative; z-index: 1; }
        .header h1 {
            font-family: 'Playfair Display', serif;
            color: white; font-size: 28px;
            margin-bottom: 4px;
        }
        .header p { color: rgba(255,255,255,0.88); font-size: 13px; }
        .header-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 10px;
        }

        /* === INFO CARD === */
        .info-card {
            background: white;
            margin: -22px 15px 16px;
            border-radius: 22px;
            padding: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.09);
            position: relative; z-index: 2;
        }
        .section-label {
            font-size: 10px; font-weight: 700;
            color: var(--muted); text-transform: uppercase;
            letter-spacing: 0.6px; margin-bottom: 10px;
        }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .field-wrap { margin-bottom: 12px; }
        .field-wrap:last-child { margin-bottom: 0; }
        .f-label {
            font-size: 10px; font-weight: 700;
            color: var(--muted); text-transform: uppercase;
            letter-spacing: 0.4px; margin-bottom: 6px; display: block;
        }
        .f-input, .f-select {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid var(--border);
            border-radius: 13px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; color: var(--dark);
            background: var(--surface);
            outline: none; transition: border-color 0.2s;
            appearance: none; -webkit-appearance: none;
        }
        .f-input:focus, .f-select:focus {
            border-color: var(--primary); background: white;
        }
        .select-wrap { position: relative; }
        .select-wrap::after {
            content: '▾'; position: absolute;
            right: 14px; top: 50%; transform: translateY(-50%);
            color: var(--muted); pointer-events: none; font-size: 14px;
        }

        /* === SEARCH & FILTER === */
        .search-section { padding: 0 15px 12px; }
        .search-box {
            position: relative; margin-bottom: 12px;
        }
        .search-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); font-size: 16px;
            pointer-events: none;
        }
        .search-input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 2px solid var(--border);
            border-radius: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; background: white; outline: none;
            transition: border-color 0.2s;
        }
        .search-input:focus { border-color: var(--primary); }
        .search-clear {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%); cursor: pointer;
            font-size: 16px; color: var(--muted);
            display: none;
        }
        .filter-scroll {
            display: flex; gap: 8px;
            overflow-x: auto; padding-bottom: 4px;
            scrollbar-width: none;
        }
        .filter-scroll::-webkit-scrollbar { display: none; }
        .pill {
            padding: 9px 18px; border-radius: 22px;
            font-size: 13px; font-weight: 600;
            border: 2px solid var(--border); background: white;
            color: var(--muted); cursor: pointer; white-space: nowrap;
            transition: all 0.2s; user-select: none; flex-shrink: 0;
        }
        .pill.active {
            background: var(--primary); border-color: var(--primary); color: white;
        }

        /* === CATEGORY HEADER === */
        .cat-header {
            display: flex; align-items: center;
            gap: 10px; padding: 16px 15px 10px;
        }
        .cat-title { font-size: 17px; font-weight: 800; color: var(--dark); white-space: nowrap; }
        .cat-line {
            flex: 1; height: 2px;
            background: linear-gradient(to right, var(--primary), transparent);
            border-radius: 2px;
        }

        /* === MENU CARD === */
        .menu-list { padding: 0 15px; }
        .menu-card {
            background: white; border-radius: 20px;
            margin-bottom: 12px;
            border: 2px solid transparent;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .menu-card.selected {
            border-color: var(--primary);
            box-shadow: 0 6px 24px var(--shadow);
        }
        .menu-card.habis { opacity: 0.5; }

        .card-main {
            display: flex; align-items: center;
            padding: 14px; gap: 12px;
        }
        .menu-img {
            width: 74px; height: 74px; border-radius: 16px;
            object-fit: cover; flex-shrink: 0;
            background: linear-gradient(135deg, #f5ede8, #fde8d8);
        }
        .menu-info { flex: 1; min-width: 0; }
        .menu-name {
            font-weight: 700; font-size: 14px;
            color: var(--dark); margin-bottom: 3px;
        }
        .menu-desc {
            font-size: 11px; color: var(--muted);
            margin-bottom: 5px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .menu-price {
            font-size: 16px; font-weight: 800; color: var(--primary);
        }
        .card-right { display: flex; align-items: center; gap: 8px; }

        /* Qty Stepper */
        .qty-control {
            display: none; align-items: center; gap: 6px;
        }
        .qty-btn {
            width: 30px; height: 30px; border-radius: 9px;
            border: none; font-size: 18px; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all 0.15s; flex-shrink: 0;
        }
        .qty-btn.minus { background: #F0EDE8; color: var(--text); }
        .qty-btn.plus { background: var(--primary); color: white; }
        .qty-btn:active { transform: scale(0.9); }
        .qty-num {
            font-weight: 800; font-size: 16px;
            min-width: 22px; text-align: center;
        }

        /* Add Button */
        .btn-add {
            background: var(--surface);
            border: 2px dashed #DDD;
            border-radius: 12px;
            padding: 9px 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; font-weight: 600;
            color: var(--muted); cursor: pointer;
            transition: all 0.2s; white-space: nowrap;
            flex-shrink: 0;
        }
        .habis-tag {
            background: #FFF0EE; color: #E8603C;
            font-size: 10px; font-weight: 700;
            padding: 7px 10px; border-radius: 10px;
        }

        /* Expanded Options */
        .card-expand {
            max-height: 0; overflow: hidden;
            transition: max-height 0.35s ease;
        }
        .card-expand.open { max-height: 250px; }
        .expand-inner {
            padding: 14px 14px 14px;
            border-top: 1.5px dashed var(--border);
        }
        .opt-label {
            font-size: 10px; font-weight: 700;
            color: var(--muted); text-transform: uppercase;
            letter-spacing: 0.4px; margin-bottom: 7px;
        }

        /* Spice Selector */
        .spice-wrap { display: flex; gap: 6px; margin-bottom: 12px; }
        .spice-btn {
            flex: 1; padding: 8px 4px;
            border-radius: 10px; border: 2px solid var(--border);
            background: white; font-size: 11px; font-weight: 700;
            cursor: pointer; text-align: center; transition: all 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text); line-height: 1.3;
        }
        .spice-btn:active { transform: scale(0.95); }
        .spice-btn.active-0 { background: #E8F5E9; border-color: #4CAF50; color: #2E7D32; }
        .spice-btn.active-1 { background: #FFF3E0; border-color: #FF9800; color: #E65100; }
        .spice-btn.active-2 { background: #FFEBEE; border-color: #E53935; color: #B71C1C; }

        /* Notes Input */
        .notes-input {
            width: 100%;
            padding: 11px 13px;
            border: 2px solid var(--border);
            border-radius: 11px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; outline: none;
            background: var(--surface);
            transition: border-color 0.2s;
        }
        .notes-input:focus { border-color: var(--primary); background: white; }

        /* === FLOATING FOOTER === */
        .footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: white;
            padding: 16px 20px 24px;
            border-radius: 30px 30px 0 0;
            box-shadow: 0 -8px 30px rgba(0,0,0,0.1);
            z-index: 999;
        }
        .footer-top {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 12px;
        }
        .footer-label { font-size: 12px; color: var(--muted); font-weight: 600; margin-bottom: 2px; }
        .footer-total { font-size: 22px; font-weight: 800; color: var(--dark); }
        .cart-badge {
            background: var(--primary); color: white;
            font-size: 11px; font-weight: 700;
            padding: 3px 9px; border-radius: 12px;
            margin-left: 7px; vertical-align: middle;
        }
        .btn-order {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; border: none;
            padding: 17px; border-radius: 17px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all 0.3s;
            letter-spacing: 0.3px;
        }
        .btn-order:disabled {
            background: #E8E8E8; color: #B0B0B0; cursor: not-allowed;
        }
        .btn-order:not(:disabled):active { transform: scale(0.98); }

        /* No results */
        .no-result {
            text-align: center; padding: 30px;
            color: var(--muted); display: none;
        }
        .no-result .icon { font-size: 40px; margin-bottom: 10px; }
        .no-result p { font-size: 14px; font-weight: 600; }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-inner">
        <h1>🍽️ Rumah Makan A</h1>
        <p>Pesan dengan mudah, langsung dari meja Anda</p>
        <span class="header-badge">✨ Menu Digital</span>
    </div>
</div>

<form action="konfirmasi.php" method="POST" id="orderForm">

<!-- INFO PELANGGAN -->
<div class="info-card">
    <div class="section-label">👤 Identitas Pelanggan</div>
    <div class="grid-2" style="margin-bottom:12px;">
        <div>
            <span class="f-label">No. Meja</span>
            <input type="number" name="meja" class="f-input" placeholder="Meja?" min="1" required>
        </div>
        <div>
            <span class="f-label">Nama</span>
            <input type="text" name="nama" class="f-input" placeholder="Nama Anda" required>
        </div>
    </div>
    <div class="grid-2">
        <div>
            <span class="f-label">Jenis Pesanan</span>
            <div class="select-wrap">
                <select name="jenis_pesanan" class="f-select">
                    <option value="Makan di Tempat">🏠 Makan di Sini</option>
                    <option value="Bawa Pulang">🛍️ Bawa Pulang</option>
                </select>
            </div>
        </div>
        <div>
            <span class="f-label">Cara Bayar</span>
            <div class="select-wrap">
                <select name="metode_bayar" class="f-select" id="metodeBayar">
                    <option value="Tunai">💵 Tunai</option>
                    <option value="QRIS">📱 QRIS</option>
                    <option value="Transfer">🏦 Transfer</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- SEARCH & FILTER -->
<div class="search-section">
    <div class="search-box">
        <span class="search-icon">🔍</span>
        <input type="text" id="searchInput" class="search-input" placeholder="Cari menu makanan atau minuman...">
        <span class="search-clear" id="clearBtn" onclick="clearSearch()">✕</span>
    </div>
    <div class="filter-scroll">
        <div class="pill active" data-filter="semua">🍽️ Semua</div>
        <div class="pill" data-filter="Makanan">🥘 Makanan</div>
        <div class="pill" data-filter="Minuman">🍹 Minuman</div>
    </div>
</div>

<div class="no-result" id="noResult">
    <div class="icon">🤔</div>
    <p>Menu tidak ditemukan</p>
    <small>Coba kata kunci lain</small>
</div>

<?php
$categories = ['Makanan' => '🥘 Makanan', 'Minuman' => '🍹 Minuman Segar'];
foreach ($categories as $cat => $catLabel):
    $q = mysqli_query($conn, "SELECT * FROM menu WHERE kategori='$cat' ORDER BY id_menu ASC");
    if (mysqli_num_rows($q) === 0) continue;
?>

<div class="cat-header" data-cat="<?= $cat ?>">
    <span class="cat-title"><?= $catLabel ?></span>
    <div class="cat-line"></div>
</div>

<div class="menu-list" data-catlist="<?= $cat ?>">
<?php while($m = mysqli_fetch_assoc($q)):
    $isHabis   = ($m['status_tersedia'] === 'Habis');
    $isMakanan = ($cat === 'Makanan');
    $id        = $m['id_menu'];
?>
    <div class="menu-card <?= $isHabis ? 'habis' : '' ?>"
         id="card_<?= $id ?>"
         data-id="<?= $id ?>"
         data-kategori="<?= $cat ?>"
         data-nama="<?= strtolower(htmlspecialchars($m['nama_item'])) ?>">

        <div class="card-main">
            <img src="../img/<?= htmlspecialchars($m['gambar']) ?>"
                 class="menu-img"
                 onerror="this.src='https://placehold.co/74x74/f5ede8/E8603C?text=🍽️'">

            <div class="menu-info">
                <div class="menu-name"><?= htmlspecialchars($m['nama_item']) ?></div>
                <?php if(!empty($m['deskripsi'])): ?>
                <div class="menu-desc"><?= htmlspecialchars($m['deskripsi']) ?></div>
                <?php endif; ?>
                <div class="menu-price">Rp<?= number_format($m['harga'], 0, ',', '.') ?></div>
            </div>

            <div class="card-right">
                <?php if($isHabis): ?>
                    <div class="habis-tag">HABIS</div>
                <?php else: ?>
                    <!-- Qty Control (shown when added) -->
                    <div class="qty-control" id="qty_<?= $id ?>">
                        <button type="button" class="qty-btn minus" onclick="changeQty(<?= $id ?>, -1)">−</button>
                        <span class="qty-num" id="qnum_<?= $id ?>">1</span>
                        <button type="button" class="qty-btn plus"  onclick="changeQty(<?= $id ?>,  1)">+</button>
                        <input type="hidden" name="qty_<?= $id ?>" id="qval_<?= $id ?>" value="1">
                    </div>
                    <!-- Add Button (shown by default) -->
                    <button type="button" class="btn-add" id="addBtn_<?= $id ?>"
                            onclick="toggleItem(<?= $id ?>, <?= $m['harga'] ?>, '<?= $isMakanan ? 'makanan' : 'minuman' ?>')">
                        + Pilih
                    </button>
                    <input type="checkbox" name="pilihan[]" id="chk_<?= $id ?>" value="<?= $id ?>" style="display:none;">
                <?php endif; ?>
            </div>
        </div>

        <?php if(!$isHabis): ?>
        <!-- Expanded: Spice + Notes -->
        <div class="card-expand" id="expand_<?= $id ?>">
            <div class="expand-inner">
                <?php if($isMakanan): ?>
                <div style="margin-bottom:12px;">
                    <div class="opt-label">🌶️ Tingkat Kepedasan</div>
                    <div class="spice-wrap" id="spicewrap_<?= $id ?>">
                        <button type="button" class="spice-btn active-0"
                                onclick="setSpice(<?= $id ?>, 0, 'Tidak Pedas', this)">
                            🌿<br>Tidak Pedas
                        </button>
                        <button type="button" class="spice-btn"
                                onclick="setSpice(<?= $id ?>, 1, 'Pedas', this)">
                            🌶️<br>Pedas
                        </button>
                        <button type="button" class="spice-btn"
                                onclick="setSpice(<?= $id ?>, 2, 'Ekstra Pedas', this)">
                            🔥<br>Ekstra Pedas
                        </button>
                    </div>
                    <input type="hidden" name="pedas_<?= $id ?>" id="pedasval_<?= $id ?>" value="Tidak Pedas">
                </div>
                <?php else: ?>
                <input type="hidden" name="pedas_<?= $id ?>" value="-">
                <?php endif; ?>

                <div>
                    <div class="opt-label">📝 Catatan Khusus (Opsional)</div>
                    <input type="text" name="catatan_<?= $id ?>" class="notes-input"
                           placeholder="Contoh: tanpa bawang, extra saus, dll...">
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
<?php endwhile; ?>
</div>

<?php endforeach; ?>

<!-- FLOATING FOOTER -->
<div class="footer">
    <div class="footer-top">
        <div>
            <div class="footer-label">
                Total Estimasi
                <span class="cart-badge" id="cartBadge" style="display:none">0</span>
            </div>
            <div class="footer-total" id="totalText">Rp0</div>
        </div>
    </div>
    <button type="submit" class="btn-order" id="btnOrder" disabled>
        Pilih menu terlebih dahulu
    </button>
</div>

</form>

<script>
// ============ STATE ============
const cart = {}; // id: { harga, qty }
let activeFilter = 'semua';

// ============ TOGGLE ITEM ============
function toggleItem(id, harga, kat) {
    const chk     = document.getElementById('chk_' + id);
    const addBtn  = document.getElementById('addBtn_' + id);
    const qtyCtrl = document.getElementById('qty_' + id);
    const expand  = document.getElementById('expand_' + id);
    const card    = document.getElementById('card_' + id);

    if (!chk.checked) {
        // Add
        chk.checked = true;
        addBtn.style.display = 'none';
        qtyCtrl.style.display = 'flex';
        if (expand) expand.classList.add('open');
        card.classList.add('selected');
        cart[id] = { harga, qty: 1 };
    } else {
        // Remove
        chk.checked = false;
        addBtn.style.display = '';
        qtyCtrl.style.display = 'none';
        if (expand) expand.classList.remove('open');
        card.classList.remove('selected');
        delete cart[id];
        document.getElementById('qnum_' + id).innerText = 1;
        document.getElementById('qval_' + id).value = 1;
    }
    updateTotal();
}

// ============ QTY ============
function changeQty(id, delta) {
    if (!cart[id]) return;
    let q = cart[id].qty + delta;
    if (q < 1) {
        toggleItem(id, cart[id].harga, null);
        return;
    }
    cart[id].qty = q;
    document.getElementById('qnum_' + id).innerText = q;
    document.getElementById('qval_' + id).value = q;
    updateTotal();
}

// ============ SPICE ============
function setSpice(id, level, label, btn) {
    document.querySelectorAll('#spicewrap_' + id + ' .spice-btn').forEach(b => {
        b.className = 'spice-btn'; // reset
    });
    btn.classList.add('active-' + level);
    document.getElementById('pedasval_' + id).value = label;
}

// ============ TOTAL ============
function updateTotal() {
    let total = 0, count = 0;
    for (const id in cart) {
        total += cart[id].harga * cart[id].qty;
        count++;
    }
    document.getElementById('totalText').innerText = 'Rp' + total.toLocaleString('id-ID');
    const badge = document.getElementById('cartBadge');
    if (count > 0) {
        badge.style.display = 'inline';
        badge.innerText = count + ' item';
    } else {
        badge.style.display = 'none';
    }
    const btn = document.getElementById('btnOrder');
    btn.disabled = (count === 0);
    btn.innerText = count === 0
        ? 'Pilih menu terlebih dahulu'
        : `Pesan Sekarang (${count} item)`;
}

// ============ SEARCH ============
const searchInput = document.getElementById('searchInput');
const clearBtn    = document.getElementById('clearBtn');

searchInput.addEventListener('input', function() {
    clearBtn.style.display = this.value ? 'block' : 'none';
    applyFilter();
});
function clearSearch() {
    searchInput.value = '';
    clearBtn.style.display = 'none';
    applyFilter();
}

// ============ FILTER PILLS ============
document.querySelectorAll('.pill').forEach(pill => {
    pill.addEventListener('click', function() {
        document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        activeFilter = this.dataset.filter;
        applyFilter();
    });
});

function applyFilter() {
    const keyword = searchInput.value.toLowerCase().trim();
    let visibleCount = 0;

    document.querySelectorAll('.menu-card').forEach(card => {
        const nama = card.dataset.nama || '';
        const kat  = card.dataset.kategori;
        const matchSearch = !keyword || nama.includes(keyword);
        const matchFilter = (activeFilter === 'semua') || (kat === activeFilter);
        const show = matchSearch && matchFilter;
        card.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });

    // Show/hide category headers
    document.querySelectorAll('.cat-header, [data-catlist]').forEach(el => {
        const cat = el.dataset.cat || el.dataset.catlist;
        if (activeFilter !== 'semua' && cat !== activeFilter) {
            el.style.display = 'none';
        } else {
            el.style.display = '';
        }
    });

    document.getElementById('noResult').style.display = visibleCount === 0 ? 'block' : 'none';
}

// Prevent form submit if empty
document.getElementById('orderForm').addEventListener('submit', function(e) {
    if (Object.keys(cart).length === 0) {
        e.preventDefault();
        alert('Silakan pilih menu terlebih dahulu!');
    }
});
</script>
</body>
</html>
