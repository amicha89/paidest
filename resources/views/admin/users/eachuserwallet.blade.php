@extends('admin.layouts.master')

@section('title', 'Wallets')

@section('head_style')
    <!-- dataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/DataTables_latest/DataTables-1.10.18/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/DataTables_latest/Responsive-2.2.2/css/responsive.dataTables.min.css') }}">
@endsection

@section('page_content')

    <div class="box">
       <div class="panel-body ml-20">
            <ul class="nav nav-tabs cus" role="tablist">
                <li>
                  <a href='{{ url(\Config::get('adminPrefix')."/users/edit/$users->id")}}'>Profile</a>
                </li>

                <li>
                  <a href="{{ url(\Config::get('adminPrefix')."/users/transactions/$users->id")}}">Transactions</a>
                </li>
                <li class="active">
                  <a href="{{ url(\Config::get('adminPrefix')."/users/wallets/$users->id")}}">Wallets</a>
                </li>
                <li>
                  <a href="{{ url(\Config::get('adminPrefix')."/users/tickets/$users->id")}}">Tickets</a>
                </li>
                <li>
                  <a href="{{ url(\Config::get('adminPrefix')."/users/disputes/$users->id")}}">Disputes</a>
                </li>
                @if (config('referral.is_active') && count($users->referral_award_awarded_user) > 0)
                    <li>
                        <a href='{{ url(\Config::get("adminPrefix")."/users/referral-awards/" . $users->id) }}'>{{ __('Referral Awards') }}</a>
                    </li>
                @endif
           </ul>
          <div class="clearfix"></div>
       </div>
    </div>

    @if ($users->status == 'Inactive')
        <h3>{{ $users->first_name.' '.$users->last_name }}&nbsp;<span class="label label-danger">Inactive</span></h3>
    @elseif ($users->status == 'Suspended')
        <h3>{{ $users->first_name.' '.$users->last_name }}&nbsp;<span class="label label-warning">Suspended</span></h3>
    @elseif ($users->status == 'Active')
        <h3>{{ $users->first_name.' '.$users->last_name }}&nbsp;<span class="label label-success">Active</span></h3>
    @endif
    <div class="box box-default">
        <div class="box-body">
            <div class="d-flex justify-content-between pull-right">
                <!-- <div>
                    <div class="top-bar-title padding-bottom pull-left">Tatum.io Wallets</div>
                </div> -->

                <div>
                <!-- {{url(\Config::get('adminPrefix').'/users/create')}} -->
                    @if(Common::has_permission(\Auth::guard('admin')->user()->id, 'add_user'))
                        <a href="{{url(\Config::get('adminPrefix')."/virtual-accounts/$users->id")}} " class="btn btn-theme"><span class="fa fa-plus"> &nbsp;</span>Create Virtual Account</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="box">
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="eachuserwallet">
                                <thead>
                                    <tr>
                                        <th>Currency</th>
                                        <th>Account Balance</th>
                                        <th>Available Balance</th>
                                        <th>Virtual Account ID</th>
                                        <th>Wallet Public Key</th>
                                        <th>Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($virtualAcc)
                                        @foreach($virtualAcc as $accont)
                                            <tr>
                                                <td>{{ $accont->currency }}</td>

                                                <td>{{ $accont->account_balance }}</td>
                                                <td>{{ $accont->available_balance }}</td>
                                                <td>{{ $accont->virtualacc_id }}</td>
                                                
                                                <td>{{substr($accont->xpub, 0, 20)}}</td>

                                                @if ($accont->active == '1')
                                                    <td><span class="label label-success">Yes</span></td>
                                                @elseif ($accont->active == '0')
                                                    <td><span class="label label-danger">No</span></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        No wallet Found!
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('extra_body_scripts')

<!-- jquery.dataTables js -->
<script src="{{ asset('public/backend/DataTables_latest/DataTables-1.10.18/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/backend/DataTables_latest/Responsive-2.2.2/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $(function () {
      $("#eachuserwallet").DataTable({
            "order": [],
            "language": '{{Session::get('dflt_lang')}}',
            "pageLength": '{{Session::get('row_per_page')}}'
        });
    });
</script>
@endpush
