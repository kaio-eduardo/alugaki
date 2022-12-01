<?php

namespace Project\Controller;

use Project\Lib\Helpers;

class ErroController
{
    private $twig;

    public function __construct()
    {
        $this->twig = Helpers::Loader();
    }

    public function index()
    {
        $conteudo = $this->twig->render('erro/erro404.html');
        echo $conteudo;
    }
}
