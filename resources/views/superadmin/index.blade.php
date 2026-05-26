<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Administration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/minimal.css') }}">
</head>
<body>
    <div class="max-w-7xl mx-auto p-8 pt-10">
        <div class="flex justify-between items-center mb-10 border-b border-slate-700 pb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-red-500 tracking-tight">System Administration</h1>
                <p class="text-sm font-medium text-slate-400 mt-1">Root Access Control</p>
            </div>
            <a href="{{ route('landing') }}" class="primary-btn !bg-slate-700">Main Menu</a>
        </div>

        @if(session('success'))
            <div class="bg-green-900/40 text-green-400 p-4 rounded-lg border border-green-800/50 mb-8 text-sm font-semibold">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-900/40 text-red-400 p-4 rounded-lg border border-red-800/50 mb-8 text-sm font-semibold">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="panel-card border-t-4 border-t-red-500">
                <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6">Operator Database</h2>
                <div class="space-y-3">
                    @foreach($users as $user)
                        <div class="border border-slate-700 p-4 rounded-lg flex justify-between items-center bg-slate-800/50 hover:bg-slate-800 transition">
                            <div>
                                <h3 class="font-bold text-slate-200 text-lg">
                                    {{ $user->username }} 
                                    {!! $user->is_superadmin ? '<span class="text-[10px] text-red-500 ml-2 uppercase border border-red-500/50 px-2 py-0.5 rounded font-bold bg-red-900/20">Root</span>' : '' !!}
                                </h3>
                                <p class="text-xs font-medium text-slate-400 mt-1">Hosting: {{ $user->tournaments_count }} tournaments</p>
                            </div>
                            @if(!$user->is_superadmin)
                                <form action="{{ route('superadmin.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Wipe this operator and all related data?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-900/50 text-red-400 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded text-xs font-bold transition border border-red-800/50">Wipe</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel-card border-t-4 border-t-blue-500">
                <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6">Global Tournaments</h2>
                <div class="space-y-3">
                    @foreach($tournaments as $tournament)
                        <div class="border border-slate-700 p-4 rounded-lg flex justify-between items-center bg-slate-800/50 hover:bg-slate-800 transition">
                            <div>
                                <h3 class="font-bold text-slate-200 text-lg">{{ $tournament->name }}</h3>
                                <p class="text-xs font-medium text-slate-400 mt-1">Operator: <span class="font-bold text-slate-300">{{ $tournament->user->username }}</span> &bull; {{ $tournament->game }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('tournaments.show', $tournament->id) }}" class="bg-blue-900/50 text-blue-400 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded text-xs font-bold transition border border-blue-800/50 text-center flex items-center">Manage</a>
                                <form action="{{ route('superadmin.tournament.destroy', $tournament->id) }}" method="POST" onsubmit="return confirm('Delete this tournament permanently?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-900/50 text-red-400 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded text-xs font-bold transition border border-red-800/50 flex items-center">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>