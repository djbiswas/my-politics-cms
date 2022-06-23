<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\RankRepository;
use Datatables;
use App\Models\Rank;

class RankController extends Controller
{
    /**
     * @var rankRepository
     */
    private $rankRepository;

    /**
     * @var commonService
     */
    private $commonService;

    public function __construct(RankRepository $rankRepository) {
        $this->rankRepository = $rankRepository;
    }

    /**
     * Display a listing of the ranks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        try {
            if ($request->ajax()) {
                $data = Rank::select('*');
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('image',function($row){
                            if(!empty($row->image)){
                                $img = '<img src="'.asset($row->image).'" alt="'.$row->title.'"/>';
                                return $img;
                            }
                            return "";
                        })
                        ->addColumn('action', function($row){
                                $btn = '<a href="'.route('get.rank',$row->id).'">Edit </a>';
                                return $btn;
                        })
                        ->rawColumns(['image','action'])
                        ->make(true);
            }
            return view('ranks.main-rank',['data'=>[]]);
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * Method to get Rank Data through id
     * 
     * @param $id
     */
    public function getRank($id=null){
        $getRank=Rank::select('*')->where(['id' => $id])->first();
        return view('ranks.main-rank',['data'=>$getRank]);
    }

    /**
     * Method to post politician data
     * 
     */
    public function postRank(Request $request){
        $data=$request->all();
        try{
            if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
                $img = \Image::make($image->getRealPath());
                $img->stream();
                \Storage::disk(config('constants.disk.driver'))->put('public/'.config('constants.image.rank').'/'.$fileName, $img);
                $data['image'] = $fileName;
            }
            $condition = [];
            if($data['id']){
                $condition = ['id' => $data['id']];
            }
            $rank = $this->rankRepository->saveData($condition, $data);
            \Session::flash('success','Data has been saved successfully.');
            return redirect()->route('ranks.index');
        }catch (\Exception $e){
            \Log::info($e->getMessage());
            \Session::flash('error',$e->getMessage());
            return redirect()->route('ranks.index');
        }
    }
    
    /**
     * Method to check exist title for rank
     * 
     */
    public function checkTitle(Request $request){
        $data=$request->all();
        $id = $request->header('post-id');
        try{
            if(empty($id)){
                $existData = Rank::where('title', $data['title'])->first();
            }else{
                $existData = Rank::where('title', $data['title'])->where('id','!=', $id)->first();
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
