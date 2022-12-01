<?php

namespace Project\Controller;

use Exception;
use Project\Lib\Helpers;
use Project\Model\{Produtos, Favoritos};

class FavoritosController
{
    private $parametros, $favoritos, $produtos;
    private $twig;

    public function __construct()
    {
        $this->produtos = new Produtos();
        $this->favoritos = new Favoritos();
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
        } else {
            echo '<script> location.href = "?pag=home&metodo=login" </script>';
        }
    }

    public function index()
    {
        $this->parametros['produtos'] = $this->favoritos->setId_u($_SESSION['user']->id_u)->selecionaFavoritos();
        $conteudo = $this->twig->render('favoritos/favoritos.html', $this->parametros);
        echo $conteudo;
    }

    public function adicionar($id)
    {
        try {
            if (!$this->produtos->setCodigo($id)->verificarProdExists()) {
                throw new Exception('O produto que você tentou favoritar não existe.');
            }

            if (!$this->favoritos->setId_u($_SESSION['user']->id_u)->setId_P($id)->verificaFavoritoProduto()) {
                throw new Exception('O produto que você tentou favoritar Já é um favorito.');
            }

            if ($this->favoritos->setId_u($_SESSION['user']->id_u)->setId_P($id)->inserirFavorito()) {
                $_SESSION['sucesso'] = 'Produto adicionado como favorito';
            } else {
                throw new Exception("Algo deu errado ao tentar adicionar como favorito");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😫';
        }
        echo '<script> location.href = "?pag=favoritos" </script>';
    }

    public function remover($id)
    {
        try {
            if (!$this->favoritos->setId($id)->setId_u($_SESSION['user']->id_u)->verificaFavoritoExists()) {
                throw new Exception('O favorito que você tentou excluir não existe.');
            }

            if ($this->favoritos->setId_u($_SESSION['user']->id_u)->setId($id)->deletarFavorito()) {
                $_SESSION['sucesso'] = 'Favorito deletado com sucesso';
            } else {
                throw new Exception("Algo deu errado ao tentar deletar um favorito");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😫';
        }
        echo '<script> location.href = "?pag=favoritos" </script>';
    }
}
