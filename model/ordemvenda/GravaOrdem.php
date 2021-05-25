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

// $connect->beginTransaction()
$retorno = $connect->exec("UPDATE MS_USUARIO SET OBSERVACAO = $observacao WHERE HANDLE = 154");
// $connect->commit();

echo Sistema::retornoJson(500, $retorno);



// if (isset($dados["CHAVE"])) {
//     echo $dados["CHAVE"];
// } else {
//     echo Sistema::retornoJson(500, $body);
// }