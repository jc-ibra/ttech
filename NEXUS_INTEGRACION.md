# Integración Nexus → Intranet Trantor · API v1 de Usuarios

> **Cómo usar este documento:** entrégalo tal cual al equipo (o al asistente de IA)
> que mantiene **Nexus**. Contiene el contexto del cambio, el contrato exacto de la
> API y ejemplos listos para copiar. Sustituye `{{BASE_URL}}` y `{{NEXUS_API_KEY}}`
> por los valores reales del entorno.

---

## 1. Contexto: qué cambió y por qué

La intranet separó dos conceptos que antes vivían juntos en una sola tabla:

| Concepto | Qué es | Quién lo administra |
|----------|--------|---------------------|
| **Empleado** | Nodo del **organigrama** (puesto, área, departamento, jefe). **No inicia sesión.** | La intranet (sección Empleados). |
| **Usuario** | **Cuenta de acceso** que inicia sesión en la intranet con perfil de visualización. | **Nexus**, vía esta API. |

**Implicación para Nexus:**
Nexus **ya no** administra empleados ni datos de organigrama. Nexus administra
**únicamente cuentas de login** y envía **solo la información mínima**:

- `nexus_id` — identificador del usuario **en Nexus** (clave para todas las operaciones posteriores)
- `nombre`
- `apellidos`
- `correo` (con el que inicia sesión)
- `password` (en texto plano sobre HTTPS; la intranet lo hashea)

> ⚠️ **Cambio incompatible respecto a la versión anterior:**
> - Ya **no** se envían `puesto`, `area`, `departamento`, `jefe_directo`.
> - El identificador de recurso pasó de `no_empleado` a **`nexus_id`**.
> - El alta ahora **requiere** `nexus_id`.

---

## 2. Autenticación

Todas las peticiones (excepto ninguna: también el alta) usan un **Bearer token** fijo
en el header `Authorization`. El token es el valor de `NEXUS_API_KEY` configurado en la
intranet.

```
Authorization: Bearer {{NEXUS_API_KEY}}
Content-Type: application/json
```

- Sin header válido → `401 NO_AUTORIZADO`.
- Si la intranet no tiene la API key configurada → `500 CONFIG_ERROR`.

**Base URL:** `{{BASE_URL}}` (ej. `https://intranet.trantor.com.mx`)
**Prefijo de todos los endpoints:** `/api/v1`

---

## 3. Endpoints

### 3.1 Verificar conexión — `GET /api/v1/ping`

```
GET {{BASE_URL}}/api/v1/ping
Authorization: Bearer {{NEXUS_API_KEY}}
```

**200 OK**
```json
{ "exito": true, "mensaje": "pong" }
```

---

### 3.2 Crear usuario — `POST /api/v1/usuarios`

Crea una cuenta de login con rol `user` (viewer).

**Request**
```
POST {{BASE_URL}}/api/v1/usuarios
Authorization: Bearer {{NEXUS_API_KEY}}
Content-Type: application/json
```
```json
{
  "nexus_id": "NX-10432",
  "nombre": "Juan",
  "apellidos": "Pérez López",
  "correo": "juan.perez@trantor.com.mx",
  "password": "ClaveSegura123",
  "estado": "activo"
}
```

| Campo | Requerido | Notas |
|-------|-----------|-------|
| `nexus_id` | ✅ | Identificador único en Nexus. Es la clave para actualizar/desactivar después. |
| `nombre` | ✅ | |
| `apellidos` | ✅ | |
| `correo` | ✅ | Único en la intranet. Es el usuario de login. |
| `password` | ✅ | Texto plano sobre HTTPS. La intranet lo hashea. |
| `estado` | ⛔ opcional | `"activo"` (default) o `"inactivo"`. |

**201/200 — Éxito**
```json
{ "exito": true, "id_usuario": "INT-57", "mensaje": "Usuario creado" }
```
> `id_usuario` es el id interno de la intranet (`INT-<n>`), informativo. Para
> operaciones posteriores **usa siempre `nexus_id`**, no este valor.

**Errores posibles**

| HTTP | `error_codigo` | Causa |
|------|----------------|-------|
| 400 | `CAMPO_REQUERIDO` | Falta un campo obligatorio. |
| 409 | `USUARIO_DUPLICADO` | Ya existe un usuario con ese `nexus_id`. |
| 409 | `CORREO_DUPLICADO` | Ya existe un usuario con ese `correo`. |
| 401 | `NO_AUTORIZADO` | Token ausente o inválido. |
| 500 | `ERROR_INTERNO` | No se pudo crear. |

---

### 3.3 Actualizar usuario — `PUT /api/v1/usuarios/{nexus_id}`

Actualización **parcial**: envía solo los campos que cambian.

**Request**
```
PUT {{BASE_URL}}/api/v1/usuarios/NX-10432
Authorization: Bearer {{NEXUS_API_KEY}}
Content-Type: application/json
```
```json
{
  "nombre": "Juan Carlos",
  "apellidos": "Pérez López",
  "correo": "jc.perez@trantor.com.mx",
  "estado": "inactivo",
  "password": "NuevaClave456"
}
```

| Campo | Notas |
|-------|-------|
| `nombre` | opcional |
| `apellidos` | opcional |
| `correo` | opcional; debe seguir siendo único |
| `estado` | opcional: `"activo"` / `"inactivo"` |
| `password` | opcional; si viene vacío o no viene, no se cambia |

