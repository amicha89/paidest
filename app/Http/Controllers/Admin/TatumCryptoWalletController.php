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
}
