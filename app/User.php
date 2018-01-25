<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Team;

class User extends Model
{
    protected $table = 'users';

    protected $filable = ['slack_id', 'team_id', 'name'];

    public function team()
    {
        return $this->belongsTo('App\Team');
    }

}
