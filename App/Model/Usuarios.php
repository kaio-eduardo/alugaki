<?php

namespace Project\Model;

use Exception;
use mysqli_sql_exception;
use Project\Lib\Router;
use PDO;

class Usuarios
{

    private $id;
    private $pnome;
    private $unome;
    private $username;
    private $email;
    private $senha;
    private $perfil;
    private $cpf;
    private $dnasc;
    private $CEP;
    private $cidade;
    private $estado;
    private $telefone;
    private $validation_key;

    public function __construct()
    {
        $this->id = 0;
        $this->nome = '';
        $this->username = '';
        $this->email = '';
        $this->senha = '';
        $this->perfil = '';
        $this->cpf = '';
        $this->dnasc = '';
        $this->CEP = '';
        $this->cidade = '';
        $this->estado = '';
        $this->telefone = '';
        $this->validation_key = '156156';
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

    public function getPnome()
    {
        return $this->pnome;
    }
    public function setPnome($nome)
    {
        $this->pnome = $nome;

        return $this;
    }

    public function getUnome()
    {
        return $this->unome;
    }
    public function setUnome($nome)
    {
        $this->unome = $nome;

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

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getSenha()
    {
        return $this->senha;
    }
    public function setSenha($senha)
    {
        $this->senha = sha1($senha);

        return $this;
    }

    public function getPerfil()
    {
        return $this->perfil;
    }
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    public function getCpf()
    {
        return $this->cpf;
    }
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getDnasc()
    {
        return $this->dnasc;
    }
    public function setDnasc($dnasc)
    {
        $this->dnasc = $dnasc;

        return $this;
    }

    public function getCEP()
    {
        return $this->CEP;
    }
    public function setCEP($CEP)
    {
        $this->CEP = $CEP;

        return $this;
    }

    public function getCidade()
    {
        return $this->cidade;
    }
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;

        return $this;
    }

    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    public function getValidation_key()
    {
        return $this->validation_key;
    }
    public function setValidation_key($validation_key)
    {
        $this->validation_key = $validation_key;

        return $this;
    }

    public function getAllUsuarios()
    {
        $con = Router::getConn();

        $sql = 'SELECT * FROM usuarios';

        $comando = $con->prepare($sql);
        $comando->execute();

        $valores = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valores;
    }

    public function getUsuario()
    {
        $con = Router::getConn();

        $sql = 'SELECT *, count(*) as "qtd" FROM usuarios WHERE id_u = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->execute();

        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores->qtd == 0) {
            return false;
        }
        return $valores;
    }

    public function insertUsuario()
    {
        $con = Router::getConn();

        $sql = 'INSERT INTO usuarios (id_u, pnome, unome, username, email, dnasc, senha, validation_key) 
            VALUES(:id, :pnome, :unome, :username, :email, :dnasc, :senha, :vkey)';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->bindParam('pnome', $this->pnome);
        $comando->bindParam('unome', $this->unome);
        $comando->bindParam('username', $this->username);
        $comando->bindParam('email', $this->email);
        $comando->bindParam('senha', $this->senha);
        $comando->bindParam('dnasc', $this->dnasc);
        $comando->bindParam('vkey', $this->validation_key);
        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function atualizarUsuario()
    {
        $con = Router::getConn();

        $sql = 'UPDATE usuarios
        SET pnome = :pnome, unome = :unome, username = :user, email = :email, dnasc = :dnasc, CPF = :cpf,
         CEP = :cep, cidade = :cidade, estado = :estado, telefone = :tel, perfil = :perfil WHERE id_u = :id and senha = :senha';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->bindParam('pnome', $this->pnome);
        $comando->bindParam('unome', $this->unome);
        $comando->bindParam('user', $this->username);
        $comando->bindParam('email', $this->email);
        $comando->bindParam('senha', $this->senha);
        $comando->bindParam('dnasc', $this->dnasc);
        $comando->bindParam('cpf', $this->cpf);
        $comando->bindParam('cep', $this->CEP);
        $comando->bindParam('cidade', $this->cidade);
        $comando->bindParam('estado', $this->estado);
        $comando->bindParam('tel', $this->telefone);
        $comando->bindParam('perfil', $this->perfil);

        $valor = $comando->execute();
        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function excluirUsuario()
    {
        $con = Router::getConn();

        $sql = 'DELETE FROM usuarios WHERE id_u = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);

        $valor = $comando->execute();
        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function logarUsuario()
    {
        $con = Router::getConn();

        $sql = 'SELECT *, count(*) as "qtd" FROM usuarios WHERE username = :user AND senha = :senha';

        $comando = $con->prepare($sql);
        $comando->bindParam('user', $this->username);
        $comando->bindParam('senha', $this->senha);

        $comando->execute();
        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores->qtd == 1) {
            return $valores;
        } else {
            throw new Exception('Senha ou usuario invalido');
        }
    }

    public function verificar()
    {
        $con = Router::getConn();

        $sql = 'SELECT count(*) as "qtd" FROM usuarios WHERE username = :user OR email = :email';

        $comando = $con->prepare($sql);
        $comando->bindParam('user', $this->username);
        $comando->bindParam('email', $this->email);

        $comando->execute();
        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores->qtd >= 1) {
            return false;
        } else {
            return true;
        }
    }

    public function verificarUpdate()
    {
        $con = Router::getConn();

        $sql = 'SELECT count(*) as "qtd" FROM usuarios WHERE (username = :user OR email = :email) AND id_u != :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->bindParam('user', $this->username);
        $comando->bindParam('email', $this->email);

        $comando->execute();
        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores->qtd >= 1) {
            return false;
        } else {
            return true;
        }
    }

    public function atualizarUsuarioAdmin()
    {
        $con = Router::getConn();

        $sql = 'UPDATE usuarios
        SET pnome = :pnome, unome = :unome, username = :user, email = :email, dnasc = :dnasc, CPF = :cpf,
         CEP = :cep, cidade = :cidade, estado = :estado, telefone = :tel, perfil = :perfil WHERE id_u = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->bindParam('pnome', $this->pnome);
        $comando->bindParam('unome', $this->unome);
        $comando->bindParam('user', $this->username);
        $comando->bindParam('email', $this->email);
        $comando->bindParam('dnasc', $this->dnasc);
        $comando->bindParam('cpf', $this->cpf);
        $comando->bindParam('cep', $this->CEP);
        $comando->bindParam('cidade', $this->cidade);
        $comando->bindParam('estado', $this->estado);
        $comando->bindParam('tel', $this->telefone);        
        $comando->bindParam('perfil', $this->perfil);

        $valor = $comando->execute();
        if ($valor == 0) {
            return false;
        }
        return true;
    }
}
