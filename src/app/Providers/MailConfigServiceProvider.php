<?php

namespace VCComponent\Laravel\Mail\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Mail\Repositories\MailRepository;
use VCComponent\Laravel\Mail\Repositories\MailRepositoryEloquent;

class MailConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(MailRepository::class, MailRepositoryEloquent::class);
        $this->configMail();
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'mail');
    }

    public function configMail()
    {
        if (\Schema::hasTable('mails')) {
            $mail = DB::table('mails')->first();
            if ($mail) {
                $config = array(
                    'driver'     => $mail->driver,
                    'host'       => $mail->host,
                    'port'       => $mail->port,
                    'from'       => array('address' => $mail->from_address, 'name' => $mail->from_name),
                    'encryption' => $mail->encryption,
                    'username'   => $mail->username,
                    'password'   => $mail->password,
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                );
                Config::set('mail', $config);
            }
        }
    }
}
