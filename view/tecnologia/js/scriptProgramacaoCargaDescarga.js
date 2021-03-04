var handleOcorrencia = 0;
var handleOcorrenciaAnexo = 0;
var mensagem = '';

$(document).ready(function () {
    $('#reqtablenew td').dblclick(function () {
        $('#reqtablenew td').removeClass("activetr");

        $(this).addClass("activetr");

        $('#OcorrenciaProgramacaoCargaDescarga').removeClass('display');
        $('#verObsProgramacaoCargaDescarga').removeClass('display');
    });

    $('#reqtablenewMobile td').dblclick(function () {
        $('#reqtablenewMobile td').removeClass("activetr");

        $(this).addClass("activetr");

        programacaoCheck = parseInt($(this).val());

        $('#OcorrenciaProgramacaoCargaDescargaMobile').removeClass('display');
        $('#VerObsProgramacaoCargaDescargaMobile').removeClass('display');
    });
});

function manterOcorrencia() {
    mostrarCarregando()
    
    jQuery.ajax({
        type: "POST",
        url: "../../model/operacional/GravarOcorrenciaCargaDescarga.php",
        dataType: 'json',
        data: {
            metodo: 'ManterOcorrencia',
            handleCarregamento: getHandleCarregamento(),
            tipoOcorrenciaHandle: $('#tipoOcorrenciaHandle').val(),
            progDocaHandle: $('#progDocaHandle').val(),
            docaHandle: $('#docaHandle').val(),
            veiculo: $('#veiculo').val(),
            acoplado: $('#acoplado').val(),
            conteinerHandle: $('#conteinerHandle').val(),
            motorista: $('#motorista').val(),
            observacao: $('#observacao').val(),
            ufVeiculoHandle: $('#ufVeiculoHandle').val(),
            ufAcopladoHandle: $('#ufAcopladoHandle').val(),
            propriedadeVeiculoHandle: $('#propriedadeVeiculoHandle').val(),
            documentoMotorista: $('#documentoMotorista').val(),
            tipoVeiculoHandle: $('#tipoVeiculoHandle').val(),
        },
        success: function (data) {
            handleOcorrencia = data['CHAVE'];
            if (data['MENSAGEM']){
                mensagem = 'Ocorrência cadastrada: ' + handleOcorrencia + '<br>' + ' Mensagem de bloqueio: ' + data['MENSAGEM'];
            } else {
                mensagem = 'Ocorrência cadastrada: ' + handleOcorrencia;
            }
            
            inserirAnexo();
        },
        error: function (data) {
            msg = 'Erro ' + data.responseJSON.code + ': ' + data.responseJSON.message
            fecharCarregando();
            mostrarErro(msg);
        }
    });
}

function gravarConteiner() {
    mostrarCarregando()
    
    jQuery.ajax({
        type: "POST",
        url: "../../model/operacional/GravarConteinerCargaDescarga.php",
        dataType: 'json',
        data: {
            codigoConteiner: $('#codigoConteiner').val(),
            tipoEquipamento: $('#tipoEquipamento').val(),
            tipoEquipamentoHandle: $('#tipoEquipamentoHandle').val(),
            codigoISO: $('#codigoISO').val(),
            codigoISOHandle: $('#codigoISOHandle').val(),
            alturaConteiner: $('#alturaConteiner').val(),
            larguraConteiner: $('#larguraConteiner').val(),
            comprimentoConteiner: $('#comprimentoConteiner').val(),
            capacidadeConteiner: $('#capacidadeConteiner').val(),
            taraConteiner: $('#taraConteiner').val(),
            mgwConteiner: $('#mgwConteiner').val(),
            fabricacaoConteiner: $('#fabricacaoConteiner').val(),
            obsInserirConteiner: $('#obsInserirConteiner').val()
        },
        success: function (data) {
            handleConteiner = data;
            data = 'Conteiner cadastrado: ' + handleConteiner;
            mostrarErro(data);
            fecharCarregando();
            $('#inserirConteinerModal').modal('hide');
                        
        },
        error: function (data) {
            msg = 'Erro ' + data.responseJSON.code + ': ' + data.responseJSON.message
            fecharCarregando();
            mostrarErro(msg);
        }
    });
}
function inserirAnexo() {
    if ($('#anexo').val() !== '' && handleOcorrencia != 0) {
        var form_data = new FormData();
        form_data.append('file', $('#anexo').prop('files')[0]);
        form_data.append('handleOcorrencia', handleOcorrencia);

        jQuery.ajax({
            type: "POST",
            url: "../../model/operacional/GravarAnexoCargaDescarga.php",
            dataType: 'json',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {              
                mostrarErro(mensagem);
                fecharCarregando();
                $('#modalOcorrencia').modal('hide');
            },
            error: function (data) {
                msg = 'Erro ' + data.responseJSON.code + ': ' + data.responseJSON.message
                fecharCarregando();
                mostrarErro(msg);
            }
        });
    } else {
        mostrarErro(mensagem);
        fecharCarregando();
        $('#modalOcorrencia').modal('hide');
    } 
}

function getHandleCarregamento() {
    return $('table').find('.activetr').closest('tr').find(".handle").text();
}

function atualizarPermissaoOcorrencia(acao) {
    if (acao === '2' || (acao === '5')) {
        $('#motorista').removeAttr('disabled');
        $('#motorista').removeAttr('disabled');
        $('#veiculo').removeAttr('disabled');
        $('#acoplado').removeAttr('disabled');
        $('#progDoca').removeAttr('disabled');
        $('#ufVeiculo').removeAttr('disabled');
        $('#ufAcoplado').removeAttr('disabled')
        $('#tipoVeiculo').removeAttr('disabled');
        $('#propriedadeVeiculo').removeAttr('disabled');
        $('#documentoMotorista').removeAttr('disabled');

    } else {
        $('#motorista').attr('disabled', 'true');
        $('#motorista').attr('disabled', 'true');
        $('#veiculo').attr('disabled', 'true');
        $('#acoplado').attr('disabled', 'true');
        $('#progDoca').attr('disabled', 'true');
        $('#ufVeiculo').attr('disabled', 'true');
        $('#ufAcoplado').attr('disabled', 'true');
        $('#tipoVeiculo').attr('disabled', 'true');
        $('#propriedadeVeiculo').attr('disabled', 'true');
        $('#documentoMotorista').attr('disabled', 'true');
    }
}

function verObsProgramacaoCargaDescargaFun() {
    jQuery.ajax({
        type: "POST",
        url: "../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaObs.php",
        dataType: 'json',
        data: {handle: getHandleCarregamento()},
        success: function (data) {
            $('#modalObservacao').modal('show');
            $('#modalObservacaoConteudo').html(data.retorno);
        }
    });
}

function OcorrenciaProgramacaoCargaDescargaFun() {
    mostrarCarregando();

    handleOcorrencia = 0;
    handleOcorrenciaAnexo = 0;

    var carregamento = getHandleCarregamento();

    $('#carregamento').val(carregamento);

    $("#anexo").val('');
    $('#tipoOcorrencia').val('');
    $('#tipoOcorrenciaHandle').val('');
    $('#acaoHandle').val('');
    $('#progDoca').val('');
    $('#progDocaHandle').val('');
    $('#docaHandle').val('');
    $('#tipoVeiculo').val('');
    $('#tipoVeiculoHandle').val('');
    $('#veiculo').val('');
    $('#ufVeiculo').val('');
    $('#ufVeiculoHandle').val('');
    $('#ufAcoplado').val('');
    $('#ufAcopladoHandle').val('');
    $('#propriedadeVeiculo').val('');
    $('#propriedadeVeiculoHandle').val('');
    $('#acoplado').val('');
    $('#conteiner').val('');
    $('#conteinerHandle').val('');
    $('#motorista').val('');
    $('#documentoMotorista').val('');
    $('#obs').val('');

    jQuery.ajax({
        type: "POST",
        url: "../../controller/operacional/AjaxProgramacaoCargaDescarga.php",
        dataType: 'json',
        data: {
            metodo: 'CarregarOcorrencia',
            handleCarregamento: carregamento
        },
        success: function (data) {
            fecharCarregando();

            if (data.ERRO !== '') {
                mostrarErro(data.ERRO);
            } else {
                $('#previsao').val(data.PREVISAOENTREGA);
                $('#numero').val(data.NUMERO);

                handleOcorrencia = parseInt(data.HANDLE);

                $('#tipoOcorrencia').val(data.TIPO);
                $('#tipoOcorrenciaHandle').val(data.TIPOHANDLE);
                $('#acaoHandle').val(data.ACAOHANDLE);
                $('#progDoca').val(data.PROGRAMACAODOCA);
                $('#progDocaHandle').val(data.PROGRAMACAODOCAHANDLE);
                $('#docaHandle').val(data.DOCAHANDLE);
                $('#tipoVeiculo').val(data.TIPOVEICULO);
                $('#tipoVeiculoHandle').val(data.TIPOVEICULOHANDLE);
                $('#veiculo').val(data.VEICULO);
                $('#ufVeiculo').val(data.VEICULOUF);
                $('#ufVeiculoHandle').val(data.VEICULOUFHANDLE);
                $('#ufAcoplado').val(data.ACOPLADOUF);
                $('#ufAcopladoHandle').val(data.ACOPLADOUFHANDLE);
                $('#propriedadeVeiculo').val(data.PROPRIEDADEVEICULO);
                $('#propriedadeVeiculoHandle').val(data.PROPRIEDADEVEICULOHANDLE);
                $('#acoplado').val(data.REBOQUE);
                $('#conteiner').val(data.CONTEINER);
                $('#conteinerHandle').val(data.CONTEINERHANDLE);
                $('#motorista').val(data.MOTORISTA);
                $('#documentoMotorista').val(data.MOTORISTADOCUMENTO);
                $('#obs').val(data.OBSERVACAO);

                atualizarPermissaoOcorrencia(data.ACAOHANDLE);

                if (handleOcorrencia !== 0) {
                    mostraModalOcorrencia();
                } else {
                    carregaTipoUnico();
                }
            }
        }
    });
}

function carregaTipoUnico() {
    jQuery.ajax({
        type: "POST",
        url: "../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaTipo.php",
        dataType: 'json',
        data: {handle: getHandleCarregamento()},
        success: function (data) {
            if (data.length === 1) {
                var retorno = data[0];

                $("#tipoOcorrencia").val(retorno.value);
                $("#tipoOcorrenciaHandle").val(retorno.id);
                $("#acaoHandle").val(retorno.acao);

                atualizarPermissaoOcorrencia(retorno.acao);
            } else {
                atualizarPermissaoOcorrencia(0);
            }

            mostraModalOcorrencia();
        }
    });
}

function mostraModalOcorrencia() {
    $('#modalOcorrencia').modal('show');
}

//***Define Multiselect
function multiselection() {

}

//aguarde...
// invoke blockUI as needed -->
$(document).on('click', '#sim', function () {
    $('.modal').modal('hide');
    $('#loader').removeAttr('style');
});

/**
 * Vertically center Bootstrap 3 modals so they aren't always stuck at the top
 */
$(function () {
    function reposition() {
        var modal = $(this),
            dialog = modal.find('.modal-dialog');
        modal.css('display', 'block');

        // Dividing by two centers the modal exactly, but dividing by three 
        // or four works better for larger screens.
        dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
    }

    // Reposition when a modal is shown
    $('.modal').on('show.bs.modal', reposition);
    // Reposition when the window is resized
    $(window).on('resize', function () {
        $('.modal:visible').each(reposition);
    });
});

function limpaform() {
    $('input').val('');

    $('option').removeAttr('selected');
    $('button#btnMultiselect').text("   ").attr({title: "Limpou"});
}

function clickdownTipoVeiculo() {
    $('#tipoVeiculo').autocomplete('option', 'minLength', 0);
    $('#tipoVeiculo').autocomplete('search', $('#tipoVeiculo').val());
}

function clickdownTransportadora() {
    $('#transportadora').autocomplete('option', 'minLength', 0);
    $('#transportadora').autocomplete('search', $('#transportadora').val());
}

function tipoOnPesquisar() {
    jQuery.ajax({
        type: "POST",
        url: "../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaTipo.php",
        dataType: 'json',
        data: {handle: getHandleCarregamento()},
        success: function (data) {
            $('#tipoOcorrencia').autocomplete({
                select: function (event, ui) {
                    $("#tipoOcorrenciaHandle").val(ui.item.id);
                    $("#acaoHandle").val(ui.item.acao);

                    atualizarPermissaoOcorrencia(ui.item.acao);
                },
                minLength: 0,
                source: data
            });
        }
    });
}

function programacaoDocaOnPesquisar() {
    $.getJSON('../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaDoca.php?search=', {ajax: 'true'}, function (data) {
        $('#progDoca').autocomplete({
            select: function (event, ui) {

                $("#progDoca").val(ui.item.val);
                $("#progDocaHandle").val(ui.item.id);
                $("#docaHandle").val(ui.item.DOCAHANDLE);
            },
            minLength: 0,
            source: data
        });
    });
}

function clickdownTipoOcorrencia() {
    $('#tipoOcorrencia').autocomplete('option', 'minLength', 0);
    $('#tipoOcorrencia').autocomplete('search', $('#tipoOcorrencia').val());
}

function clickdownProgDoca() {
    $('#progDoca').autocomplete('option', 'minLength', 0);
    $('#progDoca').autocomplete('search', $('#progDoca').val());
}

function clickdownUfVeiculo() {
    $('#ufVeiculo').autocomplete('option', 'minLength', 0);
    $('#ufVeiculo').autocomplete('search', $('#ufVeiculo').val());
}

function clickdownUfAcoplado() {
    $('#ufAcoplado').autocomplete('option', 'minLength', 0);
    $('#ufAcoplado').autocomplete('search', $('#ufAcoplado').val());
}
function clickdownConteiner() {
    $('#conteiner').autocomplete('option', 'minLength', 0);
    $('#conteiner').autocomplete('search', $('#conteiner').val());
}

function clickdownTipoEquipamento() {
    $('#tipoEquipamento').autocomplete('option', 'minLength', 0);
    $('#tipoEquipamento').autocomplete('search', $('#tipoEquipamento').val());
}

function clickdownCodigoISO() {
    $('#codigoISO').autocomplete('option', 'minLength', 0);
    $('#codigoISO').autocomplete('search', $('#codigoISO').val());
}

function clickdownPropriedadeVeiculo() {
    $('#propriedadeVeiculo').autocomplete('option', 'minLength', 0);
    $('#propriedadeVeiculo').autocomplete('search', $('#propriedadeVeiculo').val());
}

// Recupera Tipo
$(document).ready(function () {

    $.getJSON('../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaPropriedadeVeiculo.php?search=', {ajax: 'true'}, function (data) {

        $('#propriedadeVeiculo').autocomplete(
            {
                select: function (event, ui) {
                    $("#propriedadeVeiculo").val(ui.item.val);
                    $("#propriedadeVeiculoHandle").val(ui.item.id);
                },
                minLength: 0,
                source: data
            }
        );
    });

    $.getJSON('../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaTipoVeiculo.php?search=', {ajax: 'true'}, function (data) {

        $('#tipoVeiculo').autocomplete(
            {
                select: function (event, ui) {
                    $("#tipoVeiculoHandle").val(ui.item.id);
                },
                minLength: 0,
                source: data

            });
    });

    $.getJSON('../../controller/operacional/retornaTransportadoraFiltro.php?search=', {ajax: 'true'}, function (data) {

        $('#transportadora').autocomplete(
            {
                select: function (event, ui) {
                    $("#transportadoraHandle").val(ui.item.id);
                },
                minLength: 0,
                source: data
            });
    });

    $.getJSON('../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaUfVeiculo.php?search=', {
        carregamento: $('#carregamento').val(),
        ajax: 'true'
    }, function (data) {

        $('#ufVeiculo').autocomplete(
            {
                select: function (event, ui) {
                    $("#ufVeiculoHandle").val(ui.item.id);
                },
                minLength: 0,
                source: data

            });
    });

    $.getJSON('../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaUfAcoplado.php?search=', {
        carregamento: $('#carregamento').val(),
        ajax: 'true'
    }, function (data) {

        $('#ufAcoplado').autocomplete(
            {
                select: function (event, ui) {
                    $("#ufAcopladoHandle").val(ui.item.id);
                },
                minLength: 0,
                source: data

            });
    });

    $.getJSON('../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaConteiner.php?search=', {
        carregamento: $('#carregamento').val(),
        ajax: 'true'
    }, function (data) {

        $('#conteiner').autocomplete(
            {
                select: function (event, ui) {
                    $("#conteinerHandle").val(ui.item.id);
                },
                minLength: 0,
                source: data
            });
    });

    $.getJSON('../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaTipoEquipamento.php?search=', {
        carregamento: $('#carregamento').val(),
        ajax: 'true'
    }, function (data) {

        $('#tipoEquipamento').autocomplete(
            {
                select: function (event, ui) {
                    $("#tipoEquipamentoHandle").val(ui.item.id);
                },
                minLength: 0,
                source: data
            });
    });

    $.getJSON('../../controller/operacional/BaixarProgramacaoCargaDescargaRecuperaCodigoISO.php?search=', {
        carregamento: $('#carregamento').val(),
        ajax: 'true'
    }, function (data) {

        $('#codigoISO').autocomplete(
            {
                select: function (event, ui) {
                    $("#codigoISOHandle").val(ui.item.id);
                },
                minLength: 0,
                source: data
            });
    });

    $('#inserirConteiner').click(function () {
        $('#inserirConteinerModal').modal('show');
    });

    jQuery('#formModalInserirConteiner').submit(function () {
        $('#loader').removeAttr('style');

        
        return false;
    });
});
