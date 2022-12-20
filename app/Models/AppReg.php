<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppReg extends Model
{
    use HasFactory;
    
    protected $table = 'applications';
    public $timestamps = false;
    protected $guarded = [];
}
