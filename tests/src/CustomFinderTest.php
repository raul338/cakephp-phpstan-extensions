<?php
namespace Raul338\Phpstan\Tests\App;

use Cake\ORM\Query\SelectQuery;
use Raul338\Phpstan\Tests\App\Model\Table\TestTable;
use function PHPStan\Testing\assertType;

$table = new TestTable([]);
$q = $table->findByColumn();
assertType(SelectQuery::class, $q);

$entity = $table->newEmptyEntity();
assertType('bool', $table->touch($entity));
assertType('string', $table->translationField('field'));
