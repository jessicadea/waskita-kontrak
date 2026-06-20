<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kontrak Pemesanan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 40px;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 8px;
            border: 1px solid #000;
        }

        .label {
            width: 35%;
            font-weight: bold;
            background: #f3f3f3;
        }

        .signature {
            margin-top: 80px;
            width: 100%;
        }

        .signature td {
            border: none;
            text-align: center;
            padding-top: 60px;
        }
    </style>
</head>
<body>

    <h1>KONTRAK PEMESANAN PRODUK PRECAST</h1>

    <p>
        Pada hari ini dibuat perjanjian pemesanan produk precast antara:
    </p>

    <p>
        <strong>PT Waskita Precast</strong> sebagai pihak penyedia produk,
        dan <strong>{{ $order->company_name }}</strong>
        sebagai pihak pemesan.
    </p>

    <table>
        <tr>
            <td class="label">Nomor Order</td>
            <td>ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="label">Nama Client</td>
            <td>{{ $order->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Perusahaan</td>
            <td>{{ $order->company_name }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Perusahaan</td>
            <td>{{ $order->company_type }}</td>
        </tr>
        <tr>
            <td class="label">Nama Project</td>
            <td>{{ $order->project_name }}</td>
        </tr>
        <tr>
            <td class="label">Lokasi Project</td>
            <td>{{ $order->project_location }}</td>
        </tr>
        <tr>
            <td class="label">Produk</td>
            <td>{{ $order->product->product_name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jumlah</td>
            <td>{{ number_format($order->quantity) }} unit</td>
        </tr>
        <tr>
            <td class="label">Tanggal Kirim</td>
            <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Kondisi Pengiriman</td>
            <td>{{ $order->delivery_cond }}</td>
        </tr>
        <tr>
            <td class="label">Spesifikasi</td>
            <td>{{ $order->product_spec ?: '-' }}</td>
        </tr>
    </table>

    <p style="margin-top: 30px;">
        Dokumen ini dibuat secara otomatis oleh sistem Monitoring Kontrak PT Waskita Precast.
    </p>

    <table class="signature">
        <tr>
            <td>
                Pihak Pemesan
                <br><br><br><br>
                ______________________
            </td>
            <td>
                PT Waskita Precast
                <br><br><br><br>
                ______________________
            </td>
        </tr>
    </table>

</body>
</html>