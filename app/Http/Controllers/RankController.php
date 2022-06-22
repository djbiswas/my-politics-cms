<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\RankRepository;
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
                $data = Rank::select('*')->where(['deleted_at' => null]);
                return \Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('image',function($row){
                            if(!empty($row->image)){
                                if (str_contains($row->image, 'uploads')) { 
                                    $path = asset($row->image);
                                }else{
                                    $path = asset('storage/rank/'.$row->image);
                                }
                                $img = '<img src="'.$path.'" alt="'.$row->title.'"/>';
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
        $path = "";
        if($getRank->image){
            if (str_contains($getRank->image, 'uploads')) { 
                $path = asset($getRank->image);
            }else{
                $path = asset('storage/rank/'.$getRank->image);
            }
        }
        return view('ranks.main-rank',['data'=>$getRank, 'path'=>$path]);
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
                // $img->resize(120, 120, function ($constraint) {
                //     $constraint->aspectRatio();                 
                // });
                $img->stream(); // <-- Key point
                //dd();
                \Storage::disk('local')->put('public/rank/'.$fileName, $img, 'public');
                $data['image'] = $fileName;
            }
            unset($data['Save']);
            unset($data['_token']);
            $condition = [];
            if($data['id']){
                $condition = ['id' => $data['id']];
            }
            $rank = $this->rankRepository->saveData($condition, $data);
            \Session::flash('success','Data has been saved successfully.');
            return redirect()->route('ranks.index');
        }catch (\Exception $e){
            echo $e->getMessage();exit;
            \Session::flash('error',$e->getMessage());
            return redirect()->route('ranks.index');
        }
    }
}
