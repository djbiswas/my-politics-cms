<?php

namespace App\Repositories;

use Auth;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class PostRepository.
 */
class PostRepository
{
    /**
     * For Storing the record respective model in storage
     *
     * @param Request $request
     */
    public function createUserPost(Request $request)
    {
        $postData = [
            'user_id' => Auth::user()->id,
            'politician_id' => $request->politicianId,
            'content' => $request->postContent,
            'gif' => $request->postGif,
            'status' => config('constants.status.active'),
        ];    

        $post = Post::create($postData);

        if($post && !empty($request->postImages)) {
            foreach ($request->postImages as $images) {

                $imgArray = [
                    'post_id' => $post->id,
                    'name' => $images['name'],
                    'status' => config('constants.status.active'),
                    'created_at' => Carbon::now(),
                    'updated_at	' => Carbon::now(),
                ];

                $postImages[] = $imgArray;
            }
            PostImage::insert($postImages);
        }

        if($post && !empty($request->postVideos)) {
            foreach ($request->postVideos as $videos) {

                $videoArray = [
                    'post_id' => $post->id,
                    'name' => $videos['name'],
                    'status' => config('constants.status.active'),
                    'created_at' => Carbon::now(),
                    'updated_at	' => Carbon::now(),
                ];

                $postVideos[] = $videoArray;
            }
            $postImage = PostVideo::insert($postVideos);
        }

        return [
            'post' => $post
        ];
    }

    /**
     * For delteting the record respective model in storage
     *
     * @param Request $request
     */
    public function deletePost(Request $request)
    {
        $postData = [
            'user_id' => Auth::user()->id,
            'post_id' => $request->post_id,
        ];    

        $post = Post::where($postData)->delete();

        return true;
    }

    /**
     * For Uploading the file into storage
     *
     * @param Request $request
     */
    public function mediaUpload(Request $request)
    {
        $path = public_path('post_comment_image/');
        if(!Storage::exists($path)){
            Storage::makeDirectory($path, 0777, true, true);
        }

        $fileName = uploadFile('post_comment_image', $request->file);
        
        return $fileName;
    }
}

?>
