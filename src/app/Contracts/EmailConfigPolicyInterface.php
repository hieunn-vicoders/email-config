<?php 

namespace VCComponent\Laravel\Mail\Contracts;

interface EmailConfigPolicyInterface
{
    public function ableToUse($user);
}