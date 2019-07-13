<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Company extends Entity
{
    protected $_accessible = [
        '*' => true,
    ];
    
    protected function _setCreated():string
    {
        return date('Y-m-d');
    }
}