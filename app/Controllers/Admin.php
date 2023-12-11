<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\PerfilModel;
use App\Models\TabPerfilModel;
use App\Models\AuditoriaModel;
use App\Models\AuditoriaLogModel;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\HUAP_Functions;

class Admin extends BaseController
{
    private $v;

    public function __construct()
    {

    }

    public function teste()
    {
        return view('admin/teste');
    }

    /**
    * Tela inicial do preschuapweb
    *
    * @return void
    */
    public function index()
    {
        return view('admin/tela_admin');
    }

    /**
    * Formulário para busca de usuário a ser importado do AD/EBSERH
    *
    * @return void
    */
    public function find_user()
    {

        if (!isset($_SESSION['Sessao']['Perfil'][1]) && !isset($_SESSION['Sessao']['Perfil'][2]) )
            return redirect()->to('admin/');

        return view('admin/usuario/form_pesquisa_usuario');
    }

    /**
    * Valida o formulário de busca e retorna um ou mais resultados
    *
    * @return mixed
    */
    public function get_user($user = false)
    {

        $func = new HUAP_Functions();

        if(!$user) {

            #Captura os inputs do Formulário
            $v = $this->request->getVar(['Pesquisar']);

            #Critérios de validação
            $inputs = $this->validate([
                'Pesquisar' => 'required',
            ]);

            #Realiza a validação e retorna ao formulário se false
            if (!$inputs) {
                return view('admin/usuario/form_pesquisa_usuario', [
                    'validation' => $this->validator
                ]);
            }

        }
        else
            $v['Pesquisar'] = $user;

        #caso a pesquisa seja um cpf verifica se há pontos e traços e os elimina
        $v['Pesquisar'] = preg_replace('/^([0-9]{3})\.?([0-9]{3})\.?([0-9]{3})\-?([0-9]{2})$/i', '$1$2$3$4', $v['Pesquisar']);

        $usuario = new UsuarioModel();
        $v['mysql'] = $usuario->get_user_mysql($v['Pesquisar']);

        #se encontrar o usuário já cadastrado na base do mysql já encaminha direto para a página dele
        if ($v['mysql'])
            return redirect()->to('admin/show_user/'.$v['mysql']['Usuario']);

        #Inicia a classe de funções próprias
        $v['func'] = new HUAP_Functions();
        #Função que remove qualquer acentuação da palavra
        $v['Pesquisar'] = $v['func']->remove_accents($v['Pesquisar']);

        #Pesquisa no AD pela palavra inserida no campo de pesquisa.
        $v['ad'] = $this->get_user_ad($v['Pesquisar']);

        /*
        echo "<pre>";
        print_r($v['ad']);
        echo "</pre>";
        exit('1111');
        #*/

        #se o resultado for zero retorna erro
        if (!$v['ad']) {
            session()->setFlashdata('failed', 'Nenhum usuário encontrado. Tente novamente.');
            return redirect()->to('admin/find_user');
        }
        #se o resultado for igual a 1(um) vai direto para a página de importação
        elseif ($v['ad']['entries']['count'] == 1)
            return view('admin/usuario/form_confirma_importacao', $v);
        #se o resultado for mais que um vai para uma lista de opções
        else
            return view('admin/usuario/list_usuarios', $v);

        #exit($v['Pesquisar']);

        return view('admin/usuario/form_pesquisa_usuario');
    }

