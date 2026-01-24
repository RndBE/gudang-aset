<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Pengguna;

class ForgotPasswordController extends Controller
{
    public function show()
    {
        return view('auth.forgot-password');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email'
        ]);

        $user = Pengguna::where('email', $data['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar'])->withInput();
        }

        $plainToken = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $data['email']],
            ['token' => hash('sha256', $plainToken), 'created_at' => now()]
        );

        $resetLink = route('password.reset', $plainToken) . '?email=' . urlencode($data['email']);

        // Mail::send('auth.reset-password', [
        //     'resetLink' => $resetLink,
        //     'user' => $user
        // ], function ($m) use ($data) {
        //     $m->to($data['email'])->subject('Reset Password AWASS');
        // });
        Mail::send('emails.reset-password', [
            'resetLink' => $resetLink,
            'user' => $user
        ], function ($m) use ($data) {
            $m->to($data['email'])->subject('Reset Password AWASS');
        });

        return back()->with('success', 'Link reset password sudah dikirim ke email kamu.');
    }
}
