<?php
namespace App\Controllers\Backend;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Siswa;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SiswaController extends Controller
{
    public function index() {
        $data = \App\Models\Siswa::latest()->get();
        return view("backend." . strtolower(preg_replace("/(?<!^)[A-Z]/", "_$0", "Siswa")) . ".index", compact("data"));
    }
    public function create() {
        return view("backend." . strtolower(preg_replace("/(?<!^)[A-Z]/", "_$0", "Siswa")) . ".create");
    }
    public function store(Request $request) {
        $input = $request->except("_token");
        $input["slug"] = Str::slug($request->nama ?? $request->nama ?? $request->judul ?? time());
        if ($request->hasFile("foto")) { $input["foto"] = $request->file("foto")->store("uploads", "public"); }
        \App\Models\Siswa::create($input);
        Cache::forget("siswa_all");
        redirect('/admin/siswa')->with("success", "Data berhasil ditambahkan");
    }
    public function edit($id) {
        $data = \App\Models\Siswa::findOrFail($id);
        return view("backend." . strtolower(preg_replace("/(?<!^)[A-Z]/", "_$0", "Siswa")) . ".edit", compact("data"));
    }
    public function update(Request $request, $id) {
        $model = \App\Models\Siswa::findOrFail($id);
        $input = $request->except("_token", "_method");
        $input["slug"] = Str::slug($request->nama ?? $request->nama ?? $request->judul ?? time());
        if ($request->hasFile("foto")) { if ($model->foto) Storage::disk("public")->delete($model->foto); $input["foto"] = $request->file("foto")->store("uploads", "public"); }
        $model->update($input);
        Cache::forget("siswa_all");
        redirect('/admin/siswa')->with("success", "Data berhasil diubah");
    }
    public function destroy($id) {
        $model = \App\Models\Siswa::findOrFail($id);
        if ($model->foto) Storage::disk("public")->delete($model->foto);
        $model->delete();
        Cache::forget("siswa_all");
        redirect('/admin/siswa')->with("success", "Data berhasil dihapus");
    }
}


