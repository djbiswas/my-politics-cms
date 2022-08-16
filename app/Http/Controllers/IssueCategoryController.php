<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\IssueCategoryRepository;
use Datatables;
use App\Http\Requests\StoreIssueCategoryRequest;
use App\Http\Requests\UpdateIssueCategoryRequest;
use App\Models\IssueCategory;
Use App\Services\CommonService;

class IssueCategoryController extends Controller
{
    /**
     * @var issueCategoryRepository
     */
    private $issueCategoryRepository;

    /**
     * @var commonService
     */
    private $commonService;

    public function __construct(IssueCategoryRepository $issueCategoryRepository) {
        $this->issueCategoryRepository = $issueCategoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = IssueCategory::select('*');
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('updated_at',function($row){
                            return date('Y-m-d H:i', strtotime($row->updated_at));
                        })
                        ->addColumn('action', function($row){
                                $btn = '<a href="'.route('get.issue_category',$row->id).'">Edit </a>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            $status_datas = ['0'=>'InActive', '1'=>'Active'];
            return view('issue_categories.main-category',['data'=>[], 'status_datas'=>$status_datas]);
        } catch (\Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }


    /**
     * Method to get Category Data through id
     *
     * @param $id
     */
    public function getIssueCategory($id=null){
        $data=IssueCategory::find($id);
        $status_datas = ['0'=>'InActive', '1'=>'Active'];
        // return view('issue_categories.main-category',['data'=>$issueCategory, 'status_datas'=>$status_datas]);
        return view('issue_categories.main-category',compact('data','status_datas'));
    }


     /**
     * Method to post category data
     *
     */
    public function postIssueCategory(Request $request){
         $data=$request->all();
        try{

            if ($request->hasFile('image')) {
                $commonService = new CommonService();
                $data['image'] = $commonService->storeImage($request->file('image'), config('constants.image.issue_category'), 'image');
            }

            $condition = ['id' => $data['id']];
            $this->issueCategoryRepository->saveData($condition, $data);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('issue_categories.index');
        }catch (\Exception $e){
            \Session::flash('error',$e->getMessage());
            return redirect()->route('issue_categories.index');
        }
    }


    /**
     * Method to check exist name for category
     *
     */
    public function checkName(Request $request){
        $data=$request->all();
        $id = $request->header('issueCategory-id');
        try{
            if(empty($id)){
                $existData = IssueCategory::where('title', $data['title'])->first();
            }else{
                $existData = IssueCategory::where('title', $data['title'])->where('id','!=', $id)->first();
            }
            if (!empty($existData)) {
                $flag = 'false';
            } else {
                $flag = 'true';
            }
            return $flag;
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            return response()->json(['code'=>300,'msg'=>"Error - ".$e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreIssueCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIssueCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IssueCategory  $issueCategory
     * @return \Illuminate\Http\Response
     */
    public function show(IssueCategory $issueCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IssueCategory  $issueCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(IssueCategory $issueCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateIssueCategoryRequest  $request
     * @param  \App\Models\IssueCategory  $issueCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIssueCategoryRequest $request, IssueCategory $issueCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IssueCategory  $issueCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssueCategory $issueCategory)
    {
        //
    }
}
