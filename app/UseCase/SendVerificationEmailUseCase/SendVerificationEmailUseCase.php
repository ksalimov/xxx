<?php

namespace App\UseCase\SendVerificationEmailUseCase;

use App\Repository\UserRepository;
use Nette\Application\LinkGenerator;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Nette\Utils\Random;

class SendVerificationEmailUseCase
{
    public function __construct(
        private string $senderEmail,
        private string $senderName,
        private Mailer $mailer,
        private LinkGenerator $linkGenerator,
        private UserRepository $userRepository,
    ) {}

    public function execute(SendVerificationEmailRequest $request): void
    {
        $userEmail = $request->getUserEmail();
        $token = $this->getVerificationToken();
        $this->userRepository->saveVerificationToken($userEmail, $token);
        $verificationLink = $this->getVerificationLink($token);

        $mail = $this->createVerificationEmail($userEmail, $verificationLink);
        $this->mailer->send($mail);
    }

    private function getVerificationToken(): string
    {
        return Random::generate(16);
    }

    private function getVerificationLink(string $token): string
    {
        return $this->linkGenerator->link('VerifyEmail:default', ['token' => $token]);
    }

    private function createVerificationEmail(string $userEmail, string $verificationLink): Message
    {
        $mail = new Message();
        $mail->setFrom($this->senderEmail, $this->senderName)
            ->addTo($userEmail)
            ->setSubject('Please verify your email address')
            ->setBody("Please verify your email address using the following link: <a href=\"$verificationLink\" target=\"_blank\">Verify your email</a>");

        return $mail;
    }
}