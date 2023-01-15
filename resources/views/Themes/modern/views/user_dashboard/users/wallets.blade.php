@extends('user_dashboard.layouts.app')
@section('content')
<section class="min-vh-100">
    <div class="my-30">
        <div class="container-fluid">
            <!-- Page title start -->
            <div>
                <h3 class="page-title">{{ __('Wallets') }}</h3>
            </div>
            <!-- Page title end-->


            <!-- Crypto list section start-->
            <div class="row mt-4">
                <div class="col-lg-4">
                    <!-- Sub title start -->
                    <div class="mt-5">
                        <h3 class="sub-title">{{ __('Wallet list') }}</h3>
                        <p class="text-gray-500 text-16 text-justify">{{ __('Here you will get all of your Fiat and Crypto wallets including default one. You can also perform crypto send/receive of your crypto coins.') }}</p>
                    </div>
                    <!-- Sub title end-->
                </div>


                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-xl-10">
                            @if($wallets->count()>0)
                                @foreach($wallets as $wallet)
                                    @php
                                        $walletCurrencyCode = encrypt(optional($wallet->currency)->code);
                                        $walletId = encrypt($wallet->id);
                                        $provider = isset($wallet->cryptoAssetApiLogs->payment_method->name) && !empty($wallet->cryptoAssetApiLogs->payment_method->name) ? strtolower($wallet->cryptoAssetApiLogs->payment_method->name): '';
                                    @endphp
                                    <div class="col-md-12 mt-4">
                                        <div class="d-flex shadow bg-secondary p-5 justify-content-between">
                                            <div class="d-flex align-items-center ">
                                                <div class="pr-2">
                                                    @if(empty($wallet->currency->logo))
                                                        <img src="{{theme_asset('public/user_dashboard/images/favicon.png')}}" class="w-50p">
                                                    @else
                                                        <img src='{{asset("public/uploads/currency_logos/".$wallet->currency->logo)}}' class="w-50p">
                                                    @endif
                                                </div>
                                                <div class="pr-2">
                                                    <p>{{ $wallet->currency->code }}</p>
                                                    <p>
                                                        @if($wallet->balance > 0)
                                                            <span class="text-success">{{ '+'. formatNumber($wallet->balance, $wallet->currency->id) }}</span>
                                                        @elseif($wallet->balance == 0)
                                                            <span>{{ formatNumber($wallet->balance, $wallet->currency->id) }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            @if ($wallet->currency->type == 'crypto_asset' && $provider_status == 'Active' && isActive('BlockIo'))
                                            <div>
                                                <div class="pr-2">
                                                    <a href="{{ route('user.crypto_send.create', [$walletCurrencyCode, $walletId, $provider]) }}">
                                                        <p class="crypto-link"> <i class="fas fa-share-square"></i> {{ __('Send') }} </p>
                                                    </a>

                                                    <a href="{{ route('user.crypto_receive.create', [$walletCurrencyCode, $walletId, $provider]) }}">
                                                        <p class="crypto-link mt-3"> <i class="fas fa-hand-holding-usd"></i> {{ __('Receive') }}</p>
                                                    </a>
                                                </div>
                                            </div>
                                            @elseif (($wallet->currency->type == 'crypto' || $wallet->currency->type == 'fiat') && $wallet->currency->status == 'Active')
                                                <div>
                                                    <div class="pr-2">
                                                        <a href="{{ url("/deposit") }}">
                                                            <div class="crypto-link d-flex">
                                                                <div>
                                                                    <svg id="ej0XF8uPx911" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="sidebaricon" stroke="currentColor" viewBox="0 -20 512 512" shape-rendering="geometricPrecision" text-rendering="geometricPrecision">
                                                                        <path id="ej0XF8uPx912"  stroke-width="2" d="M478.609000,225.480000L478.609000,172.521000C478.609000,144.903000,456.140000,122.434000,428.522000,122.434000L363.913000,122.434000L255.962000,26.478000C249.637000,20.855000,240.103000,20.855000,233.778000,26.478000L125.826000,122.435000L83.478000,122.435000C37.448000,122.435000,0,159.883000,0,205.913000L0,406.261000C0,452.291000,37.448000,489.739000,83.478000,489.739000L428.521000,489.739000C456.139000,489.739000,478.608000,467.270000,478.608000,439.652000L478.608000,386.693000C498.041000,379.801000,511.999000,361.243000,511.999000,339.478000L511.999000,272.695000C512,250.930000,498.041000,232.372000,478.609000,225.480000ZM244.870000,61.294000L313.653000,122.435000L176.087000,122.435000L244.870000,61.294000ZM445.217000,439.652000C445.217000,448.858000,437.727000,456.348000,428.521000,456.348000L83.478000,456.348000C55.860000,456.348000,33.391000,433.879000,33.391000,406.261000L33.391000,205.913000C33.391000,178.295000,55.860000,155.826000,83.478000,155.826000L428.521000,155.826000C437.727000,155.826000,445.217000,163.316000,445.217000,172.522000L445.217000,222.609000L395.130000,222.609000C349.100000,222.609000,311.652000,260.057000,311.652000,306.087000C311.652000,352.117000,349.100000,389.565000,395.130000,389.565000L445.217000,389.565000L445.217000,439.652000ZM478.609000,339.478000C478.609000,348.684000,471.119000,356.174000,461.913000,356.174000L395.130000,356.174000C367.512000,356.174000,345.043000,333.705000,345.043000,306.087000C345.043000,278.469000,367.512000,256,395.130000,256L461.913000,256C471.119000,256,478.609000,263.490000,478.609000,272.696000L478.609000,339.478000Z"/>
                                                                        <circle id="ej0XF8uPx913" stroke-width="2" r="16.696000" transform="matrix(1 0 0 1 395.13000000000000 306.08699999999999)"/>
                                                                    </svg>
                                                                </div>

                                                                <div class="mt-1">
                                                                    <span>{{ __('Deposit') }} </span>
                                                                </div>
                                                            </div>
                                                        </a>

                                                        <a href="{{ url("/payout") }}">
                                                            <div class="crypto-link d-flex mt-4">
                                                                <div>
                                                                    <svg id="emXzJrluOQG1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="sidebaricon" stroke="currentColor" viewBox="0 0 512.002000 512.002000" shape-rendering="geometricPrecision" text-rendering="geometricPrecision">
                                                                        <path id="emXzJrluOQG2" stroke-width="2" d="M511.994000,139.957000C511.952000,137.461000,510.625000,135.081000,508.513000,133.744000L349.913000,33.316000C349.136000,32.824000,348.275000,32.479000,347.373000,32.298000L186.830000,0.158000C178.875000,-1.438000,174.495000,9.510000,181.346000,13.848000L253.193000,59.342000L195.352000,76.508000L146.354000,45.482000C143.870000,43.909000,140.695000,43.933000,138.234000,45.543000L78.828000,84.429000C70.975000,89.569000,78.833000,102.354000,87.043000,96.980000L142.410000,60.739000L287.089000,152.351000L166.034000,231.588000L21.355000,139.976000L53.936000,118.650000C61.789000,113.510000,53.931000,100.725000,45.721000,106.099000L3.394000,133.805000C1.312000,135.166000,0.001000,137.595000,0.001000,140.080000L0.001000,404.084000C0.001000,406.654000,1.317000,409.045000,3.489000,410.420000L162.090000,510.848000C163.886000,511.984000,166.202000,512.308000,168.236000,511.702000L443.159000,430.113000C452.157000,427.443000,448.298000,412.939000,438.891000,415.733000L173.601000,494.463000L173.601000,246.105000L497,150.129000L497,398.486000L476.521000,404.564000C467.524000,407.234000,471.380000,421.734000,480.789000,418.944000L506.635000,411.274000C509.818000,410.330000,512.001000,407.404000,512.001000,404.084000L512.001000,140.080000C512.001000,140.039000,511.995000,139.998000,511.994000,139.957000ZM343.064000,46.734000L466.335000,124.791000L346.795000,100.858000L223.524000,22.801000ZM304.955000,145.910000L212.176000,87.162000L270.017000,69.996000L339.946000,114.276000C340.723000,114.768000,341.584000,115.113000,342.486000,115.294000L473.368000,141.497000L213.021000,218.760000L305.050000,158.522000C307.184000,157.125000,308.462000,154.740000,308.443000,152.190000C308.424000,149.640000,307.110000,147.274000,304.955000,145.910000ZM15.001000,153.706000L158.602000,244.635000L158.602000,490.885000L15.001000,399.957000Z"/>
                                                                        <path id="emXzJrluOQG3" stroke-width="2" d="M466.654000,293.540000C464.756000,292.124000,462.300000,291.688000,460.031000,292.364000L206.294000,367.870000C203.114000,368.816000,200.933000,371.740000,200.933000,375.058000L200.933000,448.243000C200.933000,450.611000,202.051000,452.840000,203.949000,454.255000C205.260000,455.233000,206.836000,455.743000,208.433000,455.743000C209.149000,455.743000,209.869000,455.640000,210.572000,455.431000L464.309000,379.925000C467.489000,378.979000,469.670000,376.055000,469.670000,372.737000L469.670000,299.552000C469.670000,297.184000,468.552000,294.955000,466.654000,293.540000ZM454.670000,367.144000L215.933000,438.186000L215.933000,380.651000L454.670000,309.609000Z"/>
                                                                        <path id="emXzJrluOQG4" stroke-width="2" d="M291.679000,405.335000C295.821000,405.335000,299.179000,401.977000,299.179000,397.835000L299.179000,376.582000C299.179000,367.197000,284.179000,366.769000,284.179000,376.582000L284.179000,397.835000C284.179000,401.978000,287.537000,405.335000,291.679000,405.335000Z"/>
                                                                        <path id="emXzJrluOQG5" stroke-width="2" d="M391.210000,374.829000C395.352000,374.829000,398.710000,371.471000,398.710000,367.329000L398.710000,346.075000C398.710000,336.690000,383.710000,336.262000,383.710000,346.075000L383.710000,367.329000C383.710000,371.471000,387.068000,374.829000,391.210000,374.829000Z"/>
                                                                        <path id="emXzJrluOQG6" stroke-width="2" d="M413.346000,368.829000C417.488000,368.829000,420.846000,365.471000,420.846000,361.329000L420.846000,340.075000C420.846000,330.690000,405.846000,330.262000,405.846000,340.075000L405.846000,361.329000C405.846000,365.471000,409.204000,368.829000,413.346000,368.829000Z"/>
                                                                        <path id="emXzJrluOQG7" stroke-width="2" d="M435.482000,362.829000C439.624000,362.829000,442.982000,359.471000,442.982000,355.329000L442.982000,334.075000C442.982000,324.690000,427.982000,324.262000,427.982000,334.075000L427.982000,355.329000C427.982000,359.471000,431.340000,362.829000,435.482000,362.829000Z"/>
                                                                        <path id="emXzJrluOQG8" stroke-width="2" d="M235.335000,422.099000C239.477000,422.099000,242.835000,418.741000,242.835000,414.599000L242.835000,393.346000C242.835000,383.961000,227.835000,383.533000,227.835000,393.346000L227.835000,414.599000C227.835000,418.741000,231.193000,422.099000,235.335000,422.099000Z"/>
                                                                        <path id="emXzJrluOQG9" stroke-width="2" d="M257.013000,416.765000C261.155000,416.765000,264.513000,413.407000,264.513000,409.265000L264.513000,388.012000C264.513000,378.627000,249.513000,378.199000,249.513000,388.012000L249.513000,409.265000C249.513000,413.407000,252.871000,416.765000,257.013000,416.765000Z"/>
                                                                        <path id="emXzJrluOQG10" stroke-width="2" d="M330.843000,393.227000C334.985000,393.227000,338.343000,389.869000,338.343000,385.727000L338.343000,364.474000C338.343000,355.089000,323.343000,354.661000,323.343000,364.474000L323.343000,385.727000C323.343000,389.869000,326.701000,393.227000,330.843000,393.227000Z"/>
                                                                        <path id="emXzJrluOQG11" stroke-width="2" d="M352.046000,387.797000C356.188000,387.797000,359.546000,384.439000,359.546000,380.297000L359.546000,359.044000C359.546000,349.659000,344.546000,349.231000,344.546000,359.044000L344.546000,380.297000C344.546000,384.439000,347.904000,387.797000,352.046000,387.797000Z"/>
                                                                    </svg>
                                                                </div>

                                                                <div class="mt-1">
                                                                    <span>{{ __('Withdraw') }}</span>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!--Crypto list payment section end -->
            <!-- Virtual Accounts -->
            <div class="row mt-30 mb-30 flex-column-reverse flex-md-row">
                <div class="col-lg-12 mt-4">
                    <!-- Sub title start -->
                    <div>
                        <h3 class="sub-title">{{ __('Virtual Accounts') }}</h3>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="bg-secondary mt-3 shadow">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th class="pl-5" scope="col">Currency</th>
                                                <th scope="col">Available Balance</th>
                                                <th class="" scope="col">Virtual Account ID</th>
                                                <th class="" scope="col">Deposit Address</th>
                                                <th class="" scope="col">Wallet Primary Key</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(!empty($virtualAccounts)){ ?>
                                                @foreach($virtualAccounts as $key=>$acc)
                                                    <tr>
                                                        <td class="pl-5" scope="col">{{ $acc->currency }}</td>
                                                        <td scope="col">{{ $acc->available_balance }}</td>
                                                        <td scope="col">{{ $acc->virtualacc_id }}</td>
                                                        <td scope="col">{{ $acc->deposit_address }}</td>
                                                        <td scope="col">{{ $acc->xpub }}</td>
                                                    </tr>                                              

                                                    @endforeach
                                                <?php }else{ ?>
                                                <tr>
                                                    <td colspan="6" class="text-center p-4">
                                                        <img src="{{ theme_asset('public/images/banner/notfound.svg') }}" alt="notfound">
                                                        <p class="mt-4">{{ __('Sorry! No Record Found') }}</p>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Virtual Accounts -->
        </div>
    </div>
</section>
@endsection
