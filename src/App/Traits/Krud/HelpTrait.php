<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

trait HelpTrait
{
    protected $errors    = [];
    protected $typeError = [
        'setModelo',            // 0
        'setCampo',             // 1
        'badType',              // 2
        'badOptionsView',       // 3
        'badTypeButton',        // 4
        'badView',              // 5
        'badCalendarView',      // 6
        'typeCombo',            // 7
        'typeCollect',          // 8
        'filepath',             // 9
        'enum',                 // 10
        'value',                // 11
        'badJoinOperator',      // 12
        'badLeftJoinOperator',  // 13
        'badWhereOperator',     // 14
        'badOrWhereOperator',   // 15
        'badColumnDefinition',  // 16
        'needRealField'         // 17
    ];
}