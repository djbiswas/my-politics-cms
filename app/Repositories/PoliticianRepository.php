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
    public function fetchAllData(array $condition = [], $limit = 8)
    {
        return Politician::where($condition)
                ->orderBy('name')
                ->take($limit)
                ->get();
    }

    /**
     * For selecting the data
     *
     * @param Request $request
     */
    public function getPoliticians($request)
    {
        return [
            'count' => $this->countPoliticans($request),
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
        $votingAlerts = '';

        $politician = Politician::select('id', 'name', 'title', 'name_alias', 'image')->firstOrFail($request->politicianId);

        $politicanMeta = PoliticianMeta::select(\DB::raw('GROUP_CONCAT(key) as meta_key, GROUP_CONCAT(value) as meta_value'))
        ->where('politician_id', $request->politicianId)->first();
        
        $metaData = self::explode_meta_data_fn($politicanMeta['meta_key'], $politicanMeta['meta_value']);
         
        
        if(!empty($metaData['voting_alerts'])){
            $votingAlerts = (is_array($metaData['voting_alerts']) && in_array($this->userDetails->id, $metaData['voting_alerts'])) ? 'Yes' : 'no';
		}

        return [
            'politician' => $politician,
            'voting_alerts' => $votingAlerts,
            'meta_data' => $metaData
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
        
            $userVote = $userVotes[0]->vote;
        }

        return [
            'down_count' => $downCount,
            'up_count' => $upCount,
            'percentage' => $percentage,
            'users_vote' => $userVote,
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
            $up_count = $usertrust[0]->up;
            $down_count = $usertrust[0]->down;
            $percentage = $this->getScorePercentage($up_count, $down_count);
        }

        $userRank = User::with('ranks')->findOrFail(Auth::id());
        if(!empty($userRank->ranks)){
            $user_rank = $userRank->ranks->title;
            $rank_image = $userRank->ranks->image;
        }

        $againstData = UserTrust::where(['user_id' => $request->respondedId, 'responded_id' => Auth::id()])->first();
        if(!empty($againstData)){
            $trust_response = $againstData->trust;
        }

        return [
            'trust_percentage' => $percentage ?? '', 
            'user_rank' => $user_rank ?? '',
            'rank_image' => $rank_image ?? '',
            'trust_response' => $trust_response ?? ''
        ];
    }

    /**
     * For counting the Politicans records
     *
     * @param Request $request
     */
    public function fetchPoliticans($request)
    {
        if($request->category) {
            echo '<pre>'; print_r('There'); die;
        } else {
            return Politician::select('id', 'name', 'title', 'name_alias', 'image')->onlyActive()->paginate(config('constants.recordsPerPage'));
        }
    }

    /**
     * For counting the Politicans records
     *
     * @param Request $request
     */
    public function countPoliticans($request)
    {
        if($request->category) {
            echo '<pre>'; print_r('Here'); die;
        } else {
            return Politician::select('*')->onlyActive()->count();    
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

    public function explode_meta_data_fn($keys, $values) {
        $meta_data = [];
        $meta_keys = explode(',', $keys);
        $meta_values = explode(',', $values);
        if (!empty($meta_keys)) {
            foreach ($meta_keys as $key => $value) {
                $meta_data[$value] = $meta_values[$key];
            }
        }
        return $meta_data;
    }
}

?>
