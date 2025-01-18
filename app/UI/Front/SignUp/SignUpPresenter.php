<?php

declare(strict_types=1);

namespace App\UI\Front\SignUp;

use App\Exception\UseCaseException;
use App\Mapper\FormData\UserFormData;
use App\UseCase\SignUpUseCase\Exception\UserAlreadyExistsException;
use App\UseCase\SignUpUseCase\SignUpRequest;
use App\UseCase\SignUpUseCase\SignUpUseCase;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Http\Session;

class SignUpPresenter extends Presenter
{
    public function __construct(
        private SignUpUseCase $signUpUseCase,
        Session $session
    ) {
        parent::__construct();

        $session->start();
    }

    protected function createComponentSignUpForm(): Form
    {
        $form = new Form();

        $form->addText('username', 'Username:')
            ->setRequired('Please enter your username.')
            ->addRule($form::MinLength, 'Username must be at least %d characters', 3)
            ->addRule($form::MaxLength, 'Username must be not more than %d characters', 50);

        $form->addEmail('email', 'Email:')
            ->setRequired('Please enter your email.')
            ->addRule($form::Email, 'Please enter a valid email address.')
            ->addRule($form::MaxLength, 'Username must be not more than %d characters', 100);

        $form->addPassword('password', 'Password:')
            ->setRequired('Please enter your password.')
            ->addRule($form::MinLength, 'Password must be at least %d characters', 8);

        $form->addSubmit('signUp', 'Sign Up');

        $form->onSuccess[] = [$this, 'signUp'];

        $form->addProtection();

        return $form;
    }

    public function signUp(Form $form, UserFormData $formData): void
    {
        try {
            $this->signUpUseCase->execute(new SignUpRequest($formData));

            $this->flashMessage('Registration successful. Please verify your email.', 'success');
            $this->redirect('Home:default');
        } catch (UserAlreadyExistsException $e) {
            $form[UserFormData::FIELD_EMAIL]->addError($e->getMessage());
        } catch (UseCaseException $e) {
            $form->addError('Something went wrong. Please try again later or contact support if the issue continues.');
        }
    }
}
