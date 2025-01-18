<?php

namespace App\UseCase\SignUpUseCase;

use App\Exception\UseCaseException;
use App\Service\UserService;
use App\UseCase\SendVerificationEmailUseCase\SendVerificationEmailRequest;
use App\UseCase\SendVerificationEmailUseCase\SendVerificationEmailUseCase;
use Nette\Database\Explorer;
use Nette\Schema\ValidationException;
use Throwable;
use Tracy\Debugger;
use Tracy\ILogger;

class SignUpUseCase
{
    public function __construct(
        readonly private SendVerificationEmailUseCase $sendVerificationEmailUseCase,
        readonly private UserService $userService,
        readonly private Explorer $explorer,
        readonly private ILogger $logger,
    ) {}

    public function execute(SignUpRequest $request): void
    {
        $formData = $request->getFormData();

        $this->explorer->beginTransaction();
        try {
            $this->userService->validateUserNotExists($formData);
            $this->userService->createUser($formData);

            $this->sendVerificationEmailUseCase->execute(
                new SendVerificationEmailRequest($formData->getEmail())
            );

            $this->explorer->commit();
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->explorer->rollBack();

            $this->logger->log($e, Debugger::CRITICAL);
            throw new UseCaseException($e->getMessage());
        }
    }
}