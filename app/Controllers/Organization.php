<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\DepartmentModel;
use App\Models\AreaModel;
use App\Models\CustomOrganigramModel;
use App\Controllers\CustomOrganigram;

class Organization extends BaseController
{

    protected $userModel;
    protected $departmentModel;
    protected $areaModel;
    protected $customOrganigram;
    protected $customOrganigramModel;

    public function __construct()
    {
        $this->userModel              = new EmployeeModel();
        $this->departmentModel        = new DepartmentModel();
        $this->areaModel              = new AreaModel();
        $this->customOrganigram       = new CustomOrganigram();
        $this->customOrganigramModel  = new CustomOrganigramModel();
    }

    public function index(): string
    {
        
        return   view('shared/header',                              ['title'        => 'Organigrama'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/organization/organization',      [
                                                                        'departments'          => $this->departmentModel->getDepartments(),
                                                                        'areas'                => $this->areaModel->getAreas(),
                                                                        'generalOrganigramas'  => $this->customOrganigramModel->getGeneralOrganigramas()
                                                                    ])
                .view('shared/footer');
    }

    public function getOrganization()
    {
        
        return json_encode($this->userModel->getOrganizationChart());
    }

    public function getOrganizationByDepartment($departmentId)
    {
        return json_encode($this->userModel->getOrganizationChartByDepartment($departmentId));
    }

    public function getOrganizationByArea($areaId)
    {
        return json_encode($this->userModel->getOrganizationChartByArea($areaId));
    }

    public function getGeneralOrganigram($organigramId)
    {
        return json_encode($this->customOrganigram->getOrganigramDataGeneral($organigramId));
    }
}
