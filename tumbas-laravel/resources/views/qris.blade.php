<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pembayaran QRIS — Rumah Makan A</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 for Premium Popups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* === PREMIUM DESIGN TOKENS === */
        :root {
            --brand-primary: #991B1B; /* Deep Crimson */
            --brand-hover: #7F1D1D;
            --brand-light: #FEF2F2;
            --brand-gradient: linear-gradient(135deg, #991B1B 0%, #B91C1C 100%);
            
            --bg-app: #F4F7F9;
            --bg-surface: #FFFFFF;
            --bg-input: #F8FAFC;
            
            --text-strong: #0F172A;
            --text-base: #334155;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
            
            --radius-md: 12px;
            --radius-lg: 24px;
            
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.02);
            --shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            --shadow-float: 0 -8px 24px rgba(0,0,0,0.06);
            
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }

        body { 
            background: var(--bg-app); 
            color: var(--text-base); 
            padding-bottom: 120px; 
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.01em;
        }

        .app-container { 
            max-width: 480px; 
            margin: 0 auto; 
            background: var(--bg-app); 
            min-height: 100vh; 
            padding: 16px;
        }

        /* === ELEGANT HEADER === */
        .header { 
            background: var(--bg-surface); 
            padding: 24px 20px; 
            border-radius: var(--radius-lg);
            border: 1px solid rgba(226, 232, 240, 0.8); 
            text-align: center; 
            box-shadow: var(--shadow-sm);
            margin-bottom: 16px;
        }
        
        .brand-name { 
            font-size: 20px; 
            font-weight: 800; 
            color: var(--brand-primary); 
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .order-id { 
            font-size: 13.5px; 
            color: var(--text-muted); 
            margin-top: 6px; 
            font-weight: 600;
        }

        /* === CENTRAL PAYMENTS CARD === */
        .status-card { 
            background: var(--bg-surface); 
            padding: 32px 24px; 
            border-radius: var(--radius-lg); 
            border: 1px solid rgba(226, 232, 240, 0.8); 
            text-align: center; 
            box-shadow: var(--shadow-md); 
        }

        .status-icon { 
            width: 64px; height: 64px; 
            border-radius: 50%; 
            background: var(--brand-light); 
            color: var(--brand-primary); 
            display: flex; align-items: center; justify-content: center; 
            font-size: 24px; margin: 0 auto 20px; 
            box-shadow: 0 8px 16px rgba(153, 27, 27, 0.1);
        }

        .status-title { 
            font-size: 20px; 
            font-weight: 800; 
            color: var(--text-strong); 
            margin-bottom: 8px; 
            letter-spacing: -0.5px;
        }

        .status-msg { 
            font-size: 13.5px; 
            color: var(--text-muted); 
            line-height: 1.6; 
            font-weight: 500;
        }
        
        /* === PREMIUM QRIS SCAN AREA === */
        .qris-container {
            position: relative;
            background: var(--bg-input);
            padding: 24px;
            border-radius: var(--radius-lg);
            margin: 24px 0;
            border: 1px dashed var(--border-color);
        }

        .qris-img-wrapper {
            background: #ffffff;
            padding: 16px;
            border-radius: 16px;
            display: inline-block;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .qris-img-wrapper::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: var(--brand-primary);
            box-shadow: 0 0 8px var(--brand-primary);
            animation: qris-scanner 3s infinite linear;
        }

        @keyframes qris-scanner {
            0% { top: 0%; }
            50% { top: 100%; }
            100% { top: 0%; }
        }

        .qris-img { 
            width: 100%; 
            max-width: 220px; 
            display: block; 
            height: auto;
        }

        /* === STEP DIRECTIONS === */
        .pay-instructions {
            text-align: left;
            background: var(--bg-input);
            padding: 16px;
            border-radius: var(--radius-md);
            margin-top: 20px;
            border: 1px solid var(--border-color);
        }
        .instruction-item {
            display: flex;
            gap: 12px;
            font-size: 12.5px;
            color: var(--text-base);
            font-weight: 500;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        .instruction-item:last-child { margin-bottom: 0; }
        .instruction-item span {
            width: 20px; height: 20px;
            background-color: var(--brand-primary);
            color: #fff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center; justify-content: center;
            font-weight: 700;
            font-size: 11px;
            flex-shrink: 0;
        }
        
        .total-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            margin-top: 24px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .total-val { 
            font-size: 28px; 
            font-weight: 800; 
            color: var(--brand-primary); 
            margin: 6px 0; 
            letter-spacing: -0.5px;
        }

        /* === FLOATING FOOTER ACTION === */
        .footer { 
            position: fixed; bottom: 0; left: 0; right: 0; 
            max-width: 480px; margin: 0 auto; 
            background: var(--bg-surface); 
            padding: 16px 20px 24px; 
            border-top: 1px solid var(--border-color); 
            box-shadow: var(--shadow-float); 
            z-index: 100;
            border-radius: 20px 20px 0 0;
        }

        .btn-new { 
            display: flex; 
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%; 
            background: var(--brand-gradient); 
            color: #fff; 
            text-decoration: none; 
            padding: 16px; 
            border-radius: 14px; 
            font-weight: 700; 
            font-size: 15.5px; 
            box-shadow: 0 4px 15px rgba(153, 27, 27, 0.3);
            transition: all 0.2s ease;
        }

        .btn-new:active {
            transform: scale(0.98);
            box-shadow: 0 2px 8px rgba(153, 27, 27, 0.2);
        }
        .tracking-tip {
            margin-top: 10px;
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }
        .tracking-tip a {
            color: var(--brand-primary);
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="app-container">
    
    <!-- Branding Header -->
    <header class="header">
        <h1 class="brand-name">
            <i class="fas fa-utensils"></i>
            Rumah Makan A
        </h1>
        <p class="order-id">
            <i class="fas fa-receipt me-1 opacity-75"></i> Pesanan #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} &nbsp;·&nbsp; <strong class="text-dark">{{ $order->nama_pelanggan }}</strong>
        </p>
    </header>
    <p class="tracking-tip">
        <i class="fas fa-clock me-1"></i>
        Nomor pesanan Anda adalah <strong>#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong>. Jika halaman tertutup, buka kembali <a href="{{ route('order.track', ['id' => $order->id]) }}">Lacak Pesanan</a>.
    </p>

    <!-- Payment Box Content -->
    <div class="status-card">
        <div class="status-icon">
            <i class="fas fa-qrcode"></i>
        </div>
        <h2 class="status-title">Metode: {{ $order->metode_bayar }}</h2>
        <p class="status-msg">Silakan simpan/scan QR Code di bawah menggunakan m-banking atau e-wallet (GoPay, OVO, Dana, LinkAja) untuk melunasi pesanan Anda.</p>
        
        <!-- Scan Canvas Container -->
        <div class="qris-container">
            <div class="qris-img-wrapper">
                <img src="{{ asset('img/qris.jpg') }}" alt="QRIS" class="qris-img" onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=RM_A_ORDER_{{ $order->id }}';">
            </div>
            
            <div class="pay-instructions">
                <div class="instruction-item">
                    <span>1</span>
                    <p>Simpan/screenshot QR Code di atas</p>
                </div>
                <div class="instruction-item">
                    <span>2</span>
                    <p>Buka aplikasi pembayaran pilihan Anda, pilih opsi "Scan/Bayar"</p>
                </div>
                <div class="instruction-item">
                    <span>3</span>
                    <p>Masukkan nominal sesuai total tagihan di bawah ini</p>
                </div>
            </div>
        </div>
        
        <p class="total-label">Total Tagihan Anda</p>
        <div class="total-val">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
    </div>
</div>

<!-- Floating Action Button -->
<div class="footer">
    <a href="{{ route('order.track', ['id' => $order->id]) }}" class="btn-new" id="payButton">
        <i class="fas fa-wallet"></i>
        <span>Saya Sudah Bayar</span>
    </a>
</div>

<script>
    document.getElementById('payButton').addEventListener('click', function(e) {
        e.preventDefault();
        const targetUrl = this.getAttribute('href');
        
        // Premium SweetAlert2 Dialog
        Swal.fire({
            title: 'Pembayaran Dikirim',
            text: 'Terima kasih, pembayaran Anda sedang dicek oleh kasir. Silakan tunggu sebentar di meja Anda.',
            icon: 'success',
            confirmButtonText: 'Oke, Mengerti',
            confirmButtonColor: '#991B1B',
            customClass: {
                popup: 'rounded-lg-custom'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = targetUrl;
            }
        });
    });
</script>

</body>
</html>