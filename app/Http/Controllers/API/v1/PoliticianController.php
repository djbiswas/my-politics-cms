<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Response\CustomApiResponse;
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
     *     tags={"get-politicians"},
     *     description="Get Politicians",
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
}
