# Architecture

## Stack

- **Framework:** CodeIgniter 4 (PHP ^8.1)
- **Database:** MySQL / MariaDB (driver: MySQLi, charset: utf8mb4)
- **Frontend:** Bootstrap 5 + jQuery + ApexCharts + Tabler Icons
- **Auth:** Session-based con RBAC (3 roles: `admin`, `operator`, `user`)
- **Locale/Timezone:** `es-MX` / `America/Mexico_City`

---

## Request Flow

```
HTTP Request
  → public/index.php
  → app/Config/Routes.php        (route matching + filter assignment)
  → app/Filters/AuthFilter.php   (autenticación + autorización por rol)
  → Controller                   (lógica de negocio)
  → Model                        (acceso a DB vía Query Builder)
  → View                         (HTML renderizado)
  → HTTP Response
```

---

## Roles y Grupos de Rutas

`Routes.php` define cinco grupos protegidos por el filtro `auth`:

| Grupo | Filtro | Acceso |
|-------|--------|--------|
| Público | ninguno | Login, registro |
| General | `auth:admin,operator,user` | Alertas, perfil, organigrama |
| Admin + Operator | `auth:admin,operator` | Gestión de usuarios, documentos admin |
| Admin + User | `auth:admin,user` | Feed, documentos, directorio |
| Solo Admin | `auth:admin` | Áreas, departamentos, ocupaciones |

### AuthFilter (`app/Filters/AuthFilter.php`)

```php
// Sin sesión → redirige a /
if (!session('user')) return redirect()->to('/');

// Rol no permitido → redirige a /404
if ($arguments && !in_array(session('user')->rol, $arguments)) {
    return redirect()->to('/404');
}
```

---

## Capas de la Aplicación

### Controllers (`app/Controllers/`)

Todos extienden `BaseController`, que provee:

- `checkEmptyField(array $fields): bool` — valida que ningún campo esté vacío
- `respondWithCsrf(array $data)` — agrega `csrf_token` y `csrf_name` a respuestas JSON
- `$session` — instancia de sesión inicializada en `initController()`

`HelperUtility.php` es una clase estática auxiliar con:

- `redirectWithMessage($route, $message, $type)` — redirect con flash message
- `getCsrfToken(): array` — retorna nombre y hash del token CSRF
- `encrypt(string)` / `decrypt(string)` — cifrado vía `Services::encrypter()`

### Models (`app/Models/`)

Todos extienden `CodeIgniter\Model`. Cada modelo define su propia lógica de acceso a datos con métodos nombrados explícitamente (sin repositorios ni ORMs externos).

### Views (`app/Views/`)

Partials compartidos en `Views/shared/`. Las páginas se organizan por rol y módulo:

```
Views/
├── shared/          header, footer, navbar, sidebar (+ variantes por rol)
├── pages/
│   ├── admin/       user, area, department, ocupation, organization,
│   │                suggestion, custom-organigram, documents
│   ├── user/        directorio, documents, trantor-informa,
│   │                quejas-sugerencias, trantor-technologies
│   └── shared/      auth, alerts, profile
└── errors/          html/ (404, exception, production), cli/
```

### Services (`app/Services/`)

`ProvisioningService.php` — integración con sistemas externos al crear empleados:

- `createGlpiUser(array $data)` — registra usuario en GLPI (helpdesk IT)
- `createMailcowMailbox(array $data)` — crea buzón en Mailcow

Patrón: fire-and-forget. Los fallos se loguean pero **no revierten** la creación del usuario en DB.

Variables de entorno requeridas: `GLPI_URL`, `GLPI_APP_TOKEN`, `GLPI_USER_TOKEN`, `MAILCOW_URL`, `MAILCOW_API_KEY`.

### Exceptions (`app/Exceptions/`)

`ProvisioningException` — extiende `RuntimeException`. Se lanza cuando falla el aprovisionamiento externo; permite degradación elegante en el controlador.

### Helpers (`app/Helpers/`)

`user_helper.php` — expone `get_user_data($key)`: accede a propiedades del usuario autenticado desde `session('user')`.

---

## Módulos del Sistema

