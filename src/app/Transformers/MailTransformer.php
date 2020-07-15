<?php

namespace VCComponent\Laravel\Mail\Transformers;

use League\Fractal\TransformerAbstract;

class MailTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'           => (int) $model->id,
            'driver'       => $model->driver,
            'host'         => $model->host,
            'port'         => $model->port,
            'from_address' => $model->from_address,
            'from_name'    => $model->from_name,
            'encryption'   => $model->encryption,
            'username'     => $model->username,
            'password'     => $model->password,
            'timestamps'   => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
}
