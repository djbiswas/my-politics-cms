<?php

namespace App\Repositories;

use Auth;
use Carbon\Carbon;
use App\Models\Issue;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\Providers\Auth as ProvidersAuth;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class IssueRepository.
 */
class IssueRepository
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
     * For fetching Issues records
     *
     * @param Request $request
     */
    public function getIssues($request)
    {
        $data = [];
        $Issues = Issue::with(['userTrust', 'user', 'user.ranks', 'IssueImages', 'IssueVideos'])->where('politician_id', $request->politicianId)->get();

        $users = User::with('Issues')->get();

        $IssueCount = [];
        foreach($users as $user) {
            $IssueCount[$user->id] = $user->Issues->count();
        }

        $id = 0;
        foreach($Issues as $Issue) {
            $data[$id]['id'] = $Issue->id;
            $data[$id]['user_id'] = $Issue->user_id;
            $data[$id]['politician_id'] = $Issue->politician_id;
            $data[$id]['content'] = $Issue->content;
            // $data[$id]['gif'] = $Issue->gif;
            $data[$id]['image'] = $Issue->image;
            $data[$id]['status'] = $Issue->status;
            $data[$id]['created_at'] = $Issue->created_at;

            // $imageCount = $Issue->IssueImages->count();
            // $image = '';

            // foreach($Issue->IssueImages as $images) {
            //     if($imageCount > 1) {
            //         $image .= $images->image . ',';
            //     } else {
            //         $image .= $images->image;
            //     }
            // }

            // $videoCount = $Issue->IssueImages->count();
            // $video = '';
            // foreach($Issue->IssueVideos as $videos) {
            //     if($videoCount > 1) {
            //         $video .= $videos->image . ',';
            //     } else {
            //         $video .= $videos->image;
            //     }
            // }

            // $data[$id]['images'] = $image;
            // $data[$id]['videos'] = $video;

            // if(!empty($this->userDetails)) {
            //     $data[$id]['reaction_status'] = $Issue->reactions->where('user_id', $this->userDetails->id)->first()->reaction ?? [];
            // }

            $userMeta = $Issue->user->getMeta()->toArray();

            $data[$id]['user']['user_id'] = $Issue->user->id;
            $data[$id]['user']['first_name'] = $Issue->user->first_name;
            $data[$id]['user']['last_name'] = $Issue->user->last_name;
            $data[$id]['user']['image'] = $Issue->user->image;

            $Issue_count = Issue::where('user_id', $Issue->user->id)->count();

            $data[$id]['user']['Issue_count'] = $Issue_count;

            $data[$id]['user']['meta_data'] = $userMeta;

            $data[$id]['timeago_date'] = $Issue->created_at->diffForHumans();

            $up = $Issue->userTrust->where('trust', 'Up')->count();

            $down = $Issue->userTrust->where('trust', 'Down')->count();

            $result = self::getScorePercentage($up, $down);

            if(!empty($this->userDetails)) {
                $trust_response = $Issue->userTrust->where(['user_id' => $request->politicianId, 'responded_id' => $this->userDetails->id])->first();
            }
            $data[$id]['trust_data']['trust_per'] = $result;
            $data[$id]['trust_data']['user_rank'] = $Issue->user->ranks->title ?? '';
            $data[$id]['trust_data']['rank_image'] = $Issue->user->ranks->image ?? '';
            $data[$id]['trust_data']['rank_description'] = $Issue->user->ranks->long_desc ?? '';
            $data[$id]['trust_data']['trust_response'] = $trust_response->trust ?? '';

            $data[$id]['comment_count'] = $Issue->comments->count();

            // foreach($Issue->comments as $comment) {
            //     $data[$id]['comment'] = $comment;
            //     $data[$id]['comment']['timeago_date'] = $comment->created_at->diffForHumans();
            // }

            //$data[$id]['reactionCount'] = $Issue->reactions->count();

            $authLike = 0;

            // if(!empty($this->userDetails)) {
            //     $authLike = $Issue->reactions->where('user_id', $this->userDetails->id)->count();
            // }

            $reaction = self::getReactionValue($data[$id]['reactionCount'], $authLike);
            $data[$id]['reactionText'] = $reaction;
            $id++;
        }

        return [
            'Issue' => $data,
        ];
    }

    /**
     * For Storing the record respective model in storage
     *
     * @param Request $request
     */
    public function createUserIssue(Request $request)
    {
        $IssueData = [
            'user_id' => Auth::user()->id,
            'politician_id' => $request->politicianId,
            'content' => $request->IssueContent,
            'gif' => $request->IssueGif ?? '',
            'status' => config('constants.status.active'),
        ];

        $Issue = Issue::create($IssueData);

        if($Issue && !empty($request->IssueImages)) {
            foreach ($request->IssueImages as $images) {
                $imgArray = [
                    'Issue_id' => $Issue->id,
                    'image' => $images,
                    'status' => config('constants.status.active'),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];

                $IssueImages[] = $imgArray;
            }
            IssueImage::insert($IssueImages);
        }

        // if($Issue && !empty($request->IssueVideos)) {
        //     foreach ($request->IssueVideos as $videos) {
        //         $videoArray = [
        //             'Issue_id' => $Issue->id,
        //             'image' => $videos,
        //             'status' => config('constants.status.active'),
        //             'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //             'updated_at	' => Carbon::now()->format('Y-m-d H:i:s'),
        //         ];

        //         $IssueVideos[] = $videoArray;
        //     }
        //     $IssueImage = IssueVideo::insert($IssueVideos);
        // }

        return [
            'Issue' => $Issue
        ];
    }

    /**
     * For Updating the record respective model in storage
     *
     * @param Request $request
     */
    public function updateIssue(Request $request)
    {

        $Issue_images  = '';
        $Issue_videos  = '';
        $Issue_gif  = '';

        if(!empty($request->IssueImages)){
			$Issue_images = implode(',',$request->IssueImages);
		}

		if(!empty($request->IssueVideos)){
			$Issue_videos = implode(',',$request->IssueVideos);
		}

		if(!empty($request->IssueGif)){
		    $Issue_gif = $request->IssueGif;
		}

        $created_date = date("Y-m-d H:i:s");

        $Issues = Issue::findOrFail($request->Issue_id);

        $Issues->update(['politician_id' => $request->politicianId,
                        'content' => $request->IssueContent,
                        'gif' => $Issue_gif,
                        'images' => $Issue_images,
                        'videos' => $Issue_videos,
                        'created_at' =>  $created_date
                    ]);

        return [
            'Issue' => $Issues
        ];
    }

    /**
     * For delteting the record respective model in storage
     *
     * @param Request $request
     */
    public function deleteIssue(Request $request)
    {
        $IssueData = [
            'user_id' => Auth::user()->id,
            'id' => $request->IssueId,
        ];

        $Issue = Issue::where($IssueData)->delete();

        return true;
    }

    /**
     * For Uploading the file into storage
     *
     * @param Request $request
     */
    public function mediaUpload(Request $request)
    {
        $path = public_path('Issue');
        if(!Storage::exists($path)){
            Storage::makeDirectory($path, 0777, true, true);
        }

        $fileName = uploadFile('Issue', $request->file);

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
