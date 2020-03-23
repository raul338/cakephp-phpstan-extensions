<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

require __DIR__ . '/constants.php';

define('EXTRACT_DIRECTORY', TMP . '/phpstan');

if (file_exists(EXTRACT_DIRECTORY . '/vendor/autoload.php') == true) {
    echo "Extracted autoload already exists. Skipping phar extraction as presumably it's already extracted." . PHP_EOL;
} else {
    $composerPhar = new Phar(ROOT . '/tests/phpstan.phar');
    $composerPhar->extractTo(EXTRACT_DIRECTORY);
}
require_once EXTRACT_DIRECTORY . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);

/**
 * @return void
 */
function endCoverage()
{
    try {
        $testName = env('XDEBUG_SESSION', 'unknown_test') . '_' . time();
        xdebug_stop_code_coverage(false);
        $path = TMP . 'coverages';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $coverageName = $path . '/coverage-' . $testName . '-' . microtime(true);
        $codecoverageData = json_encode(xdebug_get_code_coverage());
        file_put_contents($coverageName . '.json', $codecoverageData);
    } catch (Exception $ex) {
        file_put_contents($coverageName . '.ex', $ex);
    }
}

class CoverageDumper
{
    /**
     * @return void
     */
    public function __destruct()
    {
        try {
            endCoverage();
        } catch (Exception $ex) {
            echo str($ex);
        }
    }
}

$_coverage_dumper = new CoverageDumper();

$classmap = require EXTRACT_DIRECTORY . '/vendor/composer/autoload_classmap.php';
$classes = [
    '\\Symfony\\Component\\Console\\Application' => null,
    '\\Symfony\\Component\\Console\\Input\\ArrayInput' => null,
];
foreach ($classes as $classToFind => $null) {
    foreach (array_keys($classmap) as $class) {
        // echo sprintf('Search %s in %s', $classToFind, $class) . PHP_EOL;
        if (strpos($class, $classToFind) !== false) {
            $classes[$classToFind] = $class;
            continue;
        }
    }
}

// vendor/bin/phpstan analyze -c tests.neon --xdebug
$application = $classes['\\Symfony\\Component\\Console\\Application'];
$app = new $application();
$app->add(new PHPStan\Command\AnalyseCommand([
    ROOT . '/vendor',
    EXTRACT_DIRECTORY . '/vendor',
]));
$arrayInput = $classes['\\Symfony\\Component\\Console\\Input\\ArrayInput'];
$input = new $arrayInput([
    'command' => 'analyze',
    '--configuration' => 'tests.neon',
    '--xdebug' => true,
    '--debug' => true,
]);
$app->run($input);
