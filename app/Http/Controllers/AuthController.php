<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;


/**
 * >>> Main Auth Controller <<<
 * > Contains:
 * views
 * store (register)
 * authenticate (login)
 * destroy (logout)
 * 
 * > TODO:
 * edit (edit profile)
 */
class AuthController extends Controller {
    public function viewLogin(Request $request) {
        if (RateLimiter::tooManyAttempts("authenticate" . $request->ip(), env("DEF_LOGIN_THROTTLE_PERMIN", 5))) {
            $seconds = RateLimiter::availableIn("authenticate" . $request->ip());
            $rejectString = "Anda melakukan terlalu banyak percobaan gagal! Kembali lagi dalam " . $seconds . " detik";
            session()->flash("loginError", $rejectString);
        }
        return view("auth.login");
    }

    public function viewRegister() {
        return view("auth.register");
    }

    public function store(Request $request) {
        $validated = $request->validate(
            [
                "fullname" => ["required", "max:255", "min:3"],
                "username" => ["required", "max:255", "min:3", "unique:users"],
                "email" => ["required", "email:dns", "unique:users"],
                "password" => ["required", "confirmed", "max:32", "min:8"],
            ],
            [
                "required" => "Atribut ini wajib diisi!",
                "email" => "Format alamat email tidak valid!",
                "min" => "Atribut ini harus memuat setidaknya :min karakter!",
                "max" => "Atribut ini maksimal memuat :max karakter!",
                "confirmed" => "Password dan konfirmasi harus sama!",
                "unique" => "Atribut ini sudah digunakan!"
            ]
        );

        $validated["password"] = Hash::make($validated["password"]);

        $created_user = User::create($validated);
        Auth::login($created_user);

        return redirect(route("home"));
    }

    public function authenticate(Request $request) {
        $executed = RateLimiter::attempt(
            "authenticate" . $request->ip(),
            $perMinute = env("DEF_LOGIN_THROTTLE_PERMIN", 5),
            function () {
            }
        );

        if (!$executed) {
            $seconds = RateLimiter::availableIn("authenticate" . $request->ip());
            $rejectString = "Anda melakukan terlalu banyak percobaan gagal! Kembali lagi dalam " . $seconds . " detik";
            return back()->with("loginError", $rejectString);
        }

        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $remember = false;
        if (isset($request->all()["remember"])) {
            $remember = true;
        }

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear("authenticate" . $request->ip());
            $request->session()->regenerate();
            return redirect()->intended(route("home"));
        }

        return back()->with("loginError", "Username atau Password yang anda berikan tidak sesuai dengan data dalam database");
    }

    public function destroy(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route("home"));
    }
}
