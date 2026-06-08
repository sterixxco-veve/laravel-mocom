<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Pdf;

class CompanyAdminController extends Controller
{
    // 1. HALAMAN UTAMA DASHBOARD HRD / LAB
    public function dashboard() {
        // 1. Proteksi Keamanan: Pastikan user yang masuk adalah Admin Company
        if (session('user_role') !== 'admin_company') return redirect()->route('login');

        $companyId = session('company_id');

        // 2. Ambil data staff terikat company_id
        $staffResponse = Http::get(env('EXPRESS_API_URL') . "/getAllStaffCompany/{$companyId}");
        $staffList = $staffResponse->successful() ? $staffResponse->json() : [];

        // 3. Ambil Informasi Detail Profil Perusahaan
        $companyResponse = Http::get(env('EXPRESS_API_URL') . "/getCompanyDetail/{$companyId}");
        $companyDetail = $companyResponse->successful() ? $companyResponse->json() : null;

        // 4. AMBIL DATA LEAVE REQUESTS (Saringan Gemini AI dari database Express)
        // Ambil endpoint ini, gunakan tanda petik dua ("") agar variabel companyId terekstrak
        $leaveResponse = Http::get(env('EXPRESS_API_URL') . "/getLeaveRequestsCompany/{$companyId}");
        $leaveRequests = $leaveResponse->successful() ? $leaveResponse->json() : [];

        // 5. PERBAIKAN UTAMA: Pastikan 'leaveRequests' tertulis di dalam compact()
        return view('admin.dashboard', compact('staffList', 'companyDetail', 'leaveRequests'));
    }

    // 2. MENAMPILKAN FORM TAMBAH STAFF
    public function showAddStaffForm() {
        if (session('user_role') !== 'admin_company') return redirect()->route('login');
        return view('admin.add_staff');
    }

    // 3. MEMPROSES PENYIMPANAN DATA STAFF BARU KE EXPRESS API
    public function storeStaff(Request $request) {
        if (session('user_role') !== 'admin_company') return redirect()->route('login');

        // Validasi input form di sisi Laravel
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username'  => 'required|string|max:50',
            'email'     => 'required|email',
            'password'  => 'required|min:6',
            'role_id'   => 'required|integer' // 2: Staff Senior, 3: Staff Junior, dll.
        ]);

        // Mengirimkan payload ke endpoint /api/register milik Express API
        $response = Http::post(env('EXPRESS_API_URL') . '/register', [
            'full_name'  => $request->full_name,
            'username'   => $request->username,
            'email'      => $request->email,
            'password'   => $request->password,
            'role_id'    => (int)$request->role_id,
            'company_id' => (int)session('company_id') // Otomatis mengunci Tenant ID dari session login
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

    public function downloadPdfReport() {
        if (session('user_role') !== 'admin_company') return redirect()->route('login');

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