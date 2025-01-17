<?php

namespace App\UseCase\VerifyEmailUseCase;

use App\Exception\UseCaseException;
use App\Repository\UserRepository;
use Throwable;
use Tracy\Debugger;
use Tracy\ILogger;

class VerifyEmailUseCase
{
    public function __construct(
        readonly private UserRepository $userRepository,
        readonly private ILogger $logger,
    ) {}

    public function execute(VerifyEmailRequest $request): VerifyEmailResponse
    {
        $token = $request->getToken();
        $response = new VerifyEmailResponse();

        try {
            $user = $this->userRepository->findByVerificationToken($token);

            if ($user) {
                $this->userRepository->verifyUser($user->getId());
                $response->setVerified(true);
            } else {
                $response->setVerified(false);
            }
        } catch (Throwable $e) {
            $this->logger->log($e, Debugger::CRITICAL);
            throw new UseCaseException($e->getMessage());
        }

        return $response;
    }
}