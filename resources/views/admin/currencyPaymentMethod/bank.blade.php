<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <button data-toggle="modal" data-target="#addModal" id="addBtn" class="btn btn-theme pull-left bank-add-btn" type="button">
            <span class="fa fa-plus"> &nbsp;</span>{{ __('Add Bank') }}
        </button>

        <div class="table-responsive">
            <table class="table table-bordered">
                <table class="table text-center" id="banks_list">
                    <thead>
                        <tr>
                            <td class="d-none"><strong>{{ __('ID') }}</strong></td>
                            <td><strong>{{ __('Bank Name') }}</strong></td>
                            <td><strong>{{ __('Account') }}</strong></td>
                            <td><strong>{{ __('Default') }}</strong></td>
                            <td><strong>{{ __('Action') }}</strong></td>
                        </tr>
                    </thead>
                    <tbody id="bank_body">
                    </tbody>
                </table>
            </table>
        </div>
    </div>
</div>

<!-- addModal Modal-->
<div class="modal fade" id="addModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header d-block">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{{ __('Add Bank Details') }}</h4>
            </div>

            <form id="add-bank" method="post" enctype="multipart/form-data">

                {{csrf_field()}}

                <div class="modal-body">
                    <div id="add-bank-error" class="d-none">
                        <div class="alert alert-danger">
                            <ul id="add-bank-error-messages">
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-1"></div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>{{ __('Default') }}</label>
                                    <select class="form-control" name="default" id="default">
                                        <option value=''></option>
                                        <option value='Yes'>{{ __('Yes') }}</option>
                                        <option value='No'>{{ __('No') }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Bank Account Holder\'s Name') }}</label>
                                    <input name="account_name" id="account_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Bank Account Number/IBAN') }}</label>
                                    <input name="account_number" id="account_number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('SWIFT Code') }}</label>
                                    <input name="swift_code" id="swift_code" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Bank Name') }}</label>
                                    <input name="bank_name" id="bank_name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-1"></div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Branch Name') }}</label>
                                    <input name="branch_name" id="branch_name" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Branch City') }}</label>
                                    <input name="branch_city" id="branch_city" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Branch Address') }}</label>
                                    <input name="branch_address" id="branch_address" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Country') }}</label>
                                    <select name="country" id="country" class="form-control">
                                        <option value=""></option>
                                        @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @php
                                    $modules = collect(addonPaymentMethods('Bank'))->sortBy('type')->reverse()->toArray();
                                @endphp

                                <!-- Activated for -->
                                <div class="form-group">
                                                            
                                    <label>{{ __('Activate For') }} </label>
                                    <div class="row">
                                        <div class="col-md-6 pr-customize">
                                            <div class="check-parent-div flex-for-column mb-box">
                                                <label class="checkbox-container">
                                                    <input type="checkbox" name="add_transaction_type" value="deposit" {{ isset($currencyPaymentMethod->activated_for)  && in_array('deposit' , explode(':', str_replace(['{', '}', '"', ','], '',  $currencyPaymentMethod->activated_for)) ) ? 'checked': "" }} id="view_0"  class="view_checkbox">
                                                    <p class="px-1 f-property mb-unset">{{ __('Deposit') }} </p>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>

                                        @foreach ($modules as $key => $module)

                                            
                                            @if (count($module['type']) < 2)
                                                <div class="col-md-6 pr-customize">
                                                    <div class="check-parent-div flex-for-column mb-box">
                                                        @foreach ($module['type'] as $type)
                                                            <label class="checkbox-container">
                                                                <input type="checkbox" name="add_transaction_type" value="{{ $type }}" {{ isset($currencyPaymentMethod->activated_for)  && in_array($type , explode(':', str_replace(['{', '}', '"', ','], '',  $currencyPaymentMethod->activated_for)) ) ? 'checked': "" }} id="view_0"  class="view_checkbox">
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
                                                                <input type="checkbox" name="add_transaction_type" value="{{ $type }}" id="view_0" class="view_checkbox" {{ isset($currencyPaymentMethod->activated_for) && in_array($type , explode(':', str_replace(['{', '}', '"', ','], '',  $currencyPaymentMethod->activated_for)) ) ? 'checked': "" }} >
                                                                <p class="px-1 f-property mb-unset">{{ str_replace('_', ' ', ucfirst($type)) }}</p>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-1"></div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>{{ __('Bank Logo') }}</label>
                                    <input type="file" name="bank_logo" id="bank_logo" class="form-control input-file-field">
                                    <div class="clearfix"></div>
                                    <small class="form-text text-muted"><strong>{{ allowedImageDimension(120,80) }}</strong></small>
                                    <div class="preview_bank_logo">
                                        <img src="{{ url('public/uploads/userPic/default-image.png') }}" width="120" height="80" id="bank-demo-logo-preview"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-1"></div>
                            <div class="col-md-4"><button type="button" class="btn btn-theme-danger pull-left" data-dismiss="modal">{{ __('Close') }}</button></div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-theme pull-right" id="submit_btn">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- editModal Modal-->
