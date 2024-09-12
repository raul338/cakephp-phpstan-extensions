<?php
namespace Raul338\Phpstan\Tests\App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Event\EventInterface;

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
        $this->Crud->on('beforeFind', function (EventInterface $event) {
            $event->getSubject()->query->contain('example');
        });
    }

    /**
     * @return void
     */
    public function crudSubjectTestWithEvent()
    {
        $this->loadComponent('Crud');
        $this->Crud->on('beforeFind', function (Event $event) {
            $event->getSubject()->query->contain('example');
        });
    }
}
