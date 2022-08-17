<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\RoleRepository;
use Datatables;
use App\Models\Role;
use Illuminate\Support\Str;
Use App\Services\CommonService;

class RoleController extends Controller
{
    //
    /**
     * @var roleRepository
     */
    private $roleRepository;

    /**
     * @var commonService
     */
    private $commonService;

    public function __construct(RoleRepository $roleRepository) {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $status_datas = ['0'=>'InActive', '1'=>'Active'];
        try {
            if ($request->ajax()) {
                $data = Role::get();
                // $data = Role::select('*');
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
                                $btn = '<a href="'.route('get.role',$row->id).'">Edit </a>';
                                return $btn;
                        })
                        ->rawColumns(['image','action'])
                        ->make(true);
            }
            return view('roles.main-role',['data'=>[],'status_datas'=>$status_datas]);
        } catch (\Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * Method to get role Data through id
     *
     * @param $id
     */
    public function getrole($id=null){
        $status_datas = ['0'=>'InActive', '1'=>'Active'];
        $getrole=Role::find($id);
        return view('roles.main-role',['data'=>$getrole, 'status_datas'=>$status_datas]);
    }

    /**
     * Method to post role data
     *
     */
    public function postrole(Request $request){
         $data=$request->all();
        try{

            $condition = ['id' => $data['id']];
            $this->roleRepository->saveData($condition, $data);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('roles.index');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());
            return redirect()->route('roles.index');
        }
    }

    /**
     * Method to check exist title for role
     *
     */
    public function checkTitle(Request $request){
        $data=$request->all();
        $id = $request->header('role-id');
        try{
            if(empty($id)){
                $existData = Role::where('role', $data['role'])->first();
            }else{
                $existData = Role::where('role', $data['role'])->where('id','!=', $id)->first();
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
