<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\PermintaanDetail;
use App\Models\Permintaan;
use Carbon\Carbon;


class GudangController extends Controller
{
    // Reusable code untuk update status bahan
    private function updateBahanStatus(BahanBaku $bahan, int $jumlahBaru = null){
        if($jumlahBaru != null){
            $bahan->jumlah = $jumlahBaru;
        }

        $today = Carbon::today();
        $expiredDate = Carbon::parse($bahan->tanggal_kadaluarsa);
    
        if ($bahan->jumlah == 0) {
            $bahan->status = 'habis';
        } elseif ($today->greaterThanOrEqualTo($expiredDate)) {
            $bahan->status = 'kadaluarsa';
        } elseif ($today->lt($expiredDate) && $today->diffInDays($expiredDate) <= 3) {
            $bahan->status = 'segera kadaluarsa';
        } else {
            $bahan->status = 'tersedia';
        }
        $bahan->save();
    }


    // Halaman dashboard
    public function index(){
        $bahan = BahanBaku::all();
        $today = Carbon::today();

        foreach ($bahan as $item) {
            $this->updateBahanStatus($item);
        }
        return view('gudang.index', compact('bahan'));
    }

    // Halaman tambah bahan baku
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

    // Update bahan baku secara langsung (buat update jumlah stok)
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer',
        ]);

        $bahan = BahanBaku::findOrFail($id);

        if ($request->jumlah < 0) {
            return redirect()->back()->with('error', 'Stok tidak boleh kurang dari 0');
        }

        $bahan->jumlah = $request->jumlah;

        // Update status otomatis
        $this->updateBahanStatus($bahan, $request->jumlah);

        return redirect()->back()->with('success', 'Stok berhasil diperbarui');
    }

    // Hapus bahan baku
    public function destroy($id)
    {
        $bahan = BahanBaku::findOrFail($id);

        // Cek apakah bahan ada di permintaan_detail
        if (PermintaanDetail::where('bahan_id', $id)->exists()) {
            return redirect()->back()->with('error', 'Bahan ini masih digunakan di permintaan, tidak bisa dihapus.');
        }

        // Hanya bahan kadaluarsa yang bisa dihapus
        $today = Carbon::today();
        $expiredDate = Carbon::parse($bahan->tanggal_kadaluarsa);

        if ($today->lt($expiredDate)) {
            return redirect()->back()->with('error', 'Bahan ini belum kadaluarsa, tidak bisa dihapus.');
        }
        $bahan->delete();
        return redirect()->route('gudang.index')->with('success', 'Bahan baku berhasil dihapus');
    }

    // Halaman Permintaan
    public function permintaan(){
        $permintaan = Permintaan::all();
        return view('gudang.permintaan', compact('permintaan'));
    }

    // Acc Permintaan
    public function accPermintaan($id){
        $permintaan = Permintaan::findOrFail($id);
        
        foreach($permintaan->detail as $detail){
            $bahan = BahanBaku::find($detail->bahan_id);
            
            if($bahan->jumlah < $detail->jumlah_diminta){
                return redirect()->back()->with('error', 'Stok bahan tidak cukup!');
            }

            $bahan->jumlah -= $detail->jumlah_diminta;
            // Update status stok
            $this->updateBahanStatus($bahan, $bahan->jumlah);
        }
        $permintaan->status = 'disetujui';
        $permintaan->save();

        return redirect()->back()->with('success', 'Permintaan berhasil disetujui!');
    }

    public function tolakPermintaan($id){
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->status = 'ditolak';
        $permintaan->save();
        return redirect()->back()->with('success', 'Permintaan berhasil ditolak!');
    }
    
}
