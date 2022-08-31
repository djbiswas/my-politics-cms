<?php

namespace App\Http\Controllers;
use App\Repositories\IssueRepository;
use App\Http\Requests\StoreIssueRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\IssueCategory;
use App\Models\Politician;
use App\Repositories\PoliticianRepository;
use Yajra\DataTables\DataTables;
Use App\Services\CommonService;

class IssueController extends Controller
{
    /**
     * @var IssueRepository
     */
    private $issueRepository;


    /**
     * @var PoliticianRepository
     */
    private $politicianRepository;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     /**
     * @var commonService
     */
    private $commonService;

    public function __construct(IssueRepository $issueRepository, PoliticianRepository $politicianRepository, CommonService $commonService) {
        $this->issueRepository = $issueRepository;
        $this->commonService = $commonService;
        $this->politicianRepository = $politicianRepository;
    }


    public function index(Request $request)
    {
        $issues =  Issue::with('politician')->with('user')->with('issue_category')->get();
        try {
            if ($request->ajax()) {
                // $data = Issue::select('id', 'name', 'name_alias', 'position', 'politician_description', 'updated_at');
                $data = Issue::with('user')->with('politician')->with('issue_category')->get();
                // $data = Issue::select('id', 'user_id', 'politician_id', 'name', 'content', 'updated_at');
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
                            $btn = '<a href="'.route('get.issue',$row->id).'">Edit </a> |';
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

            return view('issues.listing');
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
