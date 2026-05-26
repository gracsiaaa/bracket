<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function index()
    {
        if (!Auth::user()->is_superadmin) {
            return redirect()->route('landing');
        }

        $users = User::withCount('tournaments')->get();
        $tournaments = Tournament::with('user')->latest()->get();

        return view('superadmin.index', compact('users', 'tournaments'));
    }

    public function destroyTournament($id)
    {
        if (!Auth::user()->is_superadmin) return abort(403);

        Tournament::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'TOURNAMENT DELETED FROM SYSTEM!');
    }

    public function destroyUser($id)
    {
        if (!Auth::user()->is_superadmin) return abort(403);

        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'CANNOT DELETE YOURSELF!');
        }

        $user->delete();
        return redirect()->back()->with('success', 'PLAYER BANNED AND DATA WIPED!');
    }
}
