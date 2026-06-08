<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Resmi Absensi Shift - MOCOM</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e1e24; font-size: 12px; line-height: 1.5; }
        .header { border-bottom: 2px solid #4361ee; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; color: #4361ee; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 11px; color: #8d99ae; margin-top: 5px; }
        .meta-box { margin-bottom: 25px; font-size: 11px; }
        .ai-card { background-color: #f0f4ff; border-left: 4px solid #4361ee; padding: 15px; margin-bottom: 25px; border-radius: 4px; }
        .ai-title { font-weight: bold; color: #3a0ca3; margin-bottom: 5px; font-size: 12px; }
        .ai-text { font-style: italic; color: #2d3142; }
        table { w-full; width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #eef2f6; text-transform: uppercase; font-size: 10px; font-weight: bold; color: #475569; padding: 10px; border: 1px solid #cbd5e1; }
        td { padding: 10px; border: 1px solid #e2e8f0; text-align: left; }
        .text-center { text-align: center; }
        .footer { margin-top: 50px; text-align: right; font-size: 10px; color: #8d99ae; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">MOCOM EDUSHIFT PRO REPORT</h1>
        <div class="subtitle">Sistem Monitoring Presensi Multi-Tenant Terintegrasi</div>
    </div>

    <div class="meta-box">
        <strong>Nama Institusi/Perusahaan:</strong> {{ $companyName }}<br>
        <strong>Tanggal Rekap Laporan:</strong> {{ $dateToday }}<br>
        <strong>Status Dokumen:</strong> Berkas Cetak Digital Sah
    </div>

    <div class="ai-card">
        <div class="ai-title">🤖 Analisis Inteligensi Buatan (Google Gemini AI Summary)</div>
        <div class="ai-text">"{{ $aiSummary }}"</div>
    </div>

    <h3>Log Aktivitas Riwayat Shift Hari Ini</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Karyawan</th>
                <th width="30%">Nama Kelas / Shift</th>
                <th width="20%" class="text-center">Jam Check-In</th>
                <th width="20%" class="text-center">Jam Check-Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $log['full_name'] }}</strong><br><small style="color:#8d99ae">@​{{ $log['username'] }}</small></td>
                    <td>{{ $log['shift_title'] }}<br><small style="color:#8d99ae">⏱️ {{ substr($log['start_time'], 11, 5) }} - {{ substr($log['end_time'], 11, 5) }}</small></td>
                    <td class="text-center" style="color: #10b981; font-weight: bold;">{{ substr($log['check_in'], 11, 8) }}</td>
                    <td class="text-center" style="color: #4361ee; font-weight: bold;">
                        {{ $log['check_out'] ? substr($log['check_out'], 11, 8) : 'Aktif' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 30px; color: #8d99ae;">
                        Tidak ditemukan catatan log kehadiran asisten/staf untuk hari ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh Sistem Kriptografi Presensi MOCOM.<br>
        Dicetak pada tanggal: {{ date('d/m/Y H:i:s') }}
    </div>

</body>
</html>