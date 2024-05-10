//Captura o valor de timeout de sessão do arquivo .env através do input existente no uppernavbar.php
var timeout = $("input[name=timeout]").val();

//calcula o tempo de sessão de 2 horas (120 minutos)
var timesession = new Date();
//timesession.setHours( timesession.getHours() + 2 );
timesession.setSeconds( timesession.getSeconds() + parseInt(timeout) );

//fecha divs de flashdata (mensagens de erro/sucesso) após alguns segundos
$('#flashdata').delay(5000).fadeOut('slow');

//monta o time de sessão do usuário
$('#clock').countdown(timesession)
.on('update.countdown', function(event) {
    var format = '%H:%M:%S';
    $(this).html(event.strftime(format));
});
//.on('finish.countdown', function(event) {
//    $(this).html('This offer has expired!')
//    .parent().addClass('disabled');
//});

$(".clickable-row").click(function () {
    window.document.location = $(this).data("href");
});

/*
 * Função que calcula o Índice de Massa Corporal
 *
 * IMC = PESO / ALTURA^2
 *
 * @param {decimal} peso
 * @param {int} altura
 * @returns {decimal}
 */
function indiceMassaCorporal() {

    //busca os valores
    var peso = $("#Peso").val();
    var altura = $("#Altura").val();

    if(peso && altura) {
        peso = peso.replace(".","").replace(",",".");

        imc = (peso / (altura**2)) * 10000;
        imc = imc.toFixed(3);
        imc = imc.replace(".",",");

        $('#IndiceMassaCorporal').val(imc);
    }

}

/*
 * Função que calcula a Superfície Corporal
 *
 * SC (m2) = 0,007184 X ( Altura (cm)^0,725) X ( Peso (kg) )^0,425
 * Referência: https://arquivos.sbn.org.br/equacoes/eq6.htm
 *
 * @param {decimal} peso
 * @param {int} altura
 * @returns {decimal}
 */
function superficieCorporal() {

    //busca os valores
    var peso = $("#Peso").val();
    var altura = $("#Altura").val();

    if(peso && altura) {
        peso = peso.replace(".","").replace(",",".");

        sc = (0.007184 * (altura**0.725) * (peso**0.425));
        sc = sc.toFixed(3);
        sc = sc.replace(".",",");

        $('#SuperficieCorporal').val(sc);
    }

}

/*
 * Função que calcula o Clearance Creatinina
 *
 * ClCr (mL/min) = (140 – idade) × (peso em kg) × (0.85 se mulher) / (72 × Creatinina sérica)
 * Referência: https://www.mdsaude.com/nefrologia/calculadoras-clearance-creatinina/ ou
 *             https://calculadorasmedicas.com.br/calculadora/clearance-de-creatinina-equacao-cockcroft-gault
 *
 * @param {decimal} peso
 * @param {int} idade
 * @param {char} sexo
 * @param {decimal} creatinina
 * @returns {decimal}
 */
function clearanceCreatinina() {

    //busca os valores
    var peso        = $("#Peso").val();
    var idade       = $("#Idade").val();
    var sexo        = $("#Sexo").val();
    var creatinina  = $("#Creatinina").val();

    if (sexo == 'F')
        sexo = 0.85;
    else
        sexo = 1;

    if(peso && idade && sexo && creatinina) {
        peso        = peso.replace(".","").replace(",",".");
        creatinina  = creatinina.replace(".","").replace(",",".");

        clcr = ((140 - idade) * peso * sexo) / (72 * creatinina);
        clcr = clcr.toFixed(3);
        clcr = clcr.replace(".",",");
        //console.log('>vvv '+peso+'<>c '+idade+'<>d '+sexo+'<>o>> '+creatinina);
        $('#Clearance').val(clcr);
    }


}

/*
 * Função que calcula a Dose Carboplatina
 *
 * Dose Carboplatina = Valor AUC * (ClCr + 25)
 *
 * @param {decimal} peso
 * @param {int} altura
 * @returns {decimal}
 */
function doseCarboplatina() {

    //busca os valores
    var dose = $("#Dose").val();
    var clearance = $("#Clearance").val();

    if(dose && clearance) {
        dose        = dose.replace(".","").replace(",",".");
        clearance   = clearance.replace(".","").replace(",",".");

        dc = (dose * (clearance + 25));
        dc = dc.toFixed(3);
        dc = dc.replace(".",",");

        $('#DoseCarboplatina').val(dc);
    }

}

