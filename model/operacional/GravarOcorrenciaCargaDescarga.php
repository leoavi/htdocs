<?php
include_once('../../controller/tecnologia/Sistema.php');
include_once('../../controller/tecnologia/WS.php');

date_default_timezone_set('America/Argentina/Buenos_aires');

$connect = Sistema::getConexao();

$carregamento = Sistema::getPost('handleCarregamento');
$tipoOcorrenciaHandle = Sistema::getPost('tipoOcorrenciaHandle');
$veiculo = Sistema::getPost('veiculo');
$progDocaHandle = Sistema::getPost('progDocaHandle');
$docaHandle = Sistema::getPost('docaHandle');
$acoplado = Sistema::getPost('acoplado');
$conteinerHandle = Sistema::getPost('conteinerHandle');
$motorista = Sistema::getPost('motorista');
$observacao = Sistema::getPost('observacao');
$ufVeiculoHandle = Sistema::getPost('ufVeiculoHandle');
$ufAcopladoHandle = Sistema::getPost('ufAcopladoHandle');
$propriedadeVeiculoHandle = Sistema::getPost('propriedadeVeiculoHandle');
$documentoMotorista = Sistema::getPost('documentoMotorista');
$tipoVeiculoHandle = Sistema::getPost('tipoVeiculoHandle');
$data = Sistema::getDataAtual();


$queryTransportadora = $connect->prepare("SELECT TRANSPORTADORA FROM AM_CARREGAMENTO WHERE HANDLE = '" . $carregamento . "'");
$queryTransportadora->execute();
$rowTransportadora = $queryTransportadora->fetch(PDO::FETCH_ASSOC);
$transportadora = Sistema::formataInt($rowTransportadora['TRANSPORTADORA']);

$arr = array("cargaDescarga" => array());
$arr["cargaDescarga"]["handleCargaDescarga"] = $carregamento;
$arr["cargaDescarga"]["tipoOcorrencia"] = $tipoOcorrenciaHandle;
if(!empty($progDocaHandle)){
    $arr["cargaDescarga"]["progDoca"] = $progDocaHandle;
    $arr["cargaDescarga"]["doca"] = $docaHandle;
}
if(!empty($veiculo)){
    $arr["cargaDescarga"]["veiculo"] = $veiculo;
}
if(!empty($acoplado)){
    $arr["cargaDescarga"]["acoplado"] = $acoplado;
}
if(!empty($conteinerHandle)){
    $arr["cargaDescarga"]["conteiner"] = $conteinerHandle;
}
    $arr["cargaDescarga"]["observacao"] = $observacao;
if(!empty($ufVeiculoHandle)){
    $arr["cargaDescarga"]["ufVeiculo"] = $ufVeiculoHandle;
}
if(!empty($ufAcopladoHandle)){
    $arr["cargaDescarga"]["ufAcoplado"] = $ufAcopladoHandle;
}
if(!empty($propriedadeVeiculoHandle)){
    $arr["cargaDescarga"]["propriedadeVeiculo"] = $propriedadeVeiculoHandle;
}
if(!empty($motorista)){
    $arr["cargaDescarga"]["motorista"] = $motorista;
}
if(!empty($documentoMotorista)){
    $arr["cargaDescarga"]["docMotorista"] = $documentoMotorista;
}
if(!empty($tipoVeiculoHandle)){
    $arr["cargaDescarga"]["tipoVeiculo"] = $tipoVeiculoHandle;
}
$arr["cargaDescarga"]["transportadora"] = $transportadora;

$arr["cargaDescarga"]["usuario"] = $handleUsuario;
$arr["cargaDescarga"]["data"] = $data;



WebService::setupCURL("armazenagem/criar/ocorrenciaCargaDescarga", $arr);

WebService::execute();

$body = WebService::getBody();

$dados = json_decode($body);

if (isset($dados->CHAVE)) {
    echo $body;
} else {
    echo Sistema::retornoJson(500, $body);
}