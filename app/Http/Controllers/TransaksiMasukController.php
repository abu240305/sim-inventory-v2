<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiMasukController extends Controller
{
    public function index()
    {
        $transaksis = BarangMasuk::with(['barang', 'user'])->latest()->get();
        return view('transaksi_masuk.index', compact('transaksis'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('transaksi_masuk.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'sumber' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            BarangMasuk::create([
                'barang_id' => $request->barang_id,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'sumber' => $request->sumber,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id()
            ]);

            $barang = Barang::findOrFail($request->barang_id);
            $barang->increment('jumlah', $request->jumlah);
        });

        return redirect()->route('transaksi-masuk.index')->with('success', 'Transaksi Barang Masuk berhasil disimpan');
    }

    public function edit($id)
    {
        $transaksi = BarangMasuk::with('barang')->findOrFail($id);
        $barangs = Barang::all();

        return view('transaksi_masuk.edit', compact('transaksi', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = BarangMasuk::findOrFail($id);

        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'sumber' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $transaksi) {
            if ($transaksi->barang_id == $request->barang_id) {
                $delta = $request->jumlah - $transaksi->jumlah;
                $barang = Barang::findOrFail($request->barang_id);

                if ($delta > 0) {
                    $barang->increment('jumlah', $delta);
                } elseif ($delta < 0) {
                    $pengurangan = abs($delta);
                    if ($barang->jumlah < $pengurangan) {
                        throw new \Exception('Koreksi tidak dapat dilakukan karena stok barang di gudang tersisa ' . $barang->jumlah . ', tidak mencukupi untuk dikurangi ' . $pengurangan . '.');
                    }
                    $barang->decrement('jumlah', $pengurangan);
                }
            } else {
                $oldBarang = Barang::findOrFail($transaksi->barang_id);
                if ($oldBarang->jumlah < $transaksi->jumlah) {
                    throw new \Exception('Stok barang lama di gudang tidak mencukupi untuk dikoreksi.');
                }
                $oldBarang->decrement('jumlah', $transaksi->jumlah);

                $newBarang = Barang::findOrFail($request->barang_id);
                $newBarang->increment('jumlah', $request->jumlah);
            }

            $transaksi->update([
                'barang_id' => $request->barang_id,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'sumber' => $request->sumber,
                'keterangan' => $request->keterangan,
            ]);
        });

        return redirect()->route('transaksi-masuk.index')->with('success', 'Transaksi Barang Masuk berhasil diperbarui dan stok terkoreksi.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user->role || !in_array($user->role->slug, ['super-admin', 'admin'])) {
            return redirect()->route('transaksi-masuk.index')->with('error', 'Anda tidak memiliki hak akses untuk menghapus transaksi.');
        }

        $transaksi = BarangMasuk::findOrFail($id);

        DB::transaction(function () use ($transaksi) {
            $barang = Barang::find($transaksi->barang_id);
            if ($barang) {
                if ($barang->jumlah < $transaksi->jumlah) {
                    throw new \Exception('Transaksi tidak dapat dihapus karena stok saat ini (' . $barang->jumlah . ') lebih sedikit dari jumlah transaksi (' . $transaksi->jumlah . ').');
                }
                $barang->decrement('jumlah', $transaksi->jumlah);
            }
            $transaksi->delete();
        });

        return redirect()->route('transaksi-masuk.index')->with('success', 'Transaksi Barang Masuk berhasil dihapus dan stok telah disesuaikan.');
    }
}
