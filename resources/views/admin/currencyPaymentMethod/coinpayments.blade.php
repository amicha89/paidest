<!-- coinPayments - Merchant Id -->
<div class="form-group">
    <label class="col-sm-3 control-label" for="coinpayments[merchant_id]">{{ __('Merchant Id') }}</label>
    <div class="col-sm-6">
        <input class="form-control coinpayments[merchant_id]" name="coinpayments[merchant_id]" type="text" placeholder="{{ __('CoinPayments Merchant Id') }}"
        value="{{ isset($currencyPaymentMethod->method_data) ? json_decode($currencyPaymentMethod->method_data)->merchant_id : '' }}" id="coinPayments_merchant_id">
        @if ($errors->has('coinpayments[merchant_id]'))
        <span class="help-block">
            <strong>{{ $errors->first('coinpayments[merchant_id]') }}</strong>
        </span>
        @endif
    </div>
</div>
<div class="clearfix"></div>

<!-- coinPayments - Public Key -->
<div class="form-group">
    <label class="col-sm-3 control-label" for="coinpayments[public_key]">{{ __('Public Key') }}</label>
    <div class="col-sm-6">
        <input class="form-control coinpayments[public_key]" name="coinpayments[public_key]" type="text" placeholder="{{ __('CoinPayments Public Key') }}"
        value="{{ isset($currencyPaymentMethod->method_data) ? json_decode($currencyPaymentMethod->method_data)->public_key : '' }}" id="coinPayments_public_key">
        @if ($errors->has('coinpayments[public_key]'))
            <span class="help-block">
                <strong>{{ $errors->first('coinpayments[public_key]') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="clearfix"></div>

<!-- coinPayments - Private Key -->
<div class="form-group">
    <label class="col-sm-3 control-label" for="coinpayments[private_key]">{{ __('Private Key') }}</label>
    <div class="col-sm-6">
        <input class="form-control coinpayments[private_key]" name="coinpayments[private_key]" type="text" placeholder="{{ __('CoinPayments Private Key') }}"
        value="{{ isset($currencyPaymentMethod->method_data) ? json_decode($currencyPaymentMethod->method_data)->private_key : '' }}" id="coinPayments_private_key">
        @if ($errors->has('coinpayments[private_key]'))
            <span class="help-block">
                <strong>{{ $errors->first('coinpayments[private_key]') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="clearfix"></div>

<div class="form-group">
    <label class="col-sm-3 control-label" for="webhook_url">{{ __('IPN URL') }}</label>
    <div class="col-sm-6">
        <div class="d-flex justify-content-between">
            <input name="webhook_url" class="form-control coinpayments_ipn_url" type="text" readonly value="{{ url('coinpayment/check') }}">
            <button class="btn btn-md btn-primary coin-copy" id="coinpayments_copy_button">
                {{ __('Copy') }}
            </button>
        </div>
        <small class="text-color"><strong>{!! __('Copy the above url and set it in :x field.', ['x' => '<a href="'. asset("public/images/payment_gateway/coinpayments_ipn.jpg") .'">'. __('Coinpayments IPN URL') .'</a>']) !!}</strong></small>
    </div>
</div>
<div class="clearfix"></div>