<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
body{
    font-family: DejaVu Sans, sans-serif;
    font-size:11px;
    color:#222;
    line-height:1.5;
}

.header{
    border-bottom:3px solid #1E40AF;
    padding-bottom:15px;
    margin-bottom:20px;
}

.header table{
    border:none;
}

.header td{
    border:none;
    vertical-align:top;
}

.logo{
    width:115px;
}

.title{
    font-size:22px;
    color:#1E3A8A;
    font-weight:bold;
}

.subtitle{
    color:#6B7280;
    margin-top:3px;
}

.section{
    margin-top:22px;
}

.section-title{
    font-size:14px;
    color:#1E3A8A;
    font-weight:bold;
    margin-bottom:10px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#EEF2FF;
    border:1px solid #D1D5DB;
    padding:8px;
    text-align:left;
}

td{
    border:1px solid #E5E7EB;
    padding:8px;
}

.info td:first-child{
    width:180px;
    font-weight:bold;
    background:#F9FAFB;
}

.progress-box{
    width:100%;
    height:12px;
    background:#E5E7EB;
    border-radius:8px;
    overflow:hidden;
}

.progress-bar{
    height:12px;
    background:#2563EB;
}

.badge{
    display:inline-block;
    background:#DBEAFE;
    color:#1D4ED8;
    padding:4px 10px;
    border-radius:20px;
    font-weight:bold;
}

.footer{
    margin-top:40px;
}

.signature{
    width:220px;
    float:right;
    text-align:center;
}

.small{
    color:#6B7280;
    font-size:10px;
}
</style>

</head>

<body>

<div class="header">
    <table>
        <tr>
            <td style="width:130px;">
                <img src="{{ public_path('images/logo waskita.png') }}" class="logo">
            </td>

            <td>
                <div class="title">
                    LAPORAN MONITORING PROJECT
                </div>

                <div class="subtitle">
                    Sistem Pencatatan dan Monitoring Kontrak Pemesanan Produk
                </div>

                <div class="subtitle">
                    PT Waskita Beton Precast
                </div>
            </td>

            <td style="width:160px; text-align:right;">
                <b>No. Laporan</b><br>
                PRJ-{{ date('Y') }}-{{ str_pad($project->id,4,'0',STR_PAD_LEFT) }}

                <br><br>

                <b>Tanggal Cetak</b><br>
                {{ now()->format('d M Y') }}
            </td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">INFORMASI PROJECT</div>

    <table class="info">
        <tr>
            <td>Nama Project</td>
            <td>{{ $project->project_name }}</td>
        </tr>

        <tr>
            <td>Client</td>
            <td>{{ $project->order->user->name }}</td>
        </tr>

        <tr>
            <td>Produk</td>
            <td>{{ $project->order->product->product_name }}</td>
        </tr>

        <tr>
            <td>Status</td>
            <td>
                <span class="badge">
                    {{ ucfirst(str_replace('_',' ',$project->status)) }}
                </span>
            </td>
        </tr>

        <tr>
            <td>Periode</td>
            <td>
                {{ \Carbon\Carbon::parse($project->start_date)->format('d M Y') }}
                -
                {{ \Carbon\Carbon::parse($project->due_date)->format('d M Y') }}
            </td>
        </tr>

        <tr>
            <td>Progress</td>
            <td>
                <b>{{ $project->progress_percent }}%</b>

                <br><br>

                <div class="progress-box">
                    <div class="progress-bar" style="width:{{ $project->progress_percent }}%;"></div>
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">PEGAWAI YANG DITUGASKAN</div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Role</th>
            </tr>
        </thead>

        <tbody>
            @forelse($project->assignments as $assignment)
                <tr>
                    <td>{{ $assignment->employee->employee_name }}</td>
                    <td>{{ $assignment->employee->position }}</td>
                    <td>{{ $assignment->assignment_role ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Belum ada pegawai yang ditugaskan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">RIWAYAT PROGRESS</div>

    <table>
        <thead>
            <tr>
                <th width="25%">Tanggal</th>
                <th width="15%">Progress</th>
                <th>Catatan</th>
            </tr>
        </thead>

        <tbody>
            @forelse($project->logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d M Y') }}</td>
                    <td>{{ $log->progress_percent }}%</td>
                    <td>{{ $log->note }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Belum ada riwayat progress.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">KESIMPULAN</div>

    <table>
        <tr>
            <td>
                Project <b>{{ $project->project_name }}</b> saat laporan ini dibuat
                memiliki progress <b>{{ $project->progress_percent }}%</b>
                dengan status <b>{{ ucfirst(str_replace('_',' ',$project->status)) }}</b>.
                Monitoring dilakukan berdasarkan tahapan pekerjaan yang telah divalidasi pada sistem.
            </td>
        </tr>
    </table>
</div>

<div class="footer">
    <div class="signature">
        {{ now()->format('d F Y') }}

        <br><br>

        Mengetahui,

        <br><br><br><br><br>

        _________________________

        <br>

        Admin Project
    </div>
</div>

</body>
</html>