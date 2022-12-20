@extends('admin.layouts.master')
@section('title', 'API Credentials')
@section('page_content')

    <!-- Main content -->
    <div class="row">
        <div class="col-md-3 settings_bar_gap">
            @include('admin.common.settings_bar')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">API Credentials</h3>
                </div>
                <form action="{{ route('apiCred.store') }}" method="post" class="form-horizontal" id="api-credentials" >
                    {!! csrf_field() !!}
                    @method('POST')

                    <!-- box-body -->
                    <div class="box-body">
                        
                        <div class="form-group">
                          <label class="col-sm-4 control-label" for="inputEmail3">Application ID</label>
                          <div class="col-sm-6">
                            <input type="text" name="application_id" class="form-control" value="{{ $apiData['value']['application_id'] ?? '' }}" placeholder="">
                            @if($errors->has('application_id'))
                              <span class="help-block">
                                <strong class="text-danger">{{ $errors->first('application_id') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>

                        
                        <div class="form-group">
                          <label class="col-sm-4 control-label" for="inputEmail3">API key</label>
                          <div class="col-sm-6">
                            <input type="text" name="api_key" class="form-control" value="{{ $apiData['value']['api_key'] ?? '' }}" placeholder="">
                            @if($errors->has('api_key'))
                              <span class="help-block">
                                <strong class="text-danger">{{ $errors->first('api_key') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>
                      
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputEmail3">UI key</label>
                      <div class="col-sm-6">
                        <input type="text" name="ui_key" class="form-control" value="{{ $apiData['value']['ui_key'] ?? '' }}" placeholder="">
                        @if($errors->has('ui_key'))
                          <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('ui_key') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputEmail3">Corporates</label>
                      <div class="col-sm-6">
                        <input type="text" name="corporates_id" class="form-control" value="{{ $apiData['value']['corporates_id'] ?? '' }}" placeholder="">
                        @if($errors->has('corporates_id'))
                          <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('corporates_id') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group align-middle">
                      <label class="col-sm-4 control-label align-middle" for="inputEmail3">Managed Cards</label>
                      <div class="col-sm-6">
                        <span><em>managed_cards_debit</em></span>
                        <input type="text" name="managed_cards_debit" class="form-control" value="{{ $apiData['value']['managed_cards_debit'] ?? '' }}" placeholder="">
                        @if($errors->has('managed_cards_debit'))
                          <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('managed_cards_debit') }}</strong>
                          </span>
                        @endif
                        <span><em>managed_cards_prepaid</em></span>
                        <input type="text" name="managed_cards_prepaid" class="form-control" value="{{ $apiData['value']['managed_cards_prepaid'] ?? '' }}" placeholder="">
                        @if($errors->has('managed_cards_prepaid'))
                          <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('managed_cards_prepaid') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputEmail3">Transfers</label>
                      <div class="col-sm-6">
                        <input type="text" name="transfer_id" class="form-control" value="{{ $apiData['value']['transfer_id'] ?? '' }}" placeholder="">
                        @if($errors->has('transfer_id'))
                          <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('transfer_id') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputEmail3">Send</label>
                      <div class="col-sm-6">
                        <input type="text" name="send_id" class="form-control" value="{{ $apiData['value']['send_id'] ?? '' }}" placeholder="">
                        @if($errors->has('send_id'))
                          <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('send_id') }}</strong>
                          </span>
                        @endif
                    </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputEmail3">Outgoing Wire Transfers</label>
                      <div class="col-sm-6">
                        <input type="text" name="outgoing_wire_transfer" class="form-control" value="{{ $apiData['value']['outgoing_wire_transfer'] ?? '' }}" placeholder="">
                        @if($errors->has('outgoing_wire_transfer'))
                          <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('outgoing_wire_transfer') }}</strong>
                          </span>
                        @endif
                    </div>
                    </div>
                </div>
                    <!-- /.box-body -->

                    <!-- box-footer -->
                    @if(Common::has_permission(\Auth::guard('admin')->user()->id, 'edit_api_credentials'))
                        <div class="box-footer">
                          <button class="btn btn-primary btn-flat pull-right" type="submit">Submit</button>
                        </div>
                    @endif
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>

@endsection

@push('extra_body_scripts')

<!-- jquery.validate -->
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

$.validator.setDefaults({
    highlight: function(element) {
        $(element).parent('div').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).parent('div').removeClass('has-error');
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);
    }
});

$('#api-credentials').validate({
    rules: {
        captcha_secret_key: {
            required: true,
        },
        captcha_site_key: {
            required: true,
        },
    },
});

</script>

@endpush
