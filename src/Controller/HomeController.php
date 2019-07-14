<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

class HomeController extends AppController
{

    public function index():void
    {
        $this->response->header('HTTP/1.0 200', 'OK');

        $this->set(['return' => 'Api version 1.0']);

        $this->render('/json');
    }
}
