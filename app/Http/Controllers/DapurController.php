<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\PermintaanDetail;
use App\Models\Permintaan;
use Carbon\Carbon;

class DapurController extends Controller
{
    // Halaman Dashboard
    public function index() {
        $permintaan = Permintaan::with('detail', 'pemohon')->where('pemohon_id', session('user_id'))
                        ->orderBy('tgl_masak', 'desc')
                        ->get();

        return view('dapur.index', compact('permintaan'));
    }

    // Halaman Buat Permintaan
    public function create(){
        $bahan = BahanBaku::where('jumlah', '>', 0)
                            ->where('status','!=','kadaluarsa')
                            ->get();
        return view('dapur.create', compact('bahan'));
    }

    // Simpan Permintaan
    public function store(Request $request) {
        
        // Validasi input
        $request->validate([
            'tgl_masak' => 'required|date|after_or_equal:today',
            'menu_makan' => 'required|string|max:255',
            'jumlah_porsi' => 'required|integer|min:1',
            'bahan_id.*' => 'required|integer|exists:bahan_baku,id',
            'jumlah_bahan.*' => 'required|integer|min:1',
        ]);

        // Simpan permintaan utama
        $permintaan = Permintaan::create([
            'pemohon_id' => session('user_id'),
            'tgl_masak' => $request->tgl_masak,
            'menu_makan' => $request->menu_makan,
            'jumlah_porsi' => $request->jumlah_porsi,
            'status' => 'menunggu',
            'alasan_penolakan' => null,
        ]);

        // Simpan detail permintaan
        foreach ($request->bahan_id as $index => $bahanId) {
            PermintaanDetail::create([
                'permintaan_id' => $permintaan->id,
                'bahan_id' => $bahanId,
                'jumlah_diminta' => $request->jumlah_bahan[$index] ?? 0,
            ]);
        }

        return redirect()->route('dapur.index')->with('success', 'Permintaan berhasil dibuat!');
    }
    
}
