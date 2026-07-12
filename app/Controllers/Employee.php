<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\OcupationModel;
use App\Models\DepartmentModel;
use App\Models\AreaModel;
use App\Controllers\HelperUtility;

/**
 * Gestión de EMPLEADOS: alta, edición y estructura para los organigramas.
 * Los empleados NO inician sesión (eso son los Usuarios). Por eso aquí no se
 * manejan rol ni contraseña de acceso.
 */
class Employee extends BaseController
{
    protected $employeeModel;
    protected $lang;
    protected $ocupationModel;
    protected $departmentModel;
    protected $areaModel;

    public function __construct()
    {
        $this->lang             = \Config\Services::language();
        $this->lang             ->setLocale('es');
        $this->employeeModel    = new EmployeeModel();
        $this->ocupationModel   = new OcupationModel();
        $this->departmentModel  = new DepartmentModel();
        $this->areaModel        = new AreaModel();
    }

    public function index(): string
    {
        // El listado se carga vía AJAX (server-side). Aquí sólo pasamos los
        // catálogos que alimentan los filtros de la tabla.
        return   view('shared/header',                  ['title'        => 'Empleados'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/user/user',          [
                                                            'departments' => $this->departmentModel->getDepartments(),
                                                            'areas'       => $this->areaModel->getAreas(),
                                                            'ocupations'  => $this->ocupationModel->getOcupations(),
                                                        ])
                .view('shared/footer');
    }

