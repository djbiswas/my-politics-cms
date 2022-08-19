<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use App\Models\PermissionCategory;
use App\Repositories\PermissionRepository;
Use App\Services\CommonService;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    /**
     * @var PermissionRepository
     */
    private $PermissionRepository;

     /**
     * @var commonService
     */
    private $commonService;

    public function __construct(PermissionRepository $permissionRepository, CommonService $commonService) {
        $this->permissionRepository = $permissionRepository;
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $permission =  Permission::with('permission_category')->get();

        $permission_categoris = PermissionCategory::pluck('name','id');;
        try {
            if ($request->ajax()) {
                $data = Permission::with('permission_category')->get();
                return Datatables::of($data)
                        ->addIndexColumn()
                        // ->editColumn('content',function($row){
                        //     return $this->commonService->limit_text($row->content,10);
                        // })
                        ->editColumn('updated_at',function($row){
                            if(empty($row->updated_at)){
                                return '';
                            }
                            return date('Y-m-d H:i',strtotime($row->updated_at));
                        })
                        ->addColumn('action', function($row){
                            $btn = '<a href="'.route('get.permission',$row->id).'">Edit </a> |';
                            $btn .= '<form method="POST" action="'.route('issues.delete', $row->id).'" style="float:right;">
                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                        <input name="_method" type="hidden" value="DELETE">
                                        <a href="javascript:void(0)" class="btn-delete" onclick="return DeleteFunction($(this))"> Delete</a>
                                    </form>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }

            return view('permissions.main',['data'=>[],'permission_categoris'=>$permission_categoris]);
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
    public function getIssue($id=null){

        $status_datas = ['0'=>'InActive', '1'=>'Active'];

        // $politicians = $this->politicianRepository->fetchAllData([])->toArray();

        $politicians = Politician::pluck('name','id');
        $issueCategories = IssueCategory::pluck('title','id');
        if($id){
              $data=Issue::find($id);
            //$metaData = $data->getMeta()->toArray();
            return view('issues.issueFrom',['data'=>$data, 'politicians'=>$politicians, 'issueCategories'=>$issueCategories,'status_datas'=>$status_datas]);
        }
        return view('issues.issueFrom',['data'=>[]]);
    }


    public function postIssue(Request $request){

         $data=$request->all();
        try{
            // if ($request->hasFile('image')) {
            //     $data['image'] = $this->commonService->storeImage($request->file('image'), config('constants.image.politician'));
            // }
            // if ($request->hasFile('affiliation_icon')) {
            //     $data['affiliation_icon'] = $this->commonService->storeImage($request->file('affiliation_icon'), config('constants.image.politician'), 'affiliation_icon');
            // }
            $condition = ['id' => $data['id']];
            $this->issueRepository->saveData($condition, $data);

            \Session::flash('success',trans('message.success'));
            return redirect()->route('issues.index');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());
            return redirect()->route('issues.index');
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
     * @param  \App\Http\Requests\StoreIssueRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIssueRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function edit(Issue $issue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateIssueRequest  $request
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        //
    }


    /**
     * Method to delete Politician Data through id
     *
     * @param $id
     */
    public function delete($id=null){
        $data = Issue::find($id)->delete();
        \Session::flash('success',trans('message.success'));
        return redirect()->route('issues.index');
    }
}
