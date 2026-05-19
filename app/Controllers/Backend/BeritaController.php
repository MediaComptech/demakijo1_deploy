<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class BeritaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    public function index()
    {
        $data = Berita::with('kategori')->latest()->get();
        return view('backend.berita.index', compact('data'));
    }

    public function create()
    {
        $kategori = KategoriBerita::all();
        return view('backend.berita.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $input          = $request->except('_token');
        $input['slug']  = Str::slug($request->judul);
        $input['user_id'] = auth()->id();
        if (!isset($input['is_published'])) $input['is_published'] = false;

        if ($request->hasFile('gambar')) {
            $input['gambar'] = $request->file('gambar')->store('uploads', 'public');
        }

        Berita::create($input);
        Cache::forget('berita_page_1');
        redirect('/admin/berita')->with('success', 'Berita berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data     = Berita::findOrFail($id);
        $kategori = KategoriBerita::all();
        return view('backend.berita.edit', compact('data', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $model  = Berita::findOrFail($id);
        $input  = $request->except('_token', '_method');
        $input['slug'] = Str::slug($request->judul);
        if (!isset($input['is_published'])) $input['is_published'] = false;

        if ($request->hasFile('gambar')) {
            if ($model->gambar) Storage::disk('public')->delete($model->gambar);
            $input['gambar'] = $request->file('gambar')->store('uploads', 'public');
        }

        $model->update($input);
        Cache::forget('berita_page_1');
        redirect('/admin/berita')->with('success', 'Berita berhasil diubah');
    }

    public function destroy($id)
    {
        $model = Berita::findOrFail($id);
        if ($model->gambar) Storage::disk('public')->delete($model->gambar);
        $model->delete();
        Cache::forget('berita_page_1');
        redirect('/admin/berita')->with('success', 'Berita berhasil dihapus');
    }
}
