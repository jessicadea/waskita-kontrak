<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dokumen Kontrak Pemesanan</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 34px;
            color: #111827;
            line-height: 1.5;
        }

        .watermark {
            position: fixed;
            top: 32%;
            left: 14%;
            width: 520px;
            opacity: 0.045;
            z-index: -1000;
        }

        .header {
            border-bottom: 4px solid #0f2a5f;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }

        .logo {
            width: 175px;
        }

        .company {
            font-size: 18px;
            font-weight: bold;
            color: #0f2a5f;
            text-transform: uppercase;
            text-align: right;
        }

        .subtitle {
            font-size: 10.5px;
            color: #4b5563;
            margin-top: 2px;
            text-align: right;
        }

        .doc-title {
            text-align: center;
            margin: 22px 0 18px;
        }

        .doc-title h1 {
            font-size: 18px;
            margin: 0;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .doc-title p {
            margin: 5px 0 0;
            color: #6b7280;
            font-size: 11px;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .meta td {
            border: 1px solid #d1d5db;
            padding: 7px 9px;
        }

        .meta .label {
            width: 23%;
            background: #f3f4f6;
            font-weight: bold;
            color: #374151;
        }

        .badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .badge-approved {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .badge-pending {
            background: #ffedd5;
            color: #9a3412;
            border: 1px solid #fdba74;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .section {
            margin-top: 18px;
        }

        .section-title {
            background: #0f2a5f;
            color: white;
            padding: 7px 10px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid #d1d5db;
            padding: 8px 9px;
            vertical-align: top;
        }

        .info-table .label {
            width: 28%;
            background: #f9fafb;
            font-weight: bold;
            color: #374151;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th {
            background: #e5e7eb;
            border: 1px solid #9ca3af;
            padding: 8px;
            font-size: 10px;
            text-align: left;
        }

        .product-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        .note-box {
            border: 1px solid #d1d5db;
            background: #f9fafb;
            padding: 10px 12px;
            text-align: justify;
        }

        .sign-table {
            width: 100%;
            margin-top: 45px;
            border-collapse: collapse;
        }

        .sign-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            border: none;
            padding: 0 20px;
        }

        .sign-space {
            height: 65px;
        }

        .sign-name {
            border-top: 1px solid #111827;
            padding-top: 6px;
            font-weight: bold;
        }

        .sign-role {
            font-size: 10px;
            color: #4b5563;
            margin-top: 2px;
        }

        .footer {
            margin-top: 35px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            font-size: 9px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>

<body>
    @php
        $logoPath = public_path('images/logo waskita.png');

        $statusClass = match($order->status_verify) {
            'approved' => 'badge-approved',
            'rejected' => 'badge-rejected',
            default => 'badge-pending',
        };

        $statusText = match($order->status_verify) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };

        $tanggalKontrak = $order->updated_at
            ? \Carbon\Carbon::parse($order->updated_at)->translatedFormat('d F Y')
            : now()->translatedFormat('d F Y');

        $tanggalPemesanan = $order->created_at
            ? \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y')
            : '-';

        $tanggalPengiriman = $order->delivery_date
            ? \Carbon\Carbon::parse($order->delivery_date)->translatedFormat('d F Y')
            : '-';

        $items = $order->items ?? collect();
    @endphp

    @if(file_exists($logoPath))
        <img src="{{ $logoPath }}" class="watermark">
    @endif

    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 32%;">
                    @if(file_exists($logoPath))
                        <img src="{{ $logoPath }}" class="logo">
                    @endif
                </td>
                <td style="width: 68%;">
                    <div class="company">PT Waskita Beton Precast Tbk</div>
                    <div class="subtitle">Sistem Monitoring Kontrak Pemesanan Produk Beton Precast</div>
                    <div class="subtitle">Dokumen ini dibuat secara otomatis oleh sistem</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="doc-title">
        <h1>Dokumen Kontrak Pemesanan</h1>
        <p>
            Nomor Kontrak:
            WBP/SPK/{{ $order->created_at->format('Y') }}/{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
        </p>
    </div>

    <table class="meta">
        <tr>
            <td class="label">Nomor Order</td>
            <td>ORD-{{ $order->created_at->format('Y') }}-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
            <td class="label">Status Kontrak</td>
            <td><span class="badge {{ $statusClass }}">{{ $statusText }}</span></td>
        </tr>
        <tr>
            <td class="label">Tanggal Kontrak</td>
            <td>{{ $tanggalKontrak }}</td>
            <td class="label">Tanggal Pemesanan</td>
            <td>{{ $tanggalPemesanan }}</td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Informasi Client</div>
        <table class="info-table">
            <tr>
                <td class="label">Nama Pemesan</td>
                <td>{{ $order->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Email Pemesan</td>
                <td>{{ $order->user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Perusahaan</td>
                <td>{{ $order->company_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Perusahaan</td>
                <td>{{ $order->company_type ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Informasi Project</div>
        <table class="info-table">
            <tr>
                <td class="label">Nama Project</td>
                <td>{{ $order->project_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Lokasi Project</td>
                <td>{{ $order->project_location ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Pengiriman</td>
                <td>{{ $tanggalPengiriman }}</td>
            </tr>
            <tr>
                <td class="label">Kondisi Pengiriman</td>
                <td>{{ $order->delivery_cond ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Percepatan Produksi</td>
                <td>{{ $order->requires_acceleration ? 'Diajukan' : 'Tidak diajukan' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Detail Produk Pemesanan</div>

        <table class="product-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 22%;">Produk</th>
                    <th style="width: 18%;">Type</th>
                    <th style="width: 18%;">Volume / Ukuran</th>
                    <th style="width: 10%;">Qty</th>
                    <th>Spesifikasi / Catatan</th>
                </tr>
            </thead>
            <tbody>
                @if($items->count() > 0)
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product->product_name ?? '-' }}</td>
                            <td>{{ $item->variant->type_name ?? '-' }}</td>
                            <td>
                                @if($item->selectedVolume)
                                    {{ $item->selectedVolume->volume_value }} {{ $item->selectedVolume->unit }}
                                @else
                                    {{ $item->volume ?? '-' }}
                                @endif
                            </td>
                            <td>{{ number_format($item->quantity ?? 0) }} unit</td>
                            <td>{{ $item->product_spec ?: '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>1</td>
                        <td>{{ $order->product->product_name ?? '-' }}</td>
                        <td>{{ $order->variant->type_name ?? '-' }}</td>
                        <td>
                            @if($order->selectedVolume)
                                {{ $order->selectedVolume->volume_value }} {{ $order->selectedVolume->unit }}
                            @else
                                {{ $order->volume ?? '-' }}
                            @endif
                        </td>
                        <td>{{ number_format($order->quantity ?? 0) }} unit</td>
                        <td>{{ $order->product_spec ?: '-' }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Keterangan Dokumen</div>
        <div class="note-box">
            Dokumen kontrak pemesanan ini dibuat berdasarkan data pemesanan yang telah tercatat
            pada Sistem Monitoring Kontrak Pemesanan Produk PT Waskita Beton Precast Tbk.
            Dokumen ini digunakan sebagai dasar informasi pemesanan, proses produksi,
            pengiriman, serta monitoring pekerjaan sesuai data order yang telah diverifikasi.
        </div>
    </div>

    @if($order->verify_note)
        <div class="section">
            <div class="section-title">Catatan Verifikasi</div>
            <div class="note-box">
                {{ $order->verify_note }}
            </div>
        </div>
    @endif

    <table class="sign-table">
        <tr>
            <td>
                <strong>PIHAK PERTAMA</strong><br>
                PT Waskita Beton Precast Tbk
                <div class="sign-space"></div>
                <div class="sign-name">Administrator</div>
                <div class="sign-role">PT Waskita Beton Precast Tbk</div>
            </td>
            <td>
                <strong>PIHAK KEDUA</strong><br>
                {{ $order->company_name ?? 'Client' }}
                <div class="sign-space"></div>
                <div class="sign-name">{{ $order->user->name ?? 'Pemesan' }}</div>
                <div class="sign-role">Pemesan</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini dicetak pada {{ now()->translatedFormat('d F Y H:i') }} melalui
        Sistem Monitoring Kontrak Pemesanan Produk PT Waskita Beton Precast Tbk.
    </div>
</body>
</html>