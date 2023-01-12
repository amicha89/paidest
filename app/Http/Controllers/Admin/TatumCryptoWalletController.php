<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\TatumCryptoWalletDataTable;
use Illuminate\Support\Facades\Http;
use App\Models\TatumCryptoWallet;
use Illuminate\Http\Request;
use App\Http\Helpers\Common;
use DB,Config;

class TatumCryptoWalletController extends Controller
{
    protected $helper;
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
    public function bscvirtualAccounts()
    {
        return view('admin.tatumWallets.bscVirtualAcount');
    }
    public function createBscvirtualAc(Request $request)
    {
        $currency_type = $request->currency_type;
        $bsc_xpub = DB::table('tatum_bsc_wallet')->select('xpub')->first();
        $bsc_xpub = $bsc_xpub->xpub;
        //dd( $bsc_xpub->xpub);
        $xApiKey = "d56e6ccf-f6eb-4243-8241-d472e49a2316";
        $virtualAcc_url = "https://api.tatum.io/v3/ledger/account";
        $req_array = [
            "currency" => $currency_type,
            "xpub" => $bsc_xpub
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $xApiKey
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
        $lastInsertID  = DB::table('virtual_account')->insertGetId([
            'currency' => $currency,
            'active' =>  $active,
            'account_balance' => $accountBalance,
            'available_balance' => $availableBalance,
            'frozen' => $frozen,
            'xpub' => $xpub,
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
        return redirect(Config::get('adminPrefix').'/virtual-accounts');
        // $errorMsg = $this->helper->one_time_message('danger', 'Virtual Account Error: 401');
        // return back()->with($errorMsg);
    }
}
