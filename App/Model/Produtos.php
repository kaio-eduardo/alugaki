<?php

namespace Project\Model;

use Exception;
use Project\Lib\Router as Connection;
use PDO;

class Produtos
{

    private $codigo;
    private $nome;
    private $preco;
    private $estoque;
    private $desc;
    private $texto;
    private $imagem1;
    private $imagem2;
    private $imagem3;
    private $usuario;
    private $categoria;

    function __construct()
    {

        $this->codigo = 0;
        $this->nome = '';
        $this->preco = '';
        $this->estoque = '';
        $this->desc = '';
        $this->texto = '';
        $this->imagem1 = '';
        $this->imagem2 = '';
        $this->imagem3 = '';
        $this->usuario = '';
        $this->categoria = '';
    }

    /**
     * Inicio Encapsulamento de variaveis
     */
    function getCodigo()
    {
        return $this->codigo;
    }
    function setCodigo($Codigo)
    {
        $this->codigo = $Codigo;
        return $this;
    }

    function getNome()
    {
        return $this->nome;
    }
    function setNome($Nome)
    {
        $this->nome = $Nome;
        return $this;
    }

    function getPreco()
    {
        return $this->preco;
    }
    function setPreco($Preco)
    {
        $this->preco = $Preco;
        return $this;
    }

    function getEstoque()
    {
        return $this->estoque;
    }
    function setEstoque($Estoque)
    {
        $this->estoque = $Estoque;
        return $this;
    }

    function getDesc()
    {
        return $this->desc;
    }
    function setDesc($Desc)
    {
        $this->desc = $Desc;
        return $this;
    }

    public function getTexto()
    {
        return $this->texto;
    }
    public function setTexto($texto)
    {
        $this->texto = $texto;
        return $this;
    }

    public function getImagem1()
    {
        return $this->imagem1;
    }
    public function setImagem1($imagem1)
    {
        $this->imagem1 = $imagem1;
        return $this;
    }

    public function getImagem2()
    {
        return $this->imagem2;
    }
    public function setImagem2($imagem2)
    {
        $this->imagem2 = $imagem2;
        return $this;
    }

    public function getImagem3()
    {
        return $this->imagem3;
    }
    public function setImagem3($imagem3)
    {
        $this->imagem3 = $imagem3;
        return $this;
    }

    function getUsuario()
    {
        return $this->usuario;
    }
    function setUsuario($Usuario)
    {
        $this->usuario = $Usuario;
        return $this;
    }

    function getCategoria()
    {
        return $this->categoria;
    }
    function setCategoria($Categoria)
    {
        $this->categoria = $Categoria;
        return $this;
    }
    /**
     * Fim Encapsulamento de variaveis
     */

    /**
     * Inicio Funções produtos
     */
    public function InserirProdutos()
    {
        $con = Connection::getConn();

        $sql = 'INSERT INTO produtos (codigo, nome, valor, estoque, descricao_curta, descricao, img_1, img_2, img_3, dono_post, cat) VALUES(:codigo, :nome, :valor, :estoque, :desc_curta, :texto, :img1, :img2, :img3, :id_u, :cat)';

        $comando = $con->prepare($sql);
        $comando->bindParam('codigo', $this->codigo);
        $comando->bindParam('nome', $this->nome);
        $comando->bindParam('valor', $this->preco);
        $comando->bindParam('estoque', $this->estoque);
        $comando->bindParam('desc_curta', $this->desc);
        $comando->bindParam('texto', $this->texto);
        $comando->bindParam('img1', $this->imagem1);
        $comando->bindParam('img2', $this->imagem2);
        $comando->bindParam('img3', $this->imagem3);
        $comando->bindParam('id_u', $this->usuario);
        $comando->bindParam('cat', $this->categoria);

        $valor = $comando->execute();
        if ($valor == 0) {
            return false;
        }

        return true;
    }

    public function selecionaTodos()
    {
        try {
            $con = Connection::getConn();

            $sql = "SELECT p.*, c.nome_c, u.username FROM produtos p INNER JOIN usuarios u ON p.dono_post = u.id_u INNER JOIN categorias c ON c.id_c = p.cat ORDER BY p.codigo ASC";

            $comando = $con->prepare($sql);

            $comando->execute();
            $valores = $comando->fetchAll(PDO::FETCH_OBJ);

            return $valores;
        } catch (\PDOException $th) {
            throw new \Exception($th->getMessage(), $th->getCode());
        }
    }

    public function selecionaTodosDestaques()
    {
        try {
            $con = Connection::getConn();

            $sql = "SELECT p.*, round(avg(com.grade), 1) as 'media', c.nome_c, u.username FROM produtos p LEFT JOIN comentarios com ON p.codigo = com.codigo INNER JOIN usuarios u ON p.dono_post = u.id_u INNER JOIN categorias c ON c.id_c = p.cat GROUP BY p.codigo ORDER BY round(avg(com.grade), 1) DESC LIMIT 0, 8;";

            $comando = $con->prepare($sql);

            $comando->execute();
            $valores = $comando->fetchAll(PDO::FETCH_OBJ);

            return $valores;
        } catch (\PDOException $th) {
            throw new \Exception($th->getMessage(), $th->getCode());
        }
    }