/*
 * Função que calcula o ajuste da dose da prescrição
 *
 *
 * @param {decimal} peso
 * @param {int} altura
 * @returns {decimal}
 */
function ajuste(campo) {

    //busca os valores
    var dose    = $("#Dose"+campo).val();
    var ajuste  = $("#Ajuste"+campo).val();
    var tipo    = $("#TipoAjuste"+campo).val();
    var formula = $("#Formula"+campo).val();
    var peso    = $("#Peso").val();
    var sc      = $("#SuperficieCorporal").val();
    var co      = $("#CalculoOriginal"+campo).val();
    var min     = $("#CalculoLimiteMinimo"+campo).val();
    var max     = $("#CalculoLimiteMaximo"+campo).val();
    var r       = null;
    var calculo = $("#CalculoFinalOriginal"+campo).val();

    //console.log(' >>000 formula: '+formula+' <> peso: '+peso+' <> '+sc+' <> '+co+' <> '+min+' <> '+max+' <<<<> ajuste > '+ajuste+' campo:> '+campo);
    //console.log(' >>000 dose: '+dose+' ajuste: '+ajuste+' tipo: '+tipo+' calculo: '+calculo);

    if(dose && ajuste && tipo) {

        dose    = dose.replace(".","").replace(",",".");
        calculo = calculo.replace(".","").replace(",",".");
        ajuste  = ajuste.replace(".","").replace(",",".");
        peso    = peso.replace(".","").replace(",",".");
        sc      = sc.replace(".","").replace(",",".");
        co      = co.replace(".","").replace(",",".");
        min     = min.replace(".","").replace(",",".");
        max     = max.replace(".","").replace(",",".");

        //console.log(' >>111 '+formula+' <> '+peso+' <> '+sc+' <> '+co+' <> '+min+' <> '+max+' <<<<> ');

        if(tipo == 'porcentagem') {

            if(formula == 2) {
                r = (calculo * (ajuste/100));
                r = parseFloat(calculo) + parseFloat(r);
                //r = parseFloat(peso) * parseFloat(r);
            }
            else if(formula == 3) {
                r = (calculo * (ajuste/100));
                r = parseFloat(calculo) + parseFloat(r);
                //r = parseFloat(sc) * parseFloat(r);
            }
            else {
                r = (calculo * (ajuste/100));
                r = parseFloat(calculo) + parseFloat(r);
            }

        }
        else {
            r = parseFloat(ajuste);
        }

        //console.log(' r: '+r+' max: '+max+' min: '+min+' ajuste: '+ajuste+' tipo: '+tipo+' formula: '+formula);
        if(min && (r < min)) {
            r = min;
        }            
        else if(max && (r > max)) {
            r = max;
        }            
        else {
            r = r;
            r = parseFloat(r);
        }
        //console.log(' r: '+r+' max: '+max+' min: '+min);
        
        r = Number(r).toFixed(2);
        r = r.replace(".",",");

        $('#Calculo'+campo).val(r);

    }
    else {
        $("#Calculo"+campo).val($("#CalculoFinalOriginal"+campo).val());
    }

}


/*
 * Função responsável exibir/ocultar determinadas div's
 *
 * @param {string} value
 * @returns {decimal}
 */
function showHideDiv(valor, campo, div, opcoes, autofocus, alternar, alternar0, alternar1, icon0, icon1, divOcultar) {

    //console.log('>v '+valor+'<>c '+campo+'<>d '+div+'<>o '+opcoes);

    if (opcoes == 0) {

        if(alternar){

            if(valor == 1) {
                $('#' + div).html(icon0 + ' ' + alternar0 + ' ' + icon0);
                $('#' + campo).val('0');
                $('.' + campo + div).removeAttr("style");
            }
            else {
                $('#' + div).html(icon1 + ' ' + alternar1 + ' ' + icon1);
                $('#' + campo).val('1');
                $('.' + campo + div).attr("style", "display:none;");
            }

        }
        else {
            if (valor == 0)
                $('.' + campo).attr("style", "display:none;");
            else
                $('.' + campo).removeAttr("style");
        }
        //console.log('>'+valor+'<>'+campo+'<>'+div+'<>'+opcoes+'<>'+autofocus+'<>'+alternar+'<>'+alternar0+'<>'+alternar1+'<>');

    }
    else {
        var o = opcoes.split("|");

        for (i=0; i < o.length; i++) {

            $('.' + div + o[i]).attr("style", "display:none;");
            if( document.getElementById('#' + campo + valor) ) {
                $('.' + campo + valor).removeAttr("style");

            }
            $('.idTabSismicrob_IndicacaoDiv').removeAttr("style");
            console.log('>vvv '+valor+'<>c '+campo+'<>d '+div+'<>o '+opcoes+'<>len '+o.length+'<>i '+i);

        }
        
        //if (divOcultar) {            $('#Ocultar_' + div + valor).hide();            console.log('>vvv '+valor+'<> testes ');        }
            

        //console.log('###>'+valor+'<>'+campo+'<>'+div+'<>'+opcoes+'<>'+autofocus+'<>'+alternar+'<>'+alternar0+'<>'+alternar1+'<>'+'#Ocultar_' + div + valor);
    }
    //console.log('>'+valor+'<>'+campo+'<>'+div+'<>'+opcoes+'<>'+autofocus+'<>'+alternar+'<>'+alternar0+'<>'+alternar1+'<>');
}

