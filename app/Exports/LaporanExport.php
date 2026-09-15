<?php

namespace App\Exports;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $jenis;
    protected $tanggal_mulai;
    protected $tanggal_akhir;

    public function __construct($jenis, $tanggal_mulai, $tanggal_akhir)
    {
        $this->jenis = $jenis;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_akhir = $tanggal_akhir;
    }

    public function collection()
    {
        if ($this->jenis == 'masuk') {
            return BarangMasuk::with(['barang', 'user'])
                ->whereBetween('tanggal', [$this->tanggal_mulai, $this->tanggal_akhir])
                ->orderBy('tanggal', 'desc')
                ->get();
        } elseif ($this->jenis == 'keluar') {
            return BarangKeluar::with(['barang', 'user'])
                ->whereBetween('tanggal', [$this->tanggal_mulai, $this->tanggal_akhir])
                ->orderBy('tanggal', 'desc')
                ->get();
        } else {
            return Barang::with('kategori')->orderBy('nama_barang')->get();
        }
    }

    public function headings(): array
    {
        if ($this->jenis == 'masuk') {
            return ['Tanggal', 'Kode Barang', 'Nama Barang', 'Jumlah Masuk', 'Sumber', 'Keterangan', 'Petugas'];
        } elseif ($this->jenis == 'keluar') {
            return ['Tanggal', 'Kode Barang', 'Nama Barang', 'Jumlah Keluar', 'Penerima', 'Bagian', 'Keterangan', 'Petugas'];
        } else {
            return ['Kode Barang', 'Nama Barang', 'Kategori', 'Jumlah Stok', 'Kondisi', 'Lokasi'];
        }
    }

    public function map($row): array
    {
        if ($this->jenis == 'masuk') {
            return [
                $row->tanggal,
                $row->barang->kode_barang ?? '-',
                $row->barang->nama_barang ?? '-',
                $row->jumlah,
                $row->sumber,
                $row->keterangan,
                $row->user->name ?? '-',
            ];
        } elseif ($this->jenis == 'keluar') {
            return [
                $row->tanggal,
                $row->barang->kode_barang ?? '-',
                $row->barang->nama_barang ?? '-',
                $row->jumlah,
                $row->penerima,
                $row->bagian,
                $row->keterangan,
                $row->user->name ?? '-',
            ];
        } else {
            return [
                $row->kode_barang,
                $row->nama_barang,
                $row->kategori->nama_kategori ?? '-',
                $row->jumlah,
                $row->kondisi,
                $row->lokasi,
            ];
        }
    }
}
