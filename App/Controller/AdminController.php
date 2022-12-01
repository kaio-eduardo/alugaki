<?php

namespace Project\Controller;

use Exception;
use Project\Lib\Helpers;
use Project\Lib\Imgresize;
use Project\Model\{Admin, Produtos, Categorias, Usuarios, Pedidos, Config, Slider, Message};

class AdminController
{

    private $parametros;
    private $twig, $produtos, $categorias, $usuarios, $admin, $pedidos, $config, $slider, $message;

    function __construct()
    {
        $this->message = new Message();
        $this->slider = new Slider();
        $this->pedidos = new Pedidos();
        $this->produtos = new Produtos();
        $this->categorias = new Categorias();
        $this->usuarios = new Usuarios();
        $this->admin = new Admin();
        $this->config = new Config();
        $this->twig = Helpers::Loader();
        if (isset($_GET)) {
            $this->parametros['get'] = $_GET;
        }
        if (isset($_SESSION['sucesso'])) {
            $this->parametros['sucesso'] = $_SESSION['sucesso'];
            unset($_SESSION['sucesso']);
        }
        if (isset($_SESSION['error'])) {
            $this->parametros['error'] = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        if (!isset($_SESSION['admin'])) {
            echo '<script> location.href = "?pag=home&metodo=admin" </script>';
        } else {
            $this->parametros['admin'] = $_SESSION['admin'];
        }
    }

    public function index()
    {
        $this->parametros['pedidos'] = $this->pedidos->selecionaTodosLimit(5);
        $this->parametros['users'] = $this->usuarios->getAllUsuarios();
        $this->parametros['produtos'] = $this->produtos->selecionaTodos();
        $this->parametros['categorias'] = $this->categorias->getAllCategorias();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * @return Produtos
     */
    public function viewProduct()
    {
        $this->parametros['produtos'] = $this->produtos->selecionaTodos();;
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function editProduct($id)
    {
        $this->parametros['categorias'] = $this->categorias->getAllCategorias();
        $this->parametros['produto'] = $this->produtos->setCodigo($id)->selecionaProduto();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * @return Categorias
     */
    public function viewCat()
    {
        $this->parametros['categorias'] = $this->categorias->getAllCategorias();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function insertCat()
    {
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function editCat($id)
    {
        $this->parametros['categoria'] = $this->categorias->setIdcategoria($id)->getCategoria();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * @return Usuarios
     */
    public function viewUsers()
    {
        $this->parametros['users'] = $this->usuarios->getAllUsuarios();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function editUser($id)
    {
        $this->parametros['user'] = $this->usuarios->setId($id)->getUsuario();
        $this->parametros['user']->dnasc = date('d/m/Y', strtotime($this->parametros['user']->dnasc));
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * @return Orders
     */
    public function viewOrder()
    {
        $this->parametros['pedidos'] = $this->pedidos->selecionaTodos();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function viewPedProd($id)
    {
        $this->parametros['pedidos'] = $this->pedidos->setCod_pedido($id)->selecionarProduto_Pedido();
        $this->parametros['codigo'] = $this->parametros['pedidos'][0]->codigo_ped;
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function editOrder()
    {
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * @return profile
     */
    public function viewProfile()
    {
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function editProfile()
    {
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function changePass()
    {
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * @return config
     */
    public function viewAbout()
    {
        $this->parametros['config'] = $this->config->selecionaSobre();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function viewTerms()
    {
        $this->parametros['config'] = $this->config->selecionaTermos();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * @return messages
     */
    public function viewMessages()
    {
        $this->parametros['messages'] = $this->message->selecionaMessages();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function viewMessage($id)
    {
        $this->parametros['messages'] = $this->message->setId($id)->selecionaMessage();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    /**
     * @return slides
     */
    public function viewSlides()
    {
        $this->parametros['slides'] = $this->slider->selectSlides();
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }

    public function addSlide()
    {
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }


    /**
     * @return Logout
     */
    public function sair()
    {
        $conteudo = $this->twig->render('admin/adminarea.html', $this->parametros);
        echo $conteudo;
    }


    /**
     * @return FUNCTIONS
     */


    /**
     * @return message
     */
    public function deleteMessage($id) 
    {
        try {

            if (!$this->message->setId($id)->deletarMessage()) {
                throw new Exception('Mensagem não existe');
            }

            $_SESSION['sucesso'] = 'Mensagem excluida com sucesso';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        echo '<script> location.href = "?pag=admin&metodo=viewMessages" </script>';
    }

    /**
     * @return slide
     */

    public function inserirSlide()
    {
        try {
            if (!isset($_POST['url'], $_FILES['slug'])) {
                throw new Exception('Algo deu errado');
            }

            $imgfolder = "Public/imgs/Slides/";
            $newname = uniqid('slides') . '.jpg';
            $maxwidth = 1920;
            $maxheight = 350;

            $URL = $imgfolder . $newname;

            Imgresize::convertImage($_FILES['slug'], $URL, 80, $maxwidth, $maxheight);

            if (!$this->slider->setUrl($_POST['url'])->setSlug($newname)->insertSlide()) {
                throw new Exception('Algo deu errado ao inserir o slide');
            }
            $_SESSION['sucesso'] = 'Slide inserido com sucesso';
            echo '<script> location.href = "?pag=admin&metodo=viewSlides" </script>';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            echo '<script> location.href = "?pag=admin&metodo=addSlide" </script>';
        }
    }

    public function deletarSlide($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('Algo deu errado');
            }
            $valor = $this->slider->setId($id)->selectSlide();
            
            if ($valor->qtd != 1) {
                throw new Exception('Slide não existe');
            }

            unlink('Public/imgs/Slides/'.$valor->slug);

            if (!$this->slider->deleteSlide()) {
                throw new Exception('Alguma coisa não deixou o slide ser deletado');
            }

            $_SESSION['sucesso'] = 'Slide deletado com sucesso';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        echo '<script> location.href = "?pag=admin&metodo=viewSlides" </script>';
    }

    /**
     * @return config_change
     */
    public function alterarSobre()
    {
        try {
            if (!isset($_POST['sobre'])) {
                throw new Exception('Algo deu errado ao atualizar o sobre');
            }

            if ($this->config->setSobre_site($_POST['sobre'])->updateSobre()) {
                $_SESSION['sucesso'] = 'Sobre foi alterado com sucesso';
            } else {
                throw new Exception('Algo deu errado');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 🥱';
        }
        echo '<script> location.href = "?pag=admin&metodo=viewAbout" </script>';
    }

    public function alterarTermo()
    {
        try {
            if (!isset($_POST['termos'])) {
                throw new Exception('Algo deu errado ao atualizar os termos');
            }

            if ($this->config->setTermos($_POST['termos'])->updateTermos()) {
                $_SESSION['sucesso'] = 'Os termos foram alterados com sucesso';
            } else {
                throw new Exception('Algo deu errado');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 🥱';
        }
        echo '<script> location.href = "?pag=admin&metodo=viewTerms" </script>';
    }


    /**
     * @return logout
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        echo '<script> location.href = "?pag=home" </script>';
    }

    /**
     * @return Troca_senha
     */
    public function trocarsenha()
    {
        try {
            extract($_POST);

            if (!isset($senha, $csenha, $newsenha) || empty($senha) || empty($csenha) || empty($newsenha)) {
                throw new Exception('Existem campos em branco');
            }

            if ($senha != $csenha) {
                throw new Exception('Suas senhas não batem');
            }

            if (strlen($newsenha) < 8) {
                throw new Exception('A nova senha deve ter no minimo 8 caracteres');
            }

            if ($valor = $this->admin->setPassword($senha)->setEmail($_SESSION['admin']->email)->setId($_SESSION['admin']->id)->verficarAdmin()) {
                if ($valor = $this->admin->alterarSenha($newsenha)) {
                    $valor = $this->admin->setPassword($newsenha)->logar();
                    $_SESSION['admin'] = $valor;
                    $_SESSION['sucesso'] = 'Senha alterada com sucesso';
                } else {
                    throw new Exception('Algo deu errado');
                }
            } else {
                throw new Exception('Parece que você errou sua senha! Tente novamente');
            }

            echo '<script> location.href = "?pag=admin" </script>';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 🙄';
            echo '<script> location.href = "?pag=admin&metodo=changePass" </script>';
        }
    }

    /**
     * @return categoria
     */
    public function inserirCat()
    {
        if (isset($_POST['nome'])) {
            try {
                $nome = $_POST['nome'];
                if (strlen($nome) > 60) {
                    throw new Exception('O nome da categoria pode ter no maximo 60 caracteres');
                }

                $this->categorias->setNomecategoria($nome);

                if ($this->categorias->inserirCategoria()) {
                    $_SESSION['sucesso'] = 'Categoria inserida com sucesso 😊';
                } else {
                    throw new Exception('Algo deu errado');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage() . ' 😶';
            }
        }
        echo '<script> location.href = "?pag=admin&metodo=viewCat" </script>';
    }

    public function editarCat()
    {
        if (isset($_POST['nome'], $_POST['id'])) {
            try {
                $nome = $_POST['nome'];
                $id = $_POST['id'];
                if (strlen($nome) > 60) {
                    throw new Exception('O nome da categoria pode ter no maximo 60 caracteres');
                }
                if (!is_numeric($id) || empty($id)) {
                    throw new Exception('O codigo da categoria é invalido');
                }

                $this->categorias->setNomecategoria($nome)->setIdcategoria($id);

                if ($this->categorias->atualizarCategoria()) {
                    $_SESSION['sucesso'] = 'Categoria modificada com sucesso 😊';
                } else {
                    throw new Exception('Algo deu errado');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage() . ' 😶';
            }
        }
        echo '<script> location.href = "?pag=admin&metodo=viewCat" </script>';
    }

    public function deletarCat($id)
    {
        try {
            if (empty($id) || !is_numeric($id)) {
                throw new Exception('Codigo de categoria invalido');
            }

            if ($this->categorias->setIdcategoria($id)->deletarCategoria()) {
                $_SESSION['sucesso'] = 'Categoria deletada com sucesso';
            } else {
                throw new Exception('Não é possível deletar, pois existem produtos cadastrados');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😶';
        }
        echo '<script> location.href = "?pag=admin&metodo=viewCat" </script>';
    }

    /**
     * @return produto
     */
    public function editarProduto()
    {
        try {

            foreach ($_POST as $key => $value) {
                if ($key == 'desc_curta' || $key == 'descricao') {
                    $$key = $value;
                } else {
                    $$key = trim(strip_tags($value));
                }

                if (empty($value)) {
                    if ($key == 'img1' || $key == 'img2' || $key == 'img3') {
                        $$key = '';
                    } else {
                        throw new Exception("Existem campos em branco: " . $key . " não foi inserido.");
                    }
                }
            }

            if (!isset($categoria)) {
                throw new Exception("O campo categoria não pode estar vazio");
            }

            if ($this->produtos->setNome($nome)
                ->setCategoria($categoria)
                ->setPreco($valor)
                ->setEstoque($estoque)
                ->setDesc($desc_curta)
                ->setTexto($descricao)
                ->setCodigo($codigo)
                ->setUsuario($id)
                ->setImagem1($img1)
                ->setImagem2($img2)
                ->setImagem3($img3)
                ->AlterarProduto()
            ) {
                $_SESSION['sucesso'] = ucwords($nome) . ' alterado com sucesso';
            } else {
                throw new Exception('Algo deu errado durante a alteração');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        echo '<script> location.href = "?pag=admin&metodo=viewProduct" </script>';
    }
    public function deletarProduto($id)
    {
        try {
            if (empty($id) || !is_numeric($id)) {
                throw new Exception('Codigo de produto invalido');
            }

            if ($this->produtos->setCodigo($id)->ExcluirProdutoAdmin()) {
                $_SESSION['sucesso'] = 'Produto deletado com sucesso';
            } else {
                throw new Exception('Não é possível deletar, pois algo deu errado.');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😖';
        }
        echo '<script> location.href = "?pag=admin&metodo=viewProduct" </script>';
    }


    /**
     * @return user
     */
    public function editarUser()
    {
        try {
            if (!isset($_POST) || empty($_POST)) {
                throw new Exception('Nada foi postado ou variaveis padrão alteradas.');
            }

            foreach ($_POST as $key => $value) {
                $$key = trim(strip_tags($value));

                if (empty($value)) {
                    if ($key == 'cep' or $key == 'estado' or $key == 'cidade' or $key == 'cpf' or $key == 'tel') {
                        $$key = null;
                    } else {
                        throw new Exception("Existem campos em branco");
                    }
                }
            }

            if (isset($firstname, $lastname, $email, $username, $dnasc)) {

                if (is_numeric($firstname) || strlen($firstname) <= 2) {
                    throw new Exception("Seu primeiro nome não está conforme o pedido");
                }

                if (is_numeric($lastname) || strlen($lastname) <= 2) {
                    throw new Exception("Seu sobrenome não está conforme o pedido");
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Seu email está incorreto");
                }

                if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $dnasc)) {
                    throw new Exception("A data de nascimento está em formato incorreto");
                }

                if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
                    throw new Exception("Seu username está em formato incorreto");
                }

                if (isset($cep) && !preg_match('/^[0-9]{8}$/', str_replace('-', '', $cep))) {
                    throw new Exception("Informe um CEP válido");
                }

                if (isset($estado) && strlen($estado) != 2) {
                    throw new Exception("O estado informado está errado");
                }

                if (isset($cpf) && !Helpers::validaCPF($cpf)) {
                    throw new Exception("O seu número de CPF está incorreto");
                }

                if (isset($tel) && !Helpers::celular($tel)) {
                    throw new Exception("O número de celular não corresponde");
                }
            } else {
                throw new Exception("Variaveis padrão não setadas");
            }

            $this->usuarios->setId($id)->setEmail($email)->setUsername($username);

            if ($dados = $this->usuarios->verificarUpdate()) {
                $dnasc = str_replace('/', '-', $dnasc);
                $dnasc = date('Y-m-d', strtotime($dnasc));
                $this->usuarios->setPnome($firstname)
                    ->setUnome($lastname)
                    ->setDnasc($dnasc)
                    ->setCEP($cep)
                    ->setEstado($estado)
                    ->setCidade($cidade)
                    ->setCpf($cpf)
                    ->setTelefone($tel)
                    ->setPerfil($perfil)
                    ->atualizarUsuarioAdmin();
                $_SESSION['sucesso'] = 'Usuario atualizado com sucesso';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😑';
        }
        echo '<script> location.href= "?pag=admin&metodo=viewUsers" </script>';
    }
    public function deletarUser($id)
    {
        try {
            if (empty($id) || !is_numeric($id)) {
                throw new Exception('Codigo de usuario invalido');
            }

            if ($this->usuarios->setId($id)->excluirUsuario()) {
                $_SESSION['sucesso'] = 'Usuario deletado com sucesso';
            } else {
                throw new Exception('Não é possível deletar, pois esse user possui produtos em sua conta.');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage() . ' 😖';
        }
        echo '<script> location.href = "?pag=admin&metodo=viewUsers" </script>';
    }

    /**
     * 
     */
}
