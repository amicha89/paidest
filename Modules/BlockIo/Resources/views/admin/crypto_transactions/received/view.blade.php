@extends('admin.layouts.master')

@section('title', __('View Crypto Received Transaction'))

@section('page_content')
<div class="box box-default">
	<div class="box-body">
		<div class="d-flex justify-content-between">
			<div>
				<div class="top-bar-title padding-bottom pull-left">{{ __('Crypto Received Transaction Details') }}</div>
			</div>

			<div>
				@if ($transaction->status)
					<h4 class="text-left">{{ __('Status') }} :
						@if ($transaction->status == 'Success')<span class="text-green">{{ __('Success') }}</span>@endif
					</h4>
				@endif
			</div>
		</div>
	</div>
</div>

<section class="min-vh-100">
    <div class="my-30">
        <div class="row">
            <form action="#" class="form-horizontal" id="transactions_form" method="POST">
                <!-- Page title start -->
                <div class="col-md-8 col-xl-9">
                    <div class="box">
                        <div class="box-body">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="mt-4 p-4 bg-secondary rounded shadow">
                                        {{-- Sender --}}
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="sender">{{ __('Sender') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ getColumnValue($transaction->end_user) }}</p>
                                            </div>
                                        </div>

                                        {{-- Receiver --}}
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="receiver">{{ __('Receiver') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ getColumnValue($transaction->user) }}</p>
                                            </div>
                                        </div>

                                        <!-- Sender Address -->
                                        @if (isset($senderAddress))
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="crypto_sender_address">{{ __('Sender Address') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static" id="crypto_sender_address">{{ $senderAddress }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Receiver Address -->
                                        @if (isset($receiverAddress))
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="crypto_receiver_address">{{ __('Receiver Address') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static" id="crypto_receiver_address">{{ $receiverAddress }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Txid -->
                                        @if (isset($txId))
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="crypto_txid">{{ optional($transaction->payment_method)->name }} {{ __('TxId') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static" id="crypto_txid">{{ wordwrap($txId, 50, "\n", true) }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Confirmations -->
                                        @if (isset($confirmations))
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="crypto_confirmations">{{ __('Confirmations') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">{{ $confirmations }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($transaction->uuid)
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="transactions_uuid">{{ __('Transaction ID') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">{{ $transaction->uuid }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($transaction->currency)
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="currency">{{ __('Crypto Currency') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">{{ $transaction->currency->code }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($transaction->created_at)
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="created_at">{{ __('Date') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">{{ dateFormat($transaction->created_at) }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xl-3">
                    <div class="box">
                        <div class="box-body">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="mt-4 p-4 bg-secondary rounded shadow">
                                        @if ($transaction->subtotal)
                                            <div class="form-group">
                                                <label class="control-label col-sm-6" for="subtotal">{{ __('Amount') }}</label>
                                                <div class="col-sm-6">
                                                    <p class="form-control-static">
                                                    {{ moneyFormat(optional($transaction->currency)->symbol, formatNumber($transaction->subtotal, $transaction->currency_id)) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group total-deposit-feesTotal-space">
                                            <label class="control-label col-sm-6" for="fee">{{ __('Network Fees') }}</label>

                                            @php
                                                $total_transaction_fees = $transaction->charge_percentage + $transaction->charge_fixed;
                                            @endphp
                                            <div class="col-sm-6">
                                                <p class="form-control-static">
                                                    {{ moneyFormat(optional($transaction->currency)->symbol, formatNumber($transaction->charge_fixed, $transaction->currency_id)) }}
                                                </p>
                                            </div>
                                        </div>

                                        <hr class="increase-hr-height">

                                        @if ($transaction->total)
                                            <div class="form-group total-deposit-space">
                                                <label class="control-label col-sm-6" for="total">{{ __('Total') }}</label>
                                                <input type="hidden" class="form-control" name="total" value="{{ ($transaction->total) }}">
                                                <div class="col-sm-6">
                                                    <p class="form-control-static">
                                                        {{ moneyFormat(optional($transaction->currency)->symbol, str_replace("-", '', formatNumber($transaction->total, $transaction->currency_id))) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

