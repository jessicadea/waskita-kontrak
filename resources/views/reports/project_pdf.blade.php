<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Project</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 12px;
        }

        .header {
            border-bottom: 2px solid #1E3A8A;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #1E3A8A;
        }

        .subtitle {
            color: #6B7280;
            margin-top: 4px;
        }

        .section {
            margin-top: 18px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1E3A8A;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #E5E7EB;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background: #F3F4F6;
            text-align: left;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            background: #DBEAFE;
            color: #1E3A8A;
            font-weight: bold;
        }

        .progress-wrapper {
            background: #E5E7EB;
            height: 14px;
            border-radius: 8px;
            overflow: hidden;
        }

        .progress-bar {
            background: #2563EB;
            height: 14px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">Laporan Monitoring Project</div>
        <div class="subtitle">Sistem Pencatatan dan Monitoring Kontrak Pemesanan Produk</div>
        <div class="subtitle">PT Waskita Beton Precast</div>
    </div>

    <div class="section">
        <div class="section-title">Informasi Project</div>
        <table>
            <tr>
                <th>Nama Project</th>
                <td>{{ $project->project_name }}</td>
            </tr>
            <tr>
                <th>Client</th>
                <td>{{ $project->order->user->name }}</td>
            </tr>
            <tr>
                <th>Produk</th>
                <td>{{ $project->order->product->product_name }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td><span class="badge">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span></td>
            </tr>
            <tr>
                <th>Progress</th>
                <td>
                    {{ $project->progress_percent }}%
                    <div class="progress-wrapper">
                        <div class="progress-bar" style="width: {{ $project->progress_percent }}%"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Periode</th>
                <td>{{ $project->start_date }} s/d {{ $project->due_date }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Pegawai Ditugaskan</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Pegawai</th>
                    <th>Jabatan</th>
                    <th>Role Tugas</th>
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
                        <td colspan="3">Belum ada pegawai ditugaskan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Riwayat Progress Approved</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Progress</th>
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
                        <td colspan="3">Belum ada progress approved.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>