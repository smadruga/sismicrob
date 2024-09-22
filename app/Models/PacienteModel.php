<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\HUAP_Functions;

class PacienteModel extends Model
{
    protected $DBGroup              = 'aghux';
    protected $table                = 'aip_pacientes';
    protected $primaryKey           = 'codigo';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $protectFields        = true;
    protected $allowedFields        = [
                                        'codigo',
                                        'nome',
                                        'nome_mae',
                                        'dt_nascimento',
                                        'sexo',
                                        'cpf',
                                        'prontuario',
                                        'prnt_ativo',
                                        'nro_cartao_saude',
                                        'id_sistema_legado',
                                        'email',
                                        'ddd_fone_residencial',
                                        'fone_residencial',
                                        'ddd_fone_recado',
                                        'fone_recado',
                                    ];

    /**
    * Tela inicial do preschuapweb
    *
    * @return void
    */
    public function get_paciente_codigo($data)
    {

        $db = \Config\Database::connect('aghux');
        $query = $db->query("
            SELECT
                codigo
                , nome
                , nome_mae
                , to_char(dt_nascimento, 'DD/MM/YYYY') as dt_nascimento
                , extract(year from age(dt_nascimento)) as idade
                , sexo
                , cpf
                , prontuario
                , prnt_ativo
                , nro_cartao_saude
                , id_sistema_legado
                , email
                , ddd_fone_residencial
                , fone_residencial
                , ddd_fone_recado
                , fone_recado
            FROM
                aip_pacientes
            WHERE
                codigo = ".$data."
        ");

        $query_int = $db->query("
            select
                ap.nome 
                , to_char(ai.dthr_internacao, 'DD/MM/YYYY') as dthr_internacao
                , auf.sigla 
                , auf.descricao 
                , ai.lto_lto_id 
            from 
                agh.aip_pacientes ap 
                    left join agh.ain_internacoes ai on ap.codigo = ai.pac_codigo 
                    left join agh.agh_unidades_funcionais auf on ai.unf_seq = auf.seq 
            where 
                codigo = ".$data."
            order by ai.dthr_internacao desc
            limit 1
            ;
                
        ");

        $query = $query->getRowArray();
        $query_int = $query_int->getRowArray();

        $query['telefone'] = ($query['ddd_fone_residencial'] || $query['fone_residencial']) ? $query['ddd_fone_residencial'].' '.$query['fone_residencial'].' (Residencial) ' : NULL;
        $query['telefone'] .= ($query['ddd_fone_recado'] || $query['fone_recado']) ? $query['ddd_fone_recado'].' '.$query['fone_recado'].' (Recado) ' : NULL;

        $query['internacao'] = $query_int;

        /*
        echo $db->getLastQuery();
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        exit($data);
        #*/
        #return ($query->getNumRows() > 0) ? $query->getRowArray() : FALSE ;

        return $query;

    }

    /**
    * Tela inicial do preschuapweb
    *
    * @return void
    */
    public function get_paciente_bd($data, $limit = NULL, $offset = NULL)
    {

        $func = new HUAP_Functions();

        if($func->check_cpf($data))
            $where = 'cpf = \''.$func->mascara_cpf($data, 'remover').'\'';
        elseif($func->check_date($data, 'regex'))
            $where = 'dt_nascimento = \''.$func->mascara_data($data, 'db').'\'';
        elseif($func->check_date($data, 'checkdate'))
            $where = 'dt_nascimento = \''.$func->mascara_data($data, 'inverter').'\'';
        elseif(is_numeric($data) && strlen($data) <= 9)
            $where = 'prontuario = \''.$data.'\'';
        else
            $where = 'nome ilike \'%'.$data.'%\' OR nome ilike \'%'.$func->remove_accents($data).'%\'';

        $limit = ($limit) ? ' LIMIT '.$limit : NULL;
        $offset = ($offset) ? ' OFFSET '.$offset : NULL;

        $db = \Config\Database::connect('aghux');
        $query = $db->query('
            SELECT
                codigo
                , nome
                , nome_mae
                , dt_nascimento
                , prontuario
            FROM
                aip_pacientes
            WHERE
                '.$where.'
            ORDER BY nome ASC
            '.$limit.'
            '.$offset.'
        ');
        /*
        echo $db->getLastQuery();
        echo "<pre>";
        print_r($query->getResultArray());
        echo "</pre>";
        exit($data);
        #*/
        #return ($query->getNumRows() > 0) ? $query->getRowArray() : FALSE ;

        $q['count'] = $query->getNumRows();
        $q['array'] = $query->getResultArray();

        return ($query->getNumRows() > 0) ? $q : FALSE ;

    }

    /**
    * Tela inicial do preschuapweb
    *
    * @return void
    */
    public function get_conselho($data)
    {

        $db = \Config\Database::connect('aghux');
        $query = $db->query('
            SELECT
                concat(cpr_sigla, \'-\',nro_reg_conselho) as conselho
                , nome
            FROM
                agh.v_rap_servidor_conselho
            WHERE
                cpf = '.$data.'
        ');

        /*
        echo $db->getLastQuery();
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        exit($data);
        #*/
        #return ($query->getNumRows() > 0) ? $query->getRowArray() : FALSE ;

        if($query->getNumRows()) {
            $query = $query->getRowArray();
            return $query['conselho'];
        }
        else
            return 'NÃO ENCONTRADO';


    }    

}
