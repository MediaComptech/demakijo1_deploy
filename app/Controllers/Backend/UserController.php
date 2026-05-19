<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    public function index()
    {
        $data = User::latest()->get();
        return view('backend.user.index', compact('data'));
    }

    public function create()
    {
        return view('backend.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role ?? 'admin',
        ]);

        redirect('/admin/user')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);
        return view('backend.user.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $user  = User::findOrFail($id);
        $input = $request->except('_token', '_method', 'password');

        if ($request->filled('password')) {
            $input['password'] = Hash::make($request->password);
        }

        $user->update($input);
        redirect('/admin/user')->with('success', 'User berhasil diubah');
    }

    public function destroy($id)
    {
        if ($id == 1) {
            redirect('/admin/user')->with('error', 'Super Admin tidak bisa dihapus!');
            return;
        }
        User::findOrFail($id)->delete();
        redirect('/admin/user')->with('success', 'User berhasil dihapus');
    }
}
