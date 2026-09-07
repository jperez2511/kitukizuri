# Kitu Kizuri

Kitu Kizuri es una estructura de aplicación diseñada para Laravel. Proporciona un punto de partida para tu aplicación.

## Componentes

- **Krud Security**
  - Inicio de sesión con [**Jetstream**](https://jetstream.laravel.com/introduction.html) - default
  - Inicio de sesión con [**LdapRecord**](https://ldaprecord.com/docs/laravel/v3) - opcional
  - Gestión de **Usuarios**
  - Asignación de **Roles**
  - Asignación de **Permisos** por **Módulos**
- **Krud Admin**
  - GUI para administración de Krud Security
  - Generador Low Code de "Catálogos (CRUD)"
  - Gestor de base de Datos para MySQL
  - Logs en base de datos - opcional
- **Krud Aux**
  - Entorno de desarrollo basado en [**Docker**](https://www.docker.com/) integrando
  - Integración con [**Vue**](https://vuejs.org/) - opcional
  - Integración con [**MongoDB**](https://www.mongodb.com/) - opcional
  - Integración con [**Trino**](https://trino.io/) - opcional

Documentación: [**https://kitukizuri.icebearsoft.com**](https://kitukizuri.icebearsoft.com/)

## Instalación

Instalar paquete en Laravel

```bash
composer require icebearsoft/kitukizuri
```

Utilizar entorno de desarrollo con Docker
```bash
php artisan krud:set-docker

docker-compose build

docker-compose up -d
```

Instalar Krud

```bash
# utilizando recursos locales
php artisan krud:install

# utilizando entorno en docker
docker exec -it idContainer bash

php artisan krud:install
```

## Actualización de una instalación existente

```bash
# sincroniza recursos publicados, migraciones y seeders base
php artisan krud:update

# fuerza sobrescritura en todos los tags (con respaldo *_old)
php artisan krud:update --force
```

`krud:update` tambien sincroniza el archivo `database/seeders/ModulosSeeder.php` para agregar modulos faltantes sin sobrescribir personalizaciones existentes.
Para vistas y recursos visuales, actualiza archivos existentes y crea respaldo automatico con sufijo `_old` antes de reemplazar.
Despues de publicar vistas, limpia automaticamente el cache Blade para evitar conflictos de timestamps y permisos entre el usuario de consola y PHP-FPM.


## Configuración opcional

### Servidor MCP

El servidor MCP viene incluido en `icebearsoft/kitukizuri`. Requiere PHP 8.2 o superior y Laravel 12.41.1 o 13 (Laravel 13 requiere PHP 8.3 o superior). No se instala como un paquete separado.

Después de actualizar Kitukizuri, publica la configuración:

```bash
php artisan vendor:publish --tag=krud-mcp-config
```

Agrega `KITUKIZURI_MCP_ENABLED=true` al `.env` y ejecuta `php artisan config:clear`. Esto registra el endpoint HTTP `/mcp/kitukizuri`, protegido por `auth:sanctum` de forma predeterminada. El cliente MCP debe conectarse a ese endpoint con un token Bearer válido; `boost:mcp` es un servidor diferente.

Los controladores Krud deben habilitar explícitamente los recursos y campos que exponen:

```php
use Icebearsoft\Kitukizuri\Mcp\Concerns\ExposesMcp;

// Dentro de un controlador que extiende Krud y tiene rutas nombradas:
use ExposesMcp;

protected static function mcp(): array
{
    return [
        'enabled' => true,
        'operations' => ['list', 'get'],
        'company_column' => 'empresaid',
    ];
}

// En el constructor, después de configurar el modelo y sus campos:
$this->setMcpField('nombre', ['read' => true, 'write' => false]);
```

Adapta la columna de empresa y los campos al modelo. El usuario necesita los permisos Kitukizuri del módulo y su asignación a la empresa. Si usas múltiples tenants, configura el middleware que resuelve el tenant antes de `auth:sanctum` en `config/kitukizuri-mcp.php`.

Verifica el registro y los recursos configurados:

```bash
php artisan route:list --path=mcp
php artisan krud:mcp:inspect
```

El servidor está deshabilitado por defecto. Las herramientas disponibles se filtran según el usuario autenticado.

### Seeders

Agregar en **database/seeds/DatabaseSeeder.php**

```php
public function run()
{
  $this->call(ModulosSeeder::class);
  $this->call(PermisosSeeder::class);
  $this->call(MenuSeeder::class);
}
```
## Usuario default

> Usuario: admin@mail.com
> Password: "temp,123"


Visitar la wiki para conocer más acerca del proyecto.
