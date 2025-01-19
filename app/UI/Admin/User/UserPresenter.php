<?php

namespace App\UI\Admin\User;

use App\Entity\User;
use App\Exception\UseCaseException;
use App\Exception\UserAlreadyExistsException;
use App\Exception\UserDoesNotExistException;
use App\Form\User\AddUserFormFactory;
use App\Form\User\EditUserFormFactory;
use App\Form\User\UserFormFactory;
use App\Mapper\FormData\UserFormData;
use App\UseCase\DeleteUserUseCase\DeleteUserRequest;
use App\UseCase\DeleteUserUseCase\DeleteUserUseCase;
use App\UseCase\EditUserUseCase\EditUserRequest;
use App\UseCase\EditUserUseCase\EditUserUseCase;
use App\UseCase\ManageUsersAdminUseCase\ManageUsersAdminRequest;
use App\UseCase\ManageUsersAdminUseCase\ManageUsersAdminUseCase;
use App\UseCase\SignUpUseCase\SignUpRequest;
use App\UseCase\SignUpUseCase\SignUpUseCase;
use App\UseCase\UpdateUserUseCase\UpdateUserRequest;
use App\UseCase\UpdateUserUseCase\UpdateUserUseCase;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Http\Session;

class UserPresenter extends Presenter
{
    private int $itemsPerPage = 2;
    private ?User $userEntity = null;

    public function __construct(
        private ManageUsersAdminUseCase $manageUsersAdminUseCase,
        private SignUpUseCase $signUpUseCase,
        private EditUserUseCase $editUserUseCase,
        private UpdateUserUseCase $updateUserUseCase,
        private DeleteUserUseCase $deleteUserUseCase,
        private AddUserFormFactory $addUserFormFactory,
        private EditUserFormFactory $editUserFormFactory,
        Session $session
    ) {
        parent::__construct();

        $session->start();
    }

    public function renderDefault(int $page = 1): void
    {
        try {
            $response = $this->manageUsersAdminUseCase->execute(
                new ManageUsersAdminRequest($page, $this->itemsPerPage)
            );

            $this->template->users = $response->getUsers();
            $this->template->paginator = $response->getPaginator();
        } catch (UseCaseException $e) {
            $this->flashMessage('Something went wrong.', 'danger');
        }
    }

    public function actionEdit(int $id): void
    {
        try {
            $response = $this->editUserUseCase->execute(
                new EditUserRequest($id)
            );
            $this->userEntity = $response->getUser();
        } catch (UserDoesNotExistException $e) {
            $this->flashMessage($e->getMessage(), 'danger');
            $this->redirect('default');
        } catch (UseCaseException $e) {
            $this->flashMessage('Something went wrong.', 'danger');
            $this->redirect('default');
        }
    }

    protected function createComponentAddUserForm(): Form
    {
        $form = $this->addUserFormFactory->create();

        $form->addSubmit('submit', 'Create User');
        $form->onSuccess[] = [$this, 'createUser'];
        $form->addProtection();

        return $form;
    }

    public function createUser(Form $form, UserFormData $formData): void
    {
        try {
            $this->signUpUseCase->execute(new SignUpRequest($formData));

            $this->flashMessage(
                'User has been created successfully. Please verify email.',
                'success'
            );
            $this->redirect('default');
        } catch (UserAlreadyExistsException $e) {
            $form[UserFormFactory::FIELD_EMAIL]->addError($e->getMessage());
        } catch (UseCaseException $e) {
            $this->flashMessage('Something went wrong.', 'danger');
        }
    }

    protected function createComponentEditUserForm(): Form
    {
        $form = $this->editUserFormFactory->create();

        $form->setDefaults([
            'username' => $this->userEntity->getUsername(),
            'email' => $this->userEntity->getEmail(),
        ]);

        $form->addSubmit('submit', 'Update User');
        $form->onSuccess[] = [$this, 'updateUser'];
        $form->addProtection();

        return $form;
    }

    public function updateUser(Form $form, UserFormData $formData): void
    {
        try {
            $this->updateUserUseCase->execute(
                new UpdateUserRequest($formData, $this->userEntity)
            );

            $this->flashMessage('User has been updated successfully.', 'success');
            $this->redirect('default');
        } catch (UserAlreadyExistsException $e) {
            $form[UserFormFactory::FIELD_EMAIL]->addError($e->getMessage());
        } catch (UseCaseException $e) {
            $this->flashMessage('Something went wrong.', 'danger');
        }
    }

    public function handleDelete(int $id): void
    {
        try {
            $this->deleteUserUseCase->execute(new DeleteUserRequest($id));
            $this->flashMessage('User has been deleted successfully.', 'success');
        } catch (UseCaseException $e) {
            $this->flashMessage('Something went wrong.', 'danger');
        }

        $this->redirect('this');
    }
}