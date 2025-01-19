<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Mapper\FormData\UserFormData;
use App\Repository\UserRepository;

class UserValidationService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function validateUserNotExists(string $email): void
    {
        if (!empty($this->userRepository->getByEmail($email))) {
            throw new UserAlreadyExistsException('User with this email already exists.');
        }
    }

    public function validateUserUpdate(UserFormData $formData, User $existingUser): void
    {
        $email = $formData->getEmail();
        if ($email !== $existingUser->getEmail()) {
            $this->validateUserNotExists($email);
        }
    }
}