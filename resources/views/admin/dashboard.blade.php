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

<body class="bg-[#FDFDFD] text-[#1E1E24] font-sans antialiased" x-data="{ currentTab: 'monitor' }">

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
            <div
                class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <h4 class="text-lg font-black text-gray-800 mb-4">Status Beban Kerja Mingguan Asisten / Staff</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-rose-50 border border-rose-100 p-5 rounded-2xl shadow-sm">
                <span class="text-xs font-bold text-rose-500 uppercase tracking-wider block mb-1">Overworked Kritis
                    (&gt; 45 Jam)</span>
                <span class="text-3xl font-black text-rose-700">1 Orang</span>
            </div>
            <div class="bg-slate-50 border border-slate-100 p-5 rounded-2xl shadow-sm">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Normal (35 - 40
                    Jam)</span>
                <span class="text-3xl font-black text-slate-700">3 Orang</span>
            </div>
            <div class="bg-sky-50 border border-sky-100 p-5 rounded-2xl shadow-sm">
                <span class="text-xs font-bold text-sky-500 uppercase tracking-wider block mb-1">Underworked (&lt; 30
                    Jam)</span>
                <span class="text-3xl font-black text-sky-700">0 Orang</span>
            </div>
        </div>

        <div class="flex flex-wrap justify-between items-center border-b border-gray-100 pb-4 mb-8 mt-4 gap-4">
            <div class="flex flex-wrap gap-2">
                <button @click="currentTab = 'monitor'"
                    :class="currentTab === 'monitor' ? 'bg-blue-50 text-[#4361EE] border-blue-100' :
                        'text-gray-400 hover:text-gray-700 border-transparent'"
                    class="text-sm font-bold px-4 py-2.5 rounded-xl border transition shadow-sm cursor-pointer">
                    📋 Monitor Staf &amp; Workload
                </button>
                <button @click="currentTab = 'ai'"
                    :class="currentTab === 'ai' ? 'bg-blue-50 text-[#4361EE] border-blue-100' :
                        'text-gray-400 hover:text-gray-700 border-transparent'"
                    class="text-sm font-bold px-4 py-2.5 rounded-xl border transition shadow-sm cursor-pointer">
                    🤖 Validasi Gemini AI Izin
                </button>
            </div>

            <a href="{{ route('admin.download_report') }}"
                class="inline-flex items-center gap-2 bg-slate-900 text-white hover:bg-slate-800 text-xs font-bold px-4 py-3 rounded-xl transition shadow-sm border border-slate-950">
                📥 Unduh Rekap Absensi Hari Ini (.pdf)
            </a>
        </div>

        <div x-show="currentTab === 'monitor'" x-transition>
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-lg font-black text-gray-800">Daftar Anggota / Staff Terdaftar</h4>
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
                            <th class="p-4">Role ID</th>
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
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full font-bold">
                                        Role {{ $staff['role_id'] }}
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
                        // 1. Log objek asli ke konsol untuk memeriksa nama kolom yang benar dari database
                        console.log(`📦 Struktur data asli untuk ID #${id}:`, targetRequest);
        
                        // 2. Deteksi otomatis beberapa kemungkinan nama kolom alasan
                        let textReason = targetRequest.reason ||
                            targetRequest.alasan ||
                            targetRequest.Alasan ||
                            targetRequest.reason_text ||
                            '';
        
                        // Jika masih tetap kosong, beri pesan peringatan di konsol
                        if (!textReason) {
                            console.warn(`⚠️ Peringatan: Kolom alasan tidak ditemukan pada objek ID #${id}. Periksa log struktur data di atas.`);
                        }
        
                        console.log(`🤖 Mengirim alasan ke Express untuk ID #${id}:`, textReason);
        
                        let response = await fetch('http://127.0.0.1:3000/api/analyze-leave-request', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ reason: textReason })
                        });
        
                        let data = await response.json();
                        console.log(`🧠 Respon sukses dari Gemini API untuk ID #${id}:`, data);
        
                        if (data.success) {
                            this.aiResults[id] = data;
                        } else {
                            this.aiResults[id] = { is_valid: 0, ai_reason: 'Gagal memproses analisis AI.' };
                        }
                    } catch (error) {
                        console.error('❌ Kegagalan Fetching pada ID ' + id + ':', error);
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
                                        class="text-xs text-gray-300 italic font-normal">
                                        Menunggu antrean...
                                    </div>

                                    <div x-show="loadingStates[req.id]"
                                        class="flex items-center gap-1.5 text-xs text-blue-500 font-bold">
                                        <svg class="animate-spin h-3.5 w-3.5 text-blue-500" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Memeriksa...
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
