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
            <a href="{{ route('landing') }}" class="primary-btn !bg-slate-700 absolute left-0 top-2 hidden md:inline-block">&larr; Main Menu</a>
            <a href="{{ route('landing') }}" class="primary-btn !bg-slate-700 absolute left-0 top-2 md:hidden">&larr;</a>
            
            <button onclick="copyLink(this, window.location.href)" class="primary-btn !bg-slate-800 text-slate-300 border border-slate-700 absolute right-0 top-2 flex items-center gap-2 hover:!bg-slate-700 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                <span class="hidden sm:inline-block">Share Bracket</span>
            </button>

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

<script>
        function copyLink(button, urlToCopy) {
            navigator.clipboard.writeText(urlToCopy).then(() => {
                const textSpan = button.querySelector('span');
                const originalText = textSpan.innerText;
                
                textSpan.innerText = 'Copied!';
                button.classList.remove('!bg-slate-700', '!bg-slate-800', 'text-slate-300');
                button.classList.add('!bg-green-600', 'text-white', 'border-green-500');
                
                setTimeout(() => {
                    textSpan.innerText = originalText;
                    button.classList.remove('!bg-green-600', 'text-white', 'border-green-500');
                    if(urlToCopy.includes('public')) {
                        button.classList.add('!bg-slate-800', 'text-slate-300');
                    } else {
                        button.classList.add('!bg-slate-700');
                    }
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                alert('Browser tidak mengizinkan akses clipboard.');
            });
        }
    </script>
</body>
</html>