<x-auth>
    <a href="{{ route('home') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
        <img src="{{ asset('assets/images/logo.png') }}" alt="" width="150">
    </a>
    {{ auth()->user() }}
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
            @error('username')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
            @error('password')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Log-In</button>
    </form>
</x-auth>