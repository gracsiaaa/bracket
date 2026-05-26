<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function store(Request $request, $tournamentId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
        ]);

        $tournament = Tournament::findOrFail($tournamentId);

        $tournament->teams()->create([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', 'Tim berhasil didaftarkan ke turnamen!');
    }
}