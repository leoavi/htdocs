<?php
include_once('../../controller/tecnologia/Sistema.php');
include_once('../../controller/tecnologia/WS.php');

date_default_timezone_set('America/Sao_Paulo');

$connect = Sistema::getConexao(false);

$filial = Sistema::getPost('FILIAL');
$tipo = Sistema::getPost('TIPO');
$data = Sistema::getPost('DATA');
$cliente = Sistema::getPost('CLIENTE');
$observacao = Sistema::getPost('OBSERVACAO');

$observacao = 'roi';

// $connect->beginTransaction()
$connect->exec("UPDATE MS_USUARIO SET OBSERVACAO = 'oi' WHERE HANDLE = 154");
// $connect->commit();


$retorno = 'vsf';

echo Sistema::retornoJson(500, $retorno);



// if (isset($dados["CHAVE"])) {
//     echo $dados["CHAVE"];
// } else {
//     echo Sistema::retornoJson(500, $body);
// }