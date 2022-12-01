<?php

namespace Project\Controller;

use Exception;
use Project\Lib\Helpers;
use Project\Model\{Produtos, Usuarios, Admin};
use Project\Service\Validacao;

class HomeController
{

    private $parametros, $produtos, $usuarios, $admin;
    private $twig;

    function __construct()
    {
        $this->produtos = new Produtos();
        $this->usuarios = new Usuarios();
        $this->admin = new Admin;
        $this->twig = Helpers::Loader();
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
        if (isset($_SESSION['dados'])) {
            $this->parametros['dados'] = $_SESSION['dados'];
            unset($_SESSION['dados']);
        }
    }

    public function index()
    {
        $slides = new \Project\Model\Slider();
        $this->parametros['slides'] = $slides->selectSlides();;
        $this->parametros['produtos'] = $this->produtos->selecionaTodosDestaques();
        $conteudo = $this->twig->render('home/home.html', $this->parametros);
        echo $conteudo;
    }

    public function login()
    {
        if (!isset($this->parametros['user'])) {
            $conteudo = $this->twig->render('registros/Login.html', $this->parametros);
            echo $conteudo;
        } else {
            echo '<script>location.href = "?pag=minhaconta"</script>';
        }
    }
    public function logar()
    {
        if (!isset($this->parametros['user']) && isset($_POST)) {
            extract($_POST);
            try {
                if ($_SESSION['user'] = $this->usuarios
                    ->setUsername($username)
                    ->setSenha($password)
                    ->logarUsuario()
                ) {
                    $_SESSION['user']->dnasc = date('d/m/Y', strtotime($_SESSION['user']->dnasc));
                    $_SESSION['sucesso'] = 'Logado com sucesso. 😉';
                    echo '<script>location.href = "?pag=home"</script>';
                }
            } catch (Exception $e) {
                $_SESSION['dados'] = $_POST;
                $_SESSION['error'] = $e->getMessage() . ' 😫';
                echo '<script>
                        location.href = "?pag=home&metodo=login";
                    </script>';
            }
        } else {
            echo '<script>location.href = "?pag=minhaconta"</script>';
        }
    }

    public function registro()
    {   
        if (!isset($this->parametros['user'])) {
            $conteudo = $this->twig->render('registros/Registrar.html', $this->parametros);
            echo $conteudo;
        } else {
            echo '<script>location.href = "?pag=minhaconta"</script>';
        }
    }
    public function registrar()
    {
        if (!isset($this->parametros['user']) && isset($_POST)) {

            try {
                Validacao::verificarRegistro($_POST);
                $_SESSION['sucesso'] = 'Cadastro realizado com sucesso. 🤑';
                echo '<script> location.href = "?pag=home&metodo=login" </script>';
            } catch (Exception $e) {
                $_SESSION['dados'] = $_POST;
                $_SESSION['error'] = $e->getMessage() . ' 😟';
                echo '<script>
                        location.href = "?pag=home&metodo=registro";
                    </script>';
            }
        } else {
            echo '<script>location.href = "?pag=minhaconta"</script>';
        }
    }


    public function admin()
    {
        if (!isset($_SESSION['admin'])) {
            $conteudo = $this->twig->render('admin/login.html', $this->parametros);
            echo $conteudo;
        } else {
            echo '<script> location.href = "?pag=admin" </script>';
        }
    }
    public function inAdmin()
    {
        if (isset($_POST['email'], $_POST['password'])) {
            try {
                $email = $_POST['email'];
                $password = $_POST['password'];

                if ($login = $this->admin->setEmail($email)->setPassword($password)->logar()) {
                    $_SESSION['admin'] = $login;
                    $_SESSION['sucesso'] = 'Logado como admin com sucesso 🤠';
                } else {
                    throw new Exception('Senha ou Email invalido');
                }
                echo '<script> location.href = "?pag=admin" </script>';
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage().' 🤖';
                echo '<script> location.href = "?pag=home&metodo=admin" </script>';
            }
        }
        
    }
}