    /**
     * Endpoint server-side de DataTables para el listado de empleados.
     * Devuelve JSON con paginación, búsqueda, orden y filtros resueltos en SQL.
     */
    public function datatable()
    {
        $params = $this->dataTableParams();

        $draw          = (int) $this->request->getGet('draw');
        $recordsTotal  = $this->employeeModel->countDataTableTotal();
        $recordsFiltered = $this->employeeModel->countDataTable($params);
        $employees     = $this->employeeModel->getDataTable($params);

        $data = [];
        foreach ($employees as $e) {
            $editUrl = base_url('empleados/edit/' . $e->id);
            $photo   = base_url($e->photo);
            $status  = $e->active == 1
                ? '<span class="badge-success">Activo</span>'
                : '<span class="badge-critical">Inactivo</span>';

            $nameCell = '<div class="d-flex align-items-center gap-2">'
                . '<img class="rounded-circle" width="32" height="32" alt="' . esc($e->name, 'attr') . '" src="' . esc($photo, 'attr') . '" style="object-fit:cover;">'
                . '<a class="fw-semibold text-primary text-decoration-none" href="' . esc($editUrl, 'attr') . '">'
                . esc($e->name . ' ' . $e->lastname) . '</a></div>';

            $data[] = [
                $nameCell,
                esc($e->employee_number ?? ''),
                esc($e->email ?? ''),
                esc($e->ocupation_name ?? '-'),
                esc($e->department_name ?? '-'),
                esc($e->area_name ?? '-'),
                esc(trim($e->parent_name ?? '') !== '' ? $e->parent_name : '-'),
                $status,
            ];
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }

    /**
     * Exporta a CSV todos los empleados que cumplen los filtros/búsqueda activos.
     */
    public function export()
    {
        $params           = $this->dataTableParams();
        $params['length'] = -1; // sin paginación: todos los resultados filtrados
        $params['start']  = 0;

        $employees = $this->employeeModel->getDataTable($params);

        $filename = 'empleados_' . date('Y-m-d_His') . '.csv';

        // Construimos el CSV en memoria y lo devolvemos como cuerpo de la respuesta
        // para que CodeIgniter emita los headers de descarga correctamente.
        $output = fopen('php://temp', 'r+');
        // BOM para que Excel reconozca UTF-8 (acentos).
        fwrite($output, "\xEF\xBB\xBF");
        fputcsv($output, ['No. Empleado', 'Nombre', 'Apellido', 'E-mail', 'Puesto', 'Departamento', 'Área', 'Jefe directo', 'Estatus']);

        foreach ($employees as $e) {
            fputcsv($output, [
                $e->employee_number,
                $e->name,
                $e->lastname,
                $e->email,
                $e->ocupation_name,
                $e->department_name,
                $e->area_name,
                trim($e->parent_name ?? ''),
                $e->active == 1 ? 'Activo' : 'Inactivo',
            ]);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    /**
     * Normaliza los parámetros que envía DataTables (server-side) + filtros propios.
     */
    private function dataTableParams(): array
    {
        $search = $this->request->getGet('search');
        $order  = $this->request->getGet('order');

        return [
            'start'       => $this->request->getGet('start'),
            'length'      => $this->request->getGet('length'),
            'search'      => is_array($search) ? ($search['value'] ?? '') : '',
            'orderColumn' => (is_array($order) && isset($order[0]['column'])) ? $order[0]['column'] : 0,
            'orderDir'    => (is_array($order) && isset($order[0]['dir'])) ? $order[0]['dir'] : 'asc',
            'department'  => $this->request->getGet('department'),
            'area'        => $this->request->getGet('area'),
            'ocupation'   => $this->request->getGet('ocupation'),
            'status'      => $this->request->getGet('status'),
        ];
    }

    public function newUser(): string
    {
        return   view('shared/header',                  ['title'        => 'Nuevo empleado'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/user/user-new',      [
                                                            'ocupations'   => $this->ocupationModel->getOcupations(),
                                                            'users'        => $this->employeeModel->getUsers(),
                                                            'departments'  => $this->departmentModel->getDepartments(),
                                                            'areas'        => $this->areaModel->getAreas(),
                                                            'csrfName'     => csrf_token(),
                                                            'csrfHash'     => csrf_hash()
                                                        ])
                .view('shared/footer');
    }

    public function editUser($id)
    {
        $data['csrfName']   = csrf_token();
        $data['csrfHash']   = csrf_hash();
        $data['user']       = $this->employeeModel->getUsers($id);
        $data['users']      = $this->employeeModel->getUsers();
        $data['ocupations'] = $this->ocupationModel->getOcupations();
        $data['departments'] = $this->departmentModel->getDepartments();
        $data['areas']      = $this->areaModel->getAreas();

        if ($data['user']) {
            return
                view('shared/header',                       ['title'        => "Editar empleado"])
               .view('shared/sidebar')
               .view('shared/navbar')
               .view('pages/admin/user/user-edit',          ['data'        => $data])
               .view('shared/footer');
        } else {
            return redirect()->to('/empleados');
        }
    }

    public function register()
    {
        $email             = $this->request->getPost('email');
        $name              = $this->request->getPost('name');
        $lastname          = $this->request->getPost('lastname');
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

        if (!$this->checkEmptyField([$email, $name, $lastname, $ocupation, $cellphone, $date_entry, $employee_number])) {
            return HelperUtility::redirectWithMessage('/empleados/new', lang('Errors.missing_fields'));
        }

        $photoURL = 'assets/images/anonimo.jpg';

        if ($photo->isValid()) {
            $photoPath = $this->handlePhotoUpload($photo);
            if ($photoPath === false) {
                return HelperUtility::redirectWithMessage('/empleados/new', lang('Errors.gral_upload_file_error'));
            }
            $photoURL = $photoPath;
        }

        // Los empleados no inician sesión: rol/contraseña son valores marcadores.
        $rol          = 'employee';
        $passwordHash = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);

        if ($ghost == 'on') {
            $ghost_user = $this->employeeModel->createUser($name, $lastname, $email . '_ghost', $passwordHash, $photoURL, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, 1, null, null, 1, $area);

            if ($this->employeeModel->createUser($name, $lastname, $email, $passwordHash, $photoURL, $telephone, $rol, $ocupation, $department, $ghost_user, $email_secondary, $cellphone, $ext, $date_entry, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, $ghost_user, $parent, 1, $area)) {
                return HelperUtility::redirectWithMessage('/empleados/new', lang('Success.user_created'), 'success');
            }
        } else {
            if ($this->employeeModel->createUser($name, $lastname, $email, $passwordHash, $photoURL, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, null, null, null, $area)) {
                return HelperUtility::redirectWithMessage('/empleados/new', lang('Success.user_created'), 'success');
            }
        }

        return HelperUtility::redirectWithMessage('/empleados/new', lang('Errors.error_try_again_later'));
    }

    public function activeUser()
    {
        $id = $this->request->getPost('id');

        return $this->respondWithCsrf([
            'ok' => $this->employeeModel->activeUser($id),
        ]);
    }

    public function inactiveUser()
    {
        $id = $this->request->getPost('id');

        return $this->respondWithCsrf([
            'ok' => $this->employeeModel->inactiveUser($id),
        ]);
    }

    public function updateUser()
    {
        $id              = $this->request->getPost('id');
        $email           = $this->request->getPost('email');
        $name            = $this->request->getPost('name');
        $lastname        = $this->request->getPost('lastname');
        $ocupation       = $this->request->getPost('ocupation');
        $cellphone       = $this->request->getPost('cellphone');
        $date_entry      = $this->request->getPost('date_entry');
        $employee_number = $this->request->getPost('employee_number');
        $date_discharge  = $this->request->getPost('date_discharge');
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

        if (!$this->checkEmptyField([$id, $email, $name, $lastname, $ocupation, $cellphone, $date_entry, $employee_number])) {
            return HelperUtility::redirectWithMessage("/empleados/edit/$id", lang('Errors.missing_fields'));
        }

        $actualUser = $this->employeeModel->getUsers($id);

        if (!$actualUser) {
            return HelperUtility::redirectWithMessage("/empleados/edit/$id", lang('Errors.user_not_found'));
        }

        // Los empleados no inician sesión: se conserva el rol marcador actual.
        $rol = $actualUser->rol ?? 'employee';

        if ($date_discharge != '') {
            $this->employeeModel->inactiveUser($id);
            if ($actualUser->has_ghost != null) {
                $this->employeeModel->inactiveUser($actualUser->has_ghost);
            }
        }

        $newImage = $actualUser->photo;

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoPath = $this->handlePhotoUpload($photo);

            if ($photoPath === false) {
                return HelperUtility::redirectWithMessage("/empleados/edit/$id", lang('Errors.gral_upload_file_error'));
            }

            if ($actualUser->photo !== 'assets/images/anonimo.jpg' && file_exists(ROOTPATH . 'public/' . $actualUser->photo)) {
                unlink(ROOTPATH . 'public/' . $actualUser->photo);
            }

            $newImage = $photoPath;
        }

        if ($ghost == 'on') {
            if ($actualUser->has_ghost == null) {
                $ghost_user = $this->employeeModel->createUser(
                    $actualUser->name,
                    $actualUser->lastname,
                    $actualUser->email . '_ghost',
                    $actualUser->password,
                    $actualUser->photo,
                    $actualUser->telephone,
                    $rol,
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
                    return HelperUtility::redirectWithMessage("/empleados/edit/$id", lang('Success.user_updated'), 'success');
                }
            } else {
                $this->employeeModel->update($actualUser->parent, ['niveles' => $niveles]);

                if ($parent != $actualUser->parent) {
                    $this->employeeModel->setNewParent($actualUser->has_ghost, $parent);
                    if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $actualUser->parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, $actualUser->ghost, $actualUser->has_ghost, $parent, $niveles, $area)) {
                        return HelperUtility::redirectWithMessage("/empleados/edit/$id", lang('Success.user_updated'), 'success');
                    }
                }

                if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $actualUser->parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, $actualUser->ghost, $actualUser->has_ghost, $actualUser->real_parent, $niveles, $area)) {
                    return HelperUtility::redirectWithMessage("/empleados/edit/$id", lang('Success.user_updated'), 'success');
                }
            }
        } else {
            if ($actualUser->has_ghost != null) {
                $ghost_user = $this->employeeModel->getUsers($actualUser->has_ghost);
                $this->employeeModel->deleteGhost($actualUser->has_ghost);

                if ($actualUser->parent != $parent) {
                    if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, null, null, null, $area)) {
                        return HelperUtility::redirectWithMessage("/empleados/edit/$id", lang('Success.user_updated'), 'success');
                    }
                }

                if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $ghost_user->parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, null, null, $niveles, $area)) {
                    return HelperUtility::redirectWithMessage("/empleados/edit/$id", lang('Success.user_updated'), 'success');
                }
            }

            if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, $actualUser->ghost, $actualUser->has_ghost, null, $niveles, $area)) {
                return HelperUtility::redirectWithMessage("/empleados/edit/$id", lang('Success.user_updated'), 'success');
            }
        }

        return HelperUtility::redirectWithMessage("/empleados/edit/$id", lang('Errors.error_try_again_later'));
    }

    public function reingresarUsuario()
    {
        $id         = $this->request->getPost('id');
        $date_entry = $this->request->getPost('date_entry');

        if (!$this->checkEmptyField([$id, $date_entry])) {
            return $this->respondWithCsrf([
                'ok'     => false,
                'error'  => lang('Errors.missing_fields'),
            ]);
        }

        if ($this->employeeModel->reingresarUsuario($id, $date_entry)) {

            $actualUser = $this->employeeModel->getUsers($id);
            if ($actualUser->has_ghost != null) {
                $this->employeeModel->reingresarUsuario($actualUser->has_ghost, $date_entry);
            }

            return $this->respondWithCsrf([
                'ok'      => true,
                'message' => lang('Success.user_reingreso_success'),
            ]);
        }

        return $this->respondWithCsrf([
            'ok'      => false,
            'message' => lang('Errors.error_try_again_later'),
        ]);
    }

    private function updateUserData(int $id, string $name, string $lastname, string $email, string $photo, string $telephone, string $rol, string $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails, $show_in_directory, $ghost, $has_ghost, $real_parent, $niveles, $area): bool
    {
        return $this->employeeModel->updateUser($id, $name, $lastname, $email, $photo, $telephone, $rol, $ocupation, $department == 0 ? null : $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails, $show_in_directory, $ghost, $has_ghost, $real_parent, $niveles, $area);
    }

    /**
     * Sube la foto del empleado y retorna la ruta relativa lista para guardar.
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
