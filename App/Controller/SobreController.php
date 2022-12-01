<?php

namespace Project\Controller;

use Project\Lib\Helpers;
use Project\Model\Config;

class SobreController
{
    private $parametros, $config;
    private $twig;

    public function __construct()
    {
        $this->twig = Helpers::Loader();
        $this->config = new Config();
        $this->parametros['get'] = $_GET;
        if (isset($_SESSION['sucesso'])) {
            $this->parametros['sucesso'] = $_SESSION['sucesso'];
            unset($_SESSION['sucesso']);
        }
        if (isset($_SESSION['error'])) {
            $this->parametros['error'] = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['user'])) {
            $this->parametros['user'] = $_SESSION['user'];
        }
    }

    public function index()
    {
        $this->parametros['config'] = $this->config->selecionaSobre();
        $conteudo = $this->twig->render('sobre/sobre.html', $this->parametros);
        echo $conteudo;
    }
}
