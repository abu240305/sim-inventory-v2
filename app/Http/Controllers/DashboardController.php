<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard analytics page
     */
    public function index()
    {
        $user = auth()->user();
        
        $totalBarang = Barang::count();
        $stokMenipis = Barang::where('jumlah', '<', 5)->get(); // ambang batas stok 5
        
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        
        $masukBulanIni = BarangMasuk::whereMonth('tanggal', $bulanIni)
                                    ->whereYear('tanggal', $tahunIni)
                                    ->sum('jumlah');
                                    
        $keluarBulanIni = BarangKeluar::whereMonth('tanggal', $bulanIni)
                                      ->whereYear('tanggal', $tahunIni)
                                      ->sum('jumlah');
        
        $data = compact('totalBarang', 'stokMenipis', 'masukBulanIni', 'keluarBulanIni');
        
        // Check if user is Admin or Super Admin
        if ($user->role && in_array($user->role->slug, ['super-admin', 'admin'])) {
            return view('pages.dashboard.admin', $data);
        }

        // Default dashboard for non-admin users
        return view('pages.dashboard.user', $data);
    }
}
