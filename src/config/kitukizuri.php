<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Layout
    |--------------------------------------------------------------------------
    |
    | Esta opcion permite agregar un layout por defecto a las vistas del krud
    | es necesario recordar que tambien se puede definirse en el controller si
    | fuera necesario tener un layout personalizado en especifico
    | 
    */

    'layout' => 'layouts.app',
    
    /*
    |--------------------------------------------------------------------------
    | Bootstrap Version
    |--------------------------------------------------------------------------
    |
    | Esta opcion permite definir la version de Boostrap que esta utilizando el
    | template
    | 
    */

    'vBootstrap' => '',

    /*
    |--------------------------------------------------------------------------
    | Iconos y Estilos
    |--------------------------------------------------------------------------
    |
    | Esta opcion permite definir los iconos que se mostraran en los botones de
    | la tabla en la vista index, en tal caso que no se quisiera usar los 
    | iconos predeterminados (Font-Awesome, Material icons)
    | 
    */

    'edit'    => 'mdi mdi-pencil',
    'delete'  => 'mdi mdi-trash-can-outline',
    'options' => 'fa fa-plus',

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
    | Esta opcion permite definir un mensaje predeterminado al momento de 
    | guradar utilizando el KRUD. 
    | 
    */

    'storemsg' => 'Datos guardados exitosamente.',

    /*
    |--------------------------------------------------------------------------
    | Clases Bage
    |--------------------------------------------------------------------------
    |
    | Esta opcion permite definir las clases para dar estilos a los badges
    | o labels
    |
    */

    'badge' => 'badge badge',

    /*
    |--------------------------------------------------------------------------
    | Clases Menu
    |--------------------------------------------------------------------------
    |
    | Esta opcion permite definir las clases para dar estilos graficos al menu
    | 
    */

    'menu' => [
        'ul' => [
            'id' => '',
            'class' => ''
        ],
        'li-parent' => [
            'class' => '',
            'layout' => 
                '<a href="{{url}}" aria-expanded="false">
                    <span class="has-icon">
                        <i class="{{icono}}"></i>
                    </span>
                    <span class="nav-title">{{label}}</span>
                </a>',
        ],
        'li-jr' => [
            'class' => '',
            'layout' => 
                '<a href="{{url}}">
                    <span class="nav-title">{{label}}</span>
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
];