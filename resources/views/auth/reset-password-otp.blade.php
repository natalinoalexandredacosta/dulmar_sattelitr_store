<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Verifikasi OTP Reset Password - Dulmar Satellite Store</title>

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
            max-width: 440px;

            padding: 35px;

            border-radius: 14px;

            background-color: rgba(255, 255, 255, 0.95);

            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.22);
        }

        h1 {
            margin: 0 0 12px;

            color: #1f2937;

            font-size: 27px;
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

        input {
            width: 100%;

            padding: 14px 15px;

            border: 1px solid #d1d5db;
            border-radius: 8px;

            font-size: 22px;
            letter-spacing: 6px;
            text-align: center;
        }

        input:focus {
            border-color: #2563eb;

            outline: none;

            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
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

        .success {
            margin-bottom: 20px;

            padding: 13px 15px;

            border-radius: 8px;

            background-color: #dcfce7;
            color: #166534;
        }

        .error {
            margin-top: 8px;

            color: #dc2626;

            font-size: 14px;
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
    </style>
</head>

<body>

<div class="card">

    <h1>
        Verifikasi OTP
    </h1>

    <p class="description">
        Masukkan kode OTP 6 digit yang telah dikirim ke email Anda.
        Kode berlaku selama 5 menit.
    </p>

    @if (session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif

    <form
        action="{{ route('password.otp.verify') }}"
        method="POST"
    >

        @csrf

        <div class="form-group">

            <label for="otp_code">
                Kode OTP
            </label>

            <input
                type="text"
                id="otp_code"
                name="otp_code"
                maxlength="6"
                inputmode="numeric"
                pattern="[0-9]{6}"
                placeholder="000000"
                autocomplete="one-time-code"
                required
                autofocus
            >

            @error('otp_code')

                <div class="error">
                    {{ $message }}
                </div>

            @enderror

        </div>

        <button
            type="submit"
            class="button"
        >
            Verifikasi OTP
        </button>

    </form>

    <a
        href="{{ route('password.request') }}"
        class="back-link"
    >
        Kirim ulang / gunakan email lain
    </a>

</div>

</body>
</html>