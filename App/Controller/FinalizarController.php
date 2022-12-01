<?php

namespace Project\Controller;

use Exception;
use Project\Lib\Helpers;
use Project\Model\Pedidos;

class FinalizarController
{

    private $twig;
    private $parametros, $pedidos;

    function __construct()
    {
        $this->twig = Helpers::Loader();
        $this->parametros['get'] = $_GET;
        $this->pedidos = new Pedidos();
        if (isset($_SESSION['sucesso'])) {
            $this->parametros['sucesso'] = $_SESSION['sucesso'];
            unset($_SESSION['sucesso']);
        }
        if (isset($_SESSION['error'])) {
            $this->parametros['error'] = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        if (empty($_SESSION['carrinho'])) {
            echo '<script> location.href = "?pag=produtos" </script>';
        } else {
            $this->parametros['carrinho'] = $_SESSION['carrinho'];
        }
        if (!isset($_SESSION['user'])) {
            echo '<script> location.href = "?pag=home&metodo=login" </script>';
        } else {
            $this->parametros['user'] = $_SESSION['user'];
        }
    }

    public function index()
    {
        $conteudo = $this->twig->render('finalizar/finalizar.html', $this->parametros);
        echo $conteudo;
    }

    public function confirmar()
    {
        try {
            if (empty($_SESSION['user']->CPF) || empty($_SESSION['user']->CEP)) {
                throw new Exception('Para finalizar o pedido é necessario ter CPF e CEP cadastrados');
            }
            
            if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
                throw new Exception('Seu carrinho está vazio no momento');
            }

            $codigo = uniqid('pedido');
            $id = $_SESSION['user']->id_u;

            if ($this->pedidos->setCodigo($codigo)->setId_u($id)->inserirPedido()) {
                foreach ($_SESSION['carrinho'] as $key => $value) {
                    $valor = $value['price'] * $value['time'] * $value['qtd'];

                    if (!$this->pedidos->setCod_pedido($codigo)
                        ->setCodigo_prod($value['codigo'])
                        ->setValor_tot($valor)
                        ->setQtd_produto($value['qtd'])
                        ->setPeriodo($value['time'])
                        ->inserirProduto_Pedido()) {
                        throw new Exception('Falha ao adicionar produto ao seu pedido');
                    }
                }
                unset($_SESSION['carrinho']);
                $_SESSION['sucesso'] = 'Pedido inserido com sucesso';
                echo '<script> location.href = "?pag=minhaconta" </script>';
            } else {
                throw new Exception('Algo deu errado');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            echo '<script> location.href = "?pag=carrinho" </script>';
        }
    }
}
