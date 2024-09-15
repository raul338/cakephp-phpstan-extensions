<?php

namespace Raul338\Phpstan\Tests;

use PHPStan\Testing\TypeInferenceTestCase;

class TableMethodsClassReflectionExtensionTest extends TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public static function dataFileAsserts(): iterable
    {
        yield from self::gatherAssertTypes(ROOT . '/tests/src/CustomFinderTest.php');
    }

    /**
     * @dataProvider dataFileAsserts
     * @covers \Raul338\Phpstan\Cake\CrudSubjectDynamicMethodReturnExtension
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
        return [ROOT . DS . 'tests.neon'];
    }
}
