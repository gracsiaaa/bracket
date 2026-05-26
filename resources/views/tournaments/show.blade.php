<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage - {{ $tournament->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/minimal.css') }}">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0f172a;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>
<body>
    <div class="max-w-[95vw] mx-auto p-6">
        <div class="flex justify-between items-center mb-8 border-b border-slate-700 pb-4">
            <a href="{{ route('tournaments.index') }}" class="primary-btn !bg-slate-700">&larr; Back to Panel</a>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-slate-100">{{ $tournament->name }}</h1>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-1">Game: {{ $tournament->game }} | Status: <span class="{{ $tournament->status == 'completed' ? 'text-green-500' : 'text-blue-500' }}">{{ $tournament->status }}</span></p>
            </div>
            
            <div class="flex gap-2">
                <button onclick="copyLink(this, '{{ route('tournaments.public', $tournament->id) }}')" class="primary-btn !bg-slate-700 text-white flex items-center gap-2 hover:!bg-slate-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    <span>Copy Link</span>
                </button>
                <a href="{{ route('tournaments.public', $tournament->id) }}" target="_blank" class="primary-btn !bg-blue-600 text-white">Live View &rarr;</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-900/40 text-green-400 p-3 rounded-md border border-green-800/50 mb-6 text-sm font-medium">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-900/40 text-red-400 p-3 rounded-md border border-red-800/50 mb-6 text-sm font-medium">{{ session('error') }}</div>
        @endif

        @if($tournament->status === 'registration')
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-12 items-start">
                <div class="panel-card md:col-span-4 sticky top-6">
                    <h2 class="text-sm font-bold text-slate-400 mb-4 uppercase">Register Team ({{ $tournament->teams->count() }}/{{ $tournament->max_teams }})</h2>
                    @if($tournament->teams->count() < $tournament->max_teams)
                        <form action="{{ route('teams.store', $tournament->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="text" name="name" placeholder="Team Name..." required class="form-input py-3">
                            <button type="submit" class="primary-btn w-full py-3">Add Team</button>
                        </form>
                    @else
                        <div class="text-center text-sm font-semibold text-orange-400 bg-orange-900/30 p-4 rounded-md border border-orange-800/50">Maximum Slots Reached!</div>
                    @endif
                </div>

                <div class="panel-card md:col-span-8">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-700/50 pb-4">
                        <h2 class="text-sm font-bold text-slate-400 uppercase">Roster List</h2>
                        @if($tournament->teams->count() >= 2)
                            <form action="{{ route('tournaments.generate', $tournament->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="primary-btn !py-2 !px-4 !text-xs bg-blue-600 hover:bg-blue-500">Generate Bracket</button>
                            </form>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($tournament->teams as $team)
                            <div class="bg-slate-800/50 border border-slate-700 hover:border-slate-500 transition-colors duration-200 p-3 rounded-lg text-sm font-medium text-slate-300 text-center truncate cursor-default shadow-sm">
                                {{ $team->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if($tournament->matches->count() > 0)
            <div class="panel-card overflow-hidden">
                <div class="bracket-wrapper">
                    @php
                        $matchesByRound = $tournament->matches->groupBy('round');
                        $totalRounds = $matchesByRound->count();
                    @endphp

                    @foreach($matchesByRound as $round => $matches)
                        @php
                            $matchCount = $matches->count();
                            if ($matchCount == 1) {
                                $roundName = $tournament->status === 'completed' ? 'Champion' : 'Final';
                            } elseif ($matchCount == 2) {
                                $roundName = 'Semifinal';
                            } elseif ($matchCount == 4) {
                                $roundName = 'Quarter Final';
                            } else {
                                $roundName = 'Round ' . $round;
                            }
                        @endphp

                        <div class="bracket-round">
                            <div class="round-title">{{ $roundName }}</div>

                            @foreach($matches as $match)
                                <div class="match-wrapper {{ $loop->iteration % 2 != 0 ? 'connect-down' : 'connect-up' }}">
                                    <div class="match-box">
                                        <form action="{{ route('matches.updateScore', $match->id) }}" method="POST">
                                            @csrf
                                            <div class="team-row {{ $match->winner_id == $match->team1_id ? 'winner' : '' }}">
                                                <span class="truncate pr-2 {{ !$match->team1 ? 'text-slate-500 italic' : '' }}">
                                                    {{ $match->team1 ? $match->team1->name : ($round == 1 ? 'BYE' : 'TBD') }}
                                                </span>
                                                @if($match->team1)
                                                    <input type="number" name="team1_score" value="{{ $match->team1_score }}" class="score-input">
                                                @endif
                                            </div>
                                            <div class="team-row {{ $match->winner_id == $match->team2_id && $match->team2_id != null ? 'winner' : '' }}">
                                                <span class="truncate pr-2 {{ !$match->team2 ? 'text-slate-500 italic' : '' }}">
                                                    {{ $match->team2 ? $match->team2->name : ($round == 1 ? 'BYE' : 'TBD') }}
                                                </span>
                                                @if($match->team2)
                                                    <input type="number" name="team2_score" value="{{ $match->team2_score }}" class="score-input">
                                                @endif
                                            </div>
                                            
                                            @if($match->team1 || $match->team2)
                                                <div class="px-2 py-2 bg-slate-900/50 flex gap-2 border-t border-slate-700">
                                                    <select name="winner_id" class="flex-1">
                                                        <option value="">- WINNER -</option>
                                                        @if($match->team1)<option value="{{ $match->team1_id }}" {{ $match->winner_id == $match->team1_id ? 'selected' : '' }}>{{ $match->team1->name }}</option>@endif
                                                        @if($match->team2)<option value="{{ $match->team2_id }}" {{ $match->winner_id == $match->team2_id ? 'selected' : '' }}>{{ $match->team2->name }}</option>@endif
                                                    </select>
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-[11px] px-3 py-1 rounded transition font-semibold">Save</button>
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function copyLink(button, url) {
            const fullUrl = window.location.origin + url;
            
            navigator.clipboard.writeText(fullUrl).then(() => {
                const textSpan = button.querySelector('span');
                const originalText = textSpan.innerText;
                
                textSpan.innerText = 'Copied!';
                button.classList.remove('!bg-slate-700');
                button.classList.add('!bg-green-600');
                
                setTimeout(() => {
                    textSpan.innerText = originalText;
                    button.classList.remove('!bg-green-600');
                    button.classList.add('!bg-slate-700');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                alert('Gagal menyalin link.');
            });
        }
    </script>
</body>
</html>