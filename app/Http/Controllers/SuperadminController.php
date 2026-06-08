<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SuperadminController extends Controller
{
    public function dashboard() {
        if (session('user_role') !== 'superadmin') return redirect()->route('login');
        
        // Mengambil semua data perusahaan dari Express API untuk ditampilkan di dashboard
        $response = Http::get(env('EXPRESS_API_URL') . '/getAllCompanies');
        $companies = $response->successful() ? $response->json() : [];

        return view('superadmin.dashboard', compact('companies'));
    }

    public function showRegisterCompanyForm() {
        if (session('user_role') !== 'superadmin') return redirect()->route('login');
        return view('superadmin.add_company');
    }

    public function storeCompany(Request $request) {
        if (session('user_role') !== 'superadmin') return redirect()->route('login');

        // 1. Validasi Input Form dari Web Superadmin
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email'        => 'required|email',
            'password'     => 'required|min:6',
            'phone_number' => 'required|string',
            'address'      => 'required|string'
        ]);

        // 2. Melempar Payload Data Menuju Server Express API (Port 3000)
        $response = Http::post(env('EXPRESS_API_URL') . '/registerCompany', [
            'company_name' => $request->company_name,
            'email'        => $request->email,
            'password'     => $request->password,
            'phone_number' => $request->phone_number,
            'address'      => $request->address
        ]);

        // 3. Penanganan Respons Balikan
        if ($response->successful()) {
            return redirect()->route('superadmin.dashboard')
                ->with('success', 'Perusahaan baru berhasil didaftarkan ke sistem pusat!');
        }

        // Jika Express API mengembalikan error (misal email duplikat)
        $errorData = $response->json();
        return back()->withErrors(['msg' => $errorData['error'] ?? 'Gagal menyimpan data perusahaan baru.']);
    }
}