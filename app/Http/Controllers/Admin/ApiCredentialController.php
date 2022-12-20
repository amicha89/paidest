<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\Common;
use App\Models\ApiCredential;
use Validator;

class ApiCredentialController extends Controller
{
    
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings_menu = 'api-credentials';
        $apiData = ApiCredential::where('name', 'api_credential')->first();
        return view ('admin.apiCredential.index')->with(['apiData'=> $apiData, 'settings_menu' => $settings_menu]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'application_id'            => 'required',
            'api_key'                   => 'required',
            'ui_key'                    => 'required',
            'corporates_id'             => 'required',
            'managed_cards_debit'       => 'required',
            'managed_cards_prepaid'     => 'required',
            'transfer_id'               => 'required',
            'send_id'                   => 'required',
            'outgoing_wire_transfer'    => 'required',
        );

        $fieldNames = array(
            'application_id'            => 'Application ID',
            'api_key'                   => 'API Key',
            'ui_key'                    => 'UI Key',
            'corporates_id'             => 'Corporates ID',
            'managed_cards_debit'       => 'Managed Cards Debit',
            'managed_cards_prepaid'     => 'Managed Cards Prepaid',
            'transfer_id'               => 'Transfer ID',
            'send_id'                   => 'Send ID',
            'outgoing_wire_transfer'    => 'Outgoing Wire Transfer',
        );
        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($fieldNames);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        else{
            
            $data = $request->All();
            $apiData = ApiCredential::updateOrCreate(    
                ['name' => 'api_credential'],
                ['value' => $data],
                ['type' => 'general']
            );
            $this->helper->one_time_message('success', 'API Credentials Saved Successfully');
            $apiData['settings_menu'] = 'api-credentials';
            return view('admin.apiCredential.index')->with("apiData", $apiData)->with("settings_menu", $apiData['settings_menu']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return "destroy";
    }
}
