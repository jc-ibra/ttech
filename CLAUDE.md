# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Corporate intranet for Trantor Technologies, built with **CodeIgniter 4 (PHP)**. It provides employee management, news feed, document management, organizational charts, complaints/suggestions, and an alert system with role-based access control.

## Development Commands

```bash
# Install dependencies
composer install

# Set up environment
cp env .env
# Then edit .env with: CI_ENVIRONMENT, app.baseURL, database credentials, encryption.key

# Start development server
php spark serve

# Database migrations
php spark migrate
php spark migrate:rollback
php spark db:seed <SeederName>

# Run tests
phpunit                   # all tests
phpunit tests/unit        # unit tests only
phpunit tests/database    # database tests only

# Set writable permissions (first-time setup)
chmod -R 775 writable/
chmod -R 775 public/uploads/
```

## Architecture

**Request flow:**
```
HTTP → public/index.php → Routes (app/Config/Routes.php) → Filters (Auth/Role) → Controller → Model → View
```

**Roles:** `admin`, `operator`, `user`. Three route groups in `Routes.php` enforce role access via `AuthFilter`. The filter checks `session('user')` and the `$arguments` array of allowed roles.

**MVC layout:**
- `app/Controllers/` — extend `BaseController`, which provides `checkEmptyField()` and `respondWithCsrf()`
- `app/Models/` — extend CodeIgniter's `Model`, using Query Builder (no Eloquent/Doctrine)
- `app/Views/` — split into `shared/` (header, footer, sidebars) and `pages/{admin,user,shared}/`
- `app/Filters/` — `AuthFilter.php` handles both authentication and role-based authorization

**Usuarios vs Empleados (separación importante):** Son dos conceptos distintos en dos tablas distintas.
- **Usuarios** (`users` / `UserModel` / `User` controller, rutas `/usuarios`): cuentas que **inician sesión**. Datos mínimos (`name`, `lastname`, `email`, `password`, `nexus_id`, `rol`, `active`). Los viewers (`rol='user'`) los administra **Nexus vía API** (`Api\Usuarios`, identificados por `nexus_id`); admin/operator se dan de alta manualmente.
- **Empleados** (`employees` / `EmployeeModel` / `Employee` controller, rutas `/empleados`): nodos de los **organigramas**. Contienen puesto, área, departamento, jefe (`parent`), lógica *ghost*, foto, etc. **No inician sesión** (las columnas `password`/`rol`/`last_login` quedan como vestigiales; ver migración de separación).

**Key modules:**
| Module | Controller | Model |
|--------|-----------|-------|
| Auth (login) | `Auth.php` | `UserModel.php` |
| Usuarios (login) + Profile | `User.php` | `UserModel.php` |
| Empleados (org) | `Employee.php` | `EmployeeModel.php` |
| Nexus API | `Api/Usuarios.php` | `UserModel.php` |
| News feed | `TrantorInforma.php` | `FeedModel.php` |
| Documents | `Documents.php` | `DocumentModel.php` |
| Org chart | `Organization.php`, `CustomOrganigram.php` | `EmployeeModel.php`, `CustomOrganigramModel.php` |
| Suggestions | `Suggestion.php` | `SuggestionModel.php` |
| Alerts | `Alert.php` | `AlertModel.php` |
| Directory | `Directorio.php` | `EmployeeModel.php` |

## Conventions

**Naming:**
- Controllers: `PascalCase.php`
- Models: `PascalCaseModel.php`
- DB tables: `snake_case` plural; columns: `snake_case`
- Methods: `camelCase`

**Models define:** `$table`, `$primaryKey`, `$returnType`, `$allowedFields`, `$useSoftDeletes`, `$useTimestamps`.

**Session:** Active user stored as `session('user')`. Use the helper `get_user_data($key)` (in `app/Helpers/user_helper.php`) to access user properties in views.

**AJAX responses:** Controllers use `respondWithCsrf($data)` to append fresh CSRF tokens to JSON responses. Frontend jQuery reads the token back to update subsequent requests.

**File uploads:** Profile photos go to `public/uploads/images/profiles/`. Validate with `$file->isValid()` before moving.

**Frontend:** Bootstrap + jQuery + ApexCharts. Assets are pre-built in `public/assets/`. No npm/build step required.

## Deployment

**Docker/CapRover:** `captain-definition` uses `php:8.2-apache`, enables `mod_rewrite`, and sets DocumentRoot to `/var/www/html/public`.

**Apache:** Point DocumentRoot to `public/`. The root `.htaccess` redirects everything there; `public/.htaccess` handles CodeIgniter's front controller routing.
