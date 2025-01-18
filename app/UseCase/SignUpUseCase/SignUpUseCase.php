<?php

namespace App\UseCase\SignUpUseCase;

use App\Entity\User;
use App\Exception\UseCaseException;
use App\Mapper\FormData\UserFormData;
use App\Repository\UserRepository;
use App\UseCase\SendVerificationEmailUseCase\SendVerificationEmailRequest;
use App\UseCase\SendVerificationEmailUseCase\SendVerificationEmailUseCase;
use App\UseCase\SignUpUseCase\Exception\UserAlreadyExistsException;
use Nette\Schema\ValidationException;
use Nette\Security\Passwords;
use Throwable;
use Tracy\Debugger;
use Tracy\ILogger;

class SignUpUseCase
{
    public function __construct(
        readonly private UserRepository $userRepository,
        readonly private SendVerificationEmailUseCase $sendVerificationEmailUseCase,
        readonly private Passwords $passwordsService,
        readonly private ILogger $logger,
    ) {}

    public function execute(SignUpRequest $request): void
    {
        $formData = $request->getFormData();
        $explorer = $this->userRepository->getExplorer();

        $explorer->beginTransaction();
        try {
            $this->validateUserNotExists($formData);
            $this->createUser($formData);
            $this->sendVerificationEmailUseCase->execute(
                new SendVerificationEmailRequest($formData->getEmail())
            );

            $explorer->commit();
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            $explorer->rollBack();

            $this->logger->log($e, Debugger::CRITICAL);
            throw new UseCaseException($e->getMessage());
        }
    }

    private function validateUserNotExists(UserFormData $formData): void
    {
        if (!empty($this->userRepository->getByEmail($formData->getEmail()))) {
            throw new UserAlreadyExistsException('User with this email already exists.');
        }
    }

    private function createUser(UserFormData $formData): User
    {
        $user = new User();
        $user
            ->setUsername($formData->getUsername())
            ->setEmail($formData->getEmail())
            ->setPassword($this->passwordsService->hash($formData->getPassword()));

        return $this->userRepository->create($user);
    }
}