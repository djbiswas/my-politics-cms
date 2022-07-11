<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Response\CustomApiResponse;
use App\Repositories\PostRepository;
use App\Repositories\ReactionRepository;
use App\Repositories\PostCommentRepository;
use App\Http\Requests\CreateUserPostValidationRequest;
use App\Http\Requests\UpdateUserPostValidationRequest;
use App\Http\Requests\MediaUploadValidationRequest;
use App\Http\Requests\DeleteUserPostValidationRequest;
use App\Http\Requests\UserPostReactionValidationRequest;
use App\Http\Requests\CreatePostCommentValidationRequest;
use Exception;

class UserPostController extends Controller
{

    /**
     * @var postRepository
     */
    private $postRepository;

    /**
     * @var reactionRepository
     */
    private $reactionRepository;

    /**
     * @var postCommentRepository
     */
    private $postCommentRepository;

    /**
     * @var apiResponse
     */
    private $apiResponse;

    public function __construct(CustomApiResponse $customApiResponse, PostRepository $postRepository, 
    ReactionRepository $reactionRepository, PostCommentRepository $postCommentRepository) {
        $this->apiResponse = $customApiResponse;
        $this->postRepository = $postRepository;
        $this->reactionRepository = $reactionRepository;
        $this->postCommentRepository = $postCommentRepository;
    }

    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/get-posts",
     *     tags={"Get Posts"},
     *     summary="Get Posts",
     *     operationId="get-posts",
     * 
     *     @OA\Parameter(
     *       name="politicianId",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *          type="integer"
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
     * Get Posts API
     *
     * @param CreateUserPostValidationRequest $request
     */
    public function getPosts(CreateUserPostValidationRequest $request)
    {
        try {
            $posts = $this->postRepository->getPosts($request);

            if (!empty($posts)) {
               
                $message = trans('lang.get_posts');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $posts, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     security={{"bearerAuth":{}}},
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
     * @param CreateUserPostValidationRequest $request
     */
    public function createUserPost(CreateUserPostValidationRequest $request)
    {
        try {
            $politicians = $this->postRepository->createUserPost($request);

            if (!empty($politicians)) {
               
                $message = trans('lang.create_post');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $politicians, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Patch(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/update-post",
     *     tags={"Update Post"},
     *     summary="Update Post",
     *     operationId="update-post",
     *     
     *     @OA\Parameter(
     *       name="post_id",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ), 
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
     * @param UpdateUserPostValidationRequest $request
     */
    public function updatePost(UpdateUserPostValidationRequest $request)
    {
        try {
            $politicians = $this->postRepository->updatePost($request);

            if (!empty($politicians)) {
               
                $message = trans('lang.update_post');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $politicians, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/media-uplaod",
     *     tags={"Update Post"},
     *     summary="Update Post",
     *     operationId="media-uplaod",
     *     
     *     @OA\Parameter(
     *       name="file",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="string"
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
     * @param MediaUploadValidationRequest $request
     */
    public function mediaUpload(MediaUploadValidationRequest $request)
    {
        try {
            $image = $this->postRepository->mediaUpload($request);

            if (!empty($image)) {
               
                $message = trans('lang.media_upload');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $image, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Delete(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/delete-post",
     *     tags={"Delete Post"},
     *     summary="Delete Post",
     *     operationId="delete-post",
     *
     *     @OA\Parameter(
     *       name="post_id",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
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
     * @param DeleteUserPostValidationRequest $request
     */
    public function deletePost(DeleteUserPostValidationRequest $request)
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
     *     security={{"bearerAuth":{}}},
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
     * @param UserPostReactionValidationRequest $request
     */
    public function postReaction(UserPostReactionValidationRequest $request)
    {
        try {
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
     *     security={{"bearerAuth":{}}},
     *     path="/v1/post-comment",
     *     tags={"Post Comment"},
     *     summary="Post Comment",
     *     operationId="post-comment",
     *
     *     @OA\Parameter(
     *       name="post_id",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="comment",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="gif",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="postImage",
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
     * @param CreatePostCommentValidationRequest $request
     */
    public function postComment(CreatePostCommentValidationRequest $request)
    {
        try {
            $comment = $this->postCommentRepository->postComment($request);

            if (!empty($comment)) {
                $success = [
                    $comment
                ];
                $message = trans('lang.add_post_comment');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $success, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    
    /**
     * Get Post Comment API
     *
     * @param DeleteUserPostValidationRequest $request
     */
    public function getComments(DeleteUserPostValidationRequest $request)
    {
        try {
            $comments = $this->postCommentRepository->getComments($request);

            if (!empty($comments)) {
                $success = [
                    $comments
                ];
                $message = trans('lang.get_post_comment');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $success, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }
}
