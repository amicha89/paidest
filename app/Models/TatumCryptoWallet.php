<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TatumCryptoWallet extends Model
{
    use HasFactory;
    protected $table = 'tatum_bsc_wallet';
    protected $fillable = [
        'xpub',
        'mnemonic',
        'public_key',
        'private_key'
    ];
}
