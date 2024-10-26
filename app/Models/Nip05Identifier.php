<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nip05Identifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'public_key',
        'email',
        'passkey',
    ];
}
