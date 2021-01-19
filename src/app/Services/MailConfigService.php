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
        $configs = Config::get('mail');
        if (Schema::hasTable('mails')) {
            $mail = DB::table('mails')->first();
            if ($mail) {
                $configs = array_merge($configs, [
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
                ]);
            }
        }

        return $configs;
    }

    public function setConfig(array $configs)
    {
        Config::set('mail', $configs);
    }
}