<div class="modal fade" id="editModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header d-block">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{{ __('Edit Bank Details') }}</h4>
            </div>

            <form id="edit-bank" method="post">
                {{csrf_field()}}

                <input type="hidden" name="bank_id" id="bank_id">
                <input type="hidden" name="file_id" id="file_id">
                <input type="hidden" name="edit_currency_id" id="edit_currency_id">
                <input type="hidden" name="edit_paymentMethod" id="edit_paymentMethod">
                <input type="hidden" name="currencyPaymentMethodId" id="currencyPaymentMethodId">

                <div class="modal-body">
                    <div id="bank-error" class="d-none">
                        <div class="alert alert-danger">
                            <ul id="bank-error-messages">
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-1"></div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>{{ __('Default') }}</label>
                                    <select class="form-control" name="edit_default" id="edit_default">
                                        <option value='Yes'>{{ __('Yes') }}</option>
                                        <option value='No'>{{ __('No') }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Bank Account Holder\'s Name') }}</label>
                                    <input name="edit_account_name" id="edit_account_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Bank Account Number/IBAN') }}</label>
                                    <input name="edit_account_number" id="edit_account_number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('SWIFT Code') }}</label>
                                    <input name="edit_swift_code" id="edit_swift_code" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Bank Name') }}</label>
                                    <input name="edit_bank_name" id="edit_bank_name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-1"></div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Branch Name') }}</label>
                                    <input name="edit_branch_name" id="edit_branch_name" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Branch City') }}</label>
                                    <input name="edit_branch_city" id="edit_branch_city" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Branch Address') }}</label>
                                    <input name="edit_branch_address" id="edit_branch_address" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Country') }}</label>
                                    <select name="edit_country" id="edit_country" class="form-control">
                                        @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Activated for -->
                                <div class="form-group">
                                                            
                                    <label>{{ __('Activate For') }} </label>
                                    <div class="row">
                                        <div class="col-md-6 pr-customize">
                                            <div class="check-parent-div flex-for-column mb-box">
                                                <label class="checkbox-container">
                                                    <input type="checkbox" name="update_transaction_type" value="deposit" {{ isset($currencyPaymentMethod->activated_for)  && in_array('deposit' , explode(':', str_replace(['{', '}', '"', ','], '',  $currencyPaymentMethod->activated_for)) ) ? 'checked': "" }} id="view_0"  class="view_checkbox">
                                                    <p class="px-1 f-property mb-unset">{{ __('Deposit') }} </p>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>

                                        @foreach ($modules as $key => $module)
                                            @if (count($module['type']) < 2)
                                                <div class="col-md-6 pr-customize">
                                                    <div class="check-parent-div flex-for-column mb-box">
                                                        @foreach ($module['type'] as $type)
                                                            <label class="checkbox-container">
                                                                <input type="checkbox" name="update_transaction_type" value="{{ $type }}" {{ isset($currencyPaymentMethod->activated_for)  && in_array($type , explode(':', str_replace(['{', '}', '"', ','], '',  $currencyPaymentMethod->activated_for)) ) ? 'checked': "" }} id="view_0"  class="view_checkbox">
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
                                                                <input type="checkbox" name="update_transaction_type" value="{{ $type }}" id="view_0" class="view_checkbox" {{ isset($currencyPaymentMethod->activated_for) && in_array($type , explode(':', str_replace(['{', '}', '"', ','], '',  $currencyPaymentMethod->activated_for)) ) ? 'checked': "" }} >
                                                                <p class="px-1 f-property mb-unset">{{ str_replace('_', ' ', ucfirst($type)) }}</p>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-1"></div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>{{ __('Bank Logo') }}</label>
                                    <input type="file" name="edit_bank_logo" id="edit_bank_logo" class="form-control input-file-field">
                                    <div class="clearfix"></div>
                                    <small class="form-text text-muted"><strong>{{ allowedImageDimension(120,80) }}</strong></small>
                                    <div class="preview_edit_bank_logo"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-1"></div>
                            <div class="col-md-4"><button type="button" class="btn btn-theme-danger pull-left" data-dismiss="modal">{{ __('Close') }}</button></div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-theme pull-right" id="edit_submit_btn">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>