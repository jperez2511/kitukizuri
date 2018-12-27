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

];