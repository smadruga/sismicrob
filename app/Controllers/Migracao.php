<?php

namespace App\Controllers;

use App\Models\MigracaoModel;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\HUAP_Functions;

class Migracao extends BaseController
{

    /**
    * Completa a tabela Preschuap_Prescricao_Medicamento com dados da tabela TabPreschuap_Protocolo_Medicamento
    *
    * @return mixed
    */
    public function completa_tabela()
    {

        $migracao = new MigracaoModel(); #Inicia o objeto baseado na TabelaModel
        $v['func'] = new HUAP_Functions(); #Inicia a classe de funções próprias

        $v['tpm'] = $migracao->list_tab_protocolo_medicamento();
        $v['pm'] = $migracao->list_prescricao_medicamento();

        $r = array();
        $i=0;
        foreach ($v['pm'] as $val) {

            $r[] = [
                'idPreschuap_Prescricao_Medicamento'    => $val['idPreschuap_Prescricao_Medicamento'],
                'idPreschuap_Prescricao'                => $val['idPreschuap_Prescricao'],
                'idTabPreschuap_Protocolo_Medicamento'  => $val['idTabPreschuap_Protocolo_Medicamento'],
                'Ajuste'                                => $val['Ajuste'],
                'Calculo'                               => $val['Calculo'],
                'idTabPreschuap_Protocolo'              => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['idTabPreschuap_Protocolo'],
                'OrdemInfusao'                          => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['OrdemInfusao'],
                'idTabPreschuap_EtapaTerapia'           => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['idTabPreschuap_EtapaTerapia'],
                'idTabPreschuap_Medicamento'            => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['idTabPreschuap_Medicamento'],
                'Dose'                                  => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['Dose'],
                'idTabPreschuap_UnidadeMedida'          => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['idTabPreschuap_UnidadeMedida'],
                'idTabPreschuap_ViaAdministracao'       => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['idTabPreschuap_ViaAdministracao'],
                'idTabPreschuap_Diluente'               => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['idTabPreschuap_Diluente'],
                'Volume'                                => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['Volume'],
                'TempoInfusao'                          => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['TempoInfusao'],
                'idTabPreschuap_Posologia'              => $v['tpm'][$val['idTabPreschuap_Protocolo_Medicamento']]['idTabPreschuap_Posologia'],
            ];
            /*
            $i++;
            if ($i>=5)
                break;
            #*/
        }

        echo ($migracao->update_pm($r)) ? 'FOI' : 'fudeu';

        /*
        echo "<pre>";
        print_r($r);
        echo "</pre>";
        echo "<pre>";
        #print_r($v['tpm']);
        echo "</pre>";
        #exit('oi');
        #*/

        echo '<br /><hr />FINISH HIM!<hr />';

    }

    /**
    * Completa a tabela Preschuap_Prescricao_Medicamento com o cálculo das doses ajustadas
    *
    * @return mixed
    */
    public function calcula_tabela()
    {

        $migracao = new MigracaoModel(); #Inicia o objeto baseado na TabelaModel
        $v['func'] = new HUAP_Functions(); #Inicia a classe de funções próprias

        $v['prontuario'] = $migracao->list_prontuario();
        $v['aghux'] = $migracao->get_aghux($v['prontuario']);

        #/*
        echo "<pre>";
        print_r($v['prontuario']);
        echo "</pre>";
        echo "<pre>";
        #print_r($v['tpm']);
        echo "</pre>";
        #exit('oi');
        #*/

        echo '<br /><hr />FINISH HIM!<hr />';

    }

}
