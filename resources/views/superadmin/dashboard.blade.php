<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Superadmin Dashboard - Kontrol Pusat</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#FDFDFD] text-[#1E1E24] font-sans antialiased" x-data="{
    subTab: 'tenants',
    selectedCompany: '',
    staffList: [],
    loading: false,
    showForm: false
}">

    <nav class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <span class="text-xl font-black tracking-wider text-[#4361EE]">MOCOM CENTRAL</span>
            <span class="bg-red-50 text-red-500 text-xs px-2.5 py-1 rounded-full font-bold">SUPERADMIN</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-500">Halo, <strong
                    class="text-gray-800">{{ session('full_name') }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="text-xs font-bold text-gray-400 hover:text-red-500 transition cursor-pointer">KELUAR</button>
            </form>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-10">
        @if (session('success'))
            <div x-data="{ showSuccess: true }" x-show="showSuccess" x-transition
                class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-sm font-medium flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button @click="showSuccess = false" class="text-emerald-500 hover:text-emerald-700 font-bold ml-4 cursor-pointer focus:outline-none" title="Tutup">
                    ✕
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-xs font-bold text-[#4361EE] uppercase tracking-wider block mb-1">Total Mitra
                    Aktif</span>
                <span class="text-3xl font-black">{{ $totalMitra }} Perusahaan</span>
            </div>
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-xs font-bold text-amber-500 uppercase tracking-wider block mb-1">Menunggu
                    Legitimasi</span>
                <span class="text-3xl font-black">{{ $waitingLegitimation }} Pengajuan</span>
            </div>
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-xs font-bold text-emerald-500 uppercase tracking-wider block mb-1">Total Pendapatan
                    Deal</span>
                <span class="text-3xl font-black text-emerald-600">Pro Terbuka</span>
            </div>
        </div>

        <div class="flex gap-2 border-b border-gray-100 pb-4 mb-8">
            <button @click="subTab = 'tenants'"
                :class="subTab === 'tenants' ? 'bg-blue-50 text-[#4361EE] border-blue-100' : 'text-gray-400 border-transparent'"
                class="text-xs font-bold px-4 py-2.5 rounded-xl border transition shadow-sm cursor-pointer font-sans">
                🏢 Manajemen Tenant
            </button>
            <button @click="subTab = 'staff_intervention'"
                :class="subTab === 'staff_intervention' ? 'bg-blue-50 text-[#4361EE] border-blue-100' :
                    'text-gray-400 border-transparent'"
                class="text-xs font-bold px-4 py-2.5 rounded-xl border transition shadow-sm cursor-pointer font-sans">
                👥 Intervensi Data Karyawan (Emergency)
            </button>

        </div>

        <div x-show="subTab === 'tenants'" x-transition>
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-lg font-black text-gray-800">Daftar Akun Tenant Perusahaan Terdaftar</h4>
                <a href="{{ route('superadmin.add_company') }}"
                    class="inline-flex items-center gap-1 bg-[#4361EE] text-white font-bold text-sm px-5 py-3 rounded-xl hover:bg-blue-700 transition shadow-sm">
                    ➕ Daftarkan Perusahaan Baru
                </a>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="p-4 pl-6">ID Perusahaan</th>
                            <th class="p-4">Nama Perusahaan / Institusi</th>
                            <th class="p-4">Email Administrator</th>
                            <th class="p-4">Nomor Kontak</th>
                            <th class="p-4">Alamat Domisili Kantor</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium text-gray-700 divide-y divide-gray-50">
                        @forelse($companies as $company)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 pl-6 font-mono text-gray-400">#{{ $company['id'] }}</td>
                                <td class="p-4 text-gray-900 font-bold">{{ $company['company_name'] }}</td>
                                <td class="p-4 text-[#4361EE] font-semibold">{{ $company['email'] }}</td>
                                <td class="p-4 text-gray-500 font-mono">{{ $company['phone_number'] }}</td>
                                <td class="p-4 text-gray-400 max-w-xs truncate" title="{{ $company['address'] }}">
                                    {{ $company['address'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400 font-medium bg-white">
                                    Belum ada data perusahaan tenant yang terdaftar di sistem pusat.<br>
                                    <span class="text-xs text-gray-300 font-normal block mt-1">Klik tombol "Daftarkan
                                        Perusahaan Baru" untuk mengisi data.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="subTab === 'staff_intervention'" x-cloak x-transition>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 mb-6">
                <h4 class="text-base font-black text-gray-800 mb-2">Pilih Perusahaan Tenant Tujuan</h4>
                <p class="text-xs text-gray-400 mb-4">Pilih perusahaan untuk memonitor list staff aktif atau melakukan
                    injeksi akun staff baru jika aplikasi sisi admin mengalami kendala teknis.</p>

                <select x-model="selectedCompany"
                    @change="if(selectedCompany) { 
                        loading = true; 
                        staffList = [];
                        fetch('http://127.0.0.1:3000/api/getStaffByCompany/' + selectedCompany)
                            .then(res => {
                                if(!res.ok) throw new Error('Server Bermasalah');
                                return res.json();
                            })
                            .then(responseObj => { 
                                // 🛠️ PENYELAMAT DATA: Deteksi otomatis apakah data terbungkus properti lain atau array murni
                                console.log('📦 Respon asli dari Express:', responseObj);
                                
                                if (Array.isArray(responseObj)) {
                                    // Jika respon langsung berbentuk array murni
                                    staffList = responseObj;
                                } else if (responseObj && Array.isArray(responseObj.data)) {
                                    // Jika respon dibungkus di dalam objek .data
                                    staffList = responseObj.data;
                                } else if (responseObj && Array.isArray(responseObj.results)) {
                                    // Jika respon dibungkus di dalam objek .results
                                    staffList = responseObj.results;
                                } else {
                                    // Jika tidak terdeteksi, jadikan array kosong agar tidak memicu undefined
                                    staffList = [];
                                    console.warn('⚠️ Struktur data tidak dikenali sebagai Array:', responseObj);
                                }
                                
                                loading = false; 
                            })
                            .catch(err => { 
                                console.error('❌ Fetch Error:', err); 
                                loading = false; 
                            });
                    } else { staffList = []; }"
                    class="bg-slate-50 border border-gray-200 text-sm rounded-xl px-4 py-3 w-full md:w-1/3 focus:outline-none font-medium cursor-pointer">
                    <option value="">-- Pilih Tenant Perusahaan --</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company['id'] }}">{{ $company['company_name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div x-show="selectedCompany" x-transition x-cloak>
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-sm font-bold text-gray-700">Daftar Karyawan Terdaftar pada Tenant ID #<span
                            x-text="selectedCompany" class="font-mono text-blue-600"></span></h5>
                    <button @click="showForm = !showForm"
                        class="bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-slate-800 shadow-sm transition-all cursor-pointer">
                        <span x-text="showForm ? '❌ Tutup Form Bypass' : '➕ Tambah Staff Via Backdoor'"></span>
                    </button>
                </div>

                <div x-show="showForm" x-transition x-cloak
                    class="bg-amber-50/30 border border-amber-200/70 rounded-2xl p-6 mb-6 max-w-xl shadow-inner"
                    x-data="{
                        formData: {
                            full_name: '',
                            phone_number: '',
                            email: '',
                            password: ''
                        },
                        isSending: false
                    }">
                    <div
                        class="flex items-center gap-2 mb-4 text-amber-600 font-black text-xs tracking-wider uppercase">
                        ⚠️ EMERGENCY BYPASS SUPERADMIN CONTROL
                    </div>

                    <form
                        @submit.prevent="
                        isSending = true;
                        fetch('http://127.0.0.1:3000/api/superadmin/addStaff', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                company_id: selectedCompany,
                                full_name: formData.full_name,
                                email: formData.email,
                                password: formData.password,
                                phone_number: formData.phone_number
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            isSending = false;
                            if(data.success) {
                                alert('Berhasil menyuntikkan staff baru!');
                                // Refresh data tabel staff secara real-time tanpa reload halaman
                                fetch('http://127.0.0.1:3000/api/getStaffByCompany/' + selectedCompany)
                                    .then(res => res.json())
                                    .then(d => { staffList = d; });
                                
                                // Kosongkan form kembali
                                formData.full_name = '';
                                formData.phone_number = '';
                                formData.email = '';
                                formData.password = '';
                                showForm = false;
                            } else {
                                alert('Gagal: ' + data.error);
                            }
                        })
                        .catch(err => {
                            isSending = false;
                            console.error(err);
                            alert('Koneksi ke server Express gagal.');
                        });
                    "
                        class="space-y-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Nama
                                    Lengkap Staff</label>
                                <input type="text" x-model="formData.full_name" required
                                    class="w-full bg-white border border-gray-200 text-xs rounded-lg px-3 py-2.5 focus:outline-none focus:border-amber-500">
                            </div>

                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Email
                                    Log</label>
                                <input type="email" x-model="formData.email" required
                                    class="w-full bg-white border border-gray-200 text-xs rounded-lg px-3 py-2.5 focus:outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Password
                                    Default</label>
                                <input type="password" x-model="formData.password" required
                                    class="w-full bg-white border border-gray-200 text-xs rounded-lg px-3 py-2.5 focus:outline-none focus:border-amber-500"
                                    placeholder="Minimal 6 karakter">
                            </div>
                        </div>

                        <button type="submit" :disabled="isSending"
                            class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-black text-xs rounded-xl transition shadow-sm cursor-pointer uppercase tracking-wider disabled:opacity-50">
                            <span x-show="!isSending">🚀 Eksekusi Suntik Akun Staff Pusat</span>
                            <span x-show="isSending">Sedang Memproses ke Database...</span>
                        </button>
                    </form>
                </div>

                <div x-show="loading" class="text-center py-10 text-xs text-gray-400 font-medium">
                    <svg class="animate-spin h-5 w-5 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Sedang menarik data jaringan karyawan...
                </div>

                <div x-show="!loading"
                    class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mb-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="p-4 pl-6 w-[15%]">ID User</th>
                                <th class="p-4 w-[35%]">Nama Karyawan</th>
                                <th class="p-4 w-[30%]">Email</th>
                                <th class="p-4 w-[20%]">Nomor Kontak</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-medium text-gray-700 divide-y divide-gray-50">
                            <template x-for="staff in staffList" :key="staff.id">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 pl-6 font-mono text-gray-400" x-text="'#' + staff.id"></td>
                                    <td class="p-4 text-gray-900 font-bold" x-text="staff.full_name"></td>
                                    <td class="p-4 text-blue-600 font-semibold" x-text="staff.email"></td>
                                    <td class="p-4 text-gray-500 font-mono" x-text="staff.phone_number"></td>
                                </tr>
                            </template>

                            <tr x-show="staffList.length === 0">
                                <td colspan="4"
                                    class="p-12 text-center text-gray-400 italic text-xs bg-white font-medium">
                                    Perusahaan tenant ini belum memiliki atau menginputkan satu pun staff aktif di
                                    database.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

</body>

</html>
