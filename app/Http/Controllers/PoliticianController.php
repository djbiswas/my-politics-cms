<?php

namespace App\Http\Controllers;

use App\Repositories\PoliticianRepository;
use App\Models\Politician;
use Exception;
use Illuminate\Http\Request;
use Datatables;

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

    /**
     * Display a listing of the politicians.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        try {
            if ($request->ajax()) {
                $data = Politician::select('*');
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
           
                                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
          
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
              
            return view('politician.listing');
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }
}
