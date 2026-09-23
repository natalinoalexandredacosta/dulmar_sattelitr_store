<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Dulmar Satellite Store</title>

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/logo-dulmar.png') }}"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 105px 24px 30px;

            overflow-x: hidden;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                radial-gradient(
                    circle at top right,
                    rgba(59, 130, 246, 0.35),
                    transparent 32%
                ),
                linear-gradient(
                    135deg,
                    #172033 0%,
                    #1e3a6d 48%,
                    #2563eb 100%
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Welcome Banner
        |--------------------------------------------------------------------------
        */

        .welcome-banner {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 30;

            width: 100%;
            height: 72px;

            display: flex;
            align-items: center;

            overflow: hidden;

            border-bottom:
                1px solid rgba(
                    255,
                    255,
                    255,
                    0.22
                );

            background:
                linear-gradient(
                    90deg,
                    rgba(15, 23, 42, 0.97),
                    rgba(30, 64, 175, 0.96),
                    rgba(37, 99, 235, 0.96)
                );

            box-shadow:
                0 5px 18px rgba(
                    0,
                    0,
                    0,
                    0.22
                );
        }

        .welcome-text {
            position: absolute;
            left: 0;

            white-space: nowrap;

            color: #ffffff;

            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;

            text-shadow:
                0 2px 5px rgba(
                    0,
                    0,
                    0,
                    0.5
                );

            animation:
                bergerakKeKanan
                14s
                linear
                infinite;
        }

        .welcome-text span {
            color: #facc15;
        }

        @keyframes bergerakKeKanan {
            from {
                transform:
                    translateX(-100%);
            }

            to {
                transform:
                    translateX(100vw);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Login Card
        |--------------------------------------------------------------------------
        */

        .login-card {
            position: relative;
            z-index: 5;

            width: 100%;
            max-width: 430px;

            padding:
                38px 38px 36px;

            border:
                1px solid rgba(
                    255,
                    255,
                    255,
                    0.70
                );

            border-radius: 20px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.93
                );

            backdrop-filter:
                blur(8px);

            -webkit-backdrop-filter:
                blur(8px);

            box-shadow:
                0 24px 55px rgba(
                    0,
                    0,
                    0,
                    0.30
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Logo
        |--------------------------------------------------------------------------
        */

        .logo {
            width: 145px;
            height: 145px;

            display: flex;
            justify-content: center;
            align-items: center;

            margin:
                0 auto 22px;

            padding: 5px;

            overflow: hidden;

            border:
                4px solid #dbeafe;

            border-radius: 50%;

            background: #ffffff;

            box-shadow:
                0 8px 20px rgba(
                    37,
                    99,
                    235,
                    0.20
                ),
                0 4px 12px rgba(
                    0,
                    0,
                    0,
                    0.14
                );
        }

        .logo img {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: contain;

            border-radius: 50%;
        }

        /*
        |--------------------------------------------------------------------------
        | Title
        |--------------------------------------------------------------------------
        */

        .brand-title {
            margin:
                0 0 8px;

            color: #172033;

            font-size: 30px;
            font-weight: 800;
            line-height: 1.2;

            text-align: center;
        }

        .subtitle {
            margin:
                0 0 30px;

            color: #64748b;

            font-size: 15px;
            line-height: 1.55;

            text-align: center;
        }

        /*
        |--------------------------------------------------------------------------
        | Alert
        |--------------------------------------------------------------------------
        */

        .alert {
            margin-bottom: 22px;

            padding:
                13px 15px;

            border-radius: 10px;

            font-size: 14px;
            line-height: 1.5;
        }

        .alert-success {
            border:
                1px solid #86efac;

            background:
                #dcfce7;

            color: #166534;
        }

        .alert-error {
            border:
                1px solid #fca5a5;

            background:
                #fee2e2;

            color: #991b1b;
        }

        .alert ul {
            margin: 0;

            padding-left: 20px;
        }

        /*
        |--------------------------------------------------------------------------
        | Form
        |--------------------------------------------------------------------------
        */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;

            margin-bottom: 8px;

            color: #1f2937;

            font-size: 14px;
            font-weight: 700;
        }

        .form-control {
            width: 100%;

            padding:
                13px 14px;

            border:
                1px solid #cbd5e1;

            border-radius: 10px;

            background:
                #f8fafc;

            color: #0f172a;

            font-size: 15px;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background-color 0.2s ease;
        }

        .form-control:hover {
            background:
                #ffffff;
        }

        .form-control:focus {
            border-color:
                #2563eb;

            outline: none;

            background:
                #ffffff;

            box-shadow:
                0 0 0 4px rgba(
                    37,
                    99,
                    235,
                    0.12
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        .password-wrapper {
            position: relative;
        }

        .password-wrapper
        .form-control {
            padding-right: 105px;
        }

        .show-password {
            position: absolute;
            top: 50%;
            right: 12px;

            padding:
                4px 6px;

            border: none;

            background:
                transparent;

            color: #2563eb;

            font-size: 13px;
            font-weight: 600;

            cursor: pointer;

            transform:
                translateY(-50%);
        }

        .show-password:hover {
            color: #1d4ed8;
        }

        /*
        |--------------------------------------------------------------------------
        | Forgot Password
        |--------------------------------------------------------------------------
        */

        .forgot-password-wrapper {
            display: flex;
            justify-content: flex-end;

            margin-top: -5px;
            margin-bottom: 22px;
        }

        .forgot-password-link {
            color: #2563eb;

            font-size: 14px;
            font-weight: 600;

            text-decoration: none;
        }

        .forgot-password-link:hover {
            color: #1d4ed8;

            text-decoration: underline;
        }

        /*
        |--------------------------------------------------------------------------
        | Login Button
        |--------------------------------------------------------------------------
        */

        .button-login {
            width: 100%;

            padding: 14px;

            border: none;
            border-radius: 10px;

            background:
                linear-gradient(
                    90deg,
                    #2563eb,
                    #1d4ed8
                );

            color: #ffffff;

            font-size: 16px;
            font-weight: 700;

            cursor: pointer;

            box-shadow:
                0 8px 18px rgba(
                    37,
                    99,
                    235,
                    0.24
                );

            transition:
                transform 0.15s ease,
                box-shadow 0.15s ease;
        }

        .button-login:hover {
            transform:
                translateY(-1px);

            box-shadow:
                0 10px 22px rgba(
                    37,
                    99,
                    235,
                    0.30
                );
        }

        .button-login:active {
            transform:
                translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        @media (
            max-width: 700px
        ) {
            body {
                padding:
                    88px 14px 20px;
            }

            .welcome-banner {
                height: 62px;
            }

            .welcome-text {
                font-size: 20px;

                animation-duration:
                    11s;
            }

            .login-card {
                max-width: 390px;

                padding:
                    28px 22px 30px;

                border-radius: 16px;
            }

            .logo {
                width: 112px;
                height: 112px;

                margin-bottom: 18px;
            }

            .brand-title {
                font-size: 25px;
            }

            .subtitle {
                margin-bottom: 26px;

                font-size: 14px;
            }

            .form-control {
                font-size: 14px;
            }

            .button-login {
                font-size: 15px;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Reduce Motion
        |--------------------------------------------------------------------------
        */

        @media (
            prefers-reduced-motion:
            reduce
        ) {
            .welcome-text {
                left: 50%;

                transform:
                    translateX(-50%);

                animation: none;
            }

            .button-login {
                transition: none;
            }
        }
    </style>
</head>

<body>

    <div class="welcome-banner">

        <div class="welcome-text">

            Welcome Mai

            <span>
                Dulmar Satellite Store
            </span>

        </div>

    </div>


    <div class="login-card">

        <div class="logo">

            <img
                src="{{ asset('images/logo-dulmar.png') }}"
                alt="Dulmar Satellite Store"
            >

        </div>


        <h1 class="brand-title">
            Dulmar Satellite Store
        </h1>


        <p class="subtitle">
            Masuk untuk mengakses sistem manajemen inventaris.
        </p>


        @if (session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        @php
            $filteredErrors = collect($errors->all())->reject(function ($error) {
                return $error === 'Sesi Anda berakhir karena tidak ada aktivitas selama 10 menit. Silakan login kembali.';
            });
        @endphp


        @if ($filteredErrors->isNotEmpty())

            <div class="alert alert-error">

                <ul>

                    @foreach ($filteredErrors as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('login.process') }}"
            method="POST"
        >

            @csrf


            <div class="form-group">

                <label for="email">
                    Alamat Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="Masukkan alamat email"
                    autocomplete="email"
                    required
                    autofocus
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Kata Sandi
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan kata sandi"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="show-password"
                        onclick="togglePassword()"
                    >
                        Tampilkan
                    </button>

                </div>

            </div>


            <div class="forgot-password-wrapper">

                <a
                    href="{{ route('password.request') }}"
                    class="forgot-password-link"
                >
                    Lupa Password?
                </a>

            </div>


            <button
                type="submit"
                class="button-login"
            >
                Login
            </button>

        </form>

    </div>


    <script>
        function togglePassword() {

            const passwordInput =
                document.getElementById(
                    'password'
                );

            const button =
                document.querySelector(
                    '.show-password'
                );

            if (
                passwordInput.type ===
                'password'
            ) {
                passwordInput.type =
                    'text';

                button.textContent =
                    'Sembunyikan';

            } else {

                passwordInput.type =
                    'password';

                button.textContent =
                    'Tampilkan';
            }
        }
    </script>

</body>
</html>