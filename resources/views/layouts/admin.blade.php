<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - SMK Muda Bawean</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-smk.png') }}">
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full text-slate-100 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between shrink-0">
        <div>
            <!-- Branding -->
            <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-950/50">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-black tracking-tight text-white flex items-center gap-1.5">
                    <span class="bg-blue-500 w-3 h-3 rounded-full animate-pulse"></span>
                    <span>Admin<span class="text-blue-500">Muda</span></span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1" aria-label="Navigasi admin">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.posts.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200 {{ request()->routeIs('admin.posts.*') ? 'bg-slate-800 text-white' : '' }}">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2m-2 3h2a2 2 0 002-2V8a2 2 0 00-2-2h-2m-9 5h4m-4 4h1m1-4l2 2m-2-2v4"></path></svg>
                    Berita
                </a>

                <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200 {{ request()->routeIs('admin.comments.*') ? 'bg-slate-800 text-white' : '' }}">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    Komentar
                </a>

                <a href="{{ route('admin.announcements.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Pengumuman
                </a>

                <a href="{{ route('admin.graduations.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Kelulusan
                </a>

                <a href="{{ route('admin.alumni.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Alumni
                </a>

                <a href="{{ route('admin.ppdb.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    PPDB
                </a>

                <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Pendidik / Guru
                </a>

                <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Halaman Statis
                </a>

                <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    FAQ
                </a>

                <!-- Admin Role restricted Menus -->
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="border-t border-slate-800 my-4 pt-4"></div>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Pengguna (Users)
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition duration-200">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Pengaturan
                    </a>
                @endif
            </nav>
        </div>

        <!-- User profile section with Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/20">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 capitalize">{{ auth()->user()->role }}</p>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" aria-label="Keluar dari sistem" class="p-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-slate-800 transition duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top bar -->
        <header class="h-20 border-b border-slate-800 bg-slate-900/50 flex items-center justify-between px-8">
            <!-- Breadcrumbs -->
            <div>
                @yield('breadcrumbs')
            </div>

            <!-- School name display -->
            <div class="text-sm text-slate-400">
                SMK Muda Bawean
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-grow p-8 overflow-y-auto">
            <!-- Flash Alert messages -->
            @if(session('success'))
                <div role="alert" class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div role="alert" class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
