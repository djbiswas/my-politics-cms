<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('login-assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{ asset('login-assets/img/favicon.png')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        My Politics
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="{{ asset('login-assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('login-assets/css/paper-dashboard.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('login-assets/css/style.css')}}" rel="stylesheet" />
</head>
<body class="page-login">
    <div class="wrapper ">
        <div class="content cms-login">
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4">
                    @if($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="nc-icon nc-simple-remove"></i>
                        </button>
                        <!-- <span><b>Error:</b> Wrong credentials.</span> -->
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                    @endif
                    <!-- <x-auth-validation-errors class="mb-4" :errors="$errors" /> -->
                    <form class="form-signin {{($errors->any())? 'shake': ''}}" action="{{ route('login') }}" method="post">
                        @csrf
                        <img class="logo" src="{{ asset('login-assets/img/logo.png')}}" alt="">
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
                </div>
                <div class="col-4"></div>
            </div>
        </div>
    </div>
</body>
<!--   Core JS Files   -->
<script src="{{ asset('login-assets/js/jquery.min.js')}}"></script>

