<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserBlockRequest;
use App\Http\Requests\UpdateUserBlockRequest;
use App\Models\UserBlock;

class UserBlockController extends Controller
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
     * @param  \App\Http\Requests\StoreUserBlockRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserBlockRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserBlock  $userBlock
     * @return \Illuminate\Http\Response
     */
    public function show(UserBlock $userBlock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserBlock  $userBlock
     * @return \Illuminate\Http\Response
     */
    public function edit(UserBlock $userBlock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserBlockRequest  $request
     * @param  \App\Models\UserBlock  $userBlock
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserBlockRequest $request, UserBlock $userBlock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserBlock  $userBlock
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserBlock $userBlock)
    {
        //
    }
}
