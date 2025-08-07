<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}">
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            background: linear-gradient(to right, #e0f7fa, #f1f8e9);
            font-family: 'Segoe UI', sans-serif;
        }

        .center-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-box {
            background-color: #ffffffdd;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 980px;
            width: 100%;
            text-align: center;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: bold;
            color: #539bd6;
        }

        .hero-description {
            font-size: 1.2rem;
            margin-top: 20px;
            color: #424242;
        }

        .icon-circle {
            background: #dbeeff;
            padding: 15px;
            border-radius: 50%;
            margin-bottom: 20px;
            display: inline-block;
        }

        .login-btn {
            padding: 12px 30px;
            font-size: 1rem;
            border-radius: 30px;
            background-color: #539bd6;
            border: none;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            color: white;
            background-color: #2a5a82ff;
        }
    </style>
</head>

<body>

    <div class="container center-container">
        <div class="hero-box">
            <div class="icon-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#539bd6" class="bi bi-droplet"
                    viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M4.22 4.22a.75.75 0 011.06 0 6.49 6.49 0 002.47 1.49 6.49 6.49 0 002.47-1.49.75.75 0 011.06 1.06A8.006 8.006 0 018 7.42a8.006 8.006 0 01-4.22-2.14.75.75 0 010-1.06z" />
                    <path fill-rule="evenodd"
                        d="M7.293.293a1 1 0 011.414 0l4.95 4.95a6.5 6.5 0 11-10.607 0l4.95-4.95zM8 1.707L3.05 6.657a5 5 0 107.9 0L8 1.707z" />
                </svg>
            </div>
            <h1 class="hero-title">Glucoscreen</h1>
            <p class="hero-description">
                Aplikasi untuk pencegahan dan pengelolaan hiperglikemia melalui edukasi, pemantauan gula darah, pola
                hidup sehat, serta fitur input data kesehatan. Dilengkapi program komunitas seperti Gluco Garden,
                Glucomove, dan pelatihan kader untuk hidup lebih sehat.
            </p>

            <div class="mt-3">
                @if (auth()->check())
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-outline-primary btn-lg rounded-pill">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn login-btn">Login</a>
                @endif
            </div>

            <div class="d-flex justify-content-center mt-3 gap-3">
                <div>
                    <a href="{{ url('/apk/glucoscreen.apk') }}" class="btn login-btn bg-success" download>
                        <i class="ti ti-download"></i>
                        Download App
                    </a>
                </div>
                <div>
                    <a href="itms-services://?action=download-manifest&url=https://glucoscreen.vercel.app/ipa/manifest.plist" class="btn login-btn bg-primary" download>
                        <i class="ti ti-download"></i>
                        Install (iOS)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>