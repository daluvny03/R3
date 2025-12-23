<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba-Rugi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { text-align: center; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .section { margin-top: 30px; }
        .section-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; color: #333; }
        .row { display: flex; justify-content: space-between; padding: 8px 20px; }
        .row.total { border-top: 2px solid #000; font-weight: bold; margin-top: 10px; padding-top: 10px; }
        .row.final { border-top: 3px solid #000; font-size: 18px; margin-top: 20px; padding-top: 15px; }
        .amount { text-align: right; }
    </style>
</head>
<body>
    <h1>LAPORAN LABA-RUGI</h1>
    <div class="subtitle">{{ $periode }}</div>

    <div class="section">
        <div class="section-title">PENDAPATAN</div>
        <div class="row">
            <span>Total Penjualan ({{ $jumlahTransaksi }} transaksi)</span>
            <span class="amount">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
        </div>
        <div class="row total">
            <span>Total Pendapatan</span>
            <span class="amount">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">BEBAN OPERASIONAL</div>
        @foreach($bebanPerKategori as $kategori => $jumlah)
        <div class="row">
            <span>{{ ucfirst($kategori) }}</span>
            <span class="amount">Rp {{ number_format($jumlah, 0, ',', '.') }}</span>
        </div>
        @endforeach
        <div class="row total">
            <span>Total Beban</span>
            <span class="amount">Rp {{ number_format($totalBeban, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="row final">
        <span>LABA BERSIH</span>
        <span class="amount">Rp {{ number_format($labaBersih, 0, ',', '.') }}</span>
    </div>
</body>
</html>
