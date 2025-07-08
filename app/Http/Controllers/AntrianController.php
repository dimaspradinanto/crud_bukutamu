<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AntrianController extends Controller
{
    // Menampilkan daftar antrian (untuk admin/staff)
    public function index()
    {
        $antrians = Antrian::where('nomor', '!=', 'RESET-TOKEN')->orderBy('nomor')->get();

        $nomorSekarang = Antrian::where('status', 'dipanggil')->first()?->nomor ?? 'Belum ada yang dipanggil';

        $total = $antrians->count();
        $dipanggil = $antrians->where('status', 'dipanggil')->count();

        return view('antrian.index', compact('antrians', 'nomorSekarang', 'total', 'dipanggil'));
    }

    // Menampilkan dashboard (misalnya statistik antrian atau ringkasan)
    public function dashboard()
    {
        $data = \App\Models\Antrian::where('nomor', '!=', 'RESET-TOKEN')->orderBy('nomor')->get();
        $total = $data->count();
        $dipanggil = $data->where('status', 'dipanggil')->count();
        $nomorSekarang = $data->where('status', 'dipanggil')->last()?->nomor ?? 'Belum ada';

        return view('antrian.dashboard', compact('data', 'total', 'dipanggil', 'nomorSekarang'));
    }

    // Reset seluruh antrian dan mengosongkan nomor saat ini

    public function reset()
    {
        Antrian::truncate(); // atau Antrian::where('nomor', '!=', 'RESET-TOKEN')->delete();

        Antrian::where('nomor', 'RESET-TOKEN')->delete(); // hapus token lama

        Antrian::create([
            'nomor' => 'RESET-TOKEN',
            'reset_token' => Str::uuid(),
            'nama' => 'SYSTEM',
        ]);
        return redirect()->route('antrian.index')
            ->with('success', 'Antrian berhasil direset.');
    }

    // Memanggil antrian berdasarkan ID dan set ke session
    public function panggil($id)
    {
        // Set semua entri jadi tidak aktif
        Antrian::where('is_current', true)->update(['is_current' => false]);

        // Ambil antrian
        $antrian = Antrian::findOrFail($id);

        // Simpan status 'dipanggil' hanya jika belum pernah dipanggil
        if ($antrian->status !== 'dipanggil') {
            $antrian->status = 'dipanggil';
        }

        $antrian->is_current = true;
        $antrian->save();

        session(['nomor_sekarang' => $antrian->nomor]);

        return redirect()->route('antrian.index')
            ->with('success', 'Memanggil nomor ' . $antrian->nomor)
            ->with('tts', $antrian->nomor); // khusus untuk suara
    }
}
