<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissaoModuloModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'Sishuap_PermissaoModulo';
    protected $primaryKey           = 'idSishuap_PermissaoModulo';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $protectFields        = true;
    protected $allowedFields        = [
                                        'idSishuap_PermissaoModulo',
                                        'idSishuap_Usuario',
                                        'idTab_Modulo',
                                      ];
  /**
    * Busca a permissão desejada
    *
    * @return array
    */
    public function get_permission_bd($usuario, $modulo)
    {
        $db = \Config\Database::connect();
        $query = $db->query('
            SELECT
                *
            FROM
                Sishuap_PermissaoModulo pm
            WHERE
                pm.idSishuap_Usuario = "' . $usuario . '"
                AND pm.idTab_Modulo = "' . $modulo . '"
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

}
