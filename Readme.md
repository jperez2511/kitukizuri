# Kitu Kizuri

Este es un paquete para laravel que te permitira aplicar un control dentro de tu aplicacion en temas de permisos de usuario. Este paquete cuenta con 4 partes importantes necesarias en una aplicacion web con multiples usuarios. 

1. Modulos
2. Permisos que necesitara al modulo
3. Roles
4. Usuarios

## Modulos 

La seccion de modulos sirve para definir las partes de tu proyecto por ejemplo el modulo de clientes, proveedores, Inicio, etc. 

## Permios que necesita el modulo

En programacion es un hecho que los modulos a veces necesitan permisos mas especificos y no solo Crear, Editar, Leer y Eliminar. Es por eso que con este paquete al modulo puedes asignarles los permisos que necesitara por ejemplo si tu modulo solo requiere el permiso de leer tu puedes asignarle solamente ese permiso o crear tus propios permisos. 

## Roles

una vez que ya se han creado los modulos y asignados los permisos que este requiere, tu puedes crear tus propios roles haciendo combinaciones entre los modulos y sus permisos de esta forma tue puedes crear combinaciones infinitas segun la cantidad de modulos que existan en tu proyecto.

## Usuarios

al dejar los usuariso en manos de kitu kizuri tu puedes administrarlos incluyendo la asignacion de permisos, el paquete soporta multiples roles por lo tanto no tiendras que preocuparte por desarrollar esta funcionalidad.

## Instalacion 

para instalarlo es necesario agregar en tu **composer.json** en require la siguiente linea: 

```json
"icebearsoft/kitukizuri": "dev-master"
```

luego en tu consola correr el siguiente comando:

```bash
composer update
```

de esta forma no solo instalara el paquete sino que descargara la version mas actualizada. para luego agregar dentro de **config/app.php** en providers la siguiente linea:

```php
Icebearsoft\Kitukizuri\KitukizuriServiceProvider::class
```

