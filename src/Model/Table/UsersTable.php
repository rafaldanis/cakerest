<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        
        $this->table('user');
    }

    public function checkAccess(string $login, string $password):?object
    {
        $data = $this->find('all', ['fields' => ['access'], 'conditions' => ['username' => $login, 'password' => hash('sha512', $password)]])->first();

        return $data;
    }
}
