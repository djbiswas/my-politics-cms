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
                                $btn = '<a href="'.route('get.page',$row->id).'">Edit </a> | <a class="btn-delete" href="'.route('delete.page',$row->id).'">Delete </a>';
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
            $condition = [];
            if($data['id']){
                $condition = ['id' => $data['id']];
            }
            $this->pageRepository->saveData($condition, $data);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('pages.index');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());
            return redirect()->route('pages.pageform');
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