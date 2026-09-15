<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Kategori extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['nama_kategori'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->setDescriptionForEvent(fn(string $eventName) => "Kategori telah di {$eventName}");
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
