<?php

namespace App\UseCase\EditUserUseCase;

use App\Exception\UseCaseException;
use App\Exception\UserDoesNotExistException;
use App\Repository\UserRepository;
use Nette\Schema\ValidationException;
use Throwable;
use Tracy\Debugger;
use Tracy\ILogger;

readonly class EditUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private ILogger $logger,
    ) {}

    public function execute(EditUserRequest $request): EditUserResponse
    {
        $userId = $request->getUserId();
        $response = new EditUserResponse();

        try {
            $user = $this->userRepository->getById($userId);
            if (!$user) {
                throw new UserDoesNotExistException("User with id $userId does not exist.");
            }

            $response->setUser($user);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->logger->log($e, Debugger::CRITICAL);
            throw new UseCaseException($e->getMessage());
        }

        return $response;
    }
}