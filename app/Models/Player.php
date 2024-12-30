<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'dob',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_player')->withPivot('sort_order')->withTimestamps();
    }
}
