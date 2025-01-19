<?php

namespace App\Form\User;

use Nette\Application\UI\Form;

class UserFormFactory
{
    const FIELD_USERNAME = 'username';
    const FIELD_EMAIL = 'email';
    const FIELD_PASSWORD = 'password';

    public function create(): Form
    {
        $form = new Form();

        $form->addText(self::FIELD_USERNAME, 'Username:')
            ->setRequired('Please enter your username.')
            ->addRule($form::MinLength, 'Username must be at least %d characters', 3)
            ->addRule($form::MaxLength, 'Username must be not more than %d characters', 50);

        $form->addEmail(self::FIELD_EMAIL, 'Email:')
            ->setRequired('Please enter your email.')
            ->addRule($form::Email, 'Please enter a valid email address.')
            ->addRule($form::MaxLength, 'Username must be not more than %d characters', 100);

        return $form;
    }
}