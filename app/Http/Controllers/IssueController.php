<?php

namespace App\Http\Controllers;
use App\Repositories\IssueRepository;
use App\Http\Requests\StoreIssueRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use Yajra\DataTables\DataTables;
Use App\Services\CommonService;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     /**
     * @var commonService
     */
    private $commonService;



    public function index(Request $request)
    {
       // return $issues =  Issue::with('politician')->with('user')->get();

        try {
            if ($request->ajax()) {
                // $data = Issue::select('id', 'name', 'name_alias', 'position', 'politician_description', 'updated_at');
                $data = Issue::with('user')->with('politician')->get();
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
