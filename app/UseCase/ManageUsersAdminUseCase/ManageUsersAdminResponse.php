<?php

namespace App\UseCase\ManageUsersAdminUseCase;

use Nette\Utils\Paginator;

class ManageUsersAdminResponse
{
    private Paginator $paginator;
    private array $users;

    public function getPaginator(): Paginator
    {
        return $this->paginator;
    }

    public function setPaginator(Paginator $paginator): ManageUsersAdminResponse
    {
        $this->paginator = $paginator;
        return $this;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function setUsers(array $users): ManageUsersAdminResponse
    {
        $this->users = $users;
        return $this;
    }
}