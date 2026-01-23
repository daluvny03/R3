<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Neraca</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { text-align: center; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; }
        .section { margin-top: 30px; }
        .section-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; color: #333; }
        .row { display: flex; justify-content: space-between; padding: 8px 20px; }
        .row.total { border-top: 2px solid #000; font-weight: bold; margin-top: 10px; padding-top: 10px; }
        .amount { text-align: right; }
    </style>
</head>
<body>
    <h1>LAPORAN NERACA</h1>
    <div class="subtitle">{{ $periode }}</div>

    <div class="section">
        <div class="section-title">ASET</div>
        <div class="row">
            <span>Kas</span>
            <span class="amount">Rp {{ number_format($kas, 0, ',', '.') }}</span>
        </div>
        <div class="row">
            <span>Bank</span>
            <span class="amount">Rp {{ number_format($bank, 0, ',', '.') }}</span>
        </div>
        <div class="row total">
            <span>Total Aset</span>
            <span class="amount">Rp {{ number_format($totalAset, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">MODAL</div>
        <div class="row total">
            <span>Modal</span>
            <span class="amount">Rp {{ number_format($modal, 0, ',', '.') }}</span>
        </div>
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