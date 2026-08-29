<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Password Baru - Dulmar Satellite Store</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            font-family: Arial, sans-serif;

            background: linear-gradient(
                135deg,
                #1f2b3a,
                #2563eb
            );
        }

        .card {
            width: 100%;
            max-width: 460px;

            padding: 35px;

            border-radius: 14px;

            background-color: rgba(
                255,
                255,
                255,
                0.96
            );

            box-shadow:
                0 15px 40px
                rgba(0, 0, 0, 0.22);
        }

        h1 {
            margin: 0 0 12px;

            color: #1f2937;

            font-size: 28px;
            text-align: center;
        }

        .description {
            margin: 0 0 28px;

            color: #6b7280;

            line-height: 1.6;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;

            margin-bottom: 8px;

            color: #374151;

            font-weight: bold;
        }

        .password-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;

            padding: 13px 95px 13px 14px;

            border: 1px solid #d1d5db;
            border-radius: 8px;

            font-size: 16px;
        }

        .form-control:focus {
            border-color: #2563eb;

            outline: none;

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, 0.15);
        }

        .show-password {
            position: absolute;
            top: 50%;
            right: 12px;

            border: none;
            background: transparent;

            color: #2563eb;

            font-size: 13px;

            cursor: pointer;

            transform: translateY(-50%);
        }

        .button {
            width: 100%;

            padding: 14px;

            border: none;
            border-radius: 8px;

            background-color: #2563eb;
            color: white;

            font-size: 16px;
            font-weight: bold;

            cursor: pointer;
        }

        .button:hover {
            background-color: #1d4ed8;
        }

        .error {
            margin-top: 7px;

            color: #dc2626;

            font-size: 14px;
        }

        .info {
            margin-bottom: 22px;

            padding: 13px 15px;

            border-left: 4px solid #2563eb;
            border-radius: 7px;

            background-color: #eff6ff;
            color: #1e3a8a;

            font-size: 14px;
            line-height: 1.5;
        }

        .back-link {
            display: block;

            margin-top: 20px;

            color: #2563eb;

            text-align: center;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .card {
                padding: 28px 20px;
            }

            h1 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

<div class="card">

    <h1>
        Buat Password Baru
    </h1>

    <p class="description">
        OTP berhasil diverifikasi.
        Sekarang masukkan password baru untuk akun Anda.
    </p>

    <div class="info">
        Gunakan minimal 8 karakter dan pilih password yang mudah Anda ingat tetapi tidak mudah ditebak.
    </div>

    <form
        action="{{ route('password.update') }}"
        method="POST"
    >

        @csrf

        <div class="form-group">

            <label for="password">
                Password Baru
            </label>

            <div class="password-wrapper">

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password baru"
                    autocomplete="new-password"
                    required
                    autofocus
                >

                <button
                    type="button"
                    class="show-password"
                    onclick="togglePassword(
                        'password',
                        this
                    )"
                >
                    Tampilkan
                </button>

            </div>

            @error('password')
                <div class="error">
                    {{ $message }}
                </div>
            @enderror

        </div>


        <div class="form-group">

            <label for="password_confirmation">
                Konfirmasi Password Baru
            </label>

            <div class="password-wrapper">

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Ulangi password baru"
                    autocomplete="new-password"
                    required
                >

                <button
                    type="button"
                    class="show-password"
                    onclick="togglePassword(
                        'password_confirmation',
                        this
                    )"
                >
                    Tampilkan
                </button>

            </div>

        </div>


        <button
            type="submit"
            class="button"
        >
            Simpan Password Baru
        </button>

    </form>


    <a
        href="{{ route('login') }}"
        class="back-link"
    >
        Kembali ke Login
    </a>

</div>


<script>
    function togglePassword(
        inputId,
        button
    ) {
        const input =
            document.getElementById(
                inputId
            );

        if (
            input.type ===
            'password'
        ) {
            input.type =
                'text';

            button.textContent =
                'Sembunyikan';
        } else {
            input.type =
                'password';

            button.textContent =
                'Tampilkan';
        }
    }
</script>

</body>
</html>