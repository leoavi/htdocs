<?php

$queryRastreamentoDocumento->execute();

while ($rowRastreamentoDocumento = $queryRastreamentoDocumento->fetch(PDO::FETCH_ASSOC)) {
    $handleRastreamentoDocumento = $rowRastreamentoDocumento['HANDLE'];
    $numeroRastreamentoDocumento = $rowRastreamentoDocumento['NUMERODOCUMENTO'];
    $tipoRastreamentoDocumento = $rowRastreamentoDocumento['TIPODOCUMENTO'];
    $dataEmissaoRastreamentoDocumento = date('d/m/Y', strtotime($rowRastreamentoDocumento['DATAEMISSAO']));
    $valorRastreamentoDocumento = $rowRastreamentoDocumento['VALORDOCUMENTO'];
    $emitenteRastreamentoDocumento = $rowRastreamentoDocumento['TOMADOR'];
    $handleAnexoComprovante = $rowRastreamentoDocumento['HANDLEANEXOCOMPROVANTE'];
    $handleOcorrenciaComprovante = $rowRastreamentoDocumento['HANDLEOCORRENCIACOMPROVANTE'];
    ?>
    
    <tr style="background-color: #F5F5F5;">
        <td style="border: 0; width: 10%"><label><?php echo $tipoRastreamentoDocumento; ?> </label> <?php echo $numeroRastreamentoDocumento; ?></td>
        <td style="border: 0;"><label>Emissão:</label> <?php echo $dataEmissaoRastreamentoDocumento; ?></td>
        <td style="border: 0;"><label>Tomador:</label> <?php echo $emitenteRastreamentoDocumento; ?></td>
        <td> <a href="#"> <img id="baixarPDF" src="..\..\view\tecnologia\img\pdf_icon.svg" style="width:24px;height:24px;"/ onclick="BaixarDocumentoPdf(<?php echo $handleRastreamentoDocumento; ?>)" /> </a> </td>
        <td> <a href="#"> <img id="baixarXML" src="..\..\view\tecnologia\img\xml_icon.svg" style="width:24px;height:24px;" onclick="BaixarDocumentoXml(<?php echo $handleRastreamentoDocumento; ?>)" /> </a> </td>
        <td>
        <?php
            if ($handleAnexoComprovante != null)
            {
        ?>
            <a href="#"> <img id="baixarCOMPROVANTE" src="..\..\view\tecnologia\img\comprovante_icon.svg" style="width:24px;height:24px;" 
                    <?php
                        if ($handleAnexoComprovante != null)
                            echo 'onclick="BaixarDocumentoComprovante('.$handleAnexoComprovante.', '.$handleOcorrenciaComprovante.')" ';                                     
                    ?>
            /> </a> 
        <?php
            }
        ?>
        </td>
        <td style="border: 0; text-align: right;"><label>Valor</label> <?php echo number_format($valorRastreamentoDocumento, 2, ',', '.'); ?></td>
        
        
    </tr>
    <div>
    
            <?php    
            $queryRastreamentoDocumentoOriginario->execute(['DOCUMENTO' => $handleRastreamentoDocumento]);
            
            while ($rowDocumentoOriginario = $queryRastreamentoDocumentoOriginario->fetch(PDO::FETCH_ASSOC)) {
                $tipoDocumentoOriginario = $rowDocumentoOriginario['TIPODOCUMENTO'];
                $numeroDocumentoOriginario = $rowDocumentoOriginario['NUMERODOCUMENTO'];
                $serieDocumentoOriginario = $rowDocumentoOriginario['SERIE'];
                $chaveAcessoDocumentoOriginario = $rowDocumentoOriginario['CHAVEDOCUMENTOELETRONICO'];
                $valorTotalDocumentoOriginario = $rowDocumentoOriginario['VALORTOTAL'];
                
                
            ?>
                
                <tr style="background-color: #FFFFF5;font-size:13px">        
                    <td style="padding-left: 20pt; border: 0; width: 10%"><label><?php echo $tipoDocumentoOriginario; ?> </label> <?php echo $numeroDocumentoOriginario; ?> - <?php echo $serieDocumentoOriginario; ?></td>            
                    <td style="border: 0;"><label>Emissão:</label> <?php echo $dataEmissaoRastreamentoDocumento; ?></td>
                    <td style="border: 0;"><label>Chave de acesso:</label> <?php echo $chaveAcessoDocumentoOriginario; ?></td>
                    <td>  </td>
                    <td>  </td>                          
                    <td>  </td>     
                    <td style="border: 0; text-align: right;"><label>Valor</label> <?php echo number_format($valorTotalDocumentoOriginario, 2, ',', '.'); ?></td>
                </tr>        
                
            <?php
            }
            ?>
      </div>
    
    <?php
}