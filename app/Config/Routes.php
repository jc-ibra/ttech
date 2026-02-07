<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
*/

/**************************************
 * FUERA DE FILTRO DE AUTENTICACIÓN
***************************************/
$routes->group('', function($routes) {

    /* Auth */
    $routes->get('/', 'Auth::index');
    $routes->post('/auth/login', 'Auth::login');

});

/**************************************
 * ADMINISTRADOR y OPERADOR y USUARIO
***************************************/
$routes->group('', ['filter' => 'auth:admin,operator,user'], function($routes) {

    /* Auth */
    $routes->get('/auth/logout', 'Auth::logout');

    /* Alerts */
    $routes->get('/alerts', 'Alert::index');
    $routes->get('/alerts/unread', 'Alert::getUnreadAlerts');
    $routes->post('/alerts/read/(:num)', 'Alert::markAlertAsRead/$1');

    /* Profile */
    $routes->get('/profile', 'User::profile');
    $routes->post('/profile/update/password', 'User::updatePassword');
    $routes->post('/profile/update/photo', 'User::updatePhoto');
    $routes->post('/profile/update/profile', 'User::updateProfile');

    /* Organization */
    $routes->get('/organization', 'Organization::index');
    $routes->get('/organization/data', 'Organization::getOrganization');
    $routes->get('/organization/data/department/(:num)', 'Organization::getOrganizationByDepartment/$1');
    $routes->get('/organization/data/area/(:num)', 'Organization::getOrganizationByArea/$1');
    $routes->get('/organization/data/general/(:num)', 'Organization::getGeneralOrganigram/$1');

    
});


/**************************************
 * ADMINISTRADOR y OPERADOR 
***************************************/
$routes->group('', ['filter' => 'auth:admin,operator'], function($routes) {

    /* Auth */
    $routes->post('/auth/register', 'Auth::register');
    $routes->post('/auth/user/update', 'Auth::updateUser');
    $routes->post('/auth/user/active', 'Auth::activeUser');
    $routes->post('/auth/user/inactive', 'Auth::inactiveUser');
    $routes->post('/auth/user/reactivate', 'User::reingresarUsuario');
    
    /* Users */
    $routes->get('/user', 'User::index');
    $routes->get('/user/new', 'User::newUser');
    $routes->get('/user/edit/(:num)', 'User::editUser/$1');

    /* Custom Organigram */
    $routes->get('/custom-organigram', 'CustomOrganigram::index');
    $routes->get('/custom-organigram/create', 'CustomOrganigram::create');
    $routes->get('/custom-organigram/view/(:num)', 'CustomOrganigram::view/$1');
    $routes->get('/custom-organigram/data/(:num)', 'CustomOrganigram::getOrganigramData/$1');
    
});


/**************************************
 * ROL USUARIO Y ADMINISTRADOR
***************************************/
$routes->group('', ['filter' => 'auth:admin,user'], function($routes) {

    /* Trantor Technologies */
    $routes->get('/trantor-technologies', 'TrantorTechnologies::index');

    /* Quejas y Sugerencias */
    $routes->get('/quejas-sugerencias', 'Suggestion::index');
    $routes->post('/suggestion/create', 'Suggestion::createSuggestion');
    
    /* Documentos */
    $routes->get('/documentos', 'Documents::index');
    $routes->get('/documents/folder', 'Documents::getFolders');
    $routes->get('/documents/file/(:num)', 'Documents::getDocumentFiles/$1');

    /* TrantorInforma */
    $routes->get('/trantor-informa', 'TrantorInforma::index');
    $routes->get('/trantor-informa/text', 'TrantorInforma::getFeedText');
    $routes->get('/trantor-informa/image', 'TrantorInforma::getFeedImage');
    $routes->get('/trantor-informa/file', 'TrantorInforma::getFeedFile');

    $routes->post('/trantor-informa/like/add', 'TrantorInforma::newFeedLike');
    $routes->post('/trantor-informa/like/remove', 'TrantorInforma::removeFeedLike');
    $routes->post('/trantor-informa/comment/add', 'TrantorInforma::createComment');
    $routes->get('/trantor-informa/feed/comments/(:num)', 'TrantorInforma::getComments/$1');

    /* Directorio */
    $routes->get('/directorio', 'Directorio::index');

});


