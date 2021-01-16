<?php

namespace VCComponent\Laravel\Mail\Test\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use VCComponent\Laravel\Mail\Entities\Mail;
use VCComponent\Laravel\Mail\Facades\MailConfig;
use VCComponent\Laravel\Mail\Test\TestCase;

class MaiConfigTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_mail_configuration()
    {
        $mail    = factory(Mail::class)->make();
        $configs = [
            'driver'     => $mail->driver,
            'host'       => $mail->host,
            'port'       => $mail->port,
            'from'       => ['address' => $mail->from_address, 'name' => $mail->from_name],
            'encryption' => $mail->encryption,
            'username'   => $mail->username,
            'password'   => $mail->password,
            'sendmail'   => '/usr/sbin/sendmail -bs',
        ];

        MailConfig::setConfig($configs);

        $this->assertEquals($configs['driver'], Config::get('mail.driver'));
        $this->assertEquals($configs['host'], Config::get('mail.host'));
        $this->assertEquals($configs['port'], Config::get('mail.port'));
        $this->assertEquals($configs['from'], Config::get('mail.from'));
        $this->assertEquals($configs['encryption'], Config::get('mail.encryption'));
        $this->assertEquals($configs['username'], Config::get('mail.username'));
        $this->assertEquals($configs['password'], Config::get('mail.password'));
        $this->assertEquals($configs['sendmail'], Config::get('mail.sendmail'));
    }

    /** @test */
    public function can_get_config_from_db()
    {
        $mail = factory(Mail::class)->create();

        $configs = MailConfig::getConfigFromDb();

        $this->assertEquals($configs, [
            'driver'     => $mail->driver,
            'host'       => $mail->host,
            'port'       => $mail->port,
            'from'       => ['address' => $mail->from_address, 'name' => $mail->from_name],
            'encryption' => $mail->encryption,
            'username'   => $mail->username,
            'password'   => $mail->password,
            'sendmail'   => '/usr/sbin/sendmail -bs',
        ]);
    }
}
