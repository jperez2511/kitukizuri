<?php

return [

    /*
    |--------------------------------------------------------------------------
    |
    |                          Configuraciones generales
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    |--------------------------------------------------------------------------
    | Multi tenants
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir el uso de multi tenants para el manejo de
    | datos
    |
    */

    'multiTenants' => false,

    /*
    |--------------------------------------------------------------------------
    | Prefix de rutas
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir el el prefix predeterminado para las rutas
    | propias del KRUD
    |
    */

    'routePrefix' => 'krud',

    /*
    |--------------------------------------------------------------------------
    | Default Layout
    |--------------------------------------------------------------------------
    |
    | Esta opción permite agregar un layout por defecto a las vistas del Krud
    | es necesario recordar que también se puede definirse en el controller si
    | fuera necesario tener un layout personalizado en especifico
    |
    */

    'layout' => 'layouts.app',
    'preUi'  => false,


    /*
    |--------------------------------------------------------------------------
    | Dark Version
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir la versión oscura del estilo visual
    |
    */

    'dark' => false,

    /*
    |--------------------------------------------------------------------------
    | Dark Side Bar Version
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir la versión oscura solamente del sidebar
    |
    */

    'darkSideBar' => false,

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Version
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir la version de Bootstrap que esta utilizando el
    | template
    |
    */

    'vBootstrap' => '',

    /*
    |--------------------------------------------------------------------------
    | Iconos y Estilos
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir los iconos que se mostraran en los botones de
    | la tabla en la vista index, en tal caso que no se quisiera usar los
    | iconos predeterminados (Font-Awesome, Material icons)
    |
    */

    'edit'    => 'fa-duotone fa-solid fa-pencil',
    'delete'  => 'fa-duotone fa-solid fa-trash-can',
    'options' => 'fa-duotone fa-solid fa-grid-2',

    'classBtnEdit'    => 'btn-outline-primary',
    'classBtnDelete'  => 'btn-outline-danger',
    'classBtnOptions' => 'btn-outline-warning',

    'dtBtnAdd'   => 'btn btn-outline-success',
    'dtBtnLiner' => 'btn btn-outline-secondary',

    /*
    |--------------------------------------------------------------------------
    | Default MSG Store
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir un mensaje predeterminado al momento de
    | guardar utilizando el Krud.
    |
    */

    'storeMSG' => 'Datos guardados exitosamente.',

    /*
    |--------------------------------------------------------------------------
    | Clases Badge
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir las clases para dar estilos a los badges
    | o labels
    |
    */

    'badge' => 'badge badge',

    /*
    |--------------------------------------------------------------------------
    | Formato de ícono
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir el formato del menu
    |
    */

    'iconFormat' => '<i {{icono}}></i>',

    /*
    |--------------------------------------------------------------------------
    | Clases Menu
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir las clases para dar estilos gráficos al menu
    |
    */

    'menu' => [
        'ul' => [
            'id' => 'side-menu',
            'class' => 'metismenu list-unstyled mm-show',
        ],
        'li-parent' => [
            'class' => '',
            'layout' =>
                '<a href="{{url}}" aria-expanded="false">
                    {{iconFormat}}
                    <span>{{label}}</span>
                </a>',
            'layout-without-son' =>
                '<a href="{{url}}" class="waves-effect">
                    {{iconFormat}}
                    <span>{{label}}</span>
                </a>',
        ],
        'li-jr' => [
            'class' => '',
            'layout' =>
                '<a href="{{url}}">
                    <span>{{label}}</span>
                </a>',
        ],
        'ul-jr' => [
            'aria-expanded'=>'false',
            'class' => 'collapse in'
        ],
        'ul-jr-divStyle' => [
            'class' => ''
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    |
    |                  Configuraciones para controllers
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    |--------------------------------------------------------------------------
    | Agregar campos custom para usuarioController
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir valores custom para el controlador de usuario
    |
    */

    // 'userCustomField' => [
    //     ['nombre' => 'nit', 'campo' => 'nit']
    // ],

    /*
    |
    |--------------------------------------------------------------------------
    | Agregar campos custom para sucursalesController
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir valores custom para el controlador de sucursales
    |
    */

    // 'sucursalesCustomField' => [
    //     ['nombre' => 'nit', 'campo' => 'nit']
    // ],



];