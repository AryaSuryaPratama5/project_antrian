@extends('layouts.app')

@section('styles')
<!-- Tambahkan FontAwesome jika belum ada di layouts.app -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        /* Premium Palette */
        --brand-primary: #991B1B;
        --brand-hover: #7F1D1D;
        --brand-light: #FEF2F2;
        --brand-gradient: linear-gradient(135deg, #991B1B 0%, #B91C1C 100%);
        
        --bg-app: #F4F7F9;
        --bg-surface: #FFFFFF;
        --bg-input: #F8FAFC;
        
        --text-strong: #0F172A;
        --text-base: #334155;
        --text-muted: #64748B;
        --text-placeholder: #9CA3AF;
        
        --border-color: #E2E8F0;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.02);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.05);
        --shadow-float: 0 -8px 24px rgba(0,0,0,0.08);
        
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    body { padding-bottom: 130px; background-color: var(--bg-app); color: var(--text-base); -webkit-font-smoothing: antialiased; }
    
    /* === ELEGANT CARDS === */
    .section-card { 
        background-color: var(--bg-surface); 
        margin: 16px; 
        padding: 24px; 
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        border: 1px solid rgba(255,255,255,0.8);
    }
    
    .section-title { 
        font-size: 16px; font-weight: 700; color: var(--text-strong); 
        margin-bottom: 20px; display: flex; align-items: center; gap: 10px; 
    }
    .section-title i { color: var(--brand-primary); font-size: 18px; padding: 8px; background: var(--brand-light); border-radius: 8px; }

    /* === PREMIUM FORMS === */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .input-group { display: flex; flex-direction: column; gap: 8px; }
    .input-group label { font-size: 12.5px; font-weight: 700; color: var(--text-strong); display: flex; align-items: center; gap: 6px; }
    .input-group label i { color: var(--text-muted); font-size: 12px; }
    
    .input-control { 
        width: 100%; padding: 14px 16px; background-color: var(--bg-input); 
        border: 1px solid var(--border-color); border-radius: var(--radius-md); 
        font-family: inherit; font-size: 14px; font-weight: 500; color: var(--text-strong); 
        transition: all 0.3s ease; outline: none; appearance: none; -webkit-appearance: none; 
    }
    .input-control:focus { 
        background-color: var(--bg-surface); border-color: var(--brand-primary); 
        box-shadow: 0 0 0 4px var(--brand-light); 
    }
    
    .select-wrapper { position: relative; }
    .select-wrapper::after { 
        content: '\f107'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
        position: absolute; right: 16px; top: 50%; transform: translateY(-50%); 
        color: var(--text-muted); pointer-events: none; font-size: 12px;
    }

    /* === GLASSMORPHISM STICKY NAV === */
    .nav-sticky { 
        position: sticky; top: 0; z-index: 50; 
        background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(226, 232, 240, 0.6); box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
        padding-top: 12px;
    }
    
    .search-container { padding: 0 16px 16px; position: relative; }
    .search-icon { position: absolute; left: 32px; top: 38%; transform: translateY(-50%); color: var(--text-placeholder); font-size: 14px; }
    .search-input { 
        width: 100%; padding: 12px 16px 12px 44px; background-color: var(--bg-input); 
        border: 1px solid var(--border-color); border-radius: 50rem; 
        font-family: inherit; font-size: 14px; outline: none; transition: all 0.3s ease; 
    }
    .search-input:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 3px var(--brand-light); background: #fff; }
    
    .category-tabs { 
        display: flex; gap: 10px; padding: 0 16px 12px; overflow-x: auto; 
        scrollbar-width: none; scroll-behavior: smooth; -webkit-overflow-scrolling: touch;
    }
    .category-tabs::-webkit-scrollbar { display: none; }
    .cat-tab { 
        padding: 8px 18px; border-radius: 50rem; background-color: var(--bg-surface); 
        color: var(--text-muted); font-size: 13.5px; font-weight: 600; white-space: nowrap; 
        cursor: pointer; border: 1px solid var(--border-color); transition: all 0.2s ease;
        display: flex; align-items: center; gap: 6px; box-shadow: var(--shadow-sm);
    }
    .cat-tab i { font-size: 12px; opacity: 0.7; }
    .cat-tab.active { 
        background: var(--brand-gradient); color: #FFFFFF; border-color: transparent; 
        box-shadow: 0 4px 12px rgba(153, 27, 27, 0.25); transform: translateY(-1px);
    }
    .cat-tab.active i { opacity: 1; }

    /* === MENU SECTION === */
    .menu-section { background-color: var(--bg-app); padding-bottom: 20px; }
    .menu-category-title { 
        padding: 24px 16px 12px; font-size: 18px; font-weight: 800; 
        color: var(--text-strong); display: flex; align-items: center; gap: 8px;
    }
    .menu-category-title i { color: var(--brand-primary); }
    
    .menu-item-container { margin: 0 16px 12px; }
    .menu-item { 
        display: flex; padding: 16px; background: var(--bg-surface); 
        border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);
        gap: 16px; transition: transform 0.2s ease;
    }
    .menu-item:active { transform: scale(0.98); }
    .menu-item.habis { opacity: 0.6; filter: grayscale(100%); pointer-events: none; }
    
    .item-content { flex: 1; display: flex; flex-direction: column; }
    .item-name { font-size: 15.5px; font-weight: 700; color: var(--text-strong); margin-bottom: 4px; line-height: 1.3; }
    .item-desc { font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    
    .item-footer { margin-top: auto; display: flex; align-items: center; justify-content: space-between; }
    .item-price { font-size: 15px; font-weight: 800; color: var(--brand-primary); }
    
    .item-image-wrapper { width: 96px; height: 96px; flex-shrink: 0; position: relative; border-radius: var(--radius-sm); overflow: hidden; box-shadow: var(--shadow-sm); }
    .item-image { width: 100%; height: 100%; object-fit: cover; }

    /* Controls */
    .btn-add { 
        padding: 6px 16px; border-radius: 50rem; border: 1px solid var(--brand-primary); 
        background-color: var(--brand-light); color: var(--brand-primary); 
        font-family: inherit; font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all 0.2s; 
    }
    .btn-add:active { background-color: var(--brand-primary); color: white; }
    
    .qty-control { 
        display: none; align-items: center; background-color: var(--bg-input); 
        border: 1px solid var(--border-color); border-radius: 50rem; height: 32px; overflow: hidden; 
    }
    .qty-btn { 
        width: 32px; height: 100%; background: transparent; border: none; 
        color: var(--brand-primary); display: flex; align-items: center; justify-content: center; 
        cursor: pointer; transition: background 0.2s; font-size: 12px;
    }
    .qty-btn:active { background-color: #E2E8F0; }
    .qty-num { font-size: 13px; font-weight: 700; color: var(--text-strong); min-width: 24px; text-align: center; }
    .badge-habis { padding: 4px 10px; background-color: #FEE2E2; color: #DC2626; font-size: 11px; font-weight: 700; border-radius: 6px; letter-spacing: 0.5px; text-transform: uppercase; }

    /* Expanded Options */
    .item-expanded { display: none; padding: 0 16px 16px; background-color: var(--bg-surface); border-radius: 0 0 var(--radius-md) var(--radius-md); border: 1px solid var(--border-color); border-top: none; margin-top: -8px; box-shadow: var(--shadow-sm); }
    .item-expanded.open { display: block; animation: slideDown 0.3s ease; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    
    .options-wrapper { padding-top: 16px; border-top: 1px dashed var(--border-color); }
    .option-label { font-size: 12.5px; font-weight: 700; color: var(--text-strong); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    .option-label i { color: #F59E0B; }
    
    .radio-group { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
    .radio-pill { 
        padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 50rem; 
        font-size: 13px; font-weight: 500; color: var(--text-muted); background-color: var(--bg-surface); 
        cursor: pointer; transition: all 0.2s ease; user-select: none; 
    }
    .radio-pill.active { 
        border-color: var(--brand-primary); background-color: var(--brand-light); 
        color: var(--brand-primary); font-weight: 700; box-shadow: 0 2px 8px rgba(153, 27, 27, 0.1);
    }

    /* === FLOATING CHECKOUT FOOTER === */
    .checkout-footer { 
        position: fixed; bottom: 0; left: 0; right: 0; background-color: var(--bg-surface); 
        padding: 16px 20px 24px; box-shadow: var(--shadow-float); z-index: 100; border-top: 1px solid var(--border-color); 
    }
    @media (min-width: 768px) { .checkout-footer { max-width: 480px; margin: 0 auto; border-radius: 24px 24px 0 0; } }
    
    .checkout-flex { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
    .total-label { font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 2px; }
    .total-amount { font-size: 22px; font-weight: 800; color: var(--text-strong); }
    
    .btn-checkout { 
        width: 100%; padding: 16px; background: var(--brand-gradient); color: #FFFFFF; 
        border: none; border-radius: 14px; font-family: inherit; font-size: 16px; font-weight: 700; 
        cursor: pointer; transition: all 0.3s ease; display: flex; justify-content: center; align-items: center; gap: 10px; 
        box-shadow: 0 4px 15px rgba(153, 27, 27, 0.3);
    }
    .btn-checkout:disabled { background: var(--border-color); color: var(--text-placeholder); box-shadow: none; cursor: not-allowed; }
    .btn-checkout:not(:disabled):active { transform: scale(0.98); box-shadow: 0 2px 8px rgba(153, 27, 27, 0.2); }
    
    .item-count-badge { 
        background-color: #FFFFFF; color: var(--brand-primary); width: 26px; height: 26px; 
        border-radius: 50%; display: flex; align-items: center; justify-content: center; 
        font-size: 13px; font-weight: 800; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Empty State */
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); display: none; }
    .empty-state i { font-size: 48px; color: #CBD5E1; margin-bottom: 16px; display: block; }

    .track-card {
        padding: 18px 20px;
        background: linear-gradient(135deg, #fff7f5, #fef2f2);
        border: 1px solid rgba(241, 190, 187, 0.7);
        border-radius: 20px;
        margin: 0 16px 18px;
        box-shadow: 0 8px 15px rgba(153, 27, 27, 0.08);
    }
    .track-card-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--brand-primary);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .track-input-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .track-input-group input {
        flex: 1;
    }
    .track-button {
        padding: 12px 18px;
        border-radius: 14px;
        border: none;
        background: var(--brand-primary);
        color: white;
        cursor: pointer;
        font-weight: 700;
        transition: transform 0.2s ease;
    }
    .track-button:active {
        transform: scale(0.98);
    }
    .track-note {
        margin-top: 10px;
        font-size: 12px;
        color: var(--text-muted);
    }
</style>
@endsection

@section('content')
<form id="orderForm" action="{{ route('order.store') }}" method="POST">
    @csrf

    @if(session('error'))
        <div style="padding: 14px 20px; background-color: #FEF2F2; color: #DC2626; font-size: 14px; font-weight: 600; border-bottom: 1px solid #FECACA; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- STICKY NAVIGATION -->
    <div class="nav-sticky">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari menu favorit Anda...">
        </div>
        <div class="category-tabs">
            <div class="cat-tab active" data-filter="semua"><i class="fas fa-star"></i> Semua Menu</div>
            @foreach($menusByCategory as $key => $category)
                @php
                    // Dynamic icon assignment based on category name
                    $catLower = strtolower($key);
                    $icon = 'fa-utensils'; // Default
                    if (str_contains($catLower, 'minum')) $icon = 'fa-glass-cheers';
                    elseif (str_contains($catLower, 'snack') || str_contains($catLower, 'camilan')) $icon = 'fa-cookie-bite';
                    elseif (str_contains($catLower, 'dessert') || str_contains($catLower, 'manis')) $icon = 'fa-ice-cream';
                    elseif (str_contains($catLower, 'paket')) $icon = 'fa-box-open';
                @endphp
                <div class="cat-tab" data-filter="{{ strtolower($key) }}"><i class="fas {{ $icon }}"></i> {{ $key }}</div>
            @endforeach
        </div>
    </div>

    <section class="section-card track-card">
        <div class="track-card-title">
            <i class="fas fa-location-dot"></i>
            Lacak Pesanan Anda
        </div>
        <div class="track-input-group">
            <input type="number" id="trackOrderId" class="input-control" placeholder="Masukkan Nomor Pesanan" />
            <button type="button" class="track-button" id="trackButton">
                <i class="fas fa-search"></i> Lacak
            </button>
        </div>
        <p class="track-note">Jika HP terputus atau halaman tertutup, ketik nomor pesanan untuk melihat status kembali.</p>
    </section>

    <!-- EMPTY STATE -->
    <div class="empty-state" id="noResult">
        <i class="fas fa-search-minus"></i>
        <h3 style="color: var(--text-strong); font-size: 18px; margin-bottom: 6px; font-weight: 700;">Menu Tidak Ditemukan</h3>
        <p style="font-size: 14px;">Coba gunakan kata kunci lain untuk mencari.</p>
    </div>

    <!-- MENU ITEMS SECTION -->
    <div class="menu-section">
        @foreach($menusByCategory as $key => $category)
            <div class="cat-wrapper" data-cat="{{ strtolower($key) }}">
                <div class="menu-category-title">{{ $key }}</div>
                
                @foreach($category['items'] as $m)
                    @php
                        $isHabis = ($m->status_tersedia === 'Habis');
                        $id = $m->id;
                    @endphp
                    <div class="menu-item-container" data-nama="{{ strtolower($m->nama_item) }}" data-kategori="{{ strtolower($key) }}">
                        <div class="menu-item {{ $isHabis ? 'habis' : '' }}" id="card_{{ $id }}">
                            <div class="item-content">
                                <h3 class="item-name">{{ $m->nama_item }}</h3>
                                <p class="item-desc">{{ $m->deskripsi }}</p>
                                <div class="item-footer">
                                    <span class="item-price">Rp {{ number_format($m->harga, 0, ',', '.') }}</span>
                                    <div class="action-area">
                                        @if($isHabis)
                                            <div class="badge-habis">Habis</div>
                                        @else
                                            <button type="button" class="btn-add" id="addBtn_{{ $id }}" onclick="toggleItem({{ $id }}, {{ $m->harga }})">
                                                <i class="fas fa-plus me-1" style="font-size: 10px;"></i> Tambah
                                            </button>
                                            <div class="qty-control" id="qty_{{ $id }}">
                                                <button type="button" class="qty-btn" onclick="changeQty({{ $id }}, -1)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <span class="qty-num" id="qnum_{{ $id }}">1</span>
                                                <button type="button" class="qty-btn" onclick="changeQty({{ $id }}, 1)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name="qty_{{ $id }}" id="qval_{{ $id }}" value="1">
                                            <input type="checkbox" name="pilihan[]" id="chk_{{ $id }}" value="{{ $id }}" style="display:none;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="item-image-wrapper">
                                <img src="{{ asset('img/' . $m->gambar) }}" class="item-image" alt="{{ $m->nama_item }}" onerror="this.src='https://via.placeholder.com/150?text=Menu'">
                            </div>
                        </div>

                        @if(!$isHabis)
                        <div class="item-expanded" id="expand_{{ $id }}">
                            <div class="options-wrapper">
                                @if($key === 'Makanan')
                                    <div class="option-label"><i class="fas fa-fire"></i> Level Pedas</div>
                                    <div class="radio-group" id="spicewrap_{{ $id }}">
                                        <div class="radio-pill active" onclick="setOption({{ $id }}, 'pedasval', 'Tidak Pedas', this)">Tidak Pedas</div>
                                        <div class="radio-pill" onclick="setOption({{ $id }}, 'pedasval', 'Pedas', this)">Pedas</div>
                                        <div class="radio-pill" onclick="setOption({{ $id }}, 'pedasval', 'Ekstra Pedas', this)">Ekstra Pedas</div>
                                    </div>
                                    <input type="hidden" name="pedas_{{ $id }}" id="pedasval_{{ $id }}" value="Tidak Pedas">
                                @else
                                    <input type="hidden" name="pedas_{{ $id }}" value="-">
                                @endif
                                <div class="option-label"><i class="fas fa-sticky-note" style="color: #64748B;"></i> Catatan Khusus</div>
                                <input type="text" name="catatan_{{ $id }}" class="input-control" placeholder="Contoh: Tanpa bawang, es sedikit...">
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- CUSTOMER DETAILS (Pindah ke bawah setelah pesan makanan) -->
    <section class="section-card" style="margin-bottom: 24px;">
        <h2 class="section-title">
            <i class="fas fa-receipt"></i>
            Detail Pemesanan
        </h2>
        <div class="form-grid">
            <div class="input-group">
                <label><i class="fas fa-chair"></i> Nomor Meja</label>
                <input type="number" name="meja" class="input-control" placeholder="Contoh: 12" min="1" required value="{{ old('meja') }}">
            </div>
            <div class="input-group">
                <label><i class="fas fa-user"></i> Nama Pelanggan</label>
                <input type="text" name="nama" class="input-control" placeholder="Nama Anda" required value="{{ old('nama') }}">
            </div>
            <div class="input-group">
                <label><i class="fas fa-concierge-bell"></i> Tipe Layanan</label>
                <div class="select-wrapper">
                    <select name="jenis_pesanan" class="input-control">
                        <option value="Makan di Tempat" {{ old('jenis_pesanan') == 'Makan di Tempat' ? 'selected' : '' }}>Makan di Tempat</option>
                        <option value="Bawa Pulang" {{ old('jenis_pesanan') == 'Bawa Pulang' ? 'selected' : '' }}>Bawa Pulang</option>
                    </select>
                </div>
            </div>
            <div class="input-group">
                <label><i class="fas fa-wallet"></i> Metode Bayar</label>
                <div class="select-wrapper">
                    <select name="metode_bayar" class="input-control">
                        <option value="Tunai Kasir" {{ old('metode_bayar') == 'Tunai Kasir' ? 'selected' : '' }}>Tunai Kasir</option>
                        <option value="QRIS" {{ old('metode_bayar') == 'QRIS' ? 'selected' : '' }}>QRIS Langsung</option>
                        <option value="Transfer" {{ old('metode_bayar') == 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- FLOATING CHECKOUT FOOTER -->
    <div class="checkout-footer">
        <div class="checkout-flex">
            <div>
                <div class="total-label">Total Estimasi</div>
                <div class="total-amount" id="totalText">Rp 0</div>
            </div>
        </div>
        <button type="submit" class="btn-checkout" id="btnOrder" disabled>
            <i class="fas fa-shopping-bag"></i> Lanjutkan Pembayaran
            <div class="item-count-badge" id="cartBadge" style="display:none">0</div>
        </button>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const cart = {};
let activeFilter = 'semua';

const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number).replace('Rp', 'Rp ');
};

function toggleItem(id, harga) {
    const chk = document.getElementById('chk_' + id);
    const addBtn = document.getElementById('addBtn_' + id);
    const qtyCtrl = document.getElementById('qty_' + id);
    const expand = document.getElementById('expand_' + id);
    const card = document.getElementById('card_' + id);

    if (!chk.checked) {
        chk.checked = true;
        addBtn.style.display = 'none';
        qtyCtrl.style.display = 'flex';
        card.style.borderColor = 'var(--brand-primary)';
        card.style.boxShadow = '0 4px 12px rgba(153, 27, 27, 0.1)';
        if (expand) expand.classList.add('open');
        cart[id] = { harga, qty: 1 };
    } else {
        chk.checked = false;
        addBtn.style.display = 'block';
        qtyCtrl.style.display = 'none';
        card.style.borderColor = 'var(--border-color)';
        card.style.boxShadow = 'var(--shadow-sm)';
        if (expand) expand.classList.remove('open');
        delete cart[id];
        document.getElementById('qnum_' + id).innerText = 1;
        document.getElementById('qval_' + id).value = 1;
    }
    updateCartUI();
}

function changeQty(id, delta) {
    if (!cart[id]) return;
    let newQty = cart[id].qty + delta;
    if (newQty < 1) {
        toggleItem(id, cart[id].harga);
        return;
    }
    cart[id].qty = newQty;
    document.getElementById('qnum_' + id).innerText = newQty;
    document.getElementById('qval_' + id).value = newQty;
    updateCartUI();
}

function setOption(id, hiddenInputId, value, clickedEl) {
    const parent = clickedEl.parentElement;
    parent.querySelectorAll('.radio-pill').forEach(pill => pill.classList.remove('active'));
    clickedEl.classList.add('active');
    document.getElementById(hiddenInputId + '_' + id).value = value;
}

function updateCartUI() {
    let total = 0;
    const distinctItemCount = Object.keys(cart).length;
    for (const id in cart) {
        total += cart[id].harga * cart[id].qty;
    }
    document.getElementById('totalText').innerText = formatRupiah(total);
    const badge = document.getElementById('cartBadge');
    const btn = document.getElementById('btnOrder');
    if (distinctItemCount > 0) {
        badge.style.display = 'flex';
        badge.innerText = distinctItemCount;
        btn.disabled = false;
    } else {
        badge.style.display = 'none';
        btn.disabled = true;
    }
}

const searchInput = document.getElementById('searchInput');
searchInput.addEventListener('input', applyFilter);

document.querySelectorAll('.cat-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        activeFilter = this.dataset.filter;
        
        // Auto scroll tabs smoothly to center the clicked tab
        this.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        
        applyFilter();
    });
});

function applyFilter() {
    const keyword = searchInput.value.toLowerCase().trim();
    let visibleCount = 0;
    document.querySelectorAll('.menu-item-container').forEach(container => {
        const nama = container.dataset.nama || '';
        const kat = container.dataset.kategori;
        const matchSearch = !keyword || nama.includes(keyword);
        const matchFilter = (activeFilter === 'semua') || (kat === activeFilter);
        if (matchSearch && matchFilter) {
            container.style.display = 'block';
            visibleCount++;
        } else {
            container.style.display = 'none';
        }
    });
    document.querySelectorAll('.cat-wrapper').forEach(wrapper => {
        const hasVisibleItems = Array.from(wrapper.querySelectorAll('.menu-item-container')).some(c => c.style.display !== 'none');
        wrapper.style.display = hasVisibleItems ? 'block' : 'none';
    });
    document.getElementById('noResult').style.display = visibleCount === 0 ? 'block' : 'none';
}

document.getElementById('trackButton').addEventListener('click', function() {
    const orderId = document.getElementById('trackOrderId').value.trim();
    if (!orderId) {
        Swal.fire({
            icon: 'warning',
            title: 'Masukkan nomor pesanan',
            text: 'Silakan isi nomor pesanan sebelum melacak.',
            confirmButtonColor: '#991B1B'
        });
        return;
    }
    window.location.href = '{{ url('/order/track') }}/' + encodeURIComponent(orderId);
});

document.getElementById('orderForm').addEventListener('submit', function(e) {
    if (Object.keys(cart).length === 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Keranjang Kosong',
            text: 'Mohon pilih minimal satu menu sebelum melanjutkan pembayaran.',
            confirmButtonColor: '#991B1B'
        });
    }
});
</script>
@endsection