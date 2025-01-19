<?php

namespace App\UseCase\EditUserUseCase;

use App\Entity\User;

class EditUserResponse
{
    private User $user;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): EditUserResponse
    {
        $this->user = $user;
        return $this;
    }
}