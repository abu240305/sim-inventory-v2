<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return view('transaksi_keluar.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'penerima' => 'required|string|max:255',
            'bagian' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($request->jumlah > $barang->jumlah) {
            return back()->withInput()->withErrors(['jumlah' => 'Stok tidak mencukupi. Sisa stok: ' . $barang->jumlah]);
        }

        DB::transaction(function () use ($request, $barang) {
            BarangKeluar::create([
                'barang_id' => $request->barang_id,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'penerima' => $request->penerima,
                'bagian' => $request->bagian,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id()
            ]);

            $barang->decrement('jumlah', $request->jumlah);
        });

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi Barang Keluar berhasil disimpan');
    }
}
