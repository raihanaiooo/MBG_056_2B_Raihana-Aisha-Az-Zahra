<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permintaan extends Model
{
    use HasFactory;

    protected $table = 'permintaan';

    protected $fillable = [
        'pemohon_id',
        'tgl_masak',
        'menu_makan',
        'jumlah_porsi',
        'status'
    ];

    public $timestamps = false;

    public function detail(){
        return $this->hasMany(PermintaanDetail::class,'permintaan_id');
    }

    public function pemohon(){
        return $this->belongsTo(User::class, 'pemohon_id');
    }
}
