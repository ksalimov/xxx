<?php

namespace App\Form\User;

use Nette\Application\UI\Form;

readonly class AddUserFormFactory
{
    public function __construct(
        private UserFormFactory $userFormFactory
    ) {}

    public function create(): Form
    {
        $form = $this->userFormFactory->create();

        $form->addPassword(UserFormFactory::FIELD_PASSWORD, 'Password:')
            ->setRequired('Please enter your password.')
            ->addRule($form::MinLength, 'Password must be at least %d characters', 8);

        return $form;
    }
}