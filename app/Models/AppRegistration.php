<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppRegistration extends Model
{
    use HasFactory;
    
    protected $table = 'applications';
    public $timestamps = false;
    
     protected $fillable = [     
                            'first_name',
                            'last_name',
                            'email', 
                            'phone',
                            'formattedPhone',
                            'carrierCode',
                            'defaultCountry',
                            'dob',
                            'rule',
                            'company_name',
                            'company_number',
                            'company_type',
                            'companyIndustry',
                            'registeredCountry',
                            'source_of_funds',
                            'streetAddress',
                            'cityState',
                            'zipCode',
                            'ipAddress',
                            'status',
                            'dateTime'
                        ];
}
