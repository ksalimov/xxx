<?php

namespace App\UseCase\ManageUsersAdminUseCase;

use App\Exception\UseCaseException;
use App\Repository\UserRepository;
use Nette\Utils\Paginator;
use Throwable;
use Tracy\Debugger;
use Tracy\ILogger;

readonly class ManageUsersAdminUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private ILogger $logger
    ) {}

    public function execute(ManageUsersAdminRequest $request): ManageUsersAdminResponse
    {
        try {
            $paginator = new Paginator;
            $paginator->setItemsPerPage($request->getItemsPerPage());
            $paginator->setPage($request->getPage());
            $paginator->setItemCount($this->userRepository->getTotalCount());

            $users = $this->userRepository->fetchUsersByPage($paginator->getLength(), $paginator->getOffset());
        } catch (Throwable $e) {
            $this->logger->log($e, Debugger::CRITICAL);
            throw new UseCaseException($e->getMessage());
        }

        return (new ManageUsersAdminResponse())
            ->setPaginator($paginator)
            ->setUsers($users);
    }
}