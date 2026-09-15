<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Kategori extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['nama_kategori'];


    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
