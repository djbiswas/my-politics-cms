<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png')}}">
        <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png')}}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>
            My Politics
        </title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
        <!-- CSS Files -->
        <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
        <link href="{{ asset('assets/css/paper-dashboard.min.css')}}" rel="stylesheet" />
        <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet" />
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
                        @yield('content')
                    </div>
                    <div class="col-4"></div>
                </div>
            </div>
        </div>
    </body>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
</html>

