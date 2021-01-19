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
        $configs = array_merge(Config::get('mail', [
            'mailers' => [
                'smtp' => [
                    'transport'  => $mail->driver,
                    'host'       => $mail->host,
                    'port'       => $mail->port,
                    'encryption' => $mail->encryption,
                    'username'   => $mail->username,
                    'password'   => $mail->password,
                ],
            ],
            'from'    => [
                'address' => $mail->from_address,
                'name'    => $mail->from_name,
            ],
        ]));

        MailConfig::setConfig($configs);

        $this->assertEquals($configs['mailers']['smtp']['transport'], Config::get('mail.mailers.smtp.transport'));
        $this->assertEquals($configs['mailers']['smtp']['host'], Config::get('mail.mailers.smtp.host'));
        $this->assertEquals($configs['mailers']['smtp']['port'], Config::get('mail.mailers.smtp.port'));
        $this->assertEquals($configs['mailers']['smtp']['encryption'], Config::get('mail.mailers.smtp.encryption'));
        $this->assertEquals($configs['mailers']['smtp']['username'], Config::get('mail.mailers.smtp.username'));
        $this->assertEquals($configs['mailers']['smtp']['password'], Config::get('mail.mailers.smtp.password'));
        $this->assertEquals($configs['from'], Config::get('mail.from'));
    }

    /** @test */
    public function can_get_config_from_db()
    {
        $mail = factory(Mail::class)->create();

        $configs = MailConfig::getConfigFromDb();

        $this->assertEquals($configs, array_merge(Config::get('mail'), [
            'mailers' => [
                'smtp' => [
                    'transport'  => $mail->driver,
                    'host'       => $mail->host,
                    'port'       => $mail->port,
                    'encryption' => $mail->encryption,
                    'username'   => $mail->username,
                    'password'   => $mail->password,
                ],
            ],
            'from'    => [
                'address' => $mail->from_address,
                'name'    => $mail->from_name,
            ],
        ]));
    }
}
