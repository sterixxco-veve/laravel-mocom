<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    private $expressUrl;

    public function __construct()
    {
        $this->expressUrl = env('EXPRESS_API_URL', 'https://backend-mocom.vercel.app/api');
    }

    public function dashboard()
    {
        try {
            // 1. Tembak API Express untuk mendapatkan seluruh data perusahaan dari database pusat
            $response = Http::get("{$this->expressUrl}/getAllCompanies");
            
            // Ambil data array JSON jika request sukses
            $companies = $response->successful() ? $response->json() : [];

            // 2. Filter perusahaan yang aktif secara dinamis dari koleksi data Express
            // (Antisipasi jika field 'is_active' belum ada, kita beri default true/1)
            $activeCompanies = collect($companies)->filter(function ($company) {
                return isset($company['is_active']) ? $company['is_active'] == 1 : true;
            })->values()->all();

            $totalMitra = count($companies);
            $waitingLegitimation = 0; 

        } catch (\Exception $e) {
            $companies = [];
            $activeCompanies = [];
            $totalMitra = 0;
            $waitingLegitimation = 0;
            session()->now('error', 'Gagal memuat data dari API Express: ' . $e->getMessage());
        }

        return view('superadmin.dashboard', compact(
            'companies', 
            'activeCompanies',
            'totalMitra', 
            'waitingLegitimation'
        ));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Form (Sisi Laravel)
        $request->validate([
            'company_name'  => 'required|string|max:255',
            'company_email' => 'required|email', 
            'password'      => 'required|string|min:6',
            'phone_number'  => 'required|string|max:20',
            'address'       => 'required|string',
        ], [
            'company_name.required'  => 'Nama perusahaan / institusi wajib diisi.',
            'company_email.required' => 'Email resmi perusahaan wajib diisi.',
            'password.required'      => 'Kata sandi login web admin wajib diisi.',
            'password.min'           => 'Kata sandi minimal harus 6 karakter.',
            'phone_number.required'  => 'Nomor telepon kontak wajib diisi.',
            'address.required'       => 'Alamat domisili kantor / pusat lab wajib diisi.',
        ]);

        try {
            // 2. TEMBAK HTTP POST ke Endpoint Express /api/registerCompany
            $targetUrl = "{$this->expressUrl}/registerCompany";

            $response = Http::post($targetUrl, [
                'company_name' => $request->company_name,
                'email'        => $request->company_email,
                'password'     => $request->password, // Disarankan enkripsi di handle node backend atau gunakan bcrypt di sini jika database menyimpan plain-text/hash
                'phone_number' => $request->phone_number,
                'address'      => $request->address,
            ]);

            // 3. Debugging jika Express menolak pendaftaran
            if (!$response->successful()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Express menolak pendaftaran: ' . ($response->json()['error'] ?? 'Unknown Error'));
            }

            // 4. Sukses, kembalikan ke Dashboard Utama
            return redirect()
                ->route('superadmin.dashboard')
                ->with('success', 'Akun tenant resmi berhasil didaftarkan di database pusat!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Laravel gagal terhubung ke backend Express: ' . $e->getMessage());
        }
    }

    public function create()
    {
        // Memanggil folder 'superadmin' dan file 'add_company.blade.php'
        return view('superadmin.add_company'); 
    }

    public function deactivate($id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->is_active = false;
            $company->save();

            return redirect()->route('superadmin.dashboard')
                ->with('success', "Perusahaan {$company->company_name} berhasil dinonaktifkan!");
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal menonaktifkan perusahaan: ' . $e->getMessage()]);
        }
    }

    public function activate($id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->is_active = true;
            $company->save();

            return redirect()->route('superadmin.dashboard')
                ->with('success', "Perusahaan {$company->company_name} berhasil diaktifkan kembali!");
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal mengaktifkan perusahaan: ' . $e->getMessage()]);
        }
    }

    // PROSES EKSEKUSI PENGADUAN / PENAMBAHAN STAFF BY BACKDOOR SUPERADMIN
    public function storeStaffByAdmin(Request $request)
    {
        $request->validate([
            'company_id'   => 'required|integer',
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email',
            'password'     => 'required|string|min:6',
            'phone_number' => 'required|string',
        ]);

        try {
            // Kita satukan URL secara manual dan presisi agar tidak terjadi salah alamat/double /api
            $targetUrl = "https://backend-mocom.vercel.app/api/superadmin/addStaff";
            
            // Kirim request sebagai JSON agar lebih universal diterima body-parser Express standar
            $response = Http::post($targetUrl, [
                'company_id'   => (int)$request->company_id,
                'full_name'    => $request->full_name,
                'email'        => $request->email,
                'password'     => $request->password,
                'phone_number' => $request->phone_number,
            ]);

            // 🛠️ KUNCI DEBUGGING: Jika masih tidak masuk log, baris ini akan memaksa browser Laravel 
            // menampilkan status asli yang dikirim oleh server Node.js (apakah 404, 403, atau lainnya)
            if (!$response->successful()) {
                dd([
                    'PESAN' => 'Request terkirim tapi Express menolak / salah alamat URL!',
                    'URL_TUJUAN' => $targetUrl,
                    'STATUS_CODE_EXPRESS' => $response->status(),
                    'RESPON_MENTAH_EXPRESS' => $response->body()
                ]);
            }

            return redirect()->route('superadmin.dashboard')
                ->with('success', 'Backdoor Akses Berhasil: Staff baru bernama ' . $request->full_name . ' sukses dimasukkan!');
            
        } catch (\Exception $e) {
            // Jika koneksi ke port 3000 benar-benar murni putus/mati
            dd([
                'PESAN' => 'Laravel gagal total mengetuk port 3000. Server Express kemungkinan mati atau salah port.',
                'ERROR_MESSAGE' => $e->getMessage()
            ]);
        }
    }

    public function getStaff($companyId)
    {
        try {
            // 🚀 Tembak langsung endpoint Express API pusat yang sudah kamu siapkan sebelumnya
            $response = Http::get("https://backend-mocom.vercel.app/api/getUsersByCompanyId/{$companyId}");
            
            if ($response->successful()) {
                // Ambil data array dari Express
                $staffList = $response->json();
                
                // Mengembalikan respons JSON murni ke Alpine.js frontend
                return response()->json($staffList);
            }
            
            return response()->json(['error' => 'Gagal menarik data dari server Express pusat.'], 400);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Koneksi Laravel ke Express gagal: ' . $e->getMessage()], 500);
        }
    }
}