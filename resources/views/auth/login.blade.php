@extends('auth.layout')
@section('content')
    <form class="form-signin {{($errors->any())? 'shake': ''}}" action="{{ route('login') }}" method="post">
        @csrf
        <img class="logo" src="{{ asset('assets/img/logo.png')}}" alt="">
        <h1 class="h3 mb-3 font-weight-normal"> CMS Login</h1>
        <div class="form-group">
            <label for="inputEmail">Email address</label>
            <input type="email" id="inputEmail" name='email' class="form-control" placeholder="Email address" value="{{old('email')}}" >
        </div>
        <div class="form-group">
            <input type="password" id="inputPassword" name='password' class="form-control" placeholder="Password" >
        </div>

        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" name="remember" type="checkbox" value="">
                    Remember me
                    <span class="form-check-sign">
                        <span class="check"></span>
                    </span>
                </label>
            </div>
        </div>
        <input class="btn btn-lg btn-primary btn-block" type="submit" value="Sign in" name='input_signin'>
    </form>
@stop