    public function selecionaProduto()
    {
        $con = Connection::getConn();

        $sql = 'SELECT p.*, c.nome_c, u.username, round(avg(com.grade), 1) as "media" FROM produtos p INNER JOIN usuarios u ON p.dono_post = u.id_u INNER JOIN categorias c ON c.id_c = p.cat INNER JOIN comentarios com ON com.codigo = p.codigo WHERE com.codigo = :codigo';

        $comando = $con->prepare($sql);
        $comando->bindParam('codigo', $this->codigo);

        $comando->execute();
        $valores = $comando->fetch(PDO::FETCH_OBJ);

        return $valores;
    }

    public function selecionaProdutoCarrinho()
    {
        $con = Connection::getConn();

        $sql = "SELECT p.*, u.username FROM produtos p INNER JOIN usuarios u ON p.dono_post = u.id_u WHERE p.codigo = :codigo";

        $comando = $con->prepare($sql);
        $comando->bindParam('codigo', $this->codigo);
        $comando->execute();
        $produto = $comando->fetch(PDO::FETCH_OBJ);

        if (empty($produto)) {
            return false;
        } else {
            return $produto;
        }
    }

    public function FiltrarProdutos($filtro)
    {
        try {
            $con = Connection::getConn();
            $filtro = '%' .  $filtro . '%';

            $sql = 'SELECT p.*, c.nome_c, u.username FROM produtos p INNER JOIN usuarios u ON p.dono_post = u.id_u INNER JOIN categorias c ON c.id_c = p.cat WHERE p.nome LIKE :filtro OR c.nome_c LIKE :filtro ORDER BY p.nome ASC';

            $comando = $con->prepare($sql);
            $comando->bindParam('filtro', $filtro);

            $comando->execute();
            $valores = $comando->fetchAll(PDO::FETCH_OBJ);

            return $valores;
        } catch (\PDOException $th) {
            throw new \Exception($th->getMessage(), $th->getCode());
        }
    }

    public function ExcluirProduto()
    {

        $con = Connection::getConn();

        $sql = 'DELETE FROM produtos WHERE codigo = :codigo AND dono_post = :id_u';

        $comando = $con->prepare($sql);
        $comando->bindParam('codigo', $this->codigo);
        $comando->bindParam('id_u', $this->usuario);

        $valor =  $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function ExcluirProdutoAdmin()
    {
        $con = Connection::getConn();

        $sql = 'DELETE FROM produtos WHERE codigo = :codigo';

        $comando = $con->prepare($sql);
        $comando->bindParam('codigo', $this->codigo);

        $valor =  $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function AlterarProduto()
    {
        $con = Connection::getConn();

        $sql = 'UPDATE produtos SET nome = :nome, valor = :valor, estoque = :estoque, descricao_curta = :dcurta, descricao = :descricao, cat = :cat, img_1 = :img1, img_2 = :img2, img_3 = :img3 WHERE codigo = :codigo AND dono_post = :id_u';

        $comando = $con->prepare($sql);
        $comando->bindParam('codigo', $this->codigo);
        $comando->bindParam('id_u', $this->usuario);
        $comando->bindParam('nome', $this->nome);
        $comando->bindParam('valor', $this->preco);
        $comando->bindParam('estoque', $this->estoque);
        $comando->bindParam('descricao', $this->texto);
        $comando->bindParam('dcurta', $this->desc);
        $comando->bindParam('id_u', $this->usuario);
        $comando->bindParam('cat', $this->categoria);
        $comando->bindParam('img1', $this->imagem1);
        $comando->bindParam('img2', $this->imagem2);
        $comando->bindParam('img3', $this->imagem3);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function selecionaAllProdutoUser()
    {
        try {
            $con = Connection::getConn();

            $sql = 'SELECT p.*, c.nome_c, u.username FROM produtos p INNER JOIN usuarios u ON p.dono_post = u.id_u INNER JOIN categorias c ON c.id_c = p.cat WHERE p.dono_post = :id_u ORDER BY p.codigo';

            $comando = $con->prepare($sql);
            $comando->bindParam('id_u', $this->usuario);

            $comando->execute();
            $valores = $comando->fetchAll(PDO::FETCH_OBJ);

            return $valores;
        } catch (\PDOException $th) {
            throw new \Exception($th->getMessage(), $th->getCode());
        }
    }

    public function selecionaProdutoUser()
    {

        $con = Connection::getConn();

        $sql = 'SELECT p.*, c.nome_c, u.username, round(avg(com.grade), 1) as "media" FROM produtos p INNER JOIN usuarios u ON p.dono_post = u.id_u INNER JOIN categorias c ON c.id_c = p.cat INNER JOIN comentarios com ON com.codigo = p.codigo WHERE p.codigo = :id AND p.dono_post = :id_u';

        $comando = $con->prepare($sql);
        $comando->bindParam('id_u', $this->usuario);
        $comando->bindParam('id', $this->codigo);

        $comando->execute();
        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores == false) {
            return $valores;
        }
        return $valores;
    }

    public function verificarProdExists()
    {
        $con = Connection::getConn();

        $sql = 'SELECT count(*) as "qtd" FROM produtos WHERE codigo = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->codigo);

        $comando->execute();
        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores->qtd == 0) {
            return false;
        }
        return true;
    }

    public function verificarProdExistsUser()
    {
        $con = Connection::getConn();

        $sql = 'SELECT count(*) as "qtd" FROM produtos WHERE codigo = :id AND dono_post = :id_u';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->codigo);
        $comando->bindParam('id_u', $this->usuario);

        $comando->execute();
        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores->qtd == 0) {
            return false;
        }
        return true;
    }
}
