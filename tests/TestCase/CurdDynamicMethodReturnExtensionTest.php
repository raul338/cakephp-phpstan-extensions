<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Tests;

use PHPStan\Testing\TypeInferenceTestCase;

class CurdDynamicMethodReturnExtensionTest extends TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public static function dataFileAsserts(): iterable
    {
        // path to a file with actual asserts of expected types:
        yield from self::gatherAssertTypes(ROOT . '/tests/src/Controller/CrudActionController.php');
    }

    /**
     * @dataProvider dataFileAsserts
     * @covers \Raul338\Phpstan\Cake\CrudDynamicMethodReturnExtension
     */
    public function testFileAsserts(
        string $assertType,
        string $file,
        ...$args
    ): void {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        // path to your project's phpstan.neon, or extension.neon in case of custom extension packages
        return [ROOT . DS . 'cakephp-extensions.neon'];
    }
}
