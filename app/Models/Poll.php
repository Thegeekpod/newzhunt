<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'question',
        'is_active',
        'total_votes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_votes' => 'integer'
    ];

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }
}
