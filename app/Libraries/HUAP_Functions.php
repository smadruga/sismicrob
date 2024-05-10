<?php

namespace App\Libraries;

use CodeIgniter\I18n\Time;

class HUAP_Functions
{
    private $v;


    public function __construct()
    {

    }

    /**
    * Função que calcula diferença entre datas no formato americano
    *
    **********************************************************************
    **PARA: Date Should In YYYY-MM-DD Format
    **RESULT FORMAT:
    ** '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
    ** '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
    ** '%m Month %d Day'                                            =>  3 Month 14 Day
    ** '%d Day %h Hours'                                            =>  14 Day 11 Hours
    ** '%d Day'                                                        =>  14 Days
    ** '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
    ** '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
    ** '%h Hours                                                    =>  11 Hours
    ** '%a Days                                                        =>  468 Days
    **********************************************************************
    *
    * @return int
    *
    */
    function dateDifference($date_1 , $date_2 , $differenceFormat = '%d' )
    {
        //Create a date object out of a string (e.g. from a database):
        $date1 = date_create_from_format('Y-m-d H:i:s', $date_1);

        //Create a date object out of today's date:
        $date2 = date_create_from_format('Y-m-d H:i:s', $date_2);

        //Create a comparison of the two dates and store it in an array:
        $diff = (array) date_diff($date1, $date2);

        //Output the array:
        #echo '<pre>'.print_r($diff,1).'</pre>';

        return $diff['days'];
    }

    /**
    * Função que calcula o Índice de Massa Corporal
    *
    * Fórmula: IMC = P/(A^2)
    *   onde:
    *   P = Peso (em quilogramas (Kg))
    *   A = Altura (em metros (m))
    *
    * @return double
    */
    function calc_IndiceMassaCorporal($peso, $altura) {

        return number_format((str_replace(',', '.',$peso)/(($altura/100)**2)), 3, ',', '');

    }

    /**
    * Função que calcula a Área da superfície corporal (método Du Bois)
    *
    * Fórmula S=0,007184*X^{0,425}*Y^{0,725}
    *   onde:
    *   S = Área da superfície do corpo (em metros quadrados (m²))
    *   X = Peso (em quilogramas (Kg))
    *   Y = Altura (em centímetros (cm))
    *
    * Referência: https://arquivos.sbn.org.br/equacoes/eq6.htm
    *
    * @return double
    */
    function calc_SuperficieCorporal($peso, $altura) {

        return number_format(( (0.007184) * (str_replace(',', '.',$peso)**(0.425)) * ($altura**(0.725)) ), 3, ',', '');

    }

    /**
    * Calcula o Clearance Creatinina
    *
    * ClCr (mL/min) = (140 – idade) × (peso em kg) × (0.85 se mulher) / (72 × Creatinina sérica)
    * Referência: https://www.mdsaude.com/nefrologia/calculadoras-clearance-creatinina/ ou
    *             https://calculadorasmedicas.com.br/calculadora/clearance-de-creatinina-equacao-cockcroft-gault
    *
    * @return double
    */
    function calc_ClearanceCreatinina($peso, $idade, $sexo, $creatinina) {

        $sexo = ($sexo == 'F') ? 0.85 : 1;

        return number_format((((140 - $idade) * $peso * $sexo) / (72 * $creatinina)), 3, ',', '');

    }

    /**
    * Função que calcula a Dose Carboplatina
    *
    * Dose Carboplatina = Valor AUC * (ClCr + 25)
    *
    * @return double
    */
    function calc_DoseCarboplatina($dose, $clearance) {

        return number_format(($dose * ($clearance + 25)), 3, ',', '');

    }

    /**
    * Função que retorna o sexo por extenso
    *
    * @return varchar
    */
    function get_sexo($data) {

        if ($data == 'M')
            return 'MASCULINO';
        elseif ($data == 'F')
            return 'FEMININO';
        else
            return 'OUTRO';

    }

    /**
    * Função que verifica se a variável é uma data válida no formato brasileiro
    *
    * @return bool
    */
    function check_date($data, $opt = 'regex') {

        if($opt == 'checkdate')
            return (strlen($data) == 8 && checkdate(substr($data, 2, 2), substr($data, 0, 2), substr($data, 4, 6))) ? TRUE : FALSE;
        else
            return (preg_match('/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/', $data)) ? TRUE : FALSE;

    }

    /**
    * Função que verifica se a variável é um cpf válido
    *
    * @return bool
    */
    function check_cpf($data, $opt = 'regex') {

        return (preg_match("/^([0-9]{3})\.?([0-9]{3})\.?([0-9]{3})\-?([0-9]{2})$/", $data)) ? TRUE : FALSE;

    }

