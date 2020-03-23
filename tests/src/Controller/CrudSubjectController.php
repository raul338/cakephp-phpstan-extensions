<?php
namespace Raul338\Phpstan\Tests\Controller;

use Cake\Controller\Controller;

/**
 * @property \Crud\Controller\Component\CrudComponent $Crud
 */
class CrudSubjectController extends Controller
{
    /**
     * @return void
     */
    public function crudSubjectTest()
    {
        $this->loadComponent('Crud');
        $this->Crud->on('beforeFind', function (\Cake\Event\Event $event) {
            $event->getSubject()->query->contain('example');
        });
    }
}
