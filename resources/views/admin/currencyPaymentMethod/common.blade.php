@php
    $paymentMethod = $list_menu == 'mts' ? 'Wallet' : ucfirst($list_menu);
    $modules = collect(addonPaymentMethods($paymentMethod))->sortBy('type')->reverse()->toArray();
@endphp

@if ($list_menu != 'mts')
    <!-- processing_time -->
    <div class="form-group">
        <label class="col-sm-3 control-label" for="processing_time">{{ __('Processing Time (days)') }}</label>
        <div class="col-sm-6">
            <input class="form-control processing_time" name="processing_time" type="text" placeholder="{{ __(':X Processing Time', ['X' => $paymentMethod]) }}"
            value="{{ isset($currencyPaymentMethod->processing_time) ? $currencyPaymentMethod->processing_time : '' }}" id="processing_time">

            @if ($errors->has('processing_time'))
                <span class="help-block">
                    <strong>{{ $errors->first('processing_time') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="clearfix"></div>
@endif

<!-- Activated for -->
<div class="form-group">
							
    <label class="col-lg-3 control-label mb-7p">{{ __('Activate For') }} </label>

    <div class="col-lg-6 col-6">
        <div class="row">
            @if ($list_menu != 'mts')
                <div class="col-md-6 pr-customize">
                    <div class="check-parent-div flex-for-column mb-box">
                        <label class="checkbox-container">
                            <input type="checkbox" name="transaction_type[]" value="deposit" {{ isset($currencyPaymentMethod->activated_for)  && in_array('deposit' , explode(':', str_replace(['{', '}', '"', ','], '',  $currencyPaymentMethod->activated_for)) ) ? 'checked': "" }} id="view_0"  class="view_checkbox">
                            <p class="px-1 f-property mb-unset">{{ __('Deposit') }} </p>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
            @endif

            @if ($list_menu != 'coinpayments')
                @foreach ($modules as $key => $module)
                    @if (count($module['type']) < 2)
                        <div class="col-md-6 pr-customize">
                            <div class="check-parent-div flex-for-column mb-box">
                                @foreach ($module['type'] as $type)
                                    <label class="checkbox-container">
                                        <input type="checkbox" name="transaction_type[]" value="{{ $type }}" {{ isset($currencyPaymentMethod->activated_for)  && in_array($type , explode(':', str_replace(['{', '}', '"', ','], '',  $currencyPaymentMethod->activated_for)) ) ? 'checked': "" }} id="view_0"  class="view_checkbox">
                                        <p class="px-1 f-property mb-unset">{{ str_replace('_', ' ', ucfirst($type)) }} </p>
                                        <span class="checkmark"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="col-md-6">
                            <div class="check-parent-div flex-for-column mb-box">
                                <p class="font-bold">{{ $module['name'] }}</p>
                                @foreach ($module['type'] as $type)
                                    <label class="checkbox-container">
                                        <input type="checkbox" name="transaction_type[]" value="{{ $type }}" id="view_0" class="view_checkbox" {{ isset($currencyPaymentMethod->activated_for) && in_array($type , explode(':', str_replace(['{', '}', '"', ','], '',  $currencyPaymentMethod->activated_for)) ) ? 'checked': "" }} >
                                        <p class="px-1 f-property mb-unset">{{ str_replace('_', ' ', ucfirst($type)) }}</p>
                                        <span class="checkmark"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

        </div>	
    </div>
</div> 

<div class="box-footer">
    <a id="cancel_anchor" href="{{  url(\Config::get('adminPrefix')."/settings/currency") }}" class="btn btn-theme-danger">{{ __('Cancel') }}</a>
    <button type="submit" class="btn btn-theme pull-right" id="paymentMethodList_update">
        <i class="fa fa-spinner fa-spin d-none"></i> <span id="paymentMethodList_update_text">{{ __('Update') }}</span>
    </button>
</div>