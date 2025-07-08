<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AntrianGuestController extends Controller
{
    /**
     * Tampilkan form ambil antrian (jika belum ambil), atau tampilkan nomor jika sudah.
     */
    public function form()
    {
        $globalToken = Antrian::where('nomor', 'RESET-TOKEN')->latest()->first()?->reset_token;
        $sessionToken = Session::get('reset_token');

        // Reset session jika token tidak cocok
        if ($globalToken && $sessionToken && $globalToken !== $sessionToken) {
            Session::forget(['nomor_antrian', 'reset_token']);
        }

        // Jika sudah ambil antrian, tampilkan view dengan nomor
        if (Session::has('nomor_antrian')) {
            $nomor = Session::get('nomor_antrian');

            // 👇 Tambahkan statistik tetap
            $total = Antrian::where('nomor', '!=', 'RESET-TOKEN')->count();
            $dipanggil = Antrian::where('status', 'dipanggil')->count();
            $nomorSekarang = Antrian::where('status', 'dipanggil')->latest()->first()?->nomor ?? 'Belum ada yang dipanggil';

            return view('antrian.show', compact('nomor', 'total', 'dipanggil', 'nomorSekarang'));
        }

        // 👇 Tambahkan statistik juga untuk yang belum ambil antrian
        $total = Antrian::where('nomor', '!=', 'RESET-TOKEN')->count();
        $dipanggil = Antrian::where('status', 'dipanggil')->count();
        $nomorSekarang = Antrian::where('status', 'dipanggil')->latest()->first()?->nomor ?? 'Belum ada yang dipanggil';

        return view('antrian.ambil', compact('total', 'dipanggil', 'nomorSekarang'));
    }

    /**
     * Ambil nomor antrian, simpan ke database dan session.
     */
    public function ambil(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        if (Session::has('nomor_antrian')) {
            return redirect('/')->with('info', 'Kamu sudah mengambil nomor antrian.');
        }

        // Ambil reset token global (dari entri RESET-TOKEN)
        $resetToken = Antrian::where('nomor', 'RESET-TOKEN')->latest()->first()?->reset_token ?? Str::uuid();

        $last = Antrian::where('nomor', '!=', 'RESET-TOKEN')->latest()->first();
        $lastNomor = $last ? intval(substr($last->nomor, 1)) : 0;
        $nextNomor = 'A' . str_pad($lastNomor + 1, 3, '0', STR_PAD_LEFT);

        $antrian = Antrian::create([
            'nama' => $request->nama,
            'nomor' => $nextNomor,
            'reset_token' => $resetToken
        ]);

        Session::put('nomor_antrian', $antrian->nomor);
        Session::put('reset_token', $resetToken);

        return redirect('/')->with('success', 'Nomor antrian berhasil diambil!');
    }
}
