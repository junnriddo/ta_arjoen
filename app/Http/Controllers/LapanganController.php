<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;

class LapanganController extends Controller
{
    /**
     * Menampilkan daftar lapangan (publik)
     */
    public function index()
    {
        $lapangans = Lapangan::withCount('bookings')->get();

        return view('lapangan.index', compact('lapangans'));
    }

    /**
     * Menampilkan daftar lapangan untuk admin (CRUD)
     */
    public function adminIndex()
    {
        $lapangans = Lapangan::withCount('bookings')->paginate(5);

        return view('lapangan.admin', compact('lapangans'));
    }

    /**
     * Tampilkan form tambah lapangan
     */
    public function create()
    {
        return view('lapangan.create');
    }

    /**
     * Simpan lapangan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lapangan'  => 'required|string|max:255',
            'jenis_lapangan' => 'required|string|max:255',
            'harga_pagi'     => 'required|integer|min:0',
            'harga_malam'    => 'required|integer|min:0',
        ], [
            'nama_lapangan.required'  => 'Nama lapangan wajib diisi.',
            'jenis_lapangan.required' => 'Jenis lapangan wajib diisi.',
            'harga_pagi.required'     => 'Harga pagi wajib diisi.',
            'harga_malam.required'    => 'Harga malam wajib diisi.',
        ]);

        Lapangan::create($request->only('nama_lapangan', 'jenis_lapangan', 'harga_pagi', 'harga_malam'));

        return redirect('/admin/lapangan')->with('success', 'Lapangan berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit lapangan
     */
    public function edit(Lapangan $lapangan)
    {
        return view('lapangan.edit', compact('lapangan'));
    }

    /**
     * Update data lapangan
     */
    public function update(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'nama_lapangan'  => 'required|string|max:255',
            'jenis_lapangan' => 'required|string|max:255',
            'harga_pagi'     => 'required|integer|min:0',
            'harga_malam'    => 'required|integer|min:0',
        ]);

        $lapangan->update($request->only('nama_lapangan', 'jenis_lapangan', 'harga_pagi', 'harga_malam'));

        return redirect('/admin/lapangan')->with('success', 'Lapangan berhasil diperbarui!');
    }

    /**
     * Hapus lapangan
     */
    public function destroy(Lapangan $lapangan)
    {
        $lapangan->delete();

        return redirect('/admin/lapangan')->with('success', 'Lapangan berhasil dihapus!');
    }
}
