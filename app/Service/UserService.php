<?php

namespace App\Service;

use App\Entity\User;
use App\Mapper\FormData\UserFormData;
use App\Repository\UserRepository;
use Nette\Security\Passwords;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserValidationService $userValidationService,
        private Passwords $passwordsService,
    ) {}

    public function createUser(UserFormData $formData): User
    {
        $this->userValidationService->validateUserNotExists($formData->getEmail());

        $user = new User();
        $user
            ->setUsername($formData->getUsername())
            ->setEmail($formData->getEmail())
            ->setPassword($this->passwordsService->hash($formData->getPassword()));

        return $this->userRepository->create($user);
    }

    public function updateUser(UserFormData $formData, User $existingUser): int
    {
        $this->userValidationService->validateUserUpdate($formData, $existingUser);

        $user = new User();
        $user
            ->setId($existingUser->getId())
            ->setUsername($formData->getUsername())
            ->setEmail($formData->getEmail());

        if (!empty($formData->getPassword())) {
            $user->setPassword($this->passwordsService->hash($formData->getPassword()));
        } else {
            $user->setPassword($existingUser->getPassword());
        }

        return $this->userRepository->update($user);
    }
}