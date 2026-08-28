<?php

namespace App\Http\Controllers;

use App\Models\LoginOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->onlyInput('email');
        }

        // Hapus OTP lama milik user.
        LoginOtp::where('user_id', $user->id)->delete();

        // Membuat kode OTP 6 digit.
        $otpCode = (string) random_int(100000, 999999);

        // Simpan OTP dalam bentuk terenkripsi/hash.
        $loginOtp = LoginOtp::create([
            'user_id' => $user->id,
            'otp_code' => Hash::make($otpCode),
            'expires_at' => now()->addMinutes(5),
        ]);

        try {
            Mail::raw(
                "Kode OTP login Dulmar Satellite Store Anda adalah: {$otpCode}\n\n" .
                "Kode ini berlaku selama 5 menit.\n" .
                "Jangan berikan kode ini kepada siapa pun.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Kode OTP Login - Dulmar Satellite Store');
                }
            );
        } catch (\Throwable $exception) {
            $loginOtp->delete();

            return back()
                ->withErrors([
                    'email' => 'OTP gagal dikirim. Periksa konfigurasi email di file .env.',
                ])
                ->onlyInput('email');
        }

        // Simpan sementara ID user yang sedang melakukan verifikasi.
        $request->session()->put('pending_login_user_id', $user->id);

        return redirect()
            ->route('otp.form')
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showOtpForm(Request $request)
    {
        if (!$request->session()->has('pending_login_user_id')) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Silakan login kembali.',
                ]);
        }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('pending_login_user_id');

        if (!$userId) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Sesi login sudah berakhir. Silakan login kembali.',
                ]);
        }

        $loginOtp = LoginOtp::where('user_id', $userId)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$loginOtp) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP tidak ditemukan.',
            ]);
        }

        if (now()->greaterThan($loginOtp->expires_at)) {
            $loginOtp->delete();

            return back()->withErrors([
                'otp_code' => 'Kode OTP sudah kedaluwarsa. Silakan login kembali.',
            ]);
        }

        if (!Hash::check($validated['otp_code'], $loginOtp->otp_code)) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP yang Anda masukkan salah.',
            ]);
        }

        $loginOtp->update([
            'verified_at' => now(),
        ]);

        $user = User::findOrFail($userId);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('pending_login_user_id');

        return redirect()
            ->route('dashboard')
            ->with('success', 'Login berhasil. Selamat datang!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}