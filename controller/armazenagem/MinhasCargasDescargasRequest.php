<?php
include_once('../tecnologia/Sistema.php');
include_once('MinhasCargasDescargasController.php');

$conexao = Sistema::getConexao();

$minhasCargasDescargas = new MinhasCargasDescargasController($conexao);

switch($_POST['REQUEST']) {
    
    case 'getRegistro':
        $dadosFiltro = json_decode(json_encode($_POST["FILTRO"]));
        
        $filtro = "";
        if(is_array($dadosFiltro->TIPO)) {
            $filtro = " AND A.TIPO IN (" . implode(',', $dadosFiltro->TIPO) . ") ";
        }
        
        if(is_array($dadosFiltro->CLIENTE)) {
            $filtro .= " AND A.CLIENTE IN (" . implode(',', $dadosFiltro->CLIENTE) . ") ";
        }
        
        if(is_array($dadosFiltro->PROCESSO)) {
            $filtro .= " AND A.TIPOPROCESSO IN (" . implode(',', $dadosFiltro->PROCESSO) . ") ";
        }

        if(is_array($dadosFiltro->TRANSPORTADORA)) {
            $filtro .= " AND A.TRANSPORTADORA IN (" . implode(',', $dadosFiltro->TRANSPORTADORA) . ") ";
        }
        
        if(!empty($dadosFiltro->NRCONTROLE)) {
            $filtro .= " AND A.NUMEROCONTROLE = '" . $dadosFiltro->NRCONTROLE . "' ";
        }

        if(!empty($dadosFiltro->NRORDEM)) {
            $filtro .= " AND F.NUMERO = '" . $dadosFiltro->NRORDEM . "' ";
        }
        
        if(!empty($dadosFiltro->NRPEDIDO)) {
            $filtro .= " AND F.NUMEROPEDIDO = '" . $dadosFiltro->NRPEDIDO . "' ";
        }
               
        
        $rows = $minhasCargasDescargas->getRegistro($filtro);
        
        echo json_encode($rows);
    break;    
    
    case 'getFiltroTipo':
        $minhasCargasDescargas->montaFiltro();
        
        echo json_encode($minhasCargasDescargas->getFiltro('TIPO'));
    break;    

    case 'getFiltroCliente':
        $minhasCargasDescargas->montaFiltro();
        
        echo json_encode($minhasCargasDescargas->getFiltro('CLIENTE'));
    break;

    case 'getFiltroProcesso':
        $minhasCargasDescargas->montaFiltro();
        
        echo json_encode($minhasCargasDescargas->getFiltro('PROCESSO'));
    break;
    
    case 'getFiltroTransportadora':
        $minhasCargasDescargas->montaFiltro();
        
        echo json_encode($minhasCargasDescargas->getFiltro('TRANSPORTADORA'));
    break;    
}