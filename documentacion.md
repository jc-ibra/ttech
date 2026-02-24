# Documentación del Sistema Intranet Trantor Technologies

## Tabla de Contenidos
- [Descripción General](#descripción-general)
- [Requisitos del Sistema](#requisitos-del-sistema)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Instalación](#instalación)
- [Configuración del Entorno (.env)](#configuración-del-entorno-env)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Módulos y Funcionalidades](#módulos-y-funcionalidades)
- [Control de Acceso por Roles](#control-de-acceso-por-roles)
- [Base de Datos](#base-de-datos)
- [Rutas de la Aplicación](#rutas-de-la-aplicación)
- [Despliegue](#despliegue)

---

## Descripción General

Sistema de gestión corporativa desarrollado en CodeIgniter 4 para **Trantor Technologies**. La aplicación permite la gestión de usuarios, organigramas personalizados, directorio de empleados, documentos, sugerencias, y un sistema de noticias (feed) interno.

### Características Principales
-  Sistema de autenticación con roles (Admin, Operador, Usuario)
-  Gestión de usuarios y empleados
-  Organigramas organizacionales interactivos
-  Sistema de gestión documental
-  Sistema de quejas y sugerencias
-  Feed de noticias interno (Trantor Informa)
-  Directorio de empleados
-  Sistema de alertas y notificaciones
-  Perfiles de usuario personalizables

---

## Requisitos del Sistema

### Requisitos Mínimos
- **PHP**: 8.1 o superior
- **Servidor Web**: Apache 2.4+ (con mod_rewrite habilitado)
- **Base de Datos**: MySQL 5.7+ o MariaDB 10.3+
- **Extensiones PHP requeridas**:
  - `intl` (Internacionalización)
  - `mbstring` (Manejo de strings multibyte)
  - `json` (habilitado por defecto)
  - `mysqlnd` (MySQL Native Driver)
  - `curl` (para peticiones HTTP)
  - `gd` o `imagick` (procesamiento de imágenes)
  - `fileinfo` (detección de tipos MIME)

### Requisitos de Desarrollo
- **Composer**: Última versión estable
- **Git**: Para control de versiones
- **Node.js** (opcional): Para gestión de assets frontend

---

## Tecnologías Utilizadas

### Backend
- **Framework**: CodeIgniter 4
- **Lenguaje**: PHP 8.2
- **ORM**: CodeIgniter Query Builder
- **Autenticación**: Sistema personalizado basado en sesiones

### Frontend
- HTML5, CSS3, JavaScript
- Bootstrap (Framework CSS)
- jQuery (Manipulación DOM y AJAX)

### Base de Datos
- MySQL/MariaDB

### Herramientas de Desarrollo
- Composer (gestión de dependencias)
- PHPUnit (testing)
- PHP-CS-Fixer (Code Standard)

---

## Instalación

### 1. Clonar el Repositorio
```bash
git clone <repository-url>
cd ttech
```

### 2. Instalar Dependencias
```bash
composer install
```

### 3. Configurar el Archivo de Entorno
```bash
cp env .env
```
Luego editar el archivo `.env` con la configuración necesaria (ver siguiente sección).

### 4. Configurar la Base de Datos
Crear una base de datos MySQL:
```sql
CREATE DATABASE trantor_tech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Ejecutar Migraciones
```bash
php spark migrate
```

### 6. Ejecutar Seeders (opcional)
```bash
php spark db:seed <SeederName>
```

### 7. Configurar Permisos
```bash
chmod -R 775 writable/
chmod -R 775 public/uploads/
```

### 8. Configurar Apache
Asegurarse de que el `DocumentRoot` apunte a la carpeta `public/`:
```apache
DocumentRoot /ruta/al/proyecto/public
```

---

## Configuración del Entorno (.env)

### Variables de Entorno Requeridas

#### Configuración General
```ini
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
# Valores: development, testing, production
CI_ENVIRONMENT = development
```

#### Configuración de la Aplicación
```ini
#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
# URL base de la aplicación (CON barra final)
app.baseURL = 'http://localhost:8080/'

# Si deseas forzar HTTPS en todas las peticiones
# app.forceGlobalSecureRequests = false

# Habilitar Content Security Policy
# app.CSPEnabled = false
```

#### Configuración de Base de Datos
```ini
#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
# Configuración de conexión a la base de datos
database.default.hostname = localhost
database.default.database = trantor_tech
database.default.username = tu_usuario
database.default.password = tu_contraseña
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306
```

#### Configuración de Encriptación
```ini
#--------------------------------------------------------------------
# ENCRYPTION
#--------------------------------------------------------------------
# Clave de encriptación (32 caracteres hexadecimales)
# Generar con: php spark key:generate
encryption.key = tu_clave_de_encriptacion_aqui
```

#### Configuración de Sesiones
```ini
#--------------------------------------------------------------------
# SESSION
#--------------------------------------------------------------------
# Driver de sesión: files, database, redis, memcached
session.driver = files

# Ruta donde se guardan las sesiones (para driver 'files')
session.savePath = writable/session/

# Nombre de la cookie de sesión
# session.cookieName = ci_session

# Tiempo de vida de la sesión (en segundos)
# session.expiration = 7200
```

#### Configuración del Logger
```ini
#--------------------------------------------------------------------
# LOGGER
#--------------------------------------------------------------------
# Nivel de logging: 0-9
# 0 = Emergency, 1 = Alert, 2 = Critical, 3 = Error
# 4 = Warning, 5 = Notice, 6 = Info, 7 = Debug, 8 = All, 9 = Off
logger.threshold = 4
```

#### Configuración de Email (Opcional)
```ini
#--------------------------------------------------------------------
# EMAIL
#--------------------------------------------------------------------
# email.protocol = smtp
# email.SMTPHost = smtp.gmail.com
# email.SMTPUser = tu_email@gmail.com
# email.SMTPPass = tu_contraseña_app
# email.SMTPPort = 587
# email.SMTPCrypto = tls
# email.fromEmail = noreply@trantortech.com
# email.fromName = Trantor Technologies
```

### Generación de Clave de Encriptación

Para generar una clave de encriptación segura:
```bash
php spark key:generate
```

O manualmente:
```bash
php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
```

---

## Estructura del Proyecto

```
ttech/
├── app/
│   ├── Config/           # Archivos de configuración
│   ├── Controllers/      # Controladores de la aplicación
│   ├── Database/         # Migraciones y Seeds
│   ├── Filters/          # Filtros de autenticación y roles
│   ├── Helpers/          # Funciones auxiliares
│   ├── Language/         # Archivos de traducción (es, en)
│   ├── Libraries/        # Bibliotecas personalizadas
│   ├── Models/           # Modelos de datos
│   └── Views/            # Vistas de la aplicación
├── public/
│   ├── assets/           # CSS, JS, imágenes
│   ├── uploads/          # Archivos subidos por usuarios
│   └── index.php         # Punto de entrada
├── system/               # Core de CodeIgniter 4
├── writable/
│   ├── cache/            # Caché de la aplicación
│   ├── logs/             # Logs del sistema
│   ├── session/          # Sesiones
│   └── uploads/          # Uploads temporales
├── .env                  # Variables de entorno (NO versionar)
├── composer.json         # Dependencias PHP
└── spark                 # CLI de CodeIgniter
```

---

## Módulos y Funcionalidades

### 1. Autenticación (`Auth`)
- Login/Logout de usuarios
- Registro de nuevos usuarios (Admin/Operador)
- Actualización de datos de usuario
- Activación/Desactivación de cuentas

### 2. Gestión de Usuarios (`User`)
- Listado de usuarios
- Creación de usuarios
- Edición de perfiles
- Eliminación suave (soft delete)
- Cambio de contraseña
- Gestión de fotos de perfil
- Reingreso de usuarios dados de baja

### 3. Organización (`Organization`)
- Visualización de organigramas
- Organigramas por departamento
- Organigramas por área
- Organigrama general de la empresa

### 4. Organigramas Personalizados (`CustomOrganigram`)
- Creación de organigramas personalizados
- Clonación de organigramas
- Asignación de usuarios a organigramas
- Visualización de organigramas custom
- Edición y eliminación (solo Admin)

### 5. Gestión de Ocupaciones (`Ocupation`)
- CRUD de puestos de trabajo
- Asignación de ocupaciones a usuarios

### 6. Gestión de Áreas (`Area`)
- CRUD de áreas organizacionales
- Organización por áreas

### 7. Gestión de Departamentos (`Department`)
- CRUD de departamentos
- Asignación de usuarios a departamentos

### 8. Gestión Documental (`Documents`)
- Sistema de carpetas y subcarpetas
- Subida de archivos
- Descarga de documentos
- Organización jerárquica
- Eliminación de carpetas y archivos

### 9. Trantor Informa (`TrantorInforma`)
- Feed de noticias interno
- Publicaciones con texto, imágenes y archivos
- Sistema de likes
- Sistema de comentarios
- Edición y eliminación de publicaciones (Admin)

### 10. Quejas y Sugerencias (`Suggestion`)
- Envío de quejas y sugerencias
- Gestión por parte del Admin
- Estados: nuevo, abierto, cerrado
- Eliminación de sugerencias

### 11. Directorio (`Directorio`)
- Directorio de empleados
- Información de contacto
- Búsqueda de empleados

### 12. Alertas (`Alert`)
- Sistema de notificaciones
- Marcado de alertas como leídas
- Conteo de alertas no leídas

### 13. Gestión de Archivos (`Files`)
- Upload de archivos vía AJAX
- Validación de tipos MIME
- Manejo de imágenes y documentos

---

## Control de Acceso por Roles

El sistema implementa tres niveles de acceso:

### Roles Disponibles
1. **Admin** - Acceso completo al sistema
2. **Operator** - Acceso a gestión operativa
3. **User** - Acceso limitado a funcionalidades básicas

### Matriz de Permisos

| Funcionalidad | Admin | Operator | User |
|--------------|-------|----------|------|
| Gestión de Usuarios | ✅ | ✅ | ❌ |
| Crear/Editar Departamentos | ✅ | ❌ | ❌ |
| Crear/Editar Áreas | ✅ | ❌ | ❌ |
| Crear/Editar Ocupaciones | ✅ | ❌ | ❌ |
| Ver Organigramas | ✅ | ✅ | ✅ |
| Crear Organigramas Custom | ✅ | ✅ | ❌ |
| Editar Organigramas Custom | ✅ | ❌ | ❌ |
| Gestión Documental | ✅ | ❌ | ❌ |
| Ver Documentos | ✅ | ❌ | ✅ |
| Trantor Informa (Ver) | ✅ | ✅ | ✅ |
| Trantor Informa (Gestión) | ✅ | ❌ | ❌ |
| Enviar Sugerencias | ✅ | ❌ | ✅ |
| Gestionar Sugerencias | ✅ | ❌ | ❌ |
| Directorio | ✅ | ❌ | ✅ |
| Perfil Propio | ✅ | ✅ | ✅ |

### Filtros de Autenticación

Los filtros se configuran en `app/Config/Filters.php`:

```php
// AuthFilter: Verifica si el usuario está autenticado
// RoleFilter: Verifica si el usuario tiene el rol necesario
```

---

## Base de Datos

### Tablas Principales

#### 1. `users`
Almacena información de usuarios/empleados
- `id` - Identificador único
- `name` - Nombre
- `lastname` - Apellido
- `email` - Email principal
- `password` - Contraseña hasheada
- `rol` - Rol (admin, operator, user)
- `ocupation` - ID de ocupación
- `department` - ID de departamento
- `area` - ID de área
- `parent` - ID del jefe directo
- `photo` - Ruta de la foto
- `cellphone` - Celular
- `telephone` - Teléfono
- `ext` - Extensión
- `employee_number` - Número de empleado
- `date_entry` - Fecha de ingreso
- `active` - Estado activo/inactivo
- `show_in_directory` - Mostrar en directorio
- `ghost` - Usuario fantasma
- `deleted_at` - Fecha de eliminación (soft delete)

#### 2. `ocupations`
Catálogo de puestos de trabajo
- `id` - Identificador
- `name` - Nombre del puesto

#### 3. `departments`
Catálogo de departamentos
- `id` - Identificador
- `name` - Nombre del departamento

#### 4. `areas`
Catálogo de áreas
- `id` - Identificador
- `name` - Nombre del área

#### 5. `custom_organigramas`
Organigramas personalizados
- `id` - Identificador
- `name` - Nombre del organigrama
- `description` - Descripción
- `data` - JSON con estructura del organigrama

#### 6. `custom_organigrama_users`
Relación entre organigramas y usuarios
- `id` - Identificador
- `custom_organigrama_id` - ID del organigrama
- `user_id` - ID del usuario

#### 7. `documents`
Carpetas y estructura documental
- `id` - Identificador
- `name` - Nombre de la carpeta
- `parent_id` - ID de carpeta padre
- `type` - Tipo (folder)

#### 8. `documents_files`
Archivos dentro del sistema documental
- `id` - Identificador
- `document_id` - ID de carpeta contenedora
- `name` - Nombre del archivo
- `path` - Ruta del archivo
- `size` - Tamaño
- `mime_type` - Tipo MIME

#### 9. `feed`
Publicaciones del feed interno
- `id` - Identificador
- `user_id` - Autor
- `type` - Tipo (text, image, file)
- `content` - Contenido
- `file_id` - ID del archivo adjunto
- `likes` - Contador de likes

#### 10. `feed_comments`
Comentarios en publicaciones
- `id` - Identificador
- `feed_id` - ID de la publicación
- `user_id` - Autor del comentario
- `comment` - Texto del comentario

#### 11. `suggestions`
Quejas y sugerencias
- `id` - Identificador
- `user_id` - Usuario que envía
- `type` - Tipo (queja/sugerencia)
- `subject` - Asunto
- `description` - Descripción
- `status` - Estado (new, open, closed)

#### 12. `alerts`
Sistema de notificaciones
- `id` - Identificador
- `user_id` - Destinatario
- `type` - Tipo de alerta
- `message` - Mensaje
- `read` - Leída/no leída
- `link` - URL relacionada

#### 13. `files`
Repositorio general de archivos
- `id` - Identificador
- `name` - Nombre original
- `path` - Ruta en servidor
- `type` - Tipo de archivo
- `size` - Tamaño
- `user_id` - Usuario que subió

---

## Rutas de la Aplicación

### Rutas Públicas (Sin autenticación)
```
GET  /                  - Página de login
POST /auth/login        - Procesar login
```

### Rutas Comunes (Admin + Operator + User)
```
GET  /auth/logout                     - Cerrar sesión
GET  /alerts                          - Ver alertas
GET  /alerts/unread                   - Alertas no leídas
POST /alerts/read/:id                 - Marcar alerta como leída
GET  /profile                         - Ver perfil
POST /profile/update/password         - Cambiar contraseña
POST /profile/update/photo            - Actualizar foto
POST /profile/update/profile          - Actualizar perfil
GET  /organization                    - Ver organigrama
GET  /organization/data               - Datos del organigrama
GET  /organization/data/department/:id - Organigrama por departamento
GET  /organization/data/area/:id      - Organigrama por área
GET  /organization/data/general/:id   - Organigrama general
```

### Rutas Admin + Operator
```
POST /auth/register              - Registrar usuario
POST /auth/user/update           - Actualizar usuario
POST /auth/user/active           - Activar usuario
POST /auth/user/inactive         - Desactivar usuario
POST /auth/user/reactivate       - Reingresar usuario
GET  /user                       - Listar usuarios
GET  /user/new                   - Formulario nuevo usuario
GET  /user/edit/:id              - Formulario editar usuario
GET  /custom-organigram          - Listar organigramas custom
GET  /custom-organigram/create   - Crear organigrama
GET  /custom-organigram/view/:id - Ver organigrama
GET  /custom-organigram/data/:id - Datos del organigrama
```

### Rutas Admin + User
```
GET  /trantor-technologies           - Página corporativa
GET  /quejas-sugerencias            - Formulario de sugerencias
POST /suggestion/create             - Crear sugerencia
GET  /documentos                    - Ver documentos
GET  /documents/folder              - Listar carpetas
GET  /documents/file/:id            - Archivos de carpeta
GET  /trantor-informa               - Feed de noticias
GET  /trantor-informa/text          - Posts de texto
GET  /trantor-informa/image         - Posts con imágenes
GET  /trantor-informa/file          - Posts con archivos
POST /trantor-informa/like/add      - Dar like
POST /trantor-informa/like/remove   - Quitar like
POST /trantor-informa/comment/add   - Agregar comentario
GET  /trantor-informa/feed/comments/:id - Ver comentarios
GET  /directorio                    - Directorio de empleados
```

### Rutas Solo Admin
```
# Ocupaciones
GET  /ocupation              - Listar ocupaciones
GET  /ocupation/new          - Nueva ocupación
GET  /ocupation/edit/:id     - Editar ocupación
POST /ocupation/new          - Crear ocupación
POST /ocupation/edit         - Actualizar ocupación
POST /ocupation/delete       - Eliminar ocupación

# Áreas
GET  /area                   - Listar áreas
GET  /area/new               - Nueva área
GET  /area/edit/:id          - Editar área
POST /area/new               - Crear área
POST /area/edit              - Actualizar área
POST /area/delete            - Eliminar área

# Departamentos
GET  /department             - Listar departamentos
GET  /department/new         - Nuevo departamento
GET  /department/edit/:id    - Editar departamento
POST /department/new         - Crear departamento
POST /department/edit        - Actualizar departamento
POST /department/delete      - Eliminar departamento

# Organigramas Custom
GET  /custom-organigram/edit/:id     - Editar organigrama
POST /custom-organigram/store        - Guardar organigrama
POST /custom-organigram/update       - Actualizar organigrama
POST /custom-organigram/delete       - Eliminar organigrama
POST /custom-organigram/clone        - Clonar organigrama
POST /custom-organigram/add-user     - Agregar usuario
POST /custom-organigram/remove-user  - Remover usuario

# Documentos
GET  /documents                      - Gestión de documentos
POST /documents/folder/rename        - Renombrar carpeta
POST /documents/folder/move          - Mover carpeta
POST /documents/folder/create        - Crear carpeta
POST /documents/folder/delete        - Eliminar carpeta
POST /documents/file/create          - Subir archivo
POST /documents/file/delete          - Eliminar archivo

# Archivos
POST   /files/upload                 - Subir archivo
POST   /files/upload/file            - Subir archivo específico
DELETE /files/revert                 - Eliminar archivo temporal

# Trantor Informa (Admin)
POST /trantor-informa/new            - Nueva publicación
POST /trantor-informa/delete         - Eliminar publicación
POST /trantor-informa/update         - Actualizar publicación
POST /trantor-informa/comment/delete - Eliminar comentario

# Sugerencias (Admin)
GET  /suggestions                    - Listar sugerencias
GET  /suggestions/get                - Obtener sugerencias
GET  /suggestions/get/:id            - Obtener sugerencia
POST /suggestions/unread             - Marcar como no leída
POST /suggestions/read               - Marcar como leída
POST /suggestions/delete             - Eliminar sugerencia
```

---

## Despliegue

### Despliegue en Servidor Tradicional

#### 1. Configuración de Apache
```apache
<VirtualHost *:80>
    ServerName trantortech.com
    DocumentRoot /var/www/ttech/public
    
    <Directory /var/www/ttech/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/ttech-error.log
    CustomLog ${APACHE_LOG_DIR}/ttech-access.log combined
</VirtualHost>
```

#### 2. Permisos de Carpetas
```bash
sudo chown -R www-data:www-data /var/www/ttech
sudo chmod -R 755 /var/www/ttech
sudo chmod -R 775 /var/www/ttech/writable
sudo chmod -R 775 /var/www/ttech/public/uploads
```

### Despliegue con Docker

El proyecto incluye un `captain-definition` para despliegue en CapRover:

```json
{
  "schemaVersion": 2,
  "dockerfileLines": [
    "FROM php:8.2-apache",
    "RUN apt-get -y update",
    "RUN apt-get install -y libicu-dev",
    "RUN docker-php-ext-configure intl",
    "RUN docker-php-ext-install intl",
    "RUN docker-php-ext-install mysqli",
    "RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf",
    "RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf",
    "COPY . /var/www/html/",
    "RUN mkdir -p /var/www/html/public/uploads",
    "RUN chown -R www-data:www-data /var/www/html/writable",
    "RUN chown -R www-data:www-data /var/www/html/public/uploads",
    "RUN a2enmod rewrite",
    "RUN service apache2 restart"
  ]
}
```

### Dockerfile Manual

```dockerfile
FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    git

# Instalar extensiones PHP
RUN docker-php-ext-install intl mysqli pdo_mysql zip

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Configurar DocumentRoot
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copiar código
COPY . /var/www/html/

# Crear directorios y permisos
RUN mkdir -p /var/www/html/public/uploads && \
    chown -R www-data:www-data /var/www/html/writable && \
    chown -R www-data:www-data /var/www/html/public/uploads

WORKDIR /var/www/html
```

### Consideraciones de Seguridad

1. **Variables de Entorno**: Nunca versionar el archivo `.env` con datos sensibles
2. **Permisos**: Restringir permisos de escritura solo a carpetas necesarias
3. **HTTPS**: Usar certificados SSL en producción (Let's Encrypt)
4. **Base de Datos**: Usar credenciales únicas y seguras
5. **Firewall**: Configurar firewall para permitir solo puertos necesarios
6. **Backups**: Implementar sistema de respaldos automáticos
7. **Logs**: Monitorear logs regularmente para detectar anomalías

### Optimización para Producción

```ini
# En .env para producción
CI_ENVIRONMENT = production

# app/Config/App.php
public $CSPEnabled = true;
public $forceGlobalSecureRequests = true;

# Habilitar caché
# app/Config/Cache.php
```

---

## Comandos Útiles

### Spark CLI

```bash
# Ver todas las rutas
php spark routes

# Limpiar caché
php spark cache:clear

# Crear nueva migración
php spark make:migration NombreMigracion

# Crear nuevo controlador
php spark make:controller NombreControlador

# Crear nuevo modelo
php spark make:model NombreModelo

# Crear nuevo filtro
php spark make:filter NombreFiltro

# Ver lista de comandos disponibles
php spark list
```

### Composer

```bash
# Actualizar dependencias
composer update

# Verificar código
composer run test

# Dump autoload optimizado
composer dump-autoload -o
```

---

## Solución de Problemas Comunes

### Error: "The action you requested is not allowed"
- Verificar que CSRF esté configurado correctamente en `app/Config/Security.php`
- Incluir el token CSRF en formularios

### Error: "Database connection failed"
- Verificar credenciales en `.env`
- Verificar que el servicio MySQL esté corriendo
- Verificar permisos del usuario de base de datos

### Error 404 en rutas
- Verificar que `mod_rewrite` esté habilitado en Apache
- Verificar el archivo `.htaccess` en `public/`
- Verificar configuración de `$baseURL` en `.env`

### Error de permisos al subir archivos
- Verificar permisos de `writable/` y `public/uploads/`
- El usuario web debe tener permisos de escritura

### Sesiones no persisten
- Verificar configuración de sesiones en `.env`
- Verificar permisos de `writable/session/`
- Verificar que las cookies estén habilitadas

---

## Mantenimiento

### Respaldos Recomendados
- **Base de Datos**: Diario (automático)
- **Archivos Subidos**: Semanal
- **Código Fuente**: Control de versiones Git

### Logs a Monitorear
- `writable/logs/` - Logs de aplicación
- Apache error logs - Errores del servidor
- MySQL slow query log - Consultas lentas

### Actualizaciones
```bash
# Actualizar CodeIgniter
composer update codeigniter4/framework

# Actualizar todas las dependencias
composer update

# Ejecutar nuevas migraciones
php spark migrate
```

---

## Contacto y Soporte

Para soporte técnico o consultas sobre el sistema, contactar al equipo de desarrollo Bradev en soporte@bradev.site.

**Versión**: 1.0.0  
**Última actualización**: Febrero 2026  
**Framework**: CodeIgniter 4  
**Licencia**: MIT
