<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Pengumuman;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    public function index()
    {
        $data = Pengumuman::latest()->get();
        return view('backend.pengumuman.index', compact('data'));
    }

    public function create()
    {
        return view('backend.pengumuman.create');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $data['slug']    = unique_slug($request->judul ?? $request->nama ?? '', \App\Models\Pengumuman::class);
        $data['user_id'] = auth()->id() ?: 1; // fallback ke user pertama jika tidak ada di session
        if ($request->hasFile('file_lampiran')) {
            $data['file_lampiran'] = $request->file('file_lampiran')->store('dokumen', 'public');
        }
        Pengumuman::create($data);
        redirect('/admin/pengumuman')->with('success', 'Pengumuman berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = Pengumuman::findOrFail($id);
        return view('backend.pengumuman.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $model = Pengumuman::findOrFail($id);
        $data  = $request->except('_token', '_method');
        $data['slug'] = unique_slug($request->judul ?? $request->nama ?? '', \App\Models\Pengumuman::class, 'slug', $id);
        if ($request->hasFile('file_lampiran')) {
            if ($model->file_lampiran) Storage::disk('public')->delete($model->file_lampiran);
            $data['file_lampiran'] = $request->file('file_lampiran')->store('dokumen', 'public');
        }
        $model->update($data);
        redirect('/admin/pengumuman')->with('success', 'Pengumuman berhasil diubah');
    }

    public function destroy($id)
    {
        $model = Pengumuman::findOrFail($id);
        if ($model->file_lampiran) Storage::disk('public')->delete($model->file_lampiran);
        $model->delete();
        redirect('/admin/pengumuman')->with('success', 'Pengumuman berhasil dihapus');
    }
}
