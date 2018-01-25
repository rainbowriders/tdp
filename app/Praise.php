<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Praise extends Model
{
    protected $table = 'praises';

    protected $filable = ['requester_id', 'awarded_id', 'team_id'];

    public function requester()
    {
        return $this->hasOne('App\User', 'id', 'requester_id');
    }

    public function awarded()
    {
        return $this->hasOne('App\User', 'id', 'awarded_id');
    }
}
