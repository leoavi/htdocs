<?php

    include_once('../../controller/tecnologia/Sistema.php');
    
    include_once('../../model/rastreamento/ModelPedido.php');
    include_once('../../model/rastreamento/ModelRastreioPedidoOcorrencia.php');

    date_default_timezone_set('America/Sao_Paulo');

    //var_dump($_POST);
    $acao = $_POST['ACAO'];
    
    $Pedido = new Pedido();
    $Ocorrencia = new Ocorrencia();
    
    switch ($acao) {
        case "getHandlePorRastreamento":
            $rastreamento = $_POST['RASTREAMENTO'];

            $Pedido->getHandlePorRastreamento($rastreamento);
        break;

        case "getEtapaEvento":
            $handle = $_POST['HANDLE'];

            $Pedido->setHandle($handle);
            $Pedido->getEtapaEvento();
        break;

        case "getPrimeiraEmpresa":
            $Pedido->getPrimeiraEmpresa();

        break;
        case "getDocumentoPdf":
            $documentoHandle = $_POST['DOCUMENTO'];

            $Pedido->setDocumentoHandle($documentoHandle);
            $Pedido->getDocumentoPdf();
        break;
        case "getDocumentoXml":
            $documentoHandle = $_POST['DOCUMENTO'];

            $Pedido->setDocumentoHandle($documentoHandle);
            $Pedido->getDocumentoXml();
        break;
        case "getOcorrenciaTransporte":
            $ocorrenciaHandle = $_POST['OCORRENCIA'];
            
            $Ocorrencia->setHandle($ocorrenciaHandle);
            $Ocorrencia->getOcorrencia();
        break;
        default: 
            throw new Exception("Ação inválida",
                                "Ação: ".$acao);
    }

    ?>