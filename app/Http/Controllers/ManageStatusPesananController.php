<?php

namespace App\Http\Controllers;

use App\Models\StatusPesanan;
use Illuminate\Http\Request;

class ManageStatusPesananController extends Controller
{
    // Tampilkan semua status
    public function index()
    {
        $status = StatusPesanan::orderBy('urutan_tampilan')->get();

        return view('manage-status-pesanan.index', compact('status'));
    }

    public function input()
    {
        return view('manage-status-pesanan.create');
    }

    // SIMPAN STATUS BARU (route: /manageStatus-simpan)
    public function simpan(Request $request)
    {
        $request->validate([
            'id_status_pesanan' => 'required|unique:status_pesanan,id_status_pesanan',
            'status'            => 'required',
            'deskripsi'         => 'nullable',
            'urutan_tampilan'   => 'required|integer',
        ]);

        StatusPesanan::create([
            'id_status_pesanan' => $request->id_status_pesanan,
            'status'            => $request->status,
            'deskripsi'         => $request->deskripsi,
            'urutan_tampilan'   => $request->urutan_tampilan,
        ]);

        return redirect('/manageStatus')->with('success', 'Status pesanan berhasil ditambahkan.');
    }

    // FORM EDIT (route: /manageStatus-edit/{id_status_pesanan})
    public function edit($id_status_pesanan)
    {
        $status = StatusPesanan::findOrFail($id_status_pesanan);

        return view('manage-status-pesanan.edit', compact('status'));
    }

    // UPDATE DATA (route: /manageStatus-update/{id_status_pesanan})
    public function update(Request $request, $id_status_pesanan)
    {
        $status = StatusPesanan::findOrFail($id_status_pesanan);

        $request->validate([
            'status'          => 'required',
            'deskripsi'       => 'nullable',
            'urutan_tampilan' => 'required|integer',
        ]);

        $status->update([
            'status'          => $request->status,
            'deskripsi'       => $request->deskripsi,
            'urutan_tampilan' => $request->urutan_tampilan,
        ]);

        return redirect('/manageStatus')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // HAPUS DATA (route: /manageStatus-hapus/{id_status_pesanan})
    public function delete($id_status_pesanan)
    {
        $status = StatusPesanan::findOrFail($id_status_pesanan);
        $status->delete();

        return redirect('/manageStatus')->with('success', 'Status pesanan berhasil dihapus.');
    }
}
