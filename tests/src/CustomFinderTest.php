<?php
namespace Raul338\Phpstan\Tests\App;

use Raul338\Phpstan\Tests\App\Model\Table\TestTable;

$table = new TestTable([]);
$q = $table->findByColumn();
$count = $q->count();

$entity = $table->newEmptyEntity();
$table->touch($entity);
$table->setLocale('es');
