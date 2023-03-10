<?php
/**
 * Created By: TechVillage.net
 * Start Date: 22-Jan-2018
 */
$logo = settings('logo');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="paymoney">
    <title>Admin</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/font-awesome/css/font-awesome.min.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/AdminLTE.min.css') }}">

    <!-- iCheck -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/iCheck/square/blue.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/styles.css') }}">

    <!---favicon-->
    @if (!empty(settings('favicon')))
        <link rel="shortcut icon" href="{{asset('public/images/logos/'.settings('favicon'))}}" />
    @endif


</head>

<body class="hold-transition login-page" style="background-color:#ececec;">
<div class="login-box">
    <div class="login-logo">

        @if (!empty(settings('logo')) && file_exists(public_path('images/logos/' . settings('logo'))))
            <a href="{{ url(\Config::get('adminPrefix').'/') }}"><img src="{{ url('public/images/logos/' . settings('logo')) }}" class="img-responsive log-img" alt="Logo"/></a>  
        @else
            <img src="{{ url('public/uploads/userPic/default-logo.jpg') }}" width="288" height="90">
        @endif
    </div>

    <div class="login-box-body login-design">

        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class') }} text-center">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>{{ Session::get('message') }}</strong>
            </div>
        @endif

        <form action="{{ url(\Config::get('adminPrefix').'/adminlog') }}" method="POST" id="admin_login_form">
            {{ csrf_field() }}

            <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                <label class="control-label sr-only" for="inputSuccess2">Email</label>
                <input type="email" class="form-control" placeholder="Email" name="email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                @if ($errors->has('email'))
                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                @endif
            </div>

            <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                <label class="control-label sr-only" for="inputSuccess2">Password</label>
                <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                @if ($errors->has('password'))
                    <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                @endif
            </div>

            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Remember Me
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-theme btn-block">Sign In</button>
                </div>
            </div>
        </form>
        {{-- <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="javascript:void(0)" class="btn btn-block btn-social btn-facebook btn-flat"><i
                        class="fa fa-facebook"></i> Sign in
                using
                Facebook</a>
            <a href="javascript:void(0)" class="btn btn-block btn-social btn-google btn-flat"><i
                        class="fa fa-google-plus"></i> Sign in
                using
                Google+</a>
        </div> --}}
        <!-- /.social-auth-links -->
        <a href="{{ url(\Config::get('adminPrefix').'/forget-password') }}">I forgot my password</a><br>
        {{-- <a href="javascript:void(0)" class="text-center">Register a new membership</a> --}}
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ asset('public/backend/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>

<!-- jquery.validate -->
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}" type="text/javascript"></script>

<!-- Bootstrap 3.3.5 -->
<script src="{{ asset('public/backend/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>

<!-- iCheck -->
<script src="{{ asset('public/backend/iCheck/icheck.min.js') }}" type="text/javascript"></script>

<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });

    $.validator.setDefaults({
        highlight: function(element) {
            $(element).parent('div').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).parent('div').removeClass('has-error');
        },
    });

    $('#admin_login_form').validate({
        errorClass: "has-error",
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true
            }
        }
    });
</script>
</body>
