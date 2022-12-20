@extends('admin.layouts.master')

@section('title', 'Edit Profile')

@section('head_style')
  <!-- intlTelInput -->
  <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/css/intlTelInput.css')}}">
@endsection

@section('page_content')
<div id="user-edit">
    <div class="box">
        <div class="panel-body ml-20">
            <ul class="nav nav-tabs cus" role="tablist">
                <li class="active">
                    <a href='{{ url(\Config::get('adminPrefix')."/users/edit/$users->id")}}'>Profile</a>
                </li>
                <li>
                    <a href="{{ url(\Config::get('adminPrefix')."/users/transactions/$users->id")}}">Transactions</a>
                </li>
                <li>
                    <a href="{{ url(\Config::get('adminPrefix')."/users/wallets/$users->id")}}">Wallets</a>
                </li>
                <li>
                    <a href="{{ url(\Config::get('adminPrefix')."/users/tickets/$users->id")}}">Tickets</a>
                </li>
                <li>
                    <a href="{{ url(\Config::get('adminPrefix')."/users/disputes/$users->id")}}">Disputes</a>
                </li>
                @if (config('referral.is_active') && count($users->referral_award_awarded_user) > 0)
                    <li>
                        <a href='{{ url(\Config::get("adminPrefix")."/users/referral-awards/" . $users->id) }}'>{{ __('Referral Awards') }}</a>
                    </li>
                @endif
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            @if ($users->status == 'Inactive')
                <h3>{{ $users->first_name.' '.$users->last_name }}&nbsp;<span class="label label-danger">Inactive</span></h3>
            @elseif ($users->status == 'Suspended')
                <h3>{{ $users->first_name.' '.$users->last_name }}&nbsp;<span class="label label-warning">Suspended</span></h3>
            @elseif ($users->status == 'Active')
                <h3>{{ $users->first_name.' '.$users->last_name }}&nbsp;<span class="label label-success">Active</span></h3>
            @endif
        </div>
        <div class="col-md-3"></div>

        <div class="col-md-5">
            <div class="pull-right">
                <a style="margin-top: 15px;" href="{{ url(\Config::get('adminPrefix').'/users/deposit/create/'.$users->id) }}" class="btn btn-theme">Deposit</a>
                &nbsp;&nbsp;&nbsp;
                <a style="margin-top: 15px;" href="{{ url(\Config::get('adminPrefix').'/users/withdraw/create/'.$users->id) }}" class="btn btn-theme">Withdraw</a>
                &nbsp;&nbsp;&nbsp;
            </div>
        </div>
    </div>

    <div class="box mt-20">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- form start -->
                    <form action="{{ url(\Config::get('adminPrefix').'/users/update') }}" class="form-horizontal" id="user_form" method="POST">
                        {{ csrf_field() }}

                        <input type="hidden" value="{{ $users->id }}" name="id" id="id" />
                        <input type="hidden" value="{{ $users->defaultCountry }}" name="user_defaultCountry" id="user_defaultCountry" />
                        <input type="hidden" value="{{ $users->carrierCode }}" name="user_carrierCode" id="user_carrierCode" />
                        <input type="hidden" name="formattedPhone" id="formattedPhone">

                        <div class="box-body">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="inputEmail3">
                                                First Name
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Update First Name" name="first_name" type="text" id="first_name" value="{{ $users->first_name }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="inputEmail3">
                                                Last Name
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Update Last Name" name="last_name" type="text" id="last_name" value="{{ $users->last_name }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="inputEmail3">
                                                Phone
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="tel" class="form-control" id="phone" name="phone">
                                                <span id="phone-error"></span>
                                                <span id="tel-error"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label require" for="inputEmail3">
                                                Email
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Update Email" name="email" type="email" id="email" value="{{ $users->email }}">
                                                <span id="emailError"></span>
                                                <span id="email-ok" class="text-success"></span>
                                            </div>
                                        </div>

                                        <!-- Company Data -->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="inputEmail3">
                                                Company Name
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Enter Company Name" name="company_name" type="text" id="last_name" value="{{ $applications->company_name ?? '' }}">
                                                </input>
                                                @if($errors->has('company_name'))
                                                    <span class="error">
                                                        {{ $errors->first('company_name') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="inputEmail3">
                                                Company Name
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Enter Company Name" name="company_name" type="text" id="last_name" value="{{ $applications->company_name ?? '' }}">
                                                </input>
                                                @if($errors->has('company_name'))
                                                    <span class="error">
                                                        {{ $errors->first('company_name') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="rule">Company Position</label>
                                            <div class="col-sm-8">
                                                <select class="select2 form-control" name="rule" id="rule">
                                                    <option value='AUTHORISED_REPRESENTATIVE' {{ $applications->rule == "AUTHORISED_REPRESENTATIVE" ? 'selected' : '' }}>Authorised Representative</option>
                                                    <option value='DIRECTOR' {{ $applications->rule == "DIRECTOR" ? 'selected' : '' }}>Company Director</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="">
                                                Registration Number
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Enter Registration Number" name="company_number" type="text" id="" value="{{ $applications->company_number ?? '' }}">
                                                </input>
                                                @if($errors->has('company_number'))
                                                    <span class="error">
                                                        {{ $errors->first('company_number') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="company_type">Company Type</label>
                                            <div class="col-sm-8">
                                                <select class="select2 form-control" name="company_type" id="company_type">
                                                    <option value='SOLE_TRADER' {{ $applications->source_of_funds == "SOLE_TRADER" ? 'selected' : '' }}>Sole Trader</option>
                                                    <option value='LTD_COMPANY' {{ $applications->source_of_funds == "LTD_COMPANY" ? 'selected' : '' }}>LTD Company</option>
                                                    <option value='LLP_COMPANY' {{ $applications->source_of_funds == "LLP_COMPANY" ? 'selected' : '' }}>LLP Company</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="companyIndustry">Company Industry</label>
                                            <div class="col-sm-8">
                                                <select class="select2 form-control" name="companyIndustry" id="companyIndustry">
                                                    <option value='ACCOUNTING' {{ $applications->source_of_funds == "ACCOUNTING" ? 'selected' : '' }}>ACCOUNTING</option>
                                                    <option value='AUDIT' {{ $applications->source_of_funds == "AUDIT" ? 'selected' : '' }}>AUDIT</option>
                                                    <option value='FINANCE' {{ $applications->source_of_funds == "FINANCE" ? 'selected' : '' }}>FINANCE</option>
                                                    <option value='PUBLIC_SECTOR_ADMINISTRATION' {{ $applications->source_of_funds == "PUBLIC_SECTOR_ADMINISTRATION" ? 'selected' : '' }}>PUBLIC SECTOR ADMINISTRATION</option>
                                                    <option value='ART_ENTERTAINMENT' {{ $applications->source_of_funds == "ART_ENTERTAINMENT" ? 'selected' : '' }}>ART ENTERTAINMENT</option>
                                                    <option value='ART_ENTERTAINMENT' {{ $applications->source_of_funds == "ART_ENTERTAINMENT" ? 'selected' : '' }}>AUTO AVIATION</option>
                                                    <option value='BANKING_LENDING' {{ $applications->source_of_funds == "BANKING_LENDING" ? 'selected' : '' }}>BANKING LENDING</option>
                                                    <option value='BUSINESS_CONSULTANCY_LEGAL' {{ $applications->source_of_funds == "BUSINESS_CONSULTANCY_LEGAL" ? 'selected' : '' }}>BUSINESS CONSULTANCY LEGAL</option>
                                                    <option value='CONSTRUCTION_REPAIR' {{ $applications->source_of_funds == "CONSTRUCTION_REPAIR" ? 'selected' : '' }}>CONSTRUCTION REPAIR</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="">
                                                Registered Country
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Enter Company Name" name="registeredCountry" type="text" id="" value="{{ $applications->registeredCountry ?? '' }}">
                                                </input>
                                                @if($errors->has('registeredCountry'))
                                                    <span class="error">
                                                        {{ $errors->first('registeredCountry') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                        <label class="col-sm-4 control-label" for="source_of_funds">Source of Funds</label>
                                        <div class="col-sm-8">
                                            <select class="select2 form-control" name="source_of_funds" id="source_of_funds">
                                                <option value='LABOUR_CONTRACT' {{ $applications->source_of_funds == "LABOUR_CONTRACT" ? 'selected' : '' }} >LABOUR CONTRACT</option>
                                                <option value='CIVIL_CONTRACT' {{ $applications->source_of_funds == "CIVIL_CONTRACT" ? 'selected' : '' }}>CIVIL CONTRACT</option>
                                                <option value='FUNDS_FROM_OTHER_AUXILIARY_SOURCES' {{ $applications->source_of_funds == "FUNDS_FROM_OTHER_AUXILIARY_SOURCES" ? 'selected' : '' }}>FUNDS FROM OTHER AUXILIARY SOURCES</option>
                                                <option value='RENT' {{ $applications->source_of_funds == "RENT" ? 'selected' : '' }}>RENT</option>
                                                <option value='FUNDS_FROM_OTHER_AUXILIARY_SOURCES' {{ $applications->source_of_funds == "FUNDS_FROM_OTHER_AUXILIARY_SOURCES" ? 'selected' : '' }}>FUNDS FROM OTHER AUXILIARY SOURCES</option>
                                                <option value='SALE_OF_MOVABLE_ASSETS' {{ $applications->source_of_funds == "SALE_OF_MOVABLE_ASSETS" ? 'selected' : '' }}>SALE OF MOVABLE ASSETS</option>
                                                <option value='SALE_OF_REAL_ESTATE' {{ $applications->source_of_funds == "SALE_OF_REAL_ESTATE" ? 'selected' : '' }}>SALE OF REAL ESTATE</option>
                                            </select>
                                        </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="streetAddress">
                                                Street Address
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Enter Street Address" name="streetAddress" type="text" id="streetAddress" value="{{ $applications->streetAddress ?? '' }}">
                                                </input>
                                                @if($errors->has('streetAddress'))
                                                    <span class="error">
                                                        {{ $errors->first('streetAddress') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="cityState">
                                                City / State
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Enter City/State" name="cityState" type="text" id="cityState" value="{{ $applications->cityState ?? '' }}">
                                                </input>
                                                @if($errors->has('cityState'))
                                                    <span class="error">
                                                        {{ $errors->first('cityState') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="zipCode">
                                                Zip Code
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Enter Zip Code" name="zipCode" type="text" id="" value="{{ $applications->zipCode ?? '' }}">
                                                </input>
                                                @if($errors->has('zipCode'))
                                                    <span class="error">
                                                        {{ $errors->first('zipCode') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Status -->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="status">Company Status</label>
                                            <div class="col-sm-8">
                                                <select class="select2 form-control" name="status" id="status">
                                                    <option value='0' {{ $applications->status == '0' ? 'selected' : '' }}>Pending</option>
                                                    <option value='1' {{ $applications->status == '1' ? 'selected' : '' }}>Processing</option>
                                                    <option value='2' {{ $applications->status == '2' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- end Company Data -->
                                        <!-- Role -->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label require" for="inputEmail3">Group</label>
                                            <div class="col-sm-8">
                                                <select class="select2" name="role" id="role">
                                                    @foreach ($roles as $role)
                                                    <option value='{{ $role->id }}' {{ $role->id == $users->role_id ? 'selected':""}}> {{ $role->display_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

<!-- 

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="inputEmail3">
                                                Password
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Update Password (min 6 characters)" name="password" type="password" id="password">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="inputEmail3">
                                                Confirm Password
                                            </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" placeholder="Confirm password (min 6 characters)" name="password_confirmation" type="password" id="password_confirmation">
                                            </div>
                                        </div> -->

                                        <!-- Status -->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label require" for="status">Status</label>
                                            <div class="col-sm-8">
                                                <select class="select2" name="status" id="status">
                                                    <option value='Active' {{ $users->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                    <option value='Inactive' {{ $users->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                    <option value='Suspended' {{ $users->status == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                                </select>
                                                <label id="user-status" class="error" for="status"></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4" for="inputEmail3">
                                            </label>
                                            <div class="col-sm-8">
                                                <a class="btn btn-theme-danger" href="{{ url(\Config::get('adminPrefix').'/users') }}" id="users_cancel">
                                                    Cancel
                                                </a>
                                                <button type="submit" class="btn btn-theme pull-right" id="users_edit">
                                                    <i class="fa fa-spinner fa-spin" style="display: none;"></i> <span id="users_edit_text">Update</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('extra_body_scripts')
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/js/intlTelInput.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/dist/js/isValidPhoneNumber.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    'use strict';
    var hasPhoneError = false;
    var hasEmailError = false;
    var userNameError = '{{ __("Please enter only alphabet and spaces.") }}';
    var userNameLengthError = '{{ __("Name length can not be more than 30 characters") }}';
    var utilsScriptLoadingPath = '{{ url("public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/js/utils.js") }}';
    var formattedPhoneNumber = '{{ !empty($users->formattedPhone) ? $users->formattedPhone : NULL }}';
    var carrierCode = '{{ !empty($users->carrierCode) ? $users->carrierCode : NULL }}';
    var defaultCountry = '{{ !empty($users->defaultCountry) ? $users->defaultCountry : NULL }}';
    var validPhoneNumberErrorText = '{{ __("Please enter a valid international phone number.") }}';
    var inactiveWarning = '{!! __("Warning! User would not be able to login.") !!}';
    var suspendWarning = '{!! __("Warning! User would not be able to do any transaction.") !!}';
    var passwordMatchErrorText = '{{ __("Please enter same value as the password field.") }}';
    var updatingText = '{{ __("Updating...") }}';
</script>
<script src="{{ asset('public/dist/js/admin_custom.min.js') }}" type="text/javascript"></script>
@endpush
