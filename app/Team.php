<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Webhook;
use App\User;
class Team extends Model
{
    protected $table = 'teams';

    protected $filable = ['slack_id', 'webhook_id', 'team_name', 'access_token'];

    public function webhook()
    {
        return $this->hasOne('App\Webhook');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