/*
 * Calcula uma data fim de um tratamento a partir de uma data inicial mas os dias de tratamento
 *
 * @param {string} value
 * @returns {decimal}
 */
function calculaTempoTratamento(c1, c2, cvalor) {

    //busca os valores
    var c1 = $("#"+c1).val();
    var c2 = $("#"+c2).val();

    valor = '';
    if (c1 && c2 && c1.match(/[1-9][0-9][0-9]{2}-([0][1-9]|[1][0-2])-([1-2][0-9]|[0][1-9]|[3][0-1])/gm)) {

        //define a data no formato do momentjs
        var currentDate = moment(c1);

        //calcula as datas das próximas parcelas
        var futureDate = moment(currentDate).add(c2, 'd');

        //retorna o valor final para o campo apropriado
        valor = futureDate.format('YYYY-MM-DD')

    }

    //console.log("OI>>> "+valor+" "+c1+" "+c2);
    //o valor é escrito no seu campo no formulário
    $('#'+cvalor).val(valor);

}

/*
 * Calcular o produto entre a Dose Posológica e o Intervalo
 *
 * @param {string} value
 * @returns {decimal}
 */
function calculaProduto(c1, c2, cvalor) {

    //busca os valores
    var c1 = $("#"+c1).val();
    var c2 = $("#"+c2).val();

    s = c2.split('#');

    if(s[2] < 6) {
        var valor = (c1.replace(".","").replace(",",".") * (24 / s[0]));

        valor = ($('input[name=UnidadeMedida]:checked').val()) ?
            mascaraValorReal(valor) + ' ' + $('input[name=UnidadeMedida]:checked').val() :
            mascaraValorReal(valor);
    }
    else {
        var valor = (c1.replace(".","").replace(",",".") * 1);

        valor = ($('input[name=UnidadeMedida]:checked').val()) ?
            mascaraValorReal(valor) + ' ' + $('input[name=UnidadeMedida]:checked').val() :
            mascaraValorReal(valor);
    }

    //console.log("OI3 >>> "+c1+" % > "+c2+" < & "+valor+" unid >>> "+$('input[name=UnidadeMedida]:checked').val()+' $$$ ');

    //console.log("OI>>> "+valor);
    if(c1, c2)
        $('#'+cvalor).val(valor);

}
/*
 * Aplica a máscara de valor real com separação de decimais e milhares.
 *
 * @param {float} value
 * @returns {decimal}
 */
function mascaraValorReal(value) {

    var r;
    r = value.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    r = r.replace(/[,.]/g, function (m) {
        // m is the match found in the string
        // If `,` is matched return `.`, if `.` matched return `,`
        return m === ',' ? '.' : ',';
    });
    return r;

}

/*
 * Adiciona um btn-warning para cara checked de cada campo radio do formulário
 *
 * @param {string} value
 * @returns {decimal}
 */
// Adiciona um listener para o evento click em todos os botões/labels dentro de cada grupo
//$('.btn-group label').click(function() {
    // Remove a classe btn-warning de todos os labels dentro do grupo
    //$(this).closest('.btn-group').find('label').removeClass('btn-warning');
    // Adiciona a classe btn-secondary a todos os labels dentro do grupo
    //$(this).closest('.btn-group').find('label').addClass('btn-secondary');
    // Adiciona a classe btn-warning apenas ao label clicado
    //$(this).addClass('btn-warning');
    // Remove o atributo checked de todos os radio buttons dentro do grupo
    //$(this).closest('.btn-group').find('input[type="radio"]').prop('checked', false);
    // Marca como checked o radio button correspondente ao label clicado
    //$(this).prev('input[type="radio"]').prop('checked', true);
//});