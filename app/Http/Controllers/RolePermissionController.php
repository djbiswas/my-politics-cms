<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreRolePermissionRequest;
use App\Http\Requests\UpdateRolePermissionRequest;
use App\Models\RolePermission;
use App\Models\Permission;
use App\Models\PermissionCategory;
use App\Models\Role;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\RolePermissionRepository;
Use App\Services\CommonService;
use Yajra\DataTables\DataTables;

class RolePermissionController extends Controller
{
    /**
     * @var RolePermissionRepository
     */
    private $rolePermissionRepository;

    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

     /**
     * @var commonService
     */
    private $commonService;

    public function __construct(RolePermissionRepository $rolePermissionRepository, PermissionRepository $permissionRepository, RoleRepository $roleRepository, CommonService $commonService) {
        $this->rolePermissionRepository = $rolePermissionRepository;
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
        $this->commonService = $commonService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$permissions = Permission::pluck('name','id');
        $permissions = PermissionCategory::with('permission')->get();
        // $rolePermissions = RolePermission::with('permission')->with('role')->get();
        // return $data = Role::get();

        $roles = Role::pluck('role', 'id');
        try {
            if ($request->ajax()) {
                // $data = RolePermission::with('permission')->with('role')->get();
                $data = Role::get();
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
                            $btn = '<a href="'.route('get.role.permission',$row->id).'">Edit </a> |';
                            $btn .= '<form method="POST" action="'.route('role.permission.delete', $row->id).'" style="float:right;">
                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                        <input name="_method" type="hidden" value="DELETE">
                                        <a href="javascript:void(0)" class="btn-delete" onclick="return DeleteFunction($(this))"> Delete</a>
                                    </form>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }

            return view('role_permissions.main',['data'=>[],'permissions'=>$permissions, 'roles'=>$roles ]);
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
    public function getRolePermission($id=null){

        $roles = Role::pluck('role', 'id');
        $permissions = PermissionCategory::with('permission')->get();
        $permission_categoris = PermissionCategory::pluck('name','id');

        if($id){
            $data = RolePermission::where('id', $id)->with('role')->first();
            $role_id = $data->role_id;
            $role_name = $data->role->role;
            $data = RolePermission::where('role_id', $role_id)->with('permission')->get();

            $permission_ids = [];
            foreach($data as $rolePermission){
                array_push($permission_ids, $rolePermission->permission_id);
            }


            return view('role_permissions.main',['data'=>$data, 'permissions'=>$permissions, 'permission_ids'=> $permission_ids, 'roles'=>$roles,'role_id'=>$role_id, 'role_name'=>$role_name]);
        }
        return view('role_permissions.main',['data'=>[]]);
    }




    public function postRolePermission(Request $request){
         $data=$request->all();
        try{
            // $condition = ['id' => $data['id']];
            if($request->permission_id){
                $role_id = $request->role_id;
                foreach($request->permission_id as $permission_id){
                    $rolePermission = new RolePermission();
                    $rolePermission->role_id = $role_id;
                    $rolePermission->permission_id = $permission_id;
                    $done = $rolePermission->save();
                }
            }

            //$this->rolePermissionRepository->saveData($condition, $data);

            \Session::flash('success',trans('message.success'));
            return redirect()->route('role.permissions.index');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());
            return redirect()->route('role.permissions.index');
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
