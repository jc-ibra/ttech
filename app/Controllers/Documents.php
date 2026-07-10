<?php

namespace App\Controllers;

use App\Models\FeedModel;
use App\Models\AlertModel;
use App\Models\UserModel;
use App\Models\DocumentModel;
use App\Models\FileModel;
use App\Models\DocumentFileModel;

class Documents extends BaseController
{
    
    protected $documentModel;
    protected $filesModel;
    protected $documentFilesModel;
    protected $feedModel;
    protected $alertModel;
    protected $userModel;

    public function __construct()
    {
        $this->documentModel            = new DocumentModel();
        $this->filesModel               = new FileModel();
        $this->documentFilesModel       = new DocumentFileModel();
        $this->feedModel                = new FeedModel();
        $this->alertModel               = new AlertModel();
        $this->userModel                = new UserModel();
        $this->lang                     = \Config\Services::language();
        $this->lang                     ->setLocale('es');
    }

    public function index(): string
    {
        
        return   view('shared/header',                      ['title'     => 'Documentos'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/user/documents/documents',     [
                                                                'csrfName'  => csrf_token(),    
                                                                'csrfHash'  => csrf_hash()
                                                            ])
                .view('shared/footer');
    }
    
    public function documents(): string
    {
        
        return   view('shared/header',                      ['title'     => 'Documentos'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/documents/documents',    [
                                                                'csrfName'  => csrf_token(),    
                                                                'csrfHash'  => csrf_hash()
                                                            ])
                .view('shared/footer');
    }

    public function getFolders()
    {
        $documents = $this->documentModel->getDocumentFolders();
        
        if($documents){
            $response = [];
            $folders = [];
            
            foreach ($documents as $document) {
                $folders[$document->id] = [
                    'id'        => $document->id,
                    'text'      => $document->name,
                    'icon'      => $document->icon,
                    'type'      => $document->type,
                    'children'  => []
                ];
            }

            foreach ($documents as $document) {
                if ($document->parent != null) {
                    $folders[$document->parent]['children'][] = &$folders[$document->id];
                } else {
                    $response[] = &$folders[$document->id];
                }
            }

            usort($response, function($a, $b) {
                return $a['id'] <=> $b['id'];
            });

            return $this->response->setJSON($response);
        }

        return $this->response->setJSON([]);
    }

    public function updateFolder()
    {
        $id     = $this->request->getPost('id');
        $name   = $this->request->getPost('name');

        if($this->documentModel->updateDocumentFolder($id, $name)){
            return $this->respondWithCsrf([
                'ok'        => true,
                'message'   => lang('Success.folder_updated')
            ]);
        }
            

        return $this->respondWithCsrf([
            'ok'        => false,
            'message'   => lang('Errors.error_try_again_later')
        ]);
    }

    public function newFolder()
    {
        $parent     = $this->request->getPost('parent');
        $name       = $this->request->getPost('name');

        // Validar que los campos no esten vacios
        if(!$this->checkEmptyField([$parent, $name])){
            return $this->respondWithCsrf([
                'ok'        => false,
                'message'   => lang('Errors.missing_fields'),
            ]);
        }

        // Si se crea la carpeta
        if($this->documentModel->createDocumentFolder($name, 'folder', 'ti ti-folder', $parent, null)){
            return $this->respondWithCsrf([
                'ok'        => true,
                'message'   => lang('Success.folder_created')
            ]);
        }

        // Error al crear la carpeta
        return $this->respondWithCsrf([
            'ok'        => false,
            'message'   => lang('Errors.error_try_again_later')
        ]);

    }

    public function deleteFolder()
    {
        $id = $this->request->getPost('id');

        // Validar que el campo no este vacio
        if(!$this->checkEmptyField([$id])){
            return $this->respondWithCsrf([
                'ok'        => false,
                'message'   => lang('Errors.missing_fields'),
            ]);
        }

        // Si se elimina la carpeta
        if($this->documentModel->deleteDocumentFolder($id)){
            return $this->respondWithCsrf([
                'ok'        => true,
                'message'   => lang('Success.folder_deleted')
            ]);
        }

        // Error al eliminar la carpeta
        return $this->respondWithCsrf([
            'ok'        => false,
            'message'   => lang('Errors.error_try_again_later')
        ]);
    }

