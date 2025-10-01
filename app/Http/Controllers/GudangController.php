<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use Carbon\Carbon;
use PhpParser\Builder\Function_;

class GudangController extends Controller
{
    public function index(){
        $bahan = BahanBaku::all();
        $today = Carbon::today();

        foreach ($bahan as $item) {
            if ($item->jumlah == 0) {
                $item->status = 'habis';
            } elseif ($today >= Carbon::parse($item->tanggal_kadaluarsa)) {
                $item->status = 'kadaluarsa';
            } elseif (Carbon::parse($item->tanggal_kadaluarsa)->diffInDays($today) <= 3) {
                $item->status = 'segera kadaluarsa';
            } else {
                $item->status = 'tersedia';
            }

            // $item->save();
        }
        return view('gudang.index', compact('bahan'));
    }

    public function create()
    {
        return view('gudang.create');
    }

    // Tambah Bahan Baku
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'tanggal_masuk' => 'required|date',
            'tanggal_kadaluarsa' => 'required|date|after_or_equal:tanggal_masuk',
        ]);

        BahanBaku::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'status' => 'tersedia',
            'created_at' => now(),
        ]);

        return redirect()->route('gudang.index')->with('success', 'Bahan baku berhasil ditambahkan');
    }

    
}
