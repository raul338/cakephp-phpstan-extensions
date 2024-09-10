<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Cake;

use Cake\Event\EventInterface;
use Crud\Controller\Component\CrudComponent;
use Crud\Event\Subject as Subject;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class CrudSubjectDynamicMethodReturnExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return EventInterface::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'getSubject';
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): ?Type
    {
        $stack = $scope->getFunctionCallStack();
        if (!count($stack)) {
            return null;
        }
        $last = array_pop($stack);
        if (!$last instanceof MethodReflection) {
            return null;
        }
        if ($last->getDeclaringClass()->getName() === CrudComponent::class) {
            return new ObjectType(Subject::class);
        }

        return null;
    }
}
