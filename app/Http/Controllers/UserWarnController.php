<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserWarnRequest;
use App\Http\Requests\UpdateUserWarnRequest;
use App\Models\UserWarn;

class UserWarnController extends Controller
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
     * @param  \App\Http\Requests\StoreUserWarnRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserWarnRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserWarn  $userWarn
     * @return \Illuminate\Http\Response
     */
    public function show(UserWarn $userWarn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserWarn  $userWarn
     * @return \Illuminate\Http\Response
     */
    public function edit(UserWarn $userWarn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserWarnRequest  $request
     * @param  \App\Models\UserWarn  $userWarn
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserWarnRequest $request, UserWarn $userWarn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserWarn  $userWarn
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserWarn $userWarn)
    {
        //
    }
}
