<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use DB;
use Krud;
use Illuminate\Http\Request;
use Log as Logger;
use Icebearsoft\Kitukizuri\App\Models\Log;

class LogController extends Krud
{

    public function __construct()
    {
        $this->setModel(new Log());
        $channel = env('LOG_CHANNEL');

        if($channel == 'database')
        {
            $this->setTitle('Logs');
            $this->setField(['name' => 'Usuario', 'field' => 'u.email', 'htmlAttr' => ['disabled' => true]]);
            $this->setField(['name' => 'URL', 'field' => DB::raw('SUBSTRING(url, 1, 50)'), 'campoReal' => 'url', 'htmlAttr' => ['disabled' => true]]);
            $this->setField(['name' => 'Métodos', 'field' => 'method']);
            $this->setField(['name' => 'Nivel', 'field' => 'level']);
            $this->setField(['name' => 'Mensaje', 'field' => 'message']);
            $this->leftJoin('users as u', 'u.id', '=', 'logs.id_user');
            $this->orderBy('id_log', 'desc');
            $this->setButton(['nombre' => 'Ver', 'url' => 'logs/{id}', 'class' => 'btn btn-outline-primary', 'icon' => 'fa fa-eye']);
        }
    }

    public function index()
    {
        $channel = env('LOG_CHANNEL');

        if($channel == 'stack')
        {
            $file = storage_path('logs/laravel.log');
            return response()->download($file, 'laravel.log', ['Content-Type' => 'text/plain']);
        } else if ($channel == 'database') {
            return parent::index();
        }
    }

    public function show($id, Request $request)
    {
        if ($request->ajax() == false) {
            return  $this->edit($id, $request);
        } 

        return parent::show($id, $request);
    }
}