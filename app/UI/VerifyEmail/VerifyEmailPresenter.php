<?php

namespace App\UI\VerifyEmail;

use App\UseCase\VerifyEmailUseCase\VerifyEmailRequest;
use App\UseCase\VerifyEmailUseCase\VerifyEmailUseCase;
use Nette\Application\UI\Presenter;
use Throwable;

class VerifyEmailPresenter extends Presenter
{
    public function __construct(
        private VerifyEmailUseCase $verifyEmailUseCase,
    ) {
        parent::__construct();
    }

    public function actionDefault(string $token): void
    {
        try {
            $response = $this->verifyEmailUseCase->execute(
                new VerifyEmailRequest($token)
            );

            if ($response->isVerified()) {
                $this->flashMessage('Your email has been successfully verified.');
            } else {
                $this->flashMessage('Invalid or expired token.', 'error');
            }
        } catch (Throwable $e) {
            $this->flashMessage(
                'Something went wrong. Please try again later or contact support if the issue continues.',
                'danger'
            );
        }

        $this->redirect('Home:default');
    }
}