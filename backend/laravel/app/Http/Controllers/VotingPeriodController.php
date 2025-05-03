<?php

namespace App\Http\Controllers;

use App\Models\VotingPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VotingPeriodController extends Controller
{
    /**
     * Get the current active voting period.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCurrent()
    {
        $now = now();
        $votingPeriod = VotingPeriod::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();

        if (!$votingPeriod) {
            return response()->json(['message' => 'No active voting period found'], 404);
        }

        return response()->json($votingPeriod);
    }

    /**
     * Get all voting periods.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $votingPeriods = VotingPeriod::orderBy('start_date', 'desc')->get();
        
        foreach ($votingPeriods as $period) {
            $period->total_votes = $period->votes()->count();
        }
        
        return response()->json($votingPeriods);
    }

    /**
     * Store a newly created voting period in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $votingPeriod = VotingPeriod::create($request->all());

        return response()->json($votingPeriod, 201);
    }

    /**
     * Display the specified voting period.
     *
     * @param  \App\Models\VotingPeriod  $votingPeriod
     * @return \Illuminate\Http\Response
     */
    public function show(VotingPeriod $votingPeriod)
    {
        return response()->json($votingPeriod);
    }

    /**
     * Update the specified voting period in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VotingPeriod  $votingPeriod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VotingPeriod $votingPeriod)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $votingPeriod->update($request->all());

        return response()->json($votingPeriod);
    }

    /**
     * Remove the specified voting period from storage.
     *
     * @param  \App\Models\VotingPeriod  $votingPeriod
     * @return \Illuminate\Http\Response
     */
    public function destroy(VotingPeriod $votingPeriod)
    {
        $votingPeriod->delete();

        return response()->json(['message' => 'Voting period deleted successfully']);
    }

    /**
     * Get voting results for a specific period.
     *
     * @param  \App\Models\VotingPeriod  $votingPeriod
     * @return \Illuminate\Http\Response
     */
    public function getResults(VotingPeriod $votingPeriod)
    {
        $results = $votingPeriod->getResults();
        
        return response()->json($results);
    }
}
