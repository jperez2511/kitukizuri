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


## Configuración opcional

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
