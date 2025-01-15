<?php

namespace App\Mapper\FormData;

readonly class SignUpFormData
{
    const FIELD_USERNAME = 'username';
    const FIELD_EMAIL = 'email';
    const FIELD_PASSWORD = 'password';

    public function __construct(
        private string $username,
        private string $email,
        private string $password,
    ) {}

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}