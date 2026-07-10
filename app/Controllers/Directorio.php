<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class Directorio extends BaseController
{

    protected $userModel;

    public function __construct()
    {
        $this->userModel        = new EmployeeModel();
    }

    public function index(): string
    {
        
        return   view('shared/header',                              ['title'        => 'Organigrama'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/user/directorio/directorio',           ['users'          => $this->userModel->getDirectory()])
                .view('shared/footer');
    }
}
