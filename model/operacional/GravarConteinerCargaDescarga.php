<?php
include_once('../../controller/tecnologia/Sistema.php');
include_once('../../controller/tecnologia/WS.php');

date_default_timezone_set('America/Argentina/Buenos_aires');

$connect = Sistema::getConexao();

$codigoConteiner = Sistema::getPost('codigoConteiner');
$tipoEquipamento = Sistema::getPost('tipoEquipamento');
$tipoEquipamentoHandle = Sistema::getPost('tipoEquipamentoHandle');
$codigoISO = Sistema::getPost('codigoISO');
$codigoISOHandle = Sistema::getPost('codigoISOHandle');
$altura = Sistema::getPost('alturaConteiner');
$largura = Sistema::getPost('larguraConteiner');
$comprimento = Sistema::getPost('comprimentoConteiner');
$capacidade = Sistema::getPost('capacidadeConteiner');
$tara = Sistema::getPost('taraConteiner');
$mgw = Sistema::getPost('mgwConteiner');
$fabricacao = Sistema::getPost('fabricacaoConteiner');
$obsInserirConteiner = Sistema::getPost('obsInserirConteiner');

$data = Sistema::getDataAtual();

$arr = array("conteiner" => array());
$arr["conteiner"]["codigoConteiner"] = $codigoConteiner;
$arr["conteiner"]["tipoEquipamento"] = $tipoEquipamentoHandle;
$arr["conteiner"]["codigoISO"] = $codigoISOHandle;
$arr["conteiner"]["altura"] = $altura;
$arr["conteiner"]["largura"] = $largura;
$arr["conteiner"]["comprimento"] = $comprimento;
$arr["conteiner"]["capacidade"] = $capacidade;
$arr["conteiner"]["tara"] = $tara;
$arr["conteiner"]["mgw"] = $mgw;
$arr["conteiner"]["fabricacao"] = $fabricacao;
$arr["conteiner"]["observacao"] = $obsInserirConteiner;
$arr["conteiner"]["usuario"] = $handleUsuario;
$arr["conteiner"]["data"] = $data;

WebService::setupCURL("armazenagem/criar/conteiner", $arr);

WebService::execute();

$body = WebService::getBody();

$dados = json_decode($body);

if (isset($dados->CHAVE)) {
    echo $dados->CHAVE;
} else {
    echo Sistema::retornoJson(500, $body);
}