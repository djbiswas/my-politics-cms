<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserBanRequest;
use App\Http\Requests\UpdateUserBanRequest;
use App\Models\UserBan;

class UserBanController extends Controller
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
     * @param  \App\Http\Requests\StoreUserBanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserBanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserBan  $userBan
     * @return \Illuminate\Http\Response
     */
    public function show(UserBan $userBan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserBan  $userBan
     * @return \Illuminate\Http\Response
     */
    public function edit(UserBan $userBan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserBanRequest  $request
     * @param  \App\Models\UserBan  $userBan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserBanRequest $request, UserBan $userBan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserBan  $userBan
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserBan $userBan)
    {
        //
    }
}
