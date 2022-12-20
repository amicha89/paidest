@extends('admin.layouts.master')
@section('title', 'Email Verification')

@section('head_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/bootstrap-toggle/css/bootstrap-toggle.min.css') }}">
    <!-- intlTelInput -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/css/intlTelInput.css')}}">
@endsection

@section('page_content')
    <!-- Main content -->
    <div class="box box-default">
        <div class="box-body">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="top-bar-title padding-bottom pull-left">Email Verification</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
            </div>
                <form action="{{ url(\Config::get('adminPrefix').'/app-registrations/verify-email') }}" method="POST" class="form-horizontal" enctype="multipart/form-data" id="verify_email_form">
                    @csrf
                    @method('POST')
                    <div class="box-body">
                        <!-- Name -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="name">{{ __('Email') }}</label>
                            <div class="col-sm-6">
                                <input type="text" name="first_name" class="form-control" value="{{ $emailData['email'] ?? 'email' }}"  id="name">
                                @if($errors->has('first_name'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('first_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="name">{{ __('Verification Code') }}</label>
                            <div class="col-sm-6">
                                <input type="text" name="last_name" class="form-control" value="{{ $emailData['nonce'] ?? 'email' }}" id="name" maxlength="6">
                                @if($errors->has('last_name'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <a class="btn btn-theme-danger pull-left" href="{{ url(\Config::get('adminPrefix').'/app-registrations') }}" id="users_cancel">Cancel</a>
                            <button type="submit" class="btn btn-theme pull-right" id="users_create"><i class="fa fa-spinner fa-spin" style="display: none;"></i> <span id="users_create_text">Verify</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection