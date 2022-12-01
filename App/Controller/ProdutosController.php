<?php

namespace Project\Controller;

use Exception;
use Project\Lib\Helpers;
use Project\Model\Categorias;
use Project\Model\Comentario;
use Project\Model\Produtos;

class ProdutosController
{

    private $parametros, $produtos, $categorias, $comentarios;
    private $twig;

    function __construct()
    {
        $this->comentarios = new Comentario();
        $this->produtos = new Produtos();
        $this->categorias = new Categorias();
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
    }

    public function index()
    {
        $this->parametros['produtos'] = $this->produtos->selecionaTodos();
        $this->parametros['categorias'] = $this->categorias->getAllCategorias();
        $conteudo = $this->twig->render('produtos/catalogo.html', $this->parametros);
        echo $conteudo;
    }

    public function produto($id)
    {
        try {
            if (!$this->produtos
                ->setCodigo($id)->verificarProdExists()) {
                throw new Exception('Esse produto não existe mais');
            }

            $this->parametros['produtos'] = $this->produtos
                ->selecionaProduto();

            if ($this->parametros['produtos']) {
                $this->parametros['comentarios'] = $this->comentarios->setCodigo($id)->selecionarComentarioProduto();
                if (isset($this->parametros['user'])) {
                    if ($this->parametros['produtos']->dono_post != $this->parametros['user']->id_u) {
                        $conteudo = $this->twig->render('produtos/produto.html', $this->parametros);
                    } else {
                        echo '<script> location.href = "?pag=minhaconta&metodo=vender&id=' . $id . '" </script>';
                    }
                } else {
                    $conteudo = $this->twig->render('produtos/produto.html', $this->parametros);
                }
            } else {
                echo '<script> alert("produto não existe"); location.href = "?pag=produtos" </script>';
            }
            echo $conteudo;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 🤨';
            echo '<script> location.href = "?pag=produtos" </script>';

        }
    }

    public function buscar($id)
    {
        if ($id) {
            $this->parametros['search'] = $id;
            $this->parametros['produtos'] = $this->produtos->FiltrarProdutos($this->parametros['search']);
            $this->parametros['categorias'] = $this->categorias->getAllCategorias();
            $conteudo = $this->twig->render('produtos/catalogo.html', $this->parametros);
        } else {
            echo '<script> alert("produto não existe"); location.href = "?pag=produtos" </script>';
        }
        echo $conteudo;
    }
}
