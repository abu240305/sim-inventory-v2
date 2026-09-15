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
}
