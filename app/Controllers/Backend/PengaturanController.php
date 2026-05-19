<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\SettingWebsite;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    public function index()
    {
        $data = SettingWebsite::first();
        if (!$data) {
            $data = SettingWebsite::create([
                'nama_sekolah' => 'SDN Demakijo 1',
                'alamat'       => 'Jl. Godean, Nogotirto, Gamping, Sleman, Yogyakarta',
                'telepon'      => '0274-123456',
                'email'        => 'info@sdndemakijo1.sch.id',
                'akreditasi'   => 'A',
            ]);
        }
        return view('backend.pengaturan.index', compact('data'));
    }

    public function store(Request $request)
    {
        $data  = SettingWebsite::first();
        $input = $request->except('_token');

        // Handle Logo
        if ($request->hasFile('logo')) {
            if ($data->logo) Storage::disk('public')->delete($data->logo);
            $input['logo'] = $request->file('logo')->store('logo', 'public');
        } else {
            unset($input['logo']);
        }

        // Handle Foto Kepala Sekolah
        if ($request->hasFile('foto_kepsek')) {
            if ($data->foto_kepsek) Storage::disk('public')->delete($data->foto_kepsek);
            $input['foto_kepsek'] = $request->file('foto_kepsek')->store('uploads', 'public');
        } else {
            unset($input['foto_kepsek']);
        }

        // Handle Slider Images (1–5)
        for ($i = 1; $i <= 5; $i++) {
            $field = 'slider_' . $i;
            if ($request->hasFile($field)) {
                if ($data->$field) Storage::disk('public')->delete($data->$field);
                $input[$field] = $request->file($field)->store('slider', 'public');
            } elseif ($request->has('delete_' . $field)) {
                if ($data->$field) Storage::disk('public')->delete($data->$field);
                $input[$field] = null;
            } else {
                unset($input[$field]);
            }
        }

        $data->update($input);
        redirect('/admin/pengaturan')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
