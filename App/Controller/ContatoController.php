<?php

namespace Project\Controller;

use Exception;
use Project\Lib\Helpers;
use Project\Model\{Message};

class ContatoController
{
    private $parametros;
    private $twig, $message;

    function __construct()
    {
        $this->message = new Message();
        $this->parametros['get'] = $_GET;
        $this->twig = Helpers::Loader();
        if (isset($_SESSION['sucesso'])) {
            $this->parametros['sucesso'] = $_SESSION['sucesso'];
            unset($_SESSION['sucesso']);
        }
        if (isset($_SESSION['dados'])) {
            $this->parametros['dados'] = $_SESSION['dados'];
            unset($_SESSION['dados']);
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
        $conteudo = $this->twig->render('contato/contato.html', $this->parametros);
        echo $conteudo;
    }

    public function enviarMessage()
    {
        try {

            if (!isset($_POST['nome'], $_POST['email'], $_POST['Assunto'], $_POST['message'])) {
                throw new Exception('Algo deu errado');
            }

            if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['Assunto']) || empty($_POST['message'])) {
                throw new Exception('Existem campos em branco');
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido');
            }

            if (strlen($_POST['Assunto']) < 8 || strlen($_POST['Assunto']) > 120) {
                throw new Exception('O assunto deve estar entre 8 e 120 caracteres');
            }

            if (strlen($_POST['message']) < 20 || strlen($_POST['message']) > 240 ) {
                throw new Exception('A mensagem deve ter entre 20 e 240 caracteres');
            }

            if (!$this->message->setNome($_POST['nome'])->setEmail($_POST['email'])->setAssunto($_POST['Assunto'])->setMessage($_POST['message'])->inserirMessage()) {
                throw new Exception('Algo deu errado');
            }

            $_SESSION['sucesso'] = 'Mensagem enviada! Um admin está conferindo sua dúvida 😁';

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage().' 🤕';
            $_SESSION['dados'] = $_POST;
        }
        echo '<script> location.href = "?pag=contato" </script>';
    }
}
