# Intranet Trantor Technologies

Sistema de gestión corporativa desarrollado con **CodeIgniter 4** para Trantor Technologies.

## Resumen

La aplicación centraliza procesos internos de la empresa, incluyendo:

- Autenticación y control de acceso por roles (Admin, Operator, User)
- Gestión de usuarios y perfiles
- Organigramas organizacionales y personalizados
- Gestión documental (carpetas, archivos, descargas)
- Feed interno de noticias (**Trantor Informa**)
- Sistema de quejas y sugerencias
- Directorio de empleados y alertas

## Requisitos

### Requisitos mínimos

- PHP **8.1+** (recomendado 8.2)
- Apache **2.4+** con `mod_rewrite` habilitado
- MySQL **5.7+** o MariaDB **10.3+**

### Extensiones PHP requeridas

- `intl`
- `mbstring`
- `json`
- `mysqlnd`
- `curl`
- `gd` o `imagick`
- `fileinfo`

### Herramientas de desarrollo

- Composer
- Git
- Node.js (opcional, para assets frontend)

## Instalación rápida

1. Clonar el repositorio

```bash
git clone <repository-url>
cd ttech
```

2. Instalar dependencias

```bash
composer install
```

3. Crear archivo de entorno

```bash
cp env .env
```

4. Configurar `.env` (mínimo: `app.baseURL`, conexión de BD y `encryption.key`)

5. Crear base de datos y ejecutar migraciones

```bash
php spark migrate
```

6. Configurar permisos

```bash
chmod -R 775 writable/
chmod -R 775 public/uploads/
```

## Despliegue en servidor (Apache)

Configura el VirtualHost apuntando a `public/`:

```apache
<VirtualHost *:80>
	ServerName tu-dominio.com
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

Permisos recomendados en Linux:

```bash
sudo chown -R www-data:www-data /var/www/ttech
sudo chmod -R 755 /var/www/ttech
sudo chmod -R 775 /var/www/ttech/writable
sudo chmod -R 775 /var/www/ttech/public/uploads
```

## Despliegue con Docker (opcional)

El proyecto incluye `captain-definition` para despliegue en CapRover.

Puntos clave del contenedor:

- Imagen base `php:8.2-apache`
- `DocumentRoot` apuntando a `/var/www/html/public`
- `mod_rewrite` habilitado
- Permisos sobre `writable/` y `public/uploads/`

## Seguridad y producción

- No subir `.env` al repositorio
- Usar HTTPS en producción
- Definir `CI_ENVIRONMENT = production`
- Restringir permisos de escritura solo a carpetas necesarias
- Monitorear logs y mantener respaldos

---

Para documentación completa y detalle de módulos/rutas, revisar `documentacion.md`.
