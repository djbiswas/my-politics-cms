<?php

namespace App\Http\Controllers;

use App\Repositories\PoliticianRepository;
use App\Models\Politician;
use Exception;
use Illuminate\Http\Request;
use Datatables;
Use App\Services\CommonService;
use App\Repositories\CategoryRepository;

class PoliticianController extends Controller
{
    /**
     * @var politicianRepository
     */
    private $politicianRepository;

    /**
     * @var categoryRepository
     */
    private $categoryRepository;

    /**
     * @var commonService
     */
    private $commonService;

    public function __construct(PoliticianRepository $politicianRepository, CommonService $commonService, CategoryRepository $categoryRepository) {
        $this->politicianRepository = $politicianRepository;
        $this->commonService = $commonService;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Polician Dashboard 
     *
     */
    public function dashboard()
    {
        try {
            $condition = [];
            $politicians = $this->politicianRepository->fetchAllData($condition, 8);
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
        $categories = $this->categoryRepository->fetchAllData([])->toArray();
        if($id){
            $data=Politician::find($id);
            //$metaData = $data->getMeta()->toArray();
            return view('users.politicianForm',['data'=>$data, 'categories'=>$categories, 'metaData'=>[]]);
        }
        return view('politician.politicianForm',['data'=>[], 'categories'=>$categories, 'metaData'=>[]]);
    }

    /**
     * Method to post politician data
     * 
     */
    public function postPolitician(Request $request){

        $data=$request->all();
        try{
            echo "<pre>";
            print_r($data);
            exit;
            if ($request->hasFile('image')) {
                $data['image'] = $this->commonService->storeImage($request->file('image'), config('constants.image.politician'));
            }
            if ($request->hasFile('affiliation_icon')) {
                $data['affiliation_icon'] = $this->commonService->storeImage($request->file('affiliation_icon'), config('constants.image.politician'));
            }
            $condition = [];
            if($data['id']){
                $condition = ['id' => $data['id']];
            }
            if($data['p_pos']){
                $pPos = json_encode($data['p_pos']);
            }
            $metaData = ($data['meta'])? $data['meta'] : [];
            unset($data['meta']);
            unset($data['p_pos']);
            $this->politicianRepository->saveData($condition, $data, $metaData);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('politicians.index');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());
            return redirect()->route('politicians.index');
        }
    }

    /**
     * Method to delete Politician Data through id
     * 
     * @param $id
     */
    public function delete($id=null){
        $data = Politician::find($id)->delete();
        \Session::flash('success',trans('message.success'));
        return redirect()->route('politicians.index');
    }
}
