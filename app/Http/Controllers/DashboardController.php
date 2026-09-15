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
        
        // --- DATA GRAFIK TRAFFIC (7 Hari Terakhir) ---
        $trafficMasuk = [];
        $trafficKeluar = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $trafficMasuk[] = (int) BarangMasuk::whereDate('tanggal', $date)->sum('jumlah');
            // Keluar dijadikan negatif karena chart template menggunakan format mirror/stacked
            $trafficKeluar[] = -((int) BarangKeluar::whereDate('tanggal', $date)->sum('jumlah'));
        }

        // --- DATA GRAFIK SYSTEM HEALTH (6 Bulan Terakhir) ---
        $healthMasuk = [];
        $healthKeluar = [];
        $healthMonths = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $healthMonths[] = $date->format('M'); // 'Jan', 'Feb', dll.
            $healthMasuk[] = (int) BarangMasuk::whereMonth('tanggal', $date->month)->whereYear('tanggal', $date->year)->sum('jumlah');
            $healthKeluar[] = (int) BarangKeluar::whereMonth('tanggal', $date->month)->whereYear('tanggal', $date->year)->sum('jumlah');
        }

        $data = compact(
            'totalBarang', 'stokMenipis', 'masukBulanIni', 'keluarBulanIni',
            'trafficMasuk', 'trafficKeluar',
            'healthMasuk', 'healthKeluar', 'healthMonths'
        );
        
        // Check if user is Admin or Super Admin
        if ($user->role && in_array($user->role->slug, ['super-admin', 'admin'])) {
            return view('pages.dashboard.admin', $data);
        }

        // Default dashboard for non-admin users
        return view('pages.dashboard.user', $data);
    }
}
