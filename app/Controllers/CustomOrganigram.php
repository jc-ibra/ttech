<?php

namespace App\Controllers;

use App\Models\CustomOrganigramModel;
use App\Models\CustomOrganigramUserModel;
use App\Models\UserModel;

class CustomOrganigram extends BaseController
{
    protected $customOrganigramModel;
    protected $customOrganigramUserModel;
    protected $userModel;

    public function __construct()
    {
        $this->customOrganigramModel     = new CustomOrganigramModel();
        $this->customOrganigramUserModel = new CustomOrganigramUserModel();
        $this->userModel                 = new UserModel();
    }

    /**
     * Listar todos los organigramas personalizados
     */
    public function index(): string
    {
        $organigramas = $this->customOrganigramModel->getOrganigramas();
        
        return   view('shared/header',                                  ['title' => 'Organigramas Personalizados'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/custom-organigram/index',            ['organigramas' => $organigramas])
                .view('shared/footer');
    }

    /**
     * Vista para crear un nuevo organigrama
     */
    public function create(): string
    {
        $users = $this->userModel->getUsers();
        
        return   view('shared/header',                                  ['title' => 'Crear Organigrama'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/custom-organigram/create',           [
                                                                            'users'    => $users,
                                                                            'csrfName' => csrf_token(),
                                                                            'csrfHash' => csrf_hash()
                                                                        ])
                .view('shared/footer');
    }

    /**
     * Guardar nuevo organigrama
     */
    public function store()
    {
        $name        = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $show_in_general = $this->request->getPost('show_in_general');
        $users       = $this->request->getPost('users'); // Array de usuarios
        $created_by  = session('user')->id;

        // Validar datos
        if (empty($name)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'El nombre del organigrama es requerido'
            ]);
        }

        // Crear organigrama
        $organigramaId = $this->customOrganigramModel->createOrganigrama($name, $description, $created_by, $show_in_general == 'on' ? 1 : 0);

        if (!$organigramaId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al crear el organigrama'
            ]);
        }

        // Agregar usuarios al organigrama
        if (!empty($users) && is_array($users)) {
            foreach ($users as $index => $userData) {
                $userId   = $userData['user_id'] ?? null;
                $parentId = !empty($userData['parent_id']) ? $userData['parent_id'] : null;
                $niveles  = $userData['niveles'] ?? 0;

                if ($userId) {
                    $this->customOrganigramUserModel->addUserToOrganigrama(
                        $organigramaId,
                        $userId,
                        $parentId,
                        $niveles,
                        $index
                    );
                }
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Organigrama creado exitosamente',
            'id'      => $organigramaId
        ]);
    }

    /**
     * Vista para editar un organigrama
     */
    public function edit($id)
    {
        $organigrama = $this->customOrganigramModel->getOrganigramas($id);
        
        if (!$organigrama) {
            return redirect()->to('/custom-organigram');
        }

        $organigramaUsers = $this->customOrganigramUserModel->getUsersByOrganigrama($id);
        $allUsers         = $this->userModel->getUsers();

        return   view('shared/header',                                  ['title' => 'Editar Organigrama'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/custom-organigram/edit',             [
                                                                            'organigrama'      => $organigrama,
                                                                            'organigramaUsers' => $organigramaUsers,
                                                                            'allUsers'         => $allUsers,
                                                                            'csrfName'         => csrf_token(),
                                                                            'csrfHash'         => csrf_hash()
                                                                        ])
                .view('shared/footer');
    }

    /**
     * Actualizar organigrama
     */
    public function update()
    {
        $id          = $this->request->getPost('id');
        $name        = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $show_in_general = $this->request->getPost('show_in_general');
        $users       = $this->request->getPost('users'); // Array de usuarios

        // Validar
        if (empty($id) || empty($name)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Datos incompletos'
            ]);
        }

        // Actualizar organigrama
        $updated = $this->customOrganigramModel->updateOrganigrama($id, $name, $description, $show_in_general == 'on' ? 1 : 0);

        if (!$updated) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al actualizar el organigrama'
            ]);
        }

        // Eliminar usuarios anteriores
        $this->customOrganigramUserModel->removeAllUsersFromOrganigrama($id);

        // Agregar usuarios actualizados
        if (!empty($users) && is_array($users)) {
            foreach ($users as $index => $userData) {
                $userId   = $userData['user_id'] ?? null;
                $parentId = !empty($userData['parent_id']) ? $userData['parent_id'] : null;
                $niveles  = $userData['niveles'] ?? 0;

                if ($userId) {
                    $this->customOrganigramUserModel->addUserToOrganigrama(
                        $id,
                        $userId,
                        $parentId,
                        $niveles,
                        $index
                    );
                }
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Organigrama actualizado exitosamente'
        ]);
    }

    /**
     * Eliminar organigrama
     */
    public function delete()
    {
        $id = $this->request->getPost('id');

        if (empty($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID no proporcionado'
            ]);
        }

        $deleted = $this->customOrganigramModel->deleteOrganigrama($id);

        if ($deleted) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Organigrama eliminado exitosamente'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Error al eliminar el organigrama'
        ]);
    }

    /**
     * Clonar un organigrama existente
     */
    public function clone()
    {
        $id      = $this->request->getPost('id');
        $newName = $this->request->getPost('new_name');

        if (empty($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID no proporcionado'
            ]);
        }

        if (empty($newName)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'El nombre del nuevo organigrama es requerido'
            ]);
        }

        // Obtener el organigrama original
        $originalOrganigrama = $this->customOrganigramModel->getOrganigramas($id);
        
        if (!$originalOrganigrama) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Organigrama no encontrado'
            ]);
        }

        // Crear el nuevo organigrama con el nombre proporcionado
        $createdBy      = session('user')->id;
        $newOrganigramaId = $this->customOrganigramModel->createOrganigrama(
            $newName,
            $originalOrganigrama->description,
            $createdBy
        );

        if (!$newOrganigramaId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al crear el nuevo organigrama'
            ]);
        }

        // Obtener todos los usuarios del organigrama original
        $originalUsers = $this->customOrganigramUserModel->getUsersByOrganigrama($id);

        // Clonar los usuarios al nuevo organigrama
        if (!empty($originalUsers)) {
            foreach ($originalUsers as $user) {
                $this->customOrganigramUserModel->addUserToOrganigrama(
                    $newOrganigramaId,
                    $user->user_id,
                    $user->parent_id,
                    $user->niveles,
                    $user->position_order
                );
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Organigrama clonado exitosamente',
            'new_id'  => $newOrganigramaId
        ]);
    }

    /**
     * Vista para visualizar un organigrama
     */
    public function view($id): string
    {
        $organigrama = $this->customOrganigramModel->getOrganigramas($id);
        
        if (!$organigrama) {
            return redirect()->to('/custom-organigram');
        }

        return   view('shared/header',                                  ['title' => $organigrama->name])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/custom-organigram/view',             ['organigrama' => $organigrama])
                .view('shared/footer');
    }

    /**
     * Obtener datos del organigrama en formato JSON
     */
    public function getOrganigramData($id)
    {
        $data = $this->customOrganigramUserModel->getOrganigramChartData($id);
        
        return $this->response->setJSON($data);
    }
   
    public function getOrganigramDataGeneral($id)
    {        
        return $this->customOrganigramUserModel->getOrganigramChartData($id);
    }

    /**
     * Agregar usuario a organigrama (AJAX)
     */
    public function addUser()
    {
        $organigramaId = $this->request->getPost('organigrama_id');
        $userId        = $this->request->getPost('user_id');
        $parentId      = $this->request->getPost('parent_id');
        $niveles       = $this->request->getPost('niveles') ?? 0;

        // Convertir parent_id vacío a null
        if (empty($parentId)) {
            $parentId = null;
        }

        if (empty($organigramaId) || empty($userId)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Datos incompletos'
            ]);
        }

        // Verificar si el usuario ya está en el organigrama
        if ($this->customOrganigramUserModel->isUserInOrganigrama($organigramaId, $userId)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'El usuario ya está en este organigrama'
            ]);
        }

        $added = $this->customOrganigramUserModel->addUserToOrganigrama($organigramaId, $userId, $parentId, $niveles);

        if ($added) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Usuario agregado exitosamente'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Error al agregar el usuario'
        ]);
    }

    /**
     * Eliminar usuario de organigrama (AJAX)
     */
    public function removeUser()
    {
        $id = $this->request->getPost('id');

        if (empty($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID no proporcionado'
            ]);
        }

        $removed = $this->customOrganigramUserModel->removeUserFromOrganigrama($id);

        if ($removed) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Usuario eliminado del organigrama'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Error al eliminar el usuario'
        ]);
    }
}
