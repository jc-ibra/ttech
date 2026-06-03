<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\HelperUtility;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        \Config\Services::language()->setLocale('es');
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        return view('shared/header', ['title' => 'Login']) . view('pages/shared/auth/login');
    }

    public function register()
    {
        $email             = $this->request->getPost('email');
        $name              = $this->request->getPost('name');
        $lastname          = $this->request->getPost('lastname');
        $password          = $this->request->getPost('password');
        $password2         = $this->request->getPost('password-confirm');
        $rol               = $this->request->getPost('rol');
        $ocupation         = $this->request->getPost('ocupation');
        $cellphone         = $this->request->getPost('cellphone');
        $date_entry        = $this->request->getPost('date_entry');
        $employee_number   = $this->request->getPost('employee_number');
        $telephone         = $this->request->getPost('telephone');
        $department        = $this->request->getPost('department');
        $photo             = $this->request->getFile('photo');
        $parent            = $this->request->getPost('parent');
        $email_secondary   = $this->request->getPost('email_secondary');
        $ext               = $this->request->getPost('ext');
        $hide_emails       = $this->request->getPost('hide_emails');
        $show_in_directory = $this->request->getPost('show_in_directory');
        $ghost             = $this->request->getPost('ghost');
        $area              = $this->request->getPost('area') ?: null;

        if (!$this->checkEmptyField([$email, $name, $lastname, $password, $password2, $rol, $ocupation, $cellphone, $date_entry, $employee_number])) {
            return HelperUtility::redirectWithMessage('/user/new', lang('Errors.missing_fields'));
        }

        if ($password !== $password2) {
            return HelperUtility::redirectWithMessage('/user/new', lang('Errors.auth_password_not_match'));
        }

        if ($this->userModel->getUserByEmail($email)) {
            return HelperUtility::redirectWithMessage('/user/new', lang('Errors.auth_email_exist'));
        }

        $photoURL = 'assets/images/anonimo.jpg';

        if ($photo->isValid()) {
            $photoPath = $this->handlePhotoUpload($photo);
            if ($photoPath === false) {
                return HelperUtility::redirectWithMessage('/user/new', lang('Errors.gral_upload_file_error'));
            }
            $photoURL = $photoPath;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        if ($ghost == 'on') {
            $ghost_user = $this->userModel->createUser($name, $lastname, $email . '_ghost', $passwordHash, $photoURL, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, 1, null, null, 1, $area);

            if ($this->userModel->createUser($name, $lastname, $email, $passwordHash, $photoURL, $telephone, $rol, $ocupation, $department, $ghost_user, $email_secondary, $cellphone, $ext, $date_entry, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, $ghost_user, $parent, 1, $area)) {
                return HelperUtility::redirectWithMessage('/user/new', lang('Success.user_created'), 'success');
            }
        } else {
            if ($this->userModel->createUser($name, $lastname, $email, $passwordHash, $photoURL, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, null, null, null, $area)) {
                return HelperUtility::redirectWithMessage('/user/new', lang('Success.user_created'), 'success');
            }
        }

        return HelperUtility::redirectWithMessage('/user/new', lang('Errors.error_try_again_later'));
    }

    public function activeUser()
    {
        $id = $this->request->getPost('id');

        return $this->respondWithCsrf([
            'ok' => $this->userModel->activeUser($id),
        ]);
    }

    public function inactiveUser()
    {
        $user = $this->session->get('user');
        $id   = $this->request->getPost('id');

        if ($id == $user->id) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => lang('Errors.auth_inactive_samne_account'),
            ]);
        }

        return $this->respondWithCsrf([
            'ok' => $this->userModel->inactiveUser($id),
        ]);
    }

    public function updateUser()
    {
        $id              = $this->request->getPost('id');
        $email           = $this->request->getPost('email');
        $name            = $this->request->getPost('name');
        $lastname        = $this->request->getPost('lastname');
        $rol             = $this->request->getPost('rol');
        $ocupation       = $this->request->getPost('ocupation');
        $cellphone       = $this->request->getPost('cellphone');
        $date_entry      = $this->request->getPost('date_entry');
        $employee_number = $this->request->getPost('employee_number');
        $date_discharge  = $this->request->getPost('date_discharge');
        $password        = $this->request->getPost('password');
        $telephone       = $this->request->getPost('telephone');
        $department      = $this->request->getPost('department');
        $photo           = $this->request->getFile('photo');
        $parent          = $this->request->getPost('parent');
        $email_secondary = $this->request->getPost('email_secondary');
        $ext             = $this->request->getPost('ext');
        $hide_emails     = $this->request->getPost('hide_emails');
        $show_in_directory = $this->request->getPost('show_in_directory');
        $ghost           = $this->request->getPost('ghost');
        $niveles         = $this->request->getPost('niveles');
        $area            = $this->request->getPost('area') ?: null;

        if (!$this->checkEmptyField([$id, $email, $name, $lastname, $rol, $ocupation, $cellphone, $date_entry, $employee_number])) {
            return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Errors.missing_fields'));
        }

        $actualUser = $this->userModel->getUsers($id);

        if (!$actualUser) {
            return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Errors.user_not_found'));
        }

        if (trim($email) !== trim($actualUser->email)) {
            if ($this->userModel->getUserByEmail($email)) {
                return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Errors.auth_email_exist'));
            }
        }

        if ($date_discharge != '') {
            $this->userModel->inactiveUser($id);
            if ($actualUser->has_ghost != null) {
                $this->userModel->inactiveUser($actualUser->has_ghost);
            }
        }

        $newImage = $actualUser->photo;

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoPath = $this->handlePhotoUpload($photo);

            if ($photoPath === false) {
                return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Errors.gral_upload_file_error'));
            }

            if ($actualUser->photo !== 'assets/images/anonimo.jpg' && file_exists(ROOTPATH . 'public/' . $actualUser->photo)) {
                unlink(ROOTPATH . 'public/' . $actualUser->photo);
            }

            $newImage = $photoPath;
        }

        if ($password != '') {
            $this->setNewPassword($id, $password);
        }

        if ($ghost == 'on') {
            if ($actualUser->has_ghost == null) {
                $ghost_user = $this->userModel->createUser(
                    $actualUser->name,
                    $actualUser->lastname,
                    $actualUser->email . '_ghost',
                    $actualUser->password,
                    $actualUser->photo,
                    $actualUser->telephone,
                    $actualUser->rol,
                    $actualUser->ocupation,
                    $actualUser->department,
                    $parent,
                    $actualUser->email_secondary,
                    $actualUser->cellphone,
                    $actualUser->ext,
                    $actualUser->date_entry,
                    $actualUser->employee_number,
                    $actualUser->hide_emails,
                    1,
                    null,
                    null,
                    1,
                    $area
                );

                if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $ghost_user, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, $ghost_user, $parent, 1, $area)) {
                    return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Success.user_updated'), 'success');
                }
            } else {
                $this->userModel->update($actualUser->parent, ['niveles' => $niveles]);

                if ($parent != $actualUser->parent) {
                    $this->userModel->setNewParent($actualUser->has_ghost, $parent);
                    if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $actualUser->parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, $actualUser->ghost, $actualUser->has_ghost, $parent, $niveles, $area)) {
                        return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Success.user_updated'), 'success');
                    }
                }

                if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $actualUser->parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, $actualUser->ghost, $actualUser->has_ghost, $actualUser->real_parent, $niveles, $area)) {
                    return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Success.user_updated'), 'success');
                }
            }
        } else {
            if ($actualUser->has_ghost != null) {
                $ghost_user = $this->userModel->getUsers($actualUser->has_ghost);
                $this->userModel->deleteGhost($actualUser->has_ghost);

                if ($actualUser->parent != $parent) {
                    if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, null, null, null, $area)) {
                        return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Success.user_updated'), 'success');
                    }
                }

                if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $ghost_user->parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, null, null, $niveles, $area)) {
                    return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Success.user_updated'), 'success');
                }
            }

            if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, $actualUser->ghost, $actualUser->has_ghost, null, $niveles, $area)) {
                return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Success.user_updated'), 'success');
            }
        }

        return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Errors.error_try_again_later'));
    }

    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user     = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user->password) && $user->active == 1) {
            $this->session->set('user', $user);
            $this->userModel->setLoginUpdate($user->id);

            if ($user->rol == 'operator') {
                return redirect()->to(base_url('/organization'));
            }

            return redirect()->to(base_url('/trantor-technologies'));
        }

        return HelperUtility::redirectWithMessage('/', lang('Errors.auth_invalid_credentials'));
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }

    private function setNewPassword(int $id, string $password): bool
    {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        return $this->userModel->setNewPassword($id, $passwordHash);
    }

    private function updateUserData(int $id, string $name, string $lastname, string $email, string $photo, string $telephone, string $rol, string $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails, $show_in_directory, $ghost, $has_ghost, $real_parent, $niveles, $area): bool
    {
        return $this->userModel->updateUser($id, $name, $lastname, $email, $photo, $telephone, $rol, $ocupation, $department == 0 ? null : $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails, $show_in_directory, $ghost, $has_ghost, $real_parent, $niveles, $area);
    }

    /**
     * Sube la foto de perfil y retorna la ruta relativa lista para guardar en DB.
     * Retorna false si el archivo es inválido o el MIME no está permitido.
     */
    private function handlePhotoUpload($photo): string|false
    {
        if (!$photo || !$photo->isValid() || $photo->hasMoved()) {
            return false;
        }

        if (!in_array($photo->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            return false;
        }

        $uploadPath = ROOTPATH . 'public/uploads/images/profiles';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $photo->getRandomName();
        $photo->move($uploadPath, $newName);

        return 'uploads/images/profiles/' . $newName;
    }
}
