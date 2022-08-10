<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessionRequest;
use App\Http\Requests\UpdateLessionRequest;
use App\Models\Lession;

class LessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLessionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLessionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lession  $lession
     * @return \Illuminate\Http\Response
     */
    public function show(Lession $lession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lession  $lession
     * @return \Illuminate\Http\Response
     */
    public function edit(Lession $lession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLessionRequest  $request
     * @param  \App\Models\Lession  $lession
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLessionRequest $request, Lession $lession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lession  $lession
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lession $lession)
    {
        //
    }
}
