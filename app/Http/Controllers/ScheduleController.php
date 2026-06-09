<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    // 1. MENAMPILKAN DAFTAR BLUEPRINT SHIFT BERDASARKAN COMPANY ADMIN
    public function index()
    {
        $companyId = Auth::user()->company_id; // Mengambil ID Company dari admin yang login

        // Menembak endpoint Express API baru yang sudah kita buat
        $response = Http::get("http://localhost:3000/api/getSchedulesByCompanyId/{$companyId}");

        $schedules = [];
        if ($response->successful()) {
            $schedules = $response->json();
        }

        return view('admin.schedules.index', compact('schedules'));
    }

    // 2. PROSES MENYIMPAN MASTER SHIFT BARU
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required', // Format HH:MM dari HTML5 Time Input
            'end_time' => 'required',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        // Menyusun Payload Data terstruktur sesuai kebutuhan Express API
        $payload = [
            'company_id'  => Auth::user()->company_id, // Multi-tenant isolasi
            'created_by'  => Auth::id(),               // ID Admin pembuat
            'title'       => $request->title,
            'description' => $request->description,
            'start_time'  => $request->start_time,     // Contoh: "08:00"
            'end_time'    => $request->end_time,       // Contoh: "16:00"
            'location'    => $request->location ?? 'Default Area',
        ];

        // Kirim data ke Node.js Express API
        $response = Http::post("http://localhost:3000/api/insertSchedules", $payload);

        if ($response->successful()) {
            return redirect()->route('schedules.index')->with('success', 'Blueprint Master Shift berhasil ditambahkan!');
        }

        return redirect()->back()->withErrors(['error' => 'Gagal menyimpan ke server API backend.'])->withInput();
    }

    // 3. PROSES UPDATE MASTER SHIFT
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        $payload = [
            'company_id'  => Auth::user()->company_id,
            'created_by'  => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'location'    => $request->location,
        ];

        // Kirim PUT request untuk update data spesifik berdasarkan ID
        $response = Http::put("http://localhost:3000/api/updateSchedule/{$id}", $payload);

        if ($response->successful()) {
            return redirect()->route('schedules.index')->with('success', 'Master Shift berhasil diperbarui!');
        }

        return redirect()->back()->withErrors(['error' => 'Gagal memperbarui data di server API backend.']);
    }
}