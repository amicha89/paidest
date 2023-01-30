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
                                    <label class="col-sm-3 control-label require" for="blockchain_type">Block Chain</label>
                                    <div class="col-sm-6">
                                        <select class="select2 form-control" name="blockchain_type" id="blockchain_type">
                                            <option value="">-- Select Blockchain --</option>
                                            <option value='BSC'>BNB Smart Chain</option>
                                            <option value='ETH'>Ethereum Blockchain</option>
                                            <option value='TRON'>Tron Blockchain</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="wallet_key">Wallet Public Key</label>
                                    <div class="col-sm-6">
                                        <select class="select2 form-control" name="wallet_xpub" id="wallet_key">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label require" for="company_type">Currency</label>
                                    <div class="col-sm-6">
                                        <select class="select2 form-control" name="currency_type" id="company_type">
                                            <option value="">-- Select Currency --</option>
                                            <option value='BUSD'>BUSD</option>
                                            <option value='BUSD_BSC'>BUSD_BSC</option>
                                            <option value='USDT'>USDT</option>
                                            <option value='USDT_TRON'>USDT_TRON</option>
                                            <option value='USDC'>USDC</option>
                                            <option value='USDC'>USDC_BSC</option>
                                            <option value='BSC'>BSC</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <a class="btn btn-theme-danger pull-left" href="{{ url(\Config::get('adminPrefix')."/users/wallets/$user_id") }}" id="users_cancel">Cancel</a>
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
<!-- <script src="{{ asset('public/backend/DataTables_latest/DataTables-1.10.18/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/backend/DataTables_latest/Responsive-2.2.2/js/dataTables.responsive.min.js') }}" type="text/javascript"></script> -->

<!--  $dataTable->scripts() -->
<!-- <script type="text/javascript">
</script> -->
<script src="{{ asset('public/dist/js/admin_custom.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            // get wallets based on blockchain type
            $('#blockchain_type').on('change', function () {
                var blockchainType = this.value;
                console.log(blockchainType)
                $("#wallet_key").html('');
                $.ajax({
                    url: "{{route('wallets')}}",
                    method: "POST",
                    data: {
                        blockchain_type: blockchainType,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#wallet_key').html('<option value="">-- Select Wallet --</option>');
                        $.each(result, function (key, value) {
                            $("#wallet_key").append('<option value="' + value
                                .xpub + '">' + value.public_key + '</option>');
                        });
                        // $('#city-dropdown').html('<option value="">-- Select City --</option>');
                    }
                });
            });
  
            /*------------------------------------------
            --------------------------------------------
            State Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
            $('#state-dropdown').on('change', function () {
                var idState = this.value;
                $("#city-dropdown").html('');
                $.ajax({
                    url: "{{url('api/fetch-cities')}}",
                    type: "POST",
                    data: {
                        state_id: idState,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (res) {
                        $('#city-dropdown').html('<option value="">-- Select City --</option>');
                        $.each(res.cities, function (key, value) {
                            $("#city-dropdown").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    }
                });
            });
  
        });
    </script>
@endpush