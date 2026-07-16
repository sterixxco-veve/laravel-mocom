<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Superadmin Dashboard - Kontrol Pusat</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#FDFDFD] text-[#1E1E24] font-sans antialiased" x-data="{
    subTab: 'companies',
    selectedCompany: '',
    staffList: [],
    loading: false,
    showForm: false,
    toggleStaff(staffId, status) {
        const actionText = status ? 'mengaktifkan kembali' : 'menonaktifkan';
        const actionUrl = status ? '/superadmin/staff/' + staffId + '/activate' : '/superadmin/staff/' + staffId + '/deactivate';
        
        Swal.fire({
            title: status ? 'Aktifkan Staff?' : 'Nonaktifkan Staff?',
            text: `Apakah Anda yakin ingin ${actionText} staff ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: status ? '#10B981' : '#EF4444',
            cancelButtonColor: '#3B82F6',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ title: 'Berhasil!', text: data.message, icon: 'success' });
                        // Ambil ulang staff list
                        fetch('/superadmin/company/' + this.selectedCompany + '/staff')
                            .then(res => res.json())
                            .then(d => { this.staffList = d; });
                    } else {
                        Swal.fire({ title: 'Gagal!', text: data.error || 'Terjadi kesalahan.', icon: 'error' });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({ title: 'Error!', text: 'Koneksi ke server gagal.', icon: 'error' });
                });
            }
        });
    }
}">

    <nav
        class="sticky top-0 z-50 bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center shadow-sm">
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
                <button @click="showSuccess = false"
                    class="text-emerald-500 hover:text-emerald-700 font-bold ml-4 cursor-pointer focus:outline-none"
                    title="Tutup">
                    ✕
                </button>
            </div>
        @endif

        <div class="flex gap-2 border-b border-gray-100 pb-4 mb-8">
            <button @click="subTab = 'companies'"
                :class="subTab === 'companies' ? 'bg-blue-50 text-[#4361EE] border-blue-100' : 'text-gray-400 border-transparent'"
                class="text-xs font-bold px-4 py-2.5 rounded-xl border transition shadow-sm cursor-pointer font-sans">
                🏢 Manajemen Perusahaan
            </button>
            <button @click="subTab = 'staff_intervention'" :class="subTab === 'staff_intervention' ? 'bg-blue-50 text-[#4361EE] border-blue-100' :
                    'text-gray-400 border-transparent'"
                class="text-xs font-bold px-4 py-2.5 rounded-xl border transition shadow-sm cursor-pointer font-sans">
                👥 Intervensi Data Karyawan
            </button>

        </div>

        <div x-show="subTab === 'companies'" x-transition>
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-lg font-black text-gray-800">Daftar Akun Perusahaan Terdaftar</h4>
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
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium text-gray-700 divide-y divide-gray-50">
                        @forelse($companies as $company)
                            <tr
                                class="hover:bg-slate-50/50 transition {{ !$company['is_active'] ? 'opacity-65 bg-slate-50/20' : '' }}">
                                <td class="p-4 pl-6 font-mono text-gray-400">#{{ $company['id'] }}</td>
                                <td class="p-4 text-gray-900 font-bold flex items-center gap-2">
                                    <span>{{ $company['company_name'] }}</span>
                                    @if(!$company['is_active'])
                                        <span
                                            class="bg-red-50 text-red-500 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="p-4 text-[#4361EE] font-semibold">{{ $company['email'] }}</td>
                                <td class="p-4 text-gray-500 font-mono">{{ $company['phone_number'] }}</td>
                                <td class="p-4 text-gray-400 max-w-xs truncate" title="{{ $company['address'] }}">
                                    {{ $company['address'] }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($company['is_active'])
                                        <form action="{{ route('superadmin.deactivate_company', $company['id']) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="button" onclick="confirmDeactivate(this)"
                                                class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold px-3 py-1.5 rounded-lg transition cursor-pointer"
                                                title="Nonaktifkan Perusahaan">
                                                🛑 Nonaktifkan
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('superadmin.activate_company', $company['id']) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-emerald-50 hover:bg-emerald-100 text-emerald-600 text-xs font-bold px-3 py-1.5 rounded-lg transition cursor-pointer"
                                                title="Aktifkan Kembali Perusahaan">
                                                ✅ Aktifkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-400 font-medium bg-white">
                                    Belum ada data perusahaan yang terdaftar di sistem pusat.<br>
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
                <h4 class="text-base font-black text-gray-800 mb-2">Pilih Perusahaan Tujuan</h4>
                <p class="text-xs text-gray-400 mb-4">Pilih perusahaan untuk memonitor list staff aktif atau melakukan
                    injeksi akun staff baru jika aplikasi sisi admin mengalami kendala teknis.</p>

                <select x-model="selectedCompany" @change="if(selectedCompany) { 
                        loading = true; 
                        staffList = [];
                        fetch('/superadmin/company/' + selectedCompany + '/staff')
                            .then(res => {
                                if(!res.ok) throw new Error('Server Bermasalah');
                                return res.json();
                            })
                            .then(responseObj => { 
                                // 🛠️ PENYELAMAT DATA: Deteksi otomatis apakah data terbungkus properti lain atau array murni
                                console.log('📦 Respon asli dari Laravel:', responseObj);
                                
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
                    <option value="">-- Pilih Perusahaan --</option>
                    @foreach ($activeCompanies as $company)
                        <option value="{{ $company['id'] }}">{{ $company['company_name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div x-show="selectedCompany" x-transition x-cloak>
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-sm font-bold text-gray-700">Daftar Karyawan Terdaftar pada Company ID #<span
                            x-text="selectedCompany" class="font-mono text-blue-600"></span></h5>
                    <button @click="showForm = !showForm"
                        class="bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-slate-800 shadow-sm transition-all cursor-pointer">
                        <span x-text="showForm ? '❌ Tutup Form Bypass' : '➕ Tambah Staff Via Backdoor'"></span>
                    </button>
                </div>

                <div x-show="showForm" x-transition x-cloak
                    class="bg-amber-50/30 border border-amber-200/70 rounded-2xl p-6 mb-6 max-w-xl shadow-inner" x-data="{
                        formData: {
                            full_name: '',
                            username: '',
                            phone_number: '08123456789', // Nilai dummy kontak
                            email: '',
                            password: ''
                        },
                        isSending: false
                    }">
                    <div
                        class="flex items-center gap-2 mb-4 text-amber-600 font-black text-xs tracking-wider uppercase">
                        ⚠️ EMERGENCY BYPASS SUPERADMIN CONTROL
                    </div>

                    <form @submit.prevent="
                        isSending = true;
                        fetch('https://backend-mocom.vercel.app/api/superadmin/addStaff', {
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
                                Swal.fire({ title: 'Berhasil!', text: 'Berhasil menyuntikkan staff baru!', icon: 'success' });
                                // Refresh data tabel staff secara real-time tanpa reload halaman
                                fetch('/superadmin/company/' + selectedCompany + '/staff')
                                    .then(res => res.json())
                                    .then(d => { staffList = d; });
                                
                                // Kosongkan form kembali
                                formData.full_name = '';
                                formData.username = '';
                                formData.email = '';
                                formData.password = '';
                                showForm = false;
                            } else {
                                Swal.fire({ title: 'Gagal!', text: data.error, icon: 'error' });
                            }
                        })
                        .catch(err => {
                            isSending = false;
                            console.error(err);
                            Swal.fire({ title: 'Koneksi Gagal!', text: 'Koneksi ke server Express gagal.', icon: 'error' });
                        });
                    " class="space-y-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Nama
                                    Lengkap Staff</label>
                                <input type="text" x-model="formData.full_name" required
                                    placeholder="Masukkan nama lengkap staff"
                                    class="w-full bg-white border border-gray-200 text-xs rounded-lg px-3 py-2.5 focus:outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Username</label>
                                <input type="text" :value="formData.username" disabled
                                    placeholder="Otomatis terisi dari email"
                                    class="w-full bg-slate-100 border border-gray-200 text-gray-400 text-xs rounded-lg px-3 py-2.5 focus:outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Email</label>
                                <input type="email" x-model="formData.email" required
                                    @blur="if(formData.email) { formData.username = formData.email.split('@')[0] }"
                                    placeholder="Masukkan email resmi staff"
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
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
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
                                <th class="p-4 w-[35%]">Nama Karyawan</th>
                                <th class="p-4 w-[30%]">Email</th>
                                <th class="p-4 w-[20%] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-medium text-gray-700 divide-y divide-gray-50">
                            <template x-for="staff in staffList" :key="staff.id">
                                <tr class="hover:bg-slate-50/50 transition"
                                    :class="!staff.is_active ? 'opacity-65 bg-slate-50/20' : ''">
                                    <td class="p-4 text-gray-900 font-bold flex items-center gap-2">
                                        <span x-text="staff.full_name"></span>
                                        <template x-if="!staff.is_active">
                                            <span
                                                class="bg-red-50 text-red-500 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">Nonaktif</span>
                                        </template>
                                    </td>
                                    <td class="p-4 text-blue-600 font-semibold" x-text="staff.email"></td>
                                    <td class="p-4 text-center">
                                        <template x-if="staff.is_active">
                                            <button @click="toggleStaff(staff.id, false)"
                                                class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold px-3 py-1.5 rounded-lg transition cursor-pointer">
                                                🛑 Nonaktifkan
                                            </button>
                                        </template>
                                        <template x-if="!staff.is_active">
                                            <button @click="toggleStaff(staff.id, true)"
                                                class="bg-emerald-50 hover:bg-emerald-100 text-emerald-600 text-xs font-bold px-3 py-1.5 rounded-lg transition cursor-pointer">
                                                ✅ Aktifkan
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>

                            <tr x-show="staffList.length === 0">
                                <td colspan="4"
                                    class="p-12 text-center text-gray-400 italic text-xs bg-white font-medium">
                                    Perusahaan ini belum memiliki atau menginputkan satu pun staff aktif di
                                    database.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        function confirmDeactivate(button) {
            Swal.fire({
                title: 'Nonaktifkan Perusahaan?',
                text: "Apakah Anda yakin ingin menonaktifkan perusahaan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#3B82F6',
                confirmButtonText: 'Ya, Nonaktifkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }
    </script>
</body>

</html>