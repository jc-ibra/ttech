# Conventions

## Nomenclatura

| Artefacto | Convención | Ejemplo |
|-----------|-----------|---------|
| Controladores | `PascalCase.php` | `TrantorInforma.php` |
| Modelos | `PascalCaseModel.php` | `FeedCommentModel.php` |
| Vistas (páginas) | `kebab-case.php` | `trantor-informa.php` |
| Tablas DB | `snake_case` plural | `feed_comments` |
| Columnas DB | `snake_case` | `created_at`, `likes_count` |
| Métodos | `camelCase` | `getFeedComments()` |
| Variables PHP | `camelCase` | `$feedModel`, `$newName` |
| Namespace controllers | `App\Controllers` | |
| Namespace models | `App\Models` | |
| Namespace services | `App\Services` | |
| Namespace exceptions | `App\Exceptions` | |

---

## Modelos

### Estructura base obligatoria

```php
class XxxModel extends Model
{
    protected $table            = 'tabla_plural';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';      // siempre object, nunca array
    protected $useSoftDeletes   = true;           // true por defecto
    protected $allowedFields    = [...];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}
```

**Excepción a soft deletes:** modelos de relaciones pivot (ej. `CustomOrganigramUserModel`) pueden usar `$useSoftDeletes = false`.

### Nombrado de métodos

| Acción | Prefijo | Ejemplo |
|--------|---------|---------|
| Leer uno / varios | `get*` | `getFeeds($id)`, `getUsers()` |
| Crear | `create*` | `createFeed(...)` |
| Actualizar campo específico | `set*` | `setNewPassword($id, $pass)` |
| Actualizar general | `update*` | `updateProfile($id, ...)` |
| Eliminar | `delete*` | `deleteFeed($id)` |

### Queries complejos

Encapsular JOINs y selects recurrentes en un método privado auxiliar y reutilizarlo:

```php
private function baseFeedQuery(): Builder
{
    return $this->select('feed.*, users.name, ...')
                ->join('users', 'users.id = feed.author')
                ->join('feed_comments', ..., 'left')
                ->groupBy('feed.id');
}

public function getFeeds($id = null): array|object|null
{
    $query = $this->baseFeedQuery();
    if ($id) $query->where('feed.id', $id);
    return $id ? $query->first() : $query->findAll();
}
```

---

## Controladores

### Estructura del constructor

```php
public function __construct()
{
    $this->xxxModel = new XxxModel();
    // cargar todos los modelos necesarios aquí
}
```

### Respuestas para peticiones de página (GET)

Siempre componer con la secuencia header → sidebar → navbar → contenido → footer:

```php
return view('shared/header', ['title' => 'Nombre de Página'])
     . view('shared/sidebar')
     . view('shared/navbar')
     . view('pages/seccion/nombre-pagina', $data)
     . view('shared/footer');
```

### Respuestas AJAX

Usar `respondWithCsrf()` en toda respuesta a peticiones POST/DELETE:

```php
// Éxito
return $this->respondWithCsrf([
    'ok'      => true,
    'message' => lang('Success.clave'),
]);

// Error
return $this->respondWithCsrf([
    'ok'    => false,
    'error' => lang('Errors.clave'),
]);
```

GET de solo lectura puede usar `$this->response->setJSON()` directamente (sin CSRF).

### Validación de campos

```php
if (!$this->checkEmptyField([$campo1, $campo2])) {
    return $this->respondWithCsrf(['ok' => false, 'error' => lang('Errors.missing_fields')]);
}
```

### Redirects con mensaje flash

```php
return HelperUtility::redirectWithMessage('/ruta', 'Mensaje', 'success');
// Tipos: 'success', 'error', 'message'
```

### Subida de archivos

```php
if (!$file->isValid() || $file->hasMoved()) { /* error */ }
if (!in_array($file->getClientMimeType(), $allowedMimeTypes)) { /* error */ }

$newName = $file->getRandomName();
$file->move($uploadPath, $newName);
```

- Usar siempre `getRandomName()` — nunca el nombre original del cliente
- Validar MIME explícitamente con lista blanca
- Guardar referencia en DB con nombre original, ruta relativa, MIME y tamaño
- Al reemplazar foto: eliminar el archivo anterior si no es la imagen por defecto (`assets/images/anonimo.jpg`)

