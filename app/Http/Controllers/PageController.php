<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PageRepository;
use Datatables;
use App\Models\Page;
use Illuminate\Support\Str;


class PageController extends Controller
{
    /**
     * @var pageRepository
     */
    private $pageRepository;

    /**
     * @var commonService
     */
    private $commonService;

    public function __construct(PageRepository $pageRepository) {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Display a listing of the pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        try {
            if ($request->ajax()) {
                $data = Page::select('*');
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('status',function($row){
                            if($row->status == 1){
                                return 'Active';
                            }
                            return "InActive";
                        })
                        ->addColumn('action', function($row){
                                $btn = '<a href="'.route('get.page',$row->id).'">Edit </a> | ';
                                $btn .= '<form method="POST" action="'.route('pages.delete', $row->id).'" style="float:right;">
                                            <input type="hidden" name="_token" value="'.csrf_token().'">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <a href="javascript:void(0)" class="btn-delete" onclick="return DeleteFunction($(this))"> Delete</a>
                                        </form>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            return view('pages.listing');
        } catch (\Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * Method to get Page Data through id
     * 
     * @param $id
     */
    public function getPage($id=null){
        $getRank=Page::find($id);
        return view('pages.pageform',['data'=>$getRank]);
    }

    /**
     * Method to post page data
     * 
     */
    public function postPage(Request $request){
        $data=$request->all();
        try{
            $condition = ['id' => $data['id']];
            $this->pageRepository->saveData($condition, $data);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('pages.index');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());
            return redirect()->route('pages.index');
        }
    }

    /**
     * Method to check exist name
     * 
     */
    public function checkName(Request $request){
        $data=$request->all();
        $id = $request->header('page-id');
        try{
            if(empty($id)){
                $existData = Page::where('page_name', $data['page_name'])->first();
            }else{
                $existData = Page::where('page_name', $data['page_name'])->where('id','!=', $id)->first();
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
     * Write code on Method
     *
     * @return response()
     */
    public function delete($id){
        Page::find($id)->delete();
        \Session::flash('success',trans('message.success'));
        return back();
    }
}
