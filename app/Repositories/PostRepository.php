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
        $posts = Post::with(['userTrust', 'reactions', 'comments', 'user', 'user.userMeta'  => function ($query) {
            $query->select('id', 'user_id', \DB::raw('GROUP_CONCAT(meta_key SEPARATOR "-~-") as meta_key, GROUP_CONCAT(meta_value SEPARATOR "-~-") as meta_value'));
        }, 'user.ranks'])->where('politician_id', $request->politicianId)->get();

        $users = User::with('posts')->get();

        $postCount = [];
        foreach($users as $user){
            $postCount[$user->id] = $user->posts->count();
        }

        foreach($posts as $post){

            $data['post']['id'] = $post->id;
            $data['post']['user_id'] = $post->user_id;
            $data['post']['politician_id'] = $post->politician_id;
            $data['post']['content'] = $post->content;
            $data['post']['gif'] = $post->gif;
            $data['post']['images'] = $post->images;
            $data['post']['videos'] = $post->videos;
            $data['post']['status'] = $post->status;
            $data['post']['created_at'] = $post->created_at;

            if(!empty($this->userDetails)){

                $data['post']['reaction_status'] = $post->reactions->where('user_id', $this->userDetails->id)->first()->reaction ?? '';

            }
            $userMeta = UserMeta::select(\DB::raw('GROUP_CONCAT(meta_key SEPARATOR "-~-") as meta_key, GROUP_CONCAT(meta_value SEPARATOR "-~-") as meta_value'))
            ->where('user_id', $post->user->id)->first();
           
            $meta_data = self::explode_meta_data_fn($userMeta->meta_key, $userMeta->meta_value);
           
            $data['post']['user']['user_id'] = $post->user->id;
            $data['post']['user']['first_name'] = $post->user->first_name;
            $data['post']['user']['last_name'] = $post->user->last_name;
            $data['post']['user']['image'] = $post->user->image;

            $post_count = Post::where('user_id', $post->user->id)->count();

            $data['post']['user']['post_count'] = $post_count;

            $data['post']['user']['meta_data'] = $meta_data; 
           
            $data['post']['timeago_date'] = $post->created_at->diffForHumans();

            $up = $post->userTrust->where('trust', 'Up')->count();

            $down = $post->userTrust->where('trust', 'Down')->count();

            $result = self::getScorePercentage($up, $down);

            if(!empty($this->userDetails)){
            
                $trust_response = $post->userTrust->where(['user_id' => $request->politicianId, 'responded_id' => $this->userDetails->id])->first();

            }
            $data['post']['trust_data']['trust_per'] = $result;
            $data['post']['trust_data']['user_rank'] = $post->user->ranks->title ?? '';
            $data['post']['trust_data']['rank_image'] = $post->user->ranks->image ?? '';
            $data['post']['trust_data']['rank_description'] = $post->user->ranks->long_desc ?? '';
            $data['post']['trust_data']['trust_response'] = $trust_response->trust ?? '';

            $data['post']['comment_count'] = $post->comments->count();

            foreach($post->comments as $comment){

                $data['post']['comment'] = $comment;

                $data['post']['comment']['timeago_date'] = $comment->created_at->diffForHumans();

            }

            $data['post']['reactionCount'] = $post->reactions->count();

            if(!empty($this->userDetails)){
            
                $authLike = $post->reactions->where('user_id', $this->userDetails->id)->count();

            }

            $reaction = self::getReactionValue($data['post']['reactionCount'], $authLike);

            $data['post']['reactionText'] = $reaction;
            
        }
        
        return [
            'data' => $data,
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
        $path = public_path('post_comment_image/');
        if(!Storage::exists($path)){
            Storage::makeDirectory($path, 0777, true, true);
        }

        $fileName = uploadFile('post_comment_image', $request->file);
        
        return $fileName;
    }

    public function explode_meta_data_fn($keys, $values) {
        $meta_data = [];
        $meta_keys = explode('-~-', $keys);
        $meta_values = explode('-~-', $values);
        if (!empty($meta_keys)) {
            foreach ($meta_keys as $key => $value) {
                $meta_data[$value] = $meta_values[$key] ?? null;
            }
        }
        return $meta_data;
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
