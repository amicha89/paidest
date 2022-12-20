@extends('admin.layouts.master')

@section('title', 'Add User')

@section('head_style')
  <!-- intlTelInput -->
  <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/css/intlTelInput.css')}}">
@endsection

@section('page_content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info" id="user-create">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add User</h3>
                    </div>
                    <form action="{{ url(\Config::get('adminPrefix').'/users/store') }}" class="form-horizontal" id="user_form" method="POST">

                        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">

                        <input type="hidden" name="defaultCountry" id="defaultCountry" class="form-control">
                        <input type="hidden" name="carrierCode" id="carrierCode" class="form-control">
                        <input type="hidden" name="formattedPhone" id="formattedPhone" class="form-control">

                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="inputEmail3">
                                        First Name
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Enter First Name" name="first_name" type="text" id="first_name" value="">
                                        </input>

                                        @if($errors->has('first_name'))
                                            <span class="error">
                                                {{ $errors->first('first_name') }}
                                            </span>
                                        @endif

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="inputEmail3">
                                        Last Name
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Enter Last Name" name="last_name" type="text" id="last_name" value="">
                                        </input>
                                        @if($errors->has('last_name'))
                                            <span class="error">
                                                {{ $errors->first('last_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="inputEmail3">
                                        Phone
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="tel" class="form-control" id="phone" name="phone">
                                        <span id="phone-error"></span>
                                        <span id="tel-error"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="inputEmail3">
                                        Email
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Enter a valid email" name="email" type="email" id="email">
                                        </input>
                                        @if($errors->has('email'))
                                            <span class="error">
                                                {{ $errors->first('email') }}
                                            </span>
                                        @endif
                                        <span id="email_error"></span>
                                        <span id="email_ok" class="text-success"></span>
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="inputEmail3">Group</label>
                                    <div class="col-sm-6">
                                        {{-- <select class="form-control" name="role" id="role"> --}}
                                        <select class="select2" name="role" id="role">
                                            {{-- <option value='' selected="selected"> Select Group </option> --}}
                                            @foreach ($roles as $role)
                                              <option value='{{ $role->id }}'> {{ $role->display_name }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <div id="error-message"></div> --}}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="inputEmail3">
                                        Password
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Enter new Password (min 6 characters)" name="password" type="password" id="password">
                                        </input>
                                        @if($errors->has('password'))
                                            <span class="error">
                                                {{ $errors->first('password') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="inputEmail3">
                                        Confirm Password
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Confirm password (min 6 characters)" name="password_confirmation" type="password" id="password_confirmation">
                                        </input>
                                        @if($errors->has('password_confirmation'))
                                            <span class="error">
                                                {{ $errors->first('password_confirmation') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="status">Status</label>
                                    <div class="col-sm-6">
                                        <select class="select2" name="status" id="status">
                                            <option value='Active'>Active</option>
                                            <option value='Inactive'>Inactive</option>
                                            <option value='Suspended'>Suspended</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <a class="btn btn-theme-danger pull-left" href="{{ url(\Config::get('adminPrefix').'/users') }}" id="users_cancel">Cancel</a>
                                    <button type="submit" class="btn btn-theme pull-right" id="users_create"><i class="fa fa-spinner fa-spin" style="display: none;"></i> <span id="users_create_text">Create</span></button>
                                </div>
                            </div>
                        </input>
                    </form>
            </div>
        </div>
    </div>
@endsection

@push('extra_body_scripts')

<!-- jquery.validate -->
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/js/intlTelInput.js') }}" type="text/javascript"></script>

<!-- isValidPhoneNumber -->
<script src="{{ asset('public/dist/js/isValidPhoneNumber.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    'use strict';
    var hasPhoneError = false;
    var hasEmailError = false;
    var countryShortCode = '{{ getDefaultCountry() }}';
    var userNameError = '{{ __("Please enter only alphabet and spaces") }}';
    var userNameLengthError = '{{ __("Name length can not be more than 30 characters") }}';
    var passwordMatchErrorText = '{{ __("Please enter same value as the password field.") }}';
    var creatingText = '{{ __("Creating...") }}';
    var utilsScriptLoadingPath = '{{ url("public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/js/utils.js") }}';
    var validPhoneNumberErrorText = '{{ __("Please enter a valid international phone number.") }}';
</script>
<script src="{{ asset('public/dist/js/admin_custom.min.js') }}" type="text/javascript"></script>
@endpush


