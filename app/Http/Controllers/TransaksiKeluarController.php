<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiKeluarController extends Controller
{
    public function index()
    {
        $transaksis = BarangKeluar::with(['barang', 'user'])->latest()->get();
        return view('transaksi_keluar.index', compact('transaksis'));
    }

    public function create()
    {
        $barangs = Barang::where('jumlah', '>', 0)->get();
        $defaultPemeriksa = Auth::user()->name ?? 'Pengelola Peralatan';
        $defaultPenyetuju = 'A. Zafriyadi, S.T.';

        return view('transaksi_keluar.create', compact('barangs', 'defaultPemeriksa', 'defaultPenyetuju'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'penerima' => 'required|string|max:255',
            'bagian' => 'required|string|max:255',
            'dibawa_oleh' => 'nullable|string|max:255',
            'diperiksa_oleh' => 'nullable|string|max:255',
            'disetujui_oleh' => 'nullable|string|max:255',
            'keperluan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($request->jumlah > $barang->jumlah) {
            return back()->withInput()->withErrors(['jumlah' => 'Stok tidak mencukupi. Sisa stok tersedia: ' . $barang->jumlah . ' ' . ($barang->satuan ?? 'Unit')]);
        }

        // Auto generate kode transaksi
        $todayStr = date('Ymd');
        $countToday = BarangKeluar::whereDate('created_at', today())->count() + 1;
        $kodeTransaksi = 'TRK-' . $todayStr . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($request, $barang, $kodeTransaksi) {
            BarangKeluar::create([
                'kode_transaksi' => $kodeTransaksi,
                'barang_id' => $request->barang_id,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'penerima' => $request->penerima,
                'bagian' => $request->bagian,
                'dibawa_oleh' => $request->dibawa_oleh ?: $request->penerima,
                'diperiksa_oleh' => $request->diperiksa_oleh,
                'disetujui_oleh' => $request->disetujui_oleh,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id()
            ]);

            $barang->decrement('jumlah', $request->jumlah);
        });

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi Barang Keluar berhasil disimpan. Kode: ' . $kodeTransaksi);
    }

    public function edit($id)
    {
        $transaksi = BarangKeluar::with('barang')->findOrFail($id);
        $barangs = Barang::all();

        return view('transaksi_keluar.edit', compact('transaksi', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = BarangKeluar::findOrFail($id);

        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'penerima' => 'required|string|max:255',
            'bagian' => 'required|string|max:255',
            'dibawa_oleh' => 'nullable|string|max:255',
            'diperiksa_oleh' => 'nullable|string|max:255',
            'disetujui_oleh' => 'nullable|string|max:255',
            'keperluan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $transaksi) {
            // Reconcile stock
            if ($transaksi->barang_id == $request->barang_id) {
                // Same item, calculate delta
                $delta = $request->jumlah - $transaksi->jumlah;
                $barang = Barang::findOrFail($request->barang_id);

                if ($delta > 0) {
                    if ($barang->jumlah < $delta) {
                        throw new \Exception('Stok tambahan tidak mencukupi. Sisa stok: ' . $barang->jumlah);
                    }
                    $barang->decrement('jumlah', $delta);
                } elseif ($delta < 0) {
                    $barang->increment('jumlah', abs($delta));
                }
            } else {
                // Item changed: restore old item stock, reduce new item stock
                $oldBarang = Barang::findOrFail($transaksi->barang_id);
                $oldBarang->increment('jumlah', $transaksi->jumlah);

                $newBarang = Barang::findOrFail($request->barang_id);
                if ($newBarang->jumlah < $request->jumlah) {
                    throw new \Exception('Stok barang baru tidak mencukupi. Sisa stok: ' . $newBarang->jumlah);
                }
                $newBarang->decrement('jumlah', $request->jumlah);
            }

            $transaksi->update([
                'barang_id' => $request->barang_id,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'penerima' => $request->penerima,
                'bagian' => $request->bagian,
                'dibawa_oleh' => $request->dibawa_oleh ?: $request->penerima,
                'diperiksa_oleh' => $request->diperiksa_oleh,
                'disetujui_oleh' => $request->disetujui_oleh,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
            ]);
        });

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi Barang Keluar berhasil diperbarui dan stok terkoreksi otomatis.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user->role || !in_array($user->role->slug, ['super-admin', 'admin'])) {
            return redirect()->route('transaksi-keluar.index')->with('error', 'Anda tidak memiliki hak akses untuk menghapus transaksi.');
        }

        $transaksi = BarangKeluar::findOrFail($id);

        DB::transaction(function () use ($transaksi) {
            // Restore actual unreturned stock
            $sisaKeluar = $transaksi->jumlah - $transaksi->jumlah_retur;
            if ($sisaKeluar > 0) {
                $barang = Barang::find($transaksi->barang_id);
                if ($barang) {
                    $barang->increment('jumlah', $sisaKeluar);
                }
            }
            $transaksi->delete();
        });

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi Barang Keluar berhasil dihapus dan stok telah dipulihkan.');
    }

    public function retur(Request $request, $id)
    {
        $transaksi = BarangKeluar::findOrFail($id);

        $maxRetur = $transaksi->jumlah - $transaksi->jumlah_retur;

        $request->validate([
            'jumlah_retur' => 'required|integer|min:1|max:' . $maxRetur,
            'catatan_retur' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $transaksi) {
            $transaksi->jumlah_retur += $request->jumlah_retur;
            
            $riwayat = now()->format('d/m/Y H:i') . ' - Diretur ' . $request->jumlah_retur . ' unit oleh ' . (Auth::user()->name ?? 'Admin') . ': ' . ($request->catatan_retur ?: 'Tanpa keterangan');
            $transaksi->catatan_retur = $transaksi->catatan_retur ? ($transaksi->catatan_retur . "\n" . $riwayat) : $riwayat;
            $transaksi->save();

            // Return physical stock back to master barang
            $barang = Barang::findOrFail($transaksi->barang_id);
            $barang->increment('jumlah', $request->jumlah_retur);
        });

        return redirect()->route('transaksi-keluar.index')->with('success', 'Retur barang berhasil dicatat. ' . $request->jumlah_retur . ' unit telah dikembalikan ke stok gudang.');
    }

    public function cetakForm($id)
    {
        $transaksi = BarangKeluar::with(['barang', 'user'])->findOrFail($id);

        $pdf = Pdf::loadView('transaksi_keluar.cetak_form', compact('transaksi'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Form_Keluar_Alat_' . ($transaksi->kode_transaksi ?: $transaksi->id) . '.pdf';
        return $pdf->stream($filename);
    }
}
