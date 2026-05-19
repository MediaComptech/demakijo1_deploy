<?php
namespace App\Controllers\Backend;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\KategoriBerita;
use Illuminate\Support\Str;

class KategoriBeritaController extends Controller
{
    public function index() {
        $data = KategoriBerita::withCount("beritas")->latest()->get();
        return view("backend.kategori_berita.index", compact("data"));
    }
    public function create() {
        return view("backend.kategori_berita.create");
    }
    public function store(Request $request) {
        $request->validate(["nama" => "required"]);
        KategoriBerita::create([
            "nama" => $request->nama,
            "slug" => Str::slug($request->nama),
            "deskripsi" => $request->deskripsi,
        ]);
        redirect('/admin/kategori-berita')->with("success", "Kategori berhasil ditambahkan!");
    }
    public function edit($id) {
        $data = KategoriBerita::findOrFail($id);
        return view("backend.kategori_berita.edit", compact("data"));
    }
    public function update(Request $request, $id) {
        $kategori = KategoriBerita::findOrFail($id);
        $request->validate(["nama" => "required"]);
        $kategori->update([
            "nama" => $request->nama,
            "slug" => Str::slug($request->nama),
            "deskripsi" => $request->deskripsi,
        ]);
        redirect('/admin/kategori-berita')->with("success", "Kategori berhasil diubah!");
    }
    public function destroy($id) {
        KategoriBerita::findOrFail($id)->delete();
        redirect('/admin/kategori-berita')->with("success", "Kategori berhasil dihapus!");
    }
}


