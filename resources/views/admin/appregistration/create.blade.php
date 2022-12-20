@extends('admin.layouts.master')

@section('title', 'Add Application')

@section('head_style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <!-- intlTelInput -->
  <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/css/intlTelInput.css')}}">
@endsection

@section('page_content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info" id="user-create">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add Application</h3>
                    </div>
                    <form action="{{ url(\Config::get('adminPrefix').'/app-registrations/store') }}" class="form-horizontal" id="user_form" method="POST">
                        @csrf
                        @method('POST');
                        <input type="hidden" name="defaultCountry" id="defaultCountry" class="form-control">
                        <input type="hidden" name="carrierCode" id="carrierCode" class="form-control">
                        <input type="hidden" name="formattedPhone" id="formattedPhone" class="form-control">

                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="first_name">
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
                                    <label class="col-sm-3 control-label require" for="last_name">
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

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="phone">
                                        Phone
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="tel" class="form-control" id="phone" name="phone">
                                        <span id="phone-error"></span>
                                        <span id="tel-error"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="dob">
                                        Date of Birth
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="" name="dob" type="text" id="appdateTime" value="">
                                        </input>
                                        @if($errors->has('dob'))
                                            <span class="error">
                                                {{ $errors->first('dob') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="rule">Company Position</label>
                                    <div class="col-sm-6">
                                        <select class="select2" name="rule" id="rule">
                                            <option value='AUTHORISED_REPRESENTATIVE'>Authorised Representative</option>
                                            <option value='DIRECTOR'>Company Director</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="inputEmail3">
                                        Company Name
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Enter Company Name" name="company_name" type="text" id="last_name" value="">
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
                                        <input class="form-control" placeholder="Enter Registration Number" name="company_number" type="text" id="" value="">
                                        </input>
                                        @if($errors->has('company_number'))
                                            <span class="error">
                                                {{ $errors->first('company_number') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="company_type">Company Type</label>
                                    <div class="col-sm-6">
                                        <select class="select2" name="company_type" id="company_type">
                                            <option value='SOLE_TRADER'>Sole Trader</option>
                                            <option value='LTD_COMPANY'>LTD Company</option>
                                            <option value='LLP_COMPANY'>LLP Company</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="companyIndustry">Company Industry</label>
                                    <div class="col-sm-6">
                                        <select class="select2" name="companyIndustry" id="companyIndustry">
                                            <option value='ACCOUNTING'>ACCOUNTING</option>
                                            <option value='AUDIT'>AUDIT</option>
                                            <option value='FINANCE'>FINANCE</option>
                                            <option value='PUBLIC_SECTOR_ADMINISTRATION'>PUBLIC SECTOR ADMINISTRATION</option>
                                            <option value='ART_ENTERTAINMENT'>ART ENTERTAINMENT</option>
                                            <option value='ART_ENTERTAINMENT'>AUTO AVIATION</option>
                                            <option value='BANKING_LENDING'>BANKING LENDING</option>
                                            <option value='BUSINESS_CONSULTANCY_LEGAL'>BUSINESS CONSULTANCY LEGAL</option>
                                            <option value='CONSTRUCTION_REPAIR'>CONSTRUCTION REPAIR</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="">
                                        Registered Country
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Enter Company Name" name="registeredCountry" type="text" id="" value="">
                                        </input>
                                        @if($errors->has('registeredCountry'))
                                            <span class="error">
                                                {{ $errors->first('registeredCountry') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="source_of_funds">Source of Funds</label>
                                    <div class="col-sm-6">
                                        <select class="select2" name="source_of_funds" id="source_of_funds">
                                            <option value='LABOUR_CONTRACT'>LABOUR CONTRACT</option>
                                            <option value='CIVIL_CONTRACT'>CIVIL CONTRACT</option>
                                            <option value='FUNDS_FROM_OTHER_AUXILIARY_SOURCES'>FUNDS FROM OTHER AUXILIARY SOURCES</option>
                                            <option value='RENT'>RENT</option>
                                            <option value='FUNDS_FROM_OTHER_AUXILIARY_SOURCES'>FUNDS FROM OTHER AUXILIARY SOURCES</option>
                                            <option value='SALE_OF_MOVABLE_ASSETS'>SALE OF MOVABLE ASSETS</option>
                                            <option value='SALE_OF_REAL_ESTATE'>SALE OF REAL ESTATE</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="streetAddress">
                                        Street Address
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Enter Street Address" name="streetAddress" type="text" id="streetAddress" value="">
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
                                        <input class="form-control" placeholder="Enter City/State" name="cityState" type="text" id="cityState" value="">
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
                                        <input class="form-control" placeholder="Enter Zip Code" name="zipCode" type="text" id="" value="">
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
                                    <label class="col-sm-3 control-label require" for="status">Status</label>
                                    <div class="col-sm-6">
                                        <select class="select2" name="status" id="status">
                                            <option value='0'>Pending</option>
                                            <option value='1'>Processing</option>
                                            <option value='2'>Rejected</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <a class="btn btn-theme-danger pull-left" href="{{ url(\Config::get('adminPrefix').'/app-registrations') }}" id="users_cancel">Cancel</a>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="{{ asset('public/backend/intl-tel-input-13.0.0/intl-tel-input-13.0.0/build/js/intlTelInput.js') }}" type="text/javascript"></script>

<!-- isValidPhoneNumber -->
<script src="{{ asset('public/dist/js/isValidPhoneNumber.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $('#appdateTime').datetimepicker({
		format: 'DD-MM-YYYY'
	});
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


