<?php

/**
 * @author              Kaio Eduardo Malek Moreira
 * @copyright           (c) 2020-2020, Kaio Eduardo Malek Moreira. All Rights Reserved. *
 */

define(
    'DS',
    DIRECTORY_SEPARATOR
);

require_once __DIR__ . DS . 'vendor' . DS . 'autoload.php';

use Project\Core\Core as Rotas;
use Project\Lib\Helpers;

ob_start();

Helpers::begin();
Helpers::begincarrinho();
$core = new Rotas;
$core->start($_GET);

$saida = ob_get_contents();

ob_end_clean();

echo $saida;
