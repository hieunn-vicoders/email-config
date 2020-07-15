<?php

namespace VCComponent\Laravel\Mail\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;
use VCComponent\Laravel\Vicoders\Core\Validators\ValidatorInterface;

class MailValidator extends AbstractValidator
{
    protected $rules = [
        ValidatorInterface::RULE_ADMIN_CREATE => [
            'from_address' => ['required', 'email'],
            'from_name'    => ['required'],
            'username'     => ['required'],
            'password'     => ['required'],
        ],
        ValidatorInterface::RULE_ADMIN_UPDATE => [
            'from_address' => ['required', 'email'],
            'from_name'    => ['required'],
            'username'     => ['required'],
            'password'     => ['required'],
        ],
    ];
}
