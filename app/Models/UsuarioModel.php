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
            u.idSishuap_Usuario
            , u.Nome
            , u.Usuario
            , u.Inativo
            , u.Cpf
            , u.EmailSecundario
            , pm.idSishuap_Usuario as "Permissao"
        FROM
            Sishuap_Usuario u
                LEFT JOIN Sishuap_PermissaoModulo pm ON u.idSishuap_Usuario = pm.idSishuap_Usuario
        WHERE
            u.Usuario = "' . $data . '"
            OR u.Cpf = "' . $data . '"
        ORDER BY u.Nome
        ');
        /*
        echo $db->getLastQuery();
        echo "<pre>";
        print_r($query->getRowArray());
        echo "</pre>";
        exit($data);
        #*/
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
