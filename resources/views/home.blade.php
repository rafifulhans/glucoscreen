<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Cegah & Kelola Hiperglikemia</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/glucoscreen.css') }}">
</head>

<body>
    <div class="gs-bg">
        <span class="gs-orb-primary"></span>
        <span class="gs-orb-secondary"></span>

        <div class="gs-landing">
            <!-- Navbar -->
            <nav class="gs-nav">
                <a href="{{ route('home') }}" class="gs-brand">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}">
                    {{ config('app.name') }}
                </a>
                <div class="gs-nav-links">
                    <a href="#fitur">Fitur</a>
                    @if (auth()->check())
                        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="gs-btn gs-btn-primary">
                            <i class="ti ti-layout-dashboard"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="gs-btn gs-btn-ghost">
                            <i class="ti ti-login"></i> Masuk
                        </a>
                    @endif
                </div>
            </nav>

            <!-- Hero -->
            <section class="gs-hero">
                <div>
                    <span class="gs-hero-badge gs-fade-up" style="animation-delay:.1s">
                        <i class="ti ti-heartbeat"></i> Pencegahan Hiperglikemia
                    </span>
                    <h1 class="gs-fade-up" style="animation-delay:.25s">
                        Hidup Sehat,
                        <span class="gs-title-gradient">Gula Darah Terkontrol</span>
                    </h1>
                    <p class="gs-fade-up" style="animation-delay:.4s">
                        <strong>{{ config('app.name') }}</strong> membantu mencegah &amp; mengelola hiperglikemia melalui
                        edukasi, pemantauan gula darah, pola hidup sehat, dan program komunitas
                        <strong>Gluco Garden</strong>, Glucomove, serta pelatihan kader.
                    </p>
                    <div class="gs-hero-actions gs-fade-up" style="animation-delay:.55s">
                        <a href="https://glucoscreen.vercel.app/apk/glucoscreen.apk" class="gs-btn gs-btn-primary" download>
                            <i class="ti ti-brand-android"></i> Unduh APK Android
                        </a>
                        <a href="itms-services:///?action=download-manifest&url=https://glucoscreen.vercel.app/ipa/manifest.plist" class="gs-btn gs-btn-blue">
                            <i class="ti ti-brand-apple"></i> Unduh iOS
                        </a>
                    </div>
                </div>

                <!-- Hero image -->
                <div class="gs-hero-image">
                    <img src="{{ asset('assets/images/gs-hero.png') }}" alt="Ilustrasi pemantauan gula darah"
                        class="gs-hero-img" loading="lazy" decoding="async">
                </div>
            </section>

            <!-- Features -->
            <section class="gs-features" id="fitur">
                <h2>Fitur Unggulan</h2>
                <div class="gs-features-sub">Segala yang Anda butuhkan untuk menjaga kadar gula darah tetap sehat dan terkontrol.</div>
                <div class="gs-features-grid">
                    <div class="gs-feature-card gs-fade-up" style="animation-delay:.2s">
                        <div class="gs-feature-icon"><i class="ti ti-activity-heartbeat"></i></div>
                        <h3>Pemantauan Gula Darah</h3>
                        <p>Catat dan pantau kadar gula darah Anda secara rutin dengan tampilan yang sederhana dan mudah dipahami.</p>
                    </div>
                    <div class="gs-feature-card gs-fade-up" style="animation-delay:.35s">
                        <div class="gs-feature-icon"><i class="ti ti-books"></i></div>
                        <h3>Edukasi Kesehatan</h3>
                        <p>Dapatkan informasi dan edukasi seputar hiperglikemia serta pola hidup sehat untuk keluarga.</p>
                    </div>
                    <div class="gs-feature-card gs-fade-up" style="animation-delay:.5s">
                        <div class="gs-feature-icon"><i class="ti ti-users"></i></div>
                        <h3>Program Komunitas</h3>
                        <p>Ikuti program Gluco Garden, Glucomove, dan pelatihan kader bersama komunitas posyandu terdekat.</p>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="gs-footer">
                &copy; {{ date('Y') }} {{ config('app.name') }} — Cegah & Kelola Hiperglikemia. Dibuat dengan <i class="ti ti-heart-filled" style="color:var(--gs-danger);"></i>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>
