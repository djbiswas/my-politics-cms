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
                            $btn .= '<form method="POST" action="'.route('permission.delete', $row->id).'" style="float:right;">
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
    public function getPermission($id=null){

        $permission_categoris = PermissionCategory::pluck('name','id');
        if($id){

            $data=Permission::find($id);
            return view('permissions.main',['data'=>$data, 'permission_categoris'=>$permission_categoris,]);
        }
        return view('permissions.main',['data'=>[]]);
    }


    public function postPermission(Request $request){

        $data=$request->all();
        try{
            $condition = ['id' => $data['id']];
            $this->permissionRepository->saveData($condition, $data);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('permissions.index');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());
            return redirect()->route('permissions.index');
        }
    }


    /**
     * Method to delete Politician Data through id
     *
     * @param $id
     */
    public function delete($id=null){
        $data = Permission::find($id)->delete();
        \Session::flash('success',trans('message.success'));
        return redirect()->route('permissions.index');
    }
}
