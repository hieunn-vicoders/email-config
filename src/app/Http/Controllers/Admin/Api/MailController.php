<?php

namespace VCComponent\Laravel\Mail\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;
use VCComponent\Laravel\Mail\Mail\TestMailConfigNotify;
use VCComponent\Laravel\Mail\Repositories\MailRepository;
use VCComponent\Laravel\Mail\Transformers\MailTransformer;
use VCComponent\Laravel\Mail\Validators\MailValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class MailController extends ApiController
{
    protected $repository;
    protected $validator;

    public function __construct(MailRepository $repository, MailValidator $validator)
    {
        $this->repository = $repository;
        $this->entity = $repository->getEntity();
        $this->validator = $validator;
        $this->transformer = MailTransformer::class;

        $user = $this->getAuthenticatedUser();
        if (Gate::forUser($user)->denies('manage', $this->entity)) {
            throw new PermissionDeniedException();
        }
    }

    public function getConfig()
    {
        $mail = $this->repository->first();

        if (!$mail) {
            throw new \Exception('Chưa config mail server !', 1);
        }

        return $this->response->item($mail, new $this->transformer());
    }

    public function createOrUpdate(Request $request)
    {
        $this->validator->isValid($request, 'RULE_ADMIN_CREATE');

        $data = $request->all();

        $setup_exists = $this->repository->first();

        if ($setup_exists) {
            $mail = $this->repository->update($data, $setup_exists->id);
        } else {
            $mail = $this->repository->create($data);
        }

        return $this->response->item($mail, new $this->transformer());
    }

    public function MailConfigTest(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $config = $this->repository->first();

        if (!$config) {
            throw new \Exception("Cấu hình chưa được cài đặt !", 1);
        }

        try {
            Mail::to($request->get('email'))->send(new TestMailConfigNotify());
        } catch (\Swift_TransportException $e) {
            throw new \Exception("Cấu hình Mail server chưa đúng ! Vui lòng kiểm tra lại !", 1);
        }

        return response()->json(['success' => true]);

    }
}
