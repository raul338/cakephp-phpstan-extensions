<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

require dirname(__DIR__) . '/vendor/autoload.php';
require __DIR__ . '/constants.php';

$coverages = glob(TMP . "coverages/*.json");

#increase the memory in multiples of 128M in case of memory error
ini_set('memory_limit', '512M');

$codeCoverage = new CodeCoverage();
$count = count($coverages);
$i = 0;

$filter = $codeCoverage->filter();
$filter->addDirectoryToWhitelist(SRC);

foreach ($coverages as $coverageFile) {
    $i++;
    echo "Processing coverage ($i/$count) from $coverageFile" . PHP_EOL;
    $codecoverageData = json_decode(file_get_contents($coverageFile), true);
    $testName = str_ireplace(basename($coverageFile, ".json"), "coverage-", "");
    $codeCoverage->append($codecoverageData, $testName);
}

$report = new Facade();
$report->process($codeCoverage, "reports");