    public function updateFolderParent()
    {
        $id     = $this->request->getPost('id');
        $parent = $this->request->getPost('parent');

        // Validar que los campos no esten vacios
        if(!$this->checkEmptyField([$id, $parent])){
            return $this->respondWithCsrf([
                'ok'        => false,
                'message'   => lang('Errors.missing_fields'),
            ]);
        }

        // Si se actualiza la carpeta
        if($this->documentModel->updateDocumentFolderParent($id, $parent)){
            return $this->respondWithCsrf([
                'ok'        => true,
                'message'   => lang('Success.folder_moved')
            ]);
        }

        // Error al actualizar la carpeta
        return $this->respondWithCsrf([
            'ok'        => false,
            'message'   => lang('Errors.error_try_again_later')
        ]);
    }

    public function createFile()
    {
        $document   = $this->request->getPost('document');
        $name       = $this->request->getPost('name');
        $file       = $this->request->getPost('file');
        $publish    = filter_var($this->request->getPost('publish'), FILTER_VALIDATE_BOOLEAN);

        // Validar que los campos no esten vacios
        if(!$this->checkEmptyField([$document, $name, $file])){
            return $this->respondWithCsrf([
                'ok'        => false,
                'message'   => lang('Errors.missing_fields'),
            ]);
        }

        // Obtener el File de la BDD
        $file_object = $this->filesModel->getFileByPath($file);

        if(!$file_object){
            return $this->respondWithCsrf([
                'ok'        => false,
                'message'   => lang('Errors.error_try_again_later')
            ]);
        }

        // Crear Folder
        if($this->documentFilesModel->createDocumentFile($name, $file_object->id, $document, session('user')->id)){

            if($publish === true){

                // Crear Feed
                $author         = session('user')->id;
                $body_content   = lang('Feed.new_file') . ': ' . $name;
                $file_path      = json_encode($file);
                $image_path     = null;
    
                // Crear registro en BDD (comentarios habilitados por defecto)
                $feed = $this->feedModel->createFeed($author, $body_content, $file_path, $image_path, 1);
    
                // Crea una alerta a todos los usuarios del nuevo feed publicado
                $users = $this->userModel->getUsers();
                foreach($users as $user){
                    $this->alertModel->createAlert('feed_new', lang('Alerts.new_feed'), $user->id, json_encode(
                        [
                            'feed'          => $feed,
                            'author'        => $author,
                            'author_name'   => session()->get('user')->name,
                            'content'       => $body_content,
                            'file'          => $file_path,
                            'images'        => $image_path,
                        ]
                    ));
                }
            }

            // Respuesta
            return $this->respondWithCsrf([
                'ok'        => true,
                'message'   => lang('Success.file_created'),
            ]);
        }

        // Error al crear la carpeta
        return $this->respondWithCsrf([
            'ok'        => false,
            'message'   => lang('Errors.error_try_again_later')
        ]);
    }

    public function getDocumentFiles($document)
    {

        // Validar que los campos no esten vacios
        if(!$this->checkEmptyField([$document])){
            return $this->respondWithCsrf([
                'ok'        => false,
                'message'   => lang('Errors.missing_fields'),
            ]);
        }

        $files = $this->documentFilesModel->getDocumentFileByDocument($document);

        //Si el arreglo es 0 envia template vacio
        if(count($files) == 0){
            return $this->respondWithCsrf([
                'ok'            => true,
                'files'         => view('pages/admin/documents/document-item-empty'),
                'files_object'  => $files
            ]);
        }else{

            // Iterar Archivos
            $filesList = [];
            foreach($files as $key => $file){
                $filesList[$key] = view('pages/admin/documents/document-item', ['file' => $file]);
            }
    
            return $this->respondWithCsrf([
                'ok'            => true,
                'files'         => $filesList,
                'files_object'  => $files
            ]);
        }

        return $this->respondWithCsrf([
            'ok'        => true,
            'files'     => $files
        ]);
    }

    public function deleteFile()
    {
        $id = $this->request->getPost('id');

        // Validar que los campos no esten vacios
        if(!$this->checkEmptyField([$id])){
            return $this->respondWithCsrf([
                'ok'        => false,
                'message'   => lang('Errors.missing_fields'),
            ]);
        }

        // Si se elimina la carpeta
        if($this->documentFilesModel->deleteDocumentFile($id)){
            return $this->respondWithCsrf([
                'ok'        => true,
                'message'   => lang('Success.file_deleted')
            ]);
        }

        // Error al eliminar la carpeta
        return $this->respondWithCsrf([
            'ok'        => false,
            'message'   => lang('Errors.error_try_again_later')
        ]);
    }
}
