<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCredential extends Model
{
    use HasFactory;
    protected $table = 'settings';
    public $timestamps = false;
    protected $fillable = ['name','value','type'];
    protected $casts = [
        'value' => 'array',
    ];
}
