<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    /**
     * Store a newly created vote in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'character_id' => 'required|exists:characters,id',
        ]);

        $now = now();
        $votingPeriod = \App\Models\VotingPeriod::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();

        $vote = new Vote();
        $vote->character_id = $request->character_id;
        $vote->user_ip = $request->ip();
        $vote->user_agent = $request->userAgent();
        
        if ($votingPeriod) {
            $vote->voting_period_id = $votingPeriod->id;
        }
        
        $vote->save();

        return response()->json(['message' => 'Vote recorded successfully'], 201);
    }

    /**
     * Get votes for a specific character.
     *
     * @param  \App\Models\Character  $character
     * @return \Illuminate\Http\Response
     */
    public function getVotesByCharacter(Character $character)
    {
        $votes = $character->votes()->count();
        return response()->json(['character' => $character->name, 'votes' => $votes]);
    }

    /**
     * Get voting results for all characters.
     *
     * @return \Illuminate\Http\Response
     */
    public function getResults()
    {
        $results = Character::select('characters.id', 'characters.name', 'characters.image_url', DB::raw('COUNT(votes.id) as vote_count'))
            ->leftJoin('votes', 'characters.id', '=', 'votes.character_id')
            ->groupBy('characters.id', 'characters.name', 'characters.image_url')
            ->orderBy('vote_count', 'desc')
            ->get();

        return response()->json($results);
    }
}
