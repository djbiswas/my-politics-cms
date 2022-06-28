<?php

namespace App\Repositories;

use JWTAuth;
use App\Models\Politician;
use App\Models\PoliticanVote;
use Illuminate\Support\Facades\DB;

/**
 * Class PoliticianRepository.
 */
class PoliticianRepository
{
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
    public function getPoliticianVotes($request)
    {
        $user_vote = '';

        $token = $request->header('Authorization');
        
        $politicanVote = PoliticanVote::select(DB::raw('COALESCE(SUM(vote = "up"), 0) AS up, COALESCE(SUM(vote = "down"), 0) AS down'))
        ->onlyActive()->where(['politician_id' => $request->politicianId])->get();

        if ($politicanVote) {
            $up_count = $politicanVote[0]->up;
            $down_count = $politicanVote[0]->down;
            $percentage = $this->getScorePercentage($up_count, $down_count);
        }
        
        if($token) {
            $parseValue = JWTAuth::parseToken();
            $user = $parseValue->authenticate();

            $userVote = PoliticanVote::select('vote')->onlyActive()->where(['politician_id' => $request->politicianId, 'user_id' => $user->id])->get();
        
            $user_vote = $userVote[0]->vote;
        }

        return [
            'down_count' => $down_count,
            'up_count' => $up_count,
            'percentage' => $percentage,
            'users_vote' => $user_vote,
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
}

?>
