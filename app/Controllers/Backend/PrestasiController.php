<?php
namespace App\Controllers\Backend;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Prestasi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class PrestasiController extends Controller
{
    public function index() {
        $data = \App\Models\Prestasi::latest()->get();
        return view("backend." . strtolower(preg_replace("/(?<!^)[A-Z]/", "_$0", "Prestasi")) . ".index", compact("data"));
    }
    public function create() {
        return view("backend." . strtolower(preg_replace("/(?<!^)[A-Z]/", "_$0", "Prestasi")) . ".create");
    }
    public function store(Request $request) {
        $input = $request->except("_token");
        $input["slug"] = Str::slug($request->judul ?? $request->nama ?? $request->judul ?? time());
        if ($request->hasFile("foto")) { $input["foto"] = $request->file("foto")->store("uploads", "public"); }
        \App\Models\Prestasi::create($input);
        Cache::forget("prestasi_all");
        redirect('/admin/prestasi')->with("success", "Data berhasil ditambahkan");
    }
    public function edit($id) {
        $data = \App\Models\Prestasi::findOrFail($id);
        return view("backend." . strtolower(preg_replace("/(?<!^)[A-Z]/", "_$0", "Prestasi")) . ".edit", compact("data"));
    }
    public function update(Request $request, $id) {
        $model = \App\Models\Prestasi::findOrFail($id);
        $input = $request->except("_token", "_method");
        $input["slug"] = Str::slug($request->judul ?? $request->nama ?? $request->judul ?? time());
        if ($request->hasFile("foto")) { if ($model->foto) Storage::disk("public")->delete($model->foto); $input["foto"] = $request->file("foto")->store("uploads", "public"); }
        $model->update($input);
        Cache::forget("prestasi_all");
        redirect('/admin/prestasi')->with("success", "Data berhasil diubah");
    }
    public function destroy($id) {
        $model = \App\Models\Prestasi::findOrFail($id);
        if ($model->foto) Storage::disk("public")->delete($model->foto);
        $model->delete();
        Cache::forget("prestasi_all");
        redirect('/admin/prestasi')->with("success", "Data berhasil dihapus");
    }
}


