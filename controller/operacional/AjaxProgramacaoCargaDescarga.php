<?php

include_once('../tecnologia/Sistema.php');

$connect = Sistema::getConexao();
$metodo = Sistema::getPost('metodo');
$retorno = array();

switch ($metodo) {
    
    case 'CarregarOcorrencia': {
            try {
                $carregamento = Sistema::getPost('handleCarregamento');

                $queryCarregamento = "SELECT A.NUMERO,
                                             A.PREVISAOENTREGA,
       
                                             B.NOME TIPO,
                                             A.TIPO TIPOHANDLE,
                                             B.ACAO ACAOHANDLE,
 
                                             CONVERT(VARCHAR(10), C.PREVISAO, 103) + ' ' + CONVERT(VARCHAR(10), C.PREVISAO, 108) + ' - ' + D.NOME PROGRAMACAODOCA,
                                             A.PROGRAMACAODOCA PROGRAMACAODOCAHANDLE,
                                             C.DOCA DOCAHANDLE,
 
                                             E.NOME TIPOVEICULO,
                                             A.TIPOVEICULO TIPOVEICULOHANDLE,
 
                                             A.PLACA VEICULO,
 
                                             F.SIGLA VEICULOUF,
                                             A.UFPLACA VEICULOUFHANDLE,

                                             F1.SIGLA ACOPLADOUF,
                                             A.UFCARRETA ACOPLADOUFHANDLE,
 
                                             G.NOME PROPRIEDADEVEICULO,
                                             A.PROPRIEDADEVEICULO PROPRIEDADEVEICULOHANDLE,
 
                                             A.CARRETA REBOQUE,
 
                                             H.CODIGO CONTEINER,
                                             A.CONTEINER CONTEINERHANDLE,
 
                                             A.MOTORISTA,
                                             A.DOCUMENTO MOTORISTADOCUMENTO,
       
                                             (SELECT COUNT(X.HANDLE)
                                                FROM AM_CARREGAMENTOOCORRENCIA X (NOLOCK)
                                               WHERE X.CARREGAMENTO = A.HANDLE
                                                 AND X.STATUS <> 10
                                                 AND X.ACAO IN (2, 5)) QUANTIDADEOCORRENCIA

                                        FROM AM_CARREGAMENTO A (NOLOCK)
                                        LEFT JOIN AM_TIPOCARREGAMENTOOCORRENCIA B (NOLOCK) ON B.HANDLE = A.TIPO
                                        LEFT JOIN AM_PROGRAMACAODOCA C (NOLOCK) ON C.HANDLE = A.PROGRAMACAODOCA
                                        LEFT JOIN AM_DOCA D (NOLOCK) ON D.HANDLE = C.DOCA
                                        LEFT JOIN MF_TIPOVEICULO E (NOLOCK) ON E.HANDLE = A.TIPOVEICULO
                                        LEFT JOIN MS_ESTADO F (NOLOCK) ON F.HANDLE = A.UFPLACA
                                        LEFT JOIN MS_ESTADO F1 (NOLOCK) ON F.HANDLE = A.UFCARRETA
                                        LEFT JOIN MF_PROPRIEDADEVEICULO G (NOLOCK) ON G.HANDLE = A.PROPRIEDADEVEICULO
                                        LEFT JOIN PA_CONTEINER H (NOLOCK) ON H.HANDLE = A.CONTEINER
                                       WHERE A.HANDLE = $carregamento";

                $queryCarregamentoPrepare = $connect->prepare($queryCarregamento);
                $queryCarregamentoPrepare->execute();

                $rowCarregamento = $queryCarregamentoPrepare->fetch(PDO::FETCH_ASSOC);

                $quantidadeOcorrencia = $rowCarregamento['QUANTIDADEOCORRENCIA'];
                
                $retorno['NUMERO'] = $rowCarregamento['NUMERO'];
                $retorno['PREVISAOENTREGA'] = Sistema::formataDataHoraMascaraTimeZone($rowCarregamento['PREVISAOENTREGA']);
                                
                $queryCarregamentoOcorrencia = "SELECT A.HANDLE,

                                                       B.NOME TIPO,
                                                       A.TIPO TIPOHANDLE,
                                                       A.ACAO ACAOHANDLE,

                                                       CONVERT(VARCHAR(10), C.PREVISAO, 103) + ' ' + CONVERT(VARCHAR(10), C.PREVISAO, 108) + ' - ' + D.NOME PROGRAMACAODOCA,
                                                       A.PROGRAMACAODOCA PROGRAMACAODOCAHANDLE,
                                                       A.DOCA DOCAHANDLE,

                                                       E.NOME TIPOVEICULO,
                                                       A.TIPOVEICULO TIPOVEICULOHANDLE,
                                                       
                                                       A.VEICULO,

                                                       F.SIGLA VEICULOUF,
                                                       A.VEICULOUF VEICULOUFHANDLE,

                                                       F1.SIGLA ACOPLADOUF,
                                                       A.REBOQUEUF ACOPLADOUFHANDLE,

                                                       G.NOME PROPRIEDADEVEICULO,
                                                       A.PROPRIEDADEVEICULO PROPRIEDADEVEICULOHANDLE,

                                                       A.REBOQUE,

                                                       H.CODIGO CONTEINER,
                                                       A.CONTEINER CONTEINERHANDLE,

                                                       A.MOTORISTA,
                                                       A.MOTORISTADOCUMENTO,
                                                       A.OBSERVACAO

                                                  FROM AM_CARREGAMENTOOCORRENCIA A (NOLOCK)
                                                  LEFT JOIN AM_TIPOCARREGAMENTOOCORRENCIA B (NOLOCK) ON B.HANDLE = A.TIPO
                                                  LEFT JOIN AM_PROGRAMACAODOCA C (NOLOCK) ON C.HANDLE = A.PROGRAMACAODOCA
                                                  LEFT JOIN AM_DOCA D (NOLOCK) ON D.HANDLE = C.DOCA
                                                  LEFT JOIN MF_TIPOVEICULO E (NOLOCK) ON E.HANDLE = A.TIPOVEICULO
                                                  LEFT JOIN MS_ESTADO F (NOLOCK) ON F.HANDLE = A.VEICULOUF
                                                  LEFT JOIN MS_ESTADO F1 (NOLOCK) ON F.HANDLE = A.REBOQUEUF
                                                  LEFT JOIN MF_PROPRIEDADEVEICULO G (NOLOCK) ON G.HANDLE = A.PROPRIEDADEVEICULO
                                                  LEFT JOIN PA_CONTEINER H (NOLOCK) ON H.HANDLE = A.CONTEINER
                                                  
                                                 WHERE A.CARREGAMENTO = $carregamento
                                                   AND A.STATUS NOT IN (3, 9, 10)
                                                   
                                                   AND NOT EXISTS(SELECT X.HANDLE
                                                                    FROM AM_CARREGAMENTOOCORRENCIA X (NOLOCK)
                                                                   WHERE A.CARREGAMENTO = A.CARREGAMENTO
                                                                     AND X.STATUS NOT IN (3, 9, 10)
                                                                     AND X.DATA > A.DATA)";

                $queryCarregamentoOcorrenciaPrepare = $connect->prepare($queryCarregamentoOcorrencia);
                $queryCarregamentoOcorrenciaPrepare->execute();

                $rowCarregamentoOcorrencia = $queryCarregamentoOcorrenciaPrepare->fetch(PDO::FETCH_ASSOC);

                $retorno['HANDLE'] = Sistema::formataInt($rowCarregamentoOcorrencia['HANDLE']);
                
                if (empty($retorno['HANDLE']) && !empty($quantidadeOcorrencia)) {
                    $rowRegistro = $rowCarregamento;
                } else {
                    $rowRegistro = $rowCarregamentoOcorrencia;
                    
                    $retorno['OBSERVACAO'] = $rowCarregamentoOcorrencia['OBSERVACAO'];
                }
                
                $retorno['TIPO'] = $rowRegistro['TIPO'];
                $retorno['TIPOHANDLE'] = $rowRegistro['TIPOHANDLE'];
                $retorno['ACAOHANDLE'] = $rowRegistro['ACAOHANDLE'];
                $retorno['PROGRAMACAODOCA'] = $rowRegistro['PROGRAMACAODOCA'];
                $retorno['PROGRAMACAODOCAHANDLE'] = $rowRegistro['PROGRAMACAODOCAHANDLE'];
                $retorno['DOCAHANDLE'] = $rowRegistro['DOCAHANDLE'];
                $retorno['TIPOVEICULO'] = $rowRegistro['TIPOVEICULO'];
                $retorno['TIPOVEICULOHANDLE'] = $rowRegistro['TIPOVEICULOHANDLE'];
                $retorno['VEICULO'] = $rowRegistro['VEICULO'];
                $retorno['VEICULOUF'] = $rowRegistro['VEICULOUF'];
                $retorno['VEICULOUFHANDLE'] = $rowRegistro['VEICULOUFHANDLE'];
                $retorno['ACOPLADOUF'] = $rowRegistro['ACOPLADOUF'];
                $retorno['ACOPLADOUFHANDLE'] = $rowRegistro['ACOPLADOUFHANDLE'];
                $retorno['PROPRIEDADEVEICULO'] = $rowRegistro['PROPRIEDADEVEICULO'];
                $retorno['PROPRIEDADEVEICULOHANDLE'] = $rowRegistro['PROPRIEDADEVEICULOHANDLE'];
                $retorno['REBOQUE'] = $rowRegistro['REBOQUE'];
                $retorno['CONTEINER'] = $rowRegistro['CONTEINER'];
                $retorno['CONTEINERHANDLE'] = $rowRegistro['CONTEINERHANDLE'];
                $retorno['MOTORISTA'] = $rowRegistro['MOTORISTA'];
                $retorno['MOTORISTADOCUMENTO'] = $rowRegistro['MOTORISTADOCUMENTO'];
                
                $retorno['ERRO'] = '';
            } catch (Exception $erro) {
                $retorno['ERRO'] = $erro->getMessage();
            }
        }
}

Sistema::echoToJson($retorno);
