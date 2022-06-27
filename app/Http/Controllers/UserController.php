<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Datatables;
use App\Models\User;
use App\Repositories\RankRepository;


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
        try {
            $ranks = $this->rankRepository->fetchAllData([])->toArray();
            if ($request->ajax()) {
                $data = User::leftJoin('ranks as r', 'users.rank_id','=','r.id')
                        ->select('users.*', 'r.title')
                        ->where('users.role_id','>',1);
                       
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
                                $btn = '<a href="'.route('get.user',$row->id).'">Edit </a> | <a class="btn-delete" href="{{route("get.politician")}}/'.$row->id.'">Delete </a>';
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

    /**
     * Method to get user data through id
     * 
     * @param $id
     */
    public function getUser($id=null){
        $data=User::find($id);
        $ranks = $this->rankRepository->fetchAllData([])->toArray();
        return view('users.userform',['data'=>$data, 'ranks'=>$ranks]);
    }

    /**
     * Method to post user data
     * 
     */
    public function postUser(Request $request){
        $data=$request->all();
        try{
            $condition = [];
            if($data['id']){
                $condition = ['id' => $data['id']];
            }
            $metaData = ($data['meta'])? $data['meta'] : [];
            unset($data['meta']);
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
}
