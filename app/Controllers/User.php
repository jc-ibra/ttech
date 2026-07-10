<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\HelperUtility;

/**
 * Gestión de USUARIOS: cuentas que inician sesión en la intranet.
 *
 * Los viewers (rol `user`) normalmente los crea Nexus vía API; admin/operator
 * se dan de alta aquí manualmente. Este controlador también expone el perfil
 * de la cuenta con sesión iniciada.
 */
class User extends BaseController
{
    protected $userModel;
    protected $lang;

    public function __construct()
    {
        $this->lang      = \Config\Services::language();
        $this->lang      ->setLocale('es');
        $this->userModel = new UserModel();
    }

    /* ------------------------------------------------------------------ */
    /*  Sección Usuarios (cuentas de login)                                */
    /* ------------------------------------------------------------------ */

    public function index(): string
    {
        $users = $this->userModel->getUsers();
        return   view('shared/header',                      ['title' => 'Usuarios'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/user-account/index',     ['users' => $users])
                .view('shared/footer');
    }

    public function newUser(): string
    {
        return   view('shared/header',                      ['title' => 'Nuevo usuario'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/user-account/create',    [
                                                                'csrfName' => csrf_token(),
                                                                'csrfHash' => csrf_hash(),
                                                            ])
                .view('shared/footer');
    }

    public function editUser($id)
    {
        $user = $this->userModel->getUsers($id);

        if (!$user) {
            return redirect()->to('/usuarios');
        }

        return   view('shared/header',                      ['title' => 'Editar usuario'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/user-account/edit',      [
                                                                'user'     => $user,
                                                                'csrfName' => csrf_token(),
                                                                'csrfHash' => csrf_hash(),
                                                            ])
                .view('shared/footer');
    }

    public function create()
    {
        $name     = $this->request->getPost('name');
        $lastname = $this->request->getPost('lastname');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $rol      = $this->request->getPost('rol');

        if (!$this->checkEmptyField([$name, $email, $password, $rol])) {
            return HelperUtility::redirectWithMessage('/usuarios/new', lang('Errors.missing_fields'));
        }

        if ($this->userModel->getUserByEmail($email)) {
            return HelperUtility::redirectWithMessage('/usuarios/new', lang('Errors.auth_email_exist'));
        }

        $created = $this->userModel->createUser([
            'name'     => $name,
            'lastname' => $lastname,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'rol'      => $rol,
            'active'   => 1,
        ]);

        if ($created) {
            return HelperUtility::redirectWithMessage('/usuarios', lang('Success.user_created'), 'success');
        }

        return HelperUtility::redirectWithMessage('/usuarios/new', lang('Errors.error_try_again_later'));
    }

    public function update()
    {
        $id       = $this->request->getPost('id');
        $name     = $this->request->getPost('name');
        $lastname = $this->request->getPost('lastname');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $rol      = $this->request->getPost('rol');

        if (!$this->checkEmptyField([$id, $name, $email, $rol])) {
            return HelperUtility::redirectWithMessage("/usuarios/edit/$id", lang('Errors.missing_fields'));
        }

        $actual = $this->userModel->getUsers($id);
        if (!$actual) {
            return HelperUtility::redirectWithMessage('/usuarios', lang('Errors.user_not_found'));
        }

        // Los usuarios de origen Nexus se gestionan de forma centralizada en Nexus.
        // La intranet no puede modificar sus datos: son de solo lectura.
        if (!empty($actual->nexus_id)) {
            return HelperUtility::redirectWithMessage("/usuarios/edit/$id", lang('Errors.user_nexus_readonly'));
        }

        if (trim($email) !== trim($actual->email) && $this->userModel->getUserByEmail($email)) {
            return HelperUtility::redirectWithMessage("/usuarios/edit/$id", lang('Errors.auth_email_exist'));
        }

        $data = [
            'name'     => $name,
            'lastname' => $lastname,
            'email'    => $email,
            'rol'      => $rol,
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        if ($this->userModel->update($id, $data)) {
            return HelperUtility::redirectWithMessage("/usuarios/edit/$id", lang('Success.user_updated'), 'success');
        }

        return HelperUtility::redirectWithMessage("/usuarios/edit/$id", lang('Errors.error_try_again_later'));
    }

    public function activeUser()
    {
        $id = $this->request->getPost('id');

        if ($this->isNexusUser($id)) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => lang('Errors.user_nexus_readonly'),
            ]);
        }

        return $this->respondWithCsrf([
            'ok' => $this->userModel->activeUser($id),
        ]);
    }

    public function inactiveUser()
    {
        $current = $this->session->get('user');
        $id      = $this->request->getPost('id');

        if ($id == $current->id) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => lang('Errors.auth_inactive_samne_account'),
            ]);
        }

        if ($this->isNexusUser($id)) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => lang('Errors.user_nexus_readonly'),
            ]);
        }

        return $this->respondWithCsrf([
            'ok' => $this->userModel->inactiveUser($id),
        ]);
    }

    /**
     * ¿La cuenta proviene de Nexus? De ser así, es de solo lectura en la
     * intranet (se gestiona de forma centralizada en Nexus).
     */
    private function isNexusUser($id): bool
    {
        $user = $this->userModel->getUsers($id);
        return $user && !empty($user->nexus_id);
    }

    /* ------------------------------------------------------------------ */
    /*  Perfil de la cuenta con sesión iniciada                            */
    /* ------------------------------------------------------------------ */

    public function profile(): string
    {
        return   view('shared/header',                     ['title' => 'Mi perfil'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/shared/profile/profile-home', [
                                                                'user'     => $this->userModel->getUsers(session('user')->id),
                                                                'csrfName' => csrf_token(),
                                                                'csrfHash' => csrf_hash(),
                                                            ])
                .view('shared/footer');
    }

    public function updatePassword()
    {
        $oldPassword           = $this->request->getPost('oldPassword');
        $password              = $this->request->getPost('password');
        $password_confirmation = $this->request->getPost('password_confirmation');
        $user                  = $this->userModel->getUsers(session('user')->id);

        if (empty($password) || strlen($password) < 6) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => lang('Errors.auth_invalid_password_format'),
            ]);
        }

        if (!password_verify($oldPassword, $user->password)) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => lang('Errors.auth_invalid_credentials'),
            ]);
        }

        if ($password !== $password_confirmation) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => lang('Errors.auth_password_not_match'),
            ]);
        }

        $this->userModel->setNewPassword(session('user')->id, password_hash($password, PASSWORD_BCRYPT));
        $this->refreshSession();

        return $this->respondWithCsrf([
            'ok'      => true,
            'message' => lang('Success.auth_password_updated'),
        ]);
    }

    public function updateProfile()
    {
        $id       = session('user')->id;
        $name     = $this->request->getPost('name');
        $lastname = $this->request->getPost('lastname');

        if (!$this->checkEmptyField([$name])) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => lang('Errors.missing_fields'),
            ]);
        }

        if ($this->userModel->updateProfile($id, $name, $lastname)) {
            $this->refreshSession();
            return $this->respondWithCsrf([
                'ok'      => true,
                'message' => lang('Success.auth_profile_updated'),
            ]);
        }

        return $this->respondWithCsrf([
            'ok'      => false,
            'message' => lang('Errors.error_try_again_later'),
        ]);
    }

    public function updatePhoto()
    {
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {

            $actualUser = $this->userModel->getUsers(session('user')->id);

            if (!in_array($photo->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                return $this->respondWithCsrf([
                    'ok'      => false,
                    'message' => lang('Errors.gral_upload_file_error'),
                ]);
            }

            $uploadPath = ROOTPATH . 'public/uploads/images/profiles';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $newName = $photo->getRandomName();
            $photo->move($uploadPath, $newName);
            $newImage = 'uploads/images/profiles/' . $newName;

            if ($actualUser->photo != 'assets/images/anonimo.jpg' && file_exists(ROOTPATH . 'public/' . $actualUser->photo)) {
                unlink(ROOTPATH . 'public/' . $actualUser->photo);
            }

            if ($this->userModel->setNewPhoto(session('user')->id, $newImage)) {
                $this->refreshSession();
                return $this->respondWithCsrf([
                    'ok'      => true,
                    'message' => lang('Success.auth_photo_updated'),
                ]);
            }

            return $this->respondWithCsrf([
                'ok'      => false,
                'message' => lang('Errors.error_try_again_later'),
            ]);
        }

        return $this->respondWithCsrf([
            'ok'      => false,
            'message' => lang('Errors.auth_invalid_image'),
        ]);
    }

    private function refreshSession()
    {
        $user = $this->userModel->getUsers(session('user')->id);
        $this->session->set('user', $user);
    }
}
