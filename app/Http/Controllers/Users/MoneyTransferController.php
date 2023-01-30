<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Helpers\Common;
use Validator, Auth, DB, Config;
use App\Models\{User,
    Transaction,
    FeesLimit,
    Transfer,
    Wallet
};

class MoneyTransferController extends Controller
{
    protected $helper;
    protected $transfer;

    public function __construct()
    {
        $this->helper   = new Common();
        $this->transfer = new Transfer();
    }

    //Send Money - Email/Phone validation
    public function transferUserEmailPhoneReceiverStatusValidate(Request $request)
    {
        $phoneRegex = $this->helper->validatePhoneInput(trim($request->receiver));
        if ($phoneRegex)
        {
            //Check phone number exists or not
            $user = User::where(['id' => auth()->user()->id])->first(['formattedPhone']);
            if (empty($user->formattedPhone))
            {
                return response()->json([
                    'status'  => 404,
                    'message' => __("Please set your phone number first!"),
                ]);
            }

            //Check own phone number
            if ($request->receiver == auth()->user()->formattedPhone)
            {
                return response()->json([
                    'status'  => true,
                    'message' => __("You Cannot Send Money To Yourself!"),
                ]);
            }

            //Check Receiver/Recipient is suspended/inactive - if entered phone number
            $receiver = User::where(['formattedPhone' => $request->receiver])->first(['status']);
            if (!empty($receiver))
            {
                if ($receiver->status == 'Suspended')
                {
                    return response()->json([
                        'status'  => true,
                        'message' => __("The recipient is suspended!"),
                    ]);
                }
                elseif ($receiver->status == 'Inactive')
                {
                    return response()->json([
                        'status'  => true,
                        'message' => __("The recipient is inactive!"),
                    ]);
                }
            }
        }
        else
        {
            //Check own phone email
            if ($request->receiver == auth()->user()->email)
            {
                return response()->json([
                    'status'  => true,
                    'message' => __("You Cannot Send Money To Yourself!"),
                ]);
            }

            //Check Receiver/Recipient is suspended/inactive - if entered email
            $receiver = User::where(['email' => trim($request->receiver)])->first(['status']);
            if (!empty($receiver))
            {
                if ($receiver->status == 'Suspended')
                {
                    return response()->json([
                        'status'  => true,
                        'message' => __("The recipient is suspended!"),
                    ]);
                }
                elseif ($receiver->status == 'Inactive')
                {
                    return response()->json([
                        'status'  => true,
                        'message' => __("The recipient is inactive!"),
                    ]);
                }
            }
        }
    }

