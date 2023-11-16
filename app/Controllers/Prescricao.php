<?php

namespace App\Controllers;

use App\Models\TabelaModel;
use App\Models\PrescricaoModel;
use App\Models\PrescricaoMedicamentoModel;

use App\Models\AuditoriaModel;
use App\Models\AuditoriaLogModel;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\HUAP_Functions;

class Prescricao extends BaseController
{
    private $v;

    public function __construct()
    {

    }

    /**
    * Lista as prescrições associadas ao paciente
    *
    * @return mixed
    */
    public function list_prescricao()
    {

        $prescricao = new PrescricaoModel();
        $medicamento = new PrescricaoMedicamentoModel();

        $v['pager'] = \Config\Services::pager();
        $request = \Config\Services::request();
        #Inicia a classe de funções próprias
        $v['func'] = new HUAP_Functions();

        $v['prescricao'] = $prescricao->read_prescricao($_SESSION['Paciente']['prontuario']);

        if($v['prescricao']['count'] > 0) {

            $m['where'] = null;
            foreach($v['prescricao']['array'] as $val) {
                $m['where'] .= $val['idPreschuap_Prescricao'].', ';
                $m['medicamento'][$val['idPreschuap_Prescricao']] = NULL;
            }
            $m['where'] = substr($m['where'], 0, -2);

            $v['medicamento'] = $medicamento->read_medicamento($m);

        }

        /*
        echo "<pre>";
        print_r($v['prescricao']);
        echo "</pre>";
        echo "<pre>";
        print_r($v['medicamento']);
        echo "</pre>";
        #exit('oi'.$_SESSION['Paciente']['prontuario']);
        #*/

        return view('admin/prescricao/list_prescricao', $v);

    }

    /**
    * Gera a versão para impressão da Prescrição Médica
    *
    * @return mixed
    */
    public function print_prescricao($data)
    {

        $prescricao = new PrescricaoModel();
        $medicamento = new PrescricaoMedicamentoModel();

        #Inicia a classe de funções próprias
        $v['func'] = new HUAP_Functions();

        $v['prescricao'] = $prescricao->read_prescricao($data, TRUE);

        if($v['prescricao']['count'] > 0) {

            $m['where'] = $data;
            $m['medicamento'][$data] = NULL;

            $v['medicamento'] = $medicamento->read_medicamento($m);

        }

        $v['prescricao']['conselho'] = $prescricao->get_conselho($v['prescricao']['array'][0]['Cpf']);

        /*
        echo "<pre>";
        print_r($v['medicamento']);
        echo "</pre>";
        exit('oi');
        #*/

        return view('admin/prescricao/print_prescricao', $v);

    }

    /**
    * Direciona para a página onde o usuário escolhe entre criar uma nova prescrição
    * ou carregar a última prescrição do paciente.
    *
    * @return void
    */
    public function page_prescricao()
    {
        return view('admin/prescricao/page_prescricao');
    }

