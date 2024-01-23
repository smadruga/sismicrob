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
    * Busca o TSA realizado no AGHUX do paciente e ordem especificados.
    *
    * @return void
    */
    public function show_paciente_tsa($pront, $sol, $ordem)
    {

        $db = \Config\Database::connect('aghux');
        /*
         *
         * CABEÇALHO TSA
         * 
        */
        $query1 = $db->query('
            select
                ase.seq as seq_solic_ex
                , to_char(are2.criado_em, \'DD/MM/YYYY HH24:MI:SS\') as dt_ult_mov
                , to_char(aa2.dthr_entrada , \'DD/MM/YYYY HH24:MI:SS\') as dt_coleta
                , are2.ise_seqp as ordem
                , concat(aise.ufe_ema_exa_sigla, \' - \', avl.nome_desenho) as exame
                , arc.descricao
                , ase.atd_seq 
                , auf.descricao as unidade_solicitante
                , are2.pcl_cal_seq 
            from
                agh.agh_atendimentos aa
                , agh.agh_unidades_funcionais auf
                , agh.ael_solicitacao_exames ase
                , agh.aip_pacientes ap
                , agh.ael_item_solicitacao_exames aise
                , agh.ael_versao_laudos avl
                , agh.ael_resultados_exames are2
                , agh.ael_resultados_codificados arc
                , agh.ael_parametro_campos_laudo apcl
                , agh.ael_amostra_item_exames aaie 
                , agh.ael_amostras aa2 
            where
                are2.ise_soe_seq = '. $sol . '
                and are2.ise_seqp = '. $ordem . '
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
                and aaie.ise_soe_seq = aise.soe_seq  
                    and aaie.ise_seqp = aise.seqp 
                and aa2.soe_seq = aaie.amo_soe_seq  
                    and aa2.seqp = aaie.amo_seqp 
                and auf.seq = ase.unf_seq 
            order by ase.seq desc, aise.seqp asc, dt_ult_mov asc, apcl.posicao_linha_tela asc
        ');
        $q = $query1->getResultArray();
        #echo $db->getLastQuery();

        foreach ($q as $k => $v)
            $d[$v['pcl_cal_seq']] = $v['descricao'];

        $obs = '';
        foreach ($d as $k => $v)
            $obs .= '=> '.$v.'<br>'; 

        $r['cabecalho'] = array();
        $d = '';
        foreach ($q as $k => $v) {
            $r['cabecalho'] = $v;
            #$d .= '=> '.$v['descricao'].'<br>';
        }

        $d .= $obs;

        $r['cabecalho']['descricao'] = $d;

       /*
         *
         * ANTIMICROBIANO E RESULTADO
         * 
        */
        $query2 = $db->query('
            select 
                are2.ise_soe_seq 
                , are2.ise_seqp 
                , apcl.cal_seq 
                , apcl.seqp 
                , arc.gtc_seq 
                , arc.seqp 
                , arc.descricao 
                , apcl.posicao_linha_tela 
                , are2.pcl_seqp
            from 
                agh.ael_resultados_exames are2 
                , agh.ael_resultados_codificados arc 
                , agh.ael_parametro_campos_laudo apcl
            where 
                are2.ise_soe_seq 				= '.$sol.' 
                and are2.pcl_vel_ema_exa_sigla 	= \'TSA\'
                and are2.rcd_gtc_seq 			in (3, 6)
                and are2.ise_seqp 				= '.($ordem+1).'
                and are2.rcd_gtc_seq 			= arc.gtc_seq 
                and are2.rcd_seqp 				= arc.seqp 
                and are2.pcl_vel_ema_exa_sigla 	= apcl.vel_ema_exa_sigla 
                and are2.pcl_vel_ema_man_seq 	= apcl.vel_ema_man_seq 
                and are2.pcl_vel_seqp  			= apcl.vel_seqp 
                and are2.pcl_cal_seq  			= apcl.cal_seq 
                and are2.pcl_seqp  				= apcl.seqp
            group by 
                are2.ise_soe_seq 
                , are2.ise_seqp 
                , apcl.cal_seq 
                , apcl.seqp 
                , arc.gtc_seq 
                , arc.seqp 
                , arc.descricao 
                , apcl.posicao_linha_tela 
                , are2.rcd_gtc_seq
                , are2.pcl_seqp               
            order by 
                apcl.posicao_linha_tela asc
                , are2.rcd_gtc_seq asc
        ');
        $q = $query2->getResultArray();
        #echo $db->getLastQuery();

        $r['antimicrobiano'] = array();
        foreach ($q as $k => $v)
            $r['antimicrobiano'][$v['cal_seq']][] = $v;


        /*
         *
         * MIC
         * 
        */
        $query3 = $db->query('
            select  
                adr.ree_ise_soe_seq 
                , adr.ree_pcl_cal_seq 
                , adr.ree_pcl_seqp 
                , adr.descricao	
            from 
                agh.ael_descricoes_resultado adr 
                , agh.ael_parametro_campos_laudo apcl 
            where 
                adr.ree_ise_soe_seq					= '.$sol.' 
                and adr.ree_ise_seqp 				= '.($ordem+1).'
                and adr.ree_pcl_vel_ema_exa_sigla 	= apcl.vel_ema_exa_sigla 
                and adr.ree_pcl_vel_ema_man_seq 	= apcl.vel_ema_man_seq 
                and adr.ree_pcl_vel_seqp 			= apcl.vel_seqp 
                and adr.ree_pcl_cal_seq 			= apcl.cal_seq 
                and adr.ree_pcl_seqp 				= apcl.seqp
            group by
                adr.ree_ise_soe_seq ,
                adr.ree_pcl_cal_seq ,
                adr.ree_pcl_seqp ,
                adr.descricao ,
                apcl.posicao_linha_tela
            order by 
                apcl.posicao_linha_tela asc
        ');
        $r['mic'] = $query3->getResultArray();
        #echo '<br><br>'.$db->getLastQuery();

        $r['resultado'] = array();
        $d = array();        
        foreach ($r['mic'] as $k => $v)
            $r['resultado'][$v['ree_pcl_seqp']] = $v;

        $r['mic'] = $r['resultado'];

        unset($r['resultado']);
        /*
        echo "<br><><>1<br>";
        echo "<pre>";
        print_r($r);
        echo "</pre>";
        
        #exit($data);
        #*/

        return $r;

    }

    /**
    * Relação de culturas realizadas no AGHUX do paciente especificado.
    *
    * @return void
    */
    public function list_paciente_cultura($data)
    {

        $db = \Config\Database::connect('aghux');
        /*
         * Relação de culturas realizadas no AGHUX do paciente especificado
         * 
         */
        $query = $db->query('
            select
                aa.seq as seq_atd  
                , ase.seq as seq_solic_ex
                , to_char(are2.criado_em, \'DD/MM/YYYY HH24:MI:SS\') as dt_ult_mov
                , to_char(aa2.dthr_entrada, \'DD/MM/YYYY HH24:MI:SS\') as dt_coleta
                , are2.ise_seqp as ordem
                , concat(aise.ufe_ema_exa_sigla, \' - \', avl.nome_desenho) as exame
                , avl.ema_man_seq
                , arc.descricao
                , are2.pcl_vel_seqp
                , are2.pcl_cal_seq
                , are2.pcl_seqp
                , apcl.posicao_linha_tela
                , CASE
                    WHEN aise.sit_codigo = \'AC\' THEN \'A Coletar\'
                    WHEN aise.sit_codigo = \'AE\' THEN \'Área Executora\'
                    WHEN aise.sit_codigo = \'AG\' THEN \'Agendado\'
                    WHEN aise.sit_codigo = \'AX\' THEN \'A Executar\'
                    WHEN aise.sit_codigo = \'CA\' THEN \'Cancelado\'
                    WHEN aise.sit_codigo = \'CO\' THEN \'Coletado\'
                    WHEN aise.sit_codigo = \'CS\' THEN \'Coletado Pelo Solicitante\'
                    WHEN aise.sit_codigo = \'EC\' THEN \'Em Coleta\'
                    WHEN aise.sit_codigo = \'EX\' THEN \'Executando\'
                    WHEN aise.sit_codigo = \'LI\' THEN \'Liberado\'
                    WHEN aise.sit_codigo = \'RE\' THEN \'Recebido\'
                ELSE
                    \'Pendente\'
                END as sit_codigo
            from
                agh.agh_atendimentos aa
                , agh.ael_solicitacao_exames ase
                , agh.aip_pacientes ap
                , agh.ael_item_solicitacao_exames aise
                , agh.ael_versao_laudos avl
                , agh.ael_resultados_exames are2
                , agh.ael_resultados_codificados arc
                , agh.ael_parametro_campos_laudo apcl
                , agh.ael_amostra_item_exames aaie 
                , agh.ael_amostras aa2 
            where
                aa.prontuario = '. $data . '
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
                	and aaie.ise_soe_seq = aise.soe_seq  
                    and aaie.ise_seqp = aise.seqp 
                and aa2.soe_seq = aaie.amo_soe_seq  
                    and aa2.seqp = aaie.amo_seqp 
            order by ase.seq desc, aise.seqp asc, dt_ult_mov asc, apcl.posicao_linha_tela asc
        ');

        $r['array2'] = $query->getResultArray();
        #$r['count'] = $query->getNumRows();
        #echo $db->getLastQuery();

        $r['resultado'] = array();
        $d = array();        
        foreach ($r['array2'] as $k => $v) {
            $r['resultado'][$v['seq_solic_ex']][$v['ordem']][$v['ema_man_seq']] = $v;
            $d[$v['seq_solic_ex']][$v['ordem']][$v['ema_man_seq']][$v['pcl_cal_seq']] = $v['descricao'];
        }        

        foreach($r['resultado'] as $k => $v) { 
            foreach($r['resultado'][$k] as $k2 => $v2) { 
                foreach($r['resultado'][$k][$k2] as $k3 => $v3) {
                    $r['resultado'][$k][$k2][$k3]['obs'] = '';
                    foreach($d[$k][$k2][$k3] as $k4 => $v4) { 
                        if(strlen($d[$k][$k2][$k3][$k4]) > 1) {
                            $r['resultado'][$k][$k2][$k3]['obs'] .= '=> '.$d[$k][$k2][$k3][$k4].' <br>';
                            #echo ' <br>>a '.$k.' '.$k2.' '.$k3.' '.$k4.' => '.$d[$k][$k2][$k3][$k4].' <br>';
                        }
                    }
                    $r['array'][] = $r['resultado'][$k][$k2][$k3];
                }
            }
        }

        $r['count'] = count($r['array']);

        $query3 = $db->query('
            select 
                are2.ise_soe_seq 
                , are2.ise_seqp 
            from 
                agh.ael_resultados_exames are2 
                , agh.ael_solicitacao_exames ase 
                , agh.agh_atendimentos aa
                , agh.aip_pacientes ap 
            where 
                ap.prontuario 	        = '. $data . '
                and are2.pcl_cal_seq 	= 20113 
                and ase.seq             = are2.ise_soe_seq 
                and ase.atd_seq         = aa.seq
                and aa.pac_codigo       = ap.codigo 
            group by 
                are2.ise_soe_seq
                , are2.ise_seqp
            order by
                are2.ise_soe_seq desc
                , are2.ise_seqp asc
        ');
        $r['array3'] = $query3->getResultArray();

        $r['tsa'] = array();
        foreach($r['array3'] as $k => $v)
            $r['tsa'][$v['ise_soe_seq']][$v['ise_seqp']] = 1;

        unset($r['array2'], $r['array3']);
        /*
        echo "<br><><>0<br>";
        #echo $db->getLastQuery();

        echo "<br><><>1<br>";
        echo "<pre>";
        print_r($r['array3']);
        echo "</pre>";
        echo "<br><><>2<br>";
        echo "<pre>";
        print_r($r['tsa']);
        echo "</pre>";
        echo "<br><><>3<br>";
        echo "<pre>";
        #print_r($r);
        echo "</pre>";
        echo "<br><><>4<br>";
        
        exit($data);
        #*/
        #return ($query->getNumRows() > 0) ? $query->getRowArray() : FALSE ;

        #return $r['resultado'];
        return $r;

    }

}

