<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CompanysTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        
        $this->table('company');
    }
    public function getAll():?object
    {
        return $this->find();
    }
    public function getRow(int $id):?object
    {
        return $this->findById($id)->first();
    }
    public function validationDefault(Validator $validator)
    {
        $validator
        ->requirePresence('name', ['create', 'update'])
        ->notEmpty('name', 'Please fill this field')
        ->add('website', [
            'valid' => ['rule' => 'url', 'message' => 'Wrong url format'],
            'maxLength' => ['rule' => ['maxLength', 150], 'message' => 'The maximum number of characters is 150']
        ])
        ->add('city', [
            'valid' => ['rule' => 'alphanumeric', 'message' => 'Wrong alphanumeric format'],
            'maxLength' => ['rule' => ['maxLength', 45], 'message' => 'The maximum number of characters is 150']
        ])
        ->add('address', [
            'valid' => ['rule' => 'alphanumeric', 'message' => 'Wrong alphanumeric format'],
            'maxLength' => ['rule' => ['maxLength', 150], 'message' => 'The maximum number of characters is 150']
        ])
        ->add('latitude', 'valid', ['rule' => 'numeric', 'message' => 'Wrong numeric format'])
        ->add('longitude', 'valid', ['rule' => 'numeric', 'message' => 'Wrong numeric format'])
        ->add('name', [
            'minLength' => ['rule' => ['minLength', 5], 'last' => true, 'message' => 'The minimum number of characters is 5'],
            'maxLength' => ['rule' => ['maxLength', 44], 'message' => 'The maximum number of characters is 44']
        ]);

        return $validator;
    }
}
