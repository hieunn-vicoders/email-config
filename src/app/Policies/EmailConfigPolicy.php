<?php

namespace VCComponent\Laravel\Mail\Policies;

use VCComponent\Laravel\Mail\Contracts\EmailConfigPolicyInterface;

class EmailConfigPolicy implements EmailConfigPolicyInterface
{
    public function before($user, $ability)
    {
        if ($user->isAdministrator()) {
            return true;
        }
    }

    public function manage($user)
    {
        return $user->hasPermission('manage-email-config');
    }
}