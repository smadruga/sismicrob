<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\HUAP_Functions;

class AtendimentoModel extends Model
{
    protected $DBGroup              = 'aghux';
    protected $table                = 'agh.agh_atendimentos';
    protected $primaryKey           = 'pac_codigo';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $protectFields        = true;
    protected $allowedFields        = [
                                        'pac_codigo',
                                        'dt_consulta',
                                        'origem',
                                        'prontuario',
                                    ];

    /**
    * Relação de atendimentos realizados no AGHUX do paciente especificado.
    *
    * @return void
    */
    public function list_paciente_atendimento($data)
    {

        $db = \Config\Database::connect('aghux');
        $query = $db->query("
            select 
                pac_codigo,
                to_char(dt_consulta, 'DD/MM/YYYY') as dt_consulta_formatada,
                case
                    when origem = 'I' then 'INTERNAÇÃO'
                    when origem = 'A' then 'CONSULTA'
                    when origem = 'C' then 'CIRURGIA'
                    when origem = 'N' then 'RECÉN-NASCIDO'
                    else 'CONSULTA CANCELADA'
                end as origem,
                prontuario
            from
                (
                        select 
                            aa.pac_codigo
                            , ac.dt_consulta  
                            , aa.origem
                            , aa.prontuario
                        from 
                            agh.agh_atendimentos aa 
                                left join agh.aac_consultas ac on aa.con_numero = ac.numero   
                        where 
                            
                            aa.prontuario = ".$data."
                            and ac.ret_seq = 10
                    union
                        select 
                            aa.pac_codigo
                            , aa.dthr_inicio  
                            , aa.origem
                            , aa.prontuario
                        from
                            agh.agh_atendimentos aa
                        where 
                            aa.prontuario = ".$data."
                            and aa.origem in ('C', 'I', 'N')
                ) results
            order by dt_consulta desc
        ");

        #$query = $query->getResultArray();
        #$query = $query->getNumRows();
        $r['array'] = $query->getResultArray();
        $r['count'] = $query->getNumRows();

        /*
        echo $db->getLastQuery();
        echo "<pre>";
        print_r($r);
        echo "</pre>";
        exit($data);
        #*/
        #return ($query->getNumRows() > 0) ? $query->getRowArray() : FALSE ;

        return $r;

    }

}
