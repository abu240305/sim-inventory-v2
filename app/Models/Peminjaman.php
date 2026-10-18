<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'peminjamans';

    protected $guarded = [];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'rencana_kembali' => 'date',
        'tanggal_kembali_aktual' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsOverdueAttribute()
    {
        if ($this->status === 'dipinjam' && $this->rencana_kembali) {
            return Carbon::today()->gt(Carbon::parse($this->rencana_kembali));
        }
        return false;
    }

    public function getEffectiveStatusAttribute()
    {
        if ($this->status === 'dipinjam' && $this->is_overdue) {
            return 'terlambat';
        }
        return $this->status;
    }

    protected static function getLogDescription(string $action, Model $model): string
    {
        $nomor = $model->nomor_surat ?? $model->id;
        return match ($action) {
            'created' => "Peminjaman Alat nomor '{$nomor}' telah dibuat",
            'updated' => "Peminjaman Alat nomor '{$nomor}' telah diperbarui",
            'deleted' => "Peminjaman Alat nomor '{$nomor}' telah dibatalkan / dihapus",
            default => "Peminjaman Alat '{$nomor}' - {$action}",
        };
    }
}
