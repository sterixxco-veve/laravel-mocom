<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SuperAdminController extends Controller
{
    private $expressUrl;

    public function __construct()
    {
        $this->expressUrl = env('EXPRESS_API_URL', 'http://127.0.0.1:3000/api');
    }

    public function dashboard()
    {
        try {
            $response = Http::get("{$this->expressUrl}/getAllCompanies");
            $companies = $response->successful() ? $response->json() : [];

            $totalMitra = count($companies);
            $waitingLegitimation = 0; 

        } catch (\Exception $e) {
            $companies = [];
            $totalMitra = 0;
            $waitingLegitimation = 0;
            session()->now('error', 'Gagal tersambung ke backend Node.js.');
        }

        // 🛠️ FIX SISI SUPERADMIN: Kembalikan ke view dashboard root milik superadmin/pusat
        // Gantilah 'admin.dashboard' menjadi target file blade Superadmin kamu yang sebenarnya
        return view('superadmin.dashboard', compact(
            'companies', 
            'totalMitra', 
            'waitingLegitimation'
        ));
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
            $targetUrl = "http://127.0.0.1:3000/api/superadmin/addStaff";
            
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
}