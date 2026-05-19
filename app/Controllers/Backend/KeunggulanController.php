<?php

namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Models\KeunggulanSekolah;
use App\Core\Request;
use App\Core\Auth;

class KeunggulanController extends Controller
{
    public function index()
    {
        $data = KeunggulanSekolah::orderBy('urutan')->get();
        return view('backend.keunggulan.index', compact('data'));
    }

    public function create()
    {
        return view('backend.keunggulan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon'      => 'required|string|max:100',
            'urutan'    => 'nullable|integer',
        ]);

        KeunggulanSekolah::create([
            'icon'      => $request->icon,
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'urutan'    => $request->urutan ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        redirect('/admin/keunggulan')
            ->with('success', 'Data keunggulan berhasil ditambahkan!');
    }

    public function edit(KeunggulanSekolah $keunggulan)
    {
        return view('backend.keunggulan.edit', compact('keunggulan'));
    }

    public function update(Request $request, KeunggulanSekolah $keunggulan)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon'      => 'required|string|max:100',
            'urutan'    => 'nullable|integer',
        ]);

        $keunggulan->update([
            'icon'      => $request->icon,
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'urutan'    => $request->urutan ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        redirect('/admin/keunggulan')
            ->with('success', 'Data keunggulan berhasil diperbarui!');
    }

    public function destroy(KeunggulanSekolah $keunggulan)
    {
        $keunggulan->delete();
        redirect('/admin/keunggulan')
            ->with('success', 'Data keunggulan berhasil dihapus!');
    }
}