/**************************************
 * ADMINISTRADOR
***************************************/
$routes->group('', ['filter' => 'auth:admin'], function($routes) {
    
    /* Ocupation */
    $routes->get('/ocupation', 'Ocupation::index');
    $routes->get('/ocupation/new', 'Ocupation::newOcupation');
    $routes->get('/ocupation/edit/(:num)', 'Ocupation::editOcupation/$1');
    $routes->post('/ocupation/new', 'Ocupation::createOcupation');
    $routes->post('/ocupation/edit', 'Ocupation::updateOcupation');
    $routes->post('/ocupation/delete', 'Ocupation::deleteOcupation');
    
    /* Area */
    $routes->get('/area', 'Area::index');
    $routes->get('/area/new', 'Area::newArea');
    $routes->get('/area/edit/(:num)', 'Area::editArea/$1');
    $routes->post('/area/new', 'Area::createArea');
    $routes->post('/area/edit', 'Area::updateArea');
    $routes->post('/area/delete', 'Area::deleteArea');

    /* Department */
    $routes->get('/department', 'Department::index');
    $routes->get('/department/new', 'Department::newDepartment');
    $routes->get('/department/edit/(:num)', 'Department::editDepartment/$1');
    $routes->post('/department/new', 'Department::createDepartment');
    $routes->post('/department/edit', 'Department::updateDepartment');
    $routes->post('/department/delete', 'Department::deleteDepartment');

    /* Custom Organigram - Admin routes */
    $routes->get('/custom-organigram/edit/(:num)', 'CustomOrganigram::edit/$1');
    $routes->post('/custom-organigram/store', 'CustomOrganigram::store');
    $routes->post('/custom-organigram/update', 'CustomOrganigram::update');
    $routes->post('/custom-organigram/delete', 'CustomOrganigram::delete');
    $routes->post('/custom-organigram/clone', 'CustomOrganigram::clone');
    $routes->post('/custom-organigram/add-user', 'CustomOrganigram::addUser');
    $routes->post('/custom-organigram/remove-user', 'CustomOrganigram::removeUser');
    
    /* Documents */
    $routes->get('/documents', 'Documents::documents');
    $routes->post('/documents/folder/rename', 'Documents::updateFolder');
    $routes->post('/documents/folder/move', 'Documents::updateFolderParent');
    $routes->post('/documents/folder/create', 'Documents::newFolder');
    $routes->post('/documents/folder/delete', 'Documents::deleteFolder');
    $routes->post('/documents/file/create', 'Documents::createFile');
    $routes->post('/documents/file/delete', 'Documents::deleteFile');

    /* Files */
    $routes->post('/files/upload', 'Files::handleUpload');
    $routes->post('/files/upload/file', 'Files::handleUploadFile');
    $routes->delete('/files/revert', 'Files::handleDelete');

    /* TrantorInforma */
    $routes->post('/trantor-informa/new', 'TrantorInforma::store');
    $routes->post('/trantor-informa/delete', 'TrantorInforma::deleteFeed');
    $routes->post('/trantor-informa/update', 'TrantorInforma::updateFeed');
    $routes->post('/trantor-informa/comment/delete', 'TrantorInforma::deleteComment');

    /* Quejas y Sugerencias */
    $routes->get('/suggestions', 'Suggestion::adminIndex');
    $routes->get('/suggestions/get', 'Suggestion::getSuggestions');
    $routes->get('/suggestions/get/(:num)', 'Suggestion::getSuggestion/$1');
    $routes->post('/suggestions/unread', 'Suggestion::setStatusNew');
    $routes->post('/suggestions/read', 'Suggestion::setStatusOpen');
    $routes->post('/suggestions/delete', 'Suggestion::deleteSuggestion');
});