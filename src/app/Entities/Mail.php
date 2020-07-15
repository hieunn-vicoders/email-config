<?php

namespace VCComponent\Laravel\Mail\Entities;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $fillable = [
        'driver',
        'host',
        'port',
        'from_address',
        'from_name',
        'encryption',
        'username',
        'password',
    ];

    public function ableToUse($user)
    {
        return true;
    }
}
