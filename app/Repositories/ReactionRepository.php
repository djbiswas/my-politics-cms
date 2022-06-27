<?php

namespace App\Repositories;

use Auth;
use Carbon\Carbon;
use App\Models\Reaction;
use Illuminate\Http\Request;

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
        $condition = [
            'user_id' => Auth::user()->id,
            'm_id' => $request->mId,
            'm_type' => $request->mType,
        ]; 
        
        $reactionData = [
            'user_id' => $this->userId,
            'm_id' => $request->mId,
            'm_type' => $request->mType,
            'reaction' => $request->reaction,
            'reacted_date' => Carbon::now(),
            'status' => config('constants.status.active'),
        ];    

        $reaction = Reaction::updateOrCreate($condition, $reactionData);

        return [
            'reaction' => $reaction
        ];
    }
}

?>
