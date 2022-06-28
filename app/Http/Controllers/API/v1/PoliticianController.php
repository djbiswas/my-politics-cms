<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Response\CustomApiResponse;
use App\Http\Requests\CreateUserPostValidationRequest;
use App\Repositories\PoliticianRepository;
use Exception;
use Illuminate\Http\Request;


class PoliticianController extends Controller
{

    /**
     * @var PoliticianRepository
     */
    private $politicianRepository;


    /**
     * @var CustomApiResponse
     */
    private $apiResponse;

    public function __construct(CustomApiResponse $customApiResponse, PoliticianRepository $politicianRepository) {
        $this->apiResponse = $customApiResponse;
        $this->politicianRepository = $politicianRepository;
    }

    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/get-politicians",
     *     tags={"Get Politicians"},
     *     summary="Get Politicians",
     *     operationId="get-politicians",
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
     * Get Poticians API
     *
     * @param Request $request
     */
    public function getPoliticians(Request $request)
    {
        try {
            $politicians = $this->politicianRepository->getPoliticians($request);

            if (!empty($politicians)) {
               
                $message = trans('lang.get_politicians');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $politicians, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/get-politician-detail",
     *     tags={"Get Politician Detail"},
     *     summary="Get Politician Detail",
     *     operationId="get-politician-detail",
     * 
     *     @OA\Parameter(
     *       name="politicanId",
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
     * Get Potician Details API
     *
     * @param CreateUserPostValidationRequest $request
     */
    public function getPoliticianDetail(CreateUserPostValidationRequest $request)
    {
        try {
            $politicianDetails = $this->politicianRepository->getPoliticianDetail($request);

            if (!empty($politicianDetails)) {
               
                $message = trans('lang.politician_detail');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $politicianDetails, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/get-politician-votes",
     *     tags={"Get Politician Vote"},
     *     summary="Get Politician Vote",
     *     operationId="get-politician-votes",
     * 
     *     @OA\Parameter(
     *       name="politicanId",
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
     * Get Potician Details API
     *
     * @param CreateUserPostValidationRequest $request
     */
    public function getPoliticianVotes(CreateUserPostValidationRequest $request)
    {
        try {
            $politicianDetails = $this->politicianRepository->getPoliticianVotes($request);

            if (!empty($politicianDetails)) {
               
                $message = trans('lang.politician_vote');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $politicianDetails, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/get-trust",
     *     tags={"Get Trust"},
     *     summary="Get Trust",
     *     operationId="get-trust",
     * 
     *     @OA\Parameter(
     *       name="respondedId",
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
     * Get Trust API
     *
     * @param Request $request
     */
    public function getTrust(Request $request)
    {
        try {
            $politicianDetails = $this->politicianRepository->getTrust($request);

            if (!empty($politicianDetails)) {
               
                $message = trans('lang.politician_vote');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $politicianDetails, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }
}
