<?php

namespace Raul338\Phpstan\Tests;

use Cake\Collection\CollectionInterface;
use Raul338\Phpstan\Tests\Model\Table\TestTable;

$table = new TestTable([]);
$q = $table->find('all')
    ->contain('someModel')
    ->from([
        'some_table' => [],
    ])
    ->join('some_table', [])
    ->formatResults(function (CollectionInterface $results) {
        return $results;
    });

$contains = $q->contain()[0];
$formatter = $q->formatResults()[0];
$join = $q->join()[0];
$from = $q->from()[0];

$count = count($q->toArray());
