<?php

namespace App\Service;

use App\Entity\User;
use App\Mapper\FormData\UserFormData;
use App\Repository\UserRepository;
use App\UseCase\SignUpUseCase\Exception\UserAlreadyExistsException;
use Nette\Security\Passwords;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private Passwords $passwordsService,
    ) {}

    public function validateUserNotExists(UserFormData $formData): void
    {
        if (!empty($this->userRepository->getByEmail($formData->getEmail()))) {
            throw new UserAlreadyExistsException('User with this email already exists.');
        }
    }

    public function createUser(UserFormData $formData): User
    {
        $user = new User();
        $user
            ->setUsername($formData->getUsername())
            ->setEmail($formData->getEmail())
            ->setPassword($this->passwordsService->hash($formData->getPassword()));

        return $this->userRepository->create($user);
    }
}