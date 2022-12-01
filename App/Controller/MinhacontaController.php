<?php

namespace Project\Controller;

use Exception;
use Project\Lib\Helpers;
use Project\Model\{Categorias, Pedidos, Usuarios, Produtos, Comentario};
use Project\Service\Validacao;

class MinhacontaController
{

    private $parametros, $produtos, $categorias, $pedidos, $comentario;
    private $twig;

    function __construct()
    {
        $this->comentario = new Comentario();
        $this->categorias = new Categorias();
        $this->produtos = new Produtos();
        $this->usuarios = new Usuarios();
        $this->pedidos = new Pedidos();
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
            $_SESSION['error'] = 'É necessario estar logado para realizar está ação 🥱';
            echo '<script> location.href = "?pag=home&metodo=login" </script>';
        }
    }

    /**
     * Paginas
     */

    /**
     * Pagina inicial minhaconta
     */
    public function index()
    {
        $conteudo = $this->twig->render('minhaconta/minhaconta.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * Pagina pedidos minhaconta
     */
    public function my_orders()
    {
        $this->parametros['pedidos'] = $this->pedidos->setId_u($_SESSION['user']->id_u)->selecionaPedidos();
        $conteudo = $this->twig->render('minhaconta/minhaconta.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * Pagina editar
     */
    public function editar()
    {
        $conteudo = $this->twig->render('minhaconta/minhaconta.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * Pagina novo_produto
     */
    public function novo_produto()
    {
        $this->parametros['categorias'] = $this->categorias->getAllCategorias();
        $conteudo = $this->twig->render('minhaconta/minhaconta.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * Pagina sair minhaconta
     */
    public function sair()
    {
        $conteudo = $this->twig->render('minhaconta/minhaconta.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * Pagina de gerenciar produtos cadastrados
     */
    public function gerenciar()
    {
        $this->parametros['produtos'] = $this->produtos
            ->setUsuario($this->parametros['user']->id_u)
            ->selecionaAllProdutoUser();
        $this->parametros['estoque'] = $this->pedidos->setId_u($_SESSION['user']->id_u)->estoqueProdutoIndis();
        $conteudo = $this->twig->render('minhaconta/minhaconta.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * Fim paginas
     */

    /**
     * inicio funções
     */

    /**
     * função atualizar cadastro
     */
    public function atualizarcadastro()
    {
        if (isset($_POST) && isset($_FILES)) {
            try {
                Validacao::verificarUpdateUser($_POST, $_FILES);
                $_SESSION['sucesso'] = 'Seus dados foram alterados com sucesso 😎';
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage() . ' ✊😞';
            }
        }
        echo '<script>location.href = "?pag=minhaconta&metodo=editar"</script>';
    }

    public function viewPedProd($id)
    {
        try {
            if (!$this->pedidos->setCodigo($id)->setId_u($_SESSION['user']->id_u)->verificarPedido()) {
                throw new Exception("Esse pedido não lhe pertence ou não existe");
            }

            $this->parametros['pedidos'] = $this->pedidos->setCod_pedido($id)->setId_u($_SESSION['user']->id_u)->selecionarProduto_PedidoUser();
            $this->parametros['codigo'] = $this->parametros['pedidos'][0]->codigo_ped;
            $conteudo = $this->twig->render('minhaconta/minhaconta.html', $this->parametros);
            echo $conteudo;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😡';
            echo '<script>location.href = "?pag=minhaconta&metodo=my_orders"</script>';
        }
    }

    public function deletarPedido($id)
    {
        try {
            if (!$this->pedidos->setCodigo($id)->setId_u($_SESSION['user']->id_u)->verificarPedido()) {
                throw new Exception("Esse pedido não lhe pertence ou não existe");
            }
            if (!$this->pedidos->setCodigo($id)->setId_u($_SESSION['user']->id_u)->deletarPedido()) {
                throw new Exception('Algo deu errado ao tentar excluir o pedido #'.$id);
            }
            $_SESSION['sucesso'] = 'Pedido numero #'.$id.' deletado e cancelado com sucesso 😋';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😡';
        }
        echo '<script>location.href = "?pag=minhaconta&metodo=my_orders"</script>';
    }

    /**
     * função inserir produto
     */
    public function inserirproduto()
    {
        if (isset($_POST) && isset($_FILES['arqprodutos'])) {
            try {
                Validacao::verificarProduto($_POST, $_FILES['arqprodutos']);
                $_SESSION['sucesso'] = '🤑 O produto foi inserido em nossos servidores 😆';
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage() . ' 🤔';
            }
        }
        echo '<script>location.href = "?pag=minhaconta&metodo=novo_produto"</script>';
    }


    /**
     * inicio pagina de alterar
     */
    public function vender($id)
    {
        try {
            $this->parametros['categorias'] = $this->categorias->getAllCategorias();
            $this->parametros['comentarios'] = $this->comentario->setCodigo($id)->selecionarComentarioProduto();
            if ($this->produtos
                ->setUsuario($_SESSION['user']->id_u)
                ->setCodigo($id)->verificarProdExistsUser()
            ) {
                $this->parametros['produtos'] = $this->produtos->selecionaProdutoUser();
                $conteudo = $this->twig->render('vender/anunciar.html', $this->parametros);
            } else {
                throw new Exception('Esse produto não existe ou não lhe pertence');
            }
            echo $conteudo;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 🙃';
            echo '<script> location.href = "?pag=minhaconta" </script>';
        }
    }
    public function alterar()
    {
        try {
            if (isset($_POST) && isset($_FILES['arqprodutos'])) {
                Validacao::verificarUpdateProduto($_POST, $_FILES['arqprodutos']);
                $_SESSION['sucesso'] = 'Produto alterado com sucesso';
                echo '<script> location.href = "?pag=minhaconta&metodo=gerenciar"</script>';
            } else {
                throw new Exception('Algumas coisas não foram definidas');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😫';
            echo '<script> location.href = "?pag=minhaconta" </script>';
        }
    }
    /**
     * Fim pagina alterar
     */

    public function logout()
    {
        unset($_SESSION['user']);
        echo '<script>location.href = "?pag=home"</script>';
    }

    public function comentar($id)
    {
        try {
            if (!$this->produtos->setCodigo($id)->verificarProdExists()) {
                $_SESSION['error'] = 'O produto no qual você comentou não existe 😯';
                echo '<script>location.href = "?pag=home"</script>';
            }

            if (!isset($_POST['comentario'], $_POST['grade']) || empty($_POST['comentario']) || empty($_POST['grade'])) {
                throw new Exception('Existem campos em branco');
            }

            $comentario = $_POST['comentario'];
            $grade = $_POST['grade'];

            if ($grade < 1 || $grade > 5) {
                throw new Exception('A nota é invalida');
            }

            if ($this->comentario->setCodigo($id)->setId_u($_SESSION['user']->id_u)->setGrade($grade)->setComentario($comentario)->inserirComentario()) {
                $_SESSION['sucesso'] = 'Seu Comentario / Review foi inserido 😏';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😔';
        }
        echo '<script>location.href = "?pag=produtos&metodo=produto&id=' . $id . '"</script>';
    }
}
