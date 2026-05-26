<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // =========================
    // REDIRECT KE GOOGLE
    // =========================
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // =========================
    // HANDLE CALLBACK GOOGLE
    // =========================
    public function handleGoogleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            // Jika belum ada → buat baru
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'id_google' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)),
                ]);
            } else {
                // Update id_google kalau belum ada
                if (!$user->id_google) {
                    $user->id_google = $googleUser->getId();
                }
            }

            // Generate OTP 6 karakter
            $otp = (string) random_int(100000, 999999);
            $user->otp = $otp;
            $user->save();

            // Kirim OTP
            Mail::raw("Kode OTP login Anda: $otp", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Kode OTP Login');
            });

            // Simpan ID user sementara
            session(['otp_user_id' => $user->id]);

            return redirect()->route('otp.form');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal kirim OTP: ' . $e->getMessage());
        }
    }

    // =========================
    // VERIFIKASI OTP
    // =========================
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->otp === $request->otp) {

            $user->otp = null;
            $user->save();

            Auth::login($user);

            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Kode OTP salah.');
    }
}