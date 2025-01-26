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
    protected function show Chart($id, $request)
    {
        $data = $request->data;
        $data = json_decode($data, true);

        $categoryColumn = '';

        foreach ($this->campos as $campo) {
            if(!empty($campo['isFilter']) && $campo['isFilter'] == true) {
                if($campo['tipo'] == 'dateRange') {
                    $range = $data[$campo['inputName']];
                    if($range['startDate'] != '' && $range['endDate'] != '') {
                        $this->queryBuilder = $this->queryBuilder->whereBetween($campo['campo'], [$range['startDate'], $range['endDate']]);
                    } else if ($range['startDate'] != '') {
                        $this->queryBuilder = $this->queryBuilder->where($campo['campo'], '>=', $range['startDate']);
                    } else if ($range['endDate'] != '') {
                        $this->queryBuilder = $this->queryBuilder->where($campo['campo'], '<=', $range['endDate']);
                    }
                }
            }

            if(!empty($campo['isCategory']) && $campo['isCategory'] == true) {
                $categoryColumn = $campo['campo'];
            }
        }

        $result = $this->getData();
        $result[0] = $this->formatDataChart($result[0]);

        $seriesColumns = collect($this->getSelectShow())->pluck('campo')->toArray();
        $seriesColumns = array_values(array_diff($seriesColumns, [$categoryColumn]));

        $result = $this->transformDataHC($result[0], $categoryColumn, $seriesColumns);

        return response()->json($result);   
    }

    private function formatDataChart($data) 
    {
        foreach ($data as &$a) {
            $tempArray = $a->toArray();
            foreach ($tempArray as $k => &$v) {
                $v = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
                foreach ($this->campos as $cn => $cv) {
                    if ($k == $cv['campo']) {
                        if ($cv['tipo'] == 'date' || $cv['tipo'] == 'datetime' || $cv['tipo'] == 'dateRange') {
                            if (!empty($v)) {
                                $time = strtotime($v);
                                $a->{$k} = $cv['format'] != '' ? date($cv['format'], $time) : date('d-m-Y', $time);
                            }
                        }
                    }
                }
            }
        }
        return $data;
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
    
        // Inicializar las series
    $seriesData = [];

    foreach ($seriesColumns as $seriesColumn) {
        // Detectar todos los valores únicos de la columna actual
        $uniqueValues = $data->pluck($seriesColumn)->unique();

        foreach ($uniqueValues as $value) {
            // Crear una serie para cada valor único
            $seriesKey = $seriesColumn . ': ' . $value;
            if (!isset($seriesData[$seriesKey])) {
                $seriesData[$seriesKey] = [
                    'name' => ucfirst(str_replace('_', ' ', $seriesKey)),
                    'data' => []
                ];
            }
        }
    }

    // Contar los valores únicos en cada categoría
    foreach ($grouped as $category => $items) {
        foreach ($seriesColumns as $seriesColumn) {
            $uniqueValues = $data->pluck($seriesColumn)->unique();

            foreach ($uniqueValues as $value) {
                $seriesKey = $seriesColumn . ': ' . $value;

                // Contar cuántos elementos tienen el valor actual en la categoría
                $count = $items->where($seriesColumn, $value)->count();
                $seriesData[$seriesKey]['data'][] = $count;
            }
        }
    }

    // Añadir las series al resultado
    $result['series'] = array_values($seriesData);
    
        return $result;
    }
}