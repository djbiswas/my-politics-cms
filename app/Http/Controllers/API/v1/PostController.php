<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Response\CustomApiResponse;
use App\Http\Requests\CreatePostValidationRequest;
use App\Http\Requests\DeletePostValidationRequest;
use App\Http\Requests\PostReactionValidationRequest;
use App\Repositories\PostRepository;
use App\Repositories\ReactionRepository;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var ReactionRepository
     */
    private $reactionRepository;


    /**
     * @var CustomApiResponse
     */
    private $apiResponse;

    public function __construct(CustomApiResponse $customApiResponse, PostRepository $postRepository, ReactionRepository $reactionRepository) {
        $this->apiResponse = $customApiResponse;
        $this->postRepository = $postRepository;
        $this->reactionRepository = $reactionRepository;
    }

    /**
     * @OA\Post(
     *     path="/v1/create-post",
     *     tags={"Create Post"},
     *     summary="Create Post",
     *     operationId="create-post",
     *
     *     @OA\Parameter(
     *       name="politicianId",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postContent",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postGif",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postImages",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postVideos",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ), 
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */

    /**
     * Create Post API
     *
     * @param CreatePostValidationRequest $request
     */
    public function createPost(CreatePostValidationRequest $request)
    {
        try {
            $post = $this->postRepository->createPost($request);

            if (!empty($post)) {
                $message = trans('lang.create_post');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $post, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/create-post",
     *     tags={"Create Post"},
     *     summary="Create Post",
     *     operationId="create-post",
     *
     *     @OA\Parameter(
     *       name="politicianId",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postContent",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postGif",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postImages",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postVideos",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ), 
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */

    /**
     * Upload the media of post API
     *
     * @param CreatePostValidationRequest $request
     */
    public function mediaUpload(CreatePostValidationRequest $request)
    {
        try {
            $post = $this->postRepository->createPost($request);

            if (!empty($post)) {
                $message = trans('lang.create_post');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $post, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/create-post",
     *     tags={"Create Post"},
     *     summary="Create Post",
     *     operationId="create-post",
     *
     *     @OA\Parameter(
     *       name="politicianId",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postContent",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postGif",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postImages",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postVideos",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ), 
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */

    /**
     * Update Post API
     *
     * @param CreatePostValidationRequest $request
     */
    public function updatePost(CreatePostValidationRequest $request)
    {
        try {
            $post = $this->postRepository->createPost($request);

            if (!empty($post)) {
                $message = trans('lang.create_post');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $post, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/create-post",
     *     tags={"Create Post"},
     *     summary="Create Post",
     *     operationId="create-post",
     *
     *     @OA\Parameter(
     *       name="politicianId",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postContent",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postGif",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postImages",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postVideos",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ), 
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */

    /**
     * Delete Post API
     *
     * @param DeletePostValidationRequest $request
     */
    public function deletePost(DeletePostValidationRequest $request)
    {
        try {
            $post = $this->postRepository->deletePost($request);

            if (!empty($post)) {
                $message = trans('lang.delete_post');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), '', $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/post-reaction",
     *     tags={"Post Reaction"},
     *     summary="Post Reaction",
     *     operationId="post-reaction",
     *
     *     @OA\Parameter(
     *       name="mId",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="reaction",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="mType",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */

    /**
     * Post Reaction API
     *
     * @param PostReactionValidationRequest $request
     */
    public function postReaction(PostReactionValidationRequest $request)
    {
        try {
            echo '<pre>'; print_r($request->all()); die;
            $reaction = $this->reactionRepository->postReaction($request);

            if (!empty($reaction)) {
                $message = trans('lang.post_reaction');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $reaction, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/create-post",
     *     tags={"Create Post"},
     *     summary="Create Post",
     *     operationId="create-post",
     *
     *     @OA\Parameter(
     *       name="politicianId",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postContent",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postGif",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postImages",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postVideos",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ), 
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */

    /**
     * Post Comment API
     *
     * @param DeletePostValidationRequest $request
     */
    public function postComment(DeletePostValidationRequest $request)
    {
        try {
            $post = $this->postRepository->deletePost($request);

            if (!empty($post)) {
                $message = trans('lang.add_post_comment');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), '', $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }
}
