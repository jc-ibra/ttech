<?php

namespace App\Controllers;

use App\Models\LandingContentModel;

class TrantorTechnologies extends BaseController
{

    public function __construct()
    {
    }

    public function index(): string
    {
        $content = (new LandingContentModel())->getContent();

        return   view('shared/header',                              ['title'     => 'Trantor Technologies'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/user/trantor-technologies/trantor-technologies', ['c' => $content])
                .view('shared/footer');
    }
}
