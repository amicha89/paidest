@extends('admin.layouts.master')

@section('title', 'Create Wallet')

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
                      <h3 class="box-title">Create Wallet</h3>
                    </div>
                    <form action="{{ url(\Config::get('adminPrefix').'/crypto-wallets/create-bsc') }}" class="form-horizontal" id="user_form" method="POST">
                        @csrf
                        @method('POST')
                            <div class="box-body">
                                <!-- <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="first_name">
                                        X-API-Key
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="X-API-Key" name="xApiKey" type="text" id="first_name" value="{{ old('xApiKey') }}">
                                        </input>

                                        @if($errors->has('xApiKey'))
                                            <span class="error">
                                                {{ $errors->first('xApiKey') }}
                                            </span>
                                        @endif

                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="blockchain">Blockchain</label>
                                    <div class="col-sm-6">
                                        <select class="select2" name="blockchain" id="blockchain">
                                            <option value='BSC'>BNB Smart Chain</option>
                                            <option value='ETH'>Ethereum</option>
                                            <option value='TRON'>Tron</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="first_name">
                                        Wallet Detail
                                    </label>
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="Detail" name="wallet_number" type="text" id="first_name" value="{{ old('wallet_number') }}">
                                        </input>

                                        @if($errors->has('xApiKey'))
                                            <span class="error">
                                                {{ $errors->first('xApiKey') }}
                                            </span>
                                        @endif

                                    </div>
                                </div> -->

                              
                                <div class="box-footer">
                                    <!-- <a class="btn btn-theme-danger pull-left" href="{{ url(\Config::get('adminPrefix').'/app-registrations') }}" id="users_cancel">Cancel</a> -->
                                    <button type="submit" class="btn btn-theme pull-right" id="app_create"><i class="fa fa-spinner fa-spin" style="display: none;"></i> <span id="app_create_text">Create</span></button>
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


