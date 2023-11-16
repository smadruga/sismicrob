<?php

namespace App\Controllers;

use App\Models\TabelaModel;

use App\Models\AuditoriaModel;
use App\Models\AuditoriaLogModel;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\HUAP_Functions;

class Tabela extends BaseController
{
    private $v;

    public function __construct()
    {

    }

    /**
    * Altera a Ordem de Infusão de um medicamento cadastrado em um protocolo
    *
    * @return void
    */
    public function sort_item($id, $ordem, $sort)
    {
        $tabela = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $auditoria = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func'] = new HUAP_Functions(); #Inicia a classe de funções próprias

        $prox = ($sort == 'up') ? $ordem-1 : $ordem+1;

        $v['data'] = $v['sort'] = $tabela->get_item_sort($id, $ordem.','.$prox);

        $i=0;
        foreach ($v['sort'] as $val) { #aplica a nova ordem de infusão

            $v['data'][$i]['OrdemInfusao'] = ($val['OrdemInfusao'] == $ordem) ? $prox : $ordem;

            $i++;
        }

        #aplico o update
        if($tabela->update_item_sort($v['data'])) {

            $i=0;
            foreach ($v['sort'] as $val) {

                $v['id'] = $val['idTabPreschuap_Protocolo_Medicamento'];
                unset($val['idTabPreschuap_Protocolo_Medicamento']);

                $v['campos'] = array_keys($val);
                $v['anterior'] = $val;

                $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabPreschuap_Protocolo_Medicamento', 'UPDATE', $v['id']), TRUE);
                $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'][$i], $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);

                $i++;
            }

            session()->setFlashdata('success', 'Item atualizado com sucesso!');
            return redirect()->to('tabela/list_tabela/Protocolo_Medicamento/cadastrar/'.$id);

        }
        else {

            session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');
            return redirect()->to('tabela/list_tabela/Protocolo_Medicamento/cadastrar/'.$id);

        }

        /*
        #echo $db->getLastQuery();
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        echo "<pre>";
        print_r($v['data']);
        echo "</pre>";
        exit('oi');
        #*/

    }

