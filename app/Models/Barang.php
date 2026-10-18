<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Barang extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    protected $guarded = [];


    public function scopeAlat($query)
    {
        return $query->where('jenis', 'alat');
    }

    public function scopeBahan($query)
    {
        return $query->where('jenis', 'bahan');
    }

    public function getJenisLabelAttribute()
    {
        return $this->jenis === 'alat' ? 'Alat / Instrumen' : 'Bahan / Material';
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function barangKeluars()
    {
        return $this->hasMany(BarangKeluar::class);
    }
}
