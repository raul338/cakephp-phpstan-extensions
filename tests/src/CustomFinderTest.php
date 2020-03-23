<?php
namespace Raul338\Phpstan\Tests;

$table = new TestTable([]);
$q = $table->findByColumn();
$count = $q->count();
