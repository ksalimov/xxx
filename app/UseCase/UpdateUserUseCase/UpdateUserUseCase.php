<?php

namespace App\UseCase\UpdateUserUseCase;

use App\Exception\UseCaseException;
use App\Service\UserService;
use Nette\Schema\ValidationException;
use Throwable;
use Tracy\Debugger;
use Tracy\ILogger;

readonly class UpdateUserUseCase
{
    public function __construct(
        private UserService $userService,
        private ILogger $logger,
    ) {}

    public function execute(UpdateUserRequest $request): void
    {
        $formData = $request->getFormData();
        $existingUser = $request->getUser();

        try {
            $this->userService->updateUser($formData, $existingUser);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->logger->log($e, Debugger::CRITICAL);
            throw new UseCaseException($e->getMessage());
        }
    }
}