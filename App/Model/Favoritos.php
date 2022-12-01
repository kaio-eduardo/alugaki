<?php

namespace Project\Model;

use PDO;
use Project\Lib\Router as Connection;

class Favoritos
{

    private $id;
    private $id_u;
    private $id_P;

    function __construct()
    {
        $this->id = 0;
        $this->id_u = '';
        $this->id_P = '';
    }


    public function inserirFavorito()
    {
        $con = Connection::getConn();

        $sql = 'INSERT INTO favoritos VALUES (:id, :id_u, :id_p)';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->bindParam('id_u', $this->id_u);
        $comando->bindParam('id_p', $this->id_P);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function deletarFavorito()
    {
        $con = Connection::getConn();

        $sql = 'DELETE FROM favoritos WHERE id = :id AND id_u = :id_u';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->bindParam('id_u', $this->id_u);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function selecionaFavoritos()
    {
        $con = Connection::getConn();

        $sql = 'SELECT f.id, p.*, c.nome_c, u.username FROM produtos p INNER JOIN favoritos f ON p.codigo = f.id_p INNER JOIN categorias c ON p.cat = c.id_c INNER JOIN usuarios u ON u.id_u = p.dono_post WHERE f.id_u = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id_u);

        $comando->execute();
        $valores = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valores;
    }

    public function verificaFavoritoExists()
    {
        $con = Connection::getConn();

        $sql = 'SELECT count(*) as "qtd" FROM favoritos WHERE id_u = :id_u AND id = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id_u', $this->id_u);
        $comando->bindParam('id', $this->id);

        $comando->execute();
        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores->qtd == 0) {
            return false;
        }
        return true;
    }

    public function verificaFavoritoProduto()
    {
        $con = Connection::getConn();

        $sql = 'SELECT count(*) as "qtd" FROM favoritos WHERE id_p = :id and id_u = :idu';

        $comando = $con->prepare($sql);
        $comando->bindParam('idu', $this->id_u);
        $comando->bindParam('id', $this->id_P);

        $comando->execute();
        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores->qtd == 0) {
            return true;
        }
        return false;
    }


    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId_u()
    {
        return $this->id_u;
    }
    public function setId_u($id_u)
    {
        $this->id_u = $id_u;
        return $this;
    }

    public function getId_P()
    {
        return $this->id_P;
    }
    public function setId_P($id_P)
    {
        $this->id_P = $id_P;
        return $this;
    }
}
