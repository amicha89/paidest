<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\TatumCryptoWalletDataTable;
use App\DataTables\Admin\VirtualAccountsDataTable;
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
        $blockchainType = $request->blockchain;
        // testnet key
        //$xApiKey = "d56e6ccf-f6eb-4243-8241-d472e49a2316";
        $xApiKey = config("tatumapi.tatumApiKey");
        // BSC wallet
        if ($blockchainType === "BSC")
        {
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
                        'blockchain_type' => $blockchainType,
                        'xpub' => $bsc_xpub,
                        'mnemonic' => $bsc_menmonic
                    ]);
                // generate BSC account address of wallet / public address for users to receive funds
                $xpub = $bsc_xpub;
                $index_for_public = $lastInsertedId;
                $bscPublicKeyURL = 'https://api.tatum.io/v3/bsc/address/'.$xpub.'/'.$index_for_public;
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key' => $xApiKey
                ])->get($bscPublicKeyURL);
                
                if($response->status() !== 200 ){
                    $publicKeyErrorCode = $response->status();
                    $this->helper->one_time_message('danger', "BSC Public Key Error $publicKeyErrorCode");
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
                    $this->helper->one_time_message('danger', "BSC Private Key Error $privateKeyErrorCode");
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
                $this->helper->one_time_message('success', 'BSC Wallet has been created Successfully');
                return redirect(Config::get('adminPrefix').'/crypto-wallets');
            
            }else{
                return $responseCode = $response->status();
            }
        }elseif($blockchainType === "TRON")
        {
            $tronURL = "https://api.tatum.io/v3/tron/wallet";
            $tronResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $xApiKey
            ])->get($tronURL);
            if($tronResponse->status() !== 200 ){
                $tronErrorCode = $tronResponse->status();
                $this->helper->one_time_message('danger', "Tron Request Error: $tronErrorCode");
                return redirect(Config::get('adminPrefix').'/crypto-wallets/create');
            }elseif($tronResponse->status() == 200){
                $tronWalletResponse = $tronResponse->collect();
                $tronXpub = $tronWalletResponse["xpub"];
                $tronMnemonic = $tronWalletResponse["mnemonic"];
                $lastInsertedId = DB::table('tatum_bsc_wallet')
                    ->insertGetId([
                        'blockchain_type' => $blockchainType,
                        'xpub' => $tronXpub,
                        'mnemonic' => $tronMnemonic
                    ]);
                // generate TRON Wallet public Address / public address for users to receive funds
                $index_for_public = $lastInsertedId;
                $tronPublicKeyURL = "https://api.tatum.io/v3/tron/address/" . $tronXpub . "/" . $index_for_public;
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key' => $xApiKey
                ])->get($tronPublicKeyURL);
                
                if($response->status() !== 200 ){
                    $publicKeyErrorCode = $response->status();
                    $this->helper->one_time_message('danger', "Tron Public Key Error $publicKeyErrorCode");
                    return redirect(Config::get('adminPrefix').'/crypto-wallets/create');
                }
                // get public key of TRON wallet
                $tronPublicAddress = $response->collect();
                $public_key = $tronPublicAddress['address'];
                DB::table('tatum_bsc_wallet')
                ->updateOrInsert(
                    ['id' => $lastInsertedId],
                    [
                        'public_key' => $public_key,
                    ]
                );
                // private key for TRON wallet
                $requestArray = [
                    'index' => $lastInsertedId,
                    'mnemonic' => $tronMnemonic
                ];
                
                $tronPrivateKeyURL = "https://api.tatum.io/v3/tron/wallet/priv";
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key' => $xApiKey
                ])->post($tronPrivateKeyURL, $requestArray);
    
                if($response->status() !== 201 ){
                    $privateKeyErrorCode = $response->status();
                    $this->helper->one_time_message('danger', "TRON Private Key Error $privateKeyErrorCode");
                    return redirect(Config::get('adminPrefix').'/crypto-wallets/create');
                }
    
                $tronAccountPrivateKey = $response->collect();
                $private_key = $tronAccountPrivateKey['key'];
                DB::table('tatum_bsc_wallet')
                ->updateOrInsert(
                    ['id' => $lastInsertedId],
                    [
                        'private_key' => $private_key,
                    ]
                );
                $this->helper->one_time_message('success', 'TRON Wallet has been created Successfully');
                return redirect(Config::get('adminPrefix').'/crypto-wallets');
            }
        }else{
            // ETH blockchain Wallet
            $ethURL = "https://api.tatum.io/v3/ethereum/wallet";
            $ethResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $xApiKey
            ])->get($ethURL);
            if($ethResponse->status() !== 200 ){
                $ethErrorCode = $ethResponse->status();
                $this->helper->one_time_message('danger', "Ethereum Request Error: $ethErrorCode");
                return redirect(Config::get('adminPrefix').'/crypto-wallets/create');
            }elseif($ethResponse->status() == 200){
                $ethWalletResponse = $ethResponse->collect();
                $ethXpub = $ethWalletResponse["xpub"];
                $ethMnemonic = $ethWalletResponse["mnemonic"];
                $lastInsertedId = DB::table('tatum_bsc_wallet')
                    ->insertGetId([
                        'blockchain_type' => $blockchainType,
                        'xpub' => $ethXpub,
                        'mnemonic' => $ethMnemonic
                    ]);
                // generate eth Wallet public Address / public address for users to receive funds
                $index_for_public = $lastInsertedId;
                $ethPublicKeyURL = "https://api.tatum.io/v3/ethereum/address/" . $ethXpub . "/" . $index_for_public;
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key' => $xApiKey
                ])->get($ethPublicKeyURL);
                
                if($response->status() !== 200 ){
                    $publicKeyErrorCode = $response->status();
                    $this->helper->one_time_message('danger', "Ethereum Public Key Error $publicKeyErrorCode");
                    return redirect(Config::get('adminPrefix').'/crypto-wallets/create');
                }
                // get public key of eth wallet
                $ethPublicAddress = $response->collect();
                $public_key = $ethPublicAddress['address'];
                DB::table('tatum_bsc_wallet')
                ->updateOrInsert(
                    ['id' => $lastInsertedId],
                    [
                        'public_key' => $public_key,
                    ]
                );
                // private key for eth wallet
                $requestArray = [
                    'index' => $lastInsertedId,
                    'mnemonic' => $ethMnemonic
                ];
                
                $ethPrivateKeyURL = "https://api.tatum.io/v3/ethereum/wallet/priv";
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key' => $xApiKey
                ])->post($ethPrivateKeyURL, $requestArray);
    
                if($response->status() !== 200 ){
                    $privateKeyErrorCode = $response->status();
                    $this->helper->one_time_message('danger', "Ethereum Private Key Error $privateKeyErrorCode");
                    return redirect(Config::get('adminPrefix').'/crypto-wallets/create');
                }
    
                $ethAccountPrivateKey = $response->collect();
                $private_key = $ethAccountPrivateKey['key'];
                DB::table('tatum_bsc_wallet')
                ->updateOrInsert(
                    ['id' => $lastInsertedId],
                    [
                        'private_key' => $private_key,
                    ]
                );
                $this->helper->one_time_message('success', 'Ethereum Wallet has been created Successfully');
                return redirect(Config::get('adminPrefix').'/crypto-wallets');
            }
        }
    }

    //bsc virtual account
    public function bscvirtualAccounts($id)
    {
        $allWallets = DB::table('tatum_bsc_wallet')->select('xpub','public_key')->whereNotNull('public_key')->get();
        $user_id = $id;
        return view('admin.tatumWallets.bscVirtualAcount', compact('user_id','allWallets'));
    }

    // get all wallets based on selected blockchain
    public function getWallets(Request $request)
    {
        $blockchain_Type = $request->blockchain_type;
        $wallets = DB::table('tatum_bsc_wallet')->select('xpub','public_key')->where('blockchain_type', $blockchain_Type)->get();
        return response()->json($wallets);
    }

    public function createBscvirtualAc(Request $request)
    {
        //dd($request->all());
        // Tatum.io key
        $xApiKey = config("tatumapi.tatumApiKey");
        // if($request->blockchain_type === "BSC")
        // {
            $currency_type = $request->currency_type;
            $wallet_xpub = $request->wallet_xpub;
            $bsc_xpub = DB::table('tatum_bsc_wallet')->select('public_key')->where('xpub', $wallet_xpub)->first(); //xpub is as a public key
            //dd($bsc_xpub->public_key);
            $virtualAcc_url = "https://api.tatum.io/v3/ledger/account";
            $req_array = [
                "currency" => $currency_type,
                "xpub" => $wallet_xpub
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $xApiKey
            ])->post($virtualAcc_url, $req_array);
            
            if($response->status() !== 200 ){
                $virtualAcc_ErrorCode = $response->status();
                $this->helper->one_time_message('danger', "BSC Virtual Account Request Error $virtualAcc_ErrorCode");
                return redirect(Config::get('adminPrefix').'/virtual-accounts');
            }
            $virtualAcc_respone = $response->collect();
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
                'x-api-key' => $xApiKey
            ])->post($deposit_address_url);
            
            if($response->status() !== 200 ){
                $virtualAcc_ErrorCode = $response->status();
                $this->helper->one_time_message('danger', "BSC Deposit Address Error $virtualAcc_ErrorCode");
                return redirect(Config::get('adminPrefix').'/virtual-accounts');
            }
            $depositAddress_respone = $response->collect();
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

            $this->helper->one_time_message('success', "BSC Virtual Account has been created successfully");
            return redirect(Config::get('adminPrefix')."/users/wallets/$logged_userID");

        // }elseif($request->blockchain_type === "ETH")
        // {
        //     return "ETH";
        // }else{
        //     return "TRON";
        // }
        
        // $errorMsg = $this->helper->one_time_message('danger', 'Virtual Account Error: 401');
        // return back()->with($errorMsg);
    }

    // all virtual Accounts list
    public function allVirtualAccounts(VirtualAccountsDataTable $dataTable)
    {
        return $dataTable->render('admin.tatumWallets.virtualAccountslist');
    }
}
