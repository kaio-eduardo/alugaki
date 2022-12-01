<?php

namespace Project\Model;

use PDO;
use Project\Lib\Router as Connection;

class Admin {

    private $id;
    private $email;
    private $username;
    private $password;

    function __construct()
    {
        $this->id = 0;
        $this->email = '';
        $this->username = '';
        $this->password = '';
    }

    public function logar() {
        
        $con = Connection::getConn();

        $sql = 'SELECT *, count(*) as "qtd" FROM admin WHERE email = :email AND senha = :senha';

        $comando = $con->prepare($sql);
        $comando->bindParam('email', $this->email);
        $comando->bindParam('senha', $this->password);

        $comando->execute();

        $valor = $comando->fetch(PDO::FETCH_OBJ);

        if ($valor->qtd == 1) {
            return $valor;
        } else {
            return false;
        }

    }

    public function verficarAdmin () {

        $con = Connection::getConn();

        $sql = 'SELECT count(*) as "qtd" FROM admin WHERE senha = :senha AND id = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->bindParam('senha', $this->password);

        $comando->execute();

        $valor = $comando->fetch(PDO::FETCH_OBJ);

        if ($valor->qtd == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function alterarSenha($novasenha) {

        $con = Connection::getConn();
        $newsenha = sha1($novasenha);

        $sql = 'UPDATE admin SET senha = :newsenha WHERE senha = :senha AND id = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('newsenha', $newsenha);
        $comando->bindParam('senha', $this->password);
        $comando->bindParam('id', $this->id);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        } else {
            return true;
        }
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

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = sha1($password);
        return $this;
    }
}