<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function show(Request $request, string $token)
    {
        // return view('auth.reset-password', [
        //     'token' => $token,
        //     // 'token' => $request->query('token'),
        //     'email' => $request->query('email')
        // ]);
        $email = $request->query('email');

        $row = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$row || !hash_equals($row->token, hash('sha256', $token))) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset tidak valid atau sudah digunakan.']);
        }

        if (now()->diffInMinutes($row->created_at) > 60) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset sudah kedaluwarsa.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $row = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

        if (!$row) {
            return back()->withErrors(['email' => 'Token reset tidak valid atau sudah kedaluwarsa']);
        }

        if (!hash_equals($row->token, hash('sha256', $data['token']))) {
            return back()->withErrors(['email' => 'Token reset tidak valid']);
        }

        if (now()->diffInMinutes($row->created_at) > 60) {
            return back()->withErrors(['email' => 'Token reset sudah kedaluwarsa (lebih dari 60 menit)']);
        }

        $user = Pengguna::where('email', $data['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan']);
        }

        $user->hash_password = Hash::make($data['password']);
        $user->remember_token = Str::random(60);

        if (in_array('diubah_pada', $user->getFillable())) {
            $user->diubah_pada = now();
        }

        $user->save();

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login.');
    }
}
