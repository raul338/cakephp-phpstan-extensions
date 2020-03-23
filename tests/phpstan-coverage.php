<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

require dirname(__DIR__) . '/vendor/autoload.php';
require __DIR__ . '/constants.php';

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

// vendor/bin/phpstan analyze -c tests.neon --xdebug
$app = new \Symfony\Component\Console\Application();
$app->add(new PHPStan\Command\AnalyseCommand());
$input = new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'analyze',
    '--configuration' => 'tests.neon',
    '--xdebug' => true,
    '--debug' => true,
]);
$app->run($input);
