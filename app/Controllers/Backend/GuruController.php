<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Guru;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class GuruController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    public function index()
    {
        $data = Guru::latest()->get();
        return view('backend.guru.index', compact('data'));
    }

    public function create()
    {
        return view('backend.guru.create');
    }

    public function store(Request $request)
    {
        $input = $request->except('_token');
        if (empty($input['slug'])) {
            $input['slug'] = Str::slug($request->nama ?? $request->judul ?? (string)time());
        }
        if ($request->hasFile('foto')) {
            $input['foto'] = $request->file('foto')->store('uploads', 'public');
        }
        Guru::create($input);
        Cache::forget('guru_all');
        redirect('/admin/guru')->with('success', 'Data Guru berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = Guru::findOrFail($id);
        return view('backend.guru.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $model = Guru::findOrFail($id);
        $input = $request->except('_token', '_method');
        if (empty($input['slug'])) {
            $input['slug'] = Str::slug($request->nama ?? $request->judul ?? (string)time());
        }
        if ($request->hasFile('foto')) {
            if ($model->foto) Storage::disk('public')->delete($model->foto);
            $input['foto'] = $request->file('foto')->store('uploads', 'public');
        }
        $model->update($input);
        Cache::forget('guru_all');
        redirect('/admin/guru')->with('success', 'Data Guru berhasil diubah');
    }

    public function destroy($id)
    {
        $model = Guru::findOrFail($id);
        if ($model->foto) Storage::disk('public')->delete($model->foto);
        $model->delete();
        Cache::forget('guru_all');
        redirect('/admin/guru')->with('success', 'Data Guru berhasil dihapus');
    }
}
