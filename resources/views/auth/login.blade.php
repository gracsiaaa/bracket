<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/minimal.css') }}">
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-sm p-4">
        <div class="panel-card p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-extrabold text-slate-100 tracking-tight">Welcome Back</h1>
                <p class="text-sm text-slate-400 mt-2">Sign in to manage your tournaments</p>
            </div>

            @if(session('error'))
                <div class="bg-red-900/50 text-red-400 p-3 rounded-lg text-sm font-medium mb-6 text-center border border-red-800/50">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Username</label>
                    <input type="text" name="username" required class="form-input" autocomplete="off">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" required class="form-input">
                </div>
                <button type="submit" class="primary-btn w-full py-3 mt-4">Sign In</button>
            </form>
            
            <div class="mt-8 text-center text-sm">
                <span class="text-slate-500">New operator?</span>
                <a href="{{ route('register') }}" class="font-bold text-blue-500 hover:text-blue-400 ml-1">Create account</a>
            </div>
        </div>
    </div>
</body>
</html>