**200 — Éxito**
```json
{ "exito": true, "id_usuario": "INT-57", "mensaje": "Usuario actualizado" }
```
Si no mandas ningún campo:
```json
{ "exito": true, "mensaje": "Sin cambios que aplicar" }
```

**Errores**

| HTTP | `error_codigo` | Causa |
|------|----------------|-------|
| 404 | `USUARIO_NO_ENCONTRADO` | No existe usuario con ese `nexus_id`. |
| 409 | `CORREO_DUPLICADO` | El `correo` ya lo usa otro usuario. |
| 401 | `NO_AUTORIZADO` | Token ausente o inválido. |

---

### 3.4 Desactivar usuario — `POST /api/v1/usuarios/{nexus_id}/desactivar`

Deja la cuenta inactiva (no puede iniciar sesión). No borra datos.

**Request**
```
POST {{BASE_URL}}/api/v1/usuarios/NX-10432/desactivar
Authorization: Bearer {{NEXUS_API_KEY}}
```

**200 — Éxito**
```json
{ "exito": true, "mensaje": "Usuario desactivado" }
```

| HTTP | `error_codigo` | Causa |
|------|----------------|-------|
| 404 | `USUARIO_NO_ENCONTRADO` | No existe usuario con ese `nexus_id`. |

> Para **reactivar**, usa `PUT` con `{ "estado": "activo" }`.

---

### 3.5 Cambiar contraseña — `POST /api/v1/usuarios/{nexus_id}/password`

**Request**
```
POST {{BASE_URL}}/api/v1/usuarios/NX-10432/password
Authorization: Bearer {{NEXUS_API_KEY}}
Content-Type: application/json
```
```json
{ "password": "OtraClave789" }
```

**200 — Éxito**
```json
{ "exito": true, "mensaje": "Contrasena actualizada" }
```

| HTTP | `error_codigo` | Causa |
|------|----------------|-------|
| 400 | `CAMPO_REQUERIDO` | Falta `password`. |
| 404 | `USUARIO_NO_ENCONTRADO` | No existe usuario con ese `nexus_id`. |

---

## 4. Mapeo de campos (Nexus → Intranet)

| Nexus (JSON) | Intranet (tabla `users`) |
|--------------|--------------------------|
| `nexus_id` | `nexus_id` |
| `nombre` | `name` |
| `apellidos` | `lastname` |
| `correo` | `email` |
| `password` | `password` (hasheado en el servidor) |
| `estado` | `active` (`activo`→1, `inactivo`→0) |
| — | `rol` = `user` (fijo, asignado por la intranet) |

---

## 5. Contrato de respuesta (resumen)

- Toda respuesta trae `"exito": true|false`.
- En error: `"error_codigo"` (string estable) + `"mensaje"` (texto legible).
- En éxito de escritura: `"mensaje"` y, cuando aplica, `"id_usuario": "INT-<n>"`.

**Códigos de error estables** (úsalos para lógica, no el texto):
`NO_AUTORIZADO`, `CONFIG_ERROR`, `CAMPO_REQUERIDO`, `USUARIO_DUPLICADO`,
`CORREO_DUPLICADO`, `USUARIO_NO_ENCONTRADO`, `JEFE_NO_ENCONTRADO` (obsoleto), `ERROR_INTERNO`.

---

## 6. Recomendaciones de implementación para Nexus

1. **Idempotencia por `nexus_id`:** antes de crear, si recibes `409 USUARIO_DUPLICADO`,
   trata el registro como existente y usa `PUT` para sincronizar.
2. **Sincronización de correo:** si cambia el correo en Nexus, propágalo con `PUT`;
   maneja `409 CORREO_DUPLICADO`.
3. **Altas/bajas:** usa `desactivar` (o `PUT estado`) en vez de eliminar; la intranet
   no expone borrado por API.
4. **Contraseñas:** viajan en texto plano **solo sobre HTTPS**; nunca las registres en logs.
5. **Reintentos:** `5xx` es reintentable; `4xx` (400/401/404/409) no lo es sin corregir el request.

---

## 7. Ejemplos `curl`

```bash
# Ping
curl -s {{BASE_URL}}/api/v1/ping \
  -H "Authorization: Bearer {{NEXUS_API_KEY}}"

# Crear
curl -s -X POST {{BASE_URL}}/api/v1/usuarios \
  -H "Authorization: Bearer {{NEXUS_API_KEY}}" \
  -H "Content-Type: application/json" \
  -d '{"nexus_id":"NX-10432","nombre":"Juan","apellidos":"Pérez","correo":"juan@trantor.com.mx","password":"Clave123"}'

# Actualizar
curl -s -X PUT {{BASE_URL}}/api/v1/usuarios/NX-10432 \
  -H "Authorization: Bearer {{NEXUS_API_KEY}}" \
  -H "Content-Type: application/json" \
  -d '{"correo":"jc.perez@trantor.com.mx","estado":"activo"}'

# Desactivar
curl -s -X POST {{BASE_URL}}/api/v1/usuarios/NX-10432/desactivar \
  -H "Authorization: Bearer {{NEXUS_API_KEY}}"

# Cambiar contraseña
curl -s -X POST {{BASE_URL}}/api/v1/usuarios/NX-10432/password \
  -H "Authorization: Bearer {{NEXUS_API_KEY}}" \
  -H "Content-Type: application/json" \
  -d '{"password":"OtraClave789"}'
```
