@extends('layouts.app')

@section('styles')
<style>
    body { padding-bottom: 120px; }
    .section-card { background-color: var(--bg-surface); margin-top: 12px; padding: 20px; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }
    .section-title { font-size: 15px; font-weight: 700; color: var(--text-strong); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .section-title .icon { color: var(--text-muted); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .input-group { display: flex; flex-direction: column; gap: 6px; }
    .input-group label { font-size: 12px; font-weight: 600; color: var(--text-base); }
    .input-control { width: 100%; padding: 12px 14px; background-color: var(--bg-input); border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; color: var(--text-strong); transition: all 0.2s; outline: none; appearance: none; -webkit-appearance: none; }
    .input-control:focus { background-color: var(--bg-surface); border-color: var(--brand-primary); box-shadow: 0 0 0 3px var(--brand-light); }
    .select-wrapper { position: relative; }
    .select-wrapper::after { content: ''; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: center; pointer-events: none; }

    .nav-sticky { position: sticky; top: 0; background-color: var(--bg-surface); z-index: 20; border-bottom: 1px solid var(--border-color); box-shadow: var(--shadow-sm); }
    .search-container { padding: 12px 20px; position: relative; }
    .search-icon { position: absolute; left: 32px; top: 50%; transform: translateY(-50%); color: var(--text-placeholder); }
    .search-input { width: 100%; padding: 10px 14px 10px 38px; background-color: var(--bg-input); border: 1px solid var(--border-color); border-radius: var(--radius-lg); font-family: inherit; font-size: 14px; outline: none; transition: border-color 0.2s; }
    .search-input:focus { border-color: var(--brand-primary); }
    .category-tabs { display: flex; gap: 12px; padding: 0 20px 12px; overflow-x: auto; scrollbar-width: none; }
    .category-tabs::-webkit-scrollbar { display: none; }
    .cat-tab { padding: 8px 16px; border-radius: 20px; background-color: var(--bg-input); color: var(--text-muted); font-size: 13px; font-weight: 600; white-space: nowrap; cursor: pointer; border: 1px solid var(--border-color); transition: all 0.2s; }
    .cat-tab.active { background-color: var(--brand-primary); color: #FFFFFF; border-color: var(--brand-primary); }

    .menu-section { background-color: var(--bg-surface); padding-bottom: 20px; }
    .menu-category-title { padding: 24px 20px 12px; font-size: 16px; font-weight: 700; color: var(--text-strong); background-color: var(--bg-surface); }
    .menu-item { display: flex; padding: 16px 20px; border-bottom: 1px solid var(--bg-app); gap: 16px; }
    .menu-item.habis { opacity: 0.5; pointer-events: none; }
    .item-content { flex: 1; display: flex; flex-direction: column; }
    .item-name { font-size: 15px; font-weight: 600; color: var(--text-strong); margin-bottom: 4px; }
    .item-desc { font-size: 13px; color: var(--text-muted); line-height: 1.4; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .item-footer { margin-top: auto; display: flex; align-items: center; justify-content: space-between; }
    .item-price { font-size: 15px; font-weight: 700; color: var(--text-strong); }
    .item-image-wrapper { width: 100px; height: 100px; flex-shrink: 0; position: relative; }
    .item-image { width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md); border: 1px solid var(--border-color); }

    .btn-add { padding: 6px 16px; border-radius: 20px; border: 1px solid var(--brand-primary); background-color: #FFFFFF; color: var(--brand-primary); font-family: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .btn-add:active { background-color: var(--brand-light); }
    .qty-control { display: none; align-items: center; background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 20px; height: 32px; overflow: hidden; }
    .qty-btn { width: 32px; height: 100%; background: transparent; border: none; color: var(--brand-primary); display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .qty-num { font-size: 13px; font-weight: 600; color: var(--text-strong); min-width: 24px; text-align: center; }
    .badge-habis { padding: 4px 8px; background-color: #FEE2E2; color: #DC2626; font-size: 11px; font-weight: 600; border-radius: 4px; }

    .item-expanded { display: none; padding: 0 20px 16px; background-color: var(--bg-surface); border-bottom: 1px solid var(--bg-app); }
    .item-expanded.open { display: block; }
    .options-wrapper { padding-top: 12px; border-top: 1px dashed var(--border-color); }
    .option-label { font-size: 12px; font-weight: 600; color: var(--text-strong); margin-bottom: 8px; }
    .radio-group { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
    .radio-pill { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); font-size: 13px; color: var(--text-base); background-color: var(--bg-surface); cursor: pointer; transition: all 0.2s; user-select: none; }
    .radio-pill.active { border-color: var(--brand-primary); background-color: var(--brand-light); color: var(--brand-primary); font-weight: 600; }

    .checkout-footer { position: fixed; bottom: 0; left: 0; right: 0; max-width: 480px; margin: 0 auto; background-color: var(--bg-surface); padding: 16px 20px 24px; box-shadow: var(--shadow-float); z-index: 100; border-top: 1px solid var(--border-color); }
    .checkout-flex { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    .total-label { font-size: 13px; color: var(--text-muted); margin-bottom: 2px; }
    .total-amount { font-size: 20px; font-weight: 700; color: var(--text-strong); }
    .btn-checkout { width: 100%; padding: 14px; background-color: var(--brand-primary); color: #FFFFFF; border: none; border-radius: var(--radius-md); font-family: inherit; font-size: 15px; font-weight: 600; cursor: pointer; transition: background-color 0.2s; display: flex; justify-content: center; align-items: center; gap: 8px; }
    .btn-checkout:disabled { background-color: var(--border-color); color: var(--text-placeholder); cursor: not-allowed; }
    .item-count-badge { background-color: #FFFFFF; color: var(--brand-primary); width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; }
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); display: none; background-color: var(--bg-surface); }
    .empty-state svg { margin: 0 auto 16px; color: var(--border-color); width: 48px; height: 48px; }
</style>
@endsection

@section('content')
<form id="orderForm" action="{{ route('order.store') }}" method="POST">
    @csrf

    @if(session('error'))
        <div style="padding: 12px 20px; background-color: #FEE2E2; color: #DC2626; font-size: 14px; border-bottom: 1px solid #FECACA;">
            {{ session('error') }}
        </div>
    @endif

    <!-- CUSTOMER DETAILS -->
    <section class="section-card">
        <h2 class="section-title">
            <svg class="icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Detail Pemesanan
        </h2>
        <div class="form-grid">
            <div class="input-group">
                <label>Nomor Meja</label>
                <input type="number" name="meja" class="input-control" placeholder="Contoh: 12" min="1" required value="{{ old('meja') }}">
            </div>
            <div class="input-group">
                <label>Nama Pelanggan</label>
                <input type="text" name="nama" class="input-control" placeholder="Nama Anda" required value="{{ old('nama') }}">
            </div>
            <div class="input-group">
                <label>Tipe Layanan</label>
                <div class="select-wrapper">
                    <select name="jenis_pesanan" class="input-control">
                        <option value="Makan di Tempat" {{ old('jenis_pesanan') == 'Makan di Tempat' ? 'selected' : '' }}>Makan di Tempat</option>
                        <option value="Bawa Pulang" {{ old('jenis_pesanan') == 'Bawa Pulang' ? 'selected' : '' }}>Bawa Pulang</option>
                    </select>
                </div>
            </div>
            <div class="input-group">
                <label>Metode Pembayaran</label>
                <div class="select-wrapper">
                    <select name="metode_bayar" class="input-control">
                        <option value="Tunai Kasir" {{ old('metode_bayar') == 'Tunai Kasir' ? 'selected' : '' }}>Tunai Kasir</option>
                        <option value="QRIS" {{ old('metode_bayar') == 'QRIS' ? 'selected' : '' }}>QRIS Langsung</option>
                        <option value="Transfer" {{ old('metode_bayar') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- STICKY NAVIGATION -->
    <div class="nav-sticky">
        <div class="search-container">
            <svg class="icon search-icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari menu makanan atau minuman...">
        </div>
        <div class="category-tabs">
            <div class="cat-tab active" data-filter="semua">Semua Menu</div>
            @foreach($menusByCategory as $key => $category)
                <div class="cat-tab" data-filter="{{ strtolower($key) }}">{{ $key }}</div>
            @endforeach
        </div>
    </div>

    <div class="empty-state" id="noResult">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <h3 style="color: var(--text-strong); font-size: 16px; margin-bottom: 4px;">Pencarian Tidak Ditemukan</h3>
        <p style="font-size: 14px;">Coba gunakan kata kunci lain untuk mencari menu.</p>
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
                                            <button type="button" class="btn-add" id="addBtn_{{ $id }}" onclick="toggleItem({{ $id }}, {{ $m->harga }})">Tambah</button>
                                            <div class="qty-control" id="qty_{{ $id }}">
                                                <button type="button" class="qty-btn" onclick="changeQty({{ $id }}, -1)">
                                                    <svg class="icon" style="width:16px; height:16px;" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                </button>
                                                <span class="qty-num" id="qnum_{{ $id }}">1</span>
                                                <button type="button" class="qty-btn" onclick="changeQty({{ $id }}, 1)">
                                                    <svg class="icon" style="width:16px; height:16px;" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                </button>
                                            </div>
                                            <input type="hidden" name="qty_{{ $id }}" id="qval_{{ $id }}" value="1">
                                            <input type="checkbox" name="pilihan[]" id="chk_{{ $id }}" value="{{ $id }}" style="display:none;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="item-image-wrapper">
                                <img src="{{ asset('img/' . $m->gambar) }}" class="item-image" alt="{{ $m->nama_item }}" onerror="this.src='https://via.placeholder.com/100?text=Food'">
                            </div>
                        </div>

                        @if(!$isHabis)
                        <div class="item-expanded" id="expand_{{ $id }}">
                            <div class="options-wrapper">
                                @if($key === 'Makanan')
                                    <div class="option-label">Level Pedas</div>
                                    <div class="radio-group" id="spicewrap_{{ $id }}">
                                        <div class="radio-pill active" onclick="setOption({{ $id }}, 'pedasval', 'Tidak Pedas', this)">Tidak Pedas</div>
                                        <div class="radio-pill" onclick="setOption({{ $id }}, 'pedasval', 'Pedas', this)">Pedas</div>
                                        <div class="radio-pill" onclick="setOption({{ $id }}, 'pedasval', 'Ekstra Pedas', this)">Ekstra Pedas</div>
                                    </div>
                                    <input type="hidden" name="pedas_{{ $id }}" id="pedasval_{{ $id }}" value="Tidak Pedas">
                                @else
                                    <input type="hidden" name="pedas_{{ $id }}" value="-">
                                @endif
                                <div class="option-label">Catatan Pesanan</div>
                                <input type="text" name="catatan_{{ $id }}" class="input-control" placeholder="Contoh: Tanpa bawang, es sedikit...">
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- FLOATING CHECKOUT FOOTER -->
    <div class="checkout-footer">
        <div class="checkout-flex">
            <div>
                <div class="total-label">Total Pembayaran</div>
                <div class="total-amount" id="totalText">Rp 0</div>
            </div>
        </div>
        <button type="submit" class="btn-checkout" id="btnOrder" disabled>
            Lanjutkan Pesanan
            <div class="item-count-badge" id="cartBadge" style="display:none">0</div>
        </button>
    </div>
</form>

<script>
const cart = {};

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

    if (!chk.checked) {
        chk.checked = true;
        addBtn.style.display = 'none';
        qtyCtrl.style.display = 'flex';
        if (expand) expand.classList.add('open');
        cart[id] = { harga, qty: 1 };
    } else {
        chk.checked = false;
        addBtn.style.display = 'block';
        qtyCtrl.style.display = 'none';
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

document.getElementById('orderForm').addEventListener('submit', function(e) {
    if (Object.keys(cart).length === 0) {
        e.preventDefault();
        alert('Mohon pilih menu sebelum melanjutkan pesanan.');
    }
});
</script>
@endsection
