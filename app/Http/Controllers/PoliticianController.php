<?php

namespace App\Http\Controllers;

use App\Repositories\PoliticianRepository;
use App\Models\Politician;
use Exception;
use Illuminate\Http\Request;
use Datatables;
Use App\Services\CommonService;

class PoliticianController extends Controller
{
    /**
     * @var politicianRepository
     */
    private $politicianRepository;

    /**
     * @var commonService
     */
    private $commonService;

    public function __construct(PoliticianRepository $politicianRepository, CommonService $commonService) {
        $this->politicianRepository = $politicianRepository;
        $this->commonService = $commonService;
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
                        ->editColumn('politician_description',function($row){
                            return $this->commonService->limit_text($row->politician_description,10);
                        })
                        ->editColumn('updated_at',function($row){
                            if(empty($row->updated_at)){
                                return '';
                            }
                            return date('Y-m-d H:i',strtotime($row->updated_at));
                        })
                        ->addColumn('action', function($row){
                                $btn = '<a href="{{route("get.politician")}}/'.$row->id.'">Edit </a> | <a class="btn-delete" href="{{route("get.politician")}}/'.$row->id.'">Delete </a>';
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
    
    /**
     * Method to get Politician Data through id
     * 
     * @param $id
     */
    public function getPolitician($id=null){
        $politician=Politician::select('*')->where(['id' => $id]);
        return view('politician.add-form',['data'=>$politician]);
    }

    /**
     * Method to post politician data
     * 
     */
    public function postPolitician(){
        $politician=Politician::select('*');
        return view('politician.add-form',['data'=>$politician]);
    }
}
