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

    public function __construct(RolePermissionRepository $rolePermissionRepository, PermissionRepository        $permissionRepository, RoleRepository $roleRepository, CommonService $commonService) {
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

        $roles = Role::where('id','>','1')->pluck('role', 'id');

        try {
            if ($request->ajax()) {
                // $data = RolePermission::with('permission')->with('role')->get();
                $data = Role::where('id','>','1')->get();
                //  $data = RolePermission::where('role_id', '>', '1')->select('id','role_id')->groupBy('role_id')->with('role')->get();
                return Datatables::of($data)
                        ->addIndexColumn()

                        ->addColumn('action', function($row){
                            $btn = '<a href="'.route('get.role.permission',$row->id).'">Permissions </a> |';
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

        $roles = Role::where('id','>','1')->pluck('role', 'id');
        $permissions = PermissionCategory::with('permission')->get();
        $permission_categoris = PermissionCategory::pluck('name','id');

        if($id){
            $role = Role::where('id', $id)->first();
            $role_id = $role->id;
            $role_name = $role->role;
            $data = RolePermission::where('role_id', $id)->with('permission')->get();

            $permission_ids = [];
            foreach($data as $rolePermission){
                array_push($permission_ids, $rolePermission->permission_id);
            }

            return view('role_permissions.main',['data'=>$data, 'permissions'=>$permissions, 'permission_ids'=> $permission_ids, 'roles'=>$roles,'role_id'=>$role_id, 'role_name'=>$role_name]);
        }
        return view('role_permissions.main',['data'=>[]]);
    }




    public function postRolePermission(Request $request){

        // return $data=$request->all();

            // $condition = ['id' => $data['id']];
            if($request->permission_id){

                $role_id = $request->role_id;

                RolePermission::where('role_id', $role_id)->delete();

                foreach($request->permission_id as $permission_id){
                    $rolePermission = new RolePermission();
                    $rolePermission->role_id = $role_id;
                    $rolePermission->permission_id = $permission_id;
                    $done = $rolePermission->save();
                }

                if($done){
                    \Session::flash('success',trans('message.success'));
                    return redirect()->route('get.role.permission',$role_id);
                }
            }

    }

    /**
     * Method to delete Politician Data through id
     *
     * @param $id
     */
    public function delete($id=null){

        $role_permissions = RolePermission::where('role_id',$id)->get();
        foreach($role_permissions as $role_permission){
            $data = RolePermission::find($role_permission->id)->delete();
        }
        \Session::flash('success',trans('message.success'));
        return redirect()->route('role.permissions.index');
    }

}