    /**
    * Cria, edita, excluir e gerencia uma prescrição
    *
    * @return void
    */
    public function manage_prescricao($action = FALSE, $id = FALSE)
    {

        $tabela         = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $prescricao     = new PrescricaoModel(); #Inicia o objeto baseado na TabelaModel
        $medicamento    = new PrescricaoMedicamentoModel(); #Inicia o objeto baseado na TabelaModel
        $auditoria      = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog   = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func']      = new HUAP_Functions(); #Inicia a classe de funções próprias

        $action = (!$action) ? $this->request->getVar('action', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : $action;

        if(!$this->request->getVar(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
            $v['data'] = [
                'idPreschuap_Prescricao'            => '',
                'Prontuario'                        => '',
                'DataMarcacao'                      => '',
                'DataPrescricao'                    => date('d/m/Y', time()),
                'Dia'                               => '',
                'Ciclo'                             => '',
                'Aplicabilidade'                    => '',
                'idTabPreschuap_Categoria'          => '',
                'idTabPreschuap_Subcategoria'       => '',
                'idTabPreschuap_Protocolo'          => '',
                'idTabPreschuap_TipoTerapia'        => '',
                'CiclosTotais'                      => '',
                'EntreCiclos'                       => '',

                'Peso'                              => '',
                'CreatininaSerica'                  => '',
                'Altura'                            => '',
                'ClearanceCreatinina'               => '',
                'IndiceMassaCorporal'               => '',
                'SuperficieCorporal'                => '',

                #'DescricaoServico'                  => '',
                'InformacaoComplementar'            => '',
                'ReacaoAdversa'                     => '',
                'Alergia'            => '',
                'ClearanceCreatinina'               => '',
                'IndiceMassaCorporal'               => '',
                'SuperficieCorporal'                => '',

                'submit'                            => '',
            ];
            $v['data']['Aplicabilidade'] = (!isset($v['data']['Aplicabilidade'])) ? NULL : $v['data']['Aplicabilidade'];
        }
        else {
            #Captura os inputs do Formulário
            $v['data'] = array_map('trim', $this->request->getVar(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            #echo '111111111oi';
            #$v['data']['Aplicabilidade'] = (!isset($v['data']['Aplicabilidade'])) ? NULL : $v['data']['Aplicabilidade'];
        }

        if(($action == 'editar' || $action == 'excluir' || $action == 'concluir') && !$v['data']['submit']) {

            $v['idPreschuap_Prescricao'] = $id;
            $v['data'] = $prescricao->find($v['idPreschuap_Prescricao']); #Carrega os itens da tabela selecionada
            $v['data']['submit'] = '';

            $v['data']['ClearanceCreatinina']   = (!$v['data']['ClearanceCreatinina']) ? $v['func']->calc_ClearanceCreatinina($v['data']['Peso'], $_SESSION['Paciente']['idade'], $_SESSION['Paciente']['sexo'], $v['data']['CreatininaSerica']) : $v['data']['ClearanceCreatinina'];
            $v['data']['IndiceMassaCorporal']   = (!$v['data']['IndiceMassaCorporal']) ? $v['func']->calc_IndiceMassaCorporal($v['data']['Peso'], $v['data']['Altura']) : $v['data']['IndiceMassaCorporal'];
            $v['data']['SuperficieCorporal']    = (!$v['data']['SuperficieCorporal']) ? $v['func']->calc_SuperficieCorporal($v['data']['Peso'], $v['data']['Altura']) : $v['data']['SuperficieCorporal'];

            $v['data']['DataPrescricao']        = date("d/m/Y", strtotime($v['data']['DataPrescricao']));

            $v['data']['Peso']                  = str_replace(".",",",$v['data']['Peso']);
            $v['data']['CreatininaSerica']      = str_replace(".",",",$v['data']['CreatininaSerica']);

            $v['data']['ClearanceCreatinina']   = str_replace(".",",",$v['data']['ClearanceCreatinina']);
            $v['data']['IndiceMassaCorporal']   = str_replace(".",",",$v['data']['IndiceMassaCorporal']);
            $v['data']['SuperficieCorporal']    = str_replace(".",",",$v['data']['SuperficieCorporal']);
        }

        $v['select'] = [
            'Categoria'         => $tabela->list_tabela_bd('Categoria',     FALSE, FALSE, '*', 'idTabPreschuap_Categoria', TRUE), #Carrega os itens da tabela selecionada
            'Subcategoria'      => $tabela->list_tabela_bd('Subcategoria',  FALSE, FALSE, '*', 'idTabPreschuap_Subcategoria', TRUE), #Carrega os itens da tabela selecionada
            'Protocolo'         => $tabela->list_tabela_bd('Protocolo',     FALSE, FALSE, '*', FALSE, TRUE), #Carrega os itens da tabela selecionada
            'TipoTerapia'       => $tabela->list_tabela_bd('TipoTerapia',   FALSE, FALSE, '*', FALSE, TRUE), #Carrega os itens da tabela selecionada
            'Aplicabilidade'    => ['CANCEROLOGIA', 'HEMATOLOGIA'],
        ];

        if($action == 'editar') {

            $v['opt'] = [
                'bg'        => 'bg-warning',
                'button'    => '<button class="btn btn-info" id="submit" name="submit" value="1" type="submit"><i class="fa-solid fa-save"></i> Salvar</button>',
                'title'     => 'Editar Prescrição',
                'disabled'  => '',
                'action'    => 'editar',
            ];

        }
        elseif($action == 'excluir') {

            $v['opt'] = [
                'bg'        => 'bg-danger',
                'button'    => '<button class="btn btn-danger" id="submit" name="submit" value="1" type="submit"><i class="fa-solid fa-trash-can"></i> Excluir</button>',
                'title'     => 'Tem certeza que deseja excluir os dados abaixo? Essa operação não pode ser desfeita.',
                'disabled'  => 'disabled',
                'action'    => 'excluir',
            ];

        }
        elseif($action == 'concluir') {

            $v['opt'] = [
                'bg'        => 'bg-success',
                'button'    => '<button class="btn btn-success" id="submit" name="submit" value="1" type="submit"><i class="fa-solid fa-check-circle"></i> Concluir</button>',
                'title'     => 'Tem certeza que deseja concluir a prescrição abaixo?',
                'disabled'  => 'disabled',
                'action'    => 'concluir',
            ];

        }
        else {

            $v['opt'] = [
                'bg'        => 'bg-secondary',
                'button'    => '<button class="btn btn-info" id="submit" name="submit" value="1" type="submit"><i class="fa-solid fa-circle-chevron-right"></i> Próximo</button>',
                'title'     => 'Cadastrar Prescrição',
                'disabled'  => '',
                'action'    => 'cadastrar',
            ];

        }

        if($v['data']['submit']) {

            if($action == 'cadastrar' || $action == 'editar') {
                #Critérios de validação
                $inputs = $this->validate([
                    'DataPrescricao'                    => ['label' => 'Data da Prescrição', 'rules' => 'required|valid_date[d/m/Y]'],
                    'Dia'                               => 'required|integer',
                    'Ciclo'                             => 'required|integer',
                    'Aplicabilidade'                    => 'required',
                    'idTabPreschuap_Categoria'          => ['label' => 'CID Categoria', 'rules' => 'required'],
                    #'idTabPreschuap_Subcategoria'       => ['label' => 'CID Subcategoria', 'rules' => 'required'],
                    'idTabPreschuap_Protocolo'          => ['label' => 'Protocolo', 'rules' => 'required'],
                    #'idTabPreschuap_TipoTerapia'        => ['label' => 'Tipo de Terapia', 'rules' => 'required'],
                    'CiclosTotais'                      => ['label' => 'Total de Ciclos', 'rules' => 'required|integer'],
                    'EntreCiclos'                       => ['label' => 'Entre Ciclos', 'rules' => 'required|integer'],

                    'Peso'                              => 'required|regex_match[/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/]',
                    'CreatininaSerica'                  => ['label' => 'Creatinina Sérica', 'rules' => 'required|regex_match[/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/]'],
                    'Altura'                            => 'required|integer',

                    #'DescricaoServico'                  => ['label' => 'Serviço', 'rules' => 'required'],
                    #'InformacaoComplementar'            => ['label' => 'Informação Complementar', 'rules' => 'required'],
                    #'ReacaoAdversa'                     => ['label' => 'Reação Adversa', 'rules' => 'required'],
                    #'Alergia'            => ['label' => 'Alergia', 'rules' => 'required'],
                ]);
            }
            else
                $inputs = '';

            #Realiza a validação e retorna ao formulário se false
            if (!$inputs && ($action == 'cadastrar' || $action == 'editar'))
                $v['validation'] = $this->validator;
            else {

                if($action == 'cadastrar' || $action == 'editar') {

                    $v['data']['DataPrescricao']        = date("Y-m-d", strtotime(str_replace('/', '-', $v['data']['DataPrescricao'])));

                    $v['data']['Peso']                  = str_replace(",",".",$v['data']['Peso']);
                    $v['data']['CreatininaSerica']      = str_replace(",",".",$v['data']['CreatininaSerica']);
                    $v['data']['ClearanceCreatinina']   = str_replace(",",".",$v['data']['ClearanceCreatinina']);
                    $v['data']['IndiceMassaCorporal']   = str_replace(",",".",$v['data']['IndiceMassaCorporal']);
                    $v['data']['SuperficieCorporal']    = str_replace(",",".",$v['data']['SuperficieCorporal']);

                    $v['data']['Prontuario']            = $_SESSION['Paciente']['prontuario'];
                    $v['data']['idSishuap_Usuario']     = $_SESSION['Sessao']['idSishuap_Usuario'];

                    $v['medicamento'] = $tabela->list_medicamento_bd($v['data']['idTabPreschuap_Protocolo'], TRUE);

                    $v['data']['idTabPreschuap_Subcategoria']   = ($v['data']['idTabPreschuap_Subcategoria']) ? $v['data']['idTabPreschuap_Subcategoria'] : NULL;
                    $v['data']['idTabPreschuap_TipoTerapia']    = ($v['data']['idTabPreschuap_TipoTerapia']) ? $v['data']['idTabPreschuap_TipoTerapia'] : NULL;
                    #$v['data']['idTabPreschuap_Alergia']        = ($v['data']['idTabPreschuap_Alergia']) ? $v['data']['idTabPreschuap_Alergia'] : NULL;

                }
                if($action == 'concluir')
                    $v['data']['Concluido'] = 1;

                unset(
                    $v['data']['csrf_test_name'],
                    $v['data']['Idade'],
                    $v['data']['Sexo'],
                    $v['data']['submit'],
                    $v['data']['action'],
                );
            
                $v['campos'] = array_keys($v['data']);

                if($action == 'concluir') {

                    $v['id'] = $v['data']['idPreschuap_Prescricao'];
                    $v['anterior'] = $prescricao->find($v['id']);

                    if($prescricao->update($v['id'], $v['data']) ) {

                        $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Preschuap_Prescricao', 'UPDATE', $v['id']), TRUE);
                        $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);

                        session()->setFlashdata('success', 'Item atualizado com sucesso!');

                    }
                    else
                        session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');

                }
                elseif($action == 'editar') {
                            
                    $v['id'] = $v['data']['idPreschuap_Prescricao'];
                    $v['anterior'] = $prescricao->find($v['id']);

                    if($prescricao->update($v['id'], $v['data']) ) {

                        $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Preschuap_Prescricao', 'UPDATE', $v['id']), TRUE);
                        $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);
                        
                        if($v['anterior']['idTabPreschuap_Protocolo'] && ($v['anterior']['idTabPreschuap_Protocolo'] != $v['data']['idTabPreschuap_Protocolo'])) {

                            if($medicamento->where('idPreschuap_Prescricao', $v['id'])->delete()) {

                                $v['medicamento'] = $tabela->list_medicamento_bd($v['data']['idTabPreschuap_Protocolo'], TRUE);
        
                                $i=0;
                                foreach ($v['medicamento']->getResultArray() as $val) {
                                    /*
                                    echo "<pre>";
                                    print_r($val);
                                    echo "</pre>";
                                    echo '<br> >>'.$i;
                                    #*/
        
                                    $val['idPreschuap_Prescricao'] = $v['id'];
                                    $v['campos'] = array_keys($val);
        
                                    if($val['idTabPreschuap_Formula'] == 2)
                                        $val['Calculo'] = ($val['Dose']*$v['data']['Peso']);
                                    elseif($val['idTabPreschuap_Formula'] == 4)
                                        $val['Calculo'] = $v['func']->calc_DoseCarboplatina($val['Dose'], $v['data']['ClearanceCreatinina']);
                                    elseif($val['idTabPreschuap_Formula'] == 3)
                                        $val['Calculo'] = ($val['Dose']*$v['data']['SuperficieCorporal']);
                                    else
                                        $val['Calculo'] = $val['Dose'];
        
                                    if(isset($val['CalculoLimiteMaximo']) && ($val['Calculo'] > $val['CalculoLimiteMaximo']))
                                        $val['Calculo'] = $val['CalculoLimiteMaximo'];
                                    elseif(isset($val['CalculoLimiteMinimo']) && ($val['Calculo'] > $val['CalculoLimiteMinimo']))
                                        $val['Calculo'] = $val['CalculoLimiteMinimo'];
                                    
                                    $val['Calculo'] = str_replace(",",".",$val['Calculo']);
                                    
                                    $v['mid'] = $medicamento->insert($val);
        
                                    if($v['mid']) {
                                        $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Preschuap_Prescricao_Medicamento', 'CREATE', $v['mid']), TRUE);
                                        $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $val, $v['campos'], $v['mid'], $v['auditoria']), TRUE);
                                    }
                                    $i++;
        
                                }
                            }
                            else {
                                exit('ERRO. CONTATE O SETOR DE TI');
                            }
                                
                        }
                            
                    session()->setFlashdata('success', 'Item atualizado com sucesso!');

                    }
                    else {
                        session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');
                    }

                }
                elseif($action == 'excluir') {

                    $v['id'] = $v['data']['idPreschuap_Prescricao'];
                    $v['anterior'] = $prescricao->find($v['id']);
                    $v['campos'] = array_keys($v['anterior']);
                    $v['data'] = array();

                    if($medicamento->where('idPreschuap_Prescricao', $v['id'])->delete() && $prescricao->delete($v['id'])) {

                        $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Preschuap_Prescricao', 'DELETE', $v['id']), TRUE);
                        $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], FALSE, TRUE), TRUE);

                        session()->setFlashdata('success', 'Item excluído com sucesso!');

                    }
                    else
                        session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');

                }
                elseif($action == 'cadastrar') {

                    $v['anterior'] = array();

                    $v['id'] = $prescricao->insert($v['data']);

                    if($v['id']) {

                        $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Preschuap_Prescricao', 'CREATE', $v['id']), TRUE);
                        $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria']), TRUE);
                        $i=0;
                        foreach ($v['medicamento']->getResultArray() as $val) {
                            /*
                            echo "<pre>";
                            print_r($val);
                            echo "</pre>";
                            echo '<br> >>'.$i;
                            exit('###');
                            #*/

                            $val['idPreschuap_Prescricao'] = $v['id'];
                            $v['campos'] = array_keys($val);

                            if($val['idTabPreschuap_Formula'] == 2)
                                $val['Calculo'] = ($val['Dose']*$v['data']['Peso']);
                            elseif($val['idTabPreschuap_Formula'] == 4)
                                $val['Calculo'] = $v['func']->calc_DoseCarboplatina($val['Dose'], $v['data']['ClearanceCreatinina']);
                            elseif($val['idTabPreschuap_Formula'] == 3)
                                $val['Calculo'] = ($val['Dose']*$v['data']['SuperficieCorporal']);
                            else
                                $val['Calculo'] = $val['Dose'];
   
                            if(isset($val['CalculoLimiteMaximo']) && ($val['Calculo'] > $val['CalculoLimiteMaximo']))
                                $val['Calculo'] = $val['CalculoLimiteMaximo'];
                            elseif(isset($val['CalculoLimiteMinimo']) && ($val['Calculo'] > $val['CalculoLimiteMinimo']))
                                $val['Calculo'] = $val['CalculoLimiteMinimo'];
                            
                            $val['Calculo'] = str_replace(",",".",$val['Calculo']);
                            
                            $v['mid'] = $medicamento->insert($val);

                            if($v['mid']) {
                                $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Preschuap_Prescricao_Medicamento', 'CREATE', $v['mid']), TRUE);
                                $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $val, $v['campos'], $v['mid'], $v['auditoria']), TRUE);
                            }
                            $i++;

                        }
                        
