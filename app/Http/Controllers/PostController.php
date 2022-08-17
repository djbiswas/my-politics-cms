<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Politician;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Repositories\PoliticianRepository;
Use App\Services\CommonService;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    //
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var PoliticianRepository
     */
    private $politicianRepository;


     /**
     * @var commonService
     */
    private $commonService;

    public function __construct(PostRepository $postRepository, PoliticianRepository $politicianRepository, CommonService $commonService) {
        $this->postRepository = $postRepository;
        $this->commonService = $commonService;
        $this->politicianRepository = $politicianRepository;
    }


    public function index(Request $request){
         $posts = Post::with('politician')->with('user')->get();

        try {
            if ($request->ajax()) {
                // $data = Post::get();
                $data = Post::with('politician')->with('user')->get();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('content',function($row){
                            return $this->commonService->limit_text($row->content,10);
                        })
                        ->editColumn('updated_at',function($row){
                            if(empty($row->updated_at)){
                                return '';
                            }
                            return date('Y-m-d H:i',strtotime($row->updated_at));
                        })
                        ->addColumn('action', function($row){
                            $btn = '<a href="'.route('get.post',$row->id).'">Edit </a> |';
                            $btn .= '<form method="POST" action="'.route('post.delete', $row->id).'" style="float:right;">
                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                        <input name="_method" type="hidden" value="DELETE">
                                        <a href="javascript:void(0)" class="btn-delete" onclick="return DeleteFunction($(this))"> Delete</a>
                                    </form>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }

            return view('posts.listing');
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }


    /**
     * Method to get post-data
     *
     */
    public function getPost($id=null){
        if($id){
             $data = Post::find($id)->with('user')->with('politician')->first();
            return view('posts.editform',['data'=>$data]);
        }

        return view('posts.editform',['data'=>[]]);

    }


    /**
     * Method to post post-data
     *
     */
    public function postPost(Request $request){
        $data=$request->all();
        try{
            $condition = ['id' => $data['id']];
            $this->postRepository->saveData($condition, $data);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('posts.index');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());

            return redirect()->route('posts.index');
        }
    }


    /**
     * Method to delete Post Data through id
     *
     * @param $id
     */
    public function delete($id=null){
        $data = Post::find($id)->delete();
        \Session::flash('success',trans('message.success'));
        return redirect()->route('posts.index');
    }
}
