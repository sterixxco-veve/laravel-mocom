<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        // Validasi input form dari user (Bisa menggunakan Username atau Email)
        $request->validate([
            'email_or_username' => 'required|string',
            'password'          => 'required|string',
        ]);

        // Kirim request autentikasi ke backend Express API kamu
        $response = Http::post(env('EXPRESS_API_URL') . '/login', [
            'email'    => $request->email_or_username,
            'username' => $request->email_or_username,
            'password' => $request->password
        ]);

        // Jika kredensial cocok dan server merespon dengan kode 200 OK
        if ($response->successful()) {
            $userData = $response->json();

            // DETEKSI OTOMATIS BERDASARKAN ROLE_ID DARI DATABASE
            
            // Skenario A: Jika dia adalah Superadmin (role_id = 4)
            if (isset($userData['role_id']) && $userData['role_id'] == 4) {
                session([
                    'user_id'      => $userData['id'],
                    'user_role'    => 'superadmin',
                    'full_name'    => $userData['full_name'],
                    'is_logged_in' => true
                ]);
                return redirect()->route('superadmin.dashboard');
            }

            // Skenario B: Jika dia adalah Admin Company (role_id = 1)
            if (isset($userData['role_id']) && $userData['role_id'] == 1) {
                session([
                    'user_id'      => $userData['id'],
                    'user_role'    => 'admin_company',
                    'full_name'    => $userData['full_name'],
                    'company_id'   => $userData['company_id'] ?? null, // Untuk melacak company yang mana
                    'is_logged_in' => true
                ]);
                return redirect()->route('admin.dashboard');
            }

            // Skenario C: Jika role_id tidak diizinkan masuk ke panel web (misal staff biasa)
            return back()->withErrors(['msg' => 'Akses ditolak. Panel ini hanya untuk Superadmin dan Admin Company.']);
        }

        // Jika gagal (Respons 401 dari Express API)
        return back()->withErrors(['msg' => 'Email/Username atau kata sandi salah.']);
    }

    public function logout() {
        session()->flush();
        return redirect()->route('login');
    }
}