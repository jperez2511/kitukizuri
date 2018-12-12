# Kitu Kizuri

Paquete para laravel permite aplicar control de permisos de usuario. Este paquete cuenta con 4 partes importantes necesarias en una aplicacion web con multiples usuarios. 

- **Modulos**
  - Sirve para definir las partes de tu proyecto por ejemplo el modulo de clientes, proveedores, Inicio, etc. 
- **Permisos que necesitara al modulo**
  - A cada modulo se le pueden asignar permisos de Crear, Actualizar, Leer y Eliminar pero tambien puedes agregar tus propios permisos.
- **Roles**
  - tu puedes crear tus propios roles haciendo combinaciones entre los modulos y sus permisos casi en combinaciones infinitas segun la cantidad de modulos de tu proyecto.
- **Usuarios**
  - al dejar los usuarios en manos de kitukizuri puedes administrarlos incluyendo la asignacion de permisos, soporta multiples roles.

## Instalacion 

Agregar en tu **composer.json** lo siguiente: 

```json
"requiere": {
  "icebearsoft/kitukizuri": "dev-master"
}
```

Ejecutar el siguiente comando para instalar y actualizar

```bash
composer update
```

Configurar nuestro service provider dentro de **config/app.php** :

```php
'providers' => [
  Icebearsoft\Kitukizuri\KitukizuriServiceProvider::class
]
```

Publicaremos los archivos que el usuario puede modificar del paquete, ejecutar el siguiente comando:

```bash
php artisan vendor:publish
```

## Configuracion

### Seeders

 Agregar en **database/seeds/DatabaseSeeder.php**

```php
public function run()
{
  $this->call(ModulosSeeder::class);
  $this->call(PermisosSeeder::class);
  $this->call(ModuloPermisosSeeder::class);
}
```

middleware kitukizuri es el encargado de verificar los permisos que tiene el usuario para la ruta en cuestion.

### Ejecutar Migrations & Seeders

una vez configurados los seeders es necesario ejecutar las migrations y los seeders para eso ejecutar el siguiente comando:

```bash
php artisan migrate
php artisan db:seed
php artisan db:seed --class=InicialSeeder
```

Este comando **php artisan db:seed --class=InicialSeeder** sirve para configurar el usuario inicial, con el rol GOD (rol adminstrador). Datos del usuario: 

> Usuario: admin@mail.com
> Password: temp,123


Visita la Wiki para sabes como utilizar este paquete. 