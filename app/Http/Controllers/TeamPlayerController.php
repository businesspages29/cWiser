<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamPlayerController extends Controller
{
    public function index()
    {
        $teams = Team::with('players')->get();
        $unassignedPlayers = Player::whereDoesntHave('teams')->get();

        return response()->json([
            'teams' => $teams,
            'unassignedPlayers' => $unassignedPlayers,
        ]);
    }

    public function saveAssignments(Request $request)
    {
        $assignments = $request->input('assignments');
        foreach ($assignments as $teamId => $playerIds) {
            $sortOrder = 0;
            foreach ($playerIds as $playerId) {
                DB::table('team_player')->updateOrInsert(
                    ['team_id' => $teamId, 'player_id' => $playerId],
                    ['sort_order' => $sortOrder++]
                );
            }
        }

        return response()->json(['message' => 'Assignments saved successfully.']);
    }
}
