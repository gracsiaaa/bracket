<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/minimal.css') }}">
</head>
<body>
    <div class="max-w-6xl mx-auto p-8 pt-10">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 border-b border-slate-700 pb-6 gap-4 sm:gap-0">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-100 tracking-tight">Dashboard</h1>
                <p class="text-sm font-medium text-slate-400 mt-1">Operator: {{ Auth::user()->username }}</p>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('landing') }}" class="primary-btn !bg-slate-700 flex-1 sm:flex-none text-center">Main Menu</a>
                <form action="{{ route('logout') }}" method="POST" class="flex-1 sm:flex-none flex">
                    @csrf
                    <button type="submit" class="primary-btn !bg-red-600 w-full text-center">Sign Out</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-900/40 text-green-400 p-4 rounded-lg border border-green-800/50 mb-8 text-sm font-semibold">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="panel-card">
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6">Create New</h2>
                    <form action="{{ route('tournaments.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2">Tournament Name</label>
                            <input type="text" name="name" required class="form-input">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2">Game Title</label>
                            <input type="text" name="game" required class="form-input">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2">Team Slots</label>
                            <select name="max_teams" required class="form-input !text-slate-200">
                                <option value="4">4 Teams</option>
                                <option value="8">8 Teams</option>
                                <option value="16">16 Teams</option>
                                <option value="32">32 Teams</option>
                            </select>
                        </div>
                        <button type="submit" class="primary-btn w-full py-3 mt-2">Initialize</button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="panel-card min-h-full">
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6">Active Databases</h2>
                    
                    @if($tournaments->count() > 0)
                        <div class="space-y-3">
                            @foreach($tournaments as $tournament)
                                <div class="border border-slate-700 p-4 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center bg-slate-800/50 hover:bg-slate-800 transition gap-4 sm:gap-0">
                                    <div>
                                        <div class="flex items-center gap-3 mb-1">
                                            <h3 class="font-bold text-slate-200 text-lg">{{ $tournament->name }}</h3>
                                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded border {{ $tournament->status == 'completed' ? 'text-green-500 border-green-500/50 bg-green-900/20' : 'text-blue-500 border-blue-500/50 bg-blue-900/20' }}">{{ $tournament->status }}</span>
                                        </div>
                                        <p class="text-xs font-medium text-slate-400">{{ $tournament->game }} &bull; {{ $tournament->max_teams }} Slots</p>
                                    </div>
                                    <div class="flex gap-2 w-full sm:w-auto">
                                        <a href="{{ route('tournaments.show', $tournament->id) }}" class="flex-1 sm:flex-none text-center bg-blue-900/50 text-blue-400 hover:bg-blue-600 hover:text-white px-4 py-2 rounded text-xs font-bold transition border border-blue-800/50">Manage</a>
                                        <form action="{{ route('tournaments.destroy', $tournament->id) }}" method="POST" onsubmit="return confirm('Delete this tournament permanently?');" class="flex-1 sm:flex-none flex">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full bg-red-900/50 text-red-400 hover:bg-red-600 hover:text-white px-4 py-2 rounded text-xs font-bold transition border border-red-800/50">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 border-2 border-dashed border-slate-700 rounded-lg">
                            <p class="text-sm font-bold text-slate-500">No active tournaments found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>