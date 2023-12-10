<?php

namespace App\Models;

use CodeIgniter\Model;

class TabelaModel extends Model
{
    protected $DBGroup              = 'default';

    /**
    * Lista os itens da tabela Protocolo_Medicamentos
    *
    * @return array
    */
    public function list_medicamento_bd($data, $simples = FALSE)
    {

        $db = \Config\Database::connect();

        #$inativo = ($inativo) ? ' '

        if($simples) {
            $query = $db->query(
                'SELECT
                    tpm.idTabSismicrob_Protocolo_Medicamento
                    , tpm.idTabSismicrob_Protocolo
                    , tpm.OrdemInfusao
                    , tpm.idTabSismicrob_EtapaTerapia
                    , tpm.idTabSismicrob_Medicamento
                    , tpm.Dose
                    , tpm.idTabSismicrob_UnidadeMedida
                    , tpm.idTabSismicrob_ViaAdministracao
                    , tpm.idTabSismicrob_Diluente
                    , tpm.Volume
                    , tpm.TempoInfusao
                    , tpm.idTabSismicrob_Posologia
                    , tum.idTabSismicrob_Formula
                    , format(tpm2.CalculoLimiteMinimo, 2, "pt_BR") AS CalculoLimiteMinimo
	                , format(tpm2.CalculoLimiteMaximo, 2, "pt_BR") AS CalculoLimiteMaximo
                FROM
                    TabSismicrob_Protocolo_Medicamento AS tpm
                    , TabSismicrob_UnidadeMedida AS tum
                    , TabSismicrob_Medicamento AS tpm2
                WHERE
                    idTabSismicrob_Protocolo = '.$data.'
                    and tpm.Inativo = 0
                    and tpm.idTabSismicrob_UnidadeMedida = tum.idTabSismicrob_UnidadeMedida
                    and tpm2.idTabSismicrob_Medicamento = tpm.idTabSismicrob_Medicamento  
                ORDER BY OrdemInfusao ASC'
            );
        }
        else {
            $query['count'] = $db->query('
                SELECT
                    COUNT(idTabSismicrob_Protocolo_Medicamento) AS count
                FROM
                    TabSismicrob_Protocolo_Medicamento
                WHERE
                    idTabSismicrob_Protocolo = '.$data.'
                    AND Inativo = 0;
            ');
            $query['count'] = $query['count']->getRowArray();
            $query['count'] = $query['count']['count'];

            $query['lista'] = $db->query('
                SELECT
                	tpm.idTabSismicrob_Protocolo_Medicamento
                    , tpm.idTabSismicrob_Protocolo
                    , tpm.OrdemInfusao
                    , tet.EtapaTerapia
                    , tm.Medicamento
                    , concat(format(tpm.Dose, 2, "pt_BR"), " ", tum.Representacao) AS Dose
                    , tva.ViaAdministracao
                    , td.Diluente
                    , format(tpm.Volume, 2, "pt_BR") AS Volume
                    , tpm.TempoInfusao
                    , tpo.Posologia
                    , tpm.DataCadastro
                    , date_format(tpm.DataCadastro, "%d/%m/%Y %H:%i") as Cadastro
                    , tpm.Inativo
                    , format(tm.CalculoLimiteMinimo, 2, "pt_BR") as CalculoLimiteMinimo
                    , format(tm.CalculoLimiteMaximo, 2, "pt_BR") as CalculoLimiteMaximo
                FROM
                    TabSismicrob_Protocolo_Medicamento AS tpm
                        LEFT JOIN TabSismicrob_Diluente AS td ON tpm.idTabSismicrob_Diluente = td.idTabSismicrob_Diluente
                    , TabSismicrob_EtapaTerapia 			AS tet
                    , TabSismicrob_Medicamento 				AS tm
                    , TabSismicrob_UnidadeMedida 			AS tum
                    , TabSismicrob_ViaAdministracao 		AS tva
                    , TabSismicrob_Posologia 				AS tpo
                WHERE
                	tpm.idTabSismicrob_EtapaTerapia 		= tet.idTabSismicrob_EtapaTerapia
                    and tpm.idTabSismicrob_Medicamento 		= tm.idTabSismicrob_Medicamento
                    and tpm.idTabSismicrob_UnidadeMedida 	= tum.idTabSismicrob_UnidadeMedida
                    and tpm.idTabSismicrob_ViaAdministracao = tva.idTabSismicrob_ViaAdministracao
                    and tpm.idTabSismicrob_Posologia 		= tpo.idTabSismicrob_Posologia
                    and tpm.idTabSismicrob_Protocolo = '.$data.'
                ORDER BY Inativo ASC, tpm.idTabSismicrob_Protocolo ASC, tpm.OrdemInfusao ASC
            ');
        }

        /*
        echo $db->getLastQuery();
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        exit('oi222');
        #*/

        return $query;

    }

    /**
    * Lista os itens registrados na tabela selecionada
    *
    * @return array
    */
    public function list_tabela_bd($data, $limit = NULL, $offset = NULL, $queryfields = NULL, $order = NULL, $notinativo = NULL)
    {

        $limit = $offset = NULL;
        if ($queryfields === NULL || $queryfields === FALSE) {
            $limit = ($limit) ? ' LIMIT '.$limit : NULL;
            $offset = ($offset) ? ' OFFSET '.$offset : NULL;
            $select = '*, date_format(DataCadastro, "%d/%m/%Y %H:%i") as Cadastro';
        }
        else {
            $limit = $offset = NULL;
            $select = $queryfields;
        }

        $order = ($order) ? $order : $data;

        $where = ($notinativo) ? ' WHERE Inativo = 0' : NULL;

        $db = \Config\Database::connect();

        return $db->query(
            'SELECT
                '.$select.'
            FROM
                TabSismicrob_'.$data.'
                '.$where.'
            ORDER BY '.$order.' ASC
                '.$limit.'
                '.$offset
        );

    }

    /**
    * Lista os itens registrados na tabela selecionada
    *
    * @return array
    */
    public function count_tabela_bd($data)
    {

        $db = \Config\Database::connect();
        return $db->table('TabSismicrob_'.$data)->countAll();;

    }

    /**
    * Atualiza o item no banco de dados
    *
    * @return array
    */
    public function update_item($data, $tabela, $id)
    {

        $db = \Config\Database::connect();
        $builder = $db->table('TabSismicrob_'.$tabela);

        $builder->where(['idTabSismicrob_'.$tabela => $id]);

        return $builder->update($data);

    }

    /**
    * Atualiza a ordem de infusão no banco de dados
    *
    * @return array
    */
    public function update_item_sort($data)
    {

        $db = \Config\Database::connect();
        $builder = $db->table('TabSismicrob_Protocolo_Medicamento');

        return $builder->updateBatch($data, 'idTabSismicrob_Protocolo_Medicamento');

    }

    /**
    * Registra o item no banco de dados
    *
    * @return array
    */
    public function insert_item($data, $tabela)
    {

        $db = \Config\Database::connect();
        $builder = $db->table('TabSismicrob_'.$tabela);

        $builder->insert($data);
        return $db->insertID();

    }

    /**
    * Retorna o item no banco de dados de acordo com seu id
    *
    * @return array
    */
    public function get_item($data, $tabela)
    {

        $db = \Config\Database::connect();
        $builder = $db->table('TabSismicrob_'.$tabela);

        return $builder->getWhere(['idTabSismicrob_'.$tabela => $data])->getRowArray();

    }

    /**
    * Retorna o item no banco de dados de acordo com seu id
    *
    * @return array
    */
    public function get_item_sort($id, $ordem = FALSE)
    {

        $where = ($ordem) ? 'AND OrdemInfusao IN ('.$ordem.')' : NULL;

        $db = \Config\Database::connect();
        $query = $db->query('
            SELECT
                idTabSismicrob_Protocolo_Medicamento
                , OrdemInfusao
                , Inativo
            FROM
                TabSismicrob_Protocolo_Medicamento
            WHERE
                idTabSismicrob_Protocolo = '.$id.'
                '.$where.'
            ORDER BY OrdemInfusao ASC, idTabSismicrob_Protocolo_Medicamento ASC
        ');

        return $query->getResultArray();

    }

}
