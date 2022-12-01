<?php

namespace Project\Lib;

class Helpers
{

    public static function Loader()
    {
        $loader = new \Twig\Loader\FilesystemLoader('App/View');
        $twig = new \Twig\Environment($loader);
        return $twig;
    }

    public static function begin()
    {
        session_start();
    }

    public static function begincarrinho()
    {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    public static function validaCPF($cpf = null)
    {

        if (empty($cpf)) {
            return false;
        }        
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        
        if (strlen($cpf) != 11) {
            return false;
        }
        else if (
            $cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999'
        ) {
            return false;
        } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    public static function celular($telefone)
    {
        $telefone = trim(str_replace('/', '', str_replace(' ', '', str_replace('-', '', str_replace(')', '', str_replace('(', '', $telefone))))));

        $regexCel = '/[0-9]{2}[6789][0-9]{3,4}[0-9]{4}/';
        if (preg_match($regexCel, $telefone)) {
            return true;
        } else {
            return false;
        }
    }

    public static function data()
    {
    }
}
