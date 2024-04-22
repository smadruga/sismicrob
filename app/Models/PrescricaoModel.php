<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\HUAP_Functions;

class PrescricaoModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'Sismicrob_Tratamento';
    protected $primaryKey           = 'idSismicrob_Tratamento';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $protectFields        = true;
    protected $allowedFields        = [
                                        'idSismicrob_Tratamento',      
                                        'Medicamento',                 
                                        'DataInicioTratamento',        
                                        'Duracao',                     
                                        'DataFimTratamento',           
                                        
                                        'DoseAtaque',                                
                                        'DosePosologica',              
                                        'UnidadeMedida',                              
                                        'DoseDiaria',                  
                                        'Unidades',                    
                                        'Peso',                        
                                        'Creatinina',                  
                                        'Clearance',                   
                                        'Hemodialise',                 
                                        
                                        'DiagnosticoInfecciosoOutro',  
                                        'SubstituicaoMedicamento',     
                                        'IndicacaoTipoCirurgia',               
                                        
                                        'Avaliacao',                           
                                        'AvaliacaoDose',                       
                                        'AvaliacaoDoseObs',                    
                                        'AvaliacaoDuracao',                    
                                        'AvaliacaoDuracaoObs',                 
                                        'AvaliacaoIntervalo',                  
                                        'AvaliacaoIntervaloObs',               
                                        'AvaliacaoIndicacao',                  
                                        'AvaliacaoIndicacaoObs',               
                                        'AvaliacaoPreenchimentoInadequado',    
                                        'AvaliacaoPreenchimentoInadequadoObs', 
                                        'AvaliacaoOutros',                     
                                        'AvaliacaoOutrosObs',                  
                                        
                                        'AlteracaoPorAlta',            
                                        
                                        'SubstituirTratamento',        
                                        'SubstituidoPeloTratamento',   
                                        
                                        'Justificativa',               
                                        'Suspender',                   
                                        'SuspenderObs',                
                                        
                                        'Prorrogar',                   
                                        'ProrrogarObs',                
                                                 
                                        'idTabSismicrob_ViaAdministracao',     
                                        'idTabSismicrob_Especialidade',        
                                        'idTabSismicrob_DiagnosticoInfeccioso',
                                        'idTabSismicrob_Tratamento',           
                                        'idTabSismicrob_Substituicao',         
                                        'idTabSismicrob_Indicacao',            
                                        'idTabSismicrob_Infeccao',             
                                        'idTabSismicrob_Intervalo',           
                                        'idTabSismicrob_AntibioticoMantido',

                                        'CodigoMedicamento',
                                        'NomeMedicamento',

                                        'Prontuario',
                                        'CodigoAghux',

                                        'Concluido',

                                        'DataPrescricao',
                                        'DataConclusao',

                                        'idSishuap_Usuario',
                                        'Nome',

                                        'idSishuap_Usuario1',
                                        'idSishuap_Usuario2',
                                        'AvaliacaoObs',
                                        'DataAvaliacao',
                                    ];

    /**
    * Retorna zero, um ou mais prescrições médicas registradas no banco de dados.
    *
    * @return void
    */
    public function read_prescricao($data = FALSE, $buscaid = FALSE, $row = FALSE, $avaliacao = FALSE)
    {

        #$prescricao     = new PrescricaoModel(); #Inicia o objeto baseado na TabelaModel

        #$where = ($buscaid) ? 'st.idSismicrob_Tratamento = '.$data : 'st.Prontuario = '.$data;
        if ($data && $buscaid)
            $where = 'st.idSismicrob_Tratamento = '.$data;
        elseif ($data && !$buscaid)
            $where = 'st.Prontuario = '.$data;
        elseif (!$data && $avaliacao)
            $where = 'st.Avaliacao = "'.$avaliacao.'" AND st.Concluido = 1 ';
        else
            exit('ERRO 5XEZ');

        #exit('ERRO 5XEZ'.$where);

        #exit('ERRO 5XEZ'.$where);

        $db = \Config\Database::connect();
        $query = $db->query('
            SELECT
                st.idSismicrob_Tratamento
                , date_format(st.DataInicioTratamento, "%d/%m/%Y") as DataInicioTratamento
                , st.Duracao
                , date_format(st.DataFimTratamento, "%d/%m/%Y") as DataFimTratamento
                , st.DoseAtaque
                , concat(format(st.DosePosologica, 2, "pt_BR"), " ", st.UnidadeMedida) as DosePosologica
                , st.IntervaloUnidade
                , concat(format(st.DoseDiaria, 2, "pt_BR"), " ", st.UnidadeMedida) as DoseDiaria
                , st.Unidades
                , format(st.Peso, 2, "pt_BR") as Peso
                , format(st.Creatinina, 2, "pt_BR") as Creatinina
                , format(st.Clearance, 2, "pt_BR") as Clearance
                , st.Hemodialise
                , st.DiagnosticoInfecciosoOutro
                , st.SubstituicaoMedicamento
                , st.IndicacaoTipoCirurgia
                , st.Avaliacao
                , st.AvaliacaoDose
                , st.AvaliacaoDoseObs
                , st.AvaliacaoDuracao
                , st.AvaliacaoDuracaoObs
                , st.AvaliacaoIntervalo
                , st.AvaliacaoIntervaloObs
                , st.AvaliacaoIndicacao
                , st.AvaliacaoIndicacaoObs
                , st.AvaliacaoPreenchimentoInadequado
                , st.AvaliacaoPreenchimentoInadequadoObs
                , st.AvaliacaoOutros
                , st.AvaliacaoOutrosObs
                , st.AlteracaoPorAlta
                , st.SubstituirTratamento
                , st.SubstituidoPeloTratamento
                , st.Justificativa                
                , st.Suspender
                , st.SuspenderObs                
                , st.Prorrogar
                , st.ProrrogarObs                
                , va.ViaAdministracao
                , e.Especialidade
                , di.DiagnosticoInfeccioso
                , st.idTabSismicrob_DiagnosticoInfeccioso
                , t.Tratamento
                , s.Substituicao
                , ind.Indicacao
                , st.idTabSismicrob_Indicacao
                , inf.Infeccao
                , st.idTabSismicrob_Intervalo
                , inte.Intervalo
                , concat(inte.Intervalo, " ", inte.Codigo) as Intervalo
                , st.idTabSismicrob_Intervalo
                , st.CodigoMedicamento
                , st.NomeMedicamento
                , am.AntibioticoMantido
                , st.Prontuario
                , st.CodigoAghux
                , st.Concluido
                , date_format(st.DataPrescricao, "%d/%m/%Y %H:%i:%s") as DataPrescricao
                , date_format(st.DataConclusao, "%d/%m/%Y %H:%i:%s") as DataConclusao
                , st.idSishuap_Usuario
                , u.Cpf as CpfPrescritor
                , u.Nome as NomePrescritor
                , u1.Cpf as CpfResponsavel
                , u1.Nome as NomeResponsavel
                , u2.Cpf as CpfAvaliador
                , u2.Nome as NomeAvaliador
            FROM
                preschuapweb.Sismicrob_Tratamento as st
                    left join TabSismicrob_AntibioticoMantido as am     on st.idTabSismicrob_AntibioticoMantido     = am.idTabSismicrob_AntibioticoMantido
                    left join TabSismicrob_DiagnosticoInfeccioso as di  on st.idTabSismicrob_DiagnosticoInfeccioso  = di.idTabSismicrob_DiagnosticoInfeccioso
                    left join TabSismicrob_Especialidade as e           on st.idTabSismicrob_Especialidade          = e.idTabSismicrob_Especialidade
                    left join TabSismicrob_Indicacao as ind             on st.idTabSismicrob_Indicacao              = ind.idTabSismicrob_Indicacao
                    left join TabSismicrob_Infeccao as inf              on st.idTabSismicrob_Infeccao               = inf.idTabSismicrob_Infeccao
                    left join TabSismicrob_Intervalo as inte            on st.idTabSismicrob_Intervalo              = inte.idTabSismicrob_Intervalo
                    left join TabSismicrob_Substituicao s               on st.idTabSismicrob_Substituicao           = s.idTabSismicrob_Substituicao
                    left join TabSismicrob_Tratamento t                 on st.idTabSismicrob_Tratamento             = t.idTabSismicrob_Tratamento
                    left join TabSismicrob_ViaAdministracao as va       on st.idTabSismicrob_ViaAdministracao       = va.idTabSismicrob_ViaAdministracao
                    left join Sishuap_Usuario as u                      on st.idSishuap_Usuario                     = u.idSishuap_Usuario
                    left join Sishuap_Usuario as u1                     on st.idSishuap_Usuario1                    = u1.idSishuap_Usuario
                    left join Sishuap_Usuario as u2                     on st.idSishuap_Usuario2                    = u2.idSishuap_Usuario
            WHERE
                    '.$where.'            
            ORDER BY st.idSismicrob_Tratamento DESC
        ');

        #foreach($prescricao['array'] as $v) {        }

        /*
        echo $db->getLastQuery();
        echo "<pre>";
        print_r($query->getResultArray());
        echo "</pre>";
        exit($data.' <> '.$query->getNumRows());
        #*/

        /*
        $qr = $query->getResultArray();
        $qn = $query->getNumRows();

        
        echo "<pre>";
        print_r($qr);
        echo "</pre>";
        exit($data.' <> '.$qn);
        #*/
    
        if($buscaid && $row) {

            $r['array'] = $query->getRowArray();
            $r['array']['Conselho'] = $this->get_conselho($r['array']['CpfPrescritor']);
            $r['array']['Conselho1'] = ($r['array']['CpfResponsavel']) ? $this->get_conselho($r['array']['CpfResponsavel']) : NULL;
            $r['array']['Conselho2'] = ($r['array']['CpfAvaliador']) ? $this->get_conselho($r['array']['CpfAvaliador']) : NULL;

            return $r['array'];

        }
        else {
            $r['array'] = $query->getResultArray();
            $r['count'] = $query->getNumRows();

            $i = 0;
            foreach($query->getResultArray() as $v) {        
                $r['array'][$i]['Conselho'] = $this->get_conselho($v['CpfPrescritor']);
                $r['array'][$i]['Conselho1'] = ($v['CpfResponsavel']) ? $this->get_conselho($v['CpfResponsavel']) : NULL;
                $r['array'][$i]['Conselho2'] = ($v['CpfAvaliador']) ? $this->get_conselho($v['CpfAvaliador']) : NULL;
                $i++;
            }

            return $r;
        }

    }

    /**
    * Captura o id da prescrição concluída mais recente.
    *
    * @return void
    */
    public function get_last_id($prontuario)
    {

        $db = \Config\Database::connect();
        $query = $db->query('
            SELECT
                idSismicrob_Tratamento
            FROM
                Sismicrob_Tratamento
            WHERE
                Prontuario = '.$prontuario.'
                AND Concluido = 1
            ORDER BY idSismicrob_Tratamento DESC
            LIMIT 0,1;
        ');

        /*
        echo $db->getLastQuery();
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        exit($data.'<><>');
        #*/
        #return ($query->getNumRows() > 0) ? $query->getRowArray() : FALSE ;

        $query = $query->getRowArray();
        return $query['idSismicrob_Tratamento'];

    }

    /**
    * Tela inicial do sismicrob
    *
    * @return void
    */
    public function get_conselho($data)
    {

        $db = \Config\Database::connect('aghux');
        $query = $db->query('
            SELECT
                concat(cpr_sigla, \'-\',nro_reg_conselho) as conselho
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
