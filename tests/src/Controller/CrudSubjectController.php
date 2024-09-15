<?php
namespace Raul338\Phpstan\Tests\App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Crud\Event\Subject;
use function PHPStan\Testing\assertType;

/**
 * @property \Crud\Controller\Component\CrudComponent $Crud
 */
class CrudSubjectController extends Controller
{
    /**
     * @return void
     */
    public function crudSubjectTest(): void
    {
        $this->loadComponent('Crud');
        $this->Crud->on('beforeFind', function (EventInterface $event) {
            assertType(Subject::class, $event->getSubject());
        });
    }
}
