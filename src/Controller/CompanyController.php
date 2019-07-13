<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

class CompanyController extends AppController
{
    private $access = false;
    private $accessData = null;

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Access');

        $this->loadModel('Companys');
        $this->loadModel('Users');

        $data = $this->Users->checkAccess(env('PHP_AUTH_USER'), env('PHP_AUTH_PW'));

        if ($data) {
            $this->accessData = json_decode($data->access);
            $this->access = true;
        }
    }
    private function accessCode():bool
    {
        $this->response->header('HTTP/1.0 403', 'Forbidden');

        $this->set(['return' => null]);

        $this->render('json');

        return true;
    }
    public function index():void
    {
        if ($this->access && $this->Access->checkAccess(__CLASS__, __FUNCTION__, $this->accessData)) {
            $data = $this->Companys->getAll();

            $this->response->header('HTTP/1.0 404', 'Not Found');

            if ($data) {
                $this->response->header('HTTP/1.0 200', 'OK');
            }

            $this->set(['return' => $data]);

            $this->render('json');
        } else {
            $this->accessCode();
        }
    }

    public function view(int $id):void
    {
        if ($this->access && $this->Access->checkAccess(__CLASS__, __FUNCTION__, $this->accessData)) {
            $data = $this->Companys->getRow($id);
            
            $this->response->header('HTTP/1.0 404', 'Not Found');

            if ($data) {
                $this->response->header('HTTP/1.0 200', 'OK');
            }

            $this->set(['return' => $data]);

            $this->render('json');
        } else {
            $this->accessCode();
        }
    }

    public function add():void
    {
        if ($this->access && $this->Access->checkAccess(__CLASS__, __FUNCTION__, $this->accessData)) {
            $return = null;

            $this->response->header('HTTP/1.0 400', 'Bad Request');

            if ($this->request->is('post')) {
                $data = [
                    'name' => $this->request->getData('name'),
                    'address' => $this->request->getData('address'),
                    'city' => $this->request->getData('city'),
                    'website' => $this->request->getData('website'),
                    'longitude' => $this->request->getData('longitude'),
                    'latitude' => $this->request->getData('latitude'),
                    'created' => null
                ];
                    
                $recipe = $this->Companys->newEntity($data);
                if (empty($recipe->errors())) {
                    if ($this->Companys->save($recipe)) {
                        $this->response->header('HTTP/1.0 201', 'Created');
                        $return = json_encode($recipe);
                    } else {
                        $this->response->header('HTTP/1.0 500', 'Internal Server Error');
                        $return = json_encode('error');
                    }
                } else {
                    $this->response->header('HTTP/1.0 400', 'Bad Request');
                    $return = json_encode($recipe->errors());
                }
                $this->set(['return' => $return]);
                $this->render('json');
            }
        } else {
            $this->accessCode();
        }
    }

    public function edit(int $id):void
    {
        if ($this->access && $this->Access->checkAccess(__CLASS__, __FUNCTION__, $this->accessData)) {
            $return = null;

            $this->response->header('HTTP/1.0 404', 'Not Found');

            if ($this->request->is('put')) {
                $data = $this->Companys->getRow($id);
                try {
                    $recipe = $this->Companys->patchEntity($data, $this->request->getData());

                    if (empty($recipe->errors())) {
                        if ($this->Companys->save($recipe)) {
                            $this->response->header('HTTP/1.0 200', 'OK');
                            $return = json_encode($recipe);
                        } else {
                            $this->response->header('HTTP/1.0 500', 'Internal Server Error');
                            $return = json_encode('error');
                        }
                    } else {
                        $this->response->header('HTTP/1.0 400', 'Bad Request');
                        $return = json_encode($recipe->errors());
                    }
                } catch (\Throwable $th) {
                    $this->response->header('HTTP/1.0 500', 'Internal Server Error');
                    $return = json_encode('error');
                }

                $this->set(['return' => $return]);
                $this->render('json');

            }
        } else {
            $this->accessCode();
        }
    }

    public function delete($id)
    {
        if ($this->access && $this->Access->checkAccess(__CLASS__, __FUNCTION__, $this->accessData)) {
            $return = null;

            $this->response->header('HTTP/1.0 400', 'Bad Request');

            if ($this->request->is('delete')) {

                $entity = $this->Companys->getRow($id);

                try {
                    $result = $this->Companys->delete($entity);
                    $this->response->header('HTTP/1.0 200', 'OK');
                    $return = json_encode('Deleted');
                } catch (\Throwable $th) {
                    $this->response->header('HTTP/1.0 404', 'Not Found');
                    $return = json_encode('Not Found');
                }

                $this->set(['return' => $return]);
                $this->render('json');
            }
        } else {
            $this->accessCode();
        }
    }
}