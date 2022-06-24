<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use Datatables;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * @var categoryRepository
     */
    private $categoryRepository;

    /**
     * @var commonService
     */
    private $commonService;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        try {
            if ($request->ajax()) {
                $data = Category::select('*');
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('updated_at',function($row){
                            return date('Y-m-d H:i', strtotime($row->updated_at));
                        })
                        ->addColumn('action', function($row){
                                $btn = '<a href="'.route('get.category',$row->id).'">Edit </a>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            return view('categories.main-category',['data'=>[]]);
        } catch (\Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * Method to get Category Data through id
     * 
     * @param $id
     */
    public function getCategory($id=null){
        $getData=Category::select('*')->where(['id' => $id])->first();
        return view('categories.main-category',['data'=>$getData]);
    }

    /**
     * Method to post category data
     * 
     */
    public function postCategory(Request $request){
        $data=$request->all();
        try{
            if ($request->hasFile('icon')) {
                $image      = $request->file('icon');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
                $img = \Image::make($image->getRealPath());
                $img->stream();
                \Storage::disk(config('constants.disk.driver'))->put('public/'.config('constants.image.category').'/'.$fileName, $img);
                $data['icon'] = $fileName;
            }
            unset($data['Save']);
            unset($data['_token']);
            $condition = [];
            if($data['id']){
                $condition = ['id' => $data['id']];
            }
            $rank = $this->categoryRepository->saveData($condition, $data);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('categories.index');
        }catch (\Exception $e){
            \Session::flash('error',$e->getMessage());
            return redirect()->route('categories.index');
        }
    }

    /**
     * Method to check exist name for category
     * 
     */
    public function checkName(Request $request){
        $data=$request->all();
        $id = $request->header('category-id');
        try{
            if(empty($id)){
                $existData = Category::where('name', $data['name'])->first();
            }else{
                $existData = Category::where('name', $data['name'])->where('id','!=', $id)->first();
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
}
