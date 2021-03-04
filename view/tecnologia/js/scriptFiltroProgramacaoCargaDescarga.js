//***Consulta ajax valores filtros modals
$(document).ready(function () {

    $.getJSON("../../controller/operacional/retornaTransportadoraFiltro.php", function (return_data) {
        
        var arrayValue = [];

        $('#transportadora > option:selected').each(function () {
            arrayValue.push($(this).val());
        });

        $('#transportadora').empty();
        
        $.each(return_data.data, function (key, value) {
            $("#transportadora").append("<option value='" + value.HANDLE + ";" + value.APELIDO + "'>" + value.APELIDO + "</option>");
        });

        arrayValue.forEach(function (value) {
            $('#transportadora option[value="' + value + '"]').attr('selected','selected');
        });
    });
});
