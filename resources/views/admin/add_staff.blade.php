<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftarkan Staff - Web Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#FDFDFD] text-[#1E1E24] font-sans antialiased">
    <div class="max-w-2xl mx-auto px-6 py-12">
        <div class="mb-8">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-[#4361EE] hover:underline">⬅️ Kembali ke Dashboard</a>
            <h3 class="text-2xl font-black text-gray-900 mt-4">Pembuatan Akun Staff Baru</h3>
            <p class="text-sm text-gray-400 mt-1">Akun yang dibuat di bawah ini akan otomatis mengunci parameter ke kelompok perusahaan Anda.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100 shadow-sm">
            <form action="{{ route('admin.store_staff') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap Karyawan</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4361EE]" placeholder="Contoh: Budi Santoso, S.Kom" required>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Username Unik Login</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4361EE]" placeholder="contoh: budi_santoso" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email Aktif</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4361EE]" placeholder="budi@email.com" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 tracking-wider uppercase mb-2">Kata Sandi Akun</label>
                        <input type="password" name="password" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4361EE]" placeholder="Masukkan kata sandi awal" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tingkat Hak Otoritas (Role ID)</label>
                    <select name="role_id" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4361EE]">
                        <option value="2" {{ old('role_id') == '2' ? 'selected' : '' }}>Staff Utama / Asisten Senior (Role ID 2)</option>
                        <option value="3" {{ old('role_id') == '3' ? 'selected' : '' }}>Staff Junior / Asisten Magang (Role ID 3)</option>
                        <option value="1" {{ old('role_id') == '1' ? 'selected' : '' }}>Duplikasi Admin Pembantu (Role ID 1)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-[#4361EE] text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition shadow-sm text-sm">
                    🚀 Buat Akun &amp; Kirim Akses ke Aplikasi HP
                </button>
            </form>
        </div>
    </div>
</body>
</html>