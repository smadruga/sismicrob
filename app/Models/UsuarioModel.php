<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'Sishuap_Usuario';
    protected $primaryKey           = 'idSishuap_Usuario';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $protectFields        = true;
    protected $allowedFields        = [
                                        'Inativo',
                                        'Usuario',
                                        'Nome',
                                        'Cpf',
                                        'EmailSecundario',
                                        ];

    /**
    * Tela inicial do preschuapweb
    *
    * @return void
    */
    public function get_user_mysql($data)
    {

        $db = \Config\Database::connect();
        $query = $db->query('
            SELECT
                idSishuap_Usuario
                , Nome
                , Usuario
                , Inativo
            FROM
                Sishuap_Usuario
            WHERE
                Usuario = "' . $data . '"
                OR Cpf = "' . $data . '"
            ORDER BY Nome
        ');
        /*echo $db->getLastQuery();
        echo "<pre>";
        print_r($query->getRowArray());
        echo "</pre>";
        exit($data);*/
        return ($query->getNumRows() > 0) ? $query->getRowArray() : FALSE ;

    }

    /**
    * Tela inicial do preschuapweb
    *
    * @return void
    */
    public function check_user($data)
    {

        $db = \Config\Database::connect();
        $query = $db->query('
            SELECT
                idSishuap_Usuario
                , Nome
                , Usuario
            FROM
                Sishuap_Usuario
            WHERE
                Usuario = "' . $data . '"
                OR Cpf = "' . $data . '"
            ORDER BY Nome
        ');
        /*echo $db->getLastQuery();
        echo "<pre>";
        print_r($query->getRowArray());
        echo "</pre>";
        exit($data);*/
        return ($query->getNumRows() > 0) ? $query->getRowArray() : FALSE ;

    }
}
