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
     * @var userId
     */
    private $userId;

    public function __construct() {
        $this->userId = Auth::user()->id;
    }

    /**
     * For Storing the record respective model in storage
     *
     * @param Request $request
     */
    public function createPost(Request $request)
    {
        $postData = [
            'user_id' => $this->userId,
            'm_id' => $request->mId,
            'm_type' => $request->mType,
            'reaction' => $request->reaction,
            'reacted_date' => Carbon::now(),
            'status' => config('constants.status.active'),
        ];    

        $reaction = Reaction::create($postData);

        return [
            'reaction' => $reaction
        ];
    }
}

?>
