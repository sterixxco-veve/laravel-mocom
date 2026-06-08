<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Superadmin Dashboard - Kontrol Pusat</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#FDFDFD] text-[#1E1E24] font-sans antialiased">
    <nav class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <span class="text-xl font-black tracking-wider text-[#4361EE]">MOCOM CENTRAL</span>
            <span class="bg-red-50 text-red-500 text-xs px-2.5 py-1 rounded-full font-bold">SUPERADMIN</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-500">Halo, <strong class="text-gray-800">{{ session('full_name') }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-xs font-bold text-gray-400 hover:text-red-500 transition">KELUAR</button>
            </form>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-10">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-xs font-bold text-[#4361EE] uppercase tracking-wider block mb-1">Total Mitra Aktif</span>
                <span class="text-3xl font-black">12 Perusahaan</span>
            </div>
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-xs font-bold text-amber-500 uppercase tracking-wider block mb-1">Menunggu Legitimasi</span>
                <span class="text-3xl font-black">2 Pengajuan</span>
            </div>
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-xs font-bold text-emerald-500 uppercase tracking-wider block mb-1">Total Pendapatan Deal</span>
                <span class="text-3xl font-black">Pro Terbuka</span>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h4 class="text-lg font-black text-gray-800">Daftar Akun Tenant Perusahaan Terdaftar</h4>
            <a href="{{ route('superadmin.add_company') }}" class="inline-flex items-center bg-[#4361EE] text-white font-bold text-sm px-5 py-3 rounded-xl hover:bg-blue-700 transition shadow-sm">
                ➕ Daftarkan Perusahaan Baru
            </a>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mt-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
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
                            <td class="p-4 text-[#4361EE]">{{ $company['email'] }}</td>
                            <td class="p-4 text-gray-500">{{ $company['phone_number'] }}</td>
                            <td class="p-4 text-gray-400 max-w-xs truncate">{{ $company['address'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 font-medium">
                                Belum ada data perusahaan tenant yang terdaftar di sistem pusat.<br>
                                <span class="text-xs text-gray-300">Klik tombol "Daftarkan Perusahaan Baru" untuk mengisi data.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>