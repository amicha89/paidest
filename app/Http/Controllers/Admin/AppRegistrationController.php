<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\AppRegistrationDataTable;
use Illuminate\Http\Request;
use App\Models\{AppReg,ApiCredential,user};
use App\Http\Helpers\Common;
use Illuminate\Support\Facades\Http;
use Session,Config,Validator,DB;
use Carbon\Carbon;

class AppRegistrationController extends Controller
{
    
    protected $helper;
    protected $user;
    public function __construct()
    {
        $this->helper = new Common();
    }
    
    
    public function index(AppRegistrationDataTable $dataTable)
    {
        //$data['menu']     = 'app-registrations';
        return $dataTable->render('admin.appregistration.index');
    }
    
    public function create()
    {
       return view('admin.appregistration.create');
       //return redirect(Config::get('adminPrefix').'/app-registrations/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);
        AppReg::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'formattedPhone' => $request->formattedPhone,
            'carrierCode' => $request->carrierCode,
            'defaultCountry' => $request->defaultCountry,
            'dob' => $request->dob,
            'rule' => $request->rule,
            'company_name' => $request->company_name,
            'company_number' => $request->company_number,
            'company_type' => $request->company_type,
            'companyIndustry' => $request->companyIndustry,
            'registeredCountry' => $request->registeredCountry,
            'source_of_funds' => $request->source_of_funds,
            'streetAddress' => $request->streetAddress,
            'cityState' => $request->cityState,
            'zipCode' => $request->zipCode,
            'ipAddress' => $request->ip(),
            'status' => '0',
            'dateTime' => Carbon::now()->toDateTimeString()
        ]);

        $this->helper->one_time_message('success', 'Application Created Successfully');
        return redirect(Config::get('adminPrefix').'/app-registrations');
        
    }

    public function edit($id)
    {
        $data['menu']     = 'app-registrations';
        $data['applications'] = AppReg::find($id);
        return view('admin.appregistration.edit', $data);
        
    }
    
    public function update(Request $request, $id)
    {
        // 'formattedPhone'    =>  'required',
        //     'carrierCode'    =>  'required',
        //     'defaultCountry'    =>  'required',
        $rules = array(
            'first_name'    =>  'required',
            'last_name'    =>  'required',
            'email'    =>  'required',
            'phone'    =>  'required',
            'dob'    =>  'required',
            'company_name'    =>  'required',
            'registeredCountry'    =>  'required',
            'streetAddress'    =>  'required',
            'cityState'    =>  'required',
            'zipCode'    =>  'required',
        );

        $fieldNames = array(
            'first_name'    =>  'First Name',
            'last_name'    =>  'Last Name',
            'email'    =>  'Valid Email Address',
            'phone'    =>  'Valid Phone Number',
            'dob'    =>  'Date of Birht',
            'company_name'    =>  'Company Name',
            'company_number'    =>  'Company Number',
            'registeredCountry'    =>  'Registred Country Name',
            'streetAddress'    =>  'Street Address',
            'cityState'    =>  'City/State Name',
            'zipCode'    =>  'Zipcode',
        );
        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($fieldNames);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }else{
            
            $appData = AppReg::find($id);
            $appData->first_name  = $request->first_name;
            $appData->last_name  = $request->last_name;
            $appData->email = $request->email;
            $appData->phone = $request->phone;
            $appData->dob = $request->dob;
            $appData->rule = $request->rule;
            $appData->company_name = $request->company_name;
            $appData->company_number = $request->company_number;
            $appData->company_type = $request->company_type;
            $appData->companyIndustry = $request->companyIndustry;
            $appData->registeredCountry = $request->registeredCountry;
            $appData->source_of_funds = $request->source_of_funds;
            $appData->streetAddress = $request->streetAddress;
            $appData->cityState = $request->cityState;
            $appData->zipCode = $request->zipCode;
            $appData->status = $request->status;
            $appData->dateTime = Carbon::now();
            $appData->save();
            //$appData = $appData->update($request->all());
            $this->helper->one_time_message('success', 'Record Updated Successfully');
            //return view('admin.appregistration.edit');
            return redirect(Config::get('adminPrefix').'/app-registrations');
        }
    }
    
    public function destroy($id)
    {
       
        if($id){ 
            AppReg::where('id',$id)->delete();
            $this->helper->one_time_message('success', 'Application Deleted Successfully');
            return redirect(Config::get('adminPrefix').'/app-registrations');
        }
    }
    // Application Status Change on Edit Registration
    public function appRegisStatusChange($id){
        
        $status = AppReg::where('id', $id)->update(['status' => '2']);
        $this->helper->one_time_message('danger', 'Application Rejected Successfully');
        return redirect(Config::get('adminPrefix').'/app-registrations');
        //$datatable = new AppRegistrationDataTable;
        //return $this->index($datatable);
    }

    // create a corporate: api request to weavr.io
    public function createCorporate($id){

        //Application data from database table applications
        $appData = AppReg::find($id);
        // $appData->profileId;
        $appID = $appData->id;
        $name = $appData->first_name;
        $surname = $appData->last_name;
        $email = $appData->email;
        $phone = $appData->phone;
        $formattedPhone = $appData->formattedPhone;
        $carrierCode = $appData->carrierCode;
        $dob = $appData->dob;
        $dobDay = date('d', strtotime($dob));
        $dobMonth = date('m', strtotime($dob));
        $dobYear = date('Y', strtotime($dob));
        $role = $appData->rule;
        $company_name = $appData->company_name;
        $regisrationNum = $appData->company_number;
        $company_type = $appData->company_type;
        $companyIndustry = $appData->companyIndustry;
        $registeredCountry = strtoupper($appData->registeredCountry);
        $sourceOfFunds = $appData->source_of_funds; 
        $streetAddress = $appData->streetAddress; 
        $city = $appData->cityState; 
        $zipCode = $appData->zipCode; 
        $ipAddress = $appData->ipAddress; 
        // $appData->status 
        // $appData->dateTime 

        //random email
        //$randomId       =   rand(70,5000);
        //$randomEmail = 'smarttech4422+'.$randomId.'@gmail.com';
        $apiUrl = config('weavrapiurl.createCorporate');
        $apiCredential = ApiCredential::where('name', 'api_credential')->first();
        $apiKey = $apiCredential['value']['api_key'];
        $profileID = $apiCredential['value']['corporates_id'];// profileId is Corporates id
        
        $requestArray = [
              'profileId' => $profileID,
              'tag' => '00111',
              'rootUser' => [
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'mobile' => [
                  'countryCode' => $carrierCode,
                  'number' => $phone,
                ],
                'companyPosition' => $role,
                'dateOfBirth' => [
                  'year' => $dobYear,
                  'month' => $dobMonth,
                  'day' => $dobDay,
                ],
              ],
              'company' => [
                'type' => $company_type,
                'businessAddress' => [
                  'addressLine1' => $streetAddress,
                  'city' => $city,
                  'postCode' => $zipCode,
                  'state' => $city,
                  'country' => $registeredCountry,
                ],
                'name' => $company_name,
                'registrationNumber' => $regisrationNum,
                'registrationCountry' => $registeredCountry,
              ],
              'industry' => $companyIndustry,
              'sourceOfFunds' => $sourceOfFunds,
              'sourceOfFundsOther' => 'OTHER',
              'acceptedTerms' => true,
              'ipAddress' => $ipAddress,
              'baseCurrency' => 'GBP',
              'feeGroup' => '',
        ];

        //dd($requestArray);
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'api-key' => $apiKey
        ])->post($apiUrl, $requestArray);

        if($response->status() === 200){

            $responseData = $response->collect();
            //dd($responseData);
            DB::transaction(function () use($appID,$responseData) {

                $first_name = $responseData['rootUser']['name'];
                $last_name = $responseData['rootUser']['surname'];
                $rootUserId = $responseData['rootUser']['id']['id'];
                $rootUserType = $responseData['rootUser']['id']['type'];
                $CountryCode = $responseData['rootUser']['mobile']['countryCode'];
                $phoneNumber = $responseData['rootUser']['mobile']['number'];
                $formattedPhone = "+".$CountryCode.$phoneNumber;
                $email = $responseData['rootUser']['email'];
                $sourceOfFunds = $responseData['sourceOfFunds'];
                $companyIndustry = $responseData['industry'];
                $company_type = $responseData['company']['type'];
                $ipAddress = $responseData['ipAddress'];

                DB::table('applications')
                ->updateOrInsert(
                    ['id' => $appID],
                    [
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'source_of_funds' => $sourceOfFunds,
                        'companyIndustry' => $companyIndustry,
                        'company_type' =>  $company_type,
                        'status' =>  '1',
                    ]
                );
                //create user
                DB::table('users')->insert([
                        'role_id' => '2',
                        'type' => 'user',
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'formattedPhone' => $formattedPhone,
                        'email' => $email,
                        'rootUser_id' => $rootUserId,
                        'rootuser_type' => $rootUserType,
                        'status' => 'Inactive', 
                        'ip_address' => $ipAddress 
                    ]);
                // get last inserted user_id
                //$user_id = DB::getPdo()->lastInsertId();
                
                // Create user detail
                 // Create user detail
                //$this->user->createUserDetail($user_id);
                // DB::table('user_details')->insert([
                //     'user_id' => $user_id
                // ]);
                // send email
                $apiCredential = ApiCredential::where('name', 'api_credential')->first();
                $apiKey = $apiCredential['value']['api_key'];
                $apiUrlforEmail =config('weavrapiurl.sendVerificationEmail');
                $reqArrayforEmail = [ 'email' => $email ]; 
                $sendEmailresponse = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'api-key' => $apiKey
                ])->post($apiUrlforEmail, $reqArrayforEmail);
                // end send email

            });
            //transction end
            
            $this->helper->one_time_message('success', 'Application Updated Successfully');
            return redirect(Config::get('adminPrefix').'/app-registrations');
        }else{
            $apiUrlErrorCode = $response->status();
            $this->helper->one_time_message('danger', "API Request Error $apiUrlErrorCode");
            return redirect()->back();
        }
    }// end function
}
