<?php

namespace Project\Core;

class Core
{

    private $acao;
    private $controller;
    private $id;
    private $path;
    private $caminho;

    function __construct()
    {
        $this->controller = 'HomeController';
        $this->acao = 'index';
        $this->path = '\\Project\\Controller\\';
        $this->caminho = $this->path . $this->controller;
        $this->id = null;
    }

    public function start($urlGet)
    {
        if (isset($urlGet['metodo'])) {
            $this->acao = $urlGet['metodo'];
        }

        if (isset($urlGet['pag'])) {
            $this->controller = ucfirst($urlGet['pag'] . 'Controller');
            $this->caminho = $this->path . $this->controller;
        }

        if (!class_exists($this->caminho) || !method_exists($this->caminho, $this->acao)) {
            $this->controller = 'ErroController';
            $this->acao = 'index';
            $this->caminho = $this->path . $this->controller;
        }

        if (isset($urlGet['id']) && $urlGet['id'] != null) {
            $this->id = $urlGet['id'];
        }

        call_user_func_array(array(new $this->caminho, $this->acao), array('id' => $this->id));
    }
}
