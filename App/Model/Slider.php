<?php

namespace Project\Model;

use PDO;
use Project\Lib\Router as Connection;

class Slider {

    private $id;
    private $slug;
    private $url;

    function __construct()
    {
        $this->id = 0;
        $this->slug = '';
        $this->url = '';
    }

    public function insertSlide() {
        $con = Connection::getConn();

        $sql = 'INSERT INTO slider VALUES (:id, :slug, :url)';

        $comando = $con->prepare($sql);

        $comando->bindParam('id', $this->id);
        $comando->bindParam('slug', $this->slug);
        $comando->bindParam('url', $this->url);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function deleteSlide() {
        $con = Connection::getConn();

        $sql = 'DELETE FROM slider WHERE id = :id';

        $comando = $con->prepare($sql);

        $comando->bindParam('id', $this->id);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function selectSlide() {
        $con = Connection::getConn();

        $sql = 'SELECT *, count(*) as "qtd" FROM slider WHERE id = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id);
        $comando->execute();
        $valor = $comando->fetch(PDO::FETCH_OBJ);

        return $valor;
    }

    public function selectSlides() {
        $con = Connection::getConn();

        $sql = 'SELECT * FROM slider';

        $comando = $con->prepare($sql);

        $comando->execute();
        $valor = $comando->fetchAll(PDO::FETCH_OBJ);

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

    public function getSlug()
    {
        return $this->slug;
    }
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
}