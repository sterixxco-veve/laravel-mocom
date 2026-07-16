<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Pdf;

class CompanyAdminController extends Controller
{
    // 1. HALAMAN UTAMA DASHBOARD HRD / LAB
    public function dashboard()
    {
        // 1. Ambil company_id dari session admin yang sedang login
        $companyId = session('company_id');
        $baseUrl = env('EXPRESS_API_URL', 'https://backend-mocom.vercel.app/api');

        // Safety check jika session kosong
        if (!$companyId) {
            return redirect()->route('login')->with('error', 'Sesi Anda telah berakhir.');
        }

        try {
            // 1. Mengambil data staf khusus company ini (Sudah ada sebelumnya)
            $staffResponse = Http::get("https://backend-mocom.vercel.app/api/getAllStaffCompany/{$companyId}");
            $staffList = $staffResponse->successful() ? $staffResponse->json() : [];

            // 2. Mengambil data pengajuan izin khusus company ini (Sudah ada sebelumnya)
            $leaveResponse = Http::get("https://backend-mocom.vercel.app/api/getLeaveRequestsCompany/{$companyId}");
            $allRequests = $leaveResponse->successful() ? $leaveResponse->json() : [];
            $leaveRequests = collect($allRequests)->where('status', 'pending')->values()->all();

            // 3. Mengambil detail data company (Sudah ada sebelumnya)
            $companyResponse = Http::get("https://backend-mocom.vercel.app/api/getCompanyDetail/{$companyId}");
            $companyDetail = $companyResponse->successful() ? $companyResponse->json() : null;

            // ➕ 4. FITUR BARU: Ambil Rekap Total Jam Kerja Mingguan Staf dari Express API
            $workloadResponse = Http::get("https://backend-mocom.vercel.app/api/getWeeklyWorkload/{$companyId}");
            $workloadData = $workloadResponse->successful() ? $workloadResponse->json() : [
                'overworked' => 0,
                'normal' => 0,
                'underworked' => 0,
                'details' => []
            ];

            // ➕ AMBIL LOG AKTIVITAS HARI INI:
            $todayLogResponse = Http::get("https://backend-mocom.vercel.app/api/getTodayAttendanceLog/{$companyId}");
            $todayAttendanceLogs = $todayLogResponse->successful() ? $todayLogResponse->json() : [];

            $shiftResponse = Http::get("{$baseUrl}/getSchedulesByCompanyId/{$companyId}");
            $shiftMasters = $shiftResponse->successful() ? $shiftResponse->json() : [];

        } catch (\Exception $e) {
            $shiftMasters = [];
            $leaveRequests = [];
            $workloadData = ['overworked' => 0, 'normal' => 0, 'underworked' => 0, 'details' => []];
            $todayAttendanceLogs = [];
            $companyDetail = null;
        }

        // Tambahkan workloadData ke dalam compact
        return view('admin.dashboard', compact(
            'staffList',
            'leaveRequests',
            'companyDetail',
            'workloadData',
            'todayAttendanceLogs',
            'shiftMasters' // <-- Variabel ini aman dibaca di sini
        ));

    }

    // 2. MENAMPILKAN FORM TAMBAH STAFF
    public function showAddStaffForm()
    {
        if (session('user_role') !== 'admin_company')
            return redirect()->route('login');
        return view('admin.add_staff');
    }

    // 3. MEMPROSES PENYIMPANAN DATA STAFF BARU KE EXPRESS API
    public function storeStaff(Request $request)
    {
        if (session('user_role') !== 'admin_company')
            return redirect()->route('login');

        // Validasi input form di sisi Laravel
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:50',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role_id' => 'required|integer' // 2: Staff Senior, 3: Staff Junior, dll.
        ]);

        // Mengirimkan payload ke endpoint /api/register milik Express API
        $response = Http::post(env('EXPRESS_API_URL') . '/register', [
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => (int) $request->role_id,
            'company_id' => (int) session('company_id') // Otomatis mengunci Tenant ID dari session login
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Akun staf baru berhasil dibuat dan siap digunakan di aplikasi mobile!');
        }

        // Tangkap pesan error jika terjadi kegagalan di database pusat (misal username duplikat)
        $errorData = $response->json();
        return back()->withErrors(['msg' => $errorData['error'] ?? 'Gagal membuat akun staf baru.']);
    }

    // Jangan lupa import class PDF di bagian paling atas file controller:
// use Barryvdh\DomPDF\Facade\Pdf;

    public function downloadPdfReport()
    {
        if (session('user_role') !== 'admin_company')
            return redirect()->route('login');

        $companyId = session('company_id');
        $companyName = session('company_name');

        $response = Http::get(env('EXPRESS_API_URL') . "/getAttendanceReport/{$companyId}");

        if ($response->failed()) {
            return back()->withErrors(['msg' => 'Gagal mengambil data laporan dari server pusat.']);
        }

        $reportData = $response->json();
        $logs = $reportData['logs'] ?? [];
        $aiSummary = $reportData['ai_summary'] ?? 'Tidak ada analisis AI tersedia.';
        $dateToday = date('d F Y');

        // Pemanggilan tetap sama, namun memanggil alias 'use Pdf;' di atas
        $pdf = Pdf::loadView('admin.pdf_report', compact('logs', 'aiSummary', 'companyName', 'dateToday'));

        return $pdf->download("Laporan_Absensi_Mocom_{$companyId}_" . date('Ymd') . ".pdf");
    }
}