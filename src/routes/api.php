<?php
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'admin'], function ($api) {
        $api->get("mails", "VCComponent\Laravel\Mail\Http\Controllers\Api\Admin\MailController@getConfig");
        $api->post("mails", "VCComponent\Laravel\Mail\Http\Controllers\Api\Admin\MailController@createOrUpdate");
        $api->post("mails/testconfigurations", "VCComponent\Laravel\Mail\Http\Controllers\Api\Admin\MailController@MailConfigTest");
    });

});
