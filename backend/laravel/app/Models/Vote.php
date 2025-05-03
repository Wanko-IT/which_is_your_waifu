<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'character_id',
        'user_ip',
        'user_agent',
    ];

    /**
     * Get the character that owns the vote.
     */
    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
