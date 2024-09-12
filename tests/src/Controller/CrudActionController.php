<?php

namespace Raul338\Phpstan\Tests\App\Controller;

use Cake\Controller\Controller;
use Crud\Action\AddAction;
use Crud\Action\DeleteAction;
use Crud\Action\EditAction;
use Crud\Action\IndexAction;
use Crud\Action\ViewAction;
use Crud\Controller\Component\CrudComponent;
use function PHPStan\Testing\assertType;

/**
 * @property CrudComponent $Crud
 */
class CrudActionController extends Controller
{
    /**
     * @return void
     */
    public function add(): void
    {
        $this->loadComponent('Crud');
        assertType(AddAction::class, $this->Crud->action());
    }

    /**
     * @param int $id id
     * @return void
     */
    public function edit($id): void
    {
        $this->loadComponent('Crud');
        assertType(EditAction::class, $this->Crud->action());
    }

    /**
     * @param int $id id
     * @return void
     */
    public function delete($id): void
    {
        $this->loadComponent('Crud');
        assertType(DeleteAction::class, $this->Crud->action());
    }

    /**
     * @return void
     */
    public function index(): void
    {
        $this->loadComponent('Crud');
        assertType(IndexAction::class, $this->Crud->action());
    }

    /**
     * @return void
     */
    public function view(): void
    {
        $this->loadComponent('Crud');
        assertType(ViewAction::class, $this->Crud->action());
    }

    /**
     * this test is not enabled yet
     * @ return void
     *
    public function customActionTest(): void
    {
        $this->loadComponent('Crud');
        $this->Crud->mapAction('customActionTest', 'Crud.Index');
        assertType(IndexAction::class, $this->Crud->action());
    }
     * */
}
