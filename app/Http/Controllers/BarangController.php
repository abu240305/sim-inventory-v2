<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $selectedJenis = $request->query('jenis');

        $query = Barang::with('kategori');

        if ($selectedJenis && in_array($selectedJenis, ['alat', 'bahan'])) {
            $query->where('jenis', $selectedJenis);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('merk_tipe', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $barangs = $query->latest()->get();

        $totalSemua = Barang::count();
        $totalAlat = Barang::where('jenis', 'alat')->count();
        $totalBahan = Barang::where('jenis', 'bahan')->count();

        return view('barang.index', compact('barangs', 'selectedJenis', 'totalSemua', 'totalAlat', 'totalBahan'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:255|unique:barangs',
            'nama_barang' => 'required|string|max:255',
            'jenis' => 'required|in:alat,bahan',
            'merk_tipe' => 'nullable|string|max:255',
            'satuan' => 'required|string|max:50',
            'kategori_id' => 'required|exists:kategoris,id',
            'kondisi' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
        ]);

        Barang::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'jenis' => $request->jenis,
            'merk_tipe' => $request->merk_tipe,
            'satuan' => $request->satuan,
            'kategori_id' => $request->kategori_id,
            'kondisi' => $request->kondisi,
            'lokasi' => $request->lokasi,
            'jumlah' => 0 // default 0, updated via transactions
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:255|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'jenis' => 'required|in:alat,bahan',
            'merk_tipe' => 'nullable|string|max:255',
            'satuan' => 'required|string|max:50',
            'kategori_id' => 'required|exists:kategoris,id',
            'kondisi' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
        ]);

        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'jenis' => $request->jenis,
            'merk_tipe' => $request->merk_tipe,
            'satuan' => $request->satuan,
            'kategori_id' => $request->kategori_id,
            'kondisi' => $request->kondisi,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate');
    }

    public function destroy(Barang $barang)
    {
        $user = auth()->user();
        if (!$user->role || !in_array($user->role->slug, ['super-admin', 'admin'])) {
            return redirect()->route('barang.index')->with('error', 'Anda tidak memiliki akses untuk menghapus barang.');
        }

        $barang->delete(); // Soft delete
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
