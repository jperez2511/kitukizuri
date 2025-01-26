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

    'classBtnEdit'    => 'btn-sm btn-outline-primary',
    'classBtnDelete'  => 'btn-sm btn-outline-danger',
    'classBtnOptions' => 'btn-sm btn-outline-warning',

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

    'badge' => 'badge bg',

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
            'class' => 'nk-menu nk-menu-md',
        ],
        'li-parent' => [
            'class' => 'nk-menu-item has-sub',
            'layout' =>
                '<a href="{{url}}" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon">{{iconFormat}}</span>
                    <span class="nk-menu-text">{{label}}</span>
                </a>',
            'layout-without-son' =>
                '<a href="{{url}}" class="nk-menu-link">
                    <span class="nk-menu-icon">{{iconFormat}}</span>
                    <span class="nk-menu-text">{{label}}</span>
                </a>',
        ],
        'li-jr' => [
            'class' => 'nk-menu-item',
            'layout' =>
                '<a href="{{url}}" class="nk-menu-link">
                    <span class="nk-menu-icon">{{iconFormat}}</span>
                    <span style="font-size:12px;">{{label}}</span>
                </a>',
        ],
        'ul-jr' => [
            'aria-expanded'=>'false',
            'class' => 'nk-menu-sub'
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

    /*
    |
    |--------------------------------------------------------------------------
    | Agregar campos custom para empresasController
    |--------------------------------------------------------------------------
    |
    | Esta opción permite definir valores custom para el controlador de empresas
    |
    */

    // 'companiesCustomField' => [
    //     ['name' => 'nit', 'field' => 'nit']
    // ],

    // 'companiesCustomButtons' => [
    //     ['name' => 'nit', 'url' => 'nit', 'icon' => 'fa fa-eye', 'class' => 'btn btn-outline-primary']
    // ],


];