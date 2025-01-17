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
    private const FIELD_CREATED_AT = 'created_at';
    private const FIELD_MODIFIED_AT = 'modified_at';

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

    public function getById(int $id): ?User
    {
        return $this->mapToUser(
            $this->explorer->table(self::TABLE_NAME)
                ->get($id)
        );
    }

    public function getByEmail(string $email): ?User
    {
        return $this->mapToUser(
            $this->explorer->table(self::TABLE_NAME)
                ->where(self::FIELD_EMAIL, $email)
                ->fetch()
        );
    }

    public function findByVerificationToken(string $token): ?User
    {
        return $this->mapToUser(
            $this->explorer->table(self::TABLE_NAME)
                ->where(self::FIELD_VERIFICATION_TOKEN, $token)
                ->fetch()
        );
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

    public function saveVerificationToken(string $email, string $token): void
    {
        $this->explorer->table(self::TABLE_NAME)
            ->where(self::FIELD_EMAIL, $email)
            ->update([self::FIELD_VERIFICATION_TOKEN => $token]);
    }

    public function verifyUser(int $userId): void
    {
        $this->explorer->table(self::TABLE_NAME)
            ->where(self::FIELD_ID, $userId)
            ->update([
                self::FIELD_VERIFIED => 1,
                self::FIELD_VERIFICATION_TOKEN => null,
            ]);
    }

    private function mapToUser(?ActiveRow $activeRow): ?User
    {
        if (!$activeRow) {
            return null;
        }

        return (new User())
            ->setId($activeRow->{self::FIELD_ID})
            ->setUsername($activeRow->{self::FIELD_USERNAME})
            ->setEmail($activeRow->{self::FIELD_EMAIL})
            ->setPassword($activeRow->{self::FIELD_PASSWORD})
            ->setVerified((bool) $activeRow->{self::FIELD_VERIFIED})
            ->setVerificationToken($activeRow->{self::FIELD_VERIFICATION_TOKEN})
            ->setCreatedAt($activeRow->{self::FIELD_CREATED_AT})
            ->setModifiedAt($activeRow->{self::FIELD_MODIFIED_AT});
    }
}