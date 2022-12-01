<?php

namespace Project\Controller;

use Exception;
use Project\Lib\Helpers;
use Project\Model\Produtos;

class CarrinhoController
{
    private $parametros, $produtos;
    private $twig;

    public function __construct()
    {
        $this->produtos = new Produtos();
        $this->twig = Helpers::Loader();
        $this->parametros['get'] = $_GET;
        $this->parametros['carrinho'] = $_SESSION['carrinho'];
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
        $conteudo = $this->twig->render('carrinho/carrinho.html', $this->parametros);
        echo $conteudo;
    }

    public function adicionar($id)
    {
        try {
            if (!isset($id) || empty($id) || !is_numeric($id)) {
                throw new Exception("É necessario um identificador para adicionar um produto");
            }
            if (!isset($_POST['time']) || !is_numeric($_POST['time'])) {
                throw new Exception("É necessario a quantidade de tempo para alugar um produto");
            }
            if (!isset($_POST['quant']) || empty($_POST['quant']) || !is_numeric($_POST['quant'])) {
                throw new Exception("É necessario a quantidade do produto");
            }

            if (!$produto = $this->produtos
                ->setCodigo($id)
                ->selecionaProdutoCarrinho()) {
                throw new Exception("O produto que você tentou adicionar ao carrinho não existe");
            }

            if ($produto->estoque < $_POST['quant']) {
                $_SESSION['error'] = $produto->nome.' possui apenas '.$produto->estoque.' item(s) no estoque';
                echo '<script> location.href = "?pag=produtos&metodo=produto&id='.$produto->codigo.'" </script>';
                exit;
            }

            $cararray = array($produto->codigo => array("name" => $produto->nome, "codigo" => $produto->codigo, "qtd" => $_POST['quant'], "time" => $_POST['time'], "price" => $produto->valor, "image" => $produto->img_1, "locatario" => $produto->username, "estoque" => $produto->estoque));

            if (!empty($_SESSION['carrinho'])) {
                if (in_array($id, array_keys($_SESSION['carrinho']))) {
                    throw new Exception("Esse produto já foi adicionado ao seu carrinho");
                } else {
                    $_SESSION['carrinho'] = $_SESSION['carrinho'] + $cararray;
                }
            } else {
                $_SESSION['carrinho'] = $cararray;
            }
            
            $_SESSION['sucesso'] = $cararray[$id]['name'] . ' adicionado ao carrinho 😊';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😤';
        }
        echo '<script> location.href = "?pag=carrinho" </script>';
    }

    public function remover($id)
    {
        try {
            if (in_array($id, array_keys($_SESSION['carrinho']))) {
                foreach ($_SESSION['carrinho'] as $key => $value) {
                    if ($id == $key) {
                        unset($_SESSION['carrinho'][$key]);
                    }
                    if (empty($_SESSION['carrinho'])) {
                        unset($_SESSION['carrinho']);
                    }
                }
                $_SESSION['sucesso'] = 'Produto removido com sucesso. 🤫';
            } else {
                throw new Exception("Esse produto não existe no seu carrinho.");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 🐱‍👤';
        }
        echo '<script> location.href = "?pag=carrinho" </script>';
    }

    public function limpar()
    {
        unset($_SESSION['carrinho']);
        echo '<script> location.href = "?pag=carrinho" </script>';
    }

    public function alterar()
    {
        try {
            if (empty($_SESSION['carrinho'])) {
                throw new Exception("Seu carrinho está vazio");
            }
            if (!isset($_POST['quant'], $_POST['time'])) {
                throw new Exception("Valores com nomes diferentes ou variaveis trocadas");
            }
            if (empty($_POST['quant']) || empty($_POST['time'])) {
                throw new Exception("A quantidade e o periodo não devem estar vazio");
            }
            if (count($_POST['quant']) != count($_SESSION['carrinho']) || count($_POST['time']) != count($_SESSION['carrinho'])) {
                throw new Exception("O numero de valores não bate com o numero de produtos no carrinho");
            }

            for ($i = 0; $i < count($_POST['quant']); $i++) {
                if (!is_numeric($_POST['quant'][$i]) || !is_numeric($_POST['time'][$i])) {
                    throw new Exception("A quantidade e o periodo dever ser numeros");
                }
                if ($_POST['quant'][$i] <= 0 || $_POST['time'][$i] <= 0) {
                    throw new Exception("A quantidade ou o periodo devem ser maior que zero");
                }
            }
            $i = 0;

            foreach ($_SESSION['carrinho'] as $key => $value) {
                if ($_POST['quant'][$i] > $_SESSION['carrinho'][$key]['estoque']) {
                    throw new Exception($_SESSION['carrinho'][$key]['name']." possui apenas ".$_SESSION['carrinho'][$key]['estoque']." item(s) no estoque");
                } else {
                    $_SESSION['carrinho'][$key]['qtd'] = $_POST['quant'][$i];
                    $_SESSION['carrinho'][$key]['time'] = $_POST['time'][$i];
                }
                $i += 1;
            }
            $_SESSION['sucesso'] = 'Carrinho alterado com sucesso';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 🤨';
        }
        echo '<script> location.href = "?pag=carrinho" </script>';
    }
}
