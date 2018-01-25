<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\App;
use GuzzleHttp;
use App\User;
use App\Webhook;
use App\Team;
class AuthController extends Controller
{
    public function __construct()
    {
        $this->credentials = App::find(1);
        $this->client = new GuzzleHttp\Client();
    }
    public function getAuth(Request $request)
    {
        //credentials for basic slack auth
        $client_secret = $this->credentials->client_secret;
        $client_id = $this->credentials->client_id;
        $url = 'https://slack.com/api/oauth.access?client_secret=' . $client_secret . '&client_id=' . $client_id . '&code=' . $request->get('code');

        $authReq = $this->client->request('GET', $url);

        //save in DB basic credentials
        $slackUser = json_decode($authReq->getBody(), true);
        if($slackUser['ok'] == false) {
            \Session::put('error_message', 'Invalid credentials!Please try again!');
            return redirect()->route('home');
        } else {
            $team = Team::where('slack_id', $slackUser['team_id'])->first();
            if(!$team){
                $team = new Team();
                $team->slack_id = $slackUser['team_id'];
            }
            $team->team_name = $slackUser['team_name'];
            $team->access_token = $slackUser['access_token'];
            $team->save();

            $slackUserWebhook = $slackUser['incoming_webhook'];

            $webhook = Webhook::where('team_id', $team->id)->first();
            if(!$webhook){
                $webhook = new Webhook();
                $webhook->team_id = $team->id;
            }
            $webhook->channel = $slackUserWebhook['channel'];
            $webhook->channel_id = $slackUserWebhook['channel_id'];
            $webhook->configuration_url = $slackUserWebhook['configuration_url'];
            $webhook->url = $slackUserWebhook['url'];
            $webhook->save();

            $team->webhook_id = $webhook->id;
            $team->save();
            \Session::put('success_message', 'Successfully add the app to your team directory!');
            return redirect('/#commands-section');
        }
    }
}
