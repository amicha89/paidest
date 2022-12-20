@extends('admin.layouts.master')
@section('title', 'Edit Registration')

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
                    <div class="top-bar-title padding-bottom pull-left">Edit Registration</div>
                </div>
                <div>
                @if ($applications->status == 1)
                    <a style="margin-top: 15px;" href="{{ route('status.rejected', $applications->id) }}" class="btn btn-danger pull-right">Rejected</a>
                    &nbsp;&nbsp;&nbsp;
                @else
                    <a style="margin-top: 15px;" href="{{ route('create.corporate', $applications->id) }}" class="btn btn-theme confirmation-warning">Accept & Proceed</a>
                    &nbsp;&nbsp;&nbsp;
                    <a style="margin-top: 15px;" href="{{ route('status.rejected', $applications->id) }}" class="btn btn-danger">Rejected</a>
                    &nbsp;&nbsp;&nbsp;
                @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    
                </div>
                <form action="{{ route('appRegis.update', $applications->id) }}" class="form-horizontal" id="user_form" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" value="{{ $applications->id }}" name="id" id="id" />
                        <input type="hidden" value="{{ $applications->defaultCountry }}" name="user_defaultCountry" id="user_defaultCountry" />
                        <input type="hidden" value="{{ $applications->carrierCode }}" name="user_carrierCode" id="user_carrierCode" />
                        <input type="hidden" name="formattedPhone" id="formattedPhone">

                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="first_name">
                                        First Name
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Enter First Name" name="first_name" type="text" id="first_name" value="{{ $applications->first_name ?? '' }}">
                                        </input>

                                        @if($errors->has('first_name'))
                                            <span class="error">
                                                {{ $errors->first('first_name') }}
                                            </span>
                                        @endif

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="last_name">
                                        Last Name
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Enter Last Name" name="last_name" type="text" id="last_name" value="{{ $applications->last_name ?? '' }}">
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
                                        Email
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" value="{{ $applications->email ?? ''}}" placeholder="Enter a valid email" name="email" type="email" id="email">
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

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="phone">
                                        Phone
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="tel" class="form-control" id="appphone" name="phone">
                                        <span id="phone-error"></span>
                                        <span id="tel-error"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="dob">
                                        Date of Birth
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="" name="dob" type="text" id="dob" value="{{ $applications->dob ?? '' }}">
                                        </input>
                                        @if($errors->has('dob'))
                                            <span class="error">
                                                {{ $errors->first('dob') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="rule">Company Position</label>
                                    <div class="col-sm-6">
                                        <select class="select2 form-control" name="rule" id="rule">
                                            <option value='AUTHORISED_REPRESENTATIVE' {{ $applications->rule == "AUTHORISED_REPRESENTATIVE" ? 'selected' : '' }}>Authorised Representative</option>
                                            <option value='DIRECTOR' {{ $applications->rule == "DIRECTOR" ? 'selected' : '' }}>Company Director</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="inputEmail3">
                                        Company Name
                                    </label>
                                    <div class="col-sm-6">
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
                                    <label class="col-sm-3 control-label" for="">
                                        Registration Number
                                    </label>
                                    <div class="col-sm-6">
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
                                    <label class="col-sm-3 control-label" for="company_type">Company Type</label>
                                    <div class="col-sm-6">
                                        <select class="select2 form-control" name="company_type" id="company_type">
                                            <option value='SOLE_TRADER' {{ $applications->source_of_funds == "SOLE_TRADER" ? 'selected' : '' }}>Sole Trader</option>
                                            <option value='LTD_COMPANY' {{ $applications->source_of_funds == "LTD_COMPANY" ? 'selected' : '' }}>LTD Company</option>
                                            <option value='LLP_COMPANY' {{ $applications->source_of_funds == "LLP_COMPANY" ? 'selected' : '' }}>LLP Company</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="companyIndustry">Company Industry</label>
                                    <div class="col-sm-6">
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
                                    <label class="col-sm-3 control-label" for="">
                                        Registered Country
                                    </label>
                                    <div class="col-sm-6">
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
                                <label class="col-sm-3 control-label" for="source_of_funds">Source of Funds</label>
                                <div class="col-sm-6">
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
                                    <label class="col-sm-3 control-label" for="streetAddress">
                                        Street Address
                                    </label>
                                    <div class="col-sm-6">
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
                                    <label class="col-sm-3 control-label" for="cityState">
                                        City / State
                                    </label>
                                    <div class="col-sm-6">
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
                                    <label class="col-sm-3 control-label" for="zipCode">
                                        Zip Code
                                    </label>
                                    <div class="col-sm-6">
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
                                    <label class="col-sm-3 control-label" for="status">Status</label>
                                    <div class="col-sm-6">
                                        <select class="select2 form-control" name="status" id="status">
                                            <option value='0' {{ $applications->status == '0' ? 'selected' : '' }}>Pending</option>
                                            <option value='1' {{ $applications->status == '1' ? 'selected' : '' }}>Processing</option>
                                            <option value='2' {{ $applications->status == '2' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <a class="btn btn-theme-danger pull-left" href="{{ url(\Config::get('adminPrefix').'/app-registrations') }}" id="users_cancel">Cancel</a>
                                    <button type="submit" class="btn btn-theme pull-right" id="users_create"><i class="fa fa-spinner fa-spin" style="display: none;"></i> <span id="users_create_text">Update</span></button>
                                </div>
                            </div>
                        </input>
                    </form>
                
            </div>
        </div>
    </div>
    
    <!-- Confirmation Model -->
    <div class="modal fade" id="confirmation-warning-modal" role="dialog" style="z-index:1060; color: light blue;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" style="width:100%;height:100%; background-color: aliceblue;">
                <div style="display: block" class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ __('Confirmation') }}</h4>
                </div>

                <div class="modal-body">
                    <p><strong>{{ __('Are you sure you want to Process?') }}</strong></p>
                </div>

                <div class="modal-footer">
                    <a class="btn btn-danger" id="delete-modal-yes" href="javascript:void(0)">@lang('message.form.yes')</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('message.form.no')</button>
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
    var input = document.querySelector("#appphone");
    window.intlTelInput(input, {
        initialCountry: "auto",
        geoIpLookup: function(success, failure) {
            $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                success(countryCode);
            });
        },
        utilsScript: '{{ url("public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/js/utils.js") }}'
    });
    // var hasPhoneError = false;
    // var hasEmailError = false;
    // var utilsScriptLoadingPath = '{{ url("public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/js/utils.js") }}';
    // var formattedPhoneNumber = '{{ !empty($applications->formattedPhone) ? $applications->formattedPhone : NULL }}';
    // var carrierCode = '{{ !empty($applications->carrierCode) ? $applications->carrierCode : NULL }}';
    // var defaultCountry = '{{ !empty($applications->defaultCountry) ? $applications->defaultCountry : NULL }}';
    // var validPhoneNumberErrorText = '{{ __("Please enter a valid international phone number.") }}';
    // var updatingText = '{{ __("Updating...") }}';
</script>
<script src="{{ asset('public/dist/js/admin_custom.min.js') }}" type="text/javascript"></script>
@endpush