| Módulo | Controller | Model(s) |
|--------|-----------|----------|
| Autenticación / Perfil | `Auth.php`, `User.php` | `UserModel` |
| Feed social | `TrantorInforma.php` | `FeedModel`, `FeedCommentModel` |
| Documentos | `Documents.php`, `Files.php` | `DocumentModel`, `DocumentFileModel`, `FileModel` |
| Organigramas | `Organization.php`, `CustomOrganigram.php` | `CustomOrganigramModel`, `CustomOrganigramUserModel` |
| Alertas | `Alert.php` | `AlertModel` |
| Sugerencias | `Suggestion.php` | `SuggestionModel` |
| Directorio | `Directorio.php` | `UserModel` |
| Catálogos | `Area.php`, `Department.php`, `Ocupation.php` | `AreaModel`, `DepartmentModel`, `OcupationModel` |

---

## Patrones Clave

### Composición de vistas

Los controladores concatenan partials con el operador `.`:

```php
return view('shared/header', ['title' => 'Título'])
     . view('shared/sidebar')
     . view('shared/navbar')
     . view('pages/seccion/pagina', $data)
     . view('shared/footer');
```

No existe herencia de layouts. Cada controlador ensambla manualmente su página.

### Respuestas AJAX

Estructura consistente con CSRF incluido:

```php
return $this->respondWithCsrf([
    'ok'      => true,
    'message' => lang('Success.xxx'),  // en éxito
    'error'   => lang('Errors.xxx'),   // en fallo
]);
```

GET requests de solo lectura pueden omitir `respondWithCsrf` y usar `$this->response->setJSON()` directamente.

### HTML renderizado en AJAX

Cuando el cliente necesita insertar nuevo contenido en el DOM, el controlador retorna HTML pre-renderizado:

```php
return $this->respondWithCsrf([
    'ok'      => true,
    'comment' => view('pages/user/trantor-informa/trantor-informa-comment', ['comment' => $comment]),
]);
```

### Árbol recursivo en JSON

El módulo de documentos construye una estructura anidada con referencias PHP (`&`) para responder a jsTree:

```php
foreach ($documents as $doc) { $map[$doc->id] = [..., 'children' => []]; }
foreach ($documents as $doc) {
    if ($doc->parent) $map[$doc->parent]['children'][] = &$map[$doc->id];
    else $root[] = &$map[$doc->id];
}
```

### Carga condicional de assets

`header.php` y `footer.php` usan `strpos(uri_string(), 'ruta')` para cargar JS/CSS específicos por módulo (DataTables, FilePond, jstree, Select2, OrgChart) solo donde se necesitan.

### Sistema Ghost

Los usuarios "ghost" son nodos fantasma en el organigrama. Cuando `ghost == 'on'`, se crea un usuario espejo con sufijo `_ghost` en el email. Los campos `parent`, `real_parent`, `has_ghost` y `niveles` gestionan la jerarquía.

### Alertas broadcast

Al publicar un feed o documento, se crea una alerta para **cada usuario activo**:

```php
$users = $this->userModel->getUsers();
foreach ($users as $user) {
    $this->alertModel->createAlert('feed_new', lang('Alerts.new_feed'), $user->id, json_encode($payload));
}
```

---

## Sesión

- Login: `$this->session->set('user', $userObject)` — almacena el objeto completo del usuario
- Acceso: `session('user')->id`, `session('user')->rol`
- Refresh: después de mutaciones (foto, contraseña, perfil) llamar `refreshSession()` para sincronizar sesión con DB

---

## Subida de Archivos

| Tipo | Controller | Ruta | Destino |
|------|-----------|------|---------|
| Imágenes feed | `Files::handleUpload()` | `POST /files/upload` | `public/uploads/images/` |
| Documentos | `Files::handleUploadFile()` | `POST /files/upload/file` | `public/uploads/files/` |
| Foto de perfil | `Auth/User::handlePhotoUpload()` | `POST /profile/update/photo` | `public/uploads/images/profiles/` |

Flujo estándar: validar `isValid()` → verificar MIME → generar nombre random → mover → guardar referencia en DB.

---

## Deployment

- **Docker/CapRover:** `captain-definition` usa `php:8.2-apache`, habilita `mod_rewrite`, DocumentRoot en `/var/www/html/public`
- **Apache:** DocumentRoot apuntando a `public/`. El `.htaccess` raíz redirige ahí; `public/.htaccess` maneja el front controller de CI4
