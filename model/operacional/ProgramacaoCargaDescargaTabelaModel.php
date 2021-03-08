<?php

Sistema::iniciaCarregando();

$pessoaUsuarioFiltro = Sistema::getPessoaUsuarioToStr($connect);

if (!empty($pessoaUsuarioFiltro)) {
    $filtroPessoaUsuario = " AND A.HANDLE IN (SELECT X.HANDLE
                                                FROM OP_PROGRAMACAO X
                                               WHERE X.CLIENTE IN ($pessoaUsuarioFiltro)

                                               UNION ALL

                                              SELECT X.HANDLE
                                                FROM OP_PROGRAMACAO X
                                               WHERE X.TRANSPORTADORA IN ($pessoaUsuarioFiltro)) ";
} else {
    $filtroPessoaUsuario = "";
}

$queryProgramacao = " SELECT DISTINCT TOP 1000 A.HANDLE HANDLE,  
                                               A.STATUS STATUS, 
                                               A.NUMERO NUMERO,
                                               A.TIPOPROCESSO TIPOPROCESSO,
                                               C.APELIDO TRANSPORTADORA, 
                                               A.DATA DATA, 
                                               D.NOME STATUSNOME,
                                               B.APELIDO CLIENTE,
                                               A.NUMEROPEDIDO NUMEROPEDIDO,
                                               D.RESOURCENAME RESOURCENAME,

                                                FROM OP_PROGRAMACAO A (NOLOCK) 
                                               INNER JOIN MS_PESSOA B (NOLOCK) ON B.HANDLE = A.CLIENTE 
                                                LEFT JOIN MS_PESSOA C (NOLOCK) ON C.HANDLE = A.TRANSPORTADORA
                                                LEFT JOIN MS_STATUS D (NOLOCK) ON D.HANDLE = A.STATUS 
                                               
                                             
                                              WHERE A.EMPRESA = $empresa
                                                AND A.STATUS NOT IN (4, 5)
                                                " . $filtroPessoaUsuario . "
                                                " . Sistema::getFiltroPostEntreDataMinuto('dataInicio', 'dataFinal', 'A.DATA') . "
                                                " . Sistema::getFiltroPostTexto("pedido", "A.NUMEROPEDIDO") . "
                                                " . Sistema::getFiltroPostLookup("transportadoraHandle", "A.TRANSPORTADORA") . " ";

                                                                     

try {
    $queryProgramacaoPrepare = $connect->prepare($queryProgramacao);
    $queryProgramacaoPrepare->execute();
    
    $rowProgramacao = $queryProgramacaoPrepare->fetch(PDO::FETCH_ASSOC);
    
    if (!empty($rowProgramacao)) {
        do {
            $programacaoHandle = $rowProgramacao['HANDLE'];
            $programacaoStatus = $rowProgramacao['STATUS'];
            
            $programacaoStatusIcone = Sistema::getImagem($rowProgramacao['RESOURCENAME'], $rowProgramacao['STATUSNOME']);
            $programacaoNumero = $rowProgramacao['NUMERO'];
            $programacaoNumeroPedido = $rowProgramacao['NUMEROPEDIDO'];
            $programacaoTransportadora = $rowProgramacao['TRANSPORTADORA'];
            $programacaoColeta = Sistema::formataDataHora($rowProgramacao['DATA']);
            $programacaoEntrega = Sistema::formataDataHora($rowProgramacao['PREVISAOENTREGA']);
            $programacaoProgramacaoEntrega = Sistema::formataDataHora($rowProgramacao['COLETAPROGRAMADO']);
            $programacaoTipo = $rowProgramacao['TIPO'];
            $programacaoFilial = $rowProgramacao['FILIAL'];
            $programacaoClienteFinal = $rowProgramacao['CLIENTEFINAL'];
            $programacaoLocalEntrega = $rowProgramacao['LOCALENTREGA'];
            $programacaoMunicipioLocalEntrega = $rowProgramacao['MUNICIPIOLOCALENTREGA'];
            $programacaoEstadoLocalEntrega = $rowProgramacao['ESTADOLOCALENTREGA'];
            $programacaoVolume = $rowProgramacao['QUANTIDADEEXPEDICAO'];
            $programacaoEmbalagem = $rowProgramacao['QUANTIDADEEMBALAGEM'];
            $programacaoPesoBruto = Sistema::formataValor($rowProgramacao['PESOBRUTO']);
            $programacaoTipoVeiculo = $rowProgramacao['TIPOVEICULO'];
            $programacaoVeiculo = $rowProgramacao['VEICULO'];
            $programacaoAcoplado = $rowProgramacao['ACOPLADO'];
            $programacaoConteiner = $rowProgramacao['CONTEINER'];
            $programacaoMotorista = $rowProgramacao['MOTORISTA'];
            $programacaoOBS = Sistema::formataTexto($rowProgramacao['OBSERVACAO']);

            $programacaoColetaOrdenacao = strtotime($rowProgramacao['DATA']);
            $programacaoEntregaOrdenacao = strtotime($rowProgramacao['PREVISAOENTREGA']);
            $programacaoProgramacaoEntregaOrdenacao = strtotime($rowProgramacao['COLETAPROGRAMADO']);
            echo "  <tr>
                        <td class='handle' hidden='true'>$programacaoHandle</td>
                        <td>$programacaoStatusIcone</td>
                        <td class=\"text-right\">$programacaoNumero</td>
                        <td class=\"text-right\">$programacaoNumeroPedido</td>
                        <td>$programacaoTransportadora</td>
                    </tr>";
        } while ($rowProgramacao = $queryProgramacaoPrepare->fetch(PDO::FETCH_ASSOC));
    } else {
        Sistema::getNaoEncontrado();
    }
} catch (Exception $e) {
    Sistema::tratarErro($e);
}
