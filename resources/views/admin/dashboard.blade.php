<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Panel Kontrol HRD</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#FDFDFD] text-[#1E1E24] font-sans antialiased" x-data="{ currentTab: 'staff' }">

    <nav class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <span class="text-xl font-black tracking-wider text-[#4361EE] uppercase">{{ session('company_name') }}
                PANEL</span>
            <span class="bg-blue-50 text-[#4361EE] text-xs px-2.5 py-1 rounded-full font-bold">COMPANY ADMIN</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-500">HRD: <strong
                    class="text-gray-800">{{ session('full_name') }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="text-xs font-bold text-gray-400 hover:text-red-500 transition cursor-pointer">KELUAR</button>
            </form>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-10">
        @if ($companyDetail)
            <div
                class="mb-8 p-6 bg-gradient-to-r from-blue-600 to-[#4361EE] text-white rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-xs font-bold tracking-widest uppercase opacity-75">Profil Institusi / Mitra
                        Resmi</span>
                    <h2 class="text-2xl font-black mt-0.5">{{ $companyDetail['company_name'] }}</h2>
                    <p class="text-xs opacity-90 mt-1 max-w-xl font-medium">📍 Alamat: {{ $companyDetail['address'] }}
                    </p>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-md px-4 py-3 rounded-xl border border-white/10 text-xs font-semibold">
                    <div class="mb-1">📧 Email: {{ $companyDetail['email'] }}</div>
                    <div>📞 Kontak: {{ $companyDetail['phone_number'] }}</div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div x-data="{ showSuccess: true }" x-show="showSuccess" x-transition
                class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-sm font-medium flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button @click="showSuccess = false" class="text-emerald-500 hover:text-emerald-700 font-bold ml-4 cursor-pointer focus:outline-none" title="Tutup">
                    ✕
                </button>
            </div>
        @endif

        <h4 class="text-lg font-black text-gray-800 mb-4">Status Beban Kerja Mingguan Asisten / Staff</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-rose-50 border border-rose-100 p-5 rounded-2xl shadow-sm">
                <span class="text-xs font-bold text-rose-500 uppercase tracking-wider block mb-1">Overworked Kritis
                    (&gt; 45 Jam)</span>
                <span class="text-3xl font-black text-rose-700">{{ $workloadData['overworked'] }} Orang</span>
            </div>
            <div class="bg-slate-50 border border-slate-100 p-5 rounded-2xl shadow-sm">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Normal (35 - 40
                    Jam)</span>
                <span class="text-3xl font-black text-slate-700">{{ $workloadData['normal'] }} Orang</span>
            </div>
            <div class="bg-sky-50 border border-sky-100 p-5 rounded-2xl shadow-sm">
                <span class="text-xs font-bold text-sky-500 uppercase tracking-wider block mb-1">Underworked (&lt; 30
                    Jam)</span>
                <span class="text-3xl font-black text-sky-700">{{ $workloadData['underworked'] }} Orang</span>
            </div>
        </div>

        <div class="flex flex-wrap justify-between items-center border-b border-gray-100 pb-4 mb-8 mt-4 gap-4">
            <div class="flex flex-wrap gap-2">
                <button @click="currentTab = 'staff'"
                    :class="currentTab === 'staff' ? 'bg-blue-50 text-[#4361EE] border-blue-100' :
                        'text-gray-400 hover:text-gray-700 border-transparent'"
                    class="text-sm font-bold px-4 py-2.5 rounded-xl border transition shadow-sm cursor-pointer">
                    👥 Daftar Akun Staf
                </button>
                <button @click="currentTab = 'monitor'"
                    :class="currentTab === 'monitor' ? 'bg-blue-50 text-[#4361EE] border-blue-100' :
                        'text-gray-400 hover:text-gray-700 border-transparent'"
                    class="text-sm font-bold px-4 py-2.5 rounded-xl border transition shadow-sm cursor-pointer">
                    📅 Monitor Shift &amp; Log Absen
                </button>
                <button @click="currentTab = 'ai'"
                    :class="currentTab === 'ai' ? 'bg-blue-50 text-[#4361EE] border-blue-100' :
                        'text-gray-400 hover:text-gray-700 border-transparent'"
                    class="text-sm font-bold px-4 py-2.5 rounded-xl border transition shadow-sm cursor-pointer">
                    🤖 Validasi Gemini AI Izin
                </button>
                <button @click="currentTab = 'master_shift'"
                    :class="currentTab === 'master_shift' ? 'bg-blue-50 text-[#4361EE] border-blue-100' :
                        'text-gray-400 hover:text-gray-700 border-transparent'"
                    class="text-sm font-bold px-4 py-2.5 rounded-xl border transition shadow-sm cursor-pointer">
                    ⚙️ Master Pengaturan Shift
                </button>
            </div>

            <a href="{{ route('admin.download_report') }}"
                class="inline-flex items-center gap-2 bg-slate-900 text-white hover:bg-slate-800 text-xs font-bold px-4 py-3 rounded-xl transition shadow-sm border border-slate-950">
                📥 Unduh Rekap Absensi Hari Ini (.pdf)
            </a>
        </div>

        <div x-show="currentTab === 'staff'" x-transition>
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-lg font-black text-gray-800">Daftar Anggota Terdaftar</h4>
                <a href="{{ route('admin.add_staff') }}"
                    class="bg-[#4361EE] text-white font-bold text-sm px-5 py-3 rounded-xl hover:bg-blue-700 transition shadow-sm">
                    ➕ Daftarkan Staff Baru
                </a>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="p-4 pl-6">ID Staff</th>
                            <th class="p-4">Nama Lengkap</th>
                            <th class="p-4">Username</th>
                            <th class="p-4">Alamat Email</th>
                            <th class="p-4">Role Kedudukan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium text-gray-700 divide-y divide-gray-50">
                        @forelse($staffList as $staff)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 pl-6 font-mono text-gray-400">#{{ $staff['id'] }}</td>
                                <td class="p-4 text-gray-900 font-bold">{{ $staff['full_name'] }}</td>
                                <td class="p-4 text-gray-500">@​{{ $staff['username'] }}</td>
                                <td class="p-4">{{ $staff['email'] }}</td>
                                <td class="p-4">
                                    <span class="bg-blue-50 text-[#4361EE] text-xs px-2.5 py-1 rounded-full font-bold">
                                        {{ $staff['role_id'] == 1 ? 'Admin' : 'Asisten / Lapangan' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">
                                    Belum ada data staff yang didaftarkan untuk perusahaan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="currentTab === 'monitor'" x-transition class="space-y-10">

            <div>
                <h4 class="text-lg font-black text-gray-800 mb-4">Akumulasi Jam Kerja Riil (7 Hari Terakhir)</h4>
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="p-4 pl-6">ID Staff</th>
                                <th class="p-4">Nama Lengkap</th>
                                <th class="p-4 text-center">Beban Kerja Mingguan</th>
                                <th class="p-4">Status Kepadatan</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-medium text-gray-700 divide-y divide-gray-50">
                            @forelse($workloadData['details'] as $staff)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 pl-6 font-mono text-gray-400">#{{ $staff['id'] }}</td>
                                    <td class="p-4 text-gray-900 font-bold">{{ $staff['full_name'] }}</td>
                                    <td class="p-4 text-center font-black text-gray-800">{{ $staff['total_hours'] }}
                                        Jam</td>
                                    <td class="p-4">
                                        @if ($staff['total_hours'] > 45)
                                            <span
                                                class="bg-rose-100 text-rose-700 text-xs px-2.5 py-1 rounded-full font-bold">🔴
                                                Overworked</span>
                                        @elseif($staff['total_hours'] >= 35 && $staff['total_hours'] <= 45)
                                            <span
                                                class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-bold">🟢
                                                Normal</span>
                                        @else
                                            <span
                                                class="bg-sky-100 text-sky-700 text-xs px-2.5 py-1 rounded-full font-bold">🔵
                                                Underworked</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-400">Belum ada data beban jam
                                        kerja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex h-2 w-2 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <h4 class="text-lg font-black text-gray-800">Log Aktivitas Kehadiran Staf (Hari Ini)</h4>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="p-4 pl-6">Nama Staff</th>
                                <th class="p-4">Shift Tugas</th>
                                <th class="p-4 text-center">Jam Check-In</th>
                                <th class="p-4 text-center">Jam Check-Out</th>
                                <th class="p-4">Status Lapangan</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-medium text-gray-700 divide-y divide-gray-50">
                            @forelse($todayAttendanceLogs as $log)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 pl-6">
                                        <div class="font-bold text-gray-900">{{ $log['full_name'] }}</div>
                                        <div class="text-[11px] text-gray-400 font-mono">@​{{ $log['username'] }}
                                        </div>
                                    </td>
                                    <td class="p-4 text-gray-500 font-semibold">{{ $log['shift_title'] }}</td>
                                    <td class="p-4 text-center font-mono text-emerald-600 font-bold bg-emerald-50/30">
                                        {{ $log['jam_masuk'] }}</td>
                                    <td
                                        class="p-4 text-center font-mono {{ $log['jam_keluar'] === 'Belum Pulang' ? 'text-amber-600 font-bold bg-amber-50/30' : 'text-slate-600' }}">
                                        {{ $log['jam_keluar'] }}
                                    </td>
                                    <td class="p-4">
                                        @if ($log['jam_keluar'] === 'Belum Pulang')
                                            <span
                                                class="bg-emerald-100 text-emerald-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">⚡
                                                On Duty</span>
                                        @else
                                            <span
                                                class="bg-gray-100 text-gray-600 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">🏁
                                                Finished</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400 italic font-normal">
                                        Belum ada aktivitas check-in dari staf manapun pada hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="currentTab === 'master_shift'" x-cloak x-transition x-data="{ openCreate: false, sTitle: '', sDesc: '', sStart: '', sEnd: '', sLoc: '', isSaving: false }">

            <div class="flex justify-between items-center mb-6 mt-4">
                <div>
                    <h4 class="text-lg font-black text-gray-800">Konfigurasi Pola Operasional Shift</h4>
                    <p class="text-xs text-gray-400">Sesuaikan rentang waktu tugas kerja, penempatan area, dan
                        deskripsi jobdesk internal perusahaan secara dinamis.</p>
                </div>
                <button @click="openCreate = !openCreate"
                    class="bg-[#4361EE] text-white font-bold text-sm px-5 py-3 rounded-xl hover:bg-blue-700 transition shadow-sm cursor-pointer">
                    <span x-text="openCreate ? '❌ Batal' : '➕ Buat Konfigurasi Shift Baru'"></span>
                </button>
            </div>

            <div x-show="openCreate" x-transition
                class="bg-slate-50 border border-gray-100 p-6 rounded-2xl mb-6 max-w-2xl shadow-inner">
                <form
                    @submit.prevent="
                        isSaving = true;
                        fetch('http://127.0.0.1:3000/api/insertSchedules', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                company_id: {{ session('company_id') }},
                                created_by: {{ session('user_id') ?? 1 }},
                                title: sTitle,
                                description: sDesc,
                                start_time: sStart,
                                end_time: sEnd,
                                location: sLoc
                            })
                        })
                        .then(res => {
                            if(!res.ok) {
                                return res.json().then(errData => { throw new Error(errData.error || errData.message || 'Server Bermasalah') });
                            }
                            return res.json();
                        })
                        .then(data => {
                            isSaving = false;
                            if(data.id || data.success) {
                                alert('Berhasil membuat blueprint master shift baru!');
                                
                                // 🛠️ TRIK UTAMA: Ambil ulang data schedule terbaru dari Express API agar tabel langsung ter-update otomatis
                                fetch('http://127.0.0.1:3000/api/getSchedulesByCompanyId/' + {{ session('company_id') }})
                                    .then(r => r.json())
                                    .then(updatedList => {
                                        // Paksa halaman web memperbarui data array tanpa perlu refresh browser
                                        window.location.reload();
                                    });

                                // Kosongkan input form kembali secara otomatis setelah sukses
                                sTitle = '';
                                sDesc = '';
                                sStart = '';
                                sEnd = '';
                                sLoc = '';
                                openCreate = false;
                            } else {
                                alert('Gagal memproses data.');
                            }
                        })
                        .catch(err => {
                            isSaving = false;
                            console.error('❌ Fetch Error:', err);
                            alert('Gagal: ' + err.message);
                        });
                    "
                    class="space-y-4">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama
                                Kelompok Shift</label>
                            <input type="text" x-model="sTitle" placeholder="Contoh: Shift Pagi Reguler" required
                                class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#4361EE]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Lokasi /
                                Area Penugasan</label>
                            <input type="text" x-model="sLoc" placeholder="Contoh: Lab Komputer Terpadu 3"
                                class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#4361EE]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam
                                Masuk Kerja (Check-In)</label>
                            <input type="time" x-model="sStart" required
                                class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#4361EE]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam
                                Pulang Kerja (Check-Out)</label>
                            <input type="time" x-model="sEnd" required
                                class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#4361EE]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan
                            Tugas / Deskripsi Tambahan</label>
                        <textarea x-model="sDesc" rows="2" placeholder="Tulis rincian deskripsi kerja harian untuk shift terkait..."
                            class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#4361EE]"></textarea>
                    </div>

                    <button type="submit" :disabled="isSaving"
                        class="w-full py-3 bg-[#4361EE] hover:bg-blue-700 text-white font-black rounded-xl text-xs transition uppercase tracking-wider cursor-pointer disabled:opacity-40">
                        <span x-show="!isSaving">💾 Simpan &amp; Rilis Blueprint Shift</span>
                        <span x-show="isSaving">Menghitung Jam Kerja &amp; Mengunci Data...</span>
                    </button>
                </form>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="p-4 pl-6 w-[15%]">ID Pola</th>
                            <th class="p-4 w-[35%]">Nama Pengenal Shift</th>
                            <th class="p-4 w-[20%]">Lokasi Area</th>
                            <th class="p-4 text-center w-[15%]">Waktu Tugas</th>
                            <th class="p-4 text-center w-[15%]">Durasi Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium text-gray-700 divide-y divide-gray-50">
                        @forelse($shiftMasters as $shift)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 pl-6 font-mono text-gray-400">#SHIFT-0{{ $shift['id'] }}</td>
                                <td class="p-4">
                                    <div class="text-gray-900 font-bold">{{ $shift['title'] }}</div>
                                    @if (!empty($shift['description']))
                                        <div class="text-[11px] text-gray-400 font-normal italic mt-0.5">
                                            {{ $shift['description'] }}</div>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-500 font-semibold">📍
                                    {{ $shift['location'] ?? 'Default Area' }}</td>

                                <td class="p-4 text-center font-mono text-blue-600 font-bold bg-blue-50/10">
                                    {{ $shift['jam_masuk'] ?? ($shift['start_time'] ?? '00:00') }} -
                                    {{ $shift['jam_pulang'] ?? ($shift['end_time'] ?? '00:00') }} WIB
                                </td>

                                <td class="p-4 text-center">
                                    <span
                                        class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-xs font-bold font-mono">
                                        {{ $shift['duration_hours'] ?? '0' }} Jam Kerja
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400 font-medium bg-white">
                                    Belum ada data blueprint kustomisasi shift kerja harian yang terdaftar untuk
                                    perusahaan ini.<br>
                                    <span class="text-xs text-gray-300 font-normal block mt-1">Klik tombol "Buat
                                        Konfigurasi Shift Baru" untuk merancang tipe baru.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div x-show="currentTab === 'ai'" x-cloak x-transition x-data="{
            selectedIds: [],
            leaveRequests: @js($leaveRequests),
            aiResults: {},
            loadingStates: {},
            isSubmitting: false,
        
            get allIds() {
                return this.leaveRequests.map(r => r.id);
            },
        
            toggleSelectAll() {
                if (this.selectedIds.length === this.leaveRequests.length) {
                    this.selectedIds = [];
                } else {
                    this.selectedIds = [...this.allIds];
                }
            },
        
            async runBatchAnalysis() {
                const cleanIds = Array.from(this.selectedIds);
                console.log('⚡ Memproses analisis untuk ID murni:', cleanIds);
        
                if (cleanIds.length === 0) {
                    alert('Silakan pilih minimal satu perizinan staf dengan mencentang checkbox.');
                    return;
                }
        
                for (let id of cleanIds) {
                    let targetRequest = this.leaveRequests.find(r => r.id == id);
                    if (!targetRequest) continue;
                    if (this.aiResults[id]) continue;
        
                    this.loadingStates[id] = true;
                    this.loadingStates = { ...this.loadingStates };
        
                    try {
                        let textReason = targetRequest.reason || targetRequest.alasan || targetRequest.Alasan || '';
        
                        let response = await fetch('http://127.0.0.1:3000/api/analyze-leave-request', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ reason: textReason })
                        });
        
                        let data = await response.json();
                        if (data.success) {
                            this.aiResults[id] = data;
                        } else {
                            this.aiResults[id] = { is_valid: 0, ai_reason: 'Gagal memproses analisis AI.' };
                        }
                    } catch (error) {
                        console.error(error);
                        this.aiResults[id] = { is_valid: 0, ai_reason: 'Koneksi backend gagal terhubung.' };
                    } finally {
                        this.loadingStates[id] = false;
                        this.loadingStates = { ...this.loadingStates };
                        this.aiResults = { ...this.aiResults };
                    }
                }
            },
        
            async submitBatchDecision(actionType) {
                if (this.selectedIds.length === 0) {
                    alert('Silakan tentukan perizinan yang ingin diproses.');
                    return;
                }
        
                this.isSubmitting = true;
                let successCount = 0;
        
                try {
                    for (let id of this.selectedIds) {
                        let targetAnalysis = this.aiResults[id];
                        let response = await fetch('http://127.0.0.1:3000/api/respond-leave-request', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                replacement_id: id,
                                action: actionType,
                                ai_reason: targetAnalysis ? targetAnalysis.ai_reason : 'Ditinjau massal oleh Admin.'
                            })
                        });
                        let data = await response.json();
                        if (data.success) successCount++;
                    }
        
                    alert(`Berhasil memproses ${successCount} perizinan staf.`);
                    window.location.reload();
                } catch (error) {
                    console.error(error);
                    alert('Terjadi kendala operasional saat memproses keputusan massal.');
                } finally {
                    this.isSubmitting = false;
                }
            }
        }">

            <div class="flex justify-between items-center mb-4 bg-slate-50 p-4 rounded-xl border border-gray-100">
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-600 cursor-pointer select-none">
                        <input type="checkbox" @click="toggleSelectAll()"
                            :checked="selectedIds.length === leaveRequests.length && leaveRequests.length > 0"
                            class="w-4 h-4 text-[#4361EE] focus:ring-[#4361EE] border-gray-300 rounded cursor-pointer">
                        <span>Select All</span>
                    </label>
                    <span class="text-xs text-gray-400 font-bold"
                        x-text="`Terpilih: ${selectedIds.length} Baris`"></span>
                </div>

                <button @click="runBatchAnalysis()" :disabled="selectedIds.length === 0"
                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-[#4361EE] text-white text-xs font-black rounded-xl shadow-sm hover:from-blue-700 transition cursor-pointer disabled:opacity-40">
                    ⚡ Jalankan Analisis Otomatis
                </button>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left border-collapse table-fixed">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-gray-200 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="p-4 text-center w-[10%]">Checkbox</th>
                            <th class="p-4 w-[20%]">Nama Staff</th>
                            <th class="p-4 w-[35%]">Alasan</th>
                            <th class="p-4 text-center w-[12%]">Status</th>
                            <th class="p-4 w-[23%]">Hasil Analisis AI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium text-gray-700 divide-y divide-gray-50">
                        <template x-for="req in leaveRequests" :key="req.id">
                            <tr class="hover:bg-slate-50/40 transition-colors"
                                :class="selectedIds.includes(req.id) ? 'bg-blue-50/30' : ''">
                                <td class="p-4 text-center">
                                    <input type="checkbox" :value="req.id" x-model="selectedIds"
                                        class="w-4 h-4 text-[#4361EE] focus:ring-[#4361EE] border-gray-300 rounded cursor-pointer">
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-900" x-text="req.full_name"></div>
                                    <div class="text-[11px] text-gray-400 font-mono" x-text="'@'+req.username"></div>
                                </td>
                                <td class="p-4 text-gray-600 italic font-normal text-xs leading-relaxed truncate hover:whitespace-normal"
                                    x-text="req.reason"></td>
                                <td class="p-4 text-center">
                                    <span
                                        class="px-2.5 py-1 text-[10px] font-black tracking-wider rounded-full bg-amber-50 text-amber-600 border border-amber-100"
                                        x-text="req.status"></span>
                                </td>
                                <td class="p-4 border-l border-gray-50">
                                    <div x-show="!aiResults[req.id] && !loadingStates[req.id]"
                                        class="text-xs text-gray-300 italic font-normal">Menunggu antrean...</div>
                                    <div x-show="loadingStates[req.id]"
                                        class="flex items-center gap-1.5 text-xs text-blue-500 font-bold">
                                        <svg class="animate-spin h-3.5 w-3.5 text-blue-500" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg> Memeriksa...
                                    </div>
                                    <div x-show="aiResults[req.id]" class="space-y-1">
                                        <div class="flex items-center gap-1">
                                            <template x-if="aiResults[req.id]?.is_valid === 1">
                                                <span
                                                    class="px-2 py-0.5 bg-emerald-600 text-white font-black text-[9px] tracking-wider rounded-full shadow-sm uppercase">✔️
                                                    Valid</span>
                                            </template>
                                            <template x-if="aiResults[req.id]?.is_valid === 0">
                                                <span
                                                    class="px-2 py-0.5 bg-rose-600 text-white font-black text-[9px] tracking-wider rounded-full shadow-sm uppercase">❌
                                                    Ragu</span>
                                            </template>
                                        </div>
                                        <p class="text-[11px] text-gray-500 font-medium leading-normal"
                                            x-text="aiResults[req.id]?.ai_reason"></p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="leaveRequests.length === 0">
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400 font-medium">Belum ada
                                    riwayat pengajuan izin staf.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="Object.keys(aiResults).length > 0" class="flex gap-3 justify-end">
                <button @click="submitBatchDecision('approve')" :disabled="isSubmitting"
                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl shadow-sm transition-colors cursor-pointer disabled:opacity-50">
                    <span x-show="!isSubmitting">✔️ Terima Semua Terpilih &amp; Kirim Notif</span>
                    <span x-show="isSubmitting">Memproses...</span>
                </button>
                <button @click="submitBatchDecision('reject')" :disabled="isSubmitting"
                    class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black rounded-xl shadow-sm transition-colors cursor-pointer disabled:opacity-50">
                    <span x-show="!isSubmitting">❌ Tolak Semua Terpilih</span>
                    <span x-show="isSubmitting">Memproses...</span>
                </button>
            </div>
        </div>

    </main>
</body>

</html>
