$(function () {
    let campoCep       = $('#CEP');
    let campoCpfCnpj   = $('#CPFCNPJ');
    let campoEstado    = $('#ESTADO');
    let campoMarca     = $('#MARCA');
    let campoMunicipio = $('#MUNICIPIO');
    let campoTipo      = $('#TIPO');

    PreencherFkTipo(campoTipo);
    PreencherFkMarca(campoMarca);
    PreencherFkEstado(campoEstado);

    campoCpfCnpj.on('blur', CpfCnpjOnAlterar)
    campoCep.on('blur', CepOnAlterar);
    campoEstado.on('change', EstadoOnAlterar);
    campoMunicipio.on('change', MunicipioOnAlterar);
    campoMarca.on('change', PreencherFkModelo);
    campoTipo.on('change', TipoOnAlterar);

    ComportamentoOld();
});

function CepOnAlterar(){
    let cep = $('#CEP')[0].value;
    cep = cep.replace('-', '');

    popularEnderecoFromCep(cep);
}

function CpfCnpjOnAlterar(sender){
    let cpfcnpj = sender.target.value;
    cpfcnpj = cpfcnpj.replace('-', '').replace('.', '').replace('.', '').replace('/', '');

    let campoEmail = $('#EMAIL')[0];
    let campoTelefone = $('#TELEFONE')[0];
    let campoNome = $('#NOME')[0];
    let campoCep = $('#CEP')[0];
    let campoEnderecoNumero = $('#ENDERECONUMERO')[0];
    let campoComplemento = $('#COMPLEMENTO')[0];

    if ((cpfcnpj.length == 14) || (cpfcnpj.length == 11)){
        $.ajax({
            url: "../../controller/relacionamento/Ordem.php",
            method: "POST",
            data: {
                "ACAO"     : "getDadosFromCnpjCpf",
                "CPFCNPJ"  : cpfcnpj
            },
            success: function (retorno) {    
                let retornoTratado = JSON.parse(retorno.trim());        
                
                if ((typeof(retornoTratado.APELIDO) != 'undefined') && (retornoTratado.APELIDO != '')){
                    campoNome.value = retornoTratado.APELIDO;
                }

                if ((typeof(retornoTratado.EMAIL) != 'undefined') && (retornoTratado.EMAIL != '')){
                    campoEmail.value = retornoTratado.EMAIL;
                }
                
                if ((typeof(retornoTratado.TELEFONE) != 'undefined') && (retornoTratado.TELEFONE != '')){
                    campoTelefone.value = retornoTratado.TELEFONE;
                }

                if ((typeof(retornoTratado.CEP) != 'undefined') && (retornoTratado.CEP != '')){
                    campoCep.value = retornoTratado.CEP.substring(0, 5) + '-' + retornoTratado.CEP.substring(5, 8);
                    
                    popularEnderecoFromCep(retornoTratado.CEP);

                    campoEnderecoNumero.value = retornoTratado.NUMERO;
                    campoComplemento.value = retornoTratado.COMPLEMENTO;
                }
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
}

function popularEnderecoFromCep(cep){
    let campoLogradouro = $('#LOGRADOURO')[0];
    let campoEstado     = $('#ESTADO')[0];
    let campoMunicipio  = $('#MUNICIPIO')[0];
    let campoBairro     = $('#BAIRRO')[0];

    if (cep != ''){
        //-- NÃO CRIEI UM CONTROLLER PRA PESSOA/ENDERECO PQ ACHO Q NÃO VALIA O TEMPO GASTO
        $.ajax({
            url: "../../controller/relacionamento/Ordem.php",
            method: "POST",
            data: {
                "ACAO": "getDadosFromCepSemMascara",
                "CEP" : cep
            },
            success: function (retorno) {     
                let retornoTratado = JSON.parse(retorno.trim());               
                if ((typeof(retornoTratado.LOGRADOURO) != 'undefined') && (retornoTratado.LOGRADOURO != ''))
                    campoLogradouro.value = retornoTratado.LOGRADOURO;
                    
                    let f2 = function(){
                        campoBairro.value = retornoTratado.BAIRRONOME
                      };

                    let f1 = function(){
                        if ((typeof(retornoTratado.MUNICIPIOHANDLE) != 'undefined') && (retornoTratado.MUNICIPIOHANDLE != 0)){
                            campoMunicipio.value = retornoTratado.MUNICIPIOHANDLE
        
                            PopularBairro(f2);
                        }
                        else{
                            PopularBairro(f2);
                        }
                    }                   

                    if ((typeof(retornoTratado.UFHANDLE) != 'undefined') && (retornoTratado.UFHANDLE != 0)){
                        campoEstado.value = retornoTratado.UFHANDLE
    
                        PopularMunicipio(retornoTratado.UFHANDLE, f1);
                    }
                    else{
                        PopularMunicipio(0, f1);
                    }
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
    else{
        campoLogradouro.value = '';
        campoEstado.value = '';
        campoMunicipio.value = '';
        campoBairro.value = '';
    }
}

function MunicipioOnAlterar(sender){
    let handleMunicipio = sender.target.value;
}

function PopularBairro(resolve){
    resolve();
}

function EstadoOnAlterar(sender){
    let handleEstado = sender.target.value;

    PopularMunicipio(handleEstado, function(){});
}

function PopularMunicipio(prEstado, resolve){
    let campoMunicipio = $('#MUNICIPIO')[0];
    campoMunicipio.value = '';

    let indice;

    $('select#MUNICIPIO').children('option:not(:selected)').remove();

    if (prEstado != 0) {
        $.ajax({
            url: "../../controller/relacionamento/Ordem.php",
            method: "POST",
            data: {
                "ACAO"  : "getMunicipios",
                "ESTADO": prEstado
            },
            success: function (retorno) { 
                let retornoTratado = JSON.parse(retorno.trim());
                for (indice in retornoTratado) {
    
                    opcao = document.createElement("option");
                    opcao.setAttribute("value", retornoTratado[indice].HANDLE);
                    opcao.append(retornoTratado[indice].NOME);
    
                    campoMunicipio.append(opcao);
                }

                resolve();
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
}

function PreencherFkEstado(campoEstado){
    $.ajax({
        url: "../../controller/relacionamento/Ordem.php",
        method: "POST",
        data: {
            "ACAO": "getEstados"
        },
        success: function (retorno) {            
            let retornoTratado = JSON.parse(retorno.trim());

            for (indice in retornoTratado){

                let opcao = document.createElement("option");
                opcao.setAttribute("value", retornoTratado[indice].HANDLE);
                opcao.append(retornoTratado[indice].SIGLA);

                campoEstado.append(opcao);
            }
        },
        error: function(retorno){            
		console.log('erro');          
		console.log(retorno);
            swal({
                title: "Oopss!",
                text: retorno.responseText,
                icon: "error"
            });
        }
    });
}

function TipoOnAlterar(sender){
    let valorTipo = sender.target.value;

    if (valorTipo != 0) {
        let nomeTipo = $('#TIPO option:selected')[0].text;
    
        let controlaEndereco = (nomeTipo == 'GARANTIA') || 
                               (nomeTipo == 'ASSISTÊNCIA TÉCNICA') || 
                               (nomeTipo == 'UPGRADE');

        let campoCpfCnpj = $('#CPFCNPJ')[0];
        let campoCep = $('#CEP')[0];
        let campoLogradouro = $('#LOGRADOURO')[0];
        let campoEnderecoNumero = $('#ENDERECONUMERO')[0];
        let campoComplemento = $('#COMPLEMENTO')[0];
        let campoEstado = $('#ESTADO')[0];
        let campoMunicipio = $('#MUNICIPIO')[0];
        let campoBairro = $('#BAIRRO')[0];

        let camposText = [];
        let camposSelect = [];

        camposText.push(campoCpfCnpj);
        camposText.push(campoCep);
        camposText.push(campoLogradouro);
        camposText.push(campoEnderecoNumero);
        camposText.push(campoComplemento);
        camposText.push(campoBairro);
        camposSelect.push(campoEstado);
        camposSelect.push(campoMunicipio);
        
        $.ajax({
            url: "../../controller/relacionamento/Ordem.php",
            method: "POST",
            data: {
                "ACAO": "getTipoClienteObrigatorio",
                "TIPO": valorTipo
            },
            success: function (retorno) { 
                let retornoTratado = JSON.parse(retorno.trim());
                let indice;
                
                if ((retornoTratado.EHOBRIGATORIOPESSOA == "S") || (controlaEndereco)){
                    
                    for(indice = 0; indice < camposText.length;indice++){

                        camposText[indice].value = '';
                        camposText[indice].setAttribute('required','');
                        camposText[indice].removeAttribute('readonly','');
                    }

                    for(indice = 0; indice < camposSelect.length;indice++){
                        camposSelect[indice].value = '';
                        camposSelect[indice].setAttribute('required','');
                        camposSelect[indice].removeAttribute('disabled','');
                    }
                }
                else{
                    
                    for(indice = 0; indice < camposText.length;indice++){
                        camposText[indice].value = '';
                        camposText[indice].setAttribute('readonly','');
                        camposText[indice].removeAttribute('required','');
                    }

                    for(indice = 0; indice < camposSelect.length;indice++){
                        camposSelect[indice].value = '';
                        camposSelect[indice].setAttribute('disabled','');
                        camposSelect[indice].removeAttribute('required','');
                    }
                }
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
    
}

function PreencherFkTipo(campoTipo){

    $.ajax({
        url: "../../controller/relacionamento/Ordem.php",
        method: "POST",
        data: {
            "ACAO": "getTipos"
        },
        success: function (retorno) {    
            let retornoTratado = JSON.parse(retorno.trim());

            for (indice in retornoTratado) {

                let opcao = document.createElement("option");
                opcao.setAttribute("value", retornoTratado[indice].HANDLE);
                opcao.append(retornoTratado[indice].NOME);

                campoTipo.append(opcao);
            }
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

function PreencherFkMarca(campoMarca){

    $.ajax({
        url: "../../controller/relacionamento/Ordem.php",
        method: "POST",
        data: {
            "ACAO": "getMarcas"
        },
        success: function (retorno) { 
            let retornoTratado = JSON.parse(retorno.trim());
            
            for (indice in retornoTratado) {

                let opcao = document.createElement("option");
                opcao.setAttribute("value", retornoTratado[indice].HANDLE);
                opcao.append(retornoTratado[indice].NOME);

                campoMarca.append(opcao);
            }
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

function PreencherFkModelo(){
    let campoMarca = $("#MARCA")[0];

    let campoModelo = $("#MODELO")[0];

    $("#MODELO").html('<option value="" selected >Selecione..</option>');
    
    $.ajax({
        url: "../../controller/relacionamento/Ordem.php",
        method: "POST",
        data: {
            "ACAO": "getModelos",
            "MARCA": campoMarca.value
        },
        success: function (retorno) { 
            let retornoTratado = JSON.parse(retorno.trim());
            for (indice in retornoTratado) {

                let opcao = document.createElement("option");
                opcao.setAttribute("value", retornoTratado[indice].HANDLE);
                opcao.append(retornoTratado[indice].NOME);

                if (retornoTratado.length == 1){
                    opcao.setAttribute('selected', '');
                }

                campoModelo.append(opcao);
            }
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

    $(".modal-dialog").draggable();

    $("div.dropzone").dropzone({
        autoProcessQueue: false,
        maxFilesize: 50, // MB
        timeout: 180000,
        uploadMultiple: true,
        url: '../../controller/relacionamento/Ordem.php',
        addRemoveLinks: true,
        init: function () {
            dz = this;
            this.on('sending', function (file, xhr, formData) {
                var data = $('form').serializeArray();

                $.each(data, function (key, el) {
                    formData.append(el.name, el.value);
                });
                
                let cpfcnpj = campoCpfCnpj.value;
                cpfcnpj = cpfcnpj.replace('-', '').replace('.', '').replace('.', '').replace('/', '');

                let cep = campoCep.value;
                cep = cep.replace('-','');
                
                formData.append("ACAO", "cadastrarRelacionamento");
                formData.append("CNPJCPFSEMMASCARA", cpfcnpj);
                formData.append("CEPSEMMASCARA", cep);
                formData.append("MARCANOME", $('#MARCA option:selected')[0].text);
                formData.append("MODELONOME", $('#MODELO option:selected')[0].text);
                formData.append("TIPONOME", $('#TIPO option:selected')[0].text);
                formData.append("UFSIGLA", $('#ESTADO option:selected')[0].text);
                formData.append("MUNICIPIONOME", $('#MUNICIPIO option:selected')[0].text);
                
                $('input, select, textarea').attr('disabled', "disabled");
                $('button').attr('disabled', "disabled");
                $('button').html('<i class="fas fa-sync fa-spin"></i>');
            });
        },
        success: function (file, response) {
            swal({
                title: "Sucesso!",
                text: "Seu ticket foi criado com sucesso.",
                icon: "success",
                timer: 3000,
                button: false
            }).then(function () {
                window.location.href = '/view/relacionamento/RelacionamentoClienteListarAgf.php?atendimento=' + response;
            });
        },
        error: function (file, retorno) {
            retorno = JSON.parse(retorno);
            $('input, select, textarea').removeAttr('disabled');
            $('button').removeAttr('disabled');
            $('button').html('Enviar');

            bugsnagClient.notify(new Error(retorno.message), {
                beforeSend: function (report) {
                    report.updateMetaData('Dados enviados', {
                        "DADOS": $('#formRelacionamento').serializeArray()
                    })
                }
            });

            swal({
                title: "Oopss!",
                text: "Não foi possível enviar o seu ticket: " + retorno.message,
                icon: "error"
            });

            $.each(dz.files, function (i, file) {
                file.status = Dropzone.QUEUED
            });

        }
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

    var maskCpfCnpjBehavior = function () {
        let campoCpf = $('#CPFCNPJ')[0];
        let val = campoCpf.value;
        
        return val.replace(/\D/g, '').length == 11 ? '000.000.000-00' : '00.000.000/0000-00';
        },
        options = {
            OnChange: function (val, e, field, options) {
                field.mask(maskCpfCnpjBehavior.apply({}, arguments), options);
            }
        };

        function aplicarMascaraCpf(){
            $('input[id="CPFCNPJ"]').mask(maskCpfCnpjBehavior, options);
        }

        $('input[name="TELEFONE"]').mask(maskBehavior, options);
        $('input[name="CEP"]').mask(maskBehaviorCep, options);
        $('input[id="CPFCNPJ"]').mask(maskCpfCnpjBehavior, options);
        $('input[id="CPFCNPJ"]').on('blur', aplicarMascaraCpf);
        
}