    public function create(Request $request)
    {
        //set the session for validating the action
        setActionSession();

        $data['menu']    = 'send_receive';
        $data['submenu'] = 'send';
        //return $request->all();
        if ($request->isMethod('post')) {

            $rules = array(
                'amount'   => 'required|numeric',
                'receiver' => 'required',
                'note'     => 'required',
            );

            $fieldNames = array(
                'amount'   => __("Amount"),
                'receiver' => __("Recipient"),
                'note'     => __("Note"),
            );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if ($request->sendMoneyProcessedBy == 'email') {
                $rules['receiver'] = 'required|email';
            }

            
            
            // elseif ($request->sendMoneyProcessedBy == 'phone') {
            //     $myStr = explode('+', $request->receiver);
            //     if ($request->receiver[0] != "+" || !is_numeric($myStr[1])) {
            //         return back()->withErrors(__("Please enter valid phone (ex: +12015550123)"))->withInput();
            //     }
            // }
            // elseif ($request->sendMoneyProcessedBy == 'email_or_phone') {
            //     $myStr = explode('+', $request->receiver);
            //     if ($request->receiver[0] != "+" || !is_numeric($myStr[1])) {
            //         $rules['receiver'] = 'required|email';
            //         $messages          = [
            //             'email' => __("Please enter valid email (ex: user@gmail.com) or phone (ex: +12015550123)"),
            //         ];
            //     }
            // }
           
            //Own Email or phone validation + Receiver/Recipient is suspended/Inactive validation
            $transferUserEmailPhoneReceiverStatusValidate = $this->transferUserEmailPhoneReceiverStatusValidate($request);
            if ($transferUserEmailPhoneReceiverStatusValidate) {
                if ($transferUserEmailPhoneReceiverStatusValidate->getData()->status == true || $transferUserEmailPhoneReceiverStatusValidate->getData()->status == 404) {
                    return back()->withErrors(__($transferUserEmailPhoneReceiverStatusValidate->getData()->message))->withInput();
                }
            }
            //ikFcode: Tatum.io money transfer module
            //return $request->all();
            $receiverEmail = $request->receiver;
            $sendingAmmount = $request->amount;
            $receiverUserID = User::select('id')->Where('email', $receiverEmail)->first();
            $getReceiverData = DB::table('virtual_account')->select('virtualacc_id')->where([
                                            ['id','=','23'],
                                            ['user_id','=',$receiverUserID->id],
                                        ])->first();
            $receiverAccountID =  $getReceiverData->virtualacc_id; //receiver Virtual account ID
            //dd($receiverAccountID);
            $senderUserID = auth()->user()->id;
            $getSenderData = DB::table('virtual_account')->select('virtualacc_id')->where([
                ['id','=','22'],
                ['user_id','=',  $senderUserID],
            ])->first();
            $senderAccountID = $getSenderData->virtualacc_id;
            //dd($senderAccountID);    
            // Tatum.io send money request
            $xApiKeyTestnet = "d56e6ccf-f6eb-4243-8241-d472e49a2316"; //Tatum testnet key
            $sendMoneyURL = "https://api.tatum.io/v3/ledger/transaction";
            $req_array = [
                "senderAccountId" =>  $senderAccountID,
                "recipientAccountId" => $receiverAccountID,
                "amount" => $sendingAmmount
            ];
    
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' =>  $xApiKeyTestnet
            ])->post($sendMoneyURL, $req_array);
            
            if($response->status() !== 200 ){
                $virtualAcc_ErrorCode = $response->status();
                $this->helper->one_time_message('danger', "Send Money Error: $virtualAcc_ErrorCode");
                return redirect(Config::get('adminPrefix').'/transfer');
            }
            if($response->status() == 200 ){
                $sendMoneyResponse = $response->collect();
                $reference_id = $sendMoneyResponse["reference"];
                DB::table('tatum_transctions')->insert([
                    'reference_id' => $reference_id,
                    'amount_sent' => $sendingAmmount
                ]);
                $this->helper->one_time_message('success', "Money Sent Successfullty.");
                return redirect(Config::get('adminPrefix').'/moneytransfer');
            }

            //ikFcode end
            //Amount Limit Check validation
            // $request['wallet_id']           = $request->wallet;
            // $request['transaction_type_id'] = Transferred;
            // $amountLimitCheck               = $this->amountLimitCheck($request);
            // if ($amountLimitCheck->getData()->success->status == 200) {
            //     if ($amountLimitCheck->getData()->success->totalAmount > $amountLimitCheck->getData()->success->balance) {
            //         return back()->withErrors(__("Not have enough balance."))->withInput();
            //     }
            // } else {
            //     return back()->withErrors(__($amountLimitCheck->getData()->success->message))->withInput();
            // }
            //backend validation ends
            
            // $totalFee = $amountLimitCheck->getData()->success->totalFees;
        
            // $wallet = Wallet::with(['currency:id,symbol'])->where(['id' => $request->wallet, 'user_id' => auth()->user()->id])->first(['currency_id', 'balance']);
            // $request['currency_id'] = $wallet->currency->id;
            // $request['currSymbol'] = $wallet->currency->symbol;
            // $request['totalAmount'] = $request->amount + $totalFee;
            // $request['fee'] = $totalFee;
            // $request['sendMoneyProcessedBy'] = $request->sendMoneyProcessedBy;
            
            // session(['transInfo' => $request->all()]);
            // $data['transInfo'] = $request->all();

