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
    * Lista as prescrições associadas ao paciente
    *
    * @return mixed
    */
    public function list_tabela($data)
    {

        $tabela = new TabelaModel(); #Inicia o objeto baseado na TabelaModel
        $request = \Config\Services::request(); #Inicia o recurso de request
        $v['func'] = new HUAP_Functions(); #Inicia a classe de funções próprias

        $v['pager'] = \Config\Services::pager(); #Inicia o recurso de paginação
        $v['page'] = ($request->getVar('page')) ? $request->getVar('page', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 1; #Página a ser carregada
        $v['perpage'] = 25; #Itens por página

        #$v['paciente'] = $paciente->get_paciente_bd($_SESSION['pager']['Pesquisar'], $v['perpage'], ($v['perpage']*($v['page']-1)));

        #$v['lista'] = $tabela->list_tabela_bd($data, $v['perpage'], ($v['perpage']*($v['page']-1))); #Carrega os itens da tabela selecionada
        $v['lista'] = $tabela->list_tabela_bd($data); #Carrega os itens da tabela selecionada
        $v['count'] = $tabela->count_tabela_bd($data); #Retorna o total de itens da tabela selecionada, auxiliando a paginação.
        $v['tabela'] = $data;

        /*
        echo "<pre>";
        print_r($v['lista']);
        echo "</pre>";
        echo "<pre>";
        print_r($v['lista']->getResultArray());
        echo "</pre>";
        echo "<pre>";
        print_r($v['lista']->getResultObject());
        echo "</pre>";
        exit('oi');
        #*/

        return view('admin/tabela/list_tabela', $v);

    }

}
