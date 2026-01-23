<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .summary {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .section {
            margin-top: 30px;
        }

        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #e5e7eb;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            border: 1px solid #d1d5db;
        }

        td {
            padding: 8px;
            font-size: 12px;
            border: 1px solid #d1d5db;
        }

        .amount {
            text-align: right;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h1>LAPORAN PENJUALAN</h1>
    <div class="subtitle">{{ $periode }}</div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Penjualan:</strong>
            <strong>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</strong>
        </div>
        <div class="summary-item">
            <strong>Total Transaksi:</strong>
            <strong>{{ $totalTransaksi }} transaksi</strong>
        </div>
        <div class="summary-item">
            <strong>Rata-rata per Transaksi:</strong>
            <strong>Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="section">
        <div class="section-title">PRODUK TERLARIS</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="text-right">Total Terjual</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produkTerlaris as $index => $produk)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $produk->nama_produk }}</td>
                        <td>{{ $produk->kategori }}</td>
                        <td class="text-right">{{ $produk->total_terjual }} unit</td>
                        <td class="text-right">Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PENJUALAN PER METODE PEMBAYARAN</div>
        <table>
            <thead>
                <tr>
                    <th>Metode Pembayaran</th>
                    <th class="text-right">Jumlah Transaksi</th>
                    <th class="text-right">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualanPerMetode as $metode)
                    <tr>
                        <td>{{ ucfirst($metode->metode_pembayaran) }}</td>
                        <td class="text-right">{{ $metode->jumlah_transaksi }}</td>
                        <td class="text-right">Rp {{ number_format($metode->total_penjualan, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PENJUALAN PER KASIR</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Kasir</th>
                    <th class="text-right">Jumlah Transaksi</th>
                    <th class="text-right">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualanPerKasir as $kasir)
                    <tr>
                        <td>{{ $kasir->name }}</td>
                        <td class="text-right">{{ $kasir->jumlah_transaksi }}</td>
                        <td class="text-right">Rp {{ number_format($kasir->total_penjualan, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <hr style="margin-top:40px">

    <table width="100%" style="margin-top:30px;">
        <tr>
            <td width="60%"></td>
            <td width="40%" align="center">
                <p>Disahkan oleh,</p>
                <p><strong>Ertiga POS</strong></p>

                <img src="{{ public_path('images/logo-ertiga.png') }}" width="120" style="margin:15px 0">

                <p style="margin-top:10px;">
                    Tanggal: {{ now()->format('d F Y') }}
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
