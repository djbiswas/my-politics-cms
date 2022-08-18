<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePermissionCategoryRequest;
use App\Http\Requests\UpdatePermissionCategoryRequest;
use App\Models\PermissionCategory;
use Datatables;
use App\Repositories\PermissionCategoryRepository;
Use App\Services\CommonService;

class PermissionCategoryController extends Controller
{
    /**
     * @var PermissionCategoryRepository
     */
    private $permissionCategoryRepository;

    /**
     * @var commonService
     */
    private $commonService;

    public function __construct(PermissionCategoryRepository $permissionCategoryRepository) {
        $this->permissionCategoryRepository = $permissionCategoryRepository;
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
                $data = PermissionCategory::select('*');
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('updated_at',function($row){
                            return date('Y-m-d H:i', strtotime($row->updated_at));
                        })
                        ->addColumn('action', function($row){
                                $btn = '<a href="'.route('get.permission_category',$row->id).'">Edit </a>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            $status_datas = ['0'=>'InActive', '1'=>'Active'];
            return view('permission_categories.main-category',['data'=>[], 'status_datas'=>$status_datas]);
        } catch (\Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }


    /**
     * Method to get Category Data through id
     *
     * @param $id
     */
    public function getPermissionCategory($id=null){
        $data=PermissionCategory::find($id);
        return view('permission_categories.main-category',compact('data'));
    }


     /**
     * Method to post category data
     *
     */
    public function postPermissionCategory(Request $request){
        $data=$request->all();
        try{

            $condition = ['id' => $data['id']];
            $this->permissionCategoryRepository->saveData($condition, $data);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('permission_categories.index');
        }catch (\Exception $e){
            \Session::flash('error',$e->getMessage());
            return redirect()->route('permission_categories.index');
        }
    }


    /**
     * Method to check exist name for category
     *
     */
    public function checkName(Request $request){
        $data=$request->all();
        $id = $request->header('permissionCategory-id');
        try{
            if(empty($id)){
                $existData = PermissionCategory::where('name', $data['name'])->first();
            }else{
                $existData = PermissionCategory::where('name', $data['name'])->where('id','!=', $id)->first();
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