            // return view('user_dashboard.moneytransfer.confirmation', $data);
        }

        // if(!g_c_v() && u_sm_c_v()) {
        //     Session::flush();
        //     return view('vendor.installer.errors.admin');
        // }
       
        /*Check Whether Currency is Activated in feesLimit*/
        $data['walletList'] = Wallet::where(['user_id' => auth()->user()->id])
            ->whereHas('active_currency', function ($q)
            {
                $q->whereHas('fees_limit', function ($query)
                {
                    $query->where('transaction_type_id', Transferred)->where('has_transaction', 'Yes')->select('currency_id', 'has_transaction');
                });
            })
            ->with(['active_currency:id,code,type', 'active_currency.fees_limit:id,currency_id'])
            ->get(['id', 'currency_id', 'is_default']);
        return view('user_dashboard.moneytransfer.create', $data);
    }

    //Send Money - Amount Limit Check
    public function amountLimitCheck(Request $request)
    {
        $amount      = $request->amount;
        $wallet_id   = $request->wallet_id;
        $user_id     = Auth::user()->id;
        $wallet      = Wallet::where(['id' => $wallet_id, 'user_id' => $user_id])->first(['currency_id', 'balance']);
        $currency_id = $wallet->currency_id;
        $feesDetails = FeesLimit::where(['transaction_type_id' => $request->transaction_type_id, 'currency_id' => $currency_id])->first(['max_limit', 'min_limit', 'charge_percentage', 'charge_fixed']);

        //Code for Amount Limit starts here
        if (@$feesDetails->max_limit == null)
        {
            if ((@$amount < @$feesDetails->min_limit))
            {
                $success['message'] = __('Minimum amount ') . formatNumber($feesDetails->min_limit, $currency_id);
                $success['status']  = '401';
            }
            else
            {
                $success['status'] = 200;
            }
        }
        else
        {
            if ((@$amount < @$feesDetails->min_limit) || (@$amount > @$feesDetails->max_limit))
            {
                $success['message'] = __('Minimum amount ') . formatNumber($feesDetails->min_limit, $currency_id) . __(' and Maximum amount ') . formatNumber($feesDetails->max_limit, $currency_id);
                $success['status']  = '401';
            }
            else
            {
                $success['status'] = 200;
            }
        }
        //Code for Amount Limit ends here

        //Code for Fees Limit Starts here
        if (empty($feesDetails))
        {
            $feesPercentage            = 0;
            $feesFixed                 = 0;
            $totalFees                 = $feesPercentage + $feesFixed;
            $totalAmount               = $amount + $totalFees;
            $success['feesPercentage'] = $feesPercentage;
            $success['feesFixed']      = $feesFixed;
            $success['totalFees']      = $totalFees;
            $success['totalFeesHtml']  = formatNumber($totalFees, $currency_id);
            $success['totalAmount']    = $totalAmount;
            $success['pFees']          = $feesPercentage;
            $success['fFees']          = $feesFixed;
            $success['pFeesHtml']      = formatNumber($feesPercentage, $currency_id);
            $success['fFeesHtml']      = formatNumber($feesFixed, $currency_id);
            $success['min']            = 0;
            $success['max']            = 0;
            $success['balance']        = $wallet->balance;
        }
        else
        {
            $feesPercentage            = $amount * ($feesDetails->charge_percentage / 100);
            $feesFixed                 = $feesDetails->charge_fixed;
            $totalFees                 = $feesPercentage + $feesFixed;
            $totalAmount               = $amount + $totalFees;
            $success['feesPercentage'] = $feesPercentage;
            $success['feesFixed']      = $feesFixed;
            $success['totalFees']      = $totalFees;
            $success['totalFeesHtml']  = formatNumber($totalFees, $currency_id);
            $success['totalAmount']    = $totalAmount;
            $success['pFees']          = $feesDetails->charge_percentage;
            $success['fFees']          = $feesDetails->charge_fixed;
            $success['pFeesHtml']      = formatNumber($feesDetails->charge_percentage, $currency_id);
            $success['fFeesHtml']      = formatNumber($feesDetails->charge_fixed, $currency_id);
            $success['min']            = $feesDetails->min_limit;
            $success['max']            = $feesDetails->max_limit;
            $success['balance']        = $wallet->balance;
        }
        //Code for Fees Limit Ends here
        return response()->json(['success' => $success]);
    }

    //Send Money - Confirm
    public function sendMoneyConfirm(Request $request)
    {
        $data['menu']    = 'send_receive';
        $data['submenu'] = 'send';

        $sessionValue = session('transInfo');
        if (empty($sessionValue))
        {
            return redirect('moneytransfer');
        }

        //initializing session
        actionSessionCheck();

        //Data - Wallet Balance Again Amount Check
        $total_with_fee          = $sessionValue['amount'] + $sessionValue['fee'];
        $currency_id             = session('transInfo')['currency_id'];
        $user_id                 = auth()->user()->id;
        $feesDetails             = $this->helper->getFeesLimitObject([], Transferred, $sessionValue['currency_id'], null, null, ['charge_percentage', 'charge_fixed']);
        $senderWallet            = $this->helper->getUserWallet([], ['user_id' => $user_id, 'currency_id' => $currency_id], ['id', 'balance']);
        $p_calc                  = $sessionValue['amount'] * (@$feesDetails->charge_percentage / 100);
        $processedBy             = $sessionValue['sendMoneyProcessedBy'];
        $request_wallet_currency = $sessionValue['currency_id'];
        $unique_code             = unique_code();
        $emailFilterValidate     = $this->helper->validateEmailInput(trim($sessionValue['receiver']));
        $phoneRegex              = $this->helper->validatePhoneInput(trim($sessionValue['receiver']));
        $userInfo                = $this->helper->getEmailPhoneValidatedUserInfo($emailFilterValidate, $phoneRegex, trim($sessionValue['receiver']));
        $arr                     = [
            'emailFilterValidate' => $emailFilterValidate,
            'phoneRegex'          => $phoneRegex,
            'processedBy'         => $processedBy,
            'user_id'             => $user_id,
            'userInfo'            => $userInfo,
            'currency_id'         => $request_wallet_currency,
            'uuid'                => $unique_code,
            'fee'                 => $sessionValue['fee'],
            'amount'              => $sessionValue['amount'],
            'note'                => trim($sessionValue['note']),
            'receiver'            => trim($sessionValue['receiver']),
            'charge_percentage'   => $feesDetails->charge_percentage,
            'charge_fixed'        => $feesDetails->charge_fixed,
            'p_calc'              => $p_calc,
            'total'               => $total_with_fee,
            'senderWallet'        => $senderWallet,
        ];
        $data['transInfo']['receiver']   = $sessionValue['receiver'];
        $data['transInfo']['currSymbol'] = $sessionValue['currSymbol'];
        $data['transInfo']['amount']     = $sessionValue['amount'];
        $data['transInfo']['currency_id']     = $sessionValue['currency_id'];
        $data['userPic']                 = isset($userInfo) ? $userInfo->picture : '';
        $data['receiverName']            = isset($userInfo) ? $userInfo->first_name . ' ' . $userInfo->last_name : '';

        //Get response
        $response = $this->transfer->processSendMoneyConfirmation($arr, 'web');
        if ($response['status'] != 200)
        {
            if (empty($response['transactionOrTransferId']))
            {
                session()->forget('transInfo');
                $this->helper->one_time_message('error', $response['ex']['message']);
                return redirect('moneytransfer');
            }
            $data['errorMessage'] = $response['ex']['message'];
        }
        $data['transInfo']['trans_id'] = $response['transactionOrTransferId'];

        //clearing session
        session()->forget('transInfo');
        clearActionSession();
        return view('user_dashboard.moneytransfer.success', $data);
    }

    //Send Money - Generate pdf for print
    public function transferPrintPdf($trans_id)
    {
        $data['transactionDetails'] = Transaction::with(['end_user:id,first_name,last_name', 'currency:id,symbol,code'])
            ->where(['id' => $trans_id])
            ->first(['transaction_type_id', 'end_user_id', 'currency_id', 'uuid', 'created_at', 'status', 'subtotal', 'charge_percentage', 'charge_fixed', 'total', 'note']);

        $mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/tmp']);
        $mpdf = new \Mpdf\Mpdf([
            'mode'                 => 'utf-8',
            'format'               => 'A3',
            'orientation'          => 'P',
            'shrink_tables_to_fit' => 0,
        ]);
        $mpdf->autoScriptToLang         = true;
        $mpdf->autoLangToFont           = true;
        $mpdf->allow_charset_conversion = false;
        $mpdf->SetJS('this.print();');
        $mpdf->WriteHTML(view('user_dashboard.moneytransfer.transferPaymentPdf', $data));
        $mpdf->Output('sendMoney_' . time() . '.pdf', 'I'); // this will output data
    }
}
