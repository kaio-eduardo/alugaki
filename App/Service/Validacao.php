<?php

namespace Project\Service;

use Exception;
use Project\Lib\Helpers;
use Project\Lib\ImgResize;
use Project\Model\Produtos;
use Project\Model\Usuarios;

class Validacao
{

    public static function verificarRegistro($dados)
    {
        $erro = '';

        if (!isset($dados) || empty($dados)) {
            $erro .= 'Nada foi postado.<br>';
        }

        foreach ($dados as $key => $value) {
            $$key = trim(strip_tags($value));

            if (empty($value)) {
                if ($key == 'check_termos') {
                    throw new Exception('Para se cadastrar é necessário concordar com os termos e condições');
                }
                throw new Exception('Existem campos em branco');
            }
        }

        if (!isset($check_termos) || $check_termos != 'on') {
            throw new Exception('Para se cadastrar é necessário concordar com os termos e condições');
        }

        if (isset($firstname, $lastname, $email, $username, $dnasc, $password, $cpassword)) {

            if (is_numeric($firstname) || strlen($firstname) <= 2) {
                throw new Exception('Não pode conter numeros ou ser menor que 2 caracteres');
            }

            if (is_numeric($lastname) || strlen($lastname) <= 2) {
                throw new Exception('Não pode conter numeros ou ser menor que 2 caracteres');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email invalido');
            }

            if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $dnasc)) {
                throw new Exception('Data inválida.');
            }

