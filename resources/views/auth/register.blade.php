<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/minimal.css') }}">
</head>
<body class="flex items-center justify-center min-h-screen my-8">
    <div class="w-full max-w-sm p-4">
        <div class="panel-card p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-extrabold text-slate-100 tracking-tight">Create Account</h1>
                <p class="text-sm text-slate-400 mt-2">Initialize your operator profile</p>
            </div>

            @if($errors->any())
                <div class="bg-red-900/50 text-red-400 p-4 rounded-lg text-sm font-medium mb-6 border border-red-800/50">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Username</label>
                    <input type="text" name="username" required class="form-input" autocomplete="off">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" required class="form-input">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="form-input">
                </div>
                <button type="submit" class="primary-btn w-full py-3 mt-4">Register</button>
            </form>
            
            <div class="mt-8 text-center text-sm">
                <span class="text-slate-500">Already have an account?</span>
                <a href="{{ route('login') }}" class="font-bold text-blue-500 hover:text-blue-400 ml-1">Sign In</a>
            </div>
        </div>
    </div>
</body>
</html>