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

            return redirect()->to(base_url('/trantor-informa'));
        }

        return HelperUtility::redirectWithMessage('/', lang('Errors.auth_invalid_credentials'));
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }
}
