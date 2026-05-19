<?php
namespace App\Controllers\Backend;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view("backend.notifikasi.index");
    }

    public function send(Request $request)
    {
        $request->validate(["title" => "required", "body" => "required"]);

        // Store notification in session to be broadcast via JS
        session()->flash("push_notif", [
            "title" => $request->title,
            "body"  => $request->body,
            "url"   => $request->url ?? "/",
        ]);

        // Also save to a log file for reference
        $log = [
            "sent_at" => now()->toDateTimeString(),
            "title"   => $request->title,
            "body"    => $request->body,
            "url"     => $request->url ?? "/",
        ];
        \Illuminate\Support\Facades\Cache::put("last_push_notif", $log, 3600);

        return redirect()->back()->with("success", "Notifikasi berhasil dikirim!");
    }
}


