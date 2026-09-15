<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->jenis ?? 'stok';
        $tanggal_mulai = $request->tanggal_mulai ?? date('Y-m-01');
        $tanggal_akhir = $request->tanggal_akhir ?? date('Y-m-t');

        $data = $this->queryData($jenis, $tanggal_mulai, $tanggal_akhir);

        return view('laporan.index', compact('data', 'jenis', 'tanggal_mulai', 'tanggal_akhir'));
    }

    public function exportPdf(Request $request)
    {
        $jenis = $request->jenis ?? 'stok';
        $tanggal_mulai = $request->tanggal_mulai ?? date('Y-m-01');
        $tanggal_akhir = $request->tanggal_akhir ?? date('Y-m-t');

        $data = $this->queryData($jenis, $tanggal_mulai, $tanggal_akhir);

        $pdf = Pdf::loadView('laporan.pdf', compact('data', 'jenis', 'tanggal_mulai', 'tanggal_akhir'));
        return $pdf->download('laporan_' . $jenis . '_' . date('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $jenis = $request->jenis ?? 'stok';
        $tanggal_mulai = $request->tanggal_mulai ?? date('Y-m-01');
        $tanggal_akhir = $request->tanggal_akhir ?? date('Y-m-t');

        return Excel::download(new LaporanExport($jenis, $tanggal_mulai, $tanggal_akhir), 'laporan_' . $jenis . '_' . date('Ymd') . '.xlsx');
    }

    private function queryData($jenis, $tanggal_mulai, $tanggal_akhir)
    {
        if ($jenis == 'masuk') {
            return BarangMasuk::with(['barang', 'user'])
                ->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])
                ->orderBy('tanggal', 'desc')
                ->get();
        } elseif ($jenis == 'keluar') {
            return BarangKeluar::with(['barang', 'user'])
                ->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])
                ->orderBy('tanggal', 'desc')
                ->get();
        } else {
            // Default: Stok
            return Barang::with('kategori')->orderBy('nama_barang')->get();
        }
    }
}
