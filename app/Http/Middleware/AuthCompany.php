<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        // Memeriksa apakah session admin (company_id) sudah terisi atau belum
        if (!$request->session()->has('company_id')) {
            // Jika kosong, tendang paksa admin kembali ke halaman login
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses panel.');
        }

        return $next($request);
    }
}