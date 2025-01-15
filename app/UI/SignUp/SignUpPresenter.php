<?php

declare(strict_types=1);

namespace App\UI\SignUp;

use App\Mapper\FormData\SignUpFormData;
use App\Repository\UserRepository;
use App\UseCase\SignUpUseCase\Exception\UserAlreadyExistsException;
use App\UseCase\SignUpUseCase\SignUpRequest;
use App\UseCase\SignUpUseCase\SignUpUseCase;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Http\Session;
use Nette\Security\Passwords;
use Tracy\ILogger;

class SignUpPresenter extends Presenter
{
    public function __construct(
        private UserRepository $userRepository,
        private Passwords $passwords,
        private ILogger $logger,
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

    public function signUp(Form $form, SignUpFormData $formData): void
    {
        try {
            (new SignUpUseCase($this->userRepository, $this->passwords, $this->logger))
                ->execute(new SignUpRequest($formData));

            $this->flashMessage('Registration successful. Please verify your email.', 'success');
            $this->redirect('Home:default');
        } catch (UserAlreadyExistsException $e) {
            $form[SignUpFormData::FIELD_EMAIL]->addError($e->getMessage());
        } catch (AbortException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $form->addError('Something went wrong. Please try again later or contact support if the issue continues.');
        }
    }
}
