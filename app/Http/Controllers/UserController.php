<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Datatables;
use App\Models\User;
use App\Repositories\RankRepository;
use App\Http\Controllers\csrf;
use App\Models\Role;
use App\Models\UserWarn;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @var userRepository
     */
    private $userRepository;

    /**
     * @var rankRepository
     */
    private $rankRepository;
    //

    public function __construct(UserRepository $userRepository, RankRepository $rankRepository) {
        $this->userRepository = $userRepository;
        $this->rankRepository = $rankRepository;
    }

    /**
     * Display a listing of the politicians.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        // return User::get();
        try {
            $ranks = $this->rankRepository->fetchAllData([])->toArray();
            if ($request->ajax()) {
                $data = User::with(['ranks' => function($q){
                    $q->select('id','title');
                }])
                ->with(['role' => function($q){
                    $q->select('id','role');
                }])
                ->select('users.id','rank_id', 'users.first_name', 'users.last_name', 'users.email', 'users.phone','role_id')
                ->where('users.role_id','>',2);

                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('ranks.title',function($row){
                            if(!empty($row->ranks)){
                                return $row->ranks->title;
                            }
                            return "";
                        })
                        ->addColumn('action', function($row){
                                $btn = '<a href="'.route('get.user',$row->id).'">Edit </a> |';
                                $btn .= '<form method="POST" action="'.route('users.delete', $row->id).'" style="float:right;">
                                            <input type="hidden" name="_token" value="'.csrf_token().'">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <a href="javascript:void(0)" class="btn-delete" onclick="return DeleteFunction($(this))"> Delete</a>
                                        </form>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }

            return view('users.listing', ['ranks'=>$ranks]);
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }


    public function admin_users(Request $request){
        try {
            $ranks = $this->rankRepository->fetchAllData([])->toArray();
            if ($request->ajax()) {
                $data = User::with(['ranks' => function($q){
                    $q->select('id','title');
                }])
                ->with(['role' => function($q){
                    $q->select('id','role');
                }])
                ->select('users.id','rank_id', 'users.first_name', 'users.last_name', 'users.email', 'users.phone','role_id')
                ->where('users.role_id','<',3);

                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('ranks.title',function($row){
                            if(!empty($row->ranks)){
                                return $row->ranks->title;
                            }
                            return "";
                        })
                        ->addColumn('action', function($row){
                                $btn = '<a href="'.route('get.admin',$row->id).'">Edit </a> |';
                                $btn .= '<form method="POST" action="'.route('users.delete', $row->id).'" style="float:right;">
                                            <input type="hidden" name="_token" value="'.csrf_token().'">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <a href="javascript:void(0)" class="btn-delete" onclick="return DeleteFunction($(this))"> Delete</a>
                                        </form>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }

            return view('users.admins', ['ranks'=>$ranks]);
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * Method to get user data through id
     *
     * @param $id
     */

    public function getUser($id=null){
        $ranks = $this->rankRepository->fetchAllData([])->toArray();
        // $roles = Role::where('id', '>', '2')->pluck('role','id');

        $roles = Role::pluck('role','id');
        if($id){
            $user_warns = UserWarn::where('user_id',$id)->get();
            $data=User::find($id);
            $metaData = $data->getMeta()->toArray();
            return view('users.userform',['data'=>$data, 'ranks'=>$ranks, 'roles'=>$roles,'metaData'=>$metaData,'user_warns'=>$user_warns]);
        }
        return view('users.userform',['data'=>[], 'ranks'=>$ranks, 'roles'=>$roles,'metaData'=>[]]);
    }

    public function getAdmin($id=null){
        $ranks = $this->rankRepository->fetchAllData([])->toArray();
        $roles = Role::where('id', '<', '3')->pluck('role','id');
        if($id){
            $data = User::find($id);
            // return $data;
            $metaData = $data->getMeta()->toArray();
            return view('users.adminform',['data'=>$data, 'ranks'=>$ranks, 'roles'=>$roles, 'metaData'=>$metaData]);
        }
        return view('users.adminform',['data'=>[], 'ranks'=>$ranks, 'roles'=>$roles, 'metaData'=>[]]);
    }

    /**
     * Method to post user data
     *
     */
    public function postUser(Request $request){
        $data=$request->all();
        try{
            $condition = ['id' => $data['id']];
            $metaData = ($data['meta'])? $data['meta'] : [];
            // $data['role_id'] = config('constants.role.user');
            $this->userRepository->saveData($condition, $data, $metaData);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('users.index');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());
            return redirect()->route('users.index');
        }
    }

    /**
     * Method to post user warn
     *
     */
    public function postWarn(Request $request){
        //$request->all();

        $user_id = $request->id;
        $warn_message = $request->warn_message;

        $user = User::find($user_id);
        $user->user_warn = 1;
        $user->save();

        $user_warn = New UserWarn();
        $user_warn->user_id = $user_id;
        $user_warn->warn_message = $warn_message;
        $user_warn->save();

        \Session::flash('success',trans('message.success'));
        return redirect()->route('get.user',$user_id);

    }


    /**
     * Method to post user BAN
     *
     */
    public function postBan(Request $request){
        // return $request->all();

        if($request->user_ban){
            $user_id = $request->id;
            $user = User::find($user_id);
            $user->user_ban = 1;
            $user->ban_till = $request->ban_till;
            $user->ban_reason = $request->ban_reason;
            $user->save();
        }else{
            $user_id = $request->id;
            $user = User::find($user_id);
            $user->user_ban = 0;
            $user->ban_till = null;
            $user->ban_reason = null;
            $user->save();
        }

        \Session::flash('success',trans('message.success'));
        return redirect()->route('get.user',$user_id);

    }



    /**
     * Method to post user BLOCK
     *
     */
    public function postBlock(Request $request){
        // return $request->all();

        if($request->user_block){
            $user_id = $request->id;
            $user = User::find($user_id);
            $user->user_block = 1;
            $user->block_reason = $request->block_reason;
            $user->save();
        }else{
            $user_id = $request->id;
            $user = User::find($user_id);
            $user->user_block = 0;
            $user->block_reason = null;
            $user->save();
        }

        \Session::flash('success',trans('message.success'));
        return redirect()->route('get.user',$user_id);

    }


    public function postAdmin(Request $request){
         $data=$request->all();

        // $validateData = $request->validate([
        //     'id' => 'sometimes',
        //     'email' => 'required',
        //     'phone' => 'required',
        //     'meta' => 'required',
        //     'first_name' => 'required',
        //     'last_name' => 'required',
        //     'password' => 'sometimes',
        //     're_password' => 'sometimes',
        //     'role_id' => 'required',
        //     'status' => 'required',
        // ]);

        //   if($request->filled('password')){
        //     return  $request->password;
        //   }

        try{
            $condition = ['id' => $data['id']];
            $metaData = ($data['meta'])? $data['meta'] : [];
            // $data['role_id'] = config('constants.role.user');
             $this->userRepository->saveData($condition, $data, $metaData);
            \Session::flash('success',trans('message.success'));
            return redirect()->route('admin.users');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());
            return redirect()->route('admin.users');
        }
    }

    /**
     * Method to check exist email
     *
     */
    public function checkEmail(Request $request){
        $data=$request->all();
        $id = $request->header('user-id');
        try{
            if(empty($id)){
                $existData = User::where('email', $data['email'])->first();
            }else{
                $existData = User::where('email', $data['email'])->where('id','!=', $id)->first();
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
     * Method to check exist phone
     *
     */
    public function checkPhone(Request $request){
        $data=$request->all();
        $id = $request->header('user-id');
        try{
            if(empty($id)){
                $existData = User::where('phone', $data['phone'])->first();
            }else{
                $existData = User::where('phone', $data['phone'])->where('id','!=', $id)->first();
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
     * Write code on Method
     *
     * @return response()
     */
    public function delete($id){
        User::find($id)->delete();
        return back();
    }
}
