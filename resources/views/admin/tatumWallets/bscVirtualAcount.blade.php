@extends('admin.layouts.master')
@section('title', 'BSC Virtual Accounts')

@section('head_style')
    <!-- dataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/DataTables_latest/DataTables-1.10.18/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/DataTables_latest/Responsive-2.2.2/css/responsive.dataTables.min.css') }}">
@endsection

@section('page_content')
<div class="row">
        <div class="col-md-12">
            <div class="box box-info" id="user-create">
                    <div class="box-header with-border ">
                      <h3 class="box-title">Create BSC Virtual Account</h3>
                    </div>
                    <form action="{{ url(\Config::get('adminPrefix').'/virtual-accounts/create-virtual-account') }}" class="form-horizontal" id="user_form" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="user_id" id="defaultCountry" value="{{$user_id}}" class="form-control">
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="company_type">Block Chain</label>
                                    <div class="col-sm-6">
                                        <select class="select2 form-control" name="company_type" id="company_type">
                                            <option value='bnbSmartChain'>BNB Smart Chain</option>
                                            <!-- <option value='LTD_COMPANY'>LTD Company</option>
                                            <option value='LLP_COMPANY'>LLP Company</option> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="company_type">Wallet</label>
                                    <div class="col-sm-6">
                                        <select class="select2 form-control" name="company_type" id="company_type">
                                            <option value='bscWallet'>Binance Smart Chain</option>
                                           <!--  <option value='LTD_COMPANY'>LTD Company</option>
                                            <option value='LLP_COMPANY'>LLP Company</option> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="company_type">Currency</label>
                                    <div class="col-sm-6">
                                        <select class="select2 form-control" name="currency_type" id="company_type">
                                            <option value='BUSD'>BUSD</option>
                                            <option value='USDT'>USDT</option>
                                            <option value='USDC'>USDC</option>
                                            <!-- <option value='CELO'>CELO</option>
                                            <option value='XDC'>XDC</option> -->
                                            <!-- <option value='LTD_COMPANY'>LTD Company</option>
                                            <option value='LLP_COMPANY'>LLP Company</option> -->
                                        </select>
                                    </div>
                                </div>

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

<!-- jquery.dataTables js -->
<script src="{{ asset('public/backend/DataTables_latest/DataTables-1.10.18/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/backend/DataTables_latest/Responsive-2.2.2/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>

<!--  $dataTable->scripts() -->
<script type="text/javascript">
</script>
@endpush