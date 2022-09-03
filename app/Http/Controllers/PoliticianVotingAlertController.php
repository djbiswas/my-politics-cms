<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePoliticianVotingAlertRequest;
use App\Http\Requests\UpdatePoliticianVotingAlertRequest;
use App\Models\Politician;
use App\Models\PoliticianVotingAlert;
use Yajra\DataTables\DataTables;
Use App\Services\CommonService;

class PoliticianVotingAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // $data = PoliticianVotingAlert::with('politician')->with('user')->get();
        // return $data = Politician::with('politicianVotingAlert')->get();

        try {
            if ($request->ajax()) {

                // $data = PoliticianVotingAlert::with('politician')->with('user')->get();
                $data = Politician::with('politicianVotingAlert')->get();


                return Datatables::of($data)
                        ->addIndexColumn()

                        // ->editColumn('updated_at',function($row){
                        //     if(empty($row->updated_at)){
                        //         return '';
                        //     }
                        //     return date('Y-m-d H:i',strtotime($row->updated_at));
                        // })

                        ->addColumn('action', function($row){
                            $btn = '<a href="'.route('get.pva',$row->id).'">Set Vote Date</a>';
                            // $btn .= '<form method="POST" action="'.route('post.delete', $row->id).'" style="float:right;">
                            //             <input type="hidden" name="_token" value="'.csrf_token().'">
                            //             <input name="_method" type="hidden" value="DELETE">
                            //             <a href="javascript:void(0)" class="btn-delete" onclick="return DeleteFunction($(this))"> Delete</a>
                            //         </form>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }

            return view('politician_voating_alerts.listing');
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function getPVA($id)
    {
        $politician = Politician::find($id);
        return view('politician_voating_alerts.pvform',compact('id','politician'));
    }

    /**
     * Store Vote date
     **/
    public function postPVA(Request $request){

        // return $request->all();

        $pva = new PoliticianVotingAlert();
        $pva->politician_id = $request->id;
        $pva->user_id = $request->user_id;
        $pva->date = $request->date;
        $done = $pva->save();

        if($done){
            \Session::flash('success',trans('message.success'));
            return redirect()->route('politician.voting.alerts');
        }


    }


}
