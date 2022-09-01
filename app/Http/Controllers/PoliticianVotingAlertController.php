<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePoliticianVotingAlertRequest;
use App\Http\Requests\UpdatePoliticianVotingAlertRequest;
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

        $data = PoliticianVotingAlert::with('politician')->with('user')->get();

        try {
            if ($request->ajax()) {

                $data = PoliticianVotingAlert::with('politician')->with('user')->get();

                return Datatables::of($data)
                        ->addIndexColumn()

                        // ->editColumn('updated_at',function($row){
                        //     if(empty($row->updated_at)){
                        //         return '';
                        //     }
                        //     return date('Y-m-d H:i',strtotime($row->updated_at));
                        // })

                        ->addColumn('action', function($row){
                            $btn = '<a href="'.route('get.pva',$row->id).'">Edit </a> |';
                            $btn .= '<form method="POST" action="'.route('post.delete', $row->id).'" style="float:right;">
                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                        <input name="_method" type="hidden" value="DELETE">
                                        <a href="javascript:void(0)" class="btn-delete" onclick="return DeleteFunction($(this))"> Delete</a>
                                    </form>';
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
     *
     * @param  \App\Models\PoliticianVotingAlert  $politicianVotingAlert
     * @return \Illuminate\Http\Response
     */
    public function getPVA(PoliticianVotingAlert $politicianVotingAlert)
    {
        // need to work for edit and other
    }


}
