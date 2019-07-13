<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class AccessComponent extends Component
{
    public function checkAccess(string $class, string $function, object $data):bool
    {

        if (isset($data->$class) && in_array($function, $data->$class)) {
            return true;
        }

        return false;
    }
}