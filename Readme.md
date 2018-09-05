# Kitu Kizuri

Este es un paquete para laravel que te permitira aplicar un control dentro de tu aplicacion en temas de permisos de usuario. Este paquete cuenta con 4 partes importantes necesarias en una aplicacion web con multiples usuarios. 

- **Modulos**
  - La seccion de modulos sirve para definir las partes de tu proyecto por ejemplo el modulo de clientes, proveedores, Inicio, etc. 
- **Permisos que necesitara al modulo**
  - En muchos casos los modulos necesitan permisos especificos y no solo Crear, Editar, Leer y Eliminar. Es por eso que kitukizuri permite agregar permisos adicionales.
- **Roles**
  - tu puedes crear tus propios roles haciendo combinaciones entre los modulos y sus permisos de esta forma tu puedes crear combinaciones infinitas segun la cantidad de modulos que existan en tu proyecto.
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
### Rutas

Agregar en **routes/web.php**
```php
Route::middleware(['auth', 'kitukizuri'])->group(function () {
  Route::resource('roles', 'Kitukizuri\RolesController');
  Route::resource('modulos', 'Kitukizuri\ModulosController');
  Route::resource('usuarios', 'Kitukizuri\UsuariosController');
  Route::resource('asignarpermiso', 'Kitukizuri\UsuarioRolController');
  Route::resource('permisos', 'Kitukizuri\PermisosController', ['only'=>['index', 'store']]);
  Route::resource('rolpermisos', 'Kitukizuri\RolesPermisosController', ['only'=>['index', 'store']]);
  Route::resource('empresas', 'Kitukizuri\EmpresasController');
  Route::resource('moduloempresas', 'Kitukizuri\ModuloEmpresasController');
});
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


