<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin — SMK Muda Bawean</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="h-full flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo / Branding -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 text-2xl font-black text-white tracking-tight mb-2">
                <span class="bg-blue-500 w-3 h-3 rounded-full animate-pulse"></span>
                <span>Admin<span class="text-blue-500">Muda</span></span>
            </div>
            <p class="text-sm text-slate-400">Panel Administrasi SMK Muda Bawean</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl shadow-slate-950/50 space-y-6">
            <div>
                <h1 class="text-xl font-bold text-white">Masuk ke Panel Admin</h1>
                <p class="text-xs text-slate-400 mt-1">Masukkan kredensial akun Anda untuk melanjutkan.</p>
            </div>

            @if ($errors->any())
                <div role="alert" class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form id="login-form" action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 mb-1.5">Alamat Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        autocomplete="username"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-slate-950 border border-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-xl text-white text-sm outline-none transition duration-150"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 mb-1.5">Kata Sandi</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full px-4 py-3 bg-slate-950 border border-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-xl text-white text-sm outline-none transition duration-150"
                    >
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded accent-blue-500">
                    <label for="remember" class="text-sm text-slate-400 select-none">Ingat saya</label>
                </div>

                <button
                    type="submit"
                    class="w-full py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition duration-150 shadow-lg shadow-blue-500/20 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900"
                >
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-600 mt-6">
            &copy; {{ date('Y') }} SMK Muda Bawean. Hak cipta dilindungi.
        </p>
    </div>

</body>
</html>
