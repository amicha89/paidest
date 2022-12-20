@extends('frontend.layouts.app')
@section('content')
<div class="min-vh-100">
    <!--Start banner Section-->
    <section class="bg-image" style="margin-top:40px">
        <div class="bg-dark">
            <div class="container">
                <div class="row py-5">
                    <div class="col-md-12 " style="margin-top: 20px;">
                        <h2 class="text-white font-weight-bold text-28">Verify Email</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End banner Section-->
     <!--Start Section-->
    <section class="section-login padding-30">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="margin-top: 20px;">

                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <!-- form card login -->
                            <div class="card rounded-0">
                                <div class="card-header">
                                    <h3 class="mb-0 text-left">Verify Email</h3>
                                </div>

                                <div class="card-body">
                                    @include('frontend.layouts.common.alert')
                                    <form action="{{ route('verify-email') }}" method="post" id="login_form">
                                            {{ csrf_field() }}

                                        
                                            <div class="form-group">
                                                <label for="email_only">Email Addresss</label>
                                                <input type="text" value="{{ $emailData['email'] ?? '' }}" class="form-control" aria-describedby="emailHelp" placeholder="" name="email" id="email_only">

                                                @if($errors->has('email'))
                                                    <span class="error">
                                                     {{ $errors->first('email') }}
                                                    </span>
                                                @endif
                                            </div>
                                      
                                            <div class="form-group">
                                                <label for="phone_only">Verification Code</label>
                                                <input type="text" maxlength="6" value="{{ $emailData['nonce'] ?? '' }}" class="form-control" aria-describedby="phoneHelp" placeholder="" name="nonce" id="phone_only">

                                                @if($errors->has('nonce'))
                                                    <span class="error">
                                                     {{ $errors->first('nonce') }}
                                                    </span>
                                                @endif
                                            </div>                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-cust float-left" id="login-btn">
                                                    <i class="spinner fa fa-spinner fa-spin" style="display: none;"></i>
                                                    <span id="login-btn-text font-weight-bold">
                                                       Verify
                                                    </span>
                                                </button>
                                            </div>
                                          </div>

                                    </form>
                                </div>
                                <!--/card-block-->
                            </div>
                            <!-- /form card login -->
<!--                             <div class="signin">
                                <div class="message">
                                    <span>@lang('message.login.no-account') &nbsp; </span>
                                     <a href="{{url('register')}}">@lang('message.login.sign-up-here')</a>.
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <!--/row-->
                </div>
                <!--/col-->
            </div>
            <!--/row-->
        </div>
    </section>
</div>
@endsection