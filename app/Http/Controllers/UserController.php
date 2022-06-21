<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Datatables;
use App\Models\User;


class UserController extends Controller
{
    /**
     * @var userRepository
     */
    private $userRepository;
    //

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the politicians.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        try {
            if ($request->ajax()) {
                $data = User::leftJoin('ranks as r', 'users.rank_id','=','r.id')
                        ->select('users.*', 'r.title','r.id')
                        ->where(['users.deleted_at' => null]);
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
                                $btn = '<a href="{{route("get.politician")}}/'.$row->id.'">Edit </a> | <a class="btn-delete" href="{{route("get.politician")}}/'.$row->id.'">Delete </a>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
              
            return view('users.listing');
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }
}
