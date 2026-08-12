<x-auth>
    <div class="gs-auth-brand">
        <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}">
        <h2 class="gs-title-gradient">Selamat Datang 👋</h2>
        <p class="gs-auth-subtitle">Masuk untuk mengakses {{ config('app.name') }}</p>
    </div>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="gs-alert">{{ $error }}</div>
            @endforeach
        @endif

        <div class="mb-3">
            <label for="username" class="gs-form-label">Username</label>
            <input type="text" class="gs-input" id="username" name="username" placeholder="Masukkan username Anda"
                value="{{ old('username') }}" required autocomplete="username" autofocus>
        </div>

        <div class="mb-4">
            <label for="password" class="gs-form-label">Password</label>
            <div class="gs-input-group">
                <input type="password" class="gs-input gs-input-with-icon" id="password" name="password"
                    placeholder="Masukkan password Anda" required autocomplete="current-password">
                <button type="button" class="gs-toggle-password" id="togglePassword" aria-label="Lihat password">
                    <i class="ti ti-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="gs-btn gs-btn-primary gs-btn-block">
            <i class="ti ti-login"></i> Masuk
        </button>
    </form>

    <a href="{{ route('home') }}" class="gs-auth-back">
        <i class="ti ti-arrow-left"></i> Kembali ke Beranda
    </a>

    <script>
    (function () {
        var btn = document.getElementById('togglePassword');
        var pwd = document.getElementById('password');
        if (!btn || !pwd) return;
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var reveal = pwd.type === 'password';
            pwd.type = reveal ? 'text' : 'password';
            btn.querySelector('i').className = reveal ? 'ti ti-eye-off' : 'ti ti-eye';
        });
    })();
    </script>
</x-auth>
