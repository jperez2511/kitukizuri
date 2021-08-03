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
    | Iconos
    |--------------------------------------------------------------------------
    |
    | Esta opcion permite definir los iconos que se mostraran en los botones de
    | la tabla en la vista index, en tal caso que no se quisiera usar los 
    | iconos predeterminados (Font-Awesome, Material icons)
    | 
    */

    'edit'    => 'zmdi zmdi-edit',
    'delete'  => 'zmdi zmdi-delete',
    'options' => 'fa fa-plus',

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

    'barge' => 'badge badge',

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
        ]
    ],
];