    /**
    * Lista as prescrições associadas ao paciente
    *
    * @return mixed
    */
    public function list_tabela($tab = FALSE, $action = FALSE, $data = FALSE)
    {

        $tabela = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $auditoria = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func'] = new HUAP_Functions(); #Inicia a classe de funções próprias

        $v['tabela'] = $tab;
        $action = (!$action) ? $this->request->getVar('action', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : $action;

        #Captura os inputs do Formulário
        $v['data'] = array_map('trim', $this->request->getVar(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        if($v['tabela'] == 'Protocolo')
            $v['data']['Aplicabilidade'] = (isset($v['data']['Aplicabilidade'])) ? $v['data']['Aplicabilidade'] : NULL;

        /*
        $opt = $this->get_opt($tab, $action, $data);
        $v['opt'] = $opt['opt'];
        $v['lista'] = (isset($opt['lista'])) ? $opt['lista'] : NULL;
        */
        if($action == 'editar' || $action == 'habilitar' || $action == 'desabilitar') {

            $v['id'] = $data;

            if($action == 'editar')
                $v['opt'] = [
                    'bg'        => 'bg-warning',
                    'button'    => '<button class="btn btn-warning" type="submit"><i class="fa-solid fa-save"></i> Salvar</button>',
                    'title'     => 'Editar item - Tabela: '.$v['tabela'],
                    'disabled'  => '',
                    'action'    => 'editar',
                ];
            if($action == 'desabilitar')
                $v['opt'] = [
                    'bg'        => 'bg-danger',
                    'button'    => '<button class="btn btn-danger" type="submit"><i class="fa-solid fa-ban"></i> Desabilitar</button>',
                    'title'     => 'Desabilitar item - Tabela: '.$v['tabela'].' - Tem certeza que deseja desabilitar o item abaixo?',
                    'disabled'  => 'disabled',
                    'action'    => 'desabilitar',
                ];
            if($action == 'habilitar')
                $v['opt'] = [
                    'bg'        => 'bg-info',
                    'button'    => '<button class="btn btn-info" type="submit"><i class="fa-solid fa-circle-exclamation"></i> Habilitar</button>',
                    'title'     => 'Habilitar item - Tabela: '.$v['tabela'],
                    'disabled'  => 'disabled',
                    'action'    => 'habilitar',
                ];

        }
        else {

            if($v['tabela'] == 'Protocolo_Medicamento') {
                echo "<pre>";
                print_r($v['data']);
                echo "</pre>";
                #exit('oi');
                #echo '>>'.$v['data']['idTabPreschuap_Protocolo'] = ($data) ? $data : NULL;
                echo '>>'.$v['data']['idTabPreschuap_Protocolo'] = ($data) ? $data : NULL;
                $v['lista'] = $tabela->list_medicamento_bd($v['data']['idTabPreschuap_Protocolo']); #Carrega os itens da tabela Medicamentos
                $_SESSION['config']['class'] = 'col-12';

            }
            else
                $v['lista'] = $tabela->list_tabela_bd($v['tabela']); #Carrega os itens da tabela selecionada

            $v['opt'] = [
                'bg'        => 'bg-secondary',
                'button'    => '
                    <button class="btn btn-info" type="submit"><i class="fa-solid fa-plus"></i> Cadastrar</button>
                    <a class="btn btn-warning" href="'.base_url('tabela/list_tabela/Protocolo').'"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
                ',
                'title'     => 'Cadastrar item - Tabela: '.$v['tabela'],
                'disabled'  => '',
                'action'    => 'cadastrar',
            ];

        }

        if($v['tabela'] == 'ViaAdministracao')
            $v['tab']['colspan'] = 6;
        else
            $v['tab']['colspan'] = 5;

        if($v['tabela'] == 'Protocolo') {
            $v['select']['TipoTerapia']     = $tabela->list_tabela_bd('TipoTerapia', FALSE, FALSE, '*'); #Carrega os itens da tabela selecionada
            $v['select']['Categoria']       = $tabela->list_tabela_bd('Categoria', FALSE, FALSE, '*', 'idTabPreschuap_Categoria'); #Carrega os itens da tabela selecionada
            $v['select']['Aplicabilidade']  = ['CANCEROLOGIA', 'HEMATOLOGIA'];

        }
        if($v['tabela'] == 'Protocolo_Medicamento') {
            $v['select']['Medicamento']        = $tabela->list_tabela_bd('Medicamento'); #Carrega os itens da tabela selecionada
            $v['select']['EtapaTerapia']        = $tabela->list_tabela_bd('EtapaTerapia'); #Carrega os itens da tabela selecionada
            $v['select']['Medicamento']         = $tabela->list_tabela_bd('Medicamento'); #Carrega os itens da tabela selecionada
            $v['select']['UnidadeMedida']       = $tabela->list_tabela_bd('UnidadeMedida'); #Carrega os itens da tabela selecionada
            $v['select']['ViaAdministracao']    = $tabela->list_tabela_bd('ViaAdministracao'); #Carrega os itens da tabela selecionada
            $v['select']['Diluente']            = $tabela->list_tabela_bd('Diluente'); #Carrega os itens da tabela selecionada
            $v['select']['Posologia']           = $tabela->list_tabela_bd('Posologia'); #Carrega os itens da tabela selecionada
        }

        if($action == 'habilitar' || $action == 'desabilitar') {

            if(isset($v['data']['idTabPreschuap_'.$v['tabela']])) {

                $v['id'] = $v['data']['idTabPreschuap_'.$v['tabela']];
                $v['data']['Inativo'] = ($v['data']['action'] == 'desabilitar') ? 1 : 0;
                unset(
                    $v['data']['csrf_test_name'],
                    $v['data']['Item'],
                    $v['data']['idTabPreschuap_'.$v['tabela']],
                    $v['data']['action']
                );

                $v['campos'] = array_keys($v['data']);
                $v['anterior'] = $tabela->get_item($v['id'], $v['tabela']);

                /*
                echo "<pre>";
                print_r($v['select']['EtapaTerapia']);
                echo "</pre>";
                echo "<pre>";
                print_r($v['data']);
                echo "</pre>";
                exit('oi');
                #*/

                if($tabela->update_item($v['data'], $v['tabela'], $v['id']) ) {

                    $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabPreschuap_'.$v['tabela'], 'UPDATE', $v['id']), TRUE);
                    $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);

                    session()->setFlashdata('success', 'Item atualizado com sucesso!');
                    return redirect()->to('tabela/list_tabela/'.$v['tabela']);

                }
                else {

                    session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');
                    return redirect()->to('tabela/list_tabela/'.$v['tabela']);

                }

            }
            else {

                $v['data'] = $tabela->get_item($data, $v['tabela']);
                $v['data']['Item'] = $v['data'][$v['tabela']];

            }
        }
        else {

            if(isset($v['data']['Item'])) {

                if($v['tabela'] == 'ViaAdministracao')
                    #Critérios de validação
                    $inputs = $this->validate([
                        'Item'      => 'required',
                        'Codigo'    => ['label' => 'Abreviação', 'rules' => 'required'],
                    ]);
                elseif($v['tabela'] == 'Protocolo')
                    #Critérios de validação
                    $inputs = $this->validate([
                        'Item'                          => ['label' => 'Protocolo', 'rules' => 'required'],
                        'Aplicabilidade'                => 'required',
                        'idTabPreschuap_TipoTerapia'    => ['label' => 'Tipo de Terapia', 'rules' => 'required'],
                        'idTabPreschuap_Categoria'      => ['label' => 'Categoria', 'rules' => 'required'],
                        'Observacoes'                   => ['label' => 'Observações', 'rules' => 'required'],
                    ]);
                elseif($v['tabela'] == 'Protocolo_Medicamento')
                    #Critérios de validação
                    $inputs = $this->validate([
                        'Item'                              => ['label' => 'Medicamento', 'rules' => 'required'],
                        'idTabPreschuap_EtapaTerapia'       => 'required',
                        'Dose'                              => 'required',
                        'idTabPreschuap_UnidadeMedida'      => ['label' => 'Categoria', 'rules' => 'required'],
                        'idTabPreschuap_ViaAdministracao'   => ['label' => 'Via de Administração', 'rules' => 'required'],
                        'idTabPreschuap_Diluente'           => ['label' => 'Diluente', 'rules' => 'required'],
                        'Volume'                            => 'required',
                        'TempoInfusao'                      => 'required',
                        'idTabPreschuap_Posologia'          => ['label' => 'Observações', 'rules' => 'required'],

                    ]);
                else
                    #Critérios de validação
                    $inputs = $this->validate([
                        'Item' => 'required',
                    ]);
exit('cheguei');
                #Realiza a validação e retorna ao formulário se false
                if (!$inputs)
                    $v['validation'] = $this->validator;
                else {
exit('cheguei');
                    $action = $v['data']['action'];

                    $v['data'][$v['tabela']] = $v['data']['Item'];
                    if($v['tabela'] == 'ViaAdministracao')
                        $v['data']['Codigo'] = mb_strtoupper($v['data']['Codigo']);
                    if($v['tabela'] == 'Protocolo')
                        $v['data']['Protocolo'] = mb_strtoupper($v['data']['Item']);

                    unset(
                        $v['data']['csrf_test_name'],
                        $v['data']['Item'],
                        $v['data']['action']
                    );
                    /*
                    echo "<pre>";
                    print_r($v);
                    echo "</pre>";
                    echo "<pre>";
                    print_r($v['data']);
                    echo "</pre>";
                    exit('oi');
                    #*/
                    $v['campos'] = array_keys($v['data']);

                    if($action == 'editar') {

                        $v['id'] = $v['data']['idTabPreschuap_'.$v['tabela']];
                        $v['anterior'] = $tabela->get_item($v['id'], $v['tabela']);

                        if($tabela->update_item($v['data'], $v['tabela'], $v['id']) ) {

                            $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabPreschuap_'.$v['tabela'], 'UPDATE', $v['id']), TRUE);
                            $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);

                            session()->setFlashdata('success', 'Item atualizado com sucesso!');
                            return redirect()->to('tabela/list_tabela/'.$v['tabela']);

                        }
                        else {

                            session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');
                            return redirect()->to('tabela/list_tabela/'.$v['tabela']);

                        }

                    }
                    else {
                        $v['anterior'] = array();

                        $v['id'] = $tabela->insert_item($v['data'], $v['tabela']);

                        if($v['id']) {

                            $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabPreschuap_'.$v['tabela'], 'CREATE', $v['id']), TRUE);
                            $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria']), TRUE);

                            session()->setFlashdata('success', 'Item adicionado com sucesso!');
                            return redirect()->to('tabela/list_tabela/'.$v['tabela']);

                        }
                        else {

                            session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');
                            return redirect()->to('tabela/list_tabela/'.$v['tabela']);

                        }

                    }

                }

            }
            else {

                if($action == 'editar') {
                    $v['data'] = $tabela->get_item($data, $v['tabela']);
                    $v['data']['Item'] = $v['data'][$v['tabela']];
                }
                else
                    $v['data'] = [
                        'Item'                          =>  '',
                        'Codigo'                        =>  '',
                        'Aplicabilidade'                =>  '',
                        'idTabPreschuap_TipoTerapia'    =>  '',
                        'idTabPreschuap_Categoria'      =>  '',
                        'Observacoes'                   =>  '',

                        'idTabPreschuap_Protocolo'              => $data,
                        'idTabPreschuap_EtapaTerapia'           => '',
                        'idTabPreschuap_Medicamento'            => '',
                        'Dose'                                  => '',
                        'idTabPreschuap_UnidadeMedida'          => '',
                        'idTabPreschuap_ViaAdministracao'       => '',
                        'idTabPreschuap_Diluente'               => '',
                        'Volume'                                => '',
                        'TempoInfusao'                          => '',
                        'idTabPreschuap_Posologia'              => '',
                    ]; #iniciando as variávies para serem carregadas corretamente na página de lista de itens de tabela

            }

        }

        #/*
        echo "<pre>";
        #print_r($v['select']['EtapaTerapia']);
        echo "</pre>";
        echo "<pre>";
        print_r($v['data']);
        echo "</pre>";
        #exit('oi');
        #*/

        return view('admin/tabela/form_tabela', $v);

    }

}
