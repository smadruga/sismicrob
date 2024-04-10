<?php

namespace App\Controllers;

use App\Models\TabelaModel;
use App\Models\PrescricaoModel;
use App\Models\AtendimentoModel;
use App\Models\CulturaModel;

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
    * Apresenta na tela o TSA do paciente.
    *
    * @return mixed
    */
    public function show_tsa($solicitacao, $ordem)
    {

        $cultura = new CulturaModel();
        $v['func'] = new HUAP_Functions();

        $v['tsa'] = $cultura->show_paciente_tsa ($_SESSION['Paciente']['prontuario'], $solicitacao, $ordem);
        
        /*
        echo "<pre>";
        print_r($v['tsa']);
        echo "</pre>";
        #exit('oi >><< '.$v['tsa']['antimicrobiano'][20113][1]);
        #*/

        return view('admin/prescricao/page_tsa', $v);

    }

    /**
    * Lista os resultados de cultura do AGHUX associados ao paciente
    *
    * @return mixed
    */
    public function list_cultura()
    {

        $cultura = new CulturaModel();
        $v['func'] = new HUAP_Functions();

        $v['cultura'] = $cultura->list_paciente_cultura ($_SESSION['Paciente']['prontuario']);

        /*
        echo "<pre>";
        print_r($v['cultura']['tsa']);
        echo "</pre>";
        #exit('oi'.$_SESSION['Paciente']['prontuario'].' == '.$v['cultura']['tsa'][191332][10]);
        #*/

        return view('admin/prescricao/list_cultura', $v);

    }

    /**
    * Lista os atendimentos do AGHUX associados ao paciente
    *
    * @return mixed
    */
    public function list_atendimento()
    {

        $atendimento = new AtendimentoModel();
        $v['func'] = new HUAP_Functions();

        $v['atendimento'] = $atendimento->list_paciente_atendimento ($_SESSION['Paciente']['prontuario']);

        /*
        echo "<pre>";
        print_r($v['atendimento']);
        echo "</pre>";
        exit('oi'.$_SESSION['Paciente']['prontuario']);
        #*/

        return view('admin/prescricao/list_atendimento', $v);

    }


    /**
    * Lista as prescrições associadas ao paciente
    *
    * @return mixed
    */
    public function list_prescricao()
    {

        $prescricao = new PrescricaoModel();

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

        #Inicia a classe de funções próprias
        $v['func'] = new HUAP_Functions();

        $v['prescricao'] = $prescricao->read_prescricao($data, TRUE);

        if($v['prescricao']['count'] > 0) {

            $m['where'] = $data;
            $m['medicamento'][$data] = NULL;

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
        $auditoria      = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog   = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func']      = new HUAP_Functions(); #Inicia a classe de funções próprias

        $action = (!$action) ? $this->request->getPostGet('action', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : $action;

        if(!$this->request->getVar(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
            $v['data'] = [
                'idSismicrob_Tratamento' => '',
                'Codigo' => '',
                'Medicamento' => '',
                'DataInicioTratamento' => '',
                'Duracao' => '',
                'DataFimTratamento' => '',
                
                'DoseAtaque' => '',                
                'DosePosologica' => '',
                'UnidadeMedida' => '',
                'IntervaloUnidade' => '',
                'DoseDiaria' => '',
                'Unidades' => '',
                'Peso' => '',
                'Creatinina' => '',
                'Clearance' => '',
                'Hemodialise' => '',
                
                'DiagnosticoInfecciosoOutro' => '',
                'SubstituicaoMedicamento' => '',
                'IndicacaoTipoCirurgia' => '',
                
                'Avaliacao' => '',
                'AvaliacaoDose' => '',
                'AvaliacaoDoseObs' => '',
                'AvaliacaoDuracao' => '',
                'AvaliacaoDuracaoObs' => '',
                'AvaliacaoIntervalo' => '',
                'AvaliacaoIntervaloObs' => '',
                'AvaliacaoIndicacao' => '',
                'AvaliacaoIndicacaoObs' => '',
                'AvaliacaoPreenchimentoInadequado' => '',
                'AvaliacaoPreenchimentoInadequadoObs' => '',
                'AvaliacaoOutros' => '',
                'AvaliacaoOutrosObs' => '',
                
                'AlteracaoPorAlta' => '',
                
                'SubstituirTratamento' => '',
                'SubstituidoPeloTratamento' => '',
                
                'Justificativa' => '',
                'Suspender' => '',
                'SuspenderObs' => '',
                
                'Prorrogar' => '',
                'ProrrogarObs' => '',
                
                'idTabSismicrob_Produto' => '',
                'idTabSismicrob_ViaAdministracao' => '',
                'idTabSismicrob_Especialidade' => '',
                'idTabSismicrob_DiagnosticoInfeccioso' => '',
                'idTabSismicrob_Tratamento' => '',
                'idTabSismicrob_Substituicao' => '',
                'idTabSismicrob_Indicacao' => '',
                'idTabSismicrob_Infeccao' => '',
                'idTabSismicrob_Intervalo' => '',

                'submit' => '',
            ];
            echo '111112222111oi<br>';
        }
        else {
            #Captura os inputs do Formulário
            $v['data'] = array_map('trim', $this->request->getPostGet(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS));

            $v['data']['UnidadeMedida'] = (!isset($v['data']['UnidadeMedida'])) ? null : $v['data']['UnidadeMedida'];
            #$v['data']['DoseAtaque'] = (!isset($v['data']['DoseAtaque'])) ? null : $v['data']['DoseAtaque'];
            #$v['data']['Hemodialise'] = (!isset($v['data']['Hemodialise'])) ? null : $v['data']['Hemodialise'];

            echo '111111111oi<br>';
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
            'Medicamento'           => $tabela->list_medicamento_aghux(), #Carrega os itens da tabela selecionada
            'Indicacao'             => $tabela->list_tabela_bd('Indicacao', FALSE, FALSE, '*', 'idTabSismicrob_Indicacao', TRUE), #Carrega os itens da tabela selecionada
            'Intervalo'             => $tabela->list_tabela_bd('Intervalo', FALSE, FALSE, '*', 'idTabSismicrob_Intervalo', TRUE), #Carrega os itens da tabela selecionada
            'Especialidade'         => $tabela->list_tabela_bd('Especialidade', FALSE, FALSE, '*', 'idTabSismicrob_Especialidade', TRUE), #Carrega os itens da tabela selecionada
            'ViaAdministracao'      => $tabela->list_tabela_bd('ViaAdministracao', FALSE, FALSE, '*', 'idTabSismicrob_ViaAdministracao', TRUE), #Carrega os itens da tabela selecionada
            'DiagnosticoInfeccioso' => $tabela->list_tabela_bd('DiagnosticoInfeccioso', FALSE, FALSE, '*', 'idTabSismicrob_DiagnosticoInfeccioso', TRUE), #Carrega os itens da tabela selecionada
        ];

        #/*
        #$t = $v['func']->radio_checked('mg', 'UnidadeMedida', 'g|mg|UI', FALSE, TRUE, TRUE);
        print "<pre>";
        print_r($v['data']);
        print "</pre>";
        #exit('q?');
        #*/

        $v['radio'] = array(
            'UnidadeMedida' => $v['func']->radio_checked($v['data']['UnidadeMedida'], 'UnidadeMedida', 'g|mg|UI', FALSE, TRUE, TRUE),
            'DoseAtaque'    => $v['func']->radio_checked($v['data']['DoseAtaque'], 'DoseAtaque', 'SN', 'N', FALSE, TRUE),
            'Hemodialise'   => $v['func']->radio_checked($v['data']['Hemodialise'], 'Hemodialise', 'SN', 'N', FALSE, TRUE),
        );

        $v['div'] = array(
            'DoseAtaque' => $v['func']->radio_showhide($v['data']['DoseAtaque'], 'S'),
            'idTabSismicrob_DiagnosticoInfeccioso' => $v['func']->div_showhide($v['data']['idTabSismicrob_DiagnosticoInfeccioso'], 'idTabSismicrob_DiagnosticoInfeccioso', '7'),
            'idTabSismicrob_Indicacao1' => $v['func']->div_showhide($v['data']['idTabSismicrob_Indicacao'], 'idTabSismicrob_Indicacao', '1'),
            'idTabSismicrob_Indicacao3' => $v['func']->div_showhide($v['data']['idTabSismicrob_Indicacao'], 'idTabSismicrob_Indicacao', '3'),
        );        

                    /*
                    print "<pre>";
                    print_r($v);
                    print "</pre>";
                    exit('???');
                    #*/

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

                exit('oioioioi');

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

    
}