    /**
    * Importa o usuário do AD/EBSERH e salva os dados básicos no BD PRESCHUAP
    *
    * @return mixed
    */
    public function import_user()
    {

        $usuario = new UsuarioModel();
        $auditoria = new AuditoriaModel();
        $auditorialog = new AuditoriaLogModel();

        $func = new HUAP_Functions();

        $agent = $this->request->getUserAgent();
        $request = \Config\Services::request();

        #Captura usuário a ser importado
        $v = $this->request->getVar(['Usuario']);
        $v['ad'] = $this->get_user_ad($v['Usuario']);

        #Para evitar erro de Unique Key de CPF ou usuário no BD caso o usuário tenha sido importado durante uma lentidão pontual da rede.
        $usuario = new UsuarioModel();
        $v['mysql'] = $usuario->get_user_mysql($v['Usuario']);
        if ($v['mysql'])
            return redirect()->to('admin/show_user/'.$v['mysql']['Usuario']);

        $v['data'] = [
            'Usuario'           => (isset($v['ad']['entries'][0]['samaccountname'][0])) ? esc($v['ad']['entries'][0]['samaccountname'][0]) : '',
            'Nome'              => (isset($v['ad']['entries'][0]['cn'][0])) ? esc(mb_convert_encoding($v['ad']['entries'][0]['cn'][0], "UTF-8", "ASCII")) : NULL,
            'Cpf'               => (isset($v['ad']['entries'][0]['employeeid'][0])) ? esc($v['ad']['entries'][0]['employeeid'][0]) : NULL,
            'EmailSecundario'   => (isset($v['ad']['entries'][0]['othermailbox'][0])) ? $v['ad']['entries'][0]['othermailbox'][0] : NULL,
        ];

        $v['campos'] = array_keys($v['data']);
        $v['anterior'] = array();

        $v['id'] = $usuario->insert($v['data'], TRUE);

        $v['auditoria'] = $auditoria->insert($func->create_auditoria('Sishuap_Usuario', 'CREATE', $v['id']), TRUE);
        $v['auditoriaitem'] = $auditorialog->insertBatch($func->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria']), TRUE);

        session()->setFlashdata('success', 'Usuário importado com sucesso!');
        return redirect()->to('admin/show_user/'.$v['data']['Usuario']);

        /*
        #echo $usuario->getLastQuery();
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        exit($v['Usuario']);
        */

    }

    /**
    * Importa o usuário do AD/EBSERH e salva os dados básicos no BD PRESCHUAP
    *
    * @return mixed
    */
    public function show_user($data)
    {

        $usuario = new UsuarioModel();

        $_SESSION['Usuario'] = $usuario->getWhere(['Usuario' => $data])->getRowArray();
        #Inicia a classe de funções próprias
        $v['func'] = new HUAP_Functions();

        /*
        echo "<pre>";
        print_r($session);
        echo "</pre>";
        exit();
        #*/

        return view('admin/usuario/page_usuario', $v);

    }

    /**
    * Lista os perfis atribuídos ao usuário
    *
    * @return mixed
    */
    public function list_perfil($data)
    {

        $usuario = new UsuarioModel();
        $perfil = new PerfilModel();
        $tabperfil = new TabPerfilModel();

        #Captura usuário a ser importado
        $v['data'] = $usuario->getWhere(['idSishuap_Usuario' => $data])->getRow();

        #Lista os perfis disponíveis para seleção
        $v['select']['Perfil'] = $tabperfil->where('Inativo', NULL)->findAll();

        #Perfis já atribuídos ao usuário
        $v['list']['Perfil'] = $perfil->list_perfil_bd($data, NULL, env('mod.cod'));

        #Verifica quais perfis os usuário já possui para exibir apenas aqueles ainda disponíveis pra escolha
        if($v['list']['Perfil'] !== FALSE) {
            $v['list']['delete'] = array();
            foreach ($v['list']['Perfil'] as $val) {
                $v['list']['delete'][$val['idTab_Perfil']] = TRUE;
            }
        }

        /*
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        #exit($v['data']['Usuario']);
        #*/

        return view('admin/usuario/list_perfil', $v);

    }

    /**
    * Salva os perfis selecionados no banco de dados
    *
    * @return mixed
    */
    public function set_perfil()
    {

        $perfil = new PerfilModel();
        $tabperfil = new TabPerfilModel();
        $auditoria = new AuditoriaModel();
        $auditorialog = new AuditoriaLogModel();
        $func = new HUAP_Functions();

        #Captura os inputs do Formulário
        $v = $this->request->getVar(['Perfil']);
        $data = $_SESSION['Usuario']['idSishuap_Usuario'];

        #Lista os perfis disponíveis para seleção
        $v['select']['Perfil'] = $tabperfil->where('Inativo', NULL)->findAll();

        #Perfis já atribuídos ao usuário
        $v['list']['Perfil'] = $perfil->list_perfil_bd($data, NULL, env('mod.cod'));

        #Verifica quais perfis os usuário já possui para exibir apenas aqueles ainda disponíveis pra escolha
        if($v['list']['Perfil'] !== FALSE) {
            $v['list']['delete'] = array();
            foreach ($v['list']['Perfil'] as $val) {
                $v['list']['delete'][$val['idTab_Perfil']] = TRUE;
            }
        }

        #Critérios de validação
        $inputs = $this->validate([
            'Perfil' => 'required',
        ]);

        #Realiza a validação e retorna ao formulário se false
        if (!$inputs)
            return view('admin/usuario/list_perfil', $v);

        $v['data'] = array();

        $v['data'] = [
            'idSishuap_Usuario' => $_SESSION['Usuario']['idSishuap_Usuario'],
            'idTab_Perfil'      => $v['Perfil'],
            'idTab_Modulo'      => env('mod.cod'),
        ];

        $v['campos'] = array_keys($v['data']);
        $v['anterior'] = array();

        /*
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        echo "<pre>";
        print_r($v['data']);
        echo "</pre>";        
        exit();
        #*/

        $v['id'] = $perfil->insert($v['data'], TRUE);

        $v['auditoria'] = $auditoria->insert($func->create_auditoria('Sishuap_Perfil', 'CREATE', $v['id']), TRUE);
        $v['auditoriaitem'] = $auditorialog->insertBatch($func->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria']), TRUE);

        session()->setFlashdata('success', 'Perfil adicionado com sucesso!');
        return redirect()->to('admin/list_perfil/'.$_SESSION['Usuario']['idSishuap_Usuario']);

    }

    /**
    * Deleta os perfis selecionados no banco de dados
    *
    * @return bool
    */
    public function del_perfil($data)
    {

        $perfil = new PerfilModel();
        $auditoria = new AuditoriaModel();
        $auditorialog = new AuditoriaLogModel();
        $func = new HUAP_Functions();

        $v['data'] = array();
        $v['anterior'] = $perfil->find($data);
        $v['campos'] = array_keys($v['anterior']);

        /*
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        exit();
        #*/

        $v['id'] = $perfil->delete($data);

        $v['auditoria'] = $auditoria->insert($func->create_auditoria('Sishuap_Perfil', 'DELETE', $data), TRUE);
        $v['auditoriaitem'] = $auditorialog->insertBatch($func->create_log($v['anterior'], $v['data'], $v['campos'], $v['id'], $v['auditoria'], NULL, TRUE), TRUE);

        session()->setFlashdata('success', 'Perfil excluído com sucesso!');
        return redirect()->to('admin/list_perfil/'.$_SESSION['Usuario']['idSishuap_Usuario']);

    }

    /**
    * Desabilita no sistema o usuário selecionado
    *
    * @return bool
    */
    public function disable_user($data)
    {

        $usuario = new UsuarioModel();
        $auditoria = new AuditoriaModel();
        $auditorialog = new AuditoriaLogModel();
        $func = new HUAP_Functions();

        $v = $this->request->getVar(['Desabilitar']);

        if(!$v['Desabilitar'])
            return view('admin/usuario/form_desabilita_usuario', $v);

        $v['data'] = array(
            'Inativo' => 1,
        );
        $v['anterior'] = $usuario->find($data);
        $v['campos'] = array_keys($v['data']);

        /*
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        exit();
        #*/

        $usuario->update($data, $v['data']);

        $v['auditoria'] = $auditoria->insert($func->create_auditoria('Sishuap_Usuario', 'UPDATE', $data), TRUE);
        $v['auditoriaitem'] = $auditorialog->insertBatch($func->create_log($v['anterior'], $v['data'], $v['campos'], $data, $v['auditoria'], TRUE), TRUE);

        session()->setFlashdata('success', 'Usuário desativado com sucesso!');
        return redirect()->to('admin/show_user/'.$_SESSION['Usuario']['Usuario']);

    }

    /**
    * Desabilita no sistema o usuário selecionado
    *
    * @return bool
    */
    public function enable_user($data)
    {

        $usuario = new UsuarioModel();
        $auditoria = new AuditoriaModel();
        $auditorialog = new AuditoriaLogModel();
        $func = new HUAP_Functions();

        $v = $this->request->getVar(['Habilitar']);

        if(!$v['Habilitar'])
            return view('admin/usuario/form_habilita_usuario', $v);

        $v['data'] = array(
            'Inativo' => 0,
        );
        $v['anterior'] = $usuario->find($data);
        $v['campos'] = array_keys($v['data']);

        /*
        echo "<pre>";
        print_r($v);
        echo "</pre>";
        exit();
        #*/

        $usuario->update($data, $v['data']);

        $v['auditoria'] = $auditoria->insert($func->create_auditoria('Sishuap_Usuario', 'UPDATE', $data), TRUE);
        $v['auditoriaitem'] = $auditorialog->insertBatch($func->create_log($v['anterior'], $v['data'], $v['campos'], $data, $v['auditoria'], TRUE), TRUE);

        session()->setFlashdata('success', 'Usuário ativado com sucesso!');
        return redirect()->to('admin/show_user/'.$_SESSION['Usuario']['Usuario']);

    }


    /******************* FUNÇÕES AUXILIARES *************************/

    /**
    * Valida o formulário de busca e retorna um ou mais resultados baseado no AD/EBSERH
    *
    * @return mixed
    */
    private function get_user_ad($data)
    {
        #Tenta se conectar com o servidor LDAP Master
        if (FALSE !== $v['ldap']['ldap1']=@ldap_connect(env('srv.ldap1')))
            $v['ldap']['ldap_conn'] = $v['ldap']['ldap1'];
        #Tenta se conectar com o servidor LDAP Slave caso não consiga conexão com o Master
        elseif (FALSE !== $v['ldap']['ldap2']=@ldap_connect(env('srv.ldap2')))
            $v['ldap']['ldap_conn'] = $v['ldap']['ldap2'];
        #Se nenhuma conexão acontecer é retornado false
        else
            return FALSE;

        #conexão com o usuário adm do ldap
        @ldap_bind($v['ldap']['ldap_conn'], env('ldap.usr'), env('ldap.pwd'));

        #filtros (campos de busca NOME, USUÁRIO e CPF)
        #$v['ldap']['ldap_filter'] = "(|(cn=*$data*)(samaccountname=$data)(employeeID=$data))";
        #filtros (campos de busca USUÁRIO e CPF)
        $v['ldap']['ldap_filter'] = "(|(samaccountname=$data)(employeeID=$data))";
        #campos que serão retornados após pesquisa
        $v['ldap']['ldap_att'] = array("cn", "samaccountname", "employeeID", "othermailbox");
        #resultado da pesquisa
        $v['ldap']['result'] = ldap_search($v['ldap']['ldap_conn'], env('ldap.dn'), $v['ldap']['ldap_filter'], $v['ldap']['ldap_att']);
        #organização do resultado da pesquisa
        $v['ldap']['entries'] = ldap_get_entries($v['ldap']['ldap_conn'], $v['ldap']['result']);

        /*
        echo "<pre>";
        print_r($v['ldap']);
        echo "</pre>";
        echo '<hr />';
        #*/

        #se o resultado for zero retorna FALSE
        if ($v['ldap']['entries']['count'] == 0)
            return FALSE;
        #se o resultado for 1 ou mais encaminha o array com todas as informações
        else
            return $v['ldap'];

    }
}
