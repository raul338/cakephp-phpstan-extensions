<?php

namespace Raul338\Phpstan\Tests;

use Cake\Collection\CollectionInterface;
use Raul338\Phpstan\Tests\Model\Table\TestTable;

$table = new TestTable([]);
$q = $table->find('all')
    ->contain('someModel')
    ->join('some_table', [])
    ->formatResults(function (CollectionInterface $results) {
        return $results;
    });

$contains = $q->contain()[0];
$formatter = $q->formatResults();
$join = $q->join();

$count = count($q->toArray());