                        session()->setFlashdata('success', 'Item adicionado com sucesso!');
                        return redirect()->to('prescricao/manage_medicamento/'.$v['id']);

                    }
                    else
                        session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');

                }
                else
                    session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação. ERRO: PRESCRIÇÃO-01');


                if($action == 'editar' || $action == 'cadastrar')
                    return redirect()->to('prescricao/manage_medicamento/'.$v['id']);
                else
                    return redirect()->to('prescricao/list_prescricao');

            }

        }

        /*
        echo "<pre>";
        print_r($_SESSION['Paciente']);
        echo "</pre>";
        echo "<pre>";
        print_r($v['data']);
        echo "</pre>";
        exit('oi');
        #*/

        return view('admin/prescricao/form_prescricao', $v);
    }

    /**
    * Cria, edita, excluir e gerencia uma prescrição
    *
    * @return void
    */
    public function manage_medicamento($id = FALSE)
    {

        $tabela         = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $prescricao     = new PrescricaoModel(); #Inicia o objeto baseado na TabelaModel
        $medicamento    = new PrescricaoMedicamentoModel(); #Inicia o objeto baseado na TabelaModel
        $auditoria      = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog   = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func']      = new HUAP_Functions(); #Inicia a classe de funções próprias

        $v['idPreschuap_Prescricao']    = ($id) ? $id : $this->request->getVar('idPreschuap_Prescricao');
        $v['data']['prescricao']        = $prescricao->read_prescricao($v['idPreschuap_Prescricao'], TRUE, TRUE); #Carrega os itens da tabela selecionada
        $v['data']['medicamento']       = $medicamento->read_medicamento($v['idPreschuap_Prescricao']); #Carrega os itens da tabela selecionada
        $v['data']['submit']            = '';

        $v['opt']['Peso']                  = str_replace(",",".",$v['data']['prescricao']['Peso']);
        $v['opt']['CreatininaSerica']      = str_replace(",",".",$v['data']['prescricao']['CreatininaSerica']);

        if(!$this->request->getVar(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {

            $v['data']['input'] = array();
            foreach ($v['data']['medicamento'] as $key => $val) {
                $v['data']['input'][$key]['idPreschuap_Prescricao_Medicamento'] = $val['idPreschuap_Prescricao_Medicamento'];

                $v['data']['input'][$key]['TipoAjuste']                         = $val['TipoAjuste'];
                $v['data']['input'][$key]['idTabPreschuap_MotivoAjusteDose']    = $val['idTabPreschuap_MotivoAjusteDose'];
                $v['data']['input'][$key]['Calculo']                            = $val['Calculo2'];

                if($val['Ajuste2'] == 0 || !$val['Ajuste2'])
                    $v['data']['input'][$key]['Ajuste'] = NULL;
                elseif($val['TipoAjuste'] == "porcentagem")
                    $v['data']['input'][$key]['Ajuste'] = intval($val['Ajuste']);
                else
                    $v['data']['input'][$key]['Ajuste'] = $val['Ajuste'];

            }

            $v['data']['input']['submit'] = '';

        }
        else {
            #Captura os inputs do Formulário
            $v['data']['input'] = array_map('trim', $this->request->getVar(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS));

            for ($i = 0; $i < count($v['data']['medicamento']); $i++) {
                $v['data']['input'][$i]['idPreschuap_Prescricao_Medicamento'] = $v['data']['input']['idPreschuap_Prescricao_Medicamento'.$i];
                $v['data']['input'][$i]['Ajuste']                             = $v['data']['input']['Ajuste'.$i];
                $v['data']['input'][$i]['TipoAjuste']                         = $v['data']['input']['TipoAjuste'.$i];
                $v['data']['input'][$i]['idTabPreschuap_MotivoAjusteDose']    = $v['data']['input']['idTabPreschuap_MotivoAjusteDose'.$i];
                $v['data']['input'][$i]['Calculo']                            = $v['data']['input']['Calculo'.$i];

                $inputs = $this->validate(['Ajuste'.$i => ['label' => 'Ajuste', 'rules' => 'permit_empty|regex_match[/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/]']]);
            }
        }
        
        $v['opt'] = [
            'bg'        => 'bg-warning',
            'button'    => '<button class="btn btn-info" id="submit" name="submit" value="1" type="submit"><i class="fa-solid fa-save"></i> Salvar</button>',
            'title'     => 'Ajustar doses',
            'disabled'  => '',
            'action'    => 'editar',
        ];

        $v['select'] = [
            'TipoAjuste'        => [
                'porcentagem'   => 'Porcentagem sobre o cálulo final',
                'substituicao'  => 'Substituir cálculo final pelo ajuste'
            ],
            'MotivoAjusteDose'  => $tabela->list_tabela_bd('MotivoAjusteDose', FALSE, FALSE, '*', FALSE, TRUE), #Carrega os itens da tabela selecionada
        ];
        
        if($v['data']['input']['submit']) {

            #Realiza a validação e retorna ao formulário se false
            if (!$inputs)
                $v['validation'] = $this->validator;
            else {

                for ($i = 0; $i < count($v['data']['medicamento']); $i++) {
                    $v['data']['bd'][$i]['idPreschuap_Prescricao_Medicamento'] = $v['data']['input'][$i]['idPreschuap_Prescricao_Medicamento'];

                    $v['data']['bd'][$i]['TipoAjuste']                      = $v['data']['input'][$i]['TipoAjuste'];
                    $v['data']['bd'][$i]['idTabPreschuap_MotivoAjusteDose'] =
                        (!$v['data']['input'][$i]['idTabPreschuap_MotivoAjusteDose'] || $v['data']['input'][$i]['idTabPreschuap_MotivoAjusteDose'] == 0) ? NULL : $v['data']['input'][$i]['idTabPreschuap_MotivoAjusteDose'];
                    $v['data']['bd'][$i]['Ajuste']                          = str_replace(",",".",$v['data']['input'][$i]['Ajuste']);
                    $v['data']['bd'][$i]['Calculo']                         = str_replace(",",".",$v['data']['input'][$i]['Calculo']);

                    $v['anterior'][$i]['TipoAjuste']                        = $v['data']['medicamento'][$i]['TipoAjuste'];
                    $v['anterior'][$i]['idTabPreschuap_MotivoAjusteDose']                  = $v['data']['medicamento'][$i]['idTabPreschuap_MotivoAjusteDose'];
                    $v['anterior'][$i]['Ajuste']                            = str_replace(",",".",$v['data']['medicamento'][$i]['Ajuste']);
                    $v['anterior'][$i]['Calculo']                           = str_replace(",",".",$v['data']['medicamento'][$i]['Calculo2']);

                    if(
                           $v['data']['bd'][$i]['TipoAjuste']                       != $v['anterior'][$i]['TipoAjuste']
                        || $v['data']['bd'][$i]['idTabPreschuap_MotivoAjusteDose']  != $v['anterior'][$i]['idTabPreschuap_MotivoAjusteDose']
                        || $v['data']['bd'][$i]['Ajuste']                           != $v['anterior'][$i]['Ajuste']
                        || $v['data']['bd'][$i]['Calculo']                          != $v['anterior'][$i]['Calculo']
                        )
                        $v['diff'][$i] = 1;
                }

                if($medicamento->updateBatch($v['data']['bd'], 'idPreschuap_Prescricao_Medicamento')) {

                    $i=0;
                    foreach ($v['data']['bd'] as $val) {

                        if (isset($v['diff'][$i])) {
                            $v['id'] = $val['idPreschuap_Prescricao_Medicamento'];
                            unset($val['idPreschuap_Prescricao_Medicamento']);

                            $v['campos'] = array_keys($val);

                            $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Preschuap_Protocolo_Medicamento', 'UPDATE', $v['id']), TRUE);
                            $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'][$i], $val, $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);
                        }

                        $i++;
                    }

                    session()->setFlashdata('success', 'Dados atualizados com sucesso!');

                }
                else
                    session()->setFlashdata('nothing', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');

                return redirect()->to('prescricao/list_prescricao');

            }

        }
        /*
        echo "<pre>";
        print_r($v['data']);
        echo "</pre>";
        echo "<pre>";
        print_r($v['select']);
        echo "</pre>";
        #exit('oi');
        #*/

        return view('admin/prescricao/form_medicamento', $v);

    }

    /**
    * Cria, edita, excluir e gerencia uma prescrição
    *
    * @return void
    */
    public function copy_prescricao($id = FALSE, $continue = FALSE)
    {

        $tabela         = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $prescricao     = new PrescricaoModel(); #Inicia o objeto baseado na TabelaModel
        $medicamento    = new PrescricaoMedicamentoModel(); #Inicia o objeto baseado na TabelaModel
        $auditoria      = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog   = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func']      = new HUAP_Functions(); #Inicia a classe de funções próprias

        if(!$id)
            $id = $prescricao->get_last_id($_SESSION['Paciente']['prontuario']);

        $v['data']['prescricao']    = $prescricao->find($id); #Carrega os itens da tabela selecionada
        unset($v['data']['prescricao']['idPreschuap_Prescricao'],$v['data']['prescricao']['Concluido']);
        $v['data']['medicamento']   = $medicamento->where('idPreschuap_Prescricao', $id)->findAll(); #Carrega os itens da tabela selecionada

        if($continue) {
            $v['data']['prescricao']['DataPrescricao'] = date('Y-m-d', time());
            $v['data']['prescricao']['Dia']++;
            $v['data']['prescricao']['Ciclo']++;
        }

        #alterações específicas para o comando COPIAR PRESCRIÇÃO
        $v['data']['prescricao']['InformacaoComplementar'] = '';

        $v['campos'] = array_keys($v['data']['prescricao']);
        $v['anterior'] = array();

        $v['id']['prescricao'] = $prescricao->insert($v['data']['prescricao']); #insere os dados e recebe o id de retorno

        if($v['id']['prescricao']) {

            $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Preschuap_Prescricao', 'CREATE', $v['id']['prescricao']), TRUE);
            $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data']['prescricao'], $v['campos'], $v['id']['prescricao'], $v['auditoria']), TRUE);

            foreach ($v['data']['medicamento'] as $key => $val) {

                $v['anterior'] = array();
                unset($val['idPreschuap_Prescricao_Medicamento']);
                $val['idPreschuap_Prescricao'] = $v['id']['prescricao']; #Insere o id da prescrição gerado no primeiro insert
                $v['campos'] = array_keys($val);
                $val['idTabPreschuap_MotivoAjusteDose'] = ($val['idTabPreschuap_MotivoAjusteDose'] == 0) ? NULL : $val['idTabPreschuap_MotivoAjusteDose'];
                $v['id']['medicamento'] = $medicamento->insert($val); #insere os dados e recebe o id de retorno

                if($v['id']) {
                    $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Preschuap_Prescricao_Medicamento', 'CREATE', $v['id']['medicamento']), TRUE);
                    $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $val, $v['campos'], $v['id']['medicamento'], $v['auditoria']), TRUE);
                }

            }

            session()->setFlashdata('success', 'Dados atualizados com sucesso!');
        }
        else
            session()->setFlashdata('nothing', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');

        return redirect()->to('prescricao/list_prescricao');

        /*
        echo "<pre>";
        print_r($val);
        echo "</pre>";
        echo "<pre>";
        print_r($v['campos']);
        echo "</pre>";
        #exit('oi');
        #*/

    }

}
