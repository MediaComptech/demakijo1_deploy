<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Agenda;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class AgendaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    public function index()
    {
        $data = Agenda::latest()->get();
        return view('backend.agenda.index', compact('data'));
    }

    public function create()
    {
        return view('backend.agenda.create');
    }

    public function store(Request $request)
    {
        $input = $request->except('_token');
        if (empty($input['slug'])) {
            $input['slug'] = Str::slug($request->judul ?? $request->nama ?? time());
        }
        Agenda::create($input);
        Cache::forget('agenda_all');
        redirect('/admin/agenda')->with('success', 'Agenda berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = Agenda::findOrFail($id);
        return view('backend.agenda.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $model = Agenda::findOrFail($id);
        $input = $request->except('_token', '_method');
        if (empty($input['slug'])) {
            $input['slug'] = Str::slug($request->judul ?? $request->nama ?? time());
        }
        $model->update($input);
        Cache::forget('agenda_all');
        redirect('/admin/agenda')->with('success', 'Agenda berhasil diubah');
    }

    public function destroy($id)
    {
        Agenda::findOrFail($id)->delete();
        Cache::forget('agenda_all');
        redirect('/admin/agenda')->with('success', 'Agenda berhasil dihapus');
    }
}
