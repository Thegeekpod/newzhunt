<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    protected $fillable = [
        'poll_id',
        'option_text',
        'vote_count'
    ];

    protected $casts = [
        'vote_count' => 'integer'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }
}
