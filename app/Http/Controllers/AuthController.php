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
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToAllowedPage(
                Auth::user()
            );
        }

        return view('auth.login');
    }


    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cari User
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'email',
            $validated['email']
        )->first();


        /*
        |--------------------------------------------------------------------------
        | Email Tidak Ditemukan
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | Akun Sudah Wajib Reset Password
        |--------------------------------------------------------------------------
        |
        | Jika sebelumnya sudah salah password 5 kali,
        | user tidak diperbolehkan login sampai password di-reset.
        |
        */

        if ($user->force_password_reset) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' =>
                        'Akun ini wajib melakukan reset password karena password salah 5 kali.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Password Salah
        |--------------------------------------------------------------------------
        */

        if (
            !Hash::check(
                $validated['password'],
                $user->password
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | Tambah Counter Kesalahan
            |--------------------------------------------------------------------------
            */

            $user->increment(
                'failed_login_attempts'
            );

            $user->refresh();

            /*
            |--------------------------------------------------------------------------
            | Sudah Salah 5 Kali
            |--------------------------------------------------------------------------
            */

            if (
                $user->failed_login_attempts >= 5
            ) {
                $user->force_password_reset = true;
                $user->save();

                return redirect()
                    ->route('password.request')
                    ->withErrors([
                        'email' =>
                            'Password salah 5 kali. Demi keamanan, Anda wajib membuat password baru.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Tampilkan Sisa Percobaan
            |--------------------------------------------------------------------------
            */

            $remainingAttempts =
                5 - $user->failed_login_attempts;

            return back()
                ->withErrors([
                    'email' =>
                        'Email atau password salah. Sisa percobaan: '
                        . $remainingAttempts
                        . '.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | Password Benar
        |--------------------------------------------------------------------------
        |
        | Reset counter kesalahan password.
        |
        */

        if ($user->failed_login_attempts > 0) {
            $user->failed_login_attempts = 0;
            $user->save();
        }


        /*
        |--------------------------------------------------------------------------
        | Hapus OTP Login Lama
        |--------------------------------------------------------------------------
        */

        LoginOtp::where(
            'user_id',
            $user->id
        )->delete();


        /*
        |--------------------------------------------------------------------------
        | Buat OTP Login 6 Digit
        |--------------------------------------------------------------------------
        */

        $otpCode = (string) random_int(
            100000,
            999999
        );


        /*
        |--------------------------------------------------------------------------
        | OTP Hanya Berlaku 50 Detik
        |--------------------------------------------------------------------------
        */

        $loginOtp = LoginOtp::create([
            'user_id' => $user->id,

            'otp_code' => Hash::make(
                $otpCode
            ),

            'expires_at' => now()
                ->addSeconds(50),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Kirim OTP Login ke Email
        |--------------------------------------------------------------------------
        */

        try {

            Mail::raw(
                "Kode OTP login Dulmar Satellite Store Anda adalah: {$otpCode}\n\n" .
                "Kode ini berlaku selama 50 detik.\n" .
                "Jangan berikan kode ini kepada siapa pun.",
                function ($message) use ($user) {

                    $message
                        ->to($user->email)
                        ->subject(
                            'Kode OTP Login - Dulmar Satellite Store'
                        );
                }
            );

        } catch (\Throwable $exception) {

            $loginOtp->delete();

            return back()
                ->withErrors([
                    'email' =>
                        'OTP gagal dikirim. Periksa konfigurasi email.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | Simpan User Pending Login
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'pending_login_user_id',
            $user->id
        );


        return redirect()
            ->route('otp.form')
            ->with(
                'success',
                'Kode OTP telah dikirim ke email Anda. OTP berlaku selama 50 detik.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM OTP LOGIN
    |--------------------------------------------------------------------------
    */

    public function showOtpForm(
        Request $request
    ) {
        if (
            !$request
                ->session()
                ->has(
                    'pending_login_user_id'
                )
        ) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' =>
                        'Silakan login kembali.',
                ]);
        }

        return view(
            'auth.verify-otp'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI OTP LOGIN
    |--------------------------------------------------------------------------
    */

    public function verifyOtp(
        Request $request
    ) {
        $validated = $request->validate([
            'otp_code' => [
                'required',
                'digits:6',
            ],
        ]);

        $userId = $request
            ->session()
            ->get(
                'pending_login_user_id'
            );


        if (!$userId) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' =>
                        'Sesi login sudah berakhir. Silakan login kembali.',
                ]);
        }


        $loginOtp = LoginOtp::where(
            'user_id',
            $userId
        )
            ->whereNull(
                'verified_at'
            )
            ->latest()
            ->first();


        /*
        |--------------------------------------------------------------------------
        | OTP Tidak Ditemukan
        |--------------------------------------------------------------------------
        */

        if (!$loginOtp) {
            return back()
                ->withErrors([
                    'otp_code' =>
                        'Kode OTP tidak ditemukan.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | OTP Kedaluwarsa
        |--------------------------------------------------------------------------
        */

        if (
            now()->greaterThan(
                $loginOtp->expires_at
            )
        ) {
            $loginOtp->delete();

            $request
                ->session()
                ->forget(
                    'pending_login_user_id'
                );

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' =>
                        'Kode OTP sudah kedaluwarsa. Silakan login kembali untuk mendapatkan OTP baru.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | OTP Salah
        |--------------------------------------------------------------------------
        */

        if (
            !Hash::check(
                $validated['otp_code'],
                $loginOtp->otp_code
            )
        ) {
            return back()
                ->withErrors([
                    'otp_code' =>
                        'Kode OTP yang Anda masukkan salah.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | OTP Berhasil
        |--------------------------------------------------------------------------
        */

        $loginOtp->update([
            'verified_at' => now(),
        ]);


        $user = User::findOrFail(
            $userId
        );


        /*
        |--------------------------------------------------------------------------
        | Cek Lagi Apakah User Wajib Reset Password
        |--------------------------------------------------------------------------
        */

        if ($user->force_password_reset) {

            $request
                ->session()
                ->forget(
                    'pending_login_user_id'
                );

            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' =>
                        'Akun Anda wajib melakukan reset password.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Login User
        |--------------------------------------------------------------------------
        */

        Auth::login($user);


        $request
            ->session()
            ->regenerate();


        $request
            ->session()
            ->forget(
                'pending_login_user_id'
            );


        return $this
            ->redirectToAllowedPage(
                $user
            )
            ->with(
                'success',
                'Login berhasil. Selamat datang!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LUPA PASSWORD
    |--------------------------------------------------------------------------
    */

    public function showForgotPassword()
    {
        return view(
            'auth.forgot-password'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | KIRIM OTP RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function sendResetOtp(
        Request $request
    ) {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);


        $user = User::where(
            'email',
            $validated['email']
        )->first();


        /*
        |--------------------------------------------------------------------------
        | Jangan Bocorkan Status Email
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return back()
                ->with(
                    'success',
                    'Jika email terdaftar, kode OTP reset password akan dikirim.'
                )
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | Hapus OTP Lama
        |--------------------------------------------------------------------------
        */

        LoginOtp::where(
            'user_id',
            $user->id
        )->delete();


        /*
        |--------------------------------------------------------------------------
        | Buat OTP Reset Password
        |--------------------------------------------------------------------------
        */

        $otpCode = (string) random_int(
            100000,
            999999
        );


        /*
        |--------------------------------------------------------------------------
        | OTP Reset Berlaku 50 Detik
        |--------------------------------------------------------------------------
        */

        $resetOtp = LoginOtp::create([
            'user_id' => $user->id,

            'otp_code' => Hash::make(
                $otpCode
            ),

            'expires_at' => now()
                ->addSeconds(50),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Kirim OTP Reset Password
        |--------------------------------------------------------------------------
        */

        try {

            Mail::raw(
                "Kode OTP reset password Dulmar Satellite Store Anda adalah: {$otpCode}\n\n" .
                "Kode ini berlaku selama 50 detik.\n" .
                "Jika Anda tidak meminta reset password, abaikan email ini.\n" .
                "Jangan berikan kode ini kepada siapa pun.",
                function ($message) use ($user) {

                    $message
                        ->to($user->email)
                        ->subject(
                            'Reset Password - Dulmar Satellite Store'
                        );
                }
            );

        } catch (\Throwable $exception) {

            $resetOtp->delete();

            return back()
                ->withErrors([
                    'email' =>
                        'Kode OTP gagal dikirim. Silakan coba lagi.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | Simpan User Pending Reset
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'pending_reset_user_id',
            $user->id
        );


        /*
        |--------------------------------------------------------------------------
        | Bersihkan Status Reset Lama
        |--------------------------------------------------------------------------
        */

        $request
            ->session()
            ->forget(
                'reset_password_verified_user_id'
            );


        return redirect()
            ->route(
                'password.otp.form'
            )
            ->with(
                'success',
                'Kode OTP reset password telah dikirim. OTP berlaku selama 50 detik.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM OTP RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function showResetOtpForm(
        Request $request
    ) {
        if (
            !$request
                ->session()
                ->has(
                    'pending_reset_user_id'
                )
        ) {
            return redirect()
                ->route(
                    'password.request'
                )
                ->withErrors([
                    'email' =>
                        'Silakan masukkan email terlebih dahulu.',
                ]);
        }


        return view(
            'auth.reset-password-otp'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI OTP RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function verifyResetOtp(
        Request $request
    ) {
        $validated = $request->validate([
            'otp_code' => [
                'required',
                'digits:6',
            ],
        ]);


        $userId = $request
            ->session()
            ->get(
                'pending_reset_user_id'
            );


        if (!$userId) {
            return redirect()
                ->route(
                    'password.request'
                )
                ->withErrors([
                    'email' =>
                        'Sesi reset password telah berakhir.',
                ]);
        }


        $resetOtp = LoginOtp::where(
            'user_id',
            $userId
        )
            ->whereNull(
                'verified_at'
            )
            ->latest()
            ->first();


        /*
        |--------------------------------------------------------------------------
        | OTP Tidak Ada
        |--------------------------------------------------------------------------
        */

        if (!$resetOtp) {
            return back()
                ->withErrors([
                    'otp_code' =>
                        'Kode OTP tidak ditemukan.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | OTP Kedaluwarsa
        |--------------------------------------------------------------------------
        */

        if (
            now()->greaterThan(
                $resetOtp->expires_at
            )
        ) {
            $resetOtp->delete();


            $request
                ->session()
                ->forget(
                    'pending_reset_user_id'
                );


            return redirect()
                ->route(
                    'password.request'
                )
                ->withErrors([
                    'email' =>
                        'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | OTP Salah
        |--------------------------------------------------------------------------
        */

        if (
            !Hash::check(
                $validated['otp_code'],
                $resetOtp->otp_code
            )
        ) {
            return back()
                ->withErrors([
                    'otp_code' =>
                        'Kode OTP yang Anda masukkan salah.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Tandai OTP Berhasil
        |--------------------------------------------------------------------------
        */

        $resetOtp->update([
            'verified_at' => now(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Izinkan User ke Form Password Baru
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'reset_password_verified_user_id',
            $userId
        );


        $request
            ->session()
            ->forget(
                'pending_reset_user_id'
            );


        return redirect()
            ->route(
                'password.reset.form'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM PASSWORD BARU
    |--------------------------------------------------------------------------
    */

    public function showResetPasswordForm(
        Request $request
    ) {
        if (
            !$request
                ->session()
                ->has(
                    'reset_password_verified_user_id'
                )
        ) {
            return redirect()
                ->route(
                    'password.request'
                );
        }


        return view(
            'auth.reset-password'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PASSWORD BARU
    |--------------------------------------------------------------------------
    */

    public function resetPassword(
        Request $request
    ) {
        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        $userId = $request
            ->session()
            ->get(
                'reset_password_verified_user_id'
            );


        if (!$userId) {
            return redirect()
                ->route(
                    'password.request'
                )
                ->withErrors([
                    'email' =>
                        'Sesi reset password telah berakhir.',
                ]);
        }


        $user = User::findOrFail(
            $userId
        );


        /*
        |--------------------------------------------------------------------------
        | Simpan Password Baru
        |--------------------------------------------------------------------------
        */

        $user->password = Hash::make(
            $validated['password']
        );


        /*
        |--------------------------------------------------------------------------
        | Reset Status Keamanan Login
        |--------------------------------------------------------------------------
        */

        $user->failed_login_attempts = 0;

        $user->force_password_reset = false;

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | Hapus Semua OTP Lama
        |--------------------------------------------------------------------------
        */

        LoginOtp::where(
            'user_id',
            $user->id
        )->delete();


        /*
        |--------------------------------------------------------------------------
        | Bersihkan Session Reset Password
        |--------------------------------------------------------------------------
        */

        $request
            ->session()
            ->forget([
                'pending_reset_user_id',
                'reset_password_verified_user_id',
            ]);


        return redirect()
            ->route('login')
            ->with(
                'success',
                'Password berhasil diubah. Silakan login menggunakan password baru.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(
        Request $request
    ) {
        Auth::logout();


        $request
            ->session()
            ->invalidate();


        $request
            ->session()
            ->regenerateToken();


        return redirect()
            ->route('login')
            ->with(
                'success',
                'Anda berhasil logout.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REDIRECT BERDASARKAN PERMISSION USER
    |--------------------------------------------------------------------------
    */

    private function redirectToAllowedPage(
        User $user
    ) {
        if (
            $user->can(
                'dashboard.view'
            )
        ) {
            return redirect()
                ->route(
                    'dashboard'
                );
        }


        if (
            $user->can(
                'products.view'
            )
        ) {
            return redirect()
                ->route(
                    'products.index'
                );
        }


        if (
            $user->can(
                'promo-campaigns.view'
            )
        ) {
            return redirect()
                ->route(
                    'promo-campaigns.index'
                );
        }


        if (
            $user->can(
                'stock-ins.view'
            )
        ) {
            return redirect()
                ->route(
                    'stock-ins.index'
                );
        }


        if (
            $user->can(
                'stock-outs.view'
            )
        ) {
            return redirect()
                ->route(
                    'stock-outs.index'
                );
        }


        if (
            $user->can(
                'tv-vouchers.view'
            )
        ) {
            return redirect()
                ->route(
                    'tv-vouchers.index'
                );
        }


        if (
            $user->can(
                'suppliers.view'
            )
        ) {
            return redirect()
                ->route(
                    'suppliers.index'
                );
        }


        if (
            $user->can(
                'customers.view'
            )
        ) {
            return redirect()
                ->route(
                    'customers.index'
                );
        }


        if (
            $user->can(
                'reports.view'
            )
        ) {
            return redirect()
                ->route(
                    'reports.index'
                );
        }


        if (
            $user->can(
                'users.view'
            )
        ) {
            return redirect()
                ->route(
                    'users.index'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Tidak Ada Permission
        |--------------------------------------------------------------------------
        */

        Auth::logout();


        return redirect()
            ->route('login')
            ->withErrors([
                'email' =>
                    'Akun Anda belum memiliki hak akses. Hubungi Administrator.',
            ]);
    }
}