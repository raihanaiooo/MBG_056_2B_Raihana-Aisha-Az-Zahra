<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\PermintaanDetail;
use App\Models\Permintaan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class GudangController extends Controller
{
    // Method untuk ambil status bahan
    private function getBahanStatus(BahanBaku $bahan)
    {
        $today = Carbon::today();
        $expiredDate = Carbon::parse($bahan->tanggal_kadaluarsa)->startOfDay();

        if ($bahan->jumlah == 0) {
            return 'habis';
        } elseif ($today->diffInDays($expiredDate, false) < 0) {
            return 'kadaluarsa';
        } elseif ($today->diffInDays($expiredDate, false) <= 3) {
            return 'segera_kadaluarsa';
        } else {
            return 'tersedia';
        }
    }

    // Method untuk update jumlah dan status
    private function updateBahanStatus(BahanBaku $bahan, int $jumlahBaru = null)
    {
        if ($jumlahBaru !== null) {
            $bahan->jumlah = $jumlahBaru;
        }

        $bahan->status = $this->getBahanStatus($bahan);
        $bahan->save();
    }

    // Halaman dashboard
    public function index()
    {
        $bahan = BahanBaku::all();

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
        $this->updateBahanStatus($bahan);
   
        // Cek apakah bahan kadaluarsa
        if ($bahan->status != 'kadaluarsa') {
            return redirect()->back()->with('error', 'Bahan ini belum kadaluarsa, tidak bisa dihapus.');
        }
        // Cek apakah bahan ada di permintaan yang masih aktif (menunggu atau disetujui)
        $usedInActive = PermintaanDetail::where('bahan_id', $id)
            ->whereHas('permintaan', function($query) {
                $query->whereIn('status', ['menunggu', 'disetujui']);
            })->exists();

        if ($usedInActive) {
            return redirect()->back()->with('error', 'Bahan ini masih digunakan di permintaan aktif, tidak bisa dihapus.');
        }

        $bahan->delete();

        return redirect()->route('gudang.index')->with('success', 'Bahan baku berhasil dihapus');
    }

    // Halaman Permintaan
    public function permintaan(){
        $permintaan = DB::table('permintaan as p')
            ->join('user as u', 'p.pemohon_id', '=', 'u.id')
            ->join('permintaan_detail as d', 'p.id', '=', 'd.permintaan_id')
            ->join('bahan_baku as b', 'd.bahan_id', '=', 'b.id')
            ->select(
                'p.id',
                'u.name as pemohon', 
                'p.tgl_masak',
                'p.menu_makan',
                'p.jumlah_porsi',
                'p.status',
                DB::raw('GROUP_CONCAT(CONCAT(b.nama, " (", d.jumlah_diminta, ")") SEPARATOR ", ") as bahan_diminta')
            )
            ->groupBy('p.id', 'u.name', 'p.tgl_masak', 'p.menu_makan', 'p.jumlah_porsi', 'p.status')
            ->get();

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

    // Menolak Permintaan
    public function tolakPermintaan(Request $request, $id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->status = 'ditolak';
        $permintaan->alasan_penolakan = $request->alasan;
        $permintaan->save();

        return redirect()->back()->with('success', 'Permintaan ditolak dengan alasan: ' . $request->alasan);
    }

}
