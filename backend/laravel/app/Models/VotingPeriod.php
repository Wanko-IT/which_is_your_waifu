<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VotingPeriod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the votes for this voting period.
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get the results for this voting period.
     */
    public function getResults()
    {
        return Character::select('characters.id', 'characters.name', 'characters.image_url', DB::raw('COUNT(votes.id) as vote_count'))
            ->leftJoin('votes', 'characters.id', '=', 'votes.character_id')
            ->where('votes.created_at', '>=', $this->start_date)
            ->where('votes.created_at', '<=', $this->end_date)
            ->groupBy('characters.id', 'characters.name', 'characters.image_url')
            ->orderBy('vote_count', 'desc')
            ->get();
    }
}
