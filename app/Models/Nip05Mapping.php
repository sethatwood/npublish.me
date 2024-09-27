<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nip05Mapping extends Model
{
    protected $fillable = [
        'local_part',
        'pubkey',
    ];
}
