<?php
namespace Raul338\Phpstan\Tests;

use Cake\Controller\Controller;

/**
 * @property \Crud\Controller\Component\CrudComponent $Crud
 */
class TestController extends Controller
{
    public function loadModelTest()
    {
        $this->loadModel('Test');
    }

    /**
     * Crud Action Tests
     */
    public function add()
    {
        $this->loadComponent('Crud');
        $this->Crud->action()->saveOptions([]);
    }

    public function edit()
    {
        $this->loadComponent('Crud');
        $this->Crud->action()->saveOptions([]);
    }

    public function delete()
    {
        $this->loadComponent('Crud');
        $this->Crud->action()->findMethod([]);
    }

    public function index()
    {
        $this->loadComponent('Crud');
        $this->Crud->action()->findMethod('');
    }

    public function view()
    {
        $this->loadComponent('Crud');
        $this->Crud->action()->findMethod('');
    }

    public function customActionTest()
    {
        $this->loadComponent('Crud');
        $this->Crud->mapAction('customActionTest', 'Crud.Index');
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod('');
    }

    public function relatedModelListenerTest()
    {
        $this->loadComponent('Crud');
        $this->Crud->mapAction('relatedModelListenerTest', 'Crud.Index');
        $this->Crud->addListener('relatedModels', 'Crud.RelatedModels');
        $this->Crud->listener('relatedModels')->relatedModels(true);
    }
}
