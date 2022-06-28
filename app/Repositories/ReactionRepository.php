<?php

namespace App\Repositories;

use Auth;
use Carbon\Carbon;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ReactionRepository.
 */
class ReactionRepository
{
    /**
     * For Storing the record respective model in storage
     *
     * @param Request $request
     */
    public function postReaction(Request $request)
    {
        $reactionText = '';
		$reactionTextCount = '';

        // Query Condition
        $condition = [
            'user_id' => Auth::user()->id,
            'm_id' => $request->mId,
            'm_type' => $request->mType,
        ]; 
        
        $reactionData = [
            'user_id' => Auth::user()->id,
            'm_id' => $request->mId,
            'm_type' => $request->mType,
            'reaction' => $request->reaction,
            'reacted_date' => Carbon::now(),
            'status' => config('constants.status.active'),
        ];    

        $reaction = Reaction::updateOrCreate($condition, $reactionData);

        $data = Reaction::select(DB::raw('count(*) AS reactCount, SUM(CASE WHEN user_id = '.Auth::user()->id.' THEN 1 ELSE 0 END) as userCount'))
            ->onlyActive()->where(['m_id' => $request->mId, 'reaction' => 'like'])->get();

        $reactionTextCount = $data[0]->reactCount;
        if($reactionTextCount == 0){
            $reactionText = "Be first to like this.";
        }
        if($reactionTextCount > 0 && $data[0]->userCount == 0){
            $reactionText = $reactionTextCount." people like this.";
        }
        
        if($reactionTextCount == 1 && $data[0]->userCount == 1){
            $reactionText = "You like this.";
        }
        
        if($reactionTextCount > 1 && $data[0]->userCount == 1){
            $reactionText = "You and ".($reactionTextCount-1)." others people like this.";
        }
        return [
            'reaction_text' => $reactionText,
            'reaction_count' => $reactionTextCount
        ];
    }
}

?>