    /**
    * Função que aplica máscara de CPF
    *
    * @return varchar
    */
    function mascara_cpf($data, $opt = 'aplicar') {

        if($data == 0)
            return '';

        #remove o separador do cpf
        if($opt == 'remover')
            return (strlen($data) == 11) ? $data : substr($data, 0, 3) . substr($data, 4, 3) . substr($data, 8, 3) . substr($data, 12, 2);
        #aplica o separador no cpf
        else {

            $zeros = 11 - strlen($data);
            for ($i = 0; $i < $zeros; $i++)
                $data = '0' . $data;

            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $data);
        }

    }

    /**
    * Função que retorna a data formatada conforme desejado (formato gregoriano, invertido/banco de dados, separado)
    *
    * @return varchar
    */
    function mascara_data($data, $opt, $hora = FALSE, $invertido = FALSE, $removehora = FALSE) {

        #verifica a data de acordo com a situação (se é datetime ou apenas date)
        $pm = ($hora) ? preg_match("/[0-9]{2,4}(\/|-)[0-9]{2,4}(\/|-)[0-9]{2,4}( )[0-9]{2,4}(:)[0-9]{2,4}/", $data) : preg_match("/[0-9]{2,4}(\/|-)[0-9]{2,4}(\/|-)[0-9]{2,4}/", $data);

        #retorna a data no formato de barras DD/MM/YYYY
        if ($opt == 'barras') {
            if ($pm && $data) {
                #exit('oioioi'.$data);
                #$data = ($hora) ? Time::createFromFormat('j-M-Y H:i', '15-Feb-2009 15:02', 'America/Chicago') : Time::createFromFormat('Y-m-d', $data);
                $data = ($hora) ? Time::createFromFormat('Y-m-d H:i:s', $data) : Time::createFromFormat('Y-m-d', $data);
                $data = ($hora) ? $data->format('d/m/Y H:i') : $data->format('d/m/Y');
            }
            else
                $data = ($hora) ? '0000-00-00 00:00:00' : '0000-00-00';

            if ($removehora) {
                $d = explode(" ", $data);
                $data = $d[0];
            }

        }
            #$data = ($pm && $data && $data != '0000-00-00') ? ($hora) ? nice_date($data, 'd/m/Y H:i') : nice_date($data, 'd/m/Y') : '';

        #retorna a data no formato invertido, no formato apropriado para banco de dados.
        elseif ($opt == 'db') {

            #caso a data já esteja invertida mas ainda não esteja no formato para bd
            if ($invertido) {
                if ($data > 18000000) {
                    $data = ($hora) ? Time::createFromFormat('YmdHi', $data) : Time::createFromFormat('Ymd', $data);
                    $data = ($hora) ? $data->format('Y-m-d H:i:s') : $data->format('Y-m-d');
                }
                else
                    $data = ($hora) ? '0000-00-00 00:00:00' : '0000-00-00';

            }
            #caso a data não esteja invertida
            else {
                if ($pm && $data) {
                    $data = ($hora) ? Time::createFromFormat('d/m/Y H:i', $data) : Time::createFromFormat('d/m/Y', $data);
                    $data = ($hora) ? $data->format('Y-m-d H:i') : $data->format('Y-m-d');
                }
                else
                    $data = ($hora) ? '0000-00-00 00:00:00' : '0000-00-00';

            }

        }
        #Se eu desejar apenas inverter a data
        elseif ($opt == 'inverter') {
            #Se houver data e hora
            if ($pm && $data) {
                $info = explode(" ", $data);
                //$data = DateTime::createFromFormat('d-m-Y', $info[0]);
                $data = Time::createFromFormat('d-m-Y', $info[0]);
                $data = $data->format('Y-m-d');

                if ($hora)
                    $data = $data . ' ' . $info[1];

            }
            #Se houver apenas data
            elseif (!$pm && $data)
                $data = substr($data, 4, 4).'-'.substr($data, 2, 2).'-'.substr($data, 0, 2);
            #Se não houver nada
            else
                $data = NULL;
        }

        return $data;
    }

    /**
    * Função que prepara o insert de dados de acesso para a tabela de auditoria
    *
    * @return array
    */
    function set_acesso($operacao) {

        $request = \Config\Services::request();
        $agent = $request->getUserAgent();

        #Necessário que o Usuário ID=1 do sistema esteja cadastrado como SISTEMA e inativado
        #Caso a sessão expire então é setado o valor 1 (sistema) para registro do logout
        $id = (isset($_SESSION['Sessao']['idSishuap_Usuario'])) ? $_SESSION['Sessao']['idSishuap_Usuario'] : 1;

        return [
            'SessionId'         => session_id(),
            'Operacao'          => $operacao,
            'idSishuap_Usuario' => $id,
            'Ip'                => $request->getIPAddress(),
            'So'                => $agent->getPlatform(),
            'Navegador'         => $agent->getBrowser(),
            'NavegadorVersao'   => $agent->getVersion(),
        ];

    }

    /**
    * Função que prepara o insert de log para a tabela de auditoria
    *
    * @return array
    */
    function create_auditoria($tabela, $operacao, $id) {

        $request = \Config\Services::request();
        $agent = $request->getUserAgent();

        return [
            'Tabela'            => $tabela,
            'idSishuap_Usuario' => $_SESSION['Sessao']['idSishuap_Usuario'],

            'Operacao'          => $operacao,
            'ChavePrimaria'     => $id,
            'Ip'                => $request->getIPAddress(),
            'So'                => $agent->getPlatform(),
            'Navegador'         => $agent->getBrowser(),
            'NavegadorVersao'   => $agent->getVersion(),
        ];

    }

    /**
    * Função que prepara o insert de log para a tabela de auditoria
    *
    * @return array
    */
    public function create_log($anterior, $atual, $campos, $iddata, $idauditoria, $update = NULL, $delete = NULL) {

        $query = array();

        $i = 0;
        #compara valores antigos com os novos e vê onde há mudanças
        if ($update === TRUE) {
            foreach ($campos as $novo) {
                #echo $novo.' <> '.$atual[$novo].' <> '.$anterior[$novo].'<br>';
                #if (isset($atual[$novo]) && isset($anterior[$novo]) && $atual[$novo] != $anterior[$novo]) {
                if (isset($atual[$novo]) && !isset($anterior[$novo])) {
                    $query[] = array(
                        'idSishuap_Auditoria' => $idauditoria,
                        'Campo' => $novo,
                        'ValorAnterior' => NULL,
                        'ValorAtual' => $atual[$novo],
                        'ChavePrimaria' => $iddata,
                    );
                }
                elseif (isset($atual[$novo]) && $atual[$novo] != $anterior[$novo]) {
                    $query[] = array(
                        'idSishuap_Auditoria' => $idauditoria,
                        'Campo' => $novo,
                        'ValorAnterior' => $anterior[$novo],
                        'ValorAtual' => $atual[$novo],
                        'ChavePrimaria' => $iddata,
                    );
                }
            }
        }
        #apenas monta o select para inserção de novos dados, sem fazer comparações
        else {
            if ($delete === TRUE) {
                foreach ($campos as $novo) {
                    if ($anterior[$novo]) {
                        $query[] = array(
                            'idSishuap_Auditoria' => $idauditoria,
                            'Campo' => $novo,
                            'ValorAnterior' => $anterior[$novo],
                            'ChavePrimaria' => $iddata,
                        );
                    }
                }
            }
            else {
                foreach ($campos as $novo) {
                    if ($atual[$novo]) {
                        $query[] = array(
                            'idSishuap_Auditoria' => $idauditoria,
                            'Campo' => $novo,
                            'ValorAtual' => $atual[$novo],
                            'ChavePrimaria' => $iddata,
                        );
                    }
                }
            }
        }

        #$query['PK'] = $id;

        /*
          #echo $id;
          echo "<pre>";
          print_r($query);
          echo "</pre>";
          exit();
        #*/

        return ($query) ? $query : FALSE;

    }

    /**
     * Reset the mbstring internal encoding to a users previously set encoding.
     *
     *
     * @author https://developer.wordpress.org/reference/functions/reset_mbstring_encoding/
     *
     */
     function reset_mbstring_encoding() {
         $this->mbstring_binary_safe_encoding( true );
     }

    /**
     * Set the mbstring internal encoding to a binary safe encoding when func_overload is enabled.
     *
     * NOTE: When mbstring.func_overload is in use for multi-byte encodings, the results from strlen()
     * and similar functions respect the utf8 characters, causing binary data to return incorrect lengths.
     *
     * This function overrides the mbstring encoding to a binary-safe encoding, and resets it to the users expected
     * encoding afterwards through the reset_mbstring_encoding function.
     *
     * It is safe to recursively call this function, however each mbstring_binary_safe_encoding() call must be followed
     * up with an equal number of reset_mbstring_encoding() calls.
     *
     * @author https://developer.wordpress.org/reference/functions/mbstring_binary_safe_encoding/
     *
     * @param string $reset (bool) (Optional) Whether to reset the encoding back to a previously-set encoding. Default value: false
     */
    function mbstring_binary_safe_encoding( $reset = false ) {
        static $encodings  = array();
        static $overloaded = null;

        if ( is_null( $overloaded ) ) {
            if ( function_exists( 'mb_internal_encoding' )
                && ( (int) ini_get( 'mbstring.func_overload' ) & 2 ) // phpcs:ignore PHPCompatibility.IniDirectives.RemovedIniDirectives.mbstring_func_overloadDeprecated
            ) {
                $overloaded = true;
            } else {
                $overloaded = false;
            }
        }

        if ( false === $overloaded ) {
            return;
        }

        if ( ! $reset ) {
            $encoding = mb_internal_encoding();
            array_push( $encodings, $encoding );
            mb_internal_encoding( 'ISO-8859-1' );
        }

        if ( $reset && $encodings ) {
            $encoding = array_pop( $encodings );
            mb_internal_encoding( $encoding );
        }
    }

    /**
     * Checks to see if a string is utf8 encoded.
     *
     * NOTE: This function checks for 5-Byte sequences, UTF8
     *       has Bytes Sequences with a maximum length of 4.
     *
     * @author bmorel at ssi dot fr (modified)
     * @since 1.2.1
     *
     * @param string $str The string to be checked
     * @return bool True if $str fits a UTF-8 model, false otherwise.
     */
    function seems_utf8( $str ) {
    	$this->mbstring_binary_safe_encoding();
    	$length = strlen($str);
    	$this->reset_mbstring_encoding();
    	for ($i=0; $i < $length; $i++) {
    		$c = ord($str[$i]);
    		if ($c < 0x80) $n = 0; // 0bbbbbbb
    		elseif (($c & 0xE0) == 0xC0) $n=1; // 110bbbbb
    		elseif (($c & 0xF0) == 0xE0) $n=2; // 1110bbbb
    		elseif (($c & 0xF8) == 0xF0) $n=3; // 11110bbb
    		elseif (($c & 0xFC) == 0xF8) $n=4; // 111110bb
    		elseif (($c & 0xFE) == 0xFC) $n=5; // 1111110b
    		else return false; // Does not match any model
    		for ($j=0; $j<$n; $j++) { // n bytes matching 10bbbbbb follow ?
    			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
    				return false;
    		}
    	}
    	return true;
    }

    /**
     * Converts all accent characters to ASCII characters.
     *
     * If there are no accent characters, then the string given is just returned.
     *
     * **Accent characters converted:**
     *
     * Currency signs:
     *
     * |   Code   | Glyph | Replacement |     Description     |
     * | -------- | ----- | ----------- | ------------------- |
     * | U+00A3   | £     | (empty)     | British Pound sign  |
     * | U+20AC   | €     | E           | Euro sign           |
     *
     * Decompositions for Latin-1 Supplement:
     *
     * |  Code   | Glyph | Replacement |               Description              |
     * | ------- | ----- | ----------- | -------------------------------------- |
     * | U+00AA  | ª     | a           | Feminine ordinal indicator             |
     * | U+00BA  | º     | o           | Masculine ordinal indicator            |
     * | U+00C0  | À     | A           | Latin capital letter A with grave      |
     * | U+00C1  | Á     | A           | Latin capital letter A with acute      |
     * | U+00C2  | Â     | A           | Latin capital letter A with circumflex |
     * | U+00C3  | Ã     | A           | Latin capital letter A with tilde      |
     * | U+00C4  | Ä     | A           | Latin capital letter A with diaeresis  |
     * | U+00C5  | Å     | A           | Latin capital letter A with ring above |
     * | U+00C6  | Æ     | AE          | Latin capital letter AE                |
     * | U+00C7  | Ç     | C           | Latin capital letter C with cedilla    |
     * | U+00C8  | È     | E           | Latin capital letter E with grave      |
     * | U+00C9  | É     | E           | Latin capital letter E with acute      |
     * | U+00CA  | Ê     | E           | Latin capital letter E with circumflex |
     * | U+00CB  | Ë     | E           | Latin capital letter E with diaeresis  |
     * | U+00CC  | Ì     | I           | Latin capital letter I with grave      |
     * | U+00CD  | Í     | I           | Latin capital letter I with acute      |
     * | U+00CE  | Î     | I           | Latin capital letter I with circumflex |
     * | U+00CF  | Ï     | I           | Latin capital letter I with diaeresis  |
     * | U+00D0  | Ð     | D           | Latin capital letter Eth               |
     * | U+00D1  | Ñ     | N           | Latin capital letter N with tilde      |
     * | U+00D2  | Ò     | O           | Latin capital letter O with grave      |
     * | U+00D3  | Ó     | O           | Latin capital letter O with acute      |
     * | U+00D4  | Ô     | O           | Latin capital letter O with circumflex |
     * | U+00D5  | Õ     | O           | Latin capital letter O with tilde      |
     * | U+00D6  | Ö     | O           | Latin capital letter O with diaeresis  |
     * | U+00D8  | Ø     | O           | Latin capital letter O with stroke     |
     * | U+00D9  | Ù     | U           | Latin capital letter U with grave      |
     * | U+00DA  | Ú     | U           | Latin capital letter U with acute      |
     * | U+00DB  | Û     | U           | Latin capital letter U with circumflex |
     * | U+00DC  | Ü     | U           | Latin capital letter U with diaeresis  |
     * | U+00DD  | Ý     | Y           | Latin capital letter Y with acute      |
     * | U+00DE  | Þ     | TH          | Latin capital letter Thorn             |
     * | U+00DF  | ß     | s           | Latin small letter sharp s             |
     * | U+00E0  | à     | a           | Latin small letter a with grave        |
     * | U+00E1  | á     | a           | Latin small letter a with acute        |
     * | U+00E2  | â     | a           | Latin small letter a with circumflex   |
     * | U+00E3  | ã     | a           | Latin small letter a with tilde        |
     * | U+00E4  | ä     | a           | Latin small letter a with diaeresis    |
     * | U+00E5  | å     | a           | Latin small letter a with ring above   |
     * | U+00E6  | æ     | ae          | Latin small letter ae                  |
     * | U+00E7  | ç     | c           | Latin small letter c with cedilla      |
     * | U+00E8  | è     | e           | Latin small letter e with grave        |
     * | U+00E9  | é     | e           | Latin small letter e with acute        |
     * | U+00EA  | ê     | e           | Latin small letter e with circumflex   |
     * | U+00EB  | ë     | e           | Latin small letter e with diaeresis    |
     * | U+00EC  | ì     | i           | Latin small letter i with grave        |
     * | U+00ED  | í     | i           | Latin small letter i with acute        |
     * | U+00EE  | î     | i           | Latin small letter i with circumflex   |
     * | U+00EF  | ï     | i           | Latin small letter i with diaeresis    |
     * | U+00F0  | ð     | d           | Latin small letter Eth                 |
     * | U+00F1  | ñ     | n           | Latin small letter n with tilde        |
     * | U+00F2  | ò     | o           | Latin small letter o with grave        |
     * | U+00F3  | ó     | o           | Latin small letter o with acute        |
     * | U+00F4  | ô     | o           | Latin small letter o with circumflex   |
     * | U+00F5  | õ     | o           | Latin small letter o with tilde        |
     * | U+00F6  | ö     | o           | Latin small letter o with diaeresis    |
     * | U+00F8  | ø     | o           | Latin small letter o with stroke       |
     * | U+00F9  | ù     | u           | Latin small letter u with grave        |
     * | U+00FA  | ú     | u           | Latin small letter u with acute        |
     * | U+00FB  | û     | u           | Latin small letter u with circumflex   |
     * | U+00FC  | ü     | u           | Latin small letter u with diaeresis    |
     * | U+00FD  | ý     | y           | Latin small letter y with acute        |
     * | U+00FE  | þ     | th          | Latin small letter Thorn               |
     * | U+00FF  | ÿ     | y           | Latin small letter y with diaeresis    |
     *
     * Decompositions for Latin Extended-A:
     *
     * |  Code   | Glyph | Replacement |                    Description                    |
     * | ------- | ----- | ----------- | ------------------------------------------------- |
     * | U+0100  | Ā     | A           | Latin capital letter A with macron                |
     * | U+0101  | ā     | a           | Latin small letter a with macron                  |
     * | U+0102  | Ă     | A           | Latin capital letter A with breve                 |
     * | U+0103  | ă     | a           | Latin small letter a with breve                   |
     * | U+0104  | Ą     | A           | Latin capital letter A with ogonek                |
     * | U+0105  | ą     | a           | Latin small letter a with ogonek                  |
     * | U+01006 | Ć     | C           | Latin capital letter C with acute                 |
     * | U+0107  | ć     | c           | Latin small letter c with acute                   |
     * | U+0108  | Ĉ     | C           | Latin capital letter C with circumflex            |
     * | U+0109  | ĉ     | c           | Latin small letter c with circumflex              |
     * | U+010A  | Ċ     | C           | Latin capital letter C with dot above             |
     * | U+010B  | ċ     | c           | Latin small letter c with dot above               |
     * | U+010C  | Č     | C           | Latin capital letter C with caron                 |
     * | U+010D  | č     | c           | Latin small letter c with caron                   |
     * | U+010E  | Ď     | D           | Latin capital letter D with caron                 |
     * | U+010F  | ď     | d           | Latin small letter d with caron                   |
     * | U+0110  | Đ     | D           | Latin capital letter D with stroke                |
     * | U+0111  | đ     | d           | Latin small letter d with stroke                  |
     * | U+0112  | Ē     | E           | Latin capital letter E with macron                |
     * | U+0113  | ē     | e           | Latin small letter e with macron                  |
     * | U+0114  | Ĕ     | E           | Latin capital letter E with breve                 |
     * | U+0115  | ĕ     | e           | Latin small letter e with breve                   |
     * | U+0116  | Ė     | E           | Latin capital letter E with dot above             |
     * | U+0117  | ė     | e           | Latin small letter e with dot above               |
     * | U+0118  | Ę     | E           | Latin capital letter E with ogonek                |
     * | U+0119  | ę     | e           | Latin small letter e with ogonek                  |
     * | U+011A  | Ě     | E           | Latin capital letter E with caron                 |
     * | U+011B  | ě     | e           | Latin small letter e with caron                   |
     * | U+011C  | Ĝ     | G           | Latin capital letter G with circumflex            |
     * | U+011D  | ĝ     | g           | Latin small letter g with circumflex              |
     * | U+011E  | Ğ     | G           | Latin capital letter G with breve                 |
     * | U+011F  | ğ     | g           | Latin small letter g with breve                   |
     * | U+0120  | Ġ     | G           | Latin capital letter G with dot above             |
     * | U+0121  | ġ     | g           | Latin small letter g with dot above               |
     * | U+0122  | Ģ     | G           | Latin capital letter G with cedilla               |
     * | U+0123  | ģ     | g           | Latin small letter g with cedilla                 |
     * | U+0124  | Ĥ     | H           | Latin capital letter H with circumflex            |
     * | U+0125  | ĥ     | h           | Latin small letter h with circumflex              |
     * | U+0126  | Ħ     | H           | Latin capital letter H with stroke                |
     * | U+0127  | ħ     | h           | Latin small letter h with stroke                  |
     * | U+0128  | Ĩ     | I           | Latin capital letter I with tilde                 |
     * | U+0129  | ĩ     | i           | Latin small letter i with tilde                   |
     * | U+012A  | Ī     | I           | Latin capital letter I with macron                |
     * | U+012B  | ī     | i           | Latin small letter i with macron                  |
     * | U+012C  | Ĭ     | I           | Latin capital letter I with breve                 |
     * | U+012D  | ĭ     | i           | Latin small letter i with breve                   |
     * | U+012E  | Į     | I           | Latin capital letter I with ogonek                |
     * | U+012F  | į     | i           | Latin small letter i with ogonek                  |
     * | U+0130  | İ     | I           | Latin capital letter I with dot above             |
     * | U+0131  | ı     | i           | Latin small letter dotless i                      |
     * | U+0132  | Ĳ     | IJ          | Latin capital ligature IJ                         |
     * | U+0133  | ĳ     | ij          | Latin small ligature ij                           |
     * | U+0134  | Ĵ     | J           | Latin capital letter J with circumflex            |
     * | U+0135  | ĵ     | j           | Latin small letter j with circumflex              |
     * | U+0136  | Ķ     | K           | Latin capital letter K with cedilla               |
     * | U+0137  | ķ     | k           | Latin small letter k with cedilla                 |
     * | U+0138  | ĸ     | k           | Latin small letter Kra                            |
     * | U+0139  | Ĺ     | L           | Latin capital letter L with acute                 |
     * | U+013A  | ĺ     | l           | Latin small letter l with acute                   |
     * | U+013B  | Ļ     | L           | Latin capital letter L with cedilla               |
     * | U+013C  | ļ     | l           | Latin small letter l with cedilla                 |
     * | U+013D  | Ľ     | L           | Latin capital letter L with caron                 |
     * | U+013E  | ľ     | l           | Latin small letter l with caron                   |
     * | U+013F  | Ŀ     | L           | Latin capital letter L with middle dot            |
     * | U+0140  | ŀ     | l           | Latin small letter l with middle dot              |
     * | U+0141  | Ł     | L           | Latin capital letter L with stroke                |
     * | U+0142  | ł     | l           | Latin small letter l with stroke                  |
     * | U+0143  | Ń     | N           | Latin capital letter N with acute                 |
     * | U+0144  | ń     | n           | Latin small letter N with acute                   |
     * | U+0145  | Ņ     | N           | Latin capital letter N with cedilla               |
     * | U+0146  | ņ     | n           | Latin small letter n with cedilla                 |
     * | U+0147  | Ň     | N           | Latin capital letter N with caron                 |
     * | U+0148  | ň     | n           | Latin small letter n with caron                   |
     * | U+0149  | ŉ     | n           | Latin small letter n preceded by apostrophe       |
     * | U+014A  | Ŋ     | N           | Latin capital letter Eng                          |
     * | U+014B  | ŋ     | n           | Latin small letter Eng                            |
     * | U+014C  | Ō     | O           | Latin capital letter O with macron                |
     * | U+014D  | ō     | o           | Latin small letter o with macron                  |
     * | U+014E  | Ŏ     | O           | Latin capital letter O with breve                 |
     * | U+014F  | ŏ     | o           | Latin small letter o with breve                   |
     * | U+0150  | Ő     | O           | Latin capital letter O with double acute          |
     * | U+0151  | ő     | o           | Latin small letter o with double acute            |
     * | U+0152  | Œ     | OE          | Latin capital ligature OE                         |
     * | U+0153  | œ     | oe          | Latin small ligature oe                           |
     * | U+0154  | Ŕ     | R           | Latin capital letter R with acute                 |
     * | U+0155  | ŕ     | r           | Latin small letter r with acute                   |
     * | U+0156  | Ŗ     | R           | Latin capital letter R with cedilla               |
     * | U+0157  | ŗ     | r           | Latin small letter r with cedilla                 |
     * | U+0158  | Ř     | R           | Latin capital letter R with caron                 |
     * | U+0159  | ř     | r           | Latin small letter r with caron                   |
     * | U+015A  | Ś     | S           | Latin capital letter S with acute                 |
     * | U+015B  | ś     | s           | Latin small letter s with acute                   |
     * | U+015C  | Ŝ     | S           | Latin capital letter S with circumflex            |
     * | U+015D  | ŝ     | s           | Latin small letter s with circumflex              |
     * | U+015E  | Ş     | S           | Latin capital letter S with cedilla               |
     * | U+015F  | ş     | s           | Latin small letter s with cedilla                 |
     * | U+0160  | Š     | S           | Latin capital letter S with caron                 |
     * | U+0161  | š     | s           | Latin small letter s with caron                   |
     * | U+0162  | Ţ     | T           | Latin capital letter T with cedilla               |
     * | U+0163  | ţ     | t           | Latin small letter t with cedilla                 |
     * | U+0164  | Ť     | T           | Latin capital letter T with caron                 |
     * | U+0165  | ť     | t           | Latin small letter t with caron                   |
     * | U+0166  | Ŧ     | T           | Latin capital letter T with stroke                |
     * | U+0167  | ŧ     | t           | Latin small letter t with stroke                  |
     * | U+0168  | Ũ     | U           | Latin capital letter U with tilde                 |
     * | U+0169  | ũ     | u           | Latin small letter u with tilde                   |
     * | U+016A  | Ū     | U           | Latin capital letter U with macron                |
     * | U+016B  | ū     | u           | Latin small letter u with macron                  |
     * | U+016C  | Ŭ     | U           | Latin capital letter U with breve                 |
     * | U+016D  | ŭ     | u           | Latin small letter u with breve                   |
     * | U+016E  | Ů     | U           | Latin capital letter U with ring above            |
     * | U+016F  | ů     | u           | Latin small letter u with ring above              |
     * | U+0170  | Ű     | U           | Latin capital letter U with double acute          |
     * | U+0171  | ű     | u           | Latin small letter u with double acute            |
     * | U+0172  | Ų     | U           | Latin capital letter U with ogonek                |
     * | U+0173  | ų     | u           | Latin small letter u with ogonek                  |
     * | U+0174  | Ŵ     | W           | Latin capital letter W with circumflex            |
     * | U+0175  | ŵ     | w           | Latin small letter w with circumflex              |
     * | U+0176  | Ŷ     | Y           | Latin capital letter Y with circumflex            |
     * | U+0177  | ŷ     | y           | Latin small letter y with circumflex              |
     * | U+0178  | Ÿ     | Y           | Latin capital letter Y with diaeresis             |
     * | U+0179  | Ź     | Z           | Latin capital letter Z with acute                 |
     * | U+017A  | ź     | z           | Latin small letter z with acute                   |
     * | U+017B  | Ż     | Z           | Latin capital letter Z with dot above             |
     * | U+017C  | ż     | z           | Latin small letter z with dot above               |
     * | U+017D  | Ž     | Z           | Latin capital letter Z with caron                 |
     * | U+017E  | ž     | z           | Latin small letter z with caron                   |
     * | U+017F  | ſ     | s           | Latin small letter long s                         |
     * | U+01A0  | Ơ     | O           | Latin capital letter O with horn                  |
     * | U+01A1  | ơ     | o           | Latin small letter o with horn                    |
     * | U+01AF  | Ư     | U           | Latin capital letter U with horn                  |
     * | U+01B0  | ư     | u           | Latin small letter u with horn                    |
     * | U+01CD  | Ǎ     | A           | Latin capital letter A with caron                 |
     * | U+01CE  | ǎ     | a           | Latin small letter a with caron                   |
     * | U+01CF  | Ǐ     | I           | Latin capital letter I with caron                 |
     * | U+01D0  | ǐ     | i           | Latin small letter i with caron                   |
     * | U+01D1  | Ǒ     | O           | Latin capital letter O with caron                 |
     * | U+01D2  | ǒ     | o           | Latin small letter o with caron                   |
     * | U+01D3  | Ǔ     | U           | Latin capital letter U with caron                 |
     * | U+01D4  | ǔ     | u           | Latin small letter u with caron                   |
     * | U+01D5  | Ǖ     | U           | Latin capital letter U with diaeresis and macron  |
     * | U+01D6  | ǖ     | u           | Latin small letter u with diaeresis and macron    |
     * | U+01D7  | Ǘ     | U           | Latin capital letter U with diaeresis and acute   |
     * | U+01D8  | ǘ     | u           | Latin small letter u with diaeresis and acute     |
     * | U+01D9  | Ǚ     | U           | Latin capital letter U with diaeresis and caron   |
     * | U+01DA  | ǚ     | u           | Latin small letter u with diaeresis and caron     |
     * | U+01DB  | Ǜ     | U           | Latin capital letter U with diaeresis and grave   |
     * | U+01DC  | ǜ     | u           | Latin small letter u with diaeresis and grave     |
     *
     * Decompositions for Latin Extended-B:
     *
     * |   Code   | Glyph | Replacement |                Description                |
     * | -------- | ----- | ----------- | ----------------------------------------- |
     * | U+0218   | Ș     | S           | Latin capital letter S with comma below   |
     * | U+0219   | ș     | s           | Latin small letter s with comma below     |
     * | U+021A   | Ț     | T           | Latin capital letter T with comma below   |
     * | U+021B   | ț     | t           | Latin small letter t with comma below     |
     *
     * Vowels with diacritic (Chinese, Hanyu Pinyin):
     *
     * |   Code   | Glyph | Replacement |                      Description                      |
     * | -------- | ----- | ----------- | ----------------------------------------------------- |
     * | U+0251   | ɑ     | a           | Latin small letter alpha                              |
     * | U+1EA0   | Ạ     | A           | Latin capital letter A with dot below                 |
     * | U+1EA1   | ạ     | a           | Latin small letter a with dot below                   |
     * | U+1EA2   | Ả     | A           | Latin capital letter A with hook above                |
     * | U+1EA3   | ả     | a           | Latin small letter a with hook above                  |
     * | U+1EA4   | Ấ     | A           | Latin capital letter A with circumflex and acute      |
     * | U+1EA5   | ấ     | a           | Latin small letter a with circumflex and acute        |
     * | U+1EA6   | Ầ     | A           | Latin capital letter A with circumflex and grave      |
     * | U+1EA7   | ầ     | a           | Latin small letter a with circumflex and grave        |
     * | U+1EA8   | Ẩ     | A           | Latin capital letter A with circumflex and hook above |
     * | U+1EA9   | ẩ     | a           | Latin small letter a with circumflex and hook above   |
     * | U+1EAA   | Ẫ     | A           | Latin capital letter A with circumflex and tilde      |
     * | U+1EAB   | ẫ     | a           | Latin small letter a with circumflex and tilde        |
     * | U+1EA6   | Ậ     | A           | Latin capital letter A with circumflex and dot below  |
     * | U+1EAD   | ậ     | a           | Latin small letter a with circumflex and dot below    |
     * | U+1EAE   | Ắ     | A           | Latin capital letter A with breve and acute           |
     * | U+1EAF   | ắ     | a           | Latin small letter a with breve and acute             |
     * | U+1EB0   | Ằ     | A           | Latin capital letter A with breve and grave           |
     * | U+1EB1   | ằ     | a           | Latin small letter a with breve and grave             |
     * | U+1EB2   | Ẳ     | A           | Latin capital letter A with breve and hook above      |
     * | U+1EB3   | ẳ     | a           | Latin small letter a with breve and hook above        |
     * | U+1EB4   | Ẵ     | A           | Latin capital letter A with breve and tilde           |
     * | U+1EB5   | ẵ     | a           | Latin small letter a with breve and tilde             |
     * | U+1EB6   | Ặ     | A           | Latin capital letter A with breve and dot below       |
     * | U+1EB7   | ặ     | a           | Latin small letter a with breve and dot below         |
     * | U+1EB8   | Ẹ     | E           | Latin capital letter E with dot below                 |
     * | U+1EB9   | ẹ     | e           | Latin small letter e with dot below                   |
     * | U+1EBA   | Ẻ     | E           | Latin capital letter E with hook above                |
     * | U+1EBB   | ẻ     | e           | Latin small letter e with hook above                  |
     * | U+1EBC   | Ẽ     | E           | Latin capital letter E with tilde                     |
     * | U+1EBD   | ẽ     | e           | Latin small letter e with tilde                       |
     * | U+1EBE   | Ế     | E           | Latin capital letter E with circumflex and acute      |
     * | U+1EBF   | ế     | e           | Latin small letter e with circumflex and acute        |
     * | U+1EC0   | Ề     | E           | Latin capital letter E with circumflex and grave      |
     * | U+1EC1   | ề     | e           | Latin small letter e with circumflex and grave        |
     * | U+1EC2   | Ể     | E           | Latin capital letter E with circumflex and hook above |
     * | U+1EC3   | ể     | e           | Latin small letter e with circumflex and hook above   |
     * | U+1EC4   | Ễ     | E           | Latin capital letter E with circumflex and tilde      |
     * | U+1EC5   | ễ     | e           | Latin small letter e with circumflex and tilde        |
     * | U+1EC6   | Ệ     | E           | Latin capital letter E with circumflex and dot below  |
     * | U+1EC7   | ệ     | e           | Latin small letter e with circumflex and dot below    |
     * | U+1EC8   | Ỉ     | I           | Latin capital letter I with hook above                |
     * | U+1EC9   | ỉ     | i           | Latin small letter i with hook above                  |
     * | U+1ECA   | Ị     | I           | Latin capital letter I with dot below                 |
     * | U+1ECB   | ị     | i           | Latin small letter i with dot below                   |
     * | U+1ECC   | Ọ     | O           | Latin capital letter O with dot below                 |
     * | U+1ECD   | ọ     | o           | Latin small letter o with dot below                   |
     * | U+1ECE   | Ỏ     | O           | Latin capital letter O with hook above                |
     * | U+1ECF   | ỏ     | o           | Latin small letter o with hook above                  |
     * | U+1ED0   | Ố     | O           | Latin capital letter O with circumflex and acute      |
     * | U+1ED1   | ố     | o           | Latin small letter o with circumflex and acute        |
     * | U+1ED2   | Ồ     | O           | Latin capital letter O with circumflex and grave      |
     * | U+1ED3   | ồ     | o           | Latin small letter o with circumflex and grave        |
     * | U+1ED4   | Ổ     | O           | Latin capital letter O with circumflex and hook above |
     * | U+1ED5   | ổ     | o           | Latin small letter o with circumflex and hook above   |
     * | U+1ED6   | Ỗ     | O           | Latin capital letter O with circumflex and tilde      |
     * | U+1ED7   | ỗ     | o           | Latin small letter o with circumflex and tilde        |
     * | U+1ED8   | Ộ     | O           | Latin capital letter O with circumflex and dot below  |
     * | U+1ED9   | ộ     | o           | Latin small letter o with circumflex and dot below    |
     * | U+1EDA   | Ớ     | O           | Latin capital letter O with horn and acute            |
     * | U+1EDB   | ớ     | o           | Latin small letter o with horn and acute              |
     * | U+1EDC   | Ờ     | O           | Latin capital letter O with horn and grave            |
     * | U+1EDD   | ờ     | o           | Latin small letter o with horn and grave              |
     * | U+1EDE   | Ở     | O           | Latin capital letter O with horn and hook above       |
     * | U+1EDF   | ở     | o           | Latin small letter o with horn and hook above         |
     * | U+1EE0   | Ỡ     | O           | Latin capital letter O with horn and tilde            |
     * | U+1EE1   | ỡ     | o           | Latin small letter o with horn and tilde              |
     * | U+1EE2   | Ợ     | O           | Latin capital letter O with horn and dot below        |
     * | U+1EE3   | ợ     | o           | Latin small letter o with horn and dot below          |
     * | U+1EE4   | Ụ     | U           | Latin capital letter U with dot below                 |
     * | U+1EE5   | ụ     | u           | Latin small letter u with dot below                   |
     * | U+1EE6   | Ủ     | U           | Latin capital letter U with hook above                |
     * | U+1EE7   | ủ     | u           | Latin small letter u with hook above                  |
     * | U+1EE8   | Ứ     | U           | Latin capital letter U with horn and acute            |
     * | U+1EE9   | ứ     | u           | Latin small letter u with horn and acute              |
     * | U+1EEA   | Ừ     | U           | Latin capital letter U with horn and grave            |
     * | U+1EEB   | ừ     | u           | Latin small letter u with horn and grave              |
     * | U+1EEC   | Ử     | U           | Latin capital letter U with horn and hook above       |
     * | U+1EED   | ử     | u           | Latin small letter u with horn and hook above         |
     * | U+1EEE   | Ữ     | U           | Latin capital letter U with horn and tilde            |
     * | U+1EEF   | ữ     | u           | Latin small letter u with horn and tilde              |
     * | U+1EF0   | Ự     | U           | Latin capital letter U with horn and dot below        |
     * | U+1EF1   | ự     | u           | Latin small letter u with horn and dot below          |
     * | U+1EF2   | Ỳ     | Y           | Latin capital letter Y with grave                     |
     * | U+1EF3   | ỳ     | y           | Latin small letter y with grave                       |
     * | U+1EF4   | Ỵ     | Y           | Latin capital letter Y with dot below                 |
     * | U+1EF5   | ỵ     | y           | Latin small letter y with dot below                   |
     * | U+1EF6   | Ỷ     | Y           | Latin capital letter Y with hook above                |
     * | U+1EF7   | ỷ     | y           | Latin small letter y with hook above                  |
     * | U+1EF8   | Ỹ     | Y           | Latin capital letter Y with tilde                     |
     * | U+1EF9   | ỹ     | y           | Latin small letter y with tilde                       |
     *
     * German (`de_DE`), German formal (`de_DE_formal`), German (Switzerland) formal (`de_CH`),
     * and German (Switzerland) informal (`de_CH_informal`) locales:
     *
     * |   Code   | Glyph | Replacement |               Description               |
     * | -------- | ----- | ----------- | --------------------------------------- |
     * | U+00C4   | Ä     | Ae          | Latin capital letter A with diaeresis   |
     * | U+00E4   | ä     | ae          | Latin small letter a with diaeresis     |
     * | U+00D6   | Ö     | Oe          | Latin capital letter O with diaeresis   |
     * | U+00F6   | ö     | oe          | Latin small letter o with diaeresis     |
     * | U+00DC   | Ü     | Ue          | Latin capital letter U with diaeresis   |
     * | U+00FC   | ü     | ue          | Latin small letter u with diaeresis     |
     * | U+00DF   | ß     | ss          | Latin small letter sharp s              |
     *
     * Danish (`da_DK`) locale:
     *
     * |   Code   | Glyph | Replacement |               Description               |
     * | -------- | ----- | ----------- | --------------------------------------- |
     * | U+00C6   | Æ     | Ae          | Latin capital letter AE                 |
     * | U+00E6   | æ     | ae          | Latin small letter ae                   |
     * | U+00D8   | Ø     | Oe          | Latin capital letter O with stroke      |
     * | U+00F8   | ø     | oe          | Latin small letter o with stroke        |
     * | U+00C5   | Å     | Aa          | Latin capital letter A with ring above  |
     * | U+00E5   | å     | aa          | Latin small letter a with ring above    |
     *
     * Catalan (`ca`) locale:
     *
     * |   Code   | Glyph | Replacement |               Description               |
     * | -------- | ----- | ----------- | --------------------------------------- |
     * | U+00B7   | l·l   | ll          | Flown dot (between two Ls)              |
     *
     * Serbian (`sr_RS`) and Bosnian (`bs_BA`) locales:
     *
     * |   Code   | Glyph | Replacement |               Description               |
     * | -------- | ----- | ----------- | --------------------------------------- |
     * | U+0110   | Đ     | DJ          | Latin capital letter D with stroke      |
     * | U+0111   | đ     | dj          | Latin small letter d with stroke        |
     *
     * @since 1.2.1
     * @since 4.6.0 Added locale support for `de_CH`, `de_CH_informal`, and `ca`.
     * @since 4.7.0 Added locale support for `sr_RS`.
     * @since 4.8.0 Added locale support for `bs_BA`.
     *
     * @param string $string Text that might have accent characters
     * @return string Filtered string with replaced "nice" characters.
     */
    function remove_accents( $string ) {
    	if ( !preg_match('/[\x80-\xff]/', $string) )
    		return $string;

    	if ($this->seems_utf8($string)) {
    		$chars = array(
    		// Decompositions for Latin-1 Supplement
    		'ª' => 'a', 'º' => 'o',
    		'À' => 'A', 'Á' => 'A',
    		'Â' => 'A', 'Ã' => 'A',
    		'Ä' => 'A', 'Å' => 'A',
    		'Æ' => 'AE','Ç' => 'C',
    		'È' => 'E', 'É' => 'E',
    		'Ê' => 'E', 'Ë' => 'E',
    		'Ì' => 'I', 'Í' => 'I',
    		'Î' => 'I', 'Ï' => 'I',
    		'Ð' => 'D', 'Ñ' => 'N',
    		'Ò' => 'O', 'Ó' => 'O',
    		'Ô' => 'O', 'Õ' => 'O',
    		'Ö' => 'O', 'Ù' => 'U',
    		'Ú' => 'U', 'Û' => 'U',
    		'Ü' => 'U', 'Ý' => 'Y',
    		'Þ' => 'TH','ß' => 's',
    		'à' => 'a', 'á' => 'a',
    		'â' => 'a', 'ã' => 'a',
    		'ä' => 'a', 'å' => 'a',
    		'æ' => 'ae','ç' => 'c',
    		'è' => 'e', 'é' => 'e',
    		'ê' => 'e', 'ë' => 'e',
    		'ì' => 'i', 'í' => 'i',
    		'î' => 'i', 'ï' => 'i',
    		'ð' => 'd', 'ñ' => 'n',
    		'ò' => 'o', 'ó' => 'o',
    		'ô' => 'o', 'õ' => 'o',
    		'ö' => 'o', 'ø' => 'o',
    		'ù' => 'u', 'ú' => 'u',
    		'û' => 'u', 'ü' => 'u',
    		'ý' => 'y', 'þ' => 'th',
    		'ÿ' => 'y', 'Ø' => 'O',
    		// Decompositions for Latin Extended-A
    		'Ā' => 'A', 'ā' => 'a',
    		'Ă' => 'A', 'ă' => 'a',
    		'Ą' => 'A', 'ą' => 'a',
    		'Ć' => 'C', 'ć' => 'c',
    		'Ĉ' => 'C', 'ĉ' => 'c',
    		'Ċ' => 'C', 'ċ' => 'c',
    		'Č' => 'C', 'č' => 'c',
    		'Ď' => 'D', 'ď' => 'd',
    		'Đ' => 'D', 'đ' => 'd',
    		'Ē' => 'E', 'ē' => 'e',
    		'Ĕ' => 'E', 'ĕ' => 'e',
    		'Ė' => 'E', 'ė' => 'e',
    		'Ę' => 'E', 'ę' => 'e',
    		'Ě' => 'E', 'ě' => 'e',
    		'Ĝ' => 'G', 'ĝ' => 'g',
    		'Ğ' => 'G', 'ğ' => 'g',
    		'Ġ' => 'G', 'ġ' => 'g',
    		'Ģ' => 'G', 'ģ' => 'g',
    		'Ĥ' => 'H', 'ĥ' => 'h',
    		'Ħ' => 'H', 'ħ' => 'h',
    		'Ĩ' => 'I', 'ĩ' => 'i',
    		'Ī' => 'I', 'ī' => 'i',
    		'Ĭ' => 'I', 'ĭ' => 'i',
    		'Į' => 'I', 'į' => 'i',
    		'İ' => 'I', 'ı' => 'i',
    		'Ĳ' => 'IJ','ĳ' => 'ij',
    		'Ĵ' => 'J', 'ĵ' => 'j',
    		'Ķ' => 'K', 'ķ' => 'k',
    		'ĸ' => 'k', 'Ĺ' => 'L',
    		'ĺ' => 'l', 'Ļ' => 'L',
    		'ļ' => 'l', 'Ľ' => 'L',
    		'ľ' => 'l', 'Ŀ' => 'L',
    		'ŀ' => 'l', 'Ł' => 'L',
    		'ł' => 'l', 'Ń' => 'N',
    		'ń' => 'n', 'Ņ' => 'N',
    		'ņ' => 'n', 'Ň' => 'N',
    		'ň' => 'n', 'ŉ' => 'n',
    		'Ŋ' => 'N', 'ŋ' => 'n',
    		'Ō' => 'O', 'ō' => 'o',
    		'Ŏ' => 'O', 'ŏ' => 'o',
    		'Ő' => 'O', 'ő' => 'o',
    		'Œ' => 'OE','œ' => 'oe',
    		'Ŕ' => 'R','ŕ' => 'r',
    		'Ŗ' => 'R','ŗ' => 'r',
    		'Ř' => 'R','ř' => 'r',
    		'Ś' => 'S','ś' => 's',
    		'Ŝ' => 'S','ŝ' => 's',
    		'Ş' => 'S','ş' => 's',
    		'Š' => 'S', 'š' => 's',
    		'Ţ' => 'T', 'ţ' => 't',
    		'Ť' => 'T', 'ť' => 't',
    		'Ŧ' => 'T', 'ŧ' => 't',
    		'Ũ' => 'U', 'ũ' => 'u',
    		'Ū' => 'U', 'ū' => 'u',
    		'Ŭ' => 'U', 'ŭ' => 'u',
    		'Ů' => 'U', 'ů' => 'u',
    		'Ű' => 'U', 'ű' => 'u',
    		'Ų' => 'U', 'ų' => 'u',
    		'Ŵ' => 'W', 'ŵ' => 'w',
    		'Ŷ' => 'Y', 'ŷ' => 'y',
    		'Ÿ' => 'Y', 'Ź' => 'Z',
    		'ź' => 'z', 'Ż' => 'Z',
    		'ż' => 'z', 'Ž' => 'Z',
    		'ž' => 'z', 'ſ' => 's',
    		// Decompositions for Latin Extended-B
    		'Ș' => 'S', 'ș' => 's',
    		'Ț' => 'T', 'ț' => 't',
    		// Euro Sign
    		'€' => 'E',
    		// GBP (Pound) Sign
    		'£' => '',
    		// Vowels with diacritic (Vietnamese)
    		// unmarked
    		'Ơ' => 'O', 'ơ' => 'o',
    		'Ư' => 'U', 'ư' => 'u',
    		// grave accent
    		'Ầ' => 'A', 'ầ' => 'a',
    		'Ằ' => 'A', 'ằ' => 'a',
    		'Ề' => 'E', 'ề' => 'e',
    		'Ồ' => 'O', 'ồ' => 'o',
    		'Ờ' => 'O', 'ờ' => 'o',
    		'Ừ' => 'U', 'ừ' => 'u',
    		'Ỳ' => 'Y', 'ỳ' => 'y',
    		// hook
    		'Ả' => 'A', 'ả' => 'a',
    		'Ẩ' => 'A', 'ẩ' => 'a',
    		'Ẳ' => 'A', 'ẳ' => 'a',
    		'Ẻ' => 'E', 'ẻ' => 'e',
    		'Ể' => 'E', 'ể' => 'e',
    		'Ỉ' => 'I', 'ỉ' => 'i',
    		'Ỏ' => 'O', 'ỏ' => 'o',
    		'Ổ' => 'O', 'ổ' => 'o',
    		'Ở' => 'O', 'ở' => 'o',
    		'Ủ' => 'U', 'ủ' => 'u',
    		'Ử' => 'U', 'ử' => 'u',
    		'Ỷ' => 'Y', 'ỷ' => 'y',
    		// tilde
    		'Ẫ' => 'A', 'ẫ' => 'a',
    		'Ẵ' => 'A', 'ẵ' => 'a',
    		'Ẽ' => 'E', 'ẽ' => 'e',
    		'Ễ' => 'E', 'ễ' => 'e',
    		'Ỗ' => 'O', 'ỗ' => 'o',
    		'Ỡ' => 'O', 'ỡ' => 'o',
    		'Ữ' => 'U', 'ữ' => 'u',
    		'Ỹ' => 'Y', 'ỹ' => 'y',
    		// acute accent
    		'Ấ' => 'A', 'ấ' => 'a',
    		'Ắ' => 'A', 'ắ' => 'a',
    		'Ế' => 'E', 'ế' => 'e',
    		'Ố' => 'O', 'ố' => 'o',
    		'Ớ' => 'O', 'ớ' => 'o',
    		'Ứ' => 'U', 'ứ' => 'u',
    		// dot below
    		'Ạ' => 'A', 'ạ' => 'a',
    		'Ậ' => 'A', 'ậ' => 'a',
    		'Ặ' => 'A', 'ặ' => 'a',
    		'Ẹ' => 'E', 'ẹ' => 'e',
    		'Ệ' => 'E', 'ệ' => 'e',
    		'Ị' => 'I', 'ị' => 'i',
    		'Ọ' => 'O', 'ọ' => 'o',
    		'Ộ' => 'O', 'ộ' => 'o',
    		'Ợ' => 'O', 'ợ' => 'o',
    		'Ụ' => 'U', 'ụ' => 'u',
    		'Ự' => 'U', 'ự' => 'u',
    		'Ỵ' => 'Y', 'ỵ' => 'y',
    		// Vowels with diacritic (Chinese, Hanyu Pinyin)
    		'ɑ' => 'a',
    		// macron
    		'Ǖ' => 'U', 'ǖ' => 'u',
    		// acute accent
    		'Ǘ' => 'U', 'ǘ' => 'u',
    		// caron
    		'Ǎ' => 'A', 'ǎ' => 'a',
    		'Ǐ' => 'I', 'ǐ' => 'i',
    		'Ǒ' => 'O', 'ǒ' => 'o',
    		'Ǔ' => 'U', 'ǔ' => 'u',
    		'Ǚ' => 'U', 'ǚ' => 'u',
    		// grave accent
    		'Ǜ' => 'U', 'ǜ' => 'u',
    		);

    		$string = strtr($string, $chars);
    	} else {
    		$chars = array();
    		// Assume ISO-8859-1 if not UTF-8
    		$chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
    			."\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
    			."\xc3\xc4\xc5\xc7\xc8\xc9\xca"
    			."\xcb\xcc\xcd\xce\xcf\xd1\xd2"
    			."\xd3\xd4\xd5\xd6\xd8\xd9\xda"
    			."\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
    			."\xe4\xe5\xe7\xe8\xe9\xea\xeb"
    			."\xec\xed\xee\xef\xf1\xf2\xf3"
    			."\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
    			."\xfc\xfd\xff";

    		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

    		$string = strtr($string, $chars['in'], $chars['out']);
    		$double_chars = array();
    		$double_chars['in'] = array("\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe");
    		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
    		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
    	}

    	return $string;
    }


    /**
     * 
     * Função que prepara os campos do tipo radio do formulário.
     *
     */

    function radio_checked($data, $campo, $tipo = FALSE, $default = FALSE, $multi = FALSE, $array = FALSE) {
      
        $radio = array();

        if (!$data && $default)
            $data = $default;

        if ($multi) {
            $tipo = explode('|', $tipo);
            $i = count($tipo);
        }
        else {
            $tipo = str_split($tipo);
            $i = count($tipo);
        }
    
        if ($array) {
        
            for ($j = 0; $j < $i; $j++) {
                
                if ($data == $tipo[$j]) {
                    
                    for ($k = 0; $k < $i; $k++)
                        if ($k == $j) {

                            $radio['c'][$k] = 'checked';
                            $radio['a'][$k] = 'active';
                            $radio['b'][$k] = 'warning';

                        }
                        else {
                            
                            $radio['c'][$k] = '';
                            $radio['a'][$k] = '';
                            $radio['b'][$k] = 'secondary';

                        }
                        
                }
                else {

                    $radio['c'][$j] = '';
                    $radio['a'][$j] = '';
                    $radio['b'][$j] = 'secondary';

                }
            }

        }
        else {
            
            for ($j = 0; $j < $i; $j++) {

                if ($data == $tipo[$j]) {

                    for ($k = 0; $k < $i; $k++)
                        ($k == $j) ? $radio[$k] = 'checked' : $radio[$k] = '';

                }
            }
        }

        /*
        print "<pre>";
        print_r($radio);
        print "</pre>";
        exit();
        #*/

        return $radio;

    }

    /**
     * 
     * Função que exibe/oculta o radio do formulário.
     *
     */
    function radio_showhide($data, $valor, $default = FALSE) {
        return ( ($data && $data == $valor) || (!$data && $default === TRUE) ) ? '' : 'style="display: none;"';
    }

    /**
     * 
     * Função que exibe/oculta uma div do formulário.
     *
     */
    function div_showhide($data, $campo = FALSE, $valor = FALSE, $checkbox = FALSE, $default = FALSE, $todos = FALSE, $not = FALSE) {

        if($not)
            return ($data != 1) ? '' : 'style="display: none;"';
        else {

            if ($todos == 1)
                return ($data != 0) ? '' : 'style="display: none;"';
            else {
                if ($checkbox)
                    return ($data) ? '' : 'style="display: none;"';
                else {
                    if($default && !$data)
                        return '';
                    else
                        return ($data == $valor) ? '' : 'style="display: none;"';
                }
            }
        }

    }    

}
