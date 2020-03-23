<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Cake;

use Cake\ORM\Table;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

class AssociationTableMixinClassReflectionExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        $isTable = $classReflection->isSubclassOf(Table::class);
        $isFindBy = preg_match('/^find(?:\w+)?By/', $methodName) > 0;

        return $isTable && $isFindBy;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        return new TableFindByPropertyMethodReflection($methodName, $classReflection);
    }
}
