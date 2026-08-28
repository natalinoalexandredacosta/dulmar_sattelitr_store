<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verify OTP - Dulmar Satellite Store</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-dulmar.jpg') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1f2b3a, #2563eb);
        }

        .otp-card {
            width: 100%;
            max-width: 430px;
            padding: 40px;
            border-radius: 14px;
            background-color: white;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        .otp-icon {
            width: 75px;
            height: 75px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
            border-radius: 50%;
            background-color: #dbeafe;
            font-size: 34px;
        }

        h1 {
            margin: 0 0 12px;
            color: #1f2b3a;
            font-size: 30px;
        }

        .subtitle {
            margin: 0 0 28px;
            color: #6b7280;
            font-size: 15px;
            line-height: 1.5;
        }

        .alert {
            margin-bottom: 22px;
            padding: 14px 16px;
            border-radius: 7px;
            font-size: 14px;
            line-height: 1.5;
            text-align: left;
        }

        .alert-success {
            border: 1px solid #86efac;
            background-color: #dcfce7;
            color: #166534;
        }

        .alert-error {
            border: 1px solid #fca5a5;
            background-color: #fee2e2;
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
            margin-bottom: 12px;
            color: #1f2937;
            font-size: 15px;
            font-weight: bold;
            text-align: left;
        }

        .otp-input {
            width: 100%;
            padding: 15px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 10px;
            text-align: center;
        }

        .otp-input:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .button-verify {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 7px;
            background-color: #16a34a;
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
        }

        .button-verify:hover {
            background-color: #15803d;
        }

        .expiry-info {
            margin: 22px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2563eb;
            font-size: 15px;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="otp-card">
        <div class="otp-icon">🔐</div>

        <h1>Verify OTP</h1>

        <p class="subtitle">
            Enter the 6-digit verification code sent to your email.
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

        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="otp_code">OTP Code</label>

                <input
                    type="text"
                    id="otp_code"
                    name="otp_code"
                    class="otp-input"
                    value="{{ old('otp_code') }}"
                    placeholder="000000"
                    maxlength="6"
                    minlength="6"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    autocomplete="one-time-code"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="button-verify">
                Verify OTP
            </button>
        </form>

        <p class="expiry-info">
            The OTP code is valid for 5 minutes.
        </p>

        <a href="{{ route('login') }}" class="back-link">
            ← Return to Login
        </a>
    </div>

    <script>
        const otpInput = document.getElementById('otp_code');

        otpInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>