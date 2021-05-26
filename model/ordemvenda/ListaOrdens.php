<?php
include_once('../../controller/tecnologia/Sistema.php');

date_default_timezone_set('America/Sao_Paulo');

$connect = Sistema::getConexao();
$pendente = Sistema::getGet('pendente');
$usuario = $_SESSION['handleUsuario'];
$start = $_GET["start"];
$length = $_GET["length"] + $start;
$search = $_GET["pesquisa"];
$cliente = $_GET["cliente"];

$order = $_GET["order"];
$columns = $_GET["columns"];
$col = 0;
$dir = "desc";

$sqlPessoas = 
"
SELECT PESSOA FROM MS_USUARIOPESSOA WHERE USUARIO = '". $usuario ."'
";

$sqlPessoas = $connect->exec($sqlPessoas);

$pessoas = $sqlPessoas->fetch(PDO::FETCH_ASSOC);
$pessoas["PESSOA"] = Sistema::formataValor($pessoas["PESSOA"]);

if (!empty($order)) {
    foreach ($order as $o) {
        $col = $o['column'];
        $dir = $o['dir'];
    }
}

$where = [];
$whereOr = [];

}

if($search){
    $whereOr[] =  "D.NOME LIKE '%".$search."%'";
    $whereOr[] =  "F.NOME LIKE '%".$search."%'";
    $whereOr[] =  "E.NOME LIKE '%".$search."%'";

    if(intval($search) !== 0){
        $whereOr[] =  "A.NUMERO = '" . intval($search) . "'";
    }
    
}

if(count($whereOr) > 0){
    $where[] = '(' . join(' OR ', $whereOr) . ')';
}

$whereTexto = '';
if(count($where) > 0){
    $whereTexto = "WHERE " . join(' AND ', $where);
}

$order = "ORDER BY A." . $columns[$col]["data"] . " " . $dir;

$sqlOrdens = "
SELECT EMISSAO, FROM OP_PROGRAMACAO
WHERE CLIENTE IN ('". $pessoas ."')
";

$queryOrdens = $connect->prepare($sqlOrdens);
$queryOrdens->execute();

$ordens = [];

while($dados = $queryOrdens->fetch(PDO::FETCH_ASSOC)){
    // $dados["STATUS"] = Sistema::getImagem($dados['RESOURCENAME'], $dados['STATUSNOME']);
    $dados["DATA"] = Sistema::formataData($dados["DATA"]);
    $dados["VALORTOTAL"] = Sistema::formataValor($dados["VALORTOTAL"]);

    $ordens[] = $dados;
}

$sqlOrdensFiltro = "SELECT COUNT(A.HANDLE) FILTRADO
FROM VE_ORDEM A
INNER JOIN MS_STATUS B ON A.STATUS = B.HANDLE
INNER JOIN MD_IMAGEM C ON B.IMAGEM = C.HANDLE
INNER JOIN MS_PESSOA D ON A.CLIENTE = D.HANDLE
LEFT JOIN FN_TIPOPAGAMENTO E ON A.FORMAPAGAMENTO = E.HANDLE
INNER JOIN VE_TIPOORDEM F ON A.TIPO = F.HANDLE
$whereTexto";


$queryOrdensFiltro = $connect->prepare($sqlOrdensFiltro);
$queryOrdensFiltro->execute();

$filtro = $queryOrdensFiltro->fetch(PDO::FETCH_ASSOC);

$sqlOrdensTotal = "SELECT COUNT(A.HANDLE) TOTAL
FROM VE_ORDEM A
INNER JOIN MS_STATUS B ON A.STATUS = B.HANDLE
INNER JOIN MD_IMAGEM C ON B.IMAGEM = C.HANDLE
INNER JOIN MS_PESSOA D ON A.CLIENTE = D.HANDLE
LEFT JOIN FN_TIPOPAGAMENTO E ON A.FORMAPAGAMENTO = E.HANDLE
INNER JOIN VE_TIPOORDEM F ON A.TIPO = F.HANDLE
$whereTexto";


$queryOrdensTotal = $connect->prepare($sqlOrdensTotal);
$queryOrdensTotal->execute();

$total = $queryOrdensTotal->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    "draw" => $_GET['draw'],
    "recordsTotal" => $total["TOTAL"],
    "recordsFiltered" => $filtro["FILTRADO"],
    "data" => $ordens
]);