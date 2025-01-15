<?php

namespace App\UseCase\SignUpUseCase\Exception;

use Nette\Schema\ValidationException;

class UserAlreadyExistsException extends ValidationException
{

}