<?php

namespace App\UseCase\DeleteUserUseCase;

use App\Exception\UseCaseException;
use App\Repository\UserRepository;
use Throwable;
use Tracy\Debugger;
use Tracy\ILogger;

readonly class DeleteUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private ILogger $logger,
    ) {}

    public function execute(DeleteUserRequest $request): void
    {
        try {
            $this->userRepository->delete($request->getId());
        } catch (Throwable $e) {
            $this->logger->log($e, Debugger::CRITICAL);
            throw new UseCaseException($e->getMessage());
        }
    }
}