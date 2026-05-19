<?php
namespace App\Controllers\Backend;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Komite;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class KomiteController extends Controller
{
    public function index() {
        $data = Komite::orderBy("urutan")->get();
        return view("backend.komite.index", compact("data"));
    }
    public function create() {
        return view("backend.komite.create");
    }
    public function store(Request $request) {
        $input = $request->except("_token");
        if ($request->hasFile("foto")) {
            $input["foto"] = $request->file("foto")->store("uploads", "public");
        }
        Komite::create($input);
        Cache::forget("komite_all");
        redirect('/admin/komite')->with("success", "Data komite berhasil ditambahkan");
    }
    public function edit($id) {
        $data = Komite::findOrFail($id);
        return view("backend.komite.edit", compact("data"));
    }
    public function update(Request $request, $id) {
        $model = Komite::findOrFail($id);
        $input = $request->except("_token", "_method");
        if ($request->hasFile("foto")) {
            if ($model->foto) Storage::disk("public")->delete($model->foto);
            $input["foto"] = $request->file("foto")->store("uploads", "public");
        }
        $model->update($input);
        Cache::forget("komite_all");
        redirect('/admin/komite')->with("success", "Data komite berhasil diubah");
    }
    public function destroy($id) {
        $model = Komite::findOrFail($id);
        if ($model->foto) Storage::disk("public")->delete($model->foto);
        $model->delete();
        Cache::forget("komite_all");
        redirect('/admin/komite')->with("success", "Data komite berhasil dihapus");
    }
}


