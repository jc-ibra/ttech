<?php

namespace App\Controllers;

/**
 * Hub de Catálogos: agrupa el acceso a Puestos, Departamentos y Áreas.
 */
class Catalogos extends BaseController
{
    public function index(): string
    {
        return   view('shared/header',                  ['title' => 'Catálogos'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/catalogos/catalogos')
                .view('shared/footer');
    }
}
