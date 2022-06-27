<?php

namespace App\Repositories;

use Auth;
use App\Models\PostComment;
use Illuminate\Http\Request;

/**
 * Class PostCommentRepository.
 */
class PostCommentRepository
{
    /**
     * For Storing the record respective model in storage
     *
     * @param Request $request
     */
    public function postComment(Request $request)
    {
        $commentData = [
            'parent_comment_id' => $request->parentCommentId ?? NULL,
            'post_id' => $request->postId, 
            'user_id' => Auth::user()->id,
            'comment' => $request->comment ?? NULL,
            'gif' => $request->postGif ?? NULL,
            'image' => $request->postImages ?? NULL,
            'status' => config('constants.status.active'),
        ];    
        $postComment = PostComment::create($commentData);
        
        return [
            'postComment' => $postComment,
        ];
    }
}

?>
