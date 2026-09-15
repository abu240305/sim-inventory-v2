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
        $totalStokFisik = Barang::sum('jumlah');
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
        $trafficDays = [];

        $maxDateMasuk = BarangMasuk::max('tanggal');
        $maxDateKeluar = BarangKeluar::max('tanggal');
        $today = Carbon::now()->format('Y-m-d');
        
        $maxDate = max($maxDateMasuk, $maxDateKeluar, $today);

        for ($i = 6; $i >= 0; $i--) {
            $dateObj = Carbon::parse($maxDate)->subDays($i);
            $date = $dateObj->format('Y-m-d');
            $trafficDays[] = $dateObj->format('d M');
            $trafficMasuk[] = (int) BarangMasuk::whereDate('tanggal', $date)->sum('jumlah');
            $trafficKeluar[] = (int) BarangKeluar::whereDate('tanggal', $date)->sum('jumlah');
        }

        // --- DATA GRAFIK MINGGUAN (4 Minggu Terakhir) ---
        $weeklyMasuk = [];
        $weeklyKeluar = [];
        $weeklyLabels = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::parse($maxDate)->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::parse($maxDate)->subWeeks($i)->endOfWeek();
            $weeklyLabels[] = $startOfWeek->format('d M') . ' - ' . $endOfWeek->format('d M');
            $weeklyMasuk[] = (int) BarangMasuk::whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])->sum('jumlah');
            $weeklyKeluar[] = (int) BarangKeluar::whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])->sum('jumlah');
        }

        // --- DATA GRAFIK BULANAN (6 Bulan Terakhir) ---
        $monthlyMasuk = [];
        $monthlyKeluar = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::parse($maxDate)->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');
            $monthlyMasuk[] = (int) BarangMasuk::whereMonth('tanggal', $date->month)->whereYear('tanggal', $date->year)->sum('jumlah');
            $monthlyKeluar[] = (int) BarangKeluar::whereMonth('tanggal', $date->month)->whereYear('tanggal', $date->year)->sum('jumlah');
        }

        $data = compact(
            'totalBarang', 'totalStokFisik', 'stokMenipis', 'masukBulanIni', 'keluarBulanIni',
            'trafficMasuk', 'trafficKeluar', 'trafficDays',
            'weeklyMasuk', 'weeklyKeluar', 'weeklyLabels',
            'monthlyMasuk', 'monthlyKeluar', 'monthlyLabels'
        );
        
        // Check if user is Admin or Super Admin
        if ($user->role && in_array($user->role->slug, ['super-admin', 'admin'])) {
            return view('pages.dashboard.admin', $data);
        }

        // Default dashboard for non-admin users
        return view('pages.dashboard.user', $data);
    }
}
