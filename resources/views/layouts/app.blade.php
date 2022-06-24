
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>{{ config('app.name', 'My Politics') }}</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
        <!-- CSS Files -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/paper-dashboard.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/quill.bubble.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/quill.snow.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
        @stack('css')
        <style>
            .dataTable tr td { 
                text-align: left;
            }
            .dataTable td:last-child {
                text-align: right;
            }
        </style>
    </head>

    <body class="page-index">
        <div class="wrapper ">
            @include('layouts.navigation')
            <div class="main-panel">
                @include('layouts.header-bar')
                <div class="content">
                    {{ $header }}
                    <div class="content-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            <!-- Page Heading -->
            <!-- <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header> -->

            <!-- Page Content -->
            <!-- <main>
                {{ $slot }}
            </main> -->
        </div>
        @include('layouts.footer')
        @stack('scripts')
        <script>
            $('document').ready(function(){
                    $("#validForm").validate({
                        ignore:":not(:visible)",
                        highlight: function(element) {
                            $(element).closest('.form-group').addClass('has-error was-validated');
                        },
                        unhighlight: function(element) {
                            $(element).closest('.form-group').removeClass('has-error was-validated');
                        },
                        errorClass:"error error_preview",
                        invalidHandler: function(event, validator) {
                            validator.numberOfInvalids()&&validator.errorList[0].element.focus();
                        },
                        submitHandler: function (form) {
                            return true;
                        }
                    });
            });
        </script>
    </body>
</html>
