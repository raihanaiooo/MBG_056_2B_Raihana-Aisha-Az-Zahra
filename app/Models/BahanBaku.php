<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';

    protected $fillable = [
        'nama',
        'kategori',
        'jumlah',
        'satuan',
        'tanggal_masuk',
        'tanggal_kadaluarsa',
        'status',
        'created_at'
    ];

    public function permintaanDetail(){
        return $this->hasMany(PermintaanDetail::class, 'bahan_id');
    }

    public $timestamps = false; // karena created_at manual
}
