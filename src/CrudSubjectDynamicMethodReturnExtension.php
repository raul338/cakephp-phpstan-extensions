<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Cake;

use Cake\Event\Event;
use Crud\Event\Subject as Subject;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use stdClass;

class CrudSubjectDynamicMethodReturnExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return Event::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'getSubject';
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        if (strpos($scope->getNamespace() ?: '', 'Controller') !== false) {
            return new ObjectType(Subject::class);
        }

        return new ObjectType(stdClass::class);
    }
}
