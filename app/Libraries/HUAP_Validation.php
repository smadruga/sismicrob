<?php

namespace App\Libraries;

#use CodeIgniter\Config\App\Validation;

class HUAP_Validation {

    public function required_if($val, $campo, $not = FALSE) {
        
        $list = array_map('trim', explode(',', $campo));

        if ($list[0] == $list[1]) {

            if (!$val) {
            
                $error = lang('myerrors.required_if');
                return false;
            
            }
            else
                return true;
        }
        else
            return true;
                   
    }

}
