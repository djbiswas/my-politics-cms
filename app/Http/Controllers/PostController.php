<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Politician;
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
        return $posts = Post::with('politician')->with('user')->get();

        try {
            if ($request->ajax()) {
                $data = Post::get();
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

            return view('posts.listing');
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }
}
