<?php

namespace VCComponent\Laravel\Mail\Test;

use Dingo\Api\Provider\LaravelServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use VCComponent\Laravel\Mail\Providers\MailConfigServiceProvider;
use VCComponent\Laravel\User\Providers\UserComponentEventProvider;
use VCComponent\Laravel\User\Providers\UserComponentProvider;
use VCComponent\Laravel\User\Providers\UserComponentRouteProvider;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return VCComponent\Laravel\Generator\Providers\GeneratorServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelServiceProvider::class,
            MailConfigServiceProvider::class,
            \Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
            \Illuminate\Auth\AuthServiceProvider::class,
            UserComponentEventProvider::class,
            UserComponentProvider::class,
            UserComponentRouteProvider::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../src/database/factories');
        $this->withFactories(__DIR__ . '/../tests/Stubs/Factory');
        $this->loadMigrationsFrom(__DIR__ . '/../src/database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('api', [
            'standardsTree' => 'x',
            'subtype' => '',
            'version' => 'v1',
            'prefix' => 'api',
            'domain' => null,
            'name' => null,
            'conditionalRequest' => true,
            'strict' => false,
            'debug' => true,
            'errorFormat' => [
                'message' => ':message',
                'errors' => ':errors',
                'code' => ':code',
                'status_code' => ':status_code',
                'debug' => ':debug',
            ],
            'middleware' => [

            ],
            'auth' => [

            ],
            'throttling' => [

            ],
            'transformer' => \Dingo\Api\Transformer\Adapter\Fractal::class,
            'defaultFormat' => 'json',
            'formats' => [
                'json' => \Dingo\Api\Http\Response\Format\Json::class,
            ],
            'formatsOptions' => [
                'json' => [
                    'pretty_print' => false,
                    'indent_style' => 'space',
                    'indent_size' => 2,
                ],
            ],
        ]);
        $app['config']->set('email.auth_middleware', [
            'admin' => [
                'middleware' => '',
            ],
            'frontend' => [
                'middleware' => '',
            ],
        ]);
        $app['config']->set('user', ['namespace' => 'user-management']);

        $app['config']->set('jwt.secret', '5jMwJkcDTUKlzcxEpdBRIbNIeJt1q5kmKWxa0QA2vlUEG6DRlxcgD7uErg51kbBl');
        $app['config']->set('auth.providers.users.model', \VCComponent\Laravel\User\Entities\User::class);
        $app['config']->set('repository.cache.enabled', false);
        $app['config']->set('mail', [
            'default' => env('MAIL_MAILER', 'smtp'),
            'mailers' => [
                'smtp' => [
                    'transport' => 'smtp',
                    'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
                    'port' => env('MAIL_PORT', 587),
                    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                    'username' => env('MAIL_USERNAME'),
                    'password' => env('MAIL_PASSWORD'),
                    'timeout' => null,
                    'auth_mode' => null,
                ],

                'ses' => [
                    'transport' => 'ses',
                ],

                'mailgun' => [
                    'transport' => 'mailgun',
                ],

                'postmark' => [
                    'transport' => 'postmark',
                ],

                'sendmail' => [
                    'transport' => 'sendmail',
                    'path' => '/usr/sbin/sendmail -bs',
                ],

                'log' => [
                    'transport' => 'log',
                    'channel' => env('MAIL_LOG_CHANNEL'),
                ],

                'array' => [
                    'transport' => 'array',
                ],
            ],
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
            'markdown' => [
                'theme' => 'default',

                'paths' => [
                    resource_path('views/vendor/mail'),
                ],
            ],
        ]);
        $app['config']->set('view', [
            'paths' => [
                resource_path('views'),
            ],
            'compiled' => env(
                'VIEW_COMPILED_PATH',
                realpath(storage_path('framework/views'))
            ),
            'expires' => env('VIEW_CHECK_EXPIRATION', true),
        ]);

    }
    public function assertValidation($response, $field, $error_message)
    {
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            "errors" => [
                $field => [
                    $error_message,
                ],
            ],
        ]);
    }
}
