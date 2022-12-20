<!-- PayUMoney - Secret Key -->
<div class="form-group">
    <label class="col-sm-3 control-label" for="payUmoney[key]">{{ __('Secret Key') }}</label>
    <div class="col-sm-6">
        <input class="form-control payUmoney[key]" name="payUmoney[key]" type="text" placeholder="{{ __('PayUMoney Secret Key') }}"
        value="{{ isset($currencyPaymentMethod->method_data) ? json_decode($currencyPaymentMethod->method_data)->key : '' }}" id="payUMoney_key">
        @if ($errors->has('payUmoney[key]'))
            <span class="help-block">
                <strong>{{ $errors->first('payUmoney[key]') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="clearfix"></div>

<!-- PayUMoney - Salted Key -->
<div class="form-group">
    <label class="col-sm-3 control-label" for="payUmoney[salt]">{{ __('Salted Key') }}</label>
    <div class="col-sm-6">
        <input class="form-control payUmoney[salt]" name="payUmoney[salt]" type="text" placeholder="{{ __('PayUMoney Salted Key') }}"
        value="{{ isset($currencyPaymentMethod->method_data) ? json_decode($currencyPaymentMethod->method_data)->salt : '' }}" id="payUMoney_salt">
        @if ($errors->has('payUmoney[salt]'))
            <span class="help-block">
                <strong>{{ $errors->first('payUmoney[salt]') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="clearfix"></div>

<!-- PayUMoney - Mode -->
<div class="form-group">
    <label class="col-sm-3 control-label" for="payUmoney[mode]">{{ __('Mode') }}</label>
    <div class="col-sm-6">
        <select class="form-control" name="payUmoney[mode]" id="payUMoney_mode">
            <option value="">{{ __('Select Mode') }}</option>
            <option value='sandbox' {{ isset($currencyPaymentMethod->method_data) && (json_decode($currencyPaymentMethod->method_data)->mode) == 'sandbox' ? 'selected':"" }} >{{ __('sandbox') }}</option>
            <option value='live' {{ isset($currencyPaymentMethod->method_data) && (json_decode($currencyPaymentMethod->method_data)->mode) == 'live' ? 'selected':"" }} >{{ __('live') }}</option>
        </select>
    </div>
</div>
<div class="clearfix"></div>
