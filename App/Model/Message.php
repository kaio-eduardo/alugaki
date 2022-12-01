<?php

namespace Project\Model;

use PDO;
use Project\Lib\Router as Connection;

class Message
{

    private $id;
    private $nome;
    private $email;
    private $assunto;
    private $message;

    function __construct()
    {
        $this->id = 0;
        $this->nome = '';
        $this->email = '';
        $this->assunto = '';
        $this->message = '';
    }

    public function inserirMessage()
    {

        $con = Connection::getConn();

        $sql = 'INSERT INTO message VALUES(:id, :nome, :email, :ass, :message)';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->bindParam('nome', $this->nome);
        $comando->bindParam('email', $this->email);
        $comando->bindParam('ass', $this->assunto);
        $comando->bindParam('message', $this->message);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function deletarMessage()
    {
        $con = Connection::getConn();

        $sql = 'DELETE FROM message WHERE id = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function selecionaMessages()
    {
        $con = Connection::getConn();

        $sql = 'SELECT * FROM message';

        $comando = $con->prepare($sql);

        $comando->execute();
        $valor = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valor;
    }

    public function selecionaMessage()
    {
        $con = Connection::getConn();

        $sql = 'SELECT * FROM message WHERE id = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);

        $comando->execute();
        $valor = $comando->fetch(PDO::FETCH_OBJ);

        return $valor;
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

    public function getNome()
    {
        return $this->nome;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getAssunto()
    {
        return $this->assunto;
    }
    public function setAssunto($assunto)
    {
        $this->assunto = $assunto;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
