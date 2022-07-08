<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Response\CustomApiResponse;
use App\Http\Requests\CreateUserPostValidationRequest;
use App\Http\Requests\SetPoliticianVoteValidationRequest;
use App\Http\Requests\SetPoliticianVotingAlertValidationRequest;
use App\Http\Requests\SetPoliticianTrustValidationRequest;
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
     * @OA\Post(
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
     * @OA\Post(
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
     * @param Request $request
     */
    public function getPoliticianDetail(Request $request)
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
     * @OA\Post(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/politician-voting-alerts",
     *     tags={"Politician Voting Alert"},
     *     summary="Politician Voting Alert",
     *     operationId="politician-voting-alerts",
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
     *       name="alert",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *    
     *      @OA\Response(
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
     * Create Potician Voting Alert API
     *
     * @param SetPoliticianVotingAlertValidationRequest $request
     */
    public function setPoliticianVotingAlert(SetPoliticianVotingAlertValidationRequest $request)
    {
        try {
            $politicianVotingAlert = $this->politicianRepository->setPoliticianVotingAlert($request);

            if (!empty($politicianVotingAlert)) {
               
                $message = trans('lang.set_politician_voting_alert');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $politicianVotingAlert, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/set-politician-vote",
     *     tags={"Set Politician Vote"},
     *     summary="Set Politician Vote",
     *     operationId="set-politician-vote",
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
     * Create Potician Vote API
     *
     * @param SetPoliticianVoteValidationRequest $request
     */
    public function setPoliticianVote(SetPoliticianVoteValidationRequest $request)
    {
        try {
            $politicianVote = $this->politicianRepository->setPoliticianVote($request);

            if (!empty($politicianVote)) {
               
                $message = trans('lang.set_politician_vote');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $politicianVote, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
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
     * @OA\Post(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/get-trust",
     *     tags={"Get Trust"},
     *     summary="Get Trust",
     *     operationId="get-trust",
     * 
     *     @OA\Parameter(
     *       name="respondedId",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     * 
     *     @OA\Parameter(
     *       name="trust",
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
     * Set Trust API
     *
     * @param SetPoliticianTrustValidationRequest $request
     */
    public function setTrust(SetPoliticianTrustValidationRequest $request)
    {
        try {
            $trust = $this->politicianRepository->setTrust($request);

            if (!empty($trust)) {
               
                $message = trans('lang.set_trust');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $trust, $message);
            }
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/get-trust",
     *     tags={"Get Trust"},
     *     summary="Get Trust",
     *     operationId="get-trust",
     * 
     *     @OA\Parameter(
     *       name="respondedId",
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
