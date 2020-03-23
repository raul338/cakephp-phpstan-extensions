<?php
namespace Raul338\Phpstan\Tests;

use Cake\Controller\Controller;

class TestController extends Controller
{
    public function index()
    {
        $this->loadModel('Test');
    }
}
