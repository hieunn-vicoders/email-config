<?php

namespace VCComponent\Laravel\Mail\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use \Exception;

class MailConfigService
{
    public function getConfigFromDB()
    {
        $configs = [];

        if (!DB::connection()) {
            throw new Exception('No connection');
        }

        if (Schema::hasTable('mails')) {
            $mail = DB::table('mails')->first();
            if ($mail) {
                $configs = array(
                    'driver'     => $mail->driver,
                    'host'       => $mail->host,
                    'port'       => $mail->port,
                    'from'       => array('address' => $mail->from_address, 'name' => $mail->from_name),
                    'encryption' => $mail->encryption,
                    'username'   => $mail->username,
                    'password'   => $mail->password,
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                );
            }
        }

        return $configs;
    }

    public function setConfig(array $configs)
    {
        Config::set('mail', $configs);
    }
}
