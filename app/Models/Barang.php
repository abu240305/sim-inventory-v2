<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Barang extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded()
        ->setDescriptionForEvent(fn(string $eventName) => "Barang telah di {$eventName}");
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
