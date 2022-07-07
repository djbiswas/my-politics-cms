<?php

namespace App\Repositories;

use JWTAuth;
use App\Models\Politician;
use App\Models\PoliticanVote;
use App\Models\PoliticianMeta;
use App\Models\Rank;
use App\Models\User;
use App\Models\UserTrust;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
/**
 * Class PoliticianRepository.
 */
class PoliticianRepository
{
    private $userDetails;

    public function __construct(Request $request) {
        $token = $request->header('Authorization');
        if($token) {
            $parseValue = JWTAuth::parseToken();
            $this->userDetails = $parseValue->authenticate();
        } else {
            $this->userDetails = '';
        }
    }

    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = '')
    {
        $data = Politician::where($condition)
                ->orderBy('name');
        if($limit){
            $data->take($limit);
        }
        return $data->get();
    }

    /**
     * For selecting the data
     *
     * @param Request $request
     */
    public function getPoliticians($request)
    {
        return [
            //'count' => $this->countPoliticans($request),
            'records' => $this->fetchPoliticans($request),
        ];
    }

    /**
     * For counting the Politicans records
     *
     * @param Request $request
     */
    public function getPoliticianDetail($request)
    {
        $votingAlerts = [];

        $politician = Politician::where('id',$request->politicianId)->firstOrFail();

        $politicianMeta = $politician->getMeta()->toArray();

        if(!empty($politicianMeta['voting_alerts']) && !empty($this->userDetails)){
            $politicianMeta['voting_alerts'] = (is_array($politicianMeta['voting_alerts']) && in_array($this->userDetails->id, $metaData['voting_alerts'])) ? 'Yes' : 'no';
		}

        if(!empty($politicianMeta['p_pos'])){
            $politicianMeta['p_poss'] = json_decode($politicianMeta['p_pos'], true);
        }

        $result = [];
        $result = $politician;
        $result['meta_datas'] = $politicianMeta;
        
        return [
          'politician' => $result
        ];
    }

    /**
     * For counting the Politicans records
     *
     * @param Request $request
     */
    public function getPoliticianVotes($request)
    {
        $userVote = '';

        // $token = $request->header('Authorization');
        
        $politicanVote = PoliticanVote::select(DB::raw('COALESCE(SUM(vote = "up"), 0) AS up, COALESCE(SUM(vote = "down"), 0) AS down'))
        ->onlyActive()->where(['politician_id' => $request->politicianId])->get();

        if ($politicanVote) {
            $upCount = $politicanVote[0]->up;
            $downCount = $politicanVote[0]->down;
            $percentage = $this->getScorePercentage($upCount, $downCount);
        }

       if(!empty($this->userDetails)) {
            // $parseValue = JWTAuth::parseToken();
            // $user = $parseValue->authenticate();

            $userVotes = PoliticanVote::select('vote')->onlyActive()->where(['politician_id' => $request->politicianId, 'user_id' => $this->userDetails->id])->get();
        
            if(!empty($userVotes[0])){
                $userVote = $userVotes[0]->vote;
            }
       }

        return [
            'down_count' => $downCount,
            'up_count' => $upCount,
            'percentage' => $percentage,
            'users_vote' => $userVote ?? NULL,
        ];

    }

    /**
     * For Storing the record respective model in storage
     *
     * @param Request $request
     */
    public function setPoliticianVote($request)
    {
        $is_voted = self::isVoted(Auth::id(), $request->politician_id);

        if (empty($is_voted)) {
            
            $voteData = [
                'user_id' => Auth::user()->id,
                'politician_id' => $request->politicianId,
                'vote' => $request->vote,
                'status' => config('constants.status.active')
            ];    
    
            $vote = PoliticanVote::create($voteData);	
           
        } else {
           
            $vote = $is_voted->update(['vote' => $request->vote]);
           
        }
       
        return [
            'vote' => $vote
        ];
    }

    /**
     * For fetching Trust
     *
     * @param Request $request
     */
    public function getTrust($request)
    {
       $usertrust = UserTrust::select(DB::raw('COALESCE(SUM(trust = "up"), 0) AS up, COALESCE(SUM(trust = "down"), 0) AS down'))
       ->where(['user_id' => Auth::id()])->get();

        if ($usertrust) {
            $upCount = $usertrust[0]->up;
            $downCount = $usertrust[0]->down;
            $percentage = $this->getScorePercentage($upCount, $downCount);
        }

        $userRank = User::with('ranks')->findOrFail(Auth::id());
        if($userRank->ranks){
            $userRankTitle = $userRank->ranks->title;
            $rankImage = $userRank->ranks->image;
        }

        if($request->respondedId) {
            $againstData = UserTrust::where(['user_id' => $request->respondedId, 'responded_id' => Auth::id()])->get();
            if(!$againstData->empty()){
                $trustResponse = $againstData[0]->trust;
            }
        }
        
        return [
            'trust_percentage' => $percentage ?? 0, 
            'user_rank' => $userRankTitle ?? '',
            'rank_image' => $rankImage ?? '',
            'trust_response' => $trustResponse ?? NULL
        ];
    }

    /**
     * For counting the Politicans records
     *
     * @param Request $request
     */
    public function fetchPoliticans($request)
    {
        if($request->category_id) {
            $politicianMeta = PoliticianMeta::where('key', 'p_cat')->where('value', $request->category_id)->pluck('politician_id')->toArray();
            
            return Politician::select('id', 'name', 'title', 'name_alias', 'image')->whereIn('id', $politicianMeta)->onlyActive()->paginate(config('constants.recordsPerPage'));
        } else {
            return Politician::select('id', 'name', 'title', 'name_alias', 'image')->onlyActive()->paginate(config('constants.recordsPerPage'));
        }
    }

    /**
     * For calculating the percentage
     *
     * @param int $up
     * @param int $down
     */
    function getScorePercentage($up, $down) {
        $percentage = 0;
        if ($up > 0) {
             $percentage = ceil((($up - $down) / $up ) * 100);
            $percentage = ($percentage < 1) ? 0 : $percentage;
        }
        if($up == $down){
            if($up != 0 && $down != 0){
                $percentage = 50;
            }
        }
        return $percentage;
    }

    public function isVoted($id, $politician_id){
        return PoliticanVote::where(['user_id' => $id, 'politician_id' => $politician_id])->first();
    }

    /**
     * For Create / Updating record into ranks table
     */
    public function saveData($condition = [], $fields, $metaData = [])
    {
        $modelObj = Politician::updateOrCreate($condition, $fields);
        if(!empty($metaData)){
            $modelObj->setMeta($metaData);
            $modelObj->save();
        }
        return $modelObj;
    }
}

?>
