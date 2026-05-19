<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    public function edit(Request $request)
    {
        $user = Auth::user();
        return $this->view('profile.edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user  = Auth::user();
        $input = $request->except('_token', '_method', 'password');
        if ($request->filled('password')) {
            $input['password'] = Hash::make($request->password);
        }
        User::find($user->id)->update($input);
        redirect('/profile/edit')->with('success', 'Profil berhasil diperbarui!');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        User::find($user->id)->delete();
        redirect('/');
    }
}
