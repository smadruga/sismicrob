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

                                        'Prontuario',                   
                                        'CodigoAghux',                
                                    ];

    /**
    * Retorna zero, um ou mais prescrições médicas registradas no banco de dados.
    *
    * @return void
    */
    public function read_prescricao($data, $buscaid = FALSE, $row = FALSE)
    {

        $where = ($buscaid) ? 'p.idSismicrob_Tratamento = '.$data : 'p.Prontuario = '.$data;

        $db = \Config\Database::connect();
        $query = $db->query('
            SELECT
                p.idSismicrob_Tratamento
                , p.Prontuario
                , date_format(p.DataMarcacao, "%d/%m/%Y") as DataMarcacao
                , date_format(p.DataPrescricao, "%d/%m/%Y") as DataPrescricao
                , concat("D",p.Dia) as Dia
                , p.Ciclo
                , p.Aplicabilidade
                , concat(tc.idTabPreschuap_Categoria, " - ", tc.Categoria) as Categoria
                , concat(ts.idTabPreschuap_Subcategoria, " - ", ts.Subcategoria) as Subcategoria
                , tp.Protocolo
                , tp.Observacoes
                , ttt.TipoTerapia
                , p.CiclosTotais
                , p.EntreCiclos

                , format(p.Peso, 2, "pt_BR") as Peso
                , format(p.CreatininaSerica, 2, "pt_BR") as CreatininaSerica
                , Altura
                , format(p.ClearanceCreatinina, 2, "pt_BR") as ClearanceCreatinina
                , format(p.IndiceMassaCorporal, 2, "pt_BR") as IndiceMassaCorporal
                , format(p.SuperficieCorporal, 2, "pt_BR") as SuperficieCorporal

                , u.Nome
                , u.Cpf
                , p.Status
                , p.Leito
                , p.DescricaoServico
                , tmc.MotivoCancelamento
                , p.InformacaoComplementar
                , p.ReacaoAdversa
                , p.Alergia
                , p.Concluido
            FROM
                preschuapweb.Sismicrob_Tratamento as st
                    left join TabPreschuap_Categoria as tc on p.idTabPreschuap_Categoria = tc.idTabPreschuap_Categoria
                    left join TabPreschuap_Subcategoria as ts on p.idTabPreschuap_Subcategoria = ts.idTabPreschuap_Subcategoria
                    left join TabPreschuap_Protocolo as tp on p.idTabPreschuap_Protocolo = tp.idTabPreschuap_Protocolo
                    left join TabPreschuap_TipoTerapia as ttt on p.idTabPreschuap_TipoTerapia = ttt.idTabPreschuap_TipoTerapia
                    left join Sishuap_Usuario as u on p.idSishuap_Usuario = u.idSishuap_Usuario
                    left join TabPreschuap_MotivoCancelamento as tmc on p.idTabPreschuap_MotivoCancelamento = tmc.idTabPreschuap_MotivoCancelamento
            WHERE
                '.$where.'
            ORDER BY p.idSismicrob_Tratamento DESC

        ');
        /*
        echo $db->getLastQuery();
        echo "<pre>";
        print_r($query->getResultArray());
        echo "</pre>";
        exit($data.' <> '.$query->getNumRows());
        #*/
        #return ($query->getNumRows() > 0) ? $query->getRowArray() : FALSE ;


        if($buscaid && $row) {
            return $query->getRowArray();
        }
        else {
            $r['array'] = $query->getResultArray();
            $r['count'] = $query->getNumRows();
            return $r;
        }

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

}
