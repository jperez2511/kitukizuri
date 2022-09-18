var data = {
    "setModelo" : {
        "titulo": "Debes configurar el <code>Modelo</code>",
        "comentario": "Antes de todo siempre se debe configurar un modelo, puesto que va a ser el encargado de hacer el mapeo de los campos en al base de datos, asi como el encargado de guardar, editar, eliminar los datos.",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; // <- Llamando al modelo \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training); // <- configurando modelo (lo que falta en tu código)\n\t}\n}"
    },
    "setCampo" : {
        "titulo": "Debes agregar la propiedad <code>Campo</code>",
        "comentario": "La propiedad Campo debe ser el nombre de la columna de la tabla en base de datos, donde se almacenara el valor ingresado por el usuario, si no se define un tipo de campo por defecto es texto abierto",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\'nombre\\'=>\\'Label del campo\\', \\'campo\\'=>\\'nombre_columna_base_de_datos\\']);\n\t}\n}"
    },
    "badType" : {
        "titulo": "El tipo de campo <code>{bad}</code> No existe",
        "comentario": "El tipo de campo define si será un texto, un campo numerico, etc. Para ello se han definido los siguientes tipos permitidos:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\'nombre\\'=>\\'Label del campo\\', \\'campo\\'=>\\'nombre_columna_base_de_datos\\', \\'tipo\\'=>\\'bool\\']);\n\t}\n}"
    },
    "badView" : {
        "titulo": "La vista <code>{bad}</code> No existe",
        "comentario": "La vista permite mostrar en el index si sera una tabla o un calendario:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setView(\\'calendar\\');\n\t}\n}"
    },
    "badOptionsView" : {
        "titulo": "La opción <code>{bad}</code> No existe",
        "comentario": "La vista permite las siguientes opciones:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setView(\\'calendar\\', [\\'public\\'=> true]);\n\t}\n}"
    },
    "badCalendarView" : {
        "titulo": "La vista <code>{bad}</code> No existe",
        "comentario": "La vista por defecto (Default View) permite definir como se mostrara el calendario ya sea por mes, semana o un dia en concreto estas son las vistas permitidas:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setView(\\'calendar\\');\n\t\t$this->setCalendarDefaultView(\\'month\\');\n\t}\n}"
    },
    "badTypeButton" : {
        "titulo": "El parámetro <code>{bad}</code> No existe",
        "comentario": "Los parámetros permitidos para configurar un botón son los siguientes:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setBoton([\\'nombre\\'=>\\'Label del boton\\', \\'url\\'=>\\'URL\\', \\'class\\'=>\\'btn btn-success\\', \\'icon\\'=>\\'fa fa-trash\\']);\n\t}\n}"
    }, 
    "typeCombo" : {
        "titulo": "Falta la propiedad <code>Collect</code>",
        "comentario": "El tipo de campo ComboBox requiere de la propiedad collect, este puede ser un collection de Laravel con dos elementos, tal como se muestra en el ejemplo: ",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \nuse Icebearsoft\\\\Models\\\\Example; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$collection = Example::select(\\'id\\', \\'value\\')->get();\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\'nombre\\'=>\\'Label del campo\\', \\'campo\\'=>\\'nombre_columna_base_de_datos\\', \\'tipo\\'=>\\'combobox\\', \\'collect\\'=>$collection]);\n\t}\n}"
    }, 
    "typeCollect" : {
        "titulo": "no se encontró al propiedad <code>id</code> y/o <code>value</code>",
        "comentario": "Al definir el collection puede aplicar la siguiente estructura:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \nuse Icebearsoft\\\\Models\\\\Example; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$collection = Example::select(\\'nombreDeColumna as id\\', \\'nombreDeColumna as value\\')->get();\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\'nombre\\'=>\\'Label del campo\\', \\'campo\\'=>\\'nombre_columna_base_de_datos\\', \\'tipo\\'=>\\'combobox\\', \\'collect\\'=>$collection]);\n\t}\n}"
    }, 
    "filepath" : {
        "titulo": "Falta la propiedad <code>FilePath</code>",
        "comentario": "El tipo de campo file requiere de la propiedad filepath, siendo esta la ruta donde se almacenaran los archivos dentro del servidor, esta puede ser una ruta relativa o absoluta por ejemplo:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\'nombre\\'=>\\'Label del campo\\', \\'campo\\'=>\\'nombre_columna_base_de_datos\\', \\'tipo\\'=>\\'file\\', \\'filepath\\'=>\\'/path/de/la/carpeta\\']);\n\t}\n}"
    }, 
    "enum" : {
        "titulo": "Falta la propiedad <code>EnumArray</code>",
        "comentario": "El tipo de campo enum requiere de la propiedad EnumArray, aquí es donde se definen las opciones que estarán disponibles, es importante recordad que deben ser las mismas que se han definido en la base de datos, por ejemplo:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\'nombre\\'=>\\'Label del campo\\', \\'campo\\'=>\\'nombre_columna_base_de_datos\\', \\'tipo\\'=>\\'file\\', \\'filepath\\'=>\\'/path/de/la/carpeta\\']);\n\t}\n}"
    }, 
    "value" : {
        "titulo": "Falta la propiedad <code>Value</code>",
        "comentario": "El tipo de campo hidden requiere de la propiedad value, ya que es un valor predeterminado se debe definir, por ejemplo: ",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\'nombre\\'=>\\'Label del campo\\', \\'campo\\'=>\\'nombre_columna_base_de_datos\\', \\'tipo\\'=>\\'hidden\\', \\'value\\'=>\\'valorPredeterminado\\']);\n\t}\n}"
    }, 
    "badJoinOperator" : {
        "titulo": "El operador <code>{bad}</code> No es un operador valido",
        "comentario": "Los parámetros permitidos son los siguientes:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setJoin('tabla as t', 'valor 1', 'valor 2'); // <- operador = por defecto \n\t\t$this->setJoin('tabla as t', 'valor 1', '<>','valor 2'); // <- diferente operador \n\t}\n}"
    }, 
    "badLeftJoinOperator" : {
        "titulo": "El operador <code>{bad}</code> No es un operador valido",
        "comentario": "Los parámetros permitidos son los siguientes:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setLeftJoin('tabla as t', 'valor 1', 'valor 2'); // <- operador '=' por defecto \n\t\t$this->setLeftJoin('tabla as t', 'valor 1', '>','valor 2'); // <- diferente operador \n\t}\n}"
    }, 
    "badWhereOperator" : {
        "titulo": "El operador <code>{bad}</code> No es un operador valido",
        "comentario": "Los parámetros permitidos son los siguientes:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setWhere('valor 1', 'valor 2'); // <- operador = por defecto \n\t\t$this->setWhere('valor 1', '!=','valor 2'); // <- diferente operador \n\t}\n}"
    }, 
    "badOrWhereOperator" : {
        "titulo": "El operador <code>{bad}</code> No es un operador valido",
        "comentario": "Los parámetros permitidos son los siguientes:",
        "codigo": "<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setOrWhere('valor 1', 'valor 2'); // <- operador = por defecto \n\t\t$this->setOrWhere('valor 1', '!=','valor 2'); // <- diferente operador \n\t}\n}"
    }
}