<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostFlagRequest;
use App\Http\Requests\UpdatePostFlagRequest;
use Illuminate\Http\Request;
use App\Models\PostFlag;
use Datatables;
Use App\Services\CommonService;

class PostFlagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data = PostFlag::with('post')->with('politician')->with('user')->get();
        try {
            if ($request->ajax()) {
                $data = PostFlag::with('post')->with('politician')->with('user')->get();
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
                            $btn = '<a href="'.route('get.flag',$row->id).'">Edit </a> |';
                            $btn .= '<form method="POST" action="'.route('flag.delete', $row->id).'" style="float:right;">
                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                        <input name="_method" type="hidden" value="DELETE">
                                        <a href="javascript:void(0)" class="btn-delete" onclick="return DeleteFunction($(this))"> Delete</a>
                                    </form>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }

            return view('flag_post.listing');
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }


        /**
     * Method to get post-flag data
     *
     */
    public function getFlag($id=null){
        if($id){
            $data = PostFlag::where('id',$id)->with('post')->with('politician')->with('user')->first();
            return view('flag_post.editform',['data'=>$data]);
        }

        return view('flag_post.editform',['data'=>[]]);

    }


    /**
     * Method to post post-data
     *
     */
    public function postFlag(Request $request){
        $data=$request->all();

        $condition = ['id' => $data['id']];
        $postFlag = PostFlag::find($request->id);
        $postFlag->status = $request->status;
        $done = $postFlag->save();

        if($done){
            \Session::flash('success',trans('message.success'));
            return redirect()->route('flag.index');
        }else{
            \Log::info('Some Error Happend');
            \Session::flash('error',trans('message.error'));
            return redirect()->route('flag.index');
        }
    }


    /**
     * Method to delete Post Data through id
     *
     * @param $id
     */
    public function delete($id=null){
        $data = PostFlag::find($id)->delete();
        \Session::flash('success',trans('message.success'));
        return redirect()->route('flag.index');
    }




}
