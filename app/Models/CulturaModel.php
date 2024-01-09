<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\HUAP_Functions;

class CulturaModel extends Model
{
    protected $DBGroup              = 'aghux';
    protected $table                = 'agh.ael_resultado_exames';
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
    * Relação de culturas realizadas no AGHUX do paciente especificado.
    *
    * @return void
    */
    public function list_paciente_cultura($data)
    {

        $db = \Config\Database::connect('aghux');
        $query = $db->query('
                select
                aa.seq as seq_atd  
                , ase.seq as seq_solic_ex
                , ase.localizador 	
                , to_char(aa.dthr_inicio, \'DD/MM/YYYY\') as dt_pedido
                , to_char(aa.dthr_inicio, \'DD/MM/YYYY HH:MM:SS\') as dthr_inicio
                , to_char(aa.dthr_fim, \'DD/MM/YYYY HH:MM:SS\') as dthr_fim
                , to_char(are2.criado_em, \'DD/MM/YYYY HH:MM:SS\') as dthr_criado
                , CASE
                    WHEN aa.origem = \'I\' THEN \'INTERNAÇÃO\'
                    WHEN aa.origem = \'A\' THEN \'CONSULTA\'
                    WHEN aa.origem = \'C\' THEN \'CIRURGIA\'
                    WHEN aa.origem = \'N\' THEN \'RECÉN-NASCIDO\'
                ELSE
                    \'CONSULTA CANCELADA\'
                END as tipo
                , CASE
                    WHEN aise.sit_codigo = \'AC\' THEN \'A COLETAR\'
                    WHEN aise.sit_codigo = \'AE\' THEN \'ÁREA EXECUTORA\'
                    WHEN aise.sit_codigo = \'AG\' THEN \'AGENDADO\'
                    WHEN aise.sit_codigo = \'AX\' THEN \'A EXECUTAR\'
                    WHEN aise.sit_codigo = \'CA\' THEN \'CANCELADO\'
                    WHEN aise.sit_codigo = \'CO\' THEN \'COLETADO\'
                    WHEN aise.sit_codigo = \'CS\' THEN \'COLETADO PELO SOLICITANTE\'
                    WHEN aise.sit_codigo = \'EC\' THEN \'EM COLETA\'
                    WHEN aise.sit_codigo = \'EX\' THEN \'EXECUTANDO\'
                    WHEN aise.sit_codigo = \'LI\' THEN \'LIBERADO\'
                    WHEN aise.sit_codigo = \'RE\' THEN \'RECEBIDO\'
                ELSE
                    \'PENDENTE\'
                END as sit_codigo
                , aise.seqp as seq_item_exame
                , are2.ise_seqp as ordem
                , concat(aise.ufe_ema_exa_sigla, \' - \', avl.nome_desenho) as exame
                , aise.ufe_ema_man_seq
                , avl.ema_man_seq
                , arc.descricao
                , are2.pcl_vel_ema_man_seq
                , are2.pcl_vel_seqp
                , are2.pcl_cal_seq
                , are2.pcl_seqp
                , aise.ind_gerado_automatico
                , aise.ise_seqp
                , apcl.posicao_linha_tela
            from
                agh.agh_atendimentos aa
                , agh.ael_solicitacao_exames ase
                , agh.aip_pacientes ap
                , agh.ael_item_solicitacao_exames aise
                , agh.ael_versao_laudos avl
                , agh.ael_resultados_exames are2
                , agh.ael_resultados_codificados arc
                , agh.ael_parametro_campos_laudo apcl
            where
                aa.prontuario = 2600609
                and avl.ind_situacao = \'A\'
                and avl.nome_desenho ilike \'%CULTURA%\'
                and ase.seq is not null
                and ap.codigo = aa.pac_codigo
                and aa.seq = ase.atd_seq
                and ase.seq = aise.soe_seq	
                and aise.ufe_ema_exa_sigla = avl.ema_exa_sigla
                    and aise.ufe_ema_man_seq = avl.ema_man_seq
                and aise.soe_seq = are2.ise_soe_seq	
                    and aise.seqp = are2.ise_seqp
                and are2.rcd_gtc_seq = arc.gtc_seq
                    and are2.rcd_seqp = arc.seqp
                and are2.pcl_vel_ema_exa_sigla = apcl.vel_ema_exa_sigla
                    and are2.pcl_vel_ema_man_seq = apcl.vel_ema_man_seq
                    and are2.pcl_vel_seqp = apcl.vel_seqp
                    and are2.pcl_cal_seq = apcl.cal_seq
                    and are2.pcl_seqp = apcl.seqp
            order by ase.seq desc, aise.seqp asc, are2.criado_em asc, apcl.posicao_linha_tela asc
        ');

        #$query = $query->getResultArray();
        #$query = $query->getNumRows();
        $r['array'] = $query->getResultArray();
        $r['count'] = $query->getNumRows();

        $r['culturas'] = array();
        $d = array();        
        foreach ($r['array'] as $k => $v) {
            $r['culturas'][$v['seq_solic_ex']][$v['ordem']][$v['ema_man_seq']] = $v;
            $d[$v['seq_solic_ex']][$v['ordem']][$v['ema_man_seq']][$v['pcl_cal_seq']] = $v['descricao'];
        }        

        foreach($r['culturas'] as $k => $v) { 
            echo ' <br>>a '.$k;
            foreach($r['culturas'][$k] as $k2 => $v2) { 
                echo ' <br>>>>b '.$k2;
                foreach($r['culturas'][$k][$k2] as $k3 => $v3) {
                    echo ' <br>>>>>>>c '.$k3;
                    foreach($d[$k][$k2][$k3] as $k4 => $v4) { 
                        echo ' <br>>>>>>>>>>d '.$k4.' '.$v4;
                        $r['culturas'][$k][$k2][$k3][$k4] = $d[$k][$k2][$k3][$k4];
                        $r['culturas'][$k][$k2][$k3]['resultado'] = $d[$k][$k2][$k3][$k4];
                    }
                }
            }
        }

        echo "<br><><>0<br>";

        #/*
        echo $db->getLastQuery();

        echo "<br><><>1<br>";
        echo "<pre>";
        print_r($r['culturas']);
        echo "</pre>";
        echo "<br><><>2<br>";
        echo "<pre>";
        print_r($d);
        echo "</pre>";
        echo "<br><><>3<br>";
        echo "<pre>";
        print_r($r);
        echo "</pre>";
        echo "<br><><>4<br>";
        
        exit($data);
        #*/
        #return ($query->getNumRows() > 0) ? $query->getRowArray() : FALSE ;

        return $r;

    }

}
