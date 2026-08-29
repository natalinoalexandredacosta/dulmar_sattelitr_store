<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Lupa Password - Dulmar Satellite Store</title>

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
            background-color: #f4f6f9;
        }

        .card {
            width: 100%;
            max-width: 440px;

            padding: 35px;

            border-radius: 14px;
            background-color: white;

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin: 0 0 12px;
            color: #1f2937;
            font-size: 28px;
        }

        .description {
            margin: 0 0 28px;
            color: #6b7280;
            line-height: 1.6;
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

        input {
            width: 100%;
            padding: 13px 14px;

            border: 1px solid #d1d5db;
            border-radius: 8px;

            font-size: 16px;
        }

        input:focus {
            border-color: #2563eb;
            outline: none;
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

        .back-link {
            display: block;
            margin-top: 20px;

            color: #2563eb;
            text-align: center;
            text-decoration: none;
        }

        .success {
            margin-bottom: 20px;
            padding: 13px 15px;

            border-radius: 8px;

            background-color: #dcfce7;
            color: #166534;
        }

        .error {
            margin-top: 7px;
            color: #dc2626;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="card">

    <h1>
        Lupa Password
    </h1>

    <p class="description">
        Masukkan email akun Anda.
        Kami akan mengirim kode OTP untuk melakukan reset password.
    </p>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <form
        action="{{ route('password.email') }}"
        method="POST"
    >

        @csrf

        <div class="form-group">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
            >

            @error('email')
                <div class="error">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <button
            type="submit"
            class="button"
        >
            Kirim OTP Reset Password
        </button>

    </form>

    <a
        href="{{ route('login') }}"
        class="back-link"
    >
        Kembali ke Login
    </a>

</div>

</body>
</html>