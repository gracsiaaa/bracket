<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esports Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/minimal.css') }}">
</head>
<body>
    <div class="max-w-7xl mx-auto p-8 pt-12">
        <div class="flex flex-col md:flex-row justify-between items-center mb-16 border-b border-slate-700 pb-8">
            <div class="text-center md:text-left mb-6 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-100 tracking-tight mb-2">Esports Hub.</h1>
                <p class="text-slate-400 font-medium text-lg">Professional Tournament Bracket System</p>
            </div>
            
            <div class="text-center md:text-right">
                @auth
                    <div class="panel-card py-3 px-6 inline-block border-slate-700">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Active Session</p>
                        <p class="text-slate-200 font-semibold mb-3">{{ Auth::user()->username }}</p>
                        <div class="flex gap-3 justify-center md:justify-end">
                            @if(Auth::user()->is_superadmin)
                                <a href="{{ route('superadmin.index') }}" class="primary-btn !bg-red-600">System Admin</a>
                            @endif
                            <a href="{{ route('tournaments.index') }}" class="primary-btn">Dashboard</a>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="primary-btn px-8 py-3 text-lg">Sign In / Register</a>
                @endauth
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if($tournaments->count() > 0)
                @foreach($tournaments as $tournament)
                    <a href="{{ route('tournaments.public', $tournament->id) }}" class="panel-card block transition transform hover:-translate-y-1 hover:border-blue-500 group">
                        <div class="flex justify-between items-center mb-6">
                            <span class="bg-slate-800 border border-slate-700 text-slate-300 px-3 py-1 rounded-md text-xs font-bold">{{ $tournament->game }}</span>
                            <span class="text-xs font-bold uppercase {{ $tournament->status == 'completed' ? 'text-green-500' : 'text-blue-500' }}">{{ $tournament->status }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-slate-100 mb-2 group-hover:text-blue-400 transition">{{ $tournament->name }}</h2>
                        <p class="text-sm font-medium text-slate-500">Participants: {{ $tournament->teams->count() }}/{{ $tournament->max_teams }}</p>
                    </a>
                @endforeach
            @else
                <div class="col-span-full panel-card text-center py-16">
                    <h2 class="text-lg font-bold text-slate-400 mb-2">No Active Tournaments</h2>
                    <p class="text-slate-500 text-sm">Waiting for operators to initialize new brackets.</p>
                </div>
            @endif
        </div>

        <div class="mt-24 text-center text-xs font-semibold text-slate-600 uppercase tracking-widest">
            Powered by Reality Code
        </div>
    </div>
</body>
</html>