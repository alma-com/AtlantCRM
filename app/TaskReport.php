<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskReport extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'comment',
        'hours',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
