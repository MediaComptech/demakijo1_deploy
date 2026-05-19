<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    public function index()
    {
        $data = Album::latest()->get();
        return view('backend.album.index', compact('data'));
    }

    public function create()
    {
        return view('backend.album.create');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        if ($request->has('judul')) $data['slug'] = Str::slug($request->judul);
        if ($request->has('nama'))  $data['slug'] = Str::slug($request->nama);
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('uploads', 'public');
        }
        Album::create($data);
        redirect('/admin/album')->with('success', 'Album berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = Album::findOrFail($id);
        return view('backend.album.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $model = Album::findOrFail($id);
        $data  = $request->except('_token', '_method');
        if ($request->has('judul')) $data['slug'] = Str::slug($request->judul);
        if ($request->has('nama'))  $data['slug'] = Str::slug($request->nama);
        if ($request->hasFile('cover')) {
            if ($model->cover) Storage::disk('public')->delete($model->cover);
            $data['cover'] = $request->file('cover')->store('uploads', 'public');
        }
        $model->update($data);
        redirect('/admin/album')->with('success', 'Album berhasil diubah');
    }

    public function destroy($id)
    {
        $model = Album::findOrFail($id);
        if ($model->cover) Storage::disk('public')->delete($model->cover);
        $model->delete();
        redirect('/admin/album')->with('success', 'Album berhasil dihapus');
    }
}
