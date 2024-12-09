<x-app-layout>
    <x-slot name="header">
        <h3 class="title">
            {{ __('Help') }}
            </h2>
    </x-slot>

    @php
        $data = [
            'setModelo' => [
                'titulo'     => 'You must configure the <code>Model</code>',
                'comentario' => 'It is essential to configure a model initially, as it will be responsible for mapping the fields in the database, as well as managing the operations of saving, editing, and deleting data.',
                'code'       => '&lt;?php<br><br>namespace kitukizuri&nbsp;raining;<br><br>use Krud;<br>use Icebearsoft\Models&nbsp;raining; // &lt;- Llamando al modelo<br><br>class ExampleController extends Krud<br>{<br>&nbsp;&nbsp;&nbsp;&nbsp;public function __construct()<br>&nbsp;&nbsp;&nbsp;&nbsp;{<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this-&gt;setModel(new Training); // &lt;- configurando modelo (lo que falta en tu código)<br>&nbsp;&nbsp;&nbsp;&nbsp;}<br>}'
            ],
            'setCampo' => [
                "titulo"     => 'Proterty <code>campo</code> is missing',
                "comentario" => 'The Campo property should correspond to the column name in the database table where the user-entered value will be stored. If no specific field type is defined, it will default to plain text.',
                "code"       => '&lt;?php<br><br>namespace kitukizuri&nbsp;raining;<br><br>use Krud;<br>use Icebearsoft\Models&nbsp;raining;<br><br>class ExampleController extends Krud<br>{<br>&nbsp;&nbsp;&nbsp;&nbsp;public function __construct()<br>&nbsp;&nbsp;&nbsp;&nbsp;{<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this-&gt;setModel(new Training);<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this-&gt;setCampo([\'nombre\'=&gt;\'Label del campo\', \'campo\'=&gt;\'nombre_columna_base_de_datos\']);<br>&nbsp;&nbsp;&nbsp;&nbsp;}<br>}<br>'
            ],
            'badType'=> [
                'titulo'     => 'The field type <code>{bad}</code> does not exist',
                'comentario' => 'The field type specifies whether it will be text, a numeric field, etc. The following types are allowed:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setCampo([\'nombre\'=>\'Label del campo\', \'campo\'=>\'nombre_columna_base_de_datos\', \'tipo\'=>\'bool\']);<br>&nbsp;]<br>]'
            ],
            'badView'=> [
                'titulo'     => 'The view <code>{bad}</code> does not exist',
                'comentario' => 'The view allows displaying in the index whether it will be a table or a calendar:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setView(\'calendar\');<br>&nbsp;]<br>]'
            ],
            'badOptionsView'=> [
                'titulo'     => 'The option <code>{bad}</code> does not exist',
                'comentario' => 'The view allows the following options:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setView(\'calendar\', [\'public\'=> true]);<br>&nbsp;]<br>]'
            ],
            'badCalendarView'=> [
                'titulo'     => 'The view <code>{bad}</code> does not exist',
                'comentario' => 'The default view allows defining how the calendar will be displayed, whether by month, week, or a specific day. The following views are permitted:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setView(\'calendar\');<br>&nbsp;&nbsp;$this->setCalendarDefaultView(\'month\');<br>&nbsp;]<br>]'
            ],
            'badTypeButton'=> [
                'titulo'     => 'The parameter <code>{bad}</code> does not exist',
                'comentario' => 'The allowed parameters for button configuration are as follows:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setBoton([\'nombre\'=>\'Label del boton\', \'url\'=>\'URL\', \'class\'=>\'btn btn-success\', \'icon\'=>\'fa fa-trash\']);<br>&nbsp;]<br>]'
            ],
            'typeCombo'=> [
                'titulo'     => 'The property <code>Collect</code> is missing',
                'comentario' => 'The ComboBox field type requires the collect property, which should be a Laravel collection with two elements, as shown in the example:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br>use Icebearsoft\\Models\\Example; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$collection = Example::select(\'id\', \'value\')->get();<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setCampo([\'nombre\'=>\'Label del campo\', \'campo\'=>\'nombre_columna_base_de_datos\', \'tipo\'=>\'combobox\', \'collect\'=>$collection]);<br>&nbsp;]<br>]'
            ],
            'typeCollect'=> [
                'titulo'     => 'The property <code>id</code> and/or <code>value</code> was not found',
                'comentario' => 'When defining the collection, you can apply the following structure:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br>use Icebearsoft\\Models\\Example; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$collection = Example::select(\'nombreDeColumna as id\', \'nombreDeColumna as value\')->get();<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setCampo([\'nombre\'=>\'Label del campo\', \'campo\'=>\'nombre_columna_base_de_datos\', \'tipo\'=>\'combobox\', \'collect\'=>$collection]);<br>&nbsp;]<br>]'
            ],
            'filepath'=> [
                'titulo'     => 'The property <code>FilePath</code> is missing',
                'comentario' => 'El tipo de campo file requiere de la propiedad filepath, siendo esta la ruta donde se almacenaran los archivos dentro del servidor, esta puede ser una ruta relativa o absoluta por ejemplo=>',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setCampo([\'nombre\'=>\'Label del campo\', \'campo\'=>\'nombre_columna_base_de_datos\', \'tipo\'=>\'file\', \'filepath\'=>\'/path/de/la/carpeta\']);<br>&nbsp;]<br>]'
            ],
            'enum'=> [
                'titulo'     => 'The property <code>EnumArray</code> is missing',
                'comentario' => 'The enum field type requires the EnumArray property, which defines the available options. It is important to ensure that these options match those defined in the database, for example:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setCampo([\'nombre\'=>\'Label del campo\', \'campo\'=>\'nombre_columna_base_de_datos\', \'tipo\'=>\'file\', \'filepath\'=>\'/path/de/la/carpeta\']);<br>&nbsp;]<br>]'
            ],
            'value'=> [
                'titulo'     => 'The property <code>Value</code> is missing',
                'comentario' => 'The hidden field type requires the value property, as it is a default value that must be defined, for example:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setCampo([\'nombre\'=>\'Label del campo\', \'campo\'=>\'nombre_columna_base_de_datos\', \'tipo\'=>\'hidden\', \'value\'=>\'valorPredeterminado\']);<br>&nbsp;]<br>]'
            ],
            'badJoinOperator'=> [
                'titulo'     => 'The operator <code>{bad}</code> is not a valid operator',
                'comentario' => 'The allowed parameters are as follows:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setJoin(\'tabla as t\', \'valor 1\', \'valor 2\'); // <- operador = por defecto <br>&nbsp;&nbsp;$this->setJoin(\'tabla as t\', \'valor 1\', \'<>\',\'valor 2\'); // <- diferente operador <br>&nbsp;]<br>]'
            ],
            'badLeftJoinOperator'=> [
                'titulo'     => 'The operator <code>{bad}</code> is not a valid operator',
                'comentario' => 'The allowed parameters are as follows:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setLeftJoin(\'tabla as t\', \'valor 1\', \'valor 2\'); // <- operador \'=\' por defecto <br>&nbsp;&nbsp;$this->setLeftJoin(\'tabla as t\', \'valor 1\', \'>\',\'valor 2\'); // <- diferente operador <br>&nbsp;]<br>]'
            ],
            'badWhereOperator'=> [
                'titulo'     => 'The operator <code>{bad}</code> is not a valid operator',
                'comentario' => 'The allowed parameters are as follows:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setWhere(\'valor 1\', \'valor 2\'); // <- operador = por defecto <br>&nbsp;&nbsp;$this->setWhere(\'valor 1\', \'!=\',\'valor 2\'); // <- diferente operador <br>&nbsp;]<br>]'
            ],
            'badOrWhereOperator'=> [
                'titulo'     => 'The operator <code>{bad}</code> is not a valid operator',
                'comentario' => 'The allowed parameters are as follows:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setOrWhere(\'valor 1\', \'valor 2\'); // <- operador = por defecto <br>&nbsp;&nbsp;$this->setOrWhere(\'valor 1\', \'!=\',\'valor 2\'); // <- diferente operador <br>&nbsp;]<br>]'
            ],
            'badColumnDefinition'=> [
                'titulo'     => 'The parameter column does not have a valid format',
                'comentario' => 'The allowed format is as follows:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$collect = Trainig::select(\'columnaA\', \'columnaB\', \'columnaC\')->get();<br>&nbsp;&nbsp;$this->setCampo([\'nombre\'=>\'Label del campo\', \'campo\'=>\'nombre_columna_base_de_datos\', \'tipo\'=>\'combobox\', \'collect\' => $collect, \'column\'=>[\'ColumnaA\', \'ColumnaB\']]); <br>&nbsp;]<br>]'
            ],
            'needRealField'=> [
                'titulo'     => 'When using <code>DB::raw()</code>, it is necessary to include the <code>CampoReal</code> parameter',
                'comentario' => 'The parameter is included as follows:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setCampo([\'nombre\'=>\'Label del campo\', \'campo\'=>DB::raw(\'now() as fecha_actual\'), \'campoReal\' => \'fecha_actual\']);<br>&nbsp;]<br>]'
            ],
            'needColumnParent' => [
                'titulo'     => 'When using <code>Select2 multiple in another table</code>, it is necessary to include the <code>columnParent</code> parameter',
                'comentario' => 'The parameter is included as follows:',
                'code'       => '&lt;?php <br><br>namespace kitukizuri\&nbsp;raining; <br><br>use Krud; <br>use Icebearsoft\\Models\&nbsp;raining; <br><br>class ExapleController extends Krud<br>[<br>&nbsp;public function __construct()<br>&nbsp;[<br>&nbsp;&nbsp;$this->setModel(new Training);<br>&nbsp;&nbsp;$this->setCampo([\'nombre\'=>\'Label del campo\', \'campo\'=>\'tabla_b.columna\', \'columnParent\' => \'id\']);<br>&nbsp;]<br>]'
            ]
        ]
    @endphp

    <div class="card">
        <div class="card-body">
            @if($tipo == 'help')
                <div></div>
            @else
                <div class="text-center">
                    <h4 class="text-danger">{!! $data[$tipo]['titulo'] !!}</h4>
                    <hr>
                </div>
                <div class="text-justify mb-4">
                    {{ $data[$tipo]['comentario'] }}
                </div>
                <pre class="prettyprint lang-php">{!! $data[$tipo]['code'] !!}</pre>
            @endif
        </div>
    </div>

</x-app-layout>
