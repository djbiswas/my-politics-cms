<?php

namespace App\Http\Controllers;

use App\Repositories\PoliticianRepository;
use App\Models\Politician;
use Exception;
use Illuminate\Http\Request;

class PoliticianController extends Controller
{
    /**
     * @var politicianRepository
     */
    private $politicianRepository;
    //

    public function __construct(PoliticianRepository $politicianRepository) {
        $this->politicianRepository = $politicianRepository;
    }

    /**
     * Polician Dashboard 
     *
     */
    public function dashboard()
    {
        try {
            $condition = [];
            $politicians = $this->politicianRepository->fetchAllData($condition);
            return view('dashboard', ['politicians' => $politicians]);
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    public function index(){
        try {
            echo "comming soon..";exit;
            $condition = [];
            $politicians = $this->politicianRepository->fetchAllData($condition);
            return view('dashboard', ['politicians' => $politicians]);
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }
}
