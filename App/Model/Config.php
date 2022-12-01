<?php

namespace Project\Model;

use PDO;
use Project\Lib\Router as Connection;

class Config {

    private $id;
    private $sobre_site;
    private $termos;

    function __construct()
    {
        $this->id = 0;
        $this->sobre_site = '';
        $this->termos = '';
    }

    public function updateSobre() {
        $con = Connection::getConn();

        $sql = 'UPDATE config_info SET sobre_site = :sobre';

        $comando = $con->prepare($sql);
        $comando->bindParam('sobre', $this->sobre_site);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function updateTermos() {
        $con = Connection::getConn();

        $sql = 'UPDATE config_info SET termos_condicoes = :termos';

        $comando = $con->prepare($sql);
        $comando->bindParam('termos', $this->termos);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function selecionaSobre() {
        $con = Connection::getConn();

        $sql = 'SELECT sobre_site FROM config_info';

        $comando = $con->prepare($sql);

        $comando->execute();

        $valor = $comando->fetch(PDO::FETCH_OBJ);

        return $valor;
    }

    public function selecionaTermos() {
        $con = Connection::getConn();

        $sql = 'SELECT termos_condicoes FROM config_info';

        $comando = $con->prepare($sql);

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

    public function getSobre_site()
    {
        return $this->sobre_site;
    }
    public function setSobre_site($sobre_site)
    {
        $this->sobre_site = $sobre_site;
        return $this;
    }

    public function getTermos()
    {
        return $this->termos;
    }
    public function setTermos($termos)
    {
        $this->termos = $termos;
        return $this;
    }
}