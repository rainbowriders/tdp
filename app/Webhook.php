<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Team;

class Webhook extends Model
{
    protected $table = 'webhooks';

    protected $filable = ['channel', 'channel_id', 'configuration_url', 'url', 'team_id'];

    public function team()
    {
        return $this->belongsTo('App\Team');
    }
}
