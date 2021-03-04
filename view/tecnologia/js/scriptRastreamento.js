$(function () {
	
	ComportamentoOld();
});


const b64toBlob = (b64Data, contentType='', sliceSize=512) => {
  const byteCharacters = atob(b64Data);
  const byteArrays = [];

  for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
    const slice = byteCharacters.slice(offset, offset + sliceSize);

    const byteNumbers = new Array(slice.length);
    for (let i = 0; i < slice.length; i++) {
      byteNumbers[i] = slice.charCodeAt(i);
    }

    const byteArray = new Uint8Array(byteNumbers);
    byteArrays.push(byteArray);
  }

  const blob = new Blob(byteArrays, {type: contentType});
  return blob;
}

function BaixarDocumentoPdf(handle){        

    $.ajax({
        url: "../../controller/rastreamento/Pedido.php",
        method: "POST",
        dataType: "JSON",
        data: {
            "ACAO"       : "getDocumentoPdf",
            "DOCUMENTO"  : handle
        },
        
        success: function(data) {
            
            const blob = b64toBlob(data.Arquivo, "application/octet-stream");
            var link = document.createElement('a');            
            link.href=window.URL.createObjectURL(blob);
            
            link.download = "Impressao_" + handle + ".pdf";
            link.click();
        }, 
        
        error: function( jqXHR, textStatus ) {
            var jsonErro = JSON.parse(jqXHR.responseText);
             
            swal({
                title: "Oopss!",
                text: "Não foi possível baixar o pdf: " + jsonErro.code + ":" + jsonErro.message,
                icon: "error"
            });

        }
        
        
    });
}

function BaixarDocumentoXml(handle){
    
    $.ajax({
        url: "../../controller/rastreamento/Pedido.php",
        method: "POST",
        dataType: "JSON",
        data: {
            "ACAO"       : "getDocumentoXml",
            "DOCUMENTO"  : handle
        },
        success: function(data) {
            const blob = b64toBlob(data.Arquivo, "application/octet-stream");
            var link = document.createElement('a');            
            link.href=window.URL.createObjectURL(blob);
            
            link.download = handle + ".xml";
            link.click();
        },
        error: function( jqXHR, textStatus ) {
            var jsonErro = JSON.parse(jqXHR.responseText);
             
            swal({
                title: "Oopss!",
                text: "Não foi possível baixar o pdf: " + jsonErro.code + ":" + jsonErro.message,
                icon: "error"
            });

        }
    });
}

function BaixarDocumentoComprovante(handleAnexo, handleOcorrecia){
    
    $.ajax({
            url: "../../controller/rastreamento/Ocorrencia.php", 
            method: "POST",
            dataType: "html",            
            data: {
                "ACAO"      : "baixarOcorrenciaTransporteAnexo",
                "ANEXO"     : handleAnexo,
                "OCORRENCIA": handleOcorrecia
            },
            success: function (retorno, status, request) {                
                var link=document.createElement('a');
                link.href='data:application/octet-stream;base64,' + retorno;
                link.download=/filename=(.*)$/.exec(request.getResponseHeader('Content-Disposition'))[1];
                link.click();
            },
            error: function(retorno){ 
                swal({
                    title: "Oopss!",
                    text: retorno.responseText,
                    icon: "error"
                });
            }
        });
}





function ComportamentoOld(){
    var dz = null;

    let campoBairro         = $('#BAIRRO')[0];
    let campoCep            = $('#CEP')[0];
    let campoChave          = $('#CHAVE')[0];
    let campoComplemento    = $('#COMPLEMENTO')[0];
    let campoCpfCnpj        = $('#CPFCNPJ')[0];
    let campoDescricao      = $('#DESCRICAO')[0];
    let campoEstado         = $('#ESTADO')[0];
    let campoEmail          = $('#EMAIL')[0];
    let campoEnderecoNumero = $('#ENDERECONUMERO')[0];
    let campoLogradouro     = $('#LOGRADOURO')[0];
    let campoMarca          = $('#MARCA')[0];
    let campoModelo         = $('#MODELO')[0];
    let campoMunicipio      = $('#MUNICIPIO')[0];
    let campoNome           = $('#NOME')[0];
    let campoNroSerie       = $('#NROSERIE')[0];
    let campoNroNfe         = $('#NRONFE')[0];
    let campoTelefone       = $('#TELEFONE')[0];
    let campoTipo           = $('#TIPO')[0];

    $('form').on('submit', function () {

         if (dz.getQueuedFiles().length === 0) {
            var blob = new Blob();
            blob.upload = {
                'chunked': dz.defaultOptions.chunking
            };
            
            dz.uploadFile(blob);
        } else {
            dz.processQueue();
        }
        return false;
    });

    var maskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    options = {
        onKeyPress: function (val, e, field, options) {
            field.mask(maskBehavior.apply({}, arguments), options);
        }
    };
    
    var maskBehaviorCep = function (val) {
        return '00000-000';
    },
    options = {
        onKeyPress: function (val, e, field, options) {
            field.mask(maskBehaviorCep.apply({}, arguments), options);
        }
    };

    var maskCpfCnpjBehaviorRemetente = function () {
        let campoCpf = $('#REMETENTE')[0];
        let val = campoCpf.value;
        
        return val.replace(/\D/g, '').length == 11 ? '000.000.000-00' : '00.000.000/0000-00';
    },
	
	maskCpfCnpjBehaviorDestinatario = function () {
        let campoCpf = $('#DESTINATARIO')[0];
        let val = campoCpf.value;
        
        return val.replace(/\D/g, '').length == 11 ? '000.000.000-00' : '00.000.000/0000-00';
    }
    
    optionsRemetente = {
            OnChange: function (val, e, field, options) {
                field.mask(maskCpfCnpjBehaviorRemetente.apply({}, arguments), options);
            }
        };
		
	optionsDestinatario = {
            OnChange: function (val, e, field, options) {
                field.mask(maskCpfCnpjBehaviorRemetente.apply({}, arguments), options);
            }
        };

    function aplicarMascaraCpfRemetente(){
            $('input[id="REMETENTE"]').mask(maskCpfCnpjBehaviorRemetente, optionsRemetente);
    }
	
	function aplicarMascaraCpfDestinatario(){
            $('input[id="DESTINATARIO"]').mask(maskCpfCnpjBehaviorDestinatario, optionsDestinatario);
    }
       
	$('input[id="REMETENTE"]').mask(maskCpfCnpjBehaviorRemetente, optionsRemetente);
	$('input[id="REMETENTE"]').on('blur', aplicarMascaraCpfRemetente);
	
	$('input[id="DESTINATARIO"]').mask(maskCpfCnpjBehaviorRemetente, optionsDestinatario);
	$('input[id="DESTINATARIO"]').on('blur', aplicarMascaraCpfDestinatario);
        
}