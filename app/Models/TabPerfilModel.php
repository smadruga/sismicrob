<?php

namespace App\Models;

use CodeIgniter\Model;

class TabPerfilModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'Tab_Perfil';
    protected $primaryKey           = 'idTab_Perfil';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $protectFields        = true;
    protected $allowedFields        = [
                                        'Inativo',
                                        'Perfil',
                                        'Descricao',
                                    ];

}
