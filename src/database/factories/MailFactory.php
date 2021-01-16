<?php

use VCComponent\Laravel\Mail\Entities\Mail;

$factory->define(Mail::class, function () {
    return [
        'driver'       => 'smtp',
        'host'         => 'smtp.mailtrap.io',
        'port'         => '2525',
        'from_address' => 'admin@vicoders.com',
        'from_name'    => 'Vicoders',
        'encryption'   => 'TLS',
        'username'     => 'test_username',
        'password'     => 'secret',
    ];
});
