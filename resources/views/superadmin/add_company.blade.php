<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftarkan Perusahaan - Superadmin Control</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="{{ asset('js/phone-validation.js') }}"></script>
</head>
<body class="bg-[#FDFDFD] text-[#1E1E24] font-sans antialiased">
    <div class="max-w-2xl mx-auto px-6 py-12">
        <div class="mb-8">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-bold text-[#4361EE] hover:underline">⬅️ Kembali ke Dashboard</a>
            <h3 class="text-2xl font-black text-gray-900 mt-4">Pendaftaran Akun Perusahaan Baru</h3>
            <p class="text-sm text-gray-400 mt-1">Isi data identitas fisik di bawah ini untuk membuatkan akses komparatif *Multi-Company*.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100 shadow-sm">
            <form action="{{ route('superadmin.store_company') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Perusahaan / Institusi</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4361EE]" placeholder="Contoh: PT Sejahtera Maju Jaya" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Resmi Perusahaan</label>
                        <input type="email" name="company_email" value="{{ old('email') }}" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4361EE]" placeholder="admin@maju-jaya.com" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kata Sandi Login Web Admin</label>
                        <input type="password" name="password" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4361EE]" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor Telepon Kontak</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4361EE]" placeholder="021-xxxxxx atau 08xxxx" required>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Domisili Kantor / Pusat Lab</label>
                    <textarea name="address" rows="3" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4361EE]" placeholder="Tuliskan alamat lengkap perusahaan di sini..." required>{{ old('address') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-[#4361EE] text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition shadow-sm text-sm">
                    🚀 Proses &amp; Daftarkan Akun Perusahaan Resmi
                </button>
            </form>
        </div>
    </div>
</body>
</html>