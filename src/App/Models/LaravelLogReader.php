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
        $filterDate = explode(' ',$this->config['date']);

        $pattern ="/^\[(?'date'".$filterDate[0]." [0-9]*:[0-9]*:[0-9]*)\]\s(?<env>\w+)\.(?<type>\w+):(?<message>.*(\R*.*)*)/m";
        
        $fileName  = 'laravel.log';
        $content   = file_get_contents(storage_path('logs/' . $fileName));
        $splitData = preg_split("/(?<=\[\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\])/", $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    
        $fecha = $splitData[0];
        unset($splitData[0]);

        $logs = [];
        foreach($splitData as $split) {
            // fecha siguiente elemento
            preg_match_all("/\[\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\]/m", $split, $matches, PREG_SET_ORDER, 0);

            if(!empty($matches)) {
                $fechaNext = $matches[0][0];
                $split = str_replace($fechaNext, '', $split);
            }

            $split = $fecha.$split;

            preg_match_all($pattern, $split, $matches, PREG_SET_ORDER, 0);
            
            foreach ($matches as $match) {
                $logs[] = [
                    'timestamp' => $match['date'],
                    'env'       => $match['env'],
                    'type'      => $match['type'],
                    'message'   => $match['message']
                ];
            }
            $fecha = $fechaNext;
        }

        $logs = collect($logs);
        
        if(!empty($this->config['date'])){
            $data = $logs->filter(function($item) use($filterDate){
                return $item['timestamp'] >= $this->config['date'];
            });
        } else {
            $data = $logs;
        }


        return $data;
    }


}
