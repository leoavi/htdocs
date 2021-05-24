<?php
include_once('../../controller/tecnologia/Sistema.php');
include_once('../../controller/tecnologia/WS.php');

date_default_timezone_set('America/Sao_Paulo');

$connect = Sistema::getConexao();

$filial = Sistema::getPost('FILIAL');
$tipo = Sistema::getPost('TIPO');
$data = Sistema::getPost('DATA');
$cliente = Sistema::getPost('CLIENTE');
$observacao = Sistema::getPost('OBSERVACAO');

$query = "UPDATE MS_USUARIO SET OBSERVACAO = 1 WHERE HANDLE = 154"
$query = $connect->prepare($query);						 
$query->execute();

if (isset($dados["CHAVE"])) {
    echo $dados["CHAVE"];
} else {
    echo Sistema::retornoJson(500, $body);
}