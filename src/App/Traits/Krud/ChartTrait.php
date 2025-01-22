<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

use Illuminate\Database\Query\Expression;

trait ChartTrait
{    
    /**
     * showChart
     *
     * @param  mixed $id
     * @param  mixed $request
     * @return void
     */
    protected function showChart($id, $request)
    {
        $data = $request->data;
        $data = json_decode($data, true);

        foreach ($this->campos as $campo) {
            if(!empty($campo['isFilter']) && $campo['isFilter'] == true) {
                if($campo['tipo'] == 'dateRange') {
                    $range = $data[$campo['inputName']];
                    if($range['startDate'] != '' && $range['endDate'] != '') {
                        $this->model = $this->model->whereBetween($campo['campo'], [$range['startDate'], $range['endDate']]);
                    } else if ($range['startDate'] != '') {
                        $this->model = $this->model->where($campo['campo'], '>=', $range['startDate']);
                    } else if ($range['endDate'] != '') {
                        $this->model = $this->model->where($campo['campo'], '<=', $range['endDate']);
                    }
                }
            }
        }

        $result = $this->getData();

        $result = $this->transformData($result);

        return response()->json($result);   
    }
    
    /**
     * transformData
     *
     * @param  mixed $data
     * @return void
     */
    private function transformData($data)
    {
        
    }
}