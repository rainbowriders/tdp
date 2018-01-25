<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use GuzzleHttp;
use App\Http\Requests;
use Validator;
use App\User;
use App\Team;
use App\Praise;
use App\Webhook;
use Carbon\Carbon;
class PraiseController extends Controller
{


    public function __construct()
    {
        $this->teamUsersUrl = "https://slack.com/api/users.list?token=";
        $this->client = new GuzzleHttp\Client();
    }

    public function postPraise(Request $request)
    {
        //check if not given awarded username break
        $validator = Validator::make($request->all(), [
            'text' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response('The name of the awarded user is required', 200);
        }
        $team = Team::where('slack_id', $request->get('team_id'))->with('webhook')->with('users')->first();
        //get info about all slack members in this team
        $teamUsersInfo = $this->client->request('GET', $this->teamUsersUrl . $team->access_token);
        $teamUsersInfoResponse = json_decode($teamUsersInfo->getBody(), true);
        
        if($teamUsersInfoResponse['ok'] == false) {
            return response('Unauthorized.', 401);
        }
        $members = $teamUsersInfoResponse['members'];



        $requester = User::where('team_id', $team->id)->where('slack_id', $request->get('user_id'))->first();

        //add to DB requester if not exist
        if(!$requester) {
            $requester = new User();
            $requester->slack_id = $request->get('user_id');
            $requester->name = $request->get('user_name');
            $requester->team_id = $team->id;
            $requester->save();
        }


        //check if memeber exist in slack data
        $isTarget = false;
        $targetUserName = ltrim(strtolower($request->get('text')), '@');

        foreach($members as $member) {
            //if target user exist and not deleted return true
            if($member['name'] == $targetUserName && $member['deleted'] == false) {

                if($member['id'] == $request->get('user_id')) {
                    return response("You can not reward yourself!", 200);
                }
                $isTarget = true;

                //save user if not exist in DB
                $targetMember = User::where('team_id', $team->id)->where('slack_id', $member['id'])->first();
                if(!$targetMember){
                    $targetMember = new User();
                    $targetMember->slack_id = $member['id'];
                    $targetMember->name = $member['name'];
                    $targetMember->team_id = $team->id;
                    $targetMember->save();
                }

                $today = Carbon::today();

                $praise = Praise::where('team_id', $team->id)
                    ->where('requester_id', $requester->id)
                    ->where('awarded_id', $targetMember->id)
                    ->with('requester')
                    ->with('awarded')
                    ->whereDay('created_at', '=', $today->day)
                    ->whereMonth('created_at', '=', $today->month)
                    ->whereYear('created_at', '=', $today->year)
                    ->first();
                if($praise) {
                    return response('You can not reward more than once a day ' . $targetMember->name, 200);
                }
                $praise = new Praise();
                $praise->requester_id = $requester->id;
                $praise->awarded_id = $targetMember->id;
                $praise->team_id = $team->id;
                $praise->save();

                $text = ltrim($request->get('text'), '@') . ' was praised by ' . $request->get('user_name') . ' successfully!';

                $this->client->request('POST', $team->webhook->url, ['body' =>
                    json_encode(array('text' => $text))]);
                return;

            }
        }

        if($isTarget == false) {
            return response($request->get('text') . ' is not part of your team');
        }
    }

    public function postPraiseToday(Request $request){
        $today = Carbon::today();
        $team = Team::where('slack_id', $request->get('team_id'))->first();
        $praises = Praise::where('team_id', $team->id)
            ->whereDay('created_at', '=', $today->day)
            ->whereMonth('created_at', '=', $today->month)
            ->whereYear('created_at', '=', $today->year)
            ->with('requester')
            ->with('awarded')
            ->get();

        $result = array();
        foreach($praises as $praise){
            $awardedName = $praise->awarded['name'];
            $requesterName = $praise->requester['name'];
            if(array_key_exists($awardedName, $result)) {
                array_push($result[$awardedName]['requesters'], $requesterName);
                $result[$awardedName]['total'] ++;
            } else {
                $result[$awardedName] = array();
                $result[$awardedName]['requesters'] = array();
                array_push($result[$awardedName]['requesters'], $requesterName);
                $result[$awardedName]['total'] = 1;
            }
        }
        if(count($result) == 0) {
            return Response::json(array('text' => 'No praises today'), 200);
        }
        $output = array();
        $count = 1;
        array_push($output, 'Today’s praises ');
        foreach($result as $k => $v) {
            $requesterName = $count . '. ' . $k . ' - ';
            $strRequesters = '(by ' . implode(", ", $v['requesters']) . ')';
            $totalPrices = $v['total'];
            $totalStr = $v['total'] <= 1 ? ' Praise' : ' Praises ';
            $finalStr = $requesterName . $totalPrices . $totalStr . $strRequesters;
            $count ++;
            array_push($output, $finalStr);
        }

        return Response::json(array('text' => implode("\n ", $output)), 200);

    }

    public function postPraiseYesterday(Request $request)
    {
        $team = Team::where('slack_id', $request->get('team_id'))->first();
        $yesterday = Carbon::yesterday();
        $praises = Praise::where('team_id', $team->id)
            ->whereDay('created_at', '=' , $yesterday->day)
            ->whereMonth('created_at', '=', $yesterday->month)
            ->whereYear('created_at', '=', $yesterday->year)
            ->with('requester')
            ->with('awarded')
            ->get();
        if(count($praises) == 0) {
            return Response::json(array('text' => 'No praises yesterday'), 200);
        }
        $result = array();
        foreach($praises as $praise){
            $awardedName = $praise->awarded['name'];
            $requesterName = $praise->requester['name'];
            if(array_key_exists($awardedName, $result)) {
                array_push($result[$awardedName]['requesters'], $requesterName);
                $result[$awardedName]['total'] ++;
            } else {
                $result[$awardedName] = array();
                $result[$awardedName]['requesters'] = array();
                array_push($result[$awardedName]['requesters'], $requesterName);
                $result[$awardedName]['total'] = 1;
            }
        }

        $output = array();
        $count = 1;
        array_push($output, 'Praise list - Yesterday');
        foreach($result as $k => $v) {
            $requesterName = $count . '. ' . $k . ' - ';
            $strRequesters = '(by ' . implode(", ", $v['requesters']) . ')';
            $totalPrices = $v['total'];
            $totalStr = $v['total'] <= 1 ? ' Praise ' : ' Praises ';
            $finalStr = $requesterName . $totalPrices . $totalStr . $strRequesters;
            $count ++;
            array_push($output, $finalStr);
        }

        return Response::json(array('text' => implode("\n ", $output)), 200);

    }

    public function postLastWeek(Request $request){
        $team = Team::where('slack_id', $request->get('team_id'))->first();

        $allPraises = array();
        for ($i = 0; $i < 7; $i++) {

            $today = Carbon::today();
            $formatedDay = $today->format('l');
            if($formatedDay != 'Monday'){
                $lastMonday = new Carbon('last monday');
            } else {
                $lastMonday = Carbon::today();
            }
            $startDate = $lastMonday->subWeek()->addDays($i);
            $d = $startDate->day;
            $m = $startDate->month;
            $y = $startDate->year;

            $praises = Praise::where('team_id', $team->id)
                ->whereDay('created_at', '=', $d)
                ->whereMonth('created_at', '=', $m)
                ->whereYear('created_at', '=', $y)
                ->with('requester')
                ->with('awarded')
                ->get();
            foreach($praises as $praise){
                array_push($allPraises, $praise);
            }

        }
        if(count($allPraises) == 0) {
            return Response::json(array('text' => 'No praises last week'), 200);
        }
        $results = array();
        foreach($allPraises as $praise) {
            $requesterName = $praise['requester']['name'];
            $awardedName = $praise['awarded']['name'];

            if(array_key_exists($awardedName, $results)) {
                if(array_key_exists($requesterName, $results[$awardedName]['requesters'])) {
                    $results[$awardedName]['requesters'][$requesterName] ++;
                    $results[$awardedName]['total'] ++;
                } else {
                    $results[$awardedName]['requesters'][$requesterName] = 1;
                    $results[$awardedName]['total'] ++;
                }
            } else {
                $results[$awardedName] = array();
                $results[$awardedName]['requesters'] = array();
                $results[$awardedName]['requesters'][$requesterName] = 1;
                $results[$awardedName]['total'] = 1;
            }
        }

        $output = array();
        $count = 1;
        array_push($output, 'Praise list - Last week');
        foreach($results as $key => $value){
            $outputLine = '';
            $awardedName = $count . '. ' . $key . ' - ';
            $total = $value['total'];
            $totalStr = $value['total'] <= 1 ? ' Praise' : ' Praises ';
            $outputLine = $awardedName . $total . $totalStr . ' (by ';
            $requestersLine = '';
            foreach($value['requesters'] as $k => $v) {
                $requestersLine = $requestersLine . $k . '(' . $v . '), ';
            }
            $outputLine = $outputLine . rtrim($requestersLine, ', ') . ')';
            array_push($output, $outputLine);
            $count ++;
        }

        return Response::json(array('text' => implode("\n ", $output)), 200);
    }

    public function postLastMonth(Request $request){

        $lastMonth = Carbon::today()->month - 1;
        $year = Carbon::today()->year;
        $team = Team::where('slack_id', $request->get('team_id'))->first();

        $prises = Praise::where('team_id', $team->id)
            ->whereMonth('created_at', '=', $lastMonth)
            ->whereYear('created_at', '=', $year)
            ->with('requester')
            ->with('awarded')
            ->get();

        if(count($prises) == 0) {
            return Response::json(array('text' => 'No praises last month'), 200);
        }
        $results = array();
        foreach($prises as $praise) {
            $requesterName = $praise['requester']['name'];
            $awardedName = $praise['awarded']['name'];

            if(array_key_exists($awardedName, $results)) {
                if(array_key_exists($requesterName, $results[$awardedName]['requesters'])) {
                    $results[$awardedName]['requesters'][$requesterName] ++;
                    $results[$awardedName]['total'] ++;
                } else {
                    $results[$awardedName]['requesters'][$requesterName] = 1;
                    $results[$awardedName]['total'] ++;
                }
            } else {
                $results[$awardedName] = array();
                $results[$awardedName]['requesters'] = array();
                $results[$awardedName]['requesters'][$requesterName] = 1;
                $results[$awardedName]['total'] = 1;
            }
        }

        $output = array();
        $count = 1;
        array_push($output, 'Praise list - Last month');
        foreach($results as $key => $value){
            $outputLine = '';
            $awardedName = $count . '. ' . $key . ' - ';
            $total = $value['total'];
            $totalStr = $value['total'] <= 1 ? ' Praise' : ' Praises ';
            $outputLine = $awardedName . $total . $totalStr . ' (by ';
            $requestersLine = '';
            foreach($value['requesters'] as $k => $v) {
                $requestersLine = $requestersLine . $k . '(' . $v . '), ';
            }
            $outputLine = $outputLine . rtrim($requestersLine, ', ') . ')';
            array_push($output, $outputLine);
            $count ++;
        }

        return Response::json(array('text' => implode("\n ", $output)), 200);
    }

    public function postAll(Request $request)
    {
        $team = Team::where('slack_id', $request->get('team_id'))->first();
        $users = User::where('team_id', $team->id)->get();

        $output = array();
        array_push($output, 'Praise list - All time');
        $count = 1;
        foreach($users as $user) {
            $awardedName = $count . '. ' . $user->name . ' ';
            $outputLine = $awardedName;
            $userPraisesCount = Praise::where('team_id', $team->id)
                ->where('awarded_id', $user->id)
                ->count();

            if($userPraisesCount == 0) {
                continue;
            } else if($userPraisesCount == 1) {
                $praiseTxt = ' Praise ';
                $outputLine = $outputLine . $userPraisesCount . $praiseTxt . ' (';
            } else {
                $praiseTxt = ' Praises ';
                $outputLine = $outputLine . $userPraisesCount . $praiseTxt . ' (';
            }
            foreach($users as $u) {
                $userPraisesDetails = Praise::where('team_id', $team->id)
                    ->where('awarded_id', $user->id)
                    ->where('requester_id', $u->id)
                    ->count();
                if($userPraisesDetails > 0) {
                    $outputLine = $outputLine . $u->name . '(' . $userPraisesDetails . '), ';
                }
            }
            $outputLine = rtrim($outputLine, ', ');
            $outputLine = $outputLine . ')';
            $count ++;
            array_push($output, $outputLine);
        }

        return Response::json(array('text' => implode("\n ", $output)), 200);

    }
}