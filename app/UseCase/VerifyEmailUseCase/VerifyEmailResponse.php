<?php

namespace App\UseCase\VerifyEmailUseCase;

class VerifyEmailResponse
{
    private bool $verified;

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): VerifyEmailResponse
    {
        $this->verified = $verified;
        return $this;
    }
}