<?php
namespace App\Controllers\Backend;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Alumni;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class AlumniController extends Controller
{
    public function index() {
        $data = Alumni::latest()->get();
        return view("backend.alumni.index", compact("data"));
    }
    public function create() {
        return view("backend.alumni.create");
    }
    public function store(Request $request) {
        $input = $request->except("_token");
        $input["is_verified"] = $request->input("is_verified", 0);
        if ($request->hasFile("foto")) {
            $input["foto"] = $request->file("foto")->store("uploads", "public");
        }
        Alumni::create($input);
        Cache::forget("alumni_verified");
        redirect('/admin/alumni')->with("success", "Alumni berhasil ditambahkan");
    }
    public function edit($id) {
        $data = Alumni::findOrFail($id);
        return view("backend.alumni.edit", compact("data"));
    }
    public function update(Request $request, $id) {
        $model = Alumni::findOrFail($id);
        $input = $request->except("_token", "_method");
        $input["is_verified"] = $request->input("is_verified", 0);
        if ($request->hasFile("foto")) {
            if ($model->foto) Storage::disk("public")->delete($model->foto);
            $input["foto"] = $request->file("foto")->store("uploads", "public");
        }
        $model->update($input);
        Cache::forget("alumni_verified");
        redirect('/admin/alumni')->with("success", "Alumni berhasil diubah");
    }
    public function destroy($id) {
        $model = Alumni::findOrFail($id);
        if ($model->foto) Storage::disk("public")->delete($model->foto);
        $model->delete();
        Cache::forget("alumni_verified");
        redirect('/admin/alumni')->with("success", "Alumni berhasil dihapus");
    }
}


