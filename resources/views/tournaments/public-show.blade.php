<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Bracket - {{ $tournament->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/minimal.css') }}">
</head>
<body>
    <div class="max-w-[95vw] mx-auto p-6">
        <div class="text-center relative">
            <a href="{{ route('landing') }}" class="primary-btn !bg-slate-700 absolute left-0 top-2">&larr; Main Menu</a>
            <h1 class="text-3xl font-extrabold text-slate-100 tracking-tight">{{ $tournament->name }}</h1>
            <div class="inline-flex mt-3 bg-slate-800 border border-slate-700 rounded-full px-4 py-1.5 text-xs font-semibold text-slate-300 uppercase shadow-sm">
                <span>🎮 {{ $tournament->game }}</span>
                <span class="mx-3 text-slate-600">|</span>
                <span class="{{ $tournament->status == 'completed' ? 'text-green-500' : 'text-blue-500' }}">{{ $tournament->status }}</span>
            </div>
        </div>

        @if($tournament->matches->count() > 0)
            <div class="panel-card mt-8 overflow-hidden">
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
                                <div class="match-wrapper {{ $loop->iteration % 2 != 0 ? 'connect-down' : 'connect-up' }} w-full">
                                    <div class="match-box">
                                        <div class="team-row {{ $match->winner_id == $match->team1_id ? 'winner' : ($match->winner_id ? 'loser' : '') }}">
                                            <span class="truncate pr-2 {{ !$match->team1 ? 'text-slate-500 italic' : '' }}">
                                                {{ $match->team1 ? $match->team1->name : ($round == 1 ? 'BYE' : 'TBD') }}
                                            </span>
                                            <span class="score {{ $match->winner_id == $match->team1_id ? 'winner-score' : '' }}">{{ $match->team1_score ?? '-' }}</span>
                                        </div>
                                        <div class="team-row {{ $match->winner_id == $match->team2_id && $match->team2_id != null ? 'winner' : ($match->winner_id ? 'loser' : '') }}">
                                            <span class="truncate pr-2 {{ !$match->team2 ? 'text-slate-500 italic' : '' }}">
                                                {{ $match->team2 ? $match->team2->name : ($round == 1 ? 'BYE' : 'TBD') }}
                                            </span>
                                            <span class="score {{ $match->winner_id == $match->team2_id ? 'winner-score' : '' }}">{{ $match->team2_score ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mt-16 text-center">
                <div class="inline-block bg-slate-800 p-10 rounded-lg border border-slate-700 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-400 mb-2">Awaiting Bracket Generation</h2>
                    <p class="text-sm text-slate-500">The operator has not finalized the teams for this tournament yet.</p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>