<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Dulmar Satellite Store</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            position: relative;
            margin: 0;
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            padding: 100px 25px 25px;
            font-family: Arial, sans-serif;
            background: linear-gradient(
                135deg,
                #1f2b3a,
                #2563eb
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tulisan berjalan
        |--------------------------------------------------------------------------
        */

        .welcome-banner {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 20;
            width: 100%;
            height: 75px;
            display: flex;
            align-items: center;
            overflow: hidden;
            border-bottom: 2px solid rgba(255, 255, 255, 0.4);
            background: linear-gradient(
                90deg,
                rgba(15, 23, 42, 0.94),
                rgba(37, 99, 235, 0.94)
            );
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.28);
        }

        .welcome-text {
            position: absolute;
            left: 0;
            white-space: nowrap;
            color: white;
            font-size: 30px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-shadow:
                0 3px 5px rgba(0, 0, 0, 0.7),
                0 0 15px rgba(96, 165, 250, 0.9);

            animation: bergerakKeKanan 13s linear infinite;
        }

        .welcome-text span {
            color: #facc15;
        }

        @keyframes bergerakKeKanan {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(100vw);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Kotak Login
        |--------------------------------------------------------------------------
        */

        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 430px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 14px;
            background-color: rgba(255, 255, 255, 0.86);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.28);
        }

        /* Logo kecil di atas formulir */
        .logo {
            width: 105px;
            height: 105px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
            padding: 5px;
            overflow: hidden;
            border: 3px solid #dbeafe;
            border-radius: 50%;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.16);
        }

        .logo img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: contain;
        }

        h1 {
            margin: 0 0 10px;
            color: #1f2b3a;
            font-size: 29px;
            text-align: center;
        }

        .subtitle {
            margin: 0 0 30px;
            color: #4b5563;
            font-size: 15px;
            line-height: 1.5;
            text-align: center;
        }

        .alert {
            margin-bottom: 22px;
            padding: 14px 16px;
            border-radius: 7px;
            font-size: 14px;
            line-height: 1.5;
        }

        .alert-success {
            border: 1px solid #86efac;
            background-color: rgba(220, 252, 231, 0.95);
            color: #166534;
        }

        .alert-error {
            border: 1px solid #fca5a5;
            background-color: rgba(254, 226, 226, 0.95);
            color: #991b1b;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            color: #1f2937;
            font-size: 15px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            background-color: rgba(255, 255, 255, 0.94);
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 95px;
        }

        .show-password {
            position: absolute;
            top: 50%;
            right: 12px;
            border: none;
            background: none;
            color: #2563eb;
            font-size: 14px;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .button-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 7px;
            background-color: #2563eb;
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
        }

        .button-login:hover {
            background-color: #1d4ed8;
        }

        /*
        |--------------------------------------------------------------------------
        | Tampilan HP
        |--------------------------------------------------------------------------
        */

        @media (max-width: 700px) {
            body {
                padding: 90px 15px 20px;
            }

            body::before {
                background-position: center;
                background-size: contain;
                opacity: 0.3;
            }

            .welcome-banner {
                height: 65px;
            }

            .welcome-text {
                font-size: 21px;
                animation-duration: 10s;
            }

            .login-card {
                padding: 30px 22px;
                background-color: rgba(255, 255, 255, 0.9);
            }

            .logo {
                width: 90px;
                height: 90px;
            }

            h1 {
                font-size: 25px;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Kurangi animasi jika pengguna menonaktifkan animasi
        |--------------------------------------------------------------------------
        */

        @media (prefers-reduced-motion: reduce) {
            .welcome-text {
                left: 50%;
                transform: translateX(-50%);
                animation: none;
            }
        }
    </style>
</head>

<body>
    <!-- Tulisan berjalan di bagian atas -->
    <div class="welcome-banner">
        <div class="welcome-text">
            Welcome Mai
            <span>Dulmar Satellite Store</span>
        </div>
    </div>

    <div class="login-card">
        <div class="logo">
            <img
                src="{{ asset('images/logo-dulmar.jpg') }}"
                alt="Logo Dulmar Online Shop"
            >
        </div>

        <h1>Dulmar Satellite Store</h1>

        <p class="subtitle">
            Masuk untuk mengakses sistem manajemen inventaris.
        </p>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Alamat Email</label>

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
                <label for="password">Kata Sandi</label>

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

            <button type="submit" class="button-login">
                Login dan Kirim OTP
            </button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput =
                document.getElementById('password');

            const button =
                document.querySelector('.show-password');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                button.textContent = 'Sembunyikan';
            } else {
                passwordInput.type = 'password';
                button.textContent = 'Tampilkan';
            }
        }
    </script>
</body>
</html>