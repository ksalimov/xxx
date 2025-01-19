<?php

declare(strict_types=1);

namespace App\UI\Front\SignUp;

use App\Exception\UseCaseException;
use App\Exception\UserAlreadyExistsException;
use App\Form\User\AddUserFormFactory;
use App\Form\User\UserFormFactory;
use App\Mapper\FormData\UserFormData;
use App\UseCase\SignUpUseCase\SignUpRequest;
use App\UseCase\SignUpUseCase\SignUpUseCase;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Http\Session;

class SignUpPresenter extends Presenter
{
    public function __construct(
        private AddUserFormFactory $addUserFormFactory,
        private SignUpUseCase $signUpUseCase,
        Session $session
    ) {
        parent::__construct();

        $session->start();
    }

    protected function createComponentSignUpForm(): Form
    {
        $form = $this->addUserFormFactory->create();

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
            $form[UserFormFactory::FIELD_EMAIL]->addError($e->getMessage());
        } catch (UseCaseException $e) {
            $this->flashMessage(
                'Something went wrong. Please try again later or contact support if the issue continues.',
                'danger'
            );
        }
    }
}
