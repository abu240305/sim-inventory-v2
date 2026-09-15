<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class BarangMasuk extends Model
{
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded()
        ->setDescriptionForEvent(fn(string $eventName) => "Barang Masuk telah di {$eventName}");
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
