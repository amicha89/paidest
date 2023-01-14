<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\TatumCryptoWalletDataTable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\TatumCryptoWallet;
use Illuminate\Http\Request;
use App\Http\Helpers\Common;
use App\Models\User;
use DB,Config,Session;

class TatumCryptoWalletController extends Controller
{
    protected $helper;
    protected $user;
    public function __construct()
    {
        $this->helper = new Common();
    }
    
    
    public function index(TatumCryptoWalletDataTable $dataTable)
    {
        //$data['menu']     = 'crypto-wallets';
        return $dataTable->render('admin.tatumWallets.index');
        //return view('admin.tatumWallets.index', $data);
    }
    public function create()
    {
        //$data['menu']     = 'crypto-wallets';
        return view('admin.tatumWallets.create');
    }
    public  function bscWallet(Request $request)
    {
        $xApiKey = $request->xApiKey;
        $walletDetails = $request->wallet_number;
        $bscWallet = 'https://api.tatum.io/v3/bsc/wallet';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $xApiKey
        ])->get($bscWallet);
        if($response->status() === 200){
            $responseData = $response->collect();
            $bsc_xpub = $responseData['xpub'];
            $bsc_menmonic = $responseData['mnemonic'];
            $lastInsertedId = DB::table('tatum_bsc_wallet')
                ->insertGetId([
                        'xpub' => $bsc_xpub,
                        'mnemonic' => $bsc_menmonic
                ]);
            // generate BSC account address of wallet / public address for users to receive funds /   'wallet_details' => $walletDetails
            $xpub = $bsc_xpub;
            $index_for_public = $lastInsertedId;
            $bscPublicKeyURL = 'https://api.tatum.io/v3/bsc/address/'.$xpub.'/'.$index_for_public;
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $xApiKey
            ])->get($bscPublicKeyURL);
            
            if($response->status() !== 200 ){
                $publicKeyErrorCode = $response->status();
                $this->helper->one_time_message('danger', "Public Key Error $publicKeyErrorCode");
                return redirect(Config::get('adminPrefix').'/crypto-wallets/create');
            }
            // get public key of wallet
            $bscAccountAddress = $response->collect();
            $public_key = $bscAccountAddress['address'];
            DB::table('tatum_bsc_wallet')
            ->updateOrInsert(
                ['id' => $lastInsertedId],
                [
                    'public_key' => $public_key,
                ]
            );
            // private key for wallet
            $index_for_privateKey =  $lastInsertedId;
            $mnemonic = $bsc_menmonic;
            $requestArray = [
                'index' => $index_for_privateKey,
                'mnemonic' => $mnemonic
            ];
            
            $bscPrivateKeyURL = "https://api.tatum.io/v3/bsc/wallet/priv";
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $xApiKey
            ])->post($bscPrivateKeyURL, $requestArray);

            if($response->status() !== 200 ){
                $privateKeyErrorCode = $response->status();
                $this->helper->one_time_message('danger', "Private Key Error $privateKeyErrorCode");
                return redirect(Config::get('adminPrefix').'/crypto-wallets/create');
            }

            $bscAccountPrivateKey = $response->collect();
            $private_key = $bscAccountPrivateKey['key'];
            DB::table('tatum_bsc_wallet')
            ->updateOrInsert(
                ['id' => $lastInsertedId],
                [
                    'private_key' => $private_key,
                ]
            );
            $this->helper->one_time_message('success', 'BSC Wallet has been created successfully');
            return redirect(Config::get('adminPrefix').'/crypto-wallets');
        }else{
            return $responseCode = $response->status();
        }
        
    }
    //bsc virtual account
    public function bscvirtualAccounts($id)
    {
        $allWallets = DB::table('tatum_bsc_wallet')->select('xpub','public_key')->whereNotNull('public_key')->get();
        $user_id = $id;
        return view('admin.tatumWallets.bscVirtualAcount', compact('user_id','allWallets'));
    }
    public function createBscvirtualAc(Request $request)
    {
        //dd($request->all());
        $currency_type = $request->currency_type;
        $wallet_xpub = $request->wallet_xpub;
        $bsc_xpub = DB::table('tatum_bsc_wallet')->select('public_key')->where('xpub', $wallet_xpub)->first(); //xpub is as a public key
        //dd($bsc_xpub->public_key);
        // Tatum.io testnet key
        $xApiKey = "d56e6ccf-f6eb-4243-8241-d472e49a2316";
        // Tatum.io main net key
        $xApiKeyMainNet = "03e1d1b9-b67a-4bf1-8fb7-f7a76cce1672";
        $virtualAcc_url = "https://api.tatum.io/v3/ledger/account";
        $req_array = [
            "currency" => $currency_type,
            "xpub" => $wallet_xpub
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $xApiKeyMainNet
        ])->post($virtualAcc_url, $req_array);
        
        if($response->status() !== 200 ){
            $virtualAcc_ErrorCode = $response->status();
            $this->helper->one_time_message('danger', "Virtual Account Error $virtualAcc_ErrorCode");
            return redirect(Config::get('adminPrefix').'/virtual-accounts');
        }
        $virtualAcc_respone = $response->collect();
        //dd($virtualAcc_respone);
        $currency = $virtualAcc_respone["currency"]; 
        $active = $virtualAcc_respone["active"]; 
        $accountBalance = $virtualAcc_respone["balance"]["accountBalance"]; 
        $availableBalance = $virtualAcc_respone["balance"]["availableBalance"]; 
        $frozen = $virtualAcc_respone["frozen"]; 
        $xpub = $virtualAcc_respone["xpub"]; 
        $accountingCurrency = $virtualAcc_respone["accountingCurrency"]; 
        $virtualAcc_id = $virtualAcc_respone["id"];
        $logged_userID = $request->user_id;
        $lastInsertID  = DB::table('virtual_account')->insertGetId([
            'user_id' => $logged_userID,
            'currency' => $currency,
            'active' =>  $active,
            'account_balance' => $accountBalance,
            'available_balance' => $availableBalance,
            'frozen' => $frozen,
            'xpub' => $bsc_xpub->public_key,
            'accounting_currency' => $accountingCurrency,
            'virtualacc_id' => $virtualAcc_id,
        ]);
        // Create a deposit address for a virtual account 
        
        $accountID = DB::table('virtual_account')->select('virtualacc_id')->where('id',$lastInsertID)->first();
        $accountID = $accountID->virtualacc_id;
        $accountIndex = $lastInsertID;
        $deposit_address_url = "https://api.tatum.io/v3/offchain/account/".$accountID."/address?index=".$accountIndex;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $xApiKeyMainNet
        ])->post($deposit_address_url);
        
        if($response->status() !== 200 ){
            $virtualAcc_ErrorCode = $response->status();
            $this->helper->one_time_message('danger', "Depost Address Error $virtualAcc_ErrorCode");
            return redirect(Config::get('adminPrefix').'/virtual-accounts');
        }
        $depositAddress_respone = $response->collect();
        //dd( $depositAddress_respone);
        $address = $depositAddress_respone['address'];
        $derivationKey = $depositAddress_respone['derivationKey'];
        // $currency = $depositAddress_respone['currency'];
        // $destinationTag = $depositAddress_respone['destinationTag'];
        // $memo = $depositAddress_respone['memo'];
        // $message = $depositAddress_respone['message'];

        DB::table('virtual_account')
        ->updateOrInsert(
            ['id' => $lastInsertID],
            [
                'deposit_address' => $address,
                'derivationKey' => $derivationKey
            ]
        );

        $this->helper->one_time_message('success', "Virtual Account has been created successfully");
        return redirect(Config::get('adminPrefix')."/users/wallets/$logged_userID");
        // $errorMsg = $this->helper->one_time_message('danger', 'Virtual Account Error: 401');
        // return back()->with($errorMsg);
    }
    // each user account lists
    public function virtualAccountList()
    {
        //return view('');   
    }
}
