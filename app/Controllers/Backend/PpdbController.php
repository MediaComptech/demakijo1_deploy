<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Ppdb;
use Illuminate\Support\Facades\Storage;

class PpdbController extends Controller
{
    public function index()
    {
        $data = Ppdb::latest()->get();
        return view('backend.ppdb.index', compact('data'));
    }

    public function create()
    {
        return view('backend.ppdb.create');
    }

    public function store(Request $request)
    {
        $input = $request->except('_token');
        $lastId = Ppdb::max('id') ?? 0;
        $input['no_pendaftaran'] = $input['no_pendaftaran'] ?? ('PPDB-' . date('Y') . '-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT));
        foreach (['berkas_kk', 'berkas_akta', 'berkas_pasfoto'] as $f) {
            if ($request->hasFile($f)) {
                $input[$f] = $request->file($f)->store('ppdb/' . date('Y'), 'public');
            }
        }
        Ppdb::create($input);
        redirect('/admin/ppdb')->with('success', 'Data pendaftar berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = Ppdb::findOrFail($id);
        return view('backend.ppdb.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $model = Ppdb::findOrFail($id);
        $input = $request->except('_token', '_method');
        foreach (['berkas_kk', 'berkas_akta', 'berkas_pasfoto'] as $f) {
            if ($request->hasFile($f)) {
                if ($model->$f) Storage::disk('public')->delete($model->$f);
                $input[$f] = $request->file($f)->store('ppdb/' . date('Y'), 'public');
            } else {
                unset($input[$f]);
            }
        }
        $model->update($input);
        redirect('/admin/ppdb')->with('success', 'Data pendaftar berhasil diubah');
    }

    public function destroy($id)
    {
        $model = Ppdb::findOrFail($id);
        foreach (['berkas_kk', 'berkas_akta', 'berkas_pasfoto'] as $f) {
            if ($model->$f) Storage::disk('public')->delete($model->$f);
        }
        $model->delete();
        redirect('/admin/ppdb')->with('success', 'Data pendaftar berhasil dihapus');
    }
}