### Refresh de sesión

Llamar `refreshSession()` después de cualquier mutación de datos del usuario autenticado (foto, contraseña, perfil):

```php
private function refreshSession(): void
{
    $user = $this->userModel->getUsers(session('user')->id);
    $this->session->set('user', $user);
}
```

---

## Vistas

### Datos pasados a una vista

Pasar un array asociativo como segundo argumento de `view()`:

```php
view('pages/admin/user/user', [
    'csrfName' => csrf_token(),
    'csrfHash' => csrf_hash(),
    'users'    => $users,
])
```

Siempre incluir `csrfName` y `csrfHash` en vistas con formularios AJAX.

### Output de variables

```php
<?= esc($variable) ?>          // datos del usuario u origen externo
<?= $variable ?>               // datos internos seguros (ej. base_url, ID)
```

### Acceso a sesión en vistas

```php
session('user')->name
session('user')->photo
session('user')->rol
// o mediante el helper:
get_user_data('email')
```

### Assets

Los assets se cargan condicionalmente en `header.php` y `footer.php` según la ruta:

```php
<?php if (strpos(uri_string(), 'documents') !== false): ?>
    <script src="<?= base_url('assets/js/jstree.min.js') ?>"></script>
<?php endif; ?>
```

No cargar librerías globalmente si solo las usa un módulo.

### Iconos

Usar Tabler Icons con la clase `ti ti-nombre`:

```html
<i class="ti ti-bell"></i>
<i class="ti ti-user"></i>
```

---

## Sesión

| Operación | Código |
|-----------|--------|
| Leer ID | `session('user')->id` |
| Leer rol | `session('user')->rol` |
| Leer campo desde vista | `get_user_data('email')` |
| Guardar en login | `$this->session->set('user', $userObject)` |
| Destruir en logout | `$this->session->destroy()` |

---

## CSRF

- **Vistas con formularios AJAX:** pasar `csrfName` y `csrfHash` desde el controlador
- **Respuestas AJAX POST/DELETE:** usar siempre `respondWithCsrf()` para devolver tokens frescos
- **GET de solo lectura:** no es necesario incluir CSRF en la respuesta

---

## Internacionalización (i18n)

Usar claves de lenguaje en todos los mensajes al usuario:

```php
lang('Errors.missing_fields')
lang('Success.user_created')
lang('Alerts.new_feed')
```

Nunca hardcodear strings de mensajes directamente en controladores.

---

## Manejo de Errores

| Contexto | Mecanismo |
|----------|-----------|
| Validación en AJAX | `respondWithCsrf(['ok' => false, 'error' => lang(...)])` |
| Redirect con error | `HelperUtility::redirectWithMessage('/ruta', 'msg', 'error')` |
| Acceso no autorizado | `AuthFilter` redirige a `/404` automáticamente |
| Fallo en servicio externo | `try/catch` + `log_message('error', ...)` — no bloquea la operación principal |

---

## Servicios Externos

Al integrar un servicio externo:

1. Crear clase en `app/Services/NombreService.php`
2. Leer credenciales desde variables de entorno (documentarlas en `env`)
3. Usar `guardConfig()` para validar que las variables estén presentes antes de operar
4. Crear `app/Exceptions/NombreException.php` extendiendo `RuntimeException`
5. En el controlador: capturar la excepción, loguear el error, y **no revertir** la operación principal en DB

```php
try {
    $service->crearRecurso($data);
} catch (\Throwable $e) {
    log_message('error', 'Falló NombreService: ' . $e->getMessage());
}
```

---

## Base de Datos

- Usar Query Builder de CI4 — no SQL raw salvo para queries muy complejos
- Todas las tablas deben tener `created_at`, `updated_at`, `deleted_at` (soft deletes)
- `$returnType = 'object'` en todos los modelos — acceder con `$record->campo`
- No usar `find()` sin argumento en producción si el conjunto puede ser grande; agregar `limit()` o paginación
- Los arrays en DB se almacenan como JSON (`json_encode` al guardar, `json_decode` al leer)

---

## Logs

```php
log_message('error', 'Descripción: ' . $e->getMessage());
log_message('info',  'Acción completada para user_id=' . $userId);
```

Usar `log_message()` de CI4. No usar `error_log()` ni `var_dump()` en producción.
