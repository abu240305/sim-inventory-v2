<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    private array $hariIndo = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];

    public function index(Request $request)
    {
        // Update status terlambat secara otomatis untuk peminjaman aktif yang melewati batas tanggal
        Peminjaman::where('status', 'dipinjam')
            ->whereDate('rencana_kembali', '<', Carbon::today())
            ->update(['status' => 'terlambat']);

        $query = Peminjaman::with(['details.barang', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('nama_peminjam', 'like', "%{$search}%")
                  ->orWhere('instansi_peminjam', 'like', "%{$search}%")
                  ->orWhere('keperluan', 'like', "%{$search}%")
                  ->orWhereHas('details.barang', function ($bq) use ($search) {
                      $bq->where('nama_barang', 'like', "%{$search}%")
                         ->orWhere('kode_barang', 'like', "%{$search}%");
                  });
            });
        }

        $peminjamans = $query->latest()->get();

        $totalSemua = Peminjaman::count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();
        $totalTerlambat = Peminjaman::where('status', 'terlambat')->count();

        return view('peminjaman.index', compact('peminjamans', 'totalSemua', 'totalDipinjam', 'totalDikembalikan', 'totalTerlambat'));
    }

    public function create()
    {
        // Hanya barang yang bertipe 'alat' dan memiliki stok tersedia
        $alats = Barang::where('jenis', 'alat')->where('jumlah', '>', 0)->get();

        $defaultPenyerah = 'A. Zafriyadi, S.T.';
        $defaultJabatanPenyerah = 'Ketua Satuan Pengelola Peralatan – Unit Hidrologi dan Kualitas Air';
        $defaultInstansiPenyerah = 'BBWS Pompengan Jeneberang';

        // Rekomendasi format nomor surat dinas
        $currentYear = date('Y');
        $urut = Peminjaman::whereYear('created_at', $currentYear)->count() + 1;
        $nomorSuratRekomendasi = 'UM0102/B/BBWS11.6.3/' . $currentYear . '/' . str_pad($urut, 2, '0', STR_PAD_LEFT);

        return view('peminjaman.create', compact('alats', 'defaultPenyerah', 'defaultJabatanPenyerah', 'defaultInstansiPenyerah', 'nomorSuratRekomendasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:255|unique:peminjamans',
            'tanggal_pinjam' => 'required|date',
            'rencana_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'nama_penyerah' => 'required|string|max:255',
            'jabatan_penyerah' => 'required|string|max:255',
            'instansi_penyerah' => 'required|string|max:255',
            'nama_peminjam' => 'required|string|max:255',
            'jabatan_peminjam' => 'required|string|max:255',
            'instansi_peminjam' => 'required|string|max:255',
            'kontak_peminjam' => 'nullable|string|max:255',
            'keperluan' => 'required|string',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
            'kondisi_pinjam' => 'nullable|array',
            'keterangan_alat' => 'nullable|array',
        ]);

        $dayName = Carbon::parse($request->tanggal_pinjam)->format('l');
        $hariSurat = $this->hariIndo[$dayName] ?? $dayName;

        DB::transaction(function () use ($request, $hariSurat) {
            $peminjaman = Peminjaman::create([
                'nomor_surat' => $request->nomor_surat,
                'hari_surat' => $hariSurat,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'rencana_kembali' => $request->rencana_kembali,
                'nama_penyerah' => $request->nama_penyerah,
                'jabatan_penyerah' => $request->jabatan_penyerah,
                'instansi_penyerah' => $request->instansi_penyerah,
                'nama_peminjam' => $request->nama_peminjam,
                'jabatan_peminjam' => $request->jabatan_peminjam,
                'instansi_peminjam' => $request->instansi_peminjam,
                'kontak_peminjam' => $request->kontak_peminjam,
                'keperluan' => $request->keperluan,
                'status' => 'dipinjam',
                'user_id' => Auth::id(),
            ]);

            foreach ($request->barang_id as $idx => $barangId) {
                $qty = $request->jumlah[$idx] ?? 1;
                $kondisi = $request->kondisi_pinjam[$idx] ?? 'Baik';
                $ket = $request->keterangan_alat[$idx] ?? null;

                $barang = Barang::findOrFail($barangId);
                if ($barang->jumlah < $qty) {
                    throw new \Exception("Stok alat '{$barang->nama_barang}' tidak mencukupi (tersedia: {$barang->jumlah}, diminta: {$qty}).");
                }

                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $barangId,
                    'jumlah' => $qty,
                    'kondisi_pinjam' => $kondisi,
                    'keterangan' => $ket,
                ]);

                // Kurangi stok alat yang sedang dipinjam
                $barang->decrement('jumlah', $qty);
            }
        });

        return redirect()->route('peminjaman.index')->with('success', 'Berita Acara Peminjaman Alat berhasil diterbitkan dengan nomor: ' . $request->nomor_surat);
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['details.barang', 'user'])->findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function kembalikan(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);

        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Peminjaman alat ini sudah tercatat telah dikembalikan sebelumnya.');
        }

        $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi_kembali' => 'required|string|max:255',
            'nama_penyerah_kembali' => 'required|string|max:255',
            'nama_penerima_kembali' => 'required|string|max:255',
            'catatan_kembali' => 'nullable|string',
        ]);

        $dayName = Carbon::parse($request->tanggal_kembali_aktual)->format('l');
        $hariKembali = $this->hariIndo[$dayName] ?? $dayName;

        DB::transaction(function () use ($request, $peminjaman, $hariKembali) {
            // Pulihkan stok setiap alat
            foreach ($peminjaman->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->increment('jumlah', $detail->jumlah);
                }
            }

            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'hari_kembali' => $hariKembali,
                'kondisi_kembali' => $request->kondisi_kembali,
                'nama_penyerah_kembali' => $request->nama_penyerah_kembali,
                'nama_penerima_kembali' => $request->nama_penerima_kembali,
                'catatan_kembali' => $request->catatan_kembali,
            ]);
        });

        return redirect()->route('peminjaman.index')->with('success', "Pengembalian alat untuk nomor surat '{$peminjaman->nomor_surat}' berhasil diproses dan stok alat telah dipulihkan.");
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user->role || !in_array($user->role->slug, ['super-admin', 'admin'])) {
            return redirect()->route('peminjaman.index')->with('error', 'Anda tidak memiliki hak akses untuk menghapus data peminjaman.');
        }

        $peminjaman = Peminjaman::with('details')->findOrFail($id);

        DB::transaction(function () use ($peminjaman) {
            // Jika status masih dipinjam atau terlambat, pulihkan stok unit yang terpotong
            if (in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
                foreach ($peminjaman->details as $detail) {
                    $barang = Barang::find($detail->barang_id);
                    if ($barang) {
                        $barang->increment('jumlah', $detail->jumlah);
                    }
                }
            }

            $peminjaman->delete();
        });

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus dan unit stok telah disesuaikan.');
    }

    public function cetakBeritaAcara($id)
    {
        $peminjaman = Peminjaman::with(['details.barang', 'user'])->findOrFail($id);

        // Ubah tanggal menjadi format terbilang resmi
        $tanggalTerbilang = $this->terbilangTanggal($peminjaman->tanggal_pinjam);

        $pdf = Pdf::loadView('peminjaman.berita_acara_pdf', compact('peminjaman', 'tanggalTerbilang'));
        $pdf->setPaper('A4', 'portrait');

        $cleanNomor = str_replace(['/', '\\', ' '], '_', $peminjaman->nomor_surat);
        $filename = 'Berita_Acara_Peminjaman_Alat_' . $cleanNomor . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Konversi tanggal menjadi kata terbilang resmi surat dinas
     * Contoh: 09 September 2026 -> [hari: Rabu, tanggal: Sembilan, bulan: September, tahun: Dua Ribu Dua Puluh Enam]
     */
    private function terbilangTanggal($date): array
    {
        $c = Carbon::parse($date);
        $dayName = $this->hariIndo[$c->format('l')] ?? $c->format('l');

        $angkaKata = [
            1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat', 5 => 'Lima',
            6 => 'Enam', 7 => 'Tujuh', 8 => 'Delapan', 9 => 'Sembilan', 10 => 'Sepuluh',
            11 => 'Sebelas', 12 => 'Dua Belas', 13 => 'Tiga Belas', 14 => 'Empat Belas',
            15 => 'Lima Belas', 16 => 'Enam Belas', 17 => 'Tujuh Belas', 18 => 'Delapan Belas',
            19 => 'Sembilan Belas', 20 => 'Dua Puluh', 21 => 'Dua Puluh Satu', 22 => 'Dua Puluh Dua',
            23 => 'Dua Puluh Tiga', 24 => 'Dua Puluh Empat', 25 => 'Dua Puluh Lima',
            26 => 'Dua Puluh Enam', 27 => 'Dua Puluh Tujuh', 28 => 'Dua Puluh Delapan',
            29 => 'Dua Puluh Sembilan', 30 => 'Tiga Puluh', 31 => 'Tiga Puluh Satu',
        ];

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tglInt = (int)$c->format('j');
        $blnInt = (int)$c->format('n');
        $thnInt = (int)$c->format('Y');

        return [
            'hari' => $peminjamanHari ?? $dayName,
            'tanggal' => $angkaKata[$tglInt] ?? (string)$tglInt,
            'bulan' => $bulanIndo[$blnInt] ?? $c->format('F'),
            'tahun' => $this->penyebut($thnInt),
            'formatted_full' => $c->translatedFormat('d F Y')
        ];
    }

    private function penyebut($nilai): string
    {
        $nilai = abs($nilai);
        $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } elseif ($nilai < 20) {
            $temp = $this->penyebut($nilai - 10) . " Belas";
        } elseif ($nilai < 100) {
            $temp = $this->penyebut((int)($nilai / 10)) . " Puluh " . $this->penyebut($nilai % 10);
        } elseif ($nilai < 200) {
            $temp = " Seratus " . $this->penyebut($nilai - 100);
        } elseif ($nilai < 1000) {
            $temp = $this->penyebut((int)($nilai / 100)) . " Ratus " . $this->penyebut($nilai % 100);
        } elseif ($nilai < 2000) {
            $temp = " Seribu " . $this->penyebut($nilai - 1000);
        } elseif ($nilai < 1000000) {
            $temp = $this->penyebut((int)($nilai / 1000)) . " Ribu " . $this->penyebut($nilai % 1000);
        }
        return preg_replace('/\s+/', ' ', trim($temp));
    }
}
