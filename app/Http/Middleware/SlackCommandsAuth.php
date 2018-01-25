<?php

namespace App\Http\Middleware;

use Closure;
use App\App;
class SlackCommandsAuth
{

    public function __construct()
    {
        $app = App::find(1);
        $this->slackToken = $app->verification_token;
    }

    public function handle($request, Closure $next)
    {
        if($request->get('token') != $this->slackToken){
            return response('Unauthorized! ' . $this->slackToken . ' ' . $request->get('token'), 401);
        }

        return $next($request);
    }
}
