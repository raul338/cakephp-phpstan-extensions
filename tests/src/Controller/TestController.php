<?php
namespace Raul338\Phpstan\Tests\Controller;

use Cake\Controller\Controller;

/**
 * @property \Crud\Controller\Component\CrudComponent $Crud
 */
class TestController extends Controller
{
    /**
     * @return void
     */
    public function loadModelTest()
    {
        $this->loadModel('Test');
    }

    /*
     * Crud Action Tests
     */

    /**
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $this->loadComponent('Crud');
        $this->Crud->action()->saveOptions([]);

        return $this->Crud->execute();
    }

    /**
     * @param int $id id
     * @return \Cake\Http\Response|null
     */
    public function edit($id)
    {
        $this->loadComponent('Crud');
        $this->Crud->action()->saveOptions([]);

        return $this->Crud->execute();
    }

    /**
     * @param int $id id
     * @return \Cake\Http\Response|null
     */
    public function delete($id)
    {
        $this->loadComponent('Crud');
        $this->Crud->action()->findMethod([]);

        return $this->Crud->execute();
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->loadComponent('Crud');
        $this->Crud->action()->findMethod('');

        return $this->Crud->execute();
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function view()
    {
        $this->loadComponent('Crud');
        $this->Crud->action()->findMethod('');

        return $this->Crud->execute();
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function customActionTest()
    {
        $this->loadComponent('Crud');
        $this->Crud->mapAction('customActionTest', 'Crud.Index');
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod('');

        return $this->Crud->execute();
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function relatedModelListenerTest()
    {
        $this->loadComponent('Crud');
        $this->Crud->mapAction('relatedModelListenerTest', 'Crud.Index');
        $this->Crud->addListener('relatedModels', 'Crud.RelatedModels');
        $this->Crud->listener('relatedModels')->relatedModels(true);

        return $this->Crud->execute();
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function crudSubjectTest()
    {
        $this->loadComponent('Crud');
        $this->Crud->on('beforeFind', function (\Cake\Event\Event $event) {
            $event->getSubject()->query->contain('example');
        });

        return $this->Crud->execute();
    }
}
