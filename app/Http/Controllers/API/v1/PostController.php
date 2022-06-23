<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Response\CustomApiResponse;
use App\Http\Requests\CreatePostValidationRequest;
use App\Http\Requests\DeletePostValidationRequest;
use App\Repositories\PostRepository;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * @var PostRepository
     */
    private $postRepository;


    /**
     * @var CustomApiResponse
     */
    private $apiResponse;

    public function __construct(CustomApiResponse $customApiResponse, PostRepository $postRepository) {
        $this->apiResponse = $customApiResponse;
        $this->postRepository = $postRepository;
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
}
