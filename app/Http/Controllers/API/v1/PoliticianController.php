<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Response\CustomApiResponse;
use App\Repositories\CategoryRepository;
use Exception;
use Illuminate\Http\Request;


class PoliticianController extends Controller
{

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;


    /**
     * @var CustomApiResponse
     */
    private $apiResponse;

    public function __construct(CustomApiResponse $customApiResponse, CategoryRepository $categoryRepository) {
        $this->apiResponse = $customApiResponse;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/get-politician-categories",
     *     tags={"get-politician-categories"},
     *     description="Get Politician Categories",
     *     operationId="get-politician-categories",
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
     * Get Potician Category API
     *
     * 
     */
    public function getPoliticians()
    {
        try {
            $politicians = $this->categoryRepository->getPoliticians();

            if (!empty($politicians)) {
               
                $message = trans('lang.get_politicians');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $politicians, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }
}
