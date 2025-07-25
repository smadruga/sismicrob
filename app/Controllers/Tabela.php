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
    * Reorganiza a Ordem de Infusão dos medicamentos de um protocolo caso haja duplicidade
    *
    * @return void
    */
    public function sort_medicamento($protocolo, $interno = FALSE)
    {
        $tabela = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $auditoria = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func'] = new HUAP_Functions(); #Inicia a classe de funções próprias

        $v['sort'] = $tabela->get_item_sort($protocolo);

        $i=0;
        $n=1;
        $v['data'] = array();
        foreach ($v['sort'] as $val) { #aplica a nova ordem de infusão

            $v['data'][$i]['idTabSismicrob_Protocolo_Medicamento'] = $val['idTabSismicrob_Protocolo_Medicamento'];
            if($val['Inativo'])
                $v['data'][$i]['OrdemInfusao'] = NULL;
            else {
                $v['data'][$i]['OrdemInfusao'] = $n++;
            }
            $i++;

        }

        /*
        #echo $db->getLastQuery();
        echo "<pre>";
        print_r($v['sort']);
        echo "</pre>";
        echo "<pre>";
        print_r($v['data']);
        echo "</pre>";
        exit('oi');
        #*/

        #aplico o update
        if($interno) {
            if($tabela->update_item_sort($v['data'])) {
                $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabSismicrob_Protocolo_Medicamento', 'RE-SORT', $protocolo), TRUE);
                return TRUE;
            }
            else
                return FALSE;

        }
        else {
            if($tabela->update_item_sort($v['data'])) {
                $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabSismicrob_Protocolo_Medicamento', 'RE-SORT', $protocolo), TRUE);
                session()->setFlashdata('success', 'Item atualizado com sucesso!');
            }
            else
                session()->setFlashdata('nochange', 'Sem alterações.');

            return redirect()->to('tabela/list_tabela/Protocolo_Medicamento/cadastrar/'.$protocolo);
        }

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

                $v['id'] = $val['idTabSismicrob_Protocolo_Medicamento'];
                unset($val['idTabSismicrob_Protocolo_Medicamento']);

                $v['campos'] = array_keys($val);
                $v['anterior'] = $val;

                $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabSismicrob_Protocolo_Medicamento', 'UPDATE', $v['id']), TRUE);
                $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'][$i], $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);

                $i++;
            }

            session()->setFlashdata('success', 'Item atualizado com sucesso!');

        }
        else
            session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');

        return redirect()->to('tabela/list_tabela/Protocolo_Medicamento/cadastrar/'.$id);

    }

    /**
    * Lista as prescrições associadas ao paciente
    *
    * @return mixed
    */
    public function list_tabela($tab = FALSE, $action = FALSE, $data = FALSE, $protocolo = FALSE)
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
            /*echo "<pre>";
            print_r($v['data']);
            echo "</pre>";
            exit('oi2');
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
                    'button'    => '<div class="col-md-12"><button class="btn btn-warning" id="submit" type="submit"><i class="fa-solid fa-save"></i> Salvar</button></div>',
                    'title'     => 'Editar item - Tabela: '.$v['tabela'],
                    'disabled'  => '',
                    'action'    => 'editar',
                ];
            if($action == 'desabilitar')
                $v['opt'] = [
                    'bg'        => 'bg-danger',
                    'button'    => '<div class="col-md-12"><button class="btn btn-danger" id="submit" type="submit"><i class="fa-solid fa-ban"></i> Desabilitar</button></div>',
                    'title'     => 'Desabilitar item - Tabela: '.$v['tabela'].' - Tem certeza que deseja desabilitar o item abaixo?',
                    'disabled'  => 'disabled',
                    'action'    => 'desabilitar',
                ];
            if($action == 'habilitar')
                $v['opt'] = [
                    'bg'        => 'bg-success',
                    'button'    => '<div class="col-md-12"><button class="btn btn-success" id="submit" type="submit"><i class="fa-solid fa-circle-check"></i> Habilitar</button></div>',
                    'title'     => 'Habilitar item - Tabela: '.$v['tabela'],
                    'disabled'  => 'disabled',
                    'action'    => 'habilitar',
                ];

        }
        else {
            
            $protmed = '';
            if($v['tabela'] == 'Protocolo_Medicamento') {
                $v['data']['idTabSismicrob_Protocolo'] = ($data) ? $data : $v['data']['idTabSismicrob_Protocolo'];
                $v['lista'] = $tabela->list_medicamento_bd($v['data']['idTabSismicrob_Protocolo']);
                $v['protocolo'] = $tabela->get_item($v['data']['idTabSismicrob_Protocolo'], 'Protocolo'); #Carrega os itens da tabela Medicamentos
                $_SESSION['config']['class'] = 'col-12';
                $protmed = 'Protocolo: '.$v['protocolo']['Protocolo'].' - ';

                $v['count'] = $v['lista']['count'];
                $v['lista'] = $v['lista']['lista'];

                /*
                echo "<pre>";
                print_r($v['lista']);
                echo "</pre>";
                echo "<pre>";
                #print_r($v['lista']['count']);
                echo "</pre>";
                echo $v['count'];
                exit('oi');
                #*/
            }
            else
                $v['lista'] = $tabela->list_tabela_bd($v['tabela']); #Carrega os itens da tabela selecionada

            $v['opt'] = [
                'bg'        => 'bg-secondary',
                'button'    => '
                    <div class="col-md-12">
                        <button class="btn btn-info" id="submit" type="submit"><i class="fa-solid fa-plus"></i> Cadastrar</button>
                        <a class="btn btn-warning" href="'.base_url('tabela/list_tabela/Protocolo').'"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
                    </div>
                ',
                'title'     => $protmed.'Cadastrar item - Tabela: '.$v['tabela'],
                'disabled'  => '',
                'action'    => 'cadastrar',
            ];

        }

        if($v['tabela'] == 'ViaAdministracao' || $v['tabela'] == 'Intervalo')
            $v['tab']['colspan'] = 6;
        else
            $v['tab']['colspan'] = 5;

        $notinativo = ($action == 'habilitar' || $action == 'desabilitar') ? FALSE : TRUE;
        if($v['tabela'] == 'Protocolo') {
            $v['select']['TipoTerapia']     = $tabela->list_tabela_bd('TipoTerapia', FALSE, FALSE, '*', FALSE, $notinativo); #Carrega os itens da tabela selecionada
            $v['select']['Categoria']       = $tabela->list_tabela_bd('Categoria', FALSE, FALSE, '*', 'idTabSismicrob_Categoria'); #Carrega os itens da tabela selecionada
            $v['select']['Aplicabilidade']  = ['CANCEROLOGIA', 'HEMATOLOGIA'];

        }
        if($v['tabela'] == 'Protocolo_Medicamento') {
            $v['select']['Medicamento']         = $tabela->list_tabela_bd('Medicamento', FALSE, FALSE, FALSE, FALSE, $notinativo); #Carrega os itens da tabela selecionada
            $v['select']['EtapaTerapia']        = $tabela->list_tabela_bd('EtapaTerapia', FALSE, FALSE, FALSE, FALSE, $notinativo); #Carrega os itens da tabela selecionada
            $v['select']['Medicamento']         = $tabela->list_tabela_bd('Medicamento', FALSE, FALSE, FALSE, FALSE, $notinativo); #Carrega os itens da tabela selecionada
            $v['select']['UnidadeMedida']       = $tabela->list_tabela_bd('UnidadeMedida', FALSE, FALSE, FALSE, FALSE, $notinativo); #Carrega os itens da tabela selecionada
            $v['select']['ViaAdministracao']    = $tabela->list_tabela_bd('ViaAdministracao', FALSE, FALSE, FALSE, FALSE, $notinativo); #Carrega os itens da tabela selecionada
            $v['select']['Diluente']            = $tabela->list_tabela_bd('Diluente', FALSE, FALSE, FALSE, FALSE, $notinativo); #Carrega os itens da tabela selecionada
            $v['select']['Posologia']           = $tabela->list_tabela_bd('Posologia', FALSE, FALSE, FALSE, FALSE, $notinativo); #Carrega os itens da tabela selecionada
        }

        if($action == 'habilitar' || $action == 'desabilitar') {

            if(isset($v['data']['idTabSismicrob_'.$v['tabela']])) {

                $v['id'] = $v['data']['idTabSismicrob_'.$v['tabela']];
                $v['data']['Inativo'] = ($v['data']['action'] == 'desabilitar') ? 1 : 0;
                unset(
                    $v['data']['csrf_test_name'],
                    $v['data']['Item'],
                    $v['data']['idTabSismicrob_'.$v['tabela']],
                    $v['data']['action']
                );

                $v['campos'] = array_keys($v['data']);
                $v['anterior'] = $tabela->get_item($v['id'], $v['tabela']);

                /*
                echo "<pre>";
                print_r($v['tabela']);
                echo "</pre>";
                echo "<pre>";
                print_r($v['id']);
                echo "</pre>";
                echo "<pre>";
                print_r($v['data']);
                echo "</pre>";
                exit('oi');
                #*/

                if($tabela->update_item($v['data'], $v['tabela'], $v['id']) ) {

                    if ($v['tabela'] == 'Protocolo_Medicamento')
                        $this->sort_medicamento($v['data']['idTabSismicrob_Protocolo'], TRUE);

                    $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabSismicrob_'.$v['tabela'], 'UPDATE', $v['id']), TRUE);
                    $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);

                    session()->setFlashdata('success', 'Item atualizado com sucesso!');
                }
                else
                    session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');

                if ($v['tabela'] == 'Protocolo_Medicamento')
                    return redirect()->to('tabela/list_tabela/Protocolo_Medicamento/cadastrar/'.$v['data']['idTabSismicrob_Protocolo']);
                else
                    return redirect()->to('tabela/list_tabela/'.$v['tabela']);


            }
            else {

                $v['data'] = $tabela->get_item($data, $v['tabela']);
                $v['data']['Item'] = ($v['tabela'] == 'Protocolo_Medicamento') ? $v['data']['idTabSismicrob_Medicamento'] : $v['data'][$v['tabela']];

            }
        }
        else {
            
            /*
            echo "<pre>";
            print_r($v['data']);
            echo "</pre>";
            exit('oi');
            #*/

            if(isset($v['data']['Item'])) {

                if($v['tabela'] == 'ViaAdministracao' || ['tabela'] == 'Intervalo')
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
                        'idTabSismicrob_TipoTerapia'    => ['label' => 'Tipo de Terapia', 'rules' => 'required'],
                        'idTabSismicrob_Categoria'      => ['label' => 'Categoria', 'rules' => 'required'],
                        'Observacoes'                   => ['label' => 'Observações', 'rules' => 'required'],
                    ]);
                elseif($v['tabela'] == 'Protocolo_Medicamento') {
                    #Critérios de validação
                    $inputs = $this->validate([
                        'Item'                              => ['label' => 'Medicamento', 'rules' => 'required'],
                        'idTabSismicrob_EtapaTerapia'       => ['label' => 'Etapa da Terapia', 'rules' => 'required'],
                        'Dose'                              => 'required|regex_match[/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/]',
                        'idTabSismicrob_UnidadeMedida'      => ['label' => 'Unidade de Medida', 'rules' => 'required'],
                        'idTabSismicrob_ViaAdministracao'   => ['label' => 'Via de Administração', 'rules' => 'required'],
                        'idTabSismicrob_Posologia'          => ['label' => 'Posologia', 'rules' => 'required'],
                    ]);
                    if($v['data']['idTabSismicrob_ViaAdministracao'] == 2)
                        $inputs = $this->validate([
                            'idTabSismicrob_Diluente'           => ['label' => 'Diluente', 'rules' => 'required'],
                            'Volume'                            => 'required|regex_match[/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/]',
                            'TempoInfusao'                      => ['label' => 'Tempo de Infusão', 'rules' => 'required'],

                        ]);
                }
                else
                    #Critérios de validação
                    $inputs = $this->validate([
                        'Item' => 'required',
                    ]);

                #Realiza a validação e retorna ao formulário se false
                if (!$inputs)
                    $v['validation'] = $this->validator;
                else {

                    $action = $v['data']['action'];

                    $v['data'][$v['tabela']] = $v['data']['Item'];
                    if($v['tabela'] == 'ViaAdministracao' || $v['tabela'] == 'Intervalo')
                        $v['data']['Codigo'] = mb_strtoupper($v['data']['Codigo']);
                    if($v['tabela'] == 'Protocolo')
                        $v['data']['Protocolo'] = mb_strtoupper($v['data']['Item']);
                    if($v['tabela'] == 'Protocolo_Medicamento') {
                        $v['data']['idTabSismicrob_Medicamento'] = $v['data']['Item'];
                        $v['data']['Dose'] = str_replace(",",".",$v['data']['Dose']);
                        $v['data']['Volume'] = str_replace(",",".",$v['data']['Volume']);
                        $v['data']['idTabSismicrob_Diluente'] = (!$v['data']['idTabSismicrob_Diluente'] || $v['data']['idTabSismicrob_Diluente'] == '') ? 
                            NULL : $v['data']['idTabSismicrob_Diluente'];
                        unset($v['data'][$v['tabela']]);
                    }

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

                        $v['id'] = $v['data']['idTabSismicrob_'.$v['tabela']];
                        $v['anterior'] = $tabela->get_item($v['id'], $v['tabela']);

                        if($tabela->update_item($v['data'], $v['tabela'], $v['id']) ) {

                            $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabSismicrob_'.$v['tabela'], 'UPDATE', $v['id']), TRUE);
                            $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);

                            session()->setFlashdata('success', 'Item atualizado com sucesso!');

                        }
                        else
                            session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');

                    }
                    else {
                        $v['anterior'] = array();

                        $v['id'] = $tabela->insert_item($v['data'], $v['tabela']);

                        if($v['id']) {

                            $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabSismicrob_'.$v['tabela'], 'CREATE', $v['id']), TRUE);
                            $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria']), TRUE);

                            session()->setFlashdata('success', 'Item adicionado com sucesso!');

                        }
                        else
                            session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');

                    }

                    if ($v['tabela'] == 'Protocolo_Medicamento')
                        return redirect()->to('tabela/list_tabela/Protocolo_Medicamento/cadastrar/'.$v['data']['idTabSismicrob_Protocolo']);
                    else
                        return redirect()->to('tabela/list_tabela/'.$v['tabela']);

                }

            }
            else {

                if($action == 'editar') {
                    $v['data'] = $tabela->get_item($data, $v['tabela']);

                    if ($v['tabela'] == 'Protocolo_Medicamento') {
                        $v['data']['Item'] = $v['data']['idTabSismicrob_Medicamento'];
                        $v['data']['Dose'] = ($v['data']['Dose']) ? str_replace(".",",",$v['data']['Dose']) : $v['data']['Dose'];
                        $v['data']['Volume'] = ($v['data']['Volume']) ? str_replace(".",",",$v['data']['Volume']) : $v['data']['Volume'];
                    }
                    else
                        $v['data']['Item'] = $v['data'][$v['tabela']];
                }
                else {
                    $v['data'] = [
                        'Item'                          =>  '',
                        'Codigo'                        =>  '',
                        'Aplicabilidade'                =>  '',
                        'idTabSismicrob_TipoTerapia'    =>  '',
                        'idTabSismicrob_Categoria'      =>  '',
                        'Observacoes'                   =>  '',

                        'idTabSismicrob_Protocolo'              => $data,
                        'idTabSismicrob_EtapaTerapia'           => '',
                        'idTabSismicrob_Medicamento'            => '',
                        'Dose'                                  => '',
                        'idTabSismicrob_UnidadeMedida'          => '',
                        'idTabSismicrob_ViaAdministracao'       => '',
                        'idTabSismicrob_Diluente'               => '',
                        'Volume'                                => '',
                        'TempoInfusao'                          => '',
                        'idTabSismicrob_Posologia'              => '',
                    ]; #iniciando as variávies para serem carregadas corretamente na página de lista de itens de tabela

                    if($v['tabela'] == 'Protocolo_Medicamento')
                        $v['data']['OrdemInfusao'] = (isset($v['data']['OrdemInfusao'])) ? $v['data']['OrdemInfusao'] : $v['count']+1 ;

                }


            }

        }

        /*
        echo "<pre>";
        print_r($v['tabela']);
        echo "</pre>";
        echo "<pre>";
        print_r($v['data']);
        echo "</pre>";
        #echo $v['lista']->getNumRows();
        #exit('oi');
        #*/

        return view('admin/tabela/form_tabela', $v);

    }

}
