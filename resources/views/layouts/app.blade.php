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
        <!-- select 2 cdn connect  -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">


    </head>

    <body class="page-index">
        <div class="wrapper ">
            @include('layouts.navigation')
            <div class="main-panel">
                @include('layouts.header-bar')
                <div class="content">
                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session()->get('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Oops!! Something went wrong. Please try again.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    {{ $header }}
                    <div class="content-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
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
        <!-- Select 2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2on').select2();
            });

            function formatState (state) {
                if (!state.id) {
                    return state.text;
                }

                var $state = $(state.text +' IMG');

                return $state;
            };

            $(".select2img").select2({
                // templateResult: formatState
                escapeMarkup: function(m) {
                    return m;
                }
            });
        </script>
                <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    </body>
</html>
