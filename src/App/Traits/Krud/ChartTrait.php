<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

use Illuminate\Support\Collection;
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

        $categoryColumn = '';

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

            if($campo['isCategory'] == true) {
                $categoryColumn = $campo['campo'];
            }
        }

        $result = $this->getData();

        $seriesColumns = $this->getSelectShow();

        $result = $this->transformDataHC($result, $campo['campo'], ['']);

        return response()->json($result);   
    }
    
    /**
     * transformData
     *
     * @param  mixed $data
     * @return void
     */
    private function transformDataHC(Collection $data, string $categoryColumn, array $seriesColumns): array
    {
        $result = [
            'categories' => [],
            'series' => []
        ];
    
        // Agrupar por la columna de categorías
        $grouped = $data->groupBy($categoryColumn);
    
        // Generar categorías
        $result['categories'] = $grouped->keys()->toArray();
    
        // Generar series dinámicas
        foreach ($seriesColumns as $column) {
            $seriesData = $grouped->map(fn($group) => $group->sum($column))->toArray();
    
            $result['series'][] = [
                'name' => ucfirst(str_replace('_', ' ', $column)),
                'data' => array_values($seriesData)
            ];
        }
    
        return $result;
    }
}