<?php

namespace App\Controllers;

use App\Models\PacienteModel;

use App\Models\AuditoriaModel;
use App\Models\AuditoriaLogModel;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\HUAP_Functions;

class Paciente extends BaseController
{
    private $v;

    public function __construct()
    {

    }

    /**
    * Tela inicial do preschuapweb
    *
    * @return void
    */
    public function index()
    {
        return view('admin/paciente/form_pesquisa_paciente');
    }

    /**
    * Formulário para busca de usuário a ser importado do AD/EBSERH
    *
    * @return void
    */
    public function find_paciente()
    {

        if (!isset($_SESSION['Sessao']['Perfil'][1]) && !isset($_SESSION['Sessao']['Perfil'][2]) )
            return redirect()->to('admin/');

        return view('admin/paciente/form_pesquisa_paciente');
    }

    /**
    * Valida o formulário de busca e retorna um ou mais resultados
    *
    * @return mixed
    */
    public function get_paciente($paciente = false)
    {
        
        if(!$paciente) {

            #Captura os inputs do Formulário
            $v = $this->request->getVar(['Pesquisar']);

            #Critérios de validação
            $inputs = $this->validate([
                'Pesquisar' => 'required',
            ]);

            #Realiza a validação e retorna ao formulário se false
            if (!$inputs) {
                return view('admin/paciente/form_pesquisa_paciente', [
                    'validation' => $this->validator
                ]);
            }

        }
        else
            $v['Pesquisar'] = $paciente;
            
        $paciente = new PacienteModel();
        $v['paciente'] = $paciente->get_paciente_bd($v['Pesquisar']);
        
        #Inicia a classe de funções próprias
        $v['func'] = new HUAP_Functions();
        
        /*
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        #exit('<br />oi');
        #*/

        #se o resultado for zero retorna erro
        if (!$v['paciente']) {
            session()->setFlashdata('failed', 'Nenhum paciente encontrado. Tente novamente.');
            return redirect()->to('paciente/find_paciente');
        }
        #se o resultado for um vai direto para a página de importação
        elseif ($v['paciente']['count'] == 1)
            return redirect()->to('paciente/show_paciente/'.$v['paciente']['array'][0]['codigo']);
        #se o resultado for mais que um vai para uma lista de opções
        else {
            $_SESSION['pager']['count'] = $v['paciente']['count'];
            $_SESSION['pager']['Pesquisar'] = $v['Pesquisar'];
            return redirect()->to('paciente/list_paciente/list');
        }
            #return view('admin/paciente/list_paciente', $v);



        return view('admin/paciente/form_pesquisa_paciente');
    }

    /**
    * Importa o usuário do AD/EBSERH e salva os dados básicos no BD PRESCHUAP
    *
    * @return mixed
    */
    public function show_paciente($data)
    {

        $paciente = new PacienteModel();

        $_SESSION['Paciente'] = $paciente->get_paciente_codigo($data);
        #Inicia a classe de funções próprias
        $v['func'] = new HUAP_Functions();

        /*
        echo "<pre>";
        print_r($_SESSION['Paciente']);
        echo "</pre>";
        #exit();
        #*/

        return view('admin/paciente/page_paciente', $v);

    }

    /**
    * Lista os perfis atribuídos ao usuário
    *
    * @return mixed
    */
    public function list_paciente()
    {

        $paciente = new PacienteModel();
        $v['pager'] = \Config\Services::pager();
        $request = \Config\Services::request();
        #Inicia a classe de funções próprias
        $v['func'] = new HUAP_Functions();

        $v['page'] = ($request->getVar('page')) ? $request->getVar('page', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 1;

        #Captura usuário a ser importado
        $v['perpage'] = 25;
        $v['paciente'] = $paciente->get_paciente_bd($_SESSION['pager']['Pesquisar'], $v['perpage'], ($v['perpage']*($v['page']-1)));

        /*
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        #exit($v['data']['Usuario']);
        #*/

        return view('admin/paciente/list_paciente', $v);

    }

    /******************* FUNÇÕES AUXILIARES *************************/

}
