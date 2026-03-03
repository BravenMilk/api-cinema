<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - {{ $booking_code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #5145cd;
            --primary-hover: #4338ca;
            --success: #10b981;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 480px;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #ecfdf5;
            color: var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 40px;
        }

        h1 {
            color: var(--text-main);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 32px;
        }

        .details-box {
            background: #f9fafb;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
            padding-top: 12px;
            border-top: 1px dashed #e5e7eb;
            margin-top: 12px;
        }

        .label {
            color: var(--text-muted);
        }

        .value {
            color: var(--text-main);
            font-weight: 600;
        }

        .total-label {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
        }

        .total-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 16px;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: white;
            color: var(--text-main);
            border: 1px solid #e5e7eb;
        }

        .btn-outline:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .footer-text {
            margin-top: 24px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
        }

        .badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 8px;
            border-radius: 6px;
            color: white;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="success-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            
            <h1>Pembayaran Berhasil!</h1>
            <p class="subtitle">Tiket Anda telah berhasil dipesan dan siap digunakan.</p>

            <div class="details-box">
                <div class="detail-row">
                    <span class="label">Metode Pembayaran</span>
                    <span class="value">Bank Transfer (Simulasi)</span>
                </div>
                <div class="detail-row">
                    <span class="label">Waktu Transaksi</span>
                    <span class="value">{{ now()->translatedFormat('d F Y H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">ID Transaksi</span>
                    <span class="value">#{{ $booking_code }}</span>
                </div>
                <div class="detail-row">
                    <span class="total-label">Total Bayar</span>
                    <span class="total-value">Rp {{ number_format($total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="actions">
                <a href="#" class="btn btn-primary" onclick="alert('Tiket PDF akan segera diunduh...')">Unduh Tiket (PDF)</a>
                <a href="/" class="btn btn-outline">Kembali ke Beranda</a>
            </div>
        </div>

        <p style="text-align: center;" class="footer-text">
            Terdeteksi via: <span class="badge">Local Simulation Tool</span>
        </p>
    </div>
</body>
</html>
