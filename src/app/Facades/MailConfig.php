<?php

namespace VCComponent\Laravel\Mail\Facades;

use Illuminate\Support\Facades\Facade;

class MailConfig extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mailConfig';
    }
}
