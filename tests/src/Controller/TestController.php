<?php
namespace Raul338\Phpstan\Tests\App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * @property \Crud\Controller\Component\CrudComponent $Crud
 */
class TestController extends Controller
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function crudSubjectTest()
    {
        $this->loadComponent('Crud');
        $this->Crud->on('beforeFind', function (EventInterface $event) {
            $query = $event->getSubject()->query;
            $query->contain('example');
        });

        return $this->Crud->execute();
    }
}
