<?php

namespace VCComponent\Laravel\Mail\Policies;

use VCComponent\Laravel\Mail\Contracts\EmailConfigPolicyInterface;

class EmailConfigPolicy implements EmailConfigPolicyInterface
{
    public function ableToUse($user)
    {
        return true;
    }
}