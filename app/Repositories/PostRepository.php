<?php

namespace App\Repositories;

use Auth;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostVideo;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\Providers\Auth as ProvidersAuth;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class PostRepository.
 */
class PostRepository
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
     * For fetching posts records
     *
     * @param Request $request
     */
    public function getPosts($request)
    {
        $data = [];
        $posts = Post::with(['userTrust', 'reactions', 'comments', 'user', 'user.ranks', 'postImages', 'postVideos'])->where('politician_id', $request->politicianId)->get();
       
        $users = User::with('posts')->get();

        $postCount = [];
        foreach($users as $user) {
            $postCount[$user->id] = $user->posts->count();
        }

        $id = 0;
        foreach($posts as $post) {
            $data[$id]['id'] = $post->id;
            $data[$id]['user_id'] = $post->user_id;
            $data[$id]['politician_id'] = $post->politician_id;
            $data[$id]['content'] = $post->content;
            $data[$id]['gif'] = $post->gif;
            $data[$id]['status'] = $post->status;
            $data[$id]['created_at'] = $post->created_at;

            $imageCount = $post->postImages->count();
            $image = '';
         
            foreach($post->postImages as $images) {
                if($imageCount > 1) {
                    $image .= $images->image . ',';
                } else {
                    $image .= $images->image;
                }
            }

            $videoCount = $post->postImages->count();    
            $video = '';
            foreach($post->postVideos as $videos) {
                if($videoCount > 1) {
                    $video .= $videos->image . ',';
                } else {
                    $video .= $videos->image;
                }
            }

            $data[$id]['images'] = $image;
            $data[$id]['videos'] = $video;

            if(!empty($this->userDetails)) {
                $data[$id]['reaction_status'] = $post->reactions->where('user_id', $this->userDetails->id)->first()->reaction ?? [];
            }
            
            $userMeta = $post->user->getMeta()->toArray();
           
            $data[$id]['user']['user_id'] = $post->user->id;
            $data[$id]['user']['first_name'] = $post->user->first_name;
            $data[$id]['user']['last_name'] = $post->user->last_name;
            $data[$id]['user']['image'] = $post->user->image;

            $post_count = Post::where('user_id', $post->user->id)->count();

            $data[$id]['user']['post_count'] = $post_count;

            $data[$id]['user']['meta_data'] = $userMeta; 
           
            $data[$id]['timeago_date'] = $post->created_at->diffForHumans();

            $up = $post->userTrust->where('trust', 'Up')->count();

            $down = $post->userTrust->where('trust', 'Down')->count();

            $result = self::getScorePercentage($up, $down);

            if(!empty($this->userDetails)) {
                $trust_response = $post->userTrust->where(['user_id' => $request->politicianId, 'responded_id' => $this->userDetails->id])->first();
            }
            $data[$id]['trust_data']['trust_per'] = $result;
            $data[$id]['trust_data']['user_rank'] = $post->user->ranks->title ?? '';
            $data[$id]['trust_data']['rank_image'] = $post->user->ranks->image ?? '';
            $data[$id]['trust_data']['rank_description'] = $post->user->ranks->long_desc ?? '';
            $data[$id]['trust_data']['trust_response'] = $trust_response->trust ?? '';

            $data[$id]['comment_count'] = $post->comments->count();

            foreach($post->comments as $comment) {
                $data[$id]['comment'] = $comment;
                $data[$id]['comment']['timeago_date'] = $comment->created_at->diffForHumans();
            }

            $data[$id]['reactionCount'] = $post->reactions->count();

            $authLike = 0;
            
            if(!empty($this->userDetails)) {
                $authLike = $post->reactions->where('user_id', $this->userDetails->id)->count();     
            }

            $reaction = self::getReactionValue($data[$id]['reactionCount'], $authLike);
            $data[$id]['reactionText'] = $reaction;
            $id++;
        }
        
        return [
            'post' => $data,
        ];
    }

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
            'gif' => $request->postGif ?? '',
            'status' => config('constants.status.active'),
        ];    

        $post = Post::create($postData);

        if($post && !empty($request->postImages)) {
            foreach ($request->postImages as $images) {
                $imgArray = [
                    'post_id' => $post->id,
                    'image' => $images,
                    'status' => config('constants.status.active'),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];

                $postImages[] = $imgArray;
            }
            PostImage::insert($postImages);
        }

        if($post && !empty($request->postVideos)) {
            foreach ($request->postVideos as $videos) {
                $videoArray = [
                    'post_id' => $post->id,
                    'image' => $videos,
                    'status' => config('constants.status.active'),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at	' => Carbon::now()->format('Y-m-d H:i:s'),
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
     * For Updating the record respective model in storage
     *
     * @param Request $request
     */
    public function updatePost(Request $request)
    {

        $post_images  = '';
        $post_videos  = '';
        $post_gif  = '';

        if(!empty($request->postImages)){
			$post_images = implode(',',$request->postImages);
		}
		
		if(!empty($request->postVideos)){
			$post_videos = implode(',',$request->postVideos);
		}
		
		if(!empty($request->postGif)){
		    $post_gif = $request->postGif;
		}
		
        $created_date = date("Y-m-d H:i:s");

        $posts = Post::findOrFail($request->post_id);

        $posts->update(['politician_id' => $request->politicianId, 
                        'content' => $request->postContent,
                        'gif' => $post_gif,
                        'images' => $post_images,
                        'videos' => $post_videos,
                        'created_at' =>  $created_date
                    ]);

        return [ 
            'post' => $posts
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
            'id' => $request->postId,
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
        $path = public_path('post');
        if(!Storage::exists($path)){
            Storage::makeDirectory($path, 0777, true, true);
        }

        $fileName = uploadFile('post', $request->file);
        
        return $fileName;
    }

    public function getScorePercentage($up, $down) {
        $percentage = 0;
        if ($up > 0) {
            $percentage = ceil((($up - $down) / $up ) * 100);
            $percentage = ($percentage < 1) ? 0 : $percentage;
        }
        if($up==$down){
            if($up!=0 && $down!=0){
                $percentage = 50;
            }
        }
        return $percentage;
    }

    public function getReactionValue($likeCount, $authLike){

        if($likeCount==0){
            $reactiontext = "Be first to like this.";
        }
        if($likeCount > 0 && $authLike==0){
            $reactiontext = $likeCount." people like this.";
        }

        if($likeCount == 1 && $authLike==1){
            $reactiontext = "You like this.";
        }

        if($likeCount >1 && $authLike==1){
            $reactiontext = "You and ".($likeCount-1)." others people like this.";
        }

        return $reactiontext;
    }
}

?>
