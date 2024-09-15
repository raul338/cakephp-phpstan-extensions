<?php
namespace Raul338\Phpstan\Tests\App\Controller;

use Cake\Controller\Controller;
use Crud\Listener\RelatedModelsListener;
use function PHPStan\Testing\assertType;

/**
 * @property \Crud\Controller\Component\CrudComponent $Crud
 */
class CrudListenerController extends Controller
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function relatedModelListenerTest()
    {
        $this->loadComponent('Crud');
        $this->Crud->addListener('relatedModels', 'Crud.RelatedModels');
        assertType(RelatedModelsListener::class, $this->Crud->listener('relatedModels'));

        return $this->Crud->execute();
    }
}
