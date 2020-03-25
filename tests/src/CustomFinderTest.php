<?php
namespace Raul338\Phpstan\Tests;

use Raul338\Phpstan\Tests\Model\Table\TestTable;

$table = new TestTable([]);
$q = $table->findByColumn();
$count = $q->count();

$entity = $table->newEntity();
$table->touch($entity);
$table->setLocale('es');
