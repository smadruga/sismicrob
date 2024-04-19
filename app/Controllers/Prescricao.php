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
use App\Libraries\HUAP_Validation;

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

        /*
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        exit('oi'.$_SESSION['Paciente']['prontuario']);
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
                'idSismicrob_Tratamento'        => '',
                'DataInicioTratamento'          => '',
                'Duracao'                       => '',
                'DataFimTratamento'             => '',
                
                'DoseAtaque'                    => '',                
                'DosePosologica'                => '',
                'UnidadeMedida'                 => '',
                'DoseDiaria'                    => '',
                'Unidades'                      => '',
                'Peso'                          => '',
                'Creatinina'                    => '',
                'Clearance'                     => '',
                'Hemodialise'                   => '',
                
                'DiagnosticoInfecciosoOutro'    => '',
                'SubstituicaoMedicamento'       => '',
                'IndicacaoTipoCirurgia'         => '',
                'AntibioticoMantido'            => '',
                
                'Avaliacao'                             => '',
                'AvaliacaoDose'                         => '',
                'AvaliacaoDoseObs'                      => '',
                'AvaliacaoDuracao'                      => '',
                'AvaliacaoDuracaoObs'                   => '',
                'AvaliacaoIntervalo'                    => '',
                'AvaliacaoIntervaloObs'                 => '',
                'AvaliacaoIndicacao'                    => '',
                'AvaliacaoIndicacaoObs'                 => '',
                'AvaliacaoPreenchimentoInadequado'      => '',
                'AvaliacaoPreenchimentoInadequadoObs'   => '',
                'AvaliacaoOutros'                       => '',
                'AvaliacaoOutrosObs'                    => '',
                
                'AlteracaoPorAlta'              => '',
                
                'SubstituirTratamento'          => '',
                'SubstituidoPeloTratamento'     => '',
                
                'Justificativa'                 => '',
                'Suspender'                     => '',
                'SuspenderObs'                  => '',
                
                'Prorrogar'                     => '',
                'ProrrogarObs'                  => '',
                
                'idTabSismicrob_ViaAdministracao'       => '',
                'idTabSismicrob_Especialidade'          => '',
                'idTabSismicrob_DiagnosticoInfeccioso'  => '',
                'idTabSismicrob_Tratamento'             => '',
                'idTabSismicrob_Substituicao'           => '',
                'idTabSismicrob_Indicacao'              => '',
                'idTabSismicrob_Infeccao'               => '',
                'idTabSismicrob_Intervalo'              => '',
                'idTabSismicrob_AntibioticoMantido'     => '',

                'Medicamento'                       => '',

                'submit' => '',
            ];
            
        }
        else {
            #Captura os inputs do Formulário
            $v['data'] = array_map('trim', $this->request->getPostGet(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS));

            $v['data']['UnidadeMedida'] = (!isset($v['data']['UnidadeMedida'])) ? null : $v['data']['UnidadeMedida'];
        }

        if(($action == 'editar' || $action == 'excluir' || $action == 'concluir') && !$v['data']['submit']) {

            $v['idSismicrob_Tratamento'] = $id;
            $v['data'] = $prescricao->find($v['idSismicrob_Tratamento']); #Carrega os itens da tabela selecionada
            $v['data']['submit'] = '';

            $v['data']['Medicamento']       = $v['data']['CodigoMedicamento'];

            $v['data']['DosePosologica']    = str_replace(".",",",$v['data']['DosePosologica']);
            $v['data']['DoseDiaria']        = str_replace(".",",",$v['data']['DoseDiaria']).' '.$v['data']['UnidadeMedida'];
            
            $v['data']['Peso']              = str_replace(".",",",$v['data']['Peso']);
            $v['data']['Creatinina']        = str_replace(".",",",$v['data']['Creatinina']);
            $v['data']['Clearance']         = str_replace(".",",",$v['data']['Clearance']);

            $z = $tabela->get_item($v['data']['idTabSismicrob_Intervalo'], 'Intervalo'); #Carrega os itens da tabela selecionada
            $v['data']['idTabSismicrob_Intervalo'] = $z['Intervalo'].'#'.$z['Codigo'].'#'.$z['idTabSismicrob_Intervalo'];
            
            $z = $tabela->get_item_aghux($v['data']['Medicamento'], 'agh.afa_medicamentos', 'mat_codigo = '); #Carrega os itens da tabela selecionada
            $v['data']['Medicamento'] = $z['mat_codigo'].'#'.$z['descricao'];
        }

        #if(($action == 'excluir' || $action == 'concluir') && !$v['data']['submit']) { 
        if(
            #(($action == 'cadastrar' || $action == 'editar') && (!$v['data']['submit'] || $v['data']['submit'] == 1))
            (!$v['data']['submit']) || 
            (($action == 'cadastrar' || $action == 'editar') && (!$v['data']['submit'] || $v['data']['submit'] == 1))
            
        ) { 

            if ($v['data']['idTabSismicrob_Indicacao'] != 1) {
                $v['data']['mascara']['DoseAtaque'] = 'Dose de Ataque';
                $v['data']['mascara']['DoseDiaria'] = 'Dose diária';
                $v['data']['mascara']['Intervalo']  = 'Intervalo';
            }
            else {
                $v['data']['mascara']['DoseAtaque'] = 'Dose de indução anestésica';
                $v['data']['mascara']['DoseDiaria'] = 'Dose diária - repique intraoperatório';
                $v['data']['mascara']['Intervalo']  = 'Intervalo para repique intraoperatório';
            }

            $v['select'] = [
                'Medicamento'           => $tabela->list_medicamento_aghux(), #Carrega os itens da tabela selecionada
                'Indicacao'             => $tabela->list_tabela_bd('Indicacao', FALSE, FALSE, '*', 'idTabSismicrob_Indicacao', TRUE), #Carrega os itens da tabela selecionada
                'Intervalo'             => $tabela->list_tabela_bd('Intervalo', FALSE, FALSE, '*', 'idTabSismicrob_Intervalo', TRUE), #Carrega os itens da tabela selecionada
                'Especialidade'         => $tabela->list_tabela_bd('Especialidade', FALSE, FALSE, '*', 'idTabSismicrob_Especialidade', TRUE), #Carrega os itens da tabela selecionada
                'ViaAdministracao'      => $tabela->list_tabela_bd('ViaAdministracao', FALSE, FALSE, '*', 'idTabSismicrob_ViaAdministracao', TRUE), #Carrega os itens da tabela selecionada
                'DiagnosticoInfeccioso' => $tabela->list_tabela_bd('DiagnosticoInfeccioso', FALSE, FALSE, '*', 'idTabSismicrob_DiagnosticoInfeccioso', TRUE), #Carrega os itens da tabela selecionada
                'AntibioticoMantido'    => $tabela->list_tabela_bd('AntibioticoMantido', FALSE, FALSE, '*', 'idTabSismicrob_AntibioticoMantido', TRUE), #Carrega os itens da tabela selecionada
            ];
    
            $v['radio'] = array(
                'UnidadeMedida' => $v['func']->radio_checked($v['data']['UnidadeMedida'], 'UnidadeMedida', 'g|mg|UI', FALSE, TRUE, TRUE),
                'DoseAtaque'    => $v['func']->radio_checked($v['data']['DoseAtaque'], 'DoseAtaque', 'SN', 'N', FALSE, TRUE),
                'Hemodialise'   => $v['func']->radio_checked($v['data']['Hemodialise'], 'Hemodialise', 'SN', 'N', FALSE, TRUE),        );
    
            $v['div'] = array(
                'DoseAtaque'                            => $v['func']->radio_showhide($v['data']['DoseAtaque'], 'S'),
                'idTabSismicrob_DiagnosticoInfeccioso'  => $v['func']->div_showhide($v['data']['idTabSismicrob_DiagnosticoInfeccioso'], 'idTabSismicrob_DiagnosticoInfeccioso', '7'),
                'idTabSismicrob_Indicacao1'             => $v['func']->div_showhide($v['data']['idTabSismicrob_Indicacao'], 'idTabSismicrob_Indicacao', '1'),
                'idTabSismicrob_Indicacao3'             => $v['func']->div_showhide($v['data']['idTabSismicrob_Indicacao'], 'idTabSismicrob_Indicacao', '3'),
            );                  
        
        }

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
                'button'    => '<button class="btn btn-danger" id="submit" name="submit" value="2" type="submit"><i class="fa-solid fa-trash-can"></i> Excluir</button>',
                'title'     => 'Tem certeza que deseja excluir os dados abaixo? Essa operação não pode ser desfeita.',
                'disabled'  => 'disabled',
                'action'    => 'excluir',
            ];

        }
        elseif($action == 'concluir') {

            $v['opt'] = [
                'bg'        => 'bg-success',
                'button'    => '<button class="btn btn-success" id="submit" name="submit" value="2" type="submit"><i class="fa-solid fa-check-circle"></i> Concluir</button>',
                'title'     => 'Tem certeza que deseja concluir a prescrição abaixo?',
                'disabled'  => 'disabled',
                'action'    => 'concluir',
            ];

        }
        else {

            $v['opt'] = [
                'bg'        => 'bg-secondary',
                #'button'    => '<button class="btn btn-info" id="submit" name="submit" value="1" type="submit"><i class="fa-solid fa-circle-chevron-right"></i> Próximo</button>',
                'button'    => '<button type="submit" class="btn btn-primary" name="submit" value="1"><i class="fas fa-save" aria-hidden="true"></i> Salvar e Finalizar</button>
                <button type="submit" class="btn btn-info" name="submit2" value="2"><i class="fas fa-plus" aria-hidden="true"></i> Salvar e Incluir Outro Tratamento</button>',
                'title'     => 'Cadastrar Prescrição',
                'disabled'  => '',
                'action'    => 'cadastrar',
            ];

        }

        if($v['data']['submit']) {
            
            if($action == 'cadastrar' || $action == 'editar') {

                #Critérios de validação
                $inputs = $this->validate([
                    'idTabSismicrob_Indicacao'              => ['label' => 'Indicação', 'rules' => 'required'],
                    
                    'IndicacaoTipoCirurgia'                 => ['label' => 'Tipo de Cirurgia', 'rules' => 'required_if['.$v['data']['idTabSismicrob_Indicacao'].', 1]'],
                    'idTabSismicrob_AntibioticoMantido'     => ['label' => 'acima', 'rules' => 'required_if['.$v['data']['idTabSismicrob_Indicacao'].', 1]'],
                    
                    'idTabSismicrob_DiagnosticoInfeccioso'  => ['label' => 'Diagnóstico Infeccioso', 'rules' => 'required_if['.$v['data']['idTabSismicrob_Indicacao'].', 3]'],
                    'DiagnosticoInfecciosoOutro'            => 'required_if['.$v['data']['idTabSismicrob_DiagnosticoInfeccioso'].', 7]',

                    'Justificativa'                         => 'required_if['.$v['data']['idTabSismicrob_Indicacao'].', 3]',

                    'Medicamento'                           => 'required',
                    'DataInicioTratamento'                  => ['label' => 'Data de Início', 'rules' => 'required|valid_date[Y-m-d]'],
                    'Duracao'                               => ['label' => 'Duração', 'rules' => 'required|integer'],

                    'DosePosologica'                        => ['label' => 'acima', 'rules' => 'required|regex_match[/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\,)\d+)?$/]'],
                    'UnidadeMedida'                         => ['label' => 'Unidade de Medida', 'rules' => 'required'],
                    'idTabSismicrob_Intervalo'              => 'required',

                    'idTabSismicrob_ViaAdministracao'       => ['label' => 'acima', 'rules' => 'required'],
                    'idTabSismicrob_Especialidade'          => ['label' => 'Especialidade', 'rules' => 'required'],

                    'Peso'                                  => 'required|regex_match[/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\,)\d+)?$/]',
                    'Creatinina'                            => 'required|regex_match[/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\,)\d+)?$/]',

                ]);

            }
            else
                $inputs = '';

            /*
            #$db->getLastQuery();
            print "<pre>";
            #print_r($v);
            #print_r($v['select']['Medicamento']->getResultArray());
            #print_r($v['data']);
            #print_r($v['opt']);
            print_r($v['radio']);
            print "</pre>";
            exit('oioioioi '.$action).' <br> '. $inputs;
            #exit('???');
            #*/
            
            #Realiza a validação e retorna ao formulário se false
            if (!$inputs && ($action == 'cadastrar' || $action == 'editar')) {
                $v['validation'] = $this->validator;
            }
            else {

                #exit('oioioioi '.$v['data']['DataInicioTratamento']);

                if($action == 'cadastrar' || $action == 'editar') {
       
                    $v['data']['DosePosologica']                = str_replace(",",".",$v['data']['DosePosologica']);
                    $v['data']['DoseDiaria']                    = str_replace(",",".",$v['data']['DoseDiaria']);
                    $v['data']['Peso']                          = str_replace(",",".",$v['data']['Peso']);
                    $v['data']['Creatinina']                    = str_replace(",",".",$v['data']['Creatinina']);
                    $v['data']['Clearance']                     = str_replace(",",".",$v['data']['Clearance']);
                    $v['data']['idTabSismicrob_Tratamento']     = 1;
                    $v['data']['Prontuario']                    = $_SESSION['Paciente']['prontuario'];
                    $v['data']['CodigoAghux']                   = $_SESSION['Paciente']['codigo'];

                    $v['data']['idTabSismicrob_DiagnosticoInfeccioso']  = ($v['data']['idTabSismicrob_DiagnosticoInfeccioso']) ? $v['data']['idTabSismicrob_DiagnosticoInfeccioso'] : null;
                    $v['data']['idTabSismicrob_AntibioticoMantido']     = ($v['data']['idTabSismicrob_AntibioticoMantido']) ? $v['data']['idTabSismicrob_AntibioticoMantido'] : null;

                    if($v['data']['idTabSismicrob_Indicacao'] == 1) {
                        $v['data']['idTabSismicrob_DiagnosticoInfeccioso'] = null;
                        $v['data']['DiagnosticoInfecciosoOutro'] = null;
                    }
                    elseif($v['data']['idTabSismicrob_Indicacao'] == 3) {
                        $v['data']['IndicacaoTipoCirurgia'] = null;
                        $v['data']['idTabSismicrob_AntibioticoMantido'] = null;
                    }
                    else {
                        $v['data']['idTabSismicrob_DiagnosticoInfeccioso'] = null;
                        $v['data']['DiagnosticoInfecciosoOutro'] = null;
                        $v['data']['IndicacaoTipoCirurgia'] = null;
                        $v['data']['idTabSismicrob_AntibioticoMantido'] = null;                       
                    }                 

                    $l = explode('#', $v['data']['idTabSismicrob_Intervalo']);
                    $v['data']['idTabSismicrob_Intervalo'] = $l[2];

                    $l = explode('#', $v['data']['Medicamento']);
                    $v['data']['CodigoMedicamento'] = $l[0];
                    $v['data']['NomeMedicamento'] = $l[1];

                }
                if($action == 'concluir')
                    $v['data']['Concluido'] = 1;

                unset(
                    $v['data']['csrf_test_name'],
                    $v['data']['Idade'],
                    $v['data']['Sexo'],
                    $v['data']['submit'],
                    $v['data']['action'],
                    $v['data']['mascara'],
                    $v['data']['Medicamento'],
                    $v['data']['Intervalo'],
                );

                $v['campos'] = array_keys($v['data']);

                if($action == 'concluir') {


                    unset(
                        $v['data']['DataFimTratamento'],
                        $v['data']['DoseDiaria'],
                        $v['data']['Clearance'],
                        $v['data']['UnidadeMedida'],
                    );

                    $v['id'] = $v['data']['idSismicrob_Tratamento'];
                    $v['anterior'] = $prescricao->find($v['id']);

                    if($prescricao->update($v['id'], $v['data'])) {

                        $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Sismicrob_Tratamento', 'UPDATE', $v['id']), TRUE);
                        $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);                  

                        session()->setFlashdata('success', 'Item atualizado com sucesso!');

                    }
                    else
                        session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');

                }
                elseif($action == 'editar') {
                            
                    $v['id'] = $v['data']['idSismicrob_Tratamento'];
                    $v['anterior'] = $prescricao->find($v['id']);

                    if($prescricao->update($v['id'], $v['data']) ) {

                        $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Sismicrob_Tratamento', 'UPDATE', $v['id']), TRUE);
                        $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], TRUE), TRUE);
                                                    
                        session()->setFlashdata('success', 'Item atualizado com sucesso!');

                    }
                    else {
                        session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.');
                    }

                }
                elseif($action == 'excluir') {

                    /*
                    echo "<pre>";
                    print_r($v['data']);
                    echo "</pre>";
                    exit('oi');
                    #*/

                    $v['id'] = $v['data']['idSismicrob_Tratamento'];
                    $v['anterior'] = $prescricao->find($v['id']);
                    $v['campos'] = array_keys($v['anterior']);
                    $v['data'] = array();

                    if($prescricao->delete($v['id'])) {

                        $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Sismicrob_Tratamento', 'DELETE', $v['id']), TRUE);
                        $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], FALSE, TRUE), TRUE);

                        session()->setFlashdata('success', 'Item excluído com sucesso!');

                    }
                    else
                        session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação. ERRO: XPT3');

                }
                elseif($action == 'cadastrar') {

                    $v['anterior'] = array();

                    /*
                    echo "<pre>";
                    print_r($v['data']);
                    echo "</pre>";
                    exit('oi');
                    #*/

                    $v['id'] = $prescricao->insert($v['data']);

                    if($v['id']) {

                        $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('Sismicrob_Tratamento', 'CREATE', $v['id']), TRUE);       
                        $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria']), TRUE);
                                                
                        session()->setFlashdata('success', 'Item adicionado com sucesso!');
                        #return redirect()->to('prescricao/manage_medicamento/'.$v['id']);
                        return redirect()->to('prescricao/list_prescricao');

                    }
                    else
                        session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação.  ERRO: PRESCRIÇÃO-01');

                }
                else
                    session()->setFlashdata('failed', 'Não foi possível concluir a operação. Tente novamente ou procure o setor de Tecnologia da Informação. ERRO: PRESCRIÇÃO-02');


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
