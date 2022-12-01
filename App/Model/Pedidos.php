<?php

namespace Project\Model;

use Project\Lib\Router as Connection;
use PDO;

class Pedidos
{

    private $codigo;
    private $id_u;
    private $metodo_pagamento;
    private $valor;
    private $status_pedido;

    private $id_pp;
    private $cod_pedido;
    private $codigo_prod;
    private $valor_tot;
    private $qtd_produto;
    private $periodo;
    private $status;


    function __construct()
    {
        $this->codigo = 0;
        $this->id_u = '';
        $this->metodo_pagamento = 0;
        $this->valor = 8;
        $this->status_pedido = 0;

        $this->id_pp = 0;
        $this->cod_pedido = '';
        $this->codigo_prod = '';
        $this->valor_tot = '';
        $this->qtd_produto = '';
        $this->periodo = '';
        $this->status = '';
    }

    /**
     * @return pedido
     */

    public function inserirPedido()
    {
        $con = Connection::getConn();

        $sql = 'INSERT INTO pedidos (codigo_ped, id_u, valor) VALUES (:id, :id_u, :valor)';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->codigo);
        $comando->bindParam('id_u', $this->id_u);
        $comando->bindParam('valor', $this->valor);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function deletarPedido()
    {
        $con = Connection::getConn();

        $sql = 'DELETE FROM pedidos WHERE codigo_ped = :id AND id_u = :codigo';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->codigo);
        $comando->bindParam('codigo', $this->id_u);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function updatePedido()
    {
        $con = Connection::getConn();

        $sql = 'UPDATE pedidos SET status_pedido = :status WHERE codigo_ped = :codigo AND id_u = :id';

        $comando = $con->prepare($sql);
        $comando->bindParam('codigo', $this->codigo);
        $comando->bindParam('id', $this->id_u);
        $comando->bindParam('status', $this->status_pedido);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function selecionaPedidos()
    {
        $con = Connection::getConn();

        $sql = 'SELECT * FROM pedidos WHERE id_u = :id ORDER BY codigo_ped';

        $comando = $con->prepare($sql);

        $comando->bindParam('id', $this->id_u);

        $comando->execute();

        $valores = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valores;
    }

    public function selecionaTodos()
    {
        $con = Connection::getConn();

        $sql = 'SELECT p.*, u.username FROM pedidos p INNER JOIN usuarios u ON u.id_u = p.id_u ORDER BY p.codigo_ped';

        $comando = $con->prepare($sql);

        $comando->execute();

        $valores = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valores;
    }

    public function selecionaTodosLimit($limit)
    {
        $con = Connection::getConn();

        $sql = 'SELECT p.*, u.username FROM pedidos p INNER JOIN usuarios u ON u.id_u = p.id_u ORDER BY p.cdata DESC LIMIT 0, :maximo';

        $comando = $con->prepare($sql);
        $comando->bindParam('maximo', $limit, PDO::PARAM_INT);

        $comando->execute();

        $valores = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valores;
    }

    public function verificarPedido()
    {
        $con = Connection::getConn();

        $sql = 'SELECT count(*) as "qtd" FROM pedidos WHERE id_u = :id AND codigo_ped = :codigo';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id_u);
        $comando->bindParam('codigo', $this->codigo);

        $comando->execute();

        $valores = $comando->fetch(PDO::FETCH_OBJ);

        if ($valores->qtd == 0) {
            return false;
        }
        return true;
    }

    /**
     * @return pedido_produto
     */

    public function inserirProduto_Pedido()
    {
        $con = Connection::getConn();

        $sql = 'INSERT INTO pedidos_produtos (id_pp, codigo_ped, codigo_prod, valor, qtd_produto, periodo) VALUES (:id, :id_ped, :id_prod, :valor, :qtd, :periodo)';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id_pp);
        $comando->bindParam('id_ped', $this->cod_pedido);
        $comando->bindParam('id_prod', $this->codigo_prod);
        $comando->bindParam('valor', $this->valor_tot);
        $comando->bindParam('qtd', $this->qtd_produto);
        $comando->bindParam('periodo', $this->periodo);

        $valor = $comando->execute();

        if ($valor == 0) {
            return false;
        }
        return true;
    }

    public function selecionarProduto_Pedido()
    {
        $con = Connection::getConn();

        $sql = 'SELECT pp.*, p.nome FROM pedidos_produtos pp INNER JOIN produtos p ON p.codigo = pp.codigo_prod WHERE codigo_ped = :id ';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->cod_pedido);

        $comando->execute();

        $valores = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valores;
    }

    public function selecionarProduto_PedidoUser()
    {
        $con = Connection::getConn();

        $sql = 'SELECT pp.*, p.nome FROM pedidos_produtos pp INNER JOIN produtos p ON p.codigo = pp.codigo_prod INNER JOIN pedidos ped ON ped.codigo_ped = pp.codigo_ped WHERE pp.codigo_ped = :id AND ped.id_u = :id_u';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->cod_pedido);
        $comando->bindParam('id_u', $this->id_u);

        $comando->execute();

        $valores = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valores;
    }

    public function estoqueProdutoIndis()
    {
        $con = Connection::getConn();

        $sql = 'SELECT sum(pp.qtd_produto) as "qtd" FROM pedidos_produtos pp LEFT JOIN produtos p ON p.codigo = pp.codigo_prod INNER JOIN usuarios u ON p.dono_post = u.id_u WHERE u.id_u = :id GROUP BY p.codigo ORDER BY p.codigo';

        $comando = $con->prepare($sql);
        $comando->bindParam('id', $this->id_u);

        $comando->execute();

        $valor = $comando->fetchAll(PDO::FETCH_OBJ);

        return $valor;
    }

    /**
     * @return GETTER_SETTER
     */

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

    public function getMetodo_pagamento()
    {
        return $this->metodo_pagamento;
    }
    public function setMetodo_pagamento($metodo_pagamento)
    {
        $this->metodo_pagamento = $metodo_pagamento;
        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    public function getStatus_pedido()
    {
        return $this->status_pedido;
    }
    public function setStatus_pedido($status_pedido)
    {
        $this->status_pedido = $status_pedido;
        return $this;
    }

    /**
     * @return pedido_produtogetset
     */

    public function getId_pp()
    {
        return $this->id_pp;
    }
    public function setId_pp($id_pp)
    {
        $this->id_pp = $id_pp;
        return $this;
    }

    public function getCod_pedido()
    {
        return $this->cod_pedido;
    }
    public function setCod_pedido($cod_pedido)
    {
        $this->cod_pedido = $cod_pedido;
        return $this;
    }

    public function getCodigo_prod()
    {
        return $this->codigo_prod;
    }
    public function setCodigo_prod($codigo_prod)
    {
        $this->codigo_prod = $codigo_prod;
        return $this;
    }

    public function getValor_tot()
    {
        return $this->valor_tot;
    }
    public function setValor_tot($valor_tot)
    {
        $this->valor_tot = $valor_tot;
        return $this;
    }

    public function getQtd_produto()
    {
        return $this->qtd_produto;
    }
    public function setQtd_produto($qtd_produto)
    {
        $this->qtd_produto = $qtd_produto;
        return $this;
    }

    public function getPeriodo()
    {
        return $this->periodo;
    }
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}
