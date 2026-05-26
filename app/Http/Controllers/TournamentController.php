<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function landing()
    {
        $tournaments = Tournament::latest()->get();
        return view('landing', compact('tournaments'));
    }

    public function index()
    {
        $tournaments = Tournament::where('user_id', Auth::id())->latest()->get();
        return view('tournaments.index', compact('tournaments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'game' => 'required|string|max:255',
            'max_teams' => 'required|integer|in:4,8,16,32',
        ]);

        Tournament::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'game' => $request->game,
            'max_teams' => $request->max_teams,
            'status' => 'registration',
        ]);

        return redirect()->back()->with('success', 'NEW TOURNAMENT CREATED!');
    }

    public function show($id)
    {
        $tournament = Tournament::with('teams')->findOrFail($id);

        if ($tournament->user_id !== Auth::id() && !Auth::user()->is_superadmin) {
            abort(403, 'UNAUTHORIZED ACCESS');
        }

        return view('tournaments.show', compact('tournament'));
    }

    public function publicShow($id)
    {
        $tournament = Tournament::with(['teams', 'matches.team1', 'matches.team2'])->findOrFail($id);
        return view('tournaments.public-show', compact('tournament'));
    }

    public function generateBracket($id)
    {
        $tournament = Tournament::with('teams')->findOrFail($id);

        if ($tournament->user_id !== Auth::id() && !Auth::user()->is_superadmin) {
            abort(403, 'UNAUTHORIZED ACCESS');
        }

        if ($tournament->status !== 'registration') {
            return redirect()->back();
        }

        $teams = $tournament->teams->shuffle()->values();
        $slots = $tournament->max_teams;
        $totalRounds = log($slots, 2);

        for ($round = 1; $round <= $totalRounds; $round++) {
            $matchesInRound = $slots / pow(2, $round);

            for ($i = 0; $i < $matchesInRound; $i++) {
                if ($round == 1) {
                    $team1 = $teams[$i * 2]->id ?? null;
                    $team2 = $teams[$i * 2 + 1]->id ?? null;
                } else {
                    $team1 = null;
                    $team2 = null;
                }

                $tournament->matches()->create([
                    'round' => $round,
                    'team1_id' => $team1,
                    'team2_id' => $team2,
                ]);
            }
        }

        $tournament->update(['status' => 'ongoing']);
        return redirect()->back()->with('success', 'FULL BRACKET GENERATED!');
    }

    public function updateScore(Request $request, $matchId)
    {
        $request->validate([
            'team1_score' => 'nullable|integer',
            'team2_score' => 'nullable|integer',
            'winner_id' => 'nullable|exists:teams,id'
        ]);

        $match = TournamentMatch::findOrFail($matchId);
        $tournament = Tournament::findOrFail($match->tournament_id);

        if ($tournament->user_id !== Auth::id() && !Auth::user()->is_superadmin) {
            abort(403, 'UNAUTHORIZED ACCESS');
        }

        $match->update([
            'team1_score' => $request->team1_score,
            'team2_score' => $request->team2_score,
            'winner_id' => $request->winner_id
        ]);

        $currentRound = $match->round;
        $totalRounds = log($tournament->max_teams, 2);

        if ($currentRound < $totalRounds && $request->winner_id) {
            $roundMatches = $tournament->matches()->where('round', $currentRound)->orderBy('id')->get();
            $nextRoundMatches = $tournament->matches()->where('round', $currentRound + 1)->orderBy('id')->get();

            $matchIndex = $roundMatches->search(fn($m) => $m->id === $match->id);
            $targetMatchIndex = floor($matchIndex / 2);

            if (isset($nextRoundMatches[$targetMatchIndex])) {
                $targetMatch = $nextRoundMatches[$targetMatchIndex];

                if ($matchIndex % 2 == 0) {
                    $targetMatch->update(['team1_id' => $request->winner_id]);
                } else {
                    $targetMatch->update(['team2_id' => $request->winner_id]);
                }
            }
        }

        if ($currentRound == $totalRounds && $request->winner_id) {
            $tournament->update(['status' => 'completed']);
        } elseif ($currentRound == $totalRounds && !$request->winner_id) {
            $tournament->update(['status' => 'ongoing']);
        }

        return redirect()->back()->with('success', 'RESULT SAVED & BRACKET UPDATED!');
    }

    public function destroy($id)
    {
        $tournament = Tournament::findOrFail($id);

        if ($tournament->user_id !== Auth::id() && !Auth::user()->is_superadmin) {
            abort(403, 'UNAUTHORIZED ACCESS');
        }

        $tournament->delete();
        return redirect()->back()->with('success', 'TOURNAMENT DELETED SUCCESSFULLY!');
    }
}
