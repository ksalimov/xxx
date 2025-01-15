<?php

namespace App\Repository;

use App\Entity\User;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

class UserRepository
{
    private const TABLE_NAME = 'users';
    private const FIELD_ID = 'id';
    private const FIELD_USERNAME = 'username';
    private const FIELD_EMAIL = 'email';
    private const FIELD_PASSWORD = 'password';
    private const FIELD_VERIFIED = 'verified';
    private const FIELD_VERIFICATION_TOKEN = 'verification_token';

    public function __construct(
        readonly private Explorer $explorer
    ) {}

    public function getExplorer(): Explorer
    {
        return $this->explorer;
    }

    public function create(User $user): ActiveRow
    {
        return $this->explorer->table(self::TABLE_NAME)->insert([
            self::FIELD_USERNAME => $user->getUsername(),
            self::FIELD_EMAIL => $user->getEmail(),
            self::FIELD_PASSWORD => $user->getPassword(),
            self::FIELD_VERIFIED => $user->isVerified(),
            self::FIELD_VERIFICATION_TOKEN => $user->getVerificationToken(),
        ]);
    }

    public function getById(int $id): ?ActiveRow
    {
        return $this->explorer->table(self::TABLE_NAME)
            ->get($id);
    }

    public function getByEmail(string $email): ?ActiveRow
    {
        return $this->explorer->table(self::TABLE_NAME)
            ->where(self::FIELD_EMAIL, $email)
            ->fetch();
    }

    public function update(int $id, array $data): int
    {
        return $this->explorer->table(self::TABLE_NAME)
            ->where(self::FIELD_ID, $id)
            ->update($data);
    }

    public function delete(int $id): int
    {
        return $this->explorer->table(self::TABLE_NAME)
            ->where(self::FIELD_ID, $id)
            ->delete();
    }
}