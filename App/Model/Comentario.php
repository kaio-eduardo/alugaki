<?php

namespace Project\Model;

use PDO;
use Project\Lib\Router as Connection;

class Comentario
{

    private $id;
    private $codigo;
    private $id_u;
    private $grade;
    private $comentario;

    function __construct()
    {
        $this->id = 0;
        $this->codigo = '';
        $this->id_u = '';
        $this->grade = '';
        $this->comentario = '';
    }

    public function inserirComentario()
    {
        $con = Connection::getConn();

        $sql = 'INSERT INTO comentarios VALUES (:id, :codigo, :user, :grade, :comentario)';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->bindParam('codigo', $this->codigo);
        $comando->bindParam('user', $this->id_u);
        $comando->bindParam('grade', $this->grade);
        $comando->bindParam('comentario', $this->comentario);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function selecionarComentarioProduto()
    {
        $con = Connection::getConn();

        $sql = 'SELECT c.*, u.username, u.perfil FROM comentarios c INNER JOIN usuarios u ON c.id_u = u.id_u WHERE c.codigo = :codigo';

        $comando = $con->prepare($sql);
        $comando->bindParam('codigo', $this->codigo);

        $comando->execute();

        $valores = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valores;
    }

    public function selecionarComentarioUser()
    {
        $con = Connection::getConn();

        $sql = 'SELECT c.* FROM comentarios c WHERE c.id_u = :id_u';

        $comando = $con->prepare($sql);
        $comando->bindParam('id_u', $this->id_u);
        $comando->execute();

        $valores = $comando->fetchAll(PDO::FETCH_OBJ);
        
        return $valores;
    }

    public function deletarComentario()
    {
        $con = Connection::getConn();

        $sql = 'DELETE FROM comentarios WHERE id_u = :id_u AND id = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id_u', $this->id_u);
        $comando->bindParam('id', $this->id);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function verificarComentario()
    {
        $con = Connection::getConn();

        $sql = 'SELECT count(*) FROM comentarios WHERE id_u = :id_u AND id = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id_u', $this->id_u);
        $comando->bindParam('id', $this->id);
        $comando->execute();

        $valores = $comando->fetch(PDO::FETCH_OBJ);
        
        if ($valores == 0) {
            return false;
        }
        return true;
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

    public function getCodigo()
    {
        return $this->codigo;
    }
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
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

    public function getGrade()
    {
        return $this->grade;
    }
    public function setGrade($grade)
    {
        $this->grade = $grade;
        return $this;
    }

    public function getComentario()
    {
        return $this->comentario;
    }
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
        return $this;
    }
}
