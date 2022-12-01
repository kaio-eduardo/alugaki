<?php

namespace Project\Model;

use PDO;
use Project\Lib\Router;

class Categorias
{
    private $idcategoria;
    private $nomecategoria;
    private $id_sub;

    public function __construct()
    {
        $this->idcategoria = 0;
        $this->nomecategoria = '';
        $this->id_sub = null;
    }

    /**
     * @param Encapsulamento
     */
    public function getIdcategoria()
    {
        return $this->idcategoria;
    }
    public function setIdcategoria($idcategoria)
    {
        $this->idcategoria = $idcategoria;
        return $this;
    }

    public function getNomecategoria()
    {
        return $this->nomecategoria;
    }
    public function setNomecategoria($nomecategoria)
    {
        $this->nomecategoria = $nomecategoria;
        return $this;
    }

    public function getId_sub()
    {
        return $this->id_sub;
    }
    public function setId_sub($id_sub)
    {
        $this->id_sub = $id_sub;
        return $this;
    }
    /**
     * Fim encapsulamento
     */

    public function inserirCategoria()
    {
        $con = Router::getConn();

        $sql = 'INSERT INTO categorias VALUES(:id, :nome, :id_sub)';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->idcategoria);
        $comando->bindParam('nome', $this->nomecategoria);
        $comando->bindParam('id_sub', $this->id_sub);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function deletarCategoria()
    {
        $con = Router::getConn();

        $sql = 'DELETE FROM categorias WHERE id_c = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->idcategoria);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function atualizarCategoria()
    {
        $con = Router::getConn();

        $sql = 'UPDATE categorias SET nome_c = :nome WHERE id_c = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('nome', $this->nomecategoria);
        $comando->bindParam('id', $this->idcategoria);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function getAllCategorias()
    {
        $con = Router::getConn();

        $sql = 'SELECT a.* FROM categorias as a';

        $comando = $con->prepare($sql);
        $comando->execute();

        $valores = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valores;
    }

    public function getCategoria()
    {
        $con = Router::getConn();

        $sql = 'SELECT *, count(*) as "qtd" FROM categorias WHERE id_c = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->idcategoria);
        $comando->execute();

        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores->qtd == 0) {
            return false;
        } 
        return $valores;
    }
}
