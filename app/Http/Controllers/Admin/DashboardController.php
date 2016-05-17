<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Gift;
use App\Models\Solution;
use App\Models\Relation;
use LaravelAcl\Authentication\Models\UserProfile;
use DB;

class DashboardController extends Controller {

    private $usersTable = 'users';
    private $usersGroupsTable = 'users_groups';
    private $userProfileTable = 'user_profile';

    public function getData(Request $req) {
        $clientCountRes = DB::table($this->usersTable)->select(DB::raw("COUNT(`$this->usersTable`.`id`) AS client_count"))
                        ->join($this->usersGroupsTable, $this->usersGroupsTable . '.user_id', '=', $this->usersTable . '.id')
                        ->where($this->usersGroupsTable . '.group_id', '=', Client::GROUP_ID)->get();
//print_r($clientCountRes); exit;

        $clientCount = count($clientCountRes) > 0 ? $clientCountRes[0]->client_count : 0;
        $giftCount = Gift::count();
        $queryAnsCount = Solution::where('type', 2)->whereNotNull('solution')->count();
        $queryCount = Solution::where('type', 2)->whereNull('solution')->count();
        $suggestionCount = Solution::where('type', 1)->count();
        $refCount = Relation::where('is_referrer', 1)->count();
        $data = [
            'SUCCESS' => TRUE,
            'DATA' => [
                'count' => [
                    'clientCount' => $clientCount,
                    'giftCount' => $giftCount,
                    'queryAnsCount' => $queryAnsCount,
                    'queryCount' => $queryCount,
                    'suggestionCount' => $suggestionCount,
                    'refCount' => $refCount,
                ]
            ]
        ];

        if (intval($req->get('clientCount')) < intval($clientCount)) {
            $recClients = DB::table($this->usersTable)->select($this->usersTable . '.id', $this->userProfileTable . '.first_name', $this->userProfileTable . '.last_name', $this->usersTable . '.created_at')
                            ->join($this->usersGroupsTable, $this->usersGroupsTable . '.user_id', '=', $this->usersTable . '.id')
                            ->leftJoin($this->userProfileTable, $this->userProfileTable . '.user_id', '=', $this->usersTable . '.id')
                            ->where($this->usersGroupsTable . '.group_id', '=', Client::GROUP_ID)->get();
            $data['DATA']['rec']['recClients'] = $recClients;
        }
        if (intval($req->get('giftCount')) < intval($giftCount)) {
            $recGifts = Gift::orderBy('created_at', 'DESC')->take(5)->get();
            $data['DATA']['rec']['recGifts'] = $recGifts->toArray();
        }
        if (intval($req->get('queryCount')) < intval($queryCount)) {
            $recQueries = Solution::where('type', 2)->whereNull('solution')->orderBy('created_at', 'DESC')->get();
            $data['DATA']['rec']['recQueries'] = $recQueries->toArray();
        }
        if (intval($req->get('refCount')) < intval($refCount)) {
            $recReferrers = Relation::select('rel_user_id', DB::raw("CONCAT_WS(' ',user_profile.first_name,user_profile.last_name) AS full_name"), DB::raw('COUNT(`relations`.`id`) AS ref_count'))
                            ->leftJoin('user_profile', 'user_profile.user_id', '=', 'relations.rel_user_id')
                            ->where('is_referrer', TRUE)->groupBy('rel_user_id')->orderBy('ref_count', 'DESC')->take(5)->get();
            $data['DATA']['rec']['recReferrers'] = $recReferrers->toArray();
        }
        $profile = new UserProfile();
        $data['profiletable'] = $profile->table;
        return json_encode($data);
    }

}
