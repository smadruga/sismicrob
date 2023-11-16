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
    * Desabilita item da tabela
    *
    * @return mixed
    */
    public function manage_item($tab = FALSE, $data = FALSE, $manage = FALSE)
    {

        $tabela = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $auditoria = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func'] = new HUAP_Functions(); #Inicia a classe de funções próprias

        $v['tabela'] = $tab;
        $v['id'] = $data;

        if($manage == 1) {
            $v['opt'] = [
                'form'      => 'manage_item',
                'bg'        => 'bg-danger',
                'button'    => '<button class="btn btn-danger" type="submit"><i class="fa-solid fa-ban"></i> Desabilitar</button>',
                'title'     => 'Desabilitar item - Tabela: '.$tab.' - Tem certeza que deseja desabilitar o item abaixo?',
                'disabled'  => 'disabled',
                'manage'    => '<input type="hidden" name="manage" value="1" />',
            ];
        }
        else {
            $v['opt'] = [
                'form'      => 'manage_item',
                'bg'        => 'bg-info',
                'button'    => '<button class="btn btn-info" type="submit"><i class="fa-solid fa-circle-exclamation"></i> Habilitar</button>',
                'title'     => 'Habilitar item - Tabela: '.$tab,
                'disabled'  => 'disabled',
                'manage'    => '<input type="hidden" name="manage" value="0" />',
            ];
        }

        #Captura os inputs do Formulário
        $v['data'] = $this->request->getVar(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(isset($v['data']['idTabPreschuap_'.$v['tabela']])) {

            $v['id'] = $v['data']['idTabPreschuap_'.$v['tabela']];
            $v['data']['Inativo'] = ($v['data']['manage'] == 1) ? 1 : 0;
            unset($v['data']['csrf_test_name'],$v['data']['Item'], $v['data']['idTabPreschuap_'.$v['tabela']], $v['data']['manage']);


            $v['campos'] = array_keys($v['data']);
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

            $v['data'] = $tabela->get_item($data, $tab);
            $v['data']['Item'] = $v['data'][$tab];

        }

        return view('admin/tabela/form_tabela', $v);
    }

    /**
    * Lista as prescrições associadas ao paciente
    *
    * @return mixed
    */
    public function edit_item($tab = FALSE, $data = FALSE)
    {

        $tabela = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $auditoria = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func'] = new HUAP_Functions(); #Inicia a classe de funções próprias

        $v['tabela'] = $tab;
        $v['id'] = $data;
        $v['opt'] = [
            'form'      => 'edit_item',
            'bg'        => 'bg-warning',
            'button'    => '<button class="btn btn-warning" type="submit"><i class="fa-solid fa-save"></i> Gravar</button>',
            'title'     => 'Editar item - Tabela: '.$tab,
            'disabled'  => '',
            'manage'    => '',
        ];

        #Captura os inputs do Formulário
        $v['data'] = array_map('trim', $this->request->getVar(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        if($v['tabela'] == 'ViaAdministracao')
            $v['tab']['colspan'] = 6;
        else
            $v['tab']['colspan'] = 5;

        if(isset($v['data']['Item'])) {

            if($v['tabela'] == 'ViaAdministracao')
                #Critérios de validação
                $inputs = $this->validate([
                    'Item' => 'required',
                    'Codigo' => ['label' => 'Abreviação', 'rules' => 'required'],
                ]);
            else
                #Critérios de validação
                $inputs = $this->validate([
                    'Item' => 'required',
                ]);

            #Realiza a validação e retorna ao formulário se false
            if (!$inputs)
                $v['validation'] = $this->validator;
            else {

                $v['data'][$v['tabela']] = $v['data']['Item'];
                $v['id'] = $v['data']['idTabPreschuap_'.$v['tabela']];
                if($v['tabela'] == 'ViaAdministracao')
                    $v['data']['Codigo'] = mb_strtoupper($v['data']['Codigo']);

                unset($v['data']['csrf_test_name'],$v['data']['Item'], $v['data']['idTabPreschuap_'.$v['tabela']]);

                $v['campos'] = array_keys($v['data']);
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

        }
        else {

            $v['data'] = $tabela->get_item($data, $tab);
            $v['data']['Item'] = $v['data'][$tab];

        }

        return view('admin/tabela/form_tabela', $v);
    }

    /**
    * Lista as prescrições associadas ao paciente
    *
    * @return mixed
    */
    public function list_tabela($data)
    {

        $tabela = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $auditoria = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func'] = new HUAP_Functions(); #Inicia a classe de funções próprias

        $v['lista'] = $tabela->list_tabela_bd($data); #Carrega os itens da tabela selecionada
        $v['tabela'] = $data;

        $v['opt'] = [
            'form'      => 'edit_item',
            'bg'        => 'bg-secondary',
            'button'    => '<button class="btn btn-primary" type="submit"><i class="fa-solid fa-plus"></i> Cadastrar</button>',
            'title'     => 'Cadastrar item - Tabela: '.$v['tabela'],
            'disabled'  => '',
            'manage'    => '',
        ];

        #Captura os inputs do Formulário
        $v['data'] = array_map('trim', $this->request->getVar(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        if($v['tabela'] == 'ViaAdministracao')
            $v['tab']['colspan'] = 6;
        else
            $v['tab']['colspan'] = 5;

        if(isset($v['data']['Item'])) {

            if($v['tabela'] == 'ViaAdministracao')
                #Critérios de validação
                $inputs = $this->validate([
                    'Item' => 'required',
                    'Codigo' => ['label' => 'Abreviação', 'rules' => 'required'],
                ]);
            else
                #Critérios de validação
                $inputs = $this->validate([
                    'Item' => 'required',
                ]);

            #Realiza a validação e retorna ao formulário se false
            if (!$inputs)
                $v['validation'] = $this->validator;
            else {

                $v['data'][$v['tabela']] = $v['data']['Item'];
                if($v['tabela'] == 'ViaAdministracao')
                    $v['data']['Codigo'] = mb_strtoupper($v['data']['Codigo']);

                unset($v['data']['csrf_test_name'],$v['data']['Item']);

                $v['campos'] = array_keys($v['data']);
                $v['anterior'] = array();

                $v['id'] = $tabela->insert_item($v['data'], $v['tabela']);

                $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabPreschuap_'.$v['tabela'], 'CREATE', $v['id']), TRUE);
                $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria']), TRUE);

                session()->setFlashdata('success', 'Item adicionado com sucesso!');
                return redirect()->to('tabela/form_tabela/'.$v['tabela']);

            }

        }
        else {
            $v['data']['Item'] = '';
            $v['data']['idTabPreschuap_Alergia'] = '';
        }

        /*
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        echo "<pre>";
        print_r($v['lista']);
        echo "</pre>";
        #exit('oi');
        #*/

        return view('admin/tabela/form_tabela', $v);

    }

    /**
    * Lista as prescrições associadas ao paciente
    *
    * @return mixed
    */
    public function list_tabela_bkp($data)
    {

        $tabela = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $auditoria = new AuditoriaModel(); #Inicia o objeto baseado na AuditoriaModel
        $auditorialog = new AuditoriaLogModel(); #Inicia o objeto baseado na AuditoriaLogModel
        $v['func'] = new HUAP_Functions(); #Inicia a classe de funções próprias

        $v['lista'] = $tabela->list_tabela_bd($data); #Carrega os itens da tabela selecionada
        $v['tabela'] = $data;

        #Captura os inputs do Formulário
        $v['data'] = array_map('trim', $this->request->getVar(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        if($v['tabela'] == 'ViaAdministracao')
            $v['tab']['colspan'] = 6;
        else
            $v['tab']['colspan'] = 5;

        if(isset($v['data']['Item'])) {

            if($v['tabela'] == 'ViaAdministracao')
                #Critérios de validação
                $inputs = $this->validate([
                    'Item' => 'required',
                    'Codigo' => ['label' => 'Abreviação', 'rules' => 'required'],
                ]);
            else
                #Critérios de validação
                $inputs = $this->validate([
                    'Item' => 'required',
                ]);

            #Realiza a validação e retorna ao formulário se false
            if (!$inputs)
                $v['validation'] = $this->validator;
            else {

                $v['data'][$v['tabela']] = $v['data']['Item'];
                if($v['tabela'] == 'ViaAdministracao')
                    $v['data']['Codigo'] = mb_strtoupper($v['data']['Codigo']);

                unset($v['data']['csrf_test_name'],$v['data']['Item']);

                $v['campos'] = array_keys($v['data']);
                $v['anterior'] = array();

                $v['id'] = $tabela->insert_item($v['data'], $v['tabela']);

                $v['auditoria'] = $auditoria->insert($v['func']->create_auditoria('TabPreschuap_'.$v['tabela'], 'CREATE', $v['id']), TRUE);
                $v['auditoriaitem'] = $auditorialog->insertBatch($v['func']->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria']), TRUE);

                session()->setFlashdata('success', 'Item adicionado com sucesso!');
                return redirect()->to('tabela/list_tabela/'.$v['tabela']);

            }

        }

        return view('admin/tabela/list_tabela', $v);

        /*
        echo "<pre>";
        print_r($v['data']);
        echo "</pre>";
        exit('oi');
        #*/

    }


}
