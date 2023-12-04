<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

trait UiTrait
{
    protected $layout   = null;
    protected $storeMsg = null;
    protected $titulo   = null;
    protected $template = [
        'datatable',
    ];

    /**
     * setLayout
     * Define el layout a utilizar en el controller
     *
     * @param  mixed $layout
     *
     * @return void
     */
    protected function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * getLayout
     * Define el layout predeterminado
     *
     * @return void
     */
    protected function getLayout()
    {
        return $this->layout ?? config('kitukizuri.layout');
    }

    /**
     * getStoreMSG
     *
     * @return void
     */
    protected function getStoreMSG()
    {
        return $this->storeMsg ?? config('kitukizuri.storemsg');
    }

    /**
     * setStoreMSG
     *
     * @return void
     */
    protected function setStoreMSG($msg)
    {
        $this->storeMsg = $msg;
    }

    /**
     * setTemplate
     * Define las librerías a utilizar por ejemplo DataTable, FontAwesome ,etc.
     *
     * @param  mixed $templates
     *
     * @return void
     */
    protected function setTemplate($templates)
    {
        foreach ($templates as $t) {
            $this->template[] = $t;
        }
    }

    /**
     * setTitulo
     * Define el titulo que se mostrara en pantalla index
     *
     * @param  mixed $titulo
     *
     * @return void
     */
    protected function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }
}