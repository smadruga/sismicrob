//Funções e recursos javascript e jQuery inicializadas com o carregamento da página.
$(document).ready(function() {

    //Máscaras
    $(".Numero").mask("9");
    $(".Data").mask("99/99/9999");
    $(".Hora").mask("99:99");
    $(".DataHora").mask("99/99/9999 99:99");
    $(".Cpf").mask("999.999.999-99");
    $(".Cnpj").mask("99.999.999/9999-99");
    $(".Cep").mask("99999-999");
    $(".TituloEleitor").mask("9999.9999.9999");
    $(".Telefone").mask("(99) 9999-9999");
    $(".Prontuario").mask("99.99.99");
    $(".CodigoExame").mask("***-*?*******");
    $(".Celular").mask("(99) 9999?9-9999");
    $(".Cns").mask("?999.9999.9999.9999");
    $(".CelularVariavel").on("blur", function () {
        var last = $(this).val().substr($(this).val().indexOf("-") + 1);

        if (last.length == 3) {
            var move = $(this).val().substr($(this).val().indexOf("-") - 1, 1);
            var lastfour = move + last;

            var first = $(this).val().substr(0, 9);

            $(this).val(first + '-' + lastfour);
        }
    });

    //inicializa o select2 apenas em inputs com class select2 indicado
    $('.select2').select2({
        theme: "bootstrap-5",
        language: "pt-BR",
    });

    $('#submit').one('submit', function() {
        $(this).find('input[type="submit"]').attr('disabled','disabled');
    });

    $(".click").click(function () {
        $(this).replaceWith('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
    });

    $('.btn-group input[type="radio"]').change(function(){
        // Remover a classe 'active' de todos os labels dentro do grupo de botões
        $(this).parent().find('label').removeClass('active');
        
        // Adicionar a classe 'active' apenas ao label do radio selecionado
        if ($(this).is(':checked')) {
            $(this).next('label').addClass('active');
        }
    });

    $('#idTabSismicrob_Indicacao').change(function(){
        var selectedOption = $(this).val();

        //console.log('>>> opção: '+selectedOption);
        
        // Verificar se a opção selecionada é "Profilaxia Cirúrgica"
        if (selectedOption == 1) {
            // Alterar os labels dos campos conforme necessário
            $('label[for="DoseAtaque"]').html('Dose de indução anestésica  <b class="text-danger">*</b>');
            $('label[for="Intervalo"]').html('Intervalo para repique intraoperatório <b class="text-danger">*</b>');
            $('label[for="DoseDiaria"]').text('Dose diária - repiques intraoperatório');
            // Adicionar o novo campo "Mantido"
            $('#AntibioticobMantido').css('display', 'block');;
        } else {
            // Restaurar os labels dos campos ao original
            $('label[for="DoseAtaque"]').html('Dose de ataque <b class="text-danger">*</b>');
            $('label[for="Intervalo"]').html('Intervalo <b class="text-danger">*</b>');
            $('label[for="DoseDiaria"]').text('Dose diária');
            // Ocultar o campo "Mantido"
            $('#AntibioticobMantido').css('display', 'none');;
        }
    });

});

$(document).on('select2:open', () => {
    let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
    $(this).one('mouseup keyup',()=>{
        setTimeout(()=>{
            allFound[allFound.length - 1].focus();
        },0);
    });
});
