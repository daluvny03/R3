<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk #{{ $transaction->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 20px;
            max-width: 300px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px dashed #000;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            margin: 2px 0;
        }
        .info {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        .items {
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 15px;
        }
        .item {
            margin: 8px 0;
        }
        .item-name {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }
        .totals {
            margin: 15px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 12px;
        }
        .total-row.grand-total {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #000;
        }
        .payments {
            margin: 15px 0;
            padding: 10px 0;
            border-top: 1px dashed #000;
            border-bottom: 2px dashed #000;
        }
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 11px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ERTIGA POS</h1>
        <p>Sistem Kasir Cerdas</p>
        <p>Jl. Contoh No. 123, Kota</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <div class="info">
        <div class="info-row">
            <span>No. Transaksi</span>
            <span><strong>#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</strong></span>
        </div>
        <div class="info-row">
            <span>Tanggal</span>
            <span>{{ $transaction->tanggal_transaksi->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span>Kasir</span>
            <span>{{ $transaction->kasir->name }}</span>
        </div>
    </div>

    <div class="items">
        @foreach($transaction->items as $item)
        <div class="item">
            <div class="item-name">{{ $item->product->nama_produk }}</div>
            <div class="item-details">
                <span>{{ $item->jumlah }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                <span><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="totals">
        <div class="total-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
        </div>
        <div class="total-row grand-total">
            <span>TOTAL</span>
            <span>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($transaction->payments->count() > 0)
    <div class="payments">
        <div style="font-weight: bold; margin-bottom: 8px; text-align: center;">PEMBAYARAN</div>
        @php $totalPaid = 0; @endphp
        @foreach($transaction->payments as $payment)
        <div class="payment-row">
            <span>{{ $payment->metode_pembayaran }}</span>
            <span>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</span>
        </div>
        @php $totalPaid += $payment->jumlah_bayar; @endphp
        @endforeach
        
        <div class="payment-row" style="font-weight: bold; margin-top: 8px; padding-top: 8px; border-top: 1px solid #000;">
            <span>Total Bayar</span>
            <span>Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
        </div>
        <div class="payment-row" style="font-weight: bold;">
            <span>Kembalian</span>
            <span>Rp {{ number_format($totalPaid - $transaction->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>TERIMA KASIH</strong></p>
        <p>Telah berbelanja di ERTIGA POS</p>
        <p style="margin-top: 15px; font-size: 10px;">{{ now()->format('d/m/Y H:i:s') }}</p>
        <p style="margin-top: 10px; font-size: 10px;">Powered by ERTIGA POS v1.0</p>
    </div>
</body>
</html>