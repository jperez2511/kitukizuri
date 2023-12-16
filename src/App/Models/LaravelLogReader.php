<?php

namespace Icebearsoft\Kitukizuri\App\Models;

class LaravelLogReader
{
    protected $final = [];
    protected $config = [];

    public function __construct($config = [])
    {
        if(array_key_exists('date', $config)) {
            $this->config['date'] = $config['date'];
        } else {
            $this->config['date'] = null;
        }
    }

    public function get()
    {
        $result      = [];
        $filterDate  = explode(' ',$this->config['date']);
        $fileName    = 'laravel.log';
        $segmentSize = 1024 * 1024;
        $handle      = fopen(storage_path('logs/' . $fileName), "r");
        $pattern     = "/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/";

        while (!feof($handle)) {
            // Leer un segmento del archivo
            $content = fread($handle, $segmentSize);
            // Asegúrate de no cortar un bloque a la mitad
            $extraContent = fgets($handle);
            $content .= $extraContent;
            // Procesar el contenido del segmento
            $bloques = preg_split($pattern, $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            for ($i = 0; $i < count($bloques); $i += 2) {
                if (isset($bloques[$i + 1])) {
                    $bloque = [
                        'date' => $bloques[$i],
                        'msg' => '',  // Aquí se almacenará el mensaje de error
                        'stacktrace' => ''  // Aquí se almacenará el stacktrace
                    ];

                    // Dividir el bloque en mensaje y stacktrace (si es necesario)
                    // Dependerá de cómo estén estructurados tus logs
                    // Esta es una forma básica de hacerlo, puede requerir ajustes
                    $parts = explode("\n", trim($bloques[$i + 1]));
                    $bloque['msg'] = array_shift($parts);  // El primer elemento es el mensaje
                    $bloque['stacktrace'] = implode("\n", $parts);  // El resto es el stacktrace

                    $result[] = $bloque;
                }
            }
        }

        $result = collect($result);

        if(!empty($this->config['date'])) {
            $result = $result->filter(fn($item) => str_starts_with($bloque['date'], $this->config['date']));
        }

        $result = $result->sortByDesc('date');

        return $result;
    }


}