            if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
                throw new Exception('username invalido');
            }

            if (strlen($password) < 8 || strlen($password) > 32) {
                throw new Exception('senha invalida');
            }

            if ($password != $cpassword) {
                throw new Exception('senhas sao diferentes');
            }
        } else {
            throw new Exception('Variaveis não declaradas');
        }

        if ($erro == '') {

            $usuario = new Usuarios();

            $dnasc = str_replace('/', '-', $dnasc);
            $dnasc = date('Y-m-d', strtotime($dnasc));

            $usuario->setPnome($firstname)
                ->setUnome($lastname)
                ->setUsername($username)
                ->setEmail($email)
                ->setSenha($password)
                ->setDnasc($dnasc);

            if ($dados = $usuario->verificar()) {
                $usuario->insertUsuario();
            } else {
                throw new Exception('Email ou username já em uso');
            }
        } else {
            throw new Exception('Algo deu errado');
        }
    }

    /**
     * Produto
     */
    public static function verificarProduto($dados, $files)
    {
        $erro = '';

        if (!isset($_SESSION['user'])) {
            throw new Exception("Você não está logado");
        }

        if (!isset($dados) || empty($dados) || !isset($files)) {
            throw new Exception('Nada foi postado ou variaveis padrão alteradas.');
        }

        if (empty($files)) {
            throw new Exception('Você precisa adicionar no minimo uma imagem no seu produto');
        }

        $count = count($files['name']);

        foreach ($dados as $key => $value) {
            if ($key == 'descricao' || $key == 'desc_curta') {
                $$key = $value;
            } else {
                $$key = trim(strip_tags($value));
            }

            if (empty($value)) {
                if ($key == 'produtos_img') {
                    $$key = '';
                } else {
                    throw new Exception("Existem campos em branco:" . $key . " não foi inserido.");
                }
            }
        }

        if (!isset($categoria)) {
            throw new Exception("O campo categoria não pode estar vazio");
        }

        if (isset($nome, $valor, $estoque, $desc_curta, $descricao)) {


            if (strlen($nome) < 5 || strlen($nome) > 60) {
                throw new Exception("O nome é invalido");
            }

            if (!is_numeric($valor) || $valor > 40000 || $valor < 15) {
                throw new Exception("O valor é invalido");
            }

            if (!is_numeric($estoque) || $estoque < 1 || $estoque > 999) {
                throw new Exception("O estoque é invalido");
            }

            if (strlen($desc_curta) > 1000) {
                throw new Exception("A apresentação do produto pode conter 300 letras no maximo");
            }

            if (strlen($descricao) > 10000) {
                throw new Exception("Descrição muito grande");
            }

            if (!is_numeric($categoria)) {
                throw new Exception("Categoria invalida");
            }

            if ($count > 3) {
                throw new Exception("Cada produto pode conter no maximo 3 imagens!");
            }
        } else {
            throw new Exception("Variaveis incorretas ou nomes alterados");
        }

        if ($erro == '') {

            $id = $_SESSION['user']->id_u;
            $produto = new Produtos();

            $file_ary = array();
            $file_count = count($files['name']);
            $file_keys = array_keys($files);

            for ($i = 0; $i < $file_count; $i++) {
                foreach ($file_keys as $key) {
                    $file_ary[$i][$key] = $files[$key][$i];
                }
            }

            $imgfolder = 'Public/imgs/Produtos/';
            $maxwidth = 1000;
            $maxheight = 1000;
            $perfil = array();

            for ($i = 0; $i < $count; $i++) {
                $newname = uniqid('produtos') . '.jpg';
                $URL = $imgfolder . $newname;
                if (ImgResize::convertImage($file_ary[$i], $URL, 80, $maxwidth, $maxheight)) {
                    $perfil[$i] = $newname;
                }
            }

            $produto->setNome($nome)
                ->setCategoria($categoria)
                ->setPreco($valor)
                ->setEstoque($estoque)
                ->setDesc($desc_curta)
                ->setTexto($descricao)
                ->setUsuario($id);

            if (isset($perfil[0])) {
                $produto->setImagem1($perfil[0]);
            }

            if (isset($perfil[1])) {
                $produto->setImagem2($perfil[1]);
            }

            if (isset($perfil[2])) {
                $produto->setImagem3($perfil[2]);
            }

            if ($produto->InserirProdutos()) {
                return true;
            } else {
                throw new Exception("Algo deu errado! SORRY");
            }
        }
    }

    /**
     * Editar Produto
     */
    public static function verificarUpdateProduto($dados, $files)
    {
        $erro = '';

        if (!isset($_SESSION['user'])) {
            throw new Exception("Você não está logado");
        }

        if (!isset($dados) || empty($dados) || !isset($files)) {
            throw new Exception('Nada foi postado ou variaveis padrão alteradas.');
        }

        $count = count($files['name']);

        foreach ($dados as $key => $value) {
            if ($key == 'descricao' || $key == 'desc_curta') {
                $$key = $value;
            } else {
                $$key = trim(strip_tags($value));
            }

            if (empty($value)) {
                if ($key == 'produtos_img') {
                    $$key = '';
                } else {
                    throw new Exception("Existem campos em branco:" . $key . " não foi inserido.");
                }
            }
        }

        if (!isset($categoria)) {
            throw new Exception("O campo categoria não pode estar vazio");
        }

        if (isset($nome, $valor, $estoque, $desc_curta, $descricao)) {

            if (strlen($nome) < 5 || strlen($nome) > 60) {
                throw new Exception("O nome é invalido");
            }

            if (!is_numeric($valor) || $valor > 40000 || $valor < 15) {
                throw new Exception("O valor é invalido");
            }

            if (!is_numeric($estoque) || $estoque < 1 || $estoque > 999) {
                throw new Exception("O estoque é invalido");
            }

            if (strlen($desc_curta) > 1000) {
                throw new Exception("A apresentação do produto pode conter 300 letras no maximo");
            }

            if (strlen($descricao) > 10000) {
                throw new Exception("Descrição muito grande");
            }

            if (!is_numeric($categoria)) {
                throw new Exception("Categoria invalida");
            }

            if ($count > 3) {
                throw new Exception("Cada produto pode conter no maximo 3 imagens!");
            }
        } else {
            throw new Exception("Variaveis incorretas ou nomes alterados");
        }

        if ($erro == '') {

            $id = $_SESSION['user']->id_u;
            $produto = new Produtos();

            if (!$produto->setCodigo($codigo)->setUsuario($id)->verificarProdExistsUser()) {
                throw new Exception('Esse produto não lhe pertence');
            }

            $valor_produto = $produto->selecionaProdutoUser();

            if (!empty($files['name'][0])) {
                $file_ary = array();
                $file_count = count($files['name']);
                $file_keys = array_keys($files);

                for ($i = 0; $i < $file_count; $i++) {
                    foreach ($file_keys as $key) {
                        $file_ary[$i][$key] = $files[$key][$i];
                    }
                }

                $imgfolder = 'Public/imgs/Produtos/';
                $maxwidth = 1000;
                $maxheight = 1000;
                $perfil = array();

                for ($i = 0; $i < 3; $i++) {
                    $img = 'img_' . ($i + 1);
                    if (!empty($valor_produto->$img)) {
                        unlink('Public/imgs/Produtos/' . $valor_produto->$img);
                    }
                }

                for ($i = 0; $i < $count; $i++) {
                    $newname = uniqid('produtos') . '.jpg';
                    $URL = $imgfolder . $newname;
                    if (ImgResize::convertImage($file_ary[$i], $URL, 80, $maxwidth, $maxheight)) {
                        $perfil[$i] = $newname;
                    }
                }
            } else {
                for ($i = 0; $i < 3; $i++) {
                    $img = 'img_' . ($i + 1);
                    if (!empty($valor_produto->$img)) {
                        $perfil[$i] = $valor_produto->$img;
                    }
                }
            }

            $produto->setNome($nome)
                ->setCategoria($categoria)
                ->setPreco($valor)
                ->setEstoque($estoque)
                ->setDesc($desc_curta)
                ->setTexto($descricao);

            if (isset($perfil[0])) {
                $produto->setImagem1($perfil[0]);
            }
            if (isset($perfil[1])) {
                $produto->setImagem2($perfil[1]);
            }
            if (isset($perfil[2])) {
                $produto->setImagem3($perfil[2]);
            }

            if ($produto->AlterarProduto()) {
                return true;
            } else {
                throw new Exception("Algo deu errado! SORRY");
            }
        }
    }

    /**
     * Editar usuario
     */
    public static function verificarUpdateUser($dados, $files)
    {
        $erro = '';

        if (!isset($_SESSION['user'])) {
            throw new Exception("Você não está logado");
        }

        if (!isset($dados) || empty($dados) || !isset($files['arqperfil'])) {
            throw new Exception('Nada foi postado ou variaveis padrão alteradas.');
        }

        foreach ($dados as $key => $value) {
            $$key = trim(strip_tags($value));

            if (empty($value)) {
                if ($key == 'cep' or $key == 'estado' or $key == 'cidade' or $key == 'cpf' or $key == 'tel' or $key == 'perfil') {
                    $$key = null;
                } else {
                    throw new Exception("Existem campos em branco");
                }
            }
        }

        if (isset($firstname, $lastname, $email, $username, $dnasc, $password, $cpassword)) {

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

            if (strlen($password) < 8 || strlen($password) > 32) {
                throw new Exception("Senha invalida");
            }

            if ($password != $cpassword) {
                throw new Exception("Suas senha estão diferentes");
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

            if (sha1($password) != $_SESSION['user']->senha) {
                throw new Exception("A senha informada está incorreta");
            }
        } else {
            throw new Exception("As variaveis estão incorretas");
        }

        if ($erro == '') {

            $usuario = new Usuarios();

            $id = $_SESSION['user']->id_u;
            $dnasc = str_replace('/', '-', $dnasc);
            $dnasc = date('Y-m-d', strtotime($dnasc));
            $cpf = str_replace(".", "", $cpf);
            $cpf = str_replace("-", "", $cpf);
            $tel = str_replace("(", "", $tel);
            $tel = str_replace(")", "", $tel);
            $tel = str_replace(" ", "", $tel);
            $tel = str_replace("-", "", $tel);
            $cep = str_replace("-", "", $cep);

            $imgfolder = "Public/imgs/usuarios/";
            $maxwidth = $maxheight = 400;

            if (empty($_SESSION['user']->perfil)) {
                $newname = uniqid("usuarios") . ".jpg";
            } else {
                $newname = $_SESSION['user']->perfil;
            }

            if ($files['arqperfil']['name'] == '' && !empty($_SESSION['user']->perfil)) {
                unlink('Public/imgs/usuarios/' . $_SESSION['user']->perfil);
            }

            $URL = $imgfolder . $newname;

            $usuario->setId($id)
                ->setUsername($username)
                ->setEmail($email);

            if ($dados = $usuario->verificarUpdate()) {
                try {
                    if (Imgresize::convertImage($files['arqperfil'], $URL, 80, $maxwidth, $maxheight)) {
                        $usuario->setPerfil($newname);
                    }
                    $usuario->setSenha($password)
                        ->setPnome($firstname)
                        ->setUnome($lastname)
                        ->setDnasc($dnasc)
                        ->setCEP($cep)
                        ->setEstado($estado)
                        ->setCidade($cidade)
                        ->setCpf($cpf)
                        ->setTelefone($tel)
                        ->atualizarUsuario();
                    $_SESSION['user'] = $usuario->logarUsuario();
                    $_SESSION['user']->dnasc = date('d/m/Y', strtotime($_SESSION['user']->dnasc));
                    return true;
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } else {
                throw new Exception('Email ou username já em uso');
            }
        } else {
            throw new Exception('Algo deu errado');
        }
    }

    public static function erroTratamento($erro)
    {
        $conteudo = '<script>
                alert("Error' . $erro->getCode() . ": " . $erro->getMessage() . '");
                location.href = "?pag=home";
            </script>';

        return $conteudo;
    }
}
