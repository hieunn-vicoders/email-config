<?php

namespace VCComponent\Laravel\Mail\Test\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail as MailTest;
use VCComponent\Laravel\Mail\Entities\Mail;
use VCComponent\Laravel\Mail\Facades\MailConfig;
use VCComponent\Laravel\Mail\Test\TestCase;

class MaiConfigTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_mail_configuration()
    {
        $mail = factory(Mail::class)->make();
        $configs = array_merge(Config::get('mail', [
            'mailers' => [
                'smtp' => [
                    'transport' => $mail->driver,
                    'host' => $mail->host,
                    'port' => $mail->port,
                    'encryption' => $mail->encryption,
                    'username' => $mail->username,
                    'password' => $mail->password,
                ],
            ],
            'from' => [
                'address' => $mail->from_address,
                'name' => $mail->from_name,
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
                    'transport' => $mail->driver,
                    'host' => $mail->host,
                    'port' => $mail->port,
                    'encryption' => $mail->encryption,
                    'username' => $mail->username,
                    'password' => $mail->password,
                ],
            ],
            'from' => [
                'address' => $mail->from_address,
                'name' => $mail->from_name,
            ],
        ]));
    }

    /** @test */
    public function should_not_get_mail_not_exists_admin()
    {
        $token = $this->loginToken();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', "api/admin/mails");
        $response->assertStatus(500);
        $response->assertJson(['message' => 'Chưa config mail server !']);

    }
    /** @test */
    public function should_get_mail_admin()
    {
        $token = $this->loginToken();
        $mail = factory(Mail::class)->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', "api/admin/mails");
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'host' => $mail->host,
                'port' => $mail->port,
                'username' => $mail->username,
            ],
        ]);

    }

    /** @test */
    public function should_create_mail_admin()
    {
        $token = $this->loginToken();
        $data = factory(Mail::class)->make(['from_address' => 'email'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', "api/admin/mails", $data);
        $this->assertValidation($response, 'from_address', 'The from address must be a valid email address.');

        $data = factory(Mail::class)->make()->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', "api/admin/mails", $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('mails', $data);
    }

    /** @test */
    public function should_not_create_mail_malformed_admin()
    {
        $token = $this->loginToken();
        $data = factory(Mail::class)->make(['from_address' => 'email'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', "api/admin/mails", $data);
        $this->assertValidation($response, 'from_address', 'The from address must be a valid email address.');
    }

    /** @test */
    public function should_not_create_mail_required_admin()
    {
        $token = $this->loginToken();
        $data = factory(Mail::class)->make(['from_address' => '', 'from_name' => '', 'username' => '', 'password' => ''])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', "api/admin/mails", $data);
        $this->assertValidation($response, 'from_address', 'The from address field is required.');
        $this->assertValidation($response, 'from_name', 'The from name field is required.');
        $this->assertValidation($response, 'username', 'The username field is required.');
        $this->assertValidation($response, 'password', 'The password field is required.');
    }

    /** @test */

    public function should_update_existed_mail_admin()
    {
        $token = $this->loginToken();
        $data_existed = [
            'from_address' => 'test@gmail.com',
            'host' => 'smtp.gmail.com',
            'port' => '587',
        ];

        factory(Mail::class)->create($data_existed);
        $this->assertDatabaseHas('mails', $data_existed);

        $data = [
            'from_address' => 'test@gmail.com',
            'host' => 'gmail.com',
            'port' => '443',
            'from_name' => 'test',
            'username' => 'test',
            'password' => 'secret',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', "api/admin/mails", $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('mails', $data);
        $this->assertDatabaseMissing('mails', $data_existed);

    }

    /** @test */

    public function should_test_mail_admin()
    {
        MailTest::fake();
        $token = $this->loginToken();
        factory(Mail::class)->create([]);
        $data = ['email' => 'test'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', "api/admin/mails/testconfigurations", $data);

        $this->assertValidation($response, 'email', 'The email must be a valid email address.');

        $data = ['email' => 'test@gmail.com'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', "api/admin/mails/testconfigurations", $data);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

    }

    protected function loginToken()
    {
        $dataLogin = ['username' => 'admin', 'password' => '123456789', 'email' => 'admin@test.com'];
        $user = factory(\VCComponent\Laravel\User\Entities\User::class)->make($dataLogin);
        $user->save();
        $login = $this->json('POST', 'api/user-management/login', $dataLogin);
        $token = $login->Json()['token'];
        return $token;
    }
}
