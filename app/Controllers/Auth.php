<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\HelperUtility;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->lang             = \Config\Services::language();
        $this->lang             ->setLocale('es');
        $this->userModel        = new UserModel();
    }

    public function index(): string
    {
        return view('shared/header', ['title' => 'Login']) . view('pages/shared/auth/login');
    }

    public function register()
    {
        // dd($this->request->getPost());
        $email          = $this->request->getPost('email');             // Mandatory
        $name           = $this->request->getPost('name');              // Mandatory
        $lastname       = $this->request->getPost('lastname');          // Mandatory
        $password       = $this->request->getPost('password');          // Mandatory
        $password2      = $this->request->getPost('password-confirm');  // Mandatory
        $rol            = $this->request->getPost('rol');               // Mandatory
        $ocupation      = $this->request->getPost('ocupation');         // Mandatory
        $cellphone      = $this->request->getPost('cellphone');         // Mandatory
        $date_entry     = $this->request->getPost('date_entry');        // Mandatory
        $employee_number= $this->request->getPost('employee_number');   // Mandatory
        $telephone      = $this->request->getPost('telephone');         // Optional
        $department     = $this->request->getPost('department');        // Optional
        $photo          = $this->request->getFile('photo');             // Optional
        $parent         = $this->request->getPost('parent');            // Optional
        $email_secondary= $this->request->getPost('email_secondary');   // Optional
        $ext            = $this->request->getPost('ext');               // Optional
        $hide_emails    = $this->request->getPost('hide_emails');       // Optional
        $show_in_directory = $this->request->getPost('show_in_directory'); // Optional
        $ghost          = $this->request->getPost('ghost');             // Optional
        $area           = $this->request->getPost('area');              // Optional

        if($area == 0 || $area == ''){
            $area = null;
        }
        
        // Validar que los campos no esten vacios
        if(!$this->checkEmptyField([ $email, $name, $lastname, $password, $password2, $rol, $ocupation, $cellphone, $date_entry, $employee_number])){
            return HelperUtility::redirectWithMessage('/user/new', lang('Errors.missing_fields'));
        }

        // Verificar si las contraseñas coinciden
        if ($password !== $password2) {
            return HelperUtility::redirectWithMessage('/user/new', lang('Errors.auth_password_not_match'));
        }

        // Verificar si el usuario ya existe
        if ($this->userModel->getUserByEmail($email)) {
            return HelperUtility::redirectWithMessage('/user/new', lang('Errors.auth_email_exist'));
        }

        // Verificar si la imagen es valida
        if(!$photo->isValid()){
            $photoURL = 'assets/images/anonimo.jpg';

        }else{

            // Subir la imagen
            $this->handlePhotoUpload($photo);

            // Guardar la imagen
            $newName = $photo->getName();
            $photoURL = 'uploads/images/profiles/' . $newName;
        }

        // Encriptar la contraseña
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Si tiene ghost setearlo
        if($ghost == 'on'){

            // Crear nuevo usuario ghost
            $ghost_user = $this->userModel->createUser($name, $lastname, $email."_ghost", $passwordHash, $photoURL, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, 1, null, null, 1, $area);

            // Crear usuario final
            if ($this->userModel->createUser($name, $lastname, $email, $passwordHash, $photoURL, $telephone, $rol, $ocupation, $department, $ghost_user, $email_secondary, $cellphone, $ext, $date_entry, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, $ghost_user, $parent, 1, $area)) {
                return HelperUtility::redirectWithMessage('/user/new', 'Usuario creado exitosamente', 'success');
            }

        }else{
            // Crear nuevo usuario
            if ($this->userModel->createUser($name, $lastname, $email, $passwordHash, $photoURL, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, null, null, null, $area)) {
                return HelperUtility::redirectWithMessage('/user/new', 'Usuario creado exitosamente', 'success');
            }
        }


        // En caso de error
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
        $user       = $this->session->get('user'); 
        $id         = $this->request->getPost('id');

        // Verificar si se quiere auto desactivar
        if($id == $user->id){
            return $this->respondWithCsrf([
                'ok'     => false,
                'error'  => lang('Errors.auth_inactive_samne_account'),
            ]);
        }
    
        return $this->respondWithCsrf([
            'ok' => $this->userModel->inactiveUser($id),
        ]);
        
    }

    public function updateUser()
    {
        // dd($this->request->getPost());
        $id             = $this->request->getPost('id');                // Mandatory
        $email          = $this->request->getPost('email');             // Mandatory
        $name           = $this->request->getPost('name');              // Mandatory
        $lastname       = $this->request->getPost('lastname');          // Mandatory
        $rol            = $this->request->getPost('rol');               // Mandatory
        $ocupation      = $this->request->getPost('ocupation');         // Mandatory
        $cellphone      = $this->request->getPost('cellphone');         // Mandatory
        $date_entry     = $this->request->getPost('date_entry');        // Mandatory
        $employee_number= $this->request->getPost('employee_number');   // Mandatory
        $date_discharge = $this->request->getPost('date_discharge');    // Optional
        $password       = $this->request->getPost('password');          // Optional
        $telephone      = $this->request->getPost('telephone');         // Optional
        $department     = $this->request->getPost('department');        // Optional
        $photo          = $this->request->getFile('photo');             // Optional
        $parent         = $this->request->getPost('parent');            // Optional
        $email_secondary= $this->request->getPost('email_secondary');   // Optional
        $ext            = $this->request->getPost('ext');               // Optional
        $hide_emails    = $this->request->getPost('hide_emails');       // Optional
        $show_in_directory = $this->request->getPost('show_in_directory'); // Optional
        $ghost          = $this->request->getPost('ghost');             // Optional
        $niveles        = $this->request->getPost('niveles');           // Optional
        $area           = $this->request->getPost('area');              // Optional

        if($area == 0 || $area == ''){
            $area = null;
        }


        // Validar que los campos no esten vacios
        if(!$this->checkEmptyField([ $id, $email, $name, $lastname, $rol, $ocupation, $cellphone, $date_entry, $employee_number])){
            return HelperUtility::redirectWithMessage('/user/new', lang('Errors.missing_fields'));
        }

        // Encontrar al usuario actual
        $actualUser = $this->userModel->getUsers($id);
        $newImage   = $actualUser->photo;

        // Verificar si el usuario existe
        if (!$actualUser) {
            return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Errors.user_not_found'));
        }

        // En caso de modificar el correo validar si el correo existe
        if(trim($email) != trim($actualUser->email)){
            if ($this->userModel->getUserByEmail($email)) {
            return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Errors.auth_email_exist'.$email));
            }
        }

        // En caso de llegar date_discharge desactivar usuario
        if($date_discharge != ""){
            $this->userModel->inactiveUser($id);
            // Buscar usuario ghost y desactivarlo
            if($actualUser->has_ghost != null){
                $this->userModel->inactiveUser($actualUser->has_ghost); 
            }
        }

        // En caso de modificar la imagen eliminar la anterior y guardar la nueva
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {

            // Subir la imagen
            if (!$this->handlePhotoUpload($photo)) {

                // En caso de error
                return HelperUtility::redirectWithMessage('/user/new', lang('Errors.gral_upload_file_error'));
            }

            if($actualUser->photo != 'assets/images/anonimo.jpg'){
                if (file_exists(ROOTPATH . 'public/' . $actualUser->photo)) {
                    unlink(ROOTPATH . 'public/' . $actualUser->photo);
                }
            }

            $newName    = $photo->getName();
            $newImage   = 'uploads/images/profiles/' . $newName;
        }

        // En caso de modificar la contraseña setear la nueva
        if($password != ""){
            $this->setNewPassword($id, $password);
        }

        // Validar si llega ghost
        if( $ghost == 'on'){

            // Verificar si no tiene ghost
            if($actualUser->has_ghost == null){

                // Crear nuevo usuario ghost
                $ghost_user = $this->userModel->createUser(
                    $actualUser->name, 
                    $actualUser->lastname, 
                    $actualUser->email."_ghost", 
                    $actualUser->password, 
                    $actualUser->photo, 
                    $actualUser->telephone, 
                    $actualUser->rol, 
                    $actualUser->ocupation, 
                    $actualUser->department,
                    $parent,                // parent
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

                // Actualizar usuario
                if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $ghost_user, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, $ghost_user, $parent, 1, $area)) {
                    return HelperUtility::redirectWithMessage("/user/edit/$id", 'Usuario actualizado exitosamente', 'success');
                }
            }else{

                // Actualizr niveles en el ghost
                $this->userModel->update($actualUser->parent, ['niveles' => $niveles ]);

                // Si cambio el parent actualizarlo en el ghost
                if($parent != $actualUser->parent){
                    $this->userModel->setNewParent($actualUser->has_ghost, $parent);
                    if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $actualUser->parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, $actualUser->ghost, $actualUser->has_ghost, $parent, $niveles, $area)) {

                        return HelperUtility::redirectWithMessage("/user/edit/$id", 'Usuario actualizado exitosamente', 'success');
                    }
                }

                // Actualizar usuario
                if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $actualUser->parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, $actualUser->ghost, $actualUser->has_ghost, $actualUser->real_parent, $niveles, $area)) {
                    return HelperUtility::redirectWithMessage("/user/edit/$id", 'Usuario actualizado exitosamente', 'success');
                }
            }
        }else{

            // Verificar si tiene ghost
            if($actualUser->has_ghost != null){

                // Obtener el ghost
                $ghost_user = $this->userModel->getUsers($actualUser->has_ghost);
                
                // Eliminar al ghost
                $this->userModel->deleteGhost($actualUser->has_ghost);

                // Elimina el ghost y cambia de parent
                if($actualUser->parent != $parent){
                    // Actualizar usuario
                    if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, null, null, null, $area)) {
                        return HelperUtility::redirectWithMessage("/user/edit/$id", 'Usuario actualizado exitosamente', 'success');
                    }
                }

                // Actualizar usuario
                if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $ghost_user->parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, null, null, null, $niveles, $area)) { //!AQUI VALIDAR SI CAMBIO EL ULTIMO NULL POR $NIVELES
                    return HelperUtility::redirectWithMessage("/user/edit/$id", 'Usuario actualizado exitosamente', 'success');
                }
            }

            // Actualizar usuario
            if ($this->updateUserData($id, $name, $lastname, $email, $newImage, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails == 'on' ? 1 : 0, $show_in_directory == 'on' ? 1 : 0, $actualUser->ghost, $actualUser->has_ghost, null, $niveles, $area)) {
                return HelperUtility::redirectWithMessage("/user/edit/$id", 'Usuario actualizado exitosamente', 'success');
            }
        }

        return HelperUtility::redirectWithMessage("/user/edit/$id", lang('Errors.error_try_again_later'));
    }

    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user     = $this->userModel->getUserByEmail($email);

        // Verificar si el usuario existe, la contraseña es válida y si esta activo
        if ($user && password_verify($password, $user->password) && $user->active == 1) {
            $this->session->set("user", $user);
            $this->userModel->setLoginUpdate($user->id);

            if($user->rol == 'operator'){
                return redirect()->to(base_url('/organization'));    
            }

            return redirect()->to(base_url('/trantor-technologies'));
        }

        // En caso credenciales invalidas
        return HelperUtility::redirectWithMessage('/', lang('Errors.auth_invalid_credentials'));
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }
    
    // Función auxiliar para actualizar password a un usuario
    private function setNewPassword(int $id, string $password): bool
    {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        return $this->userModel->setNewPassword($id, $passwordHash);
    }

    // Función auxiliar para actualizar a un usuario
    private function updateUserData(int $id, string $name, string $lastname, string $email, string $photo, string $telephone, string $rol, string $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails, $show_in_directory, $ghost, $has_ghost, $real_parent, $niveles, $area): bool
    {
        return $this->userModel->updateUser($id, $name, $lastname, $email, $photo, $telephone, $rol, $ocupation, $department == 0 ? null : $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails, $show_in_directory, $ghost, $has_ghost, $real_parent, $niveles, $area);
    }

    private function handlePhotoUpload($photo) : bool
    {
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {

            if (in_array($photo->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {

                $uploadPath = ROOTPATH . 'public/uploads/images/profiles';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $newName = $photo->getRandomName();
                $photo->move($uploadPath, $newName);

                return true;
            } else {
                return false;
            }
        } else {

            return false;
        }
    }
}
