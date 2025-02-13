<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Cake;

use Cake\Utility\Inflector;
use Crud\Action\AddAction;
use Crud\Action\DeleteAction;
use Crud\Action\EditAction;
use Crud\Action\IndexAction;
use Crud\Action\ViewAction;
use Crud\Controller\Component\CrudComponent;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class CrudDynamicMethodReturnExtension implements DynamicMethodReturnTypeExtension
{
    private ReflectionProvider $reflectionProvider;

    public function __construct(ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }

    public function getClass(): string
    {
        return CrudComponent::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array($methodReflection->getName(), [
            'action',
            'listener',
        ]);
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): ?Type
    {
        $method = Inflector::camelize($methodReflection->getName());

        return match ($method) {
            'Action' => $this->getTypeActionMethod($methodReflection, $methodCall, $scope),
            'Listener' => $this->getTypeListenerMethod($methodReflection, $methodCall, $scope),
            default => null,
        };
    }

    public function getTypeActionMethod(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): ?Type
    {
        $name = null;
        if ($scope->getFunction() !== null) {
            $name = $scope->getFunction()->getName();
        }

        return match ($name) {
            'index' => new ObjectType(IndexAction::class),
            'agregar',
            'add' => new ObjectType(AddAction::class),
            'editar',
            'edit' => new ObjectType(EditAction::class),
            'borrar',
            'delete' => new ObjectType(DeleteAction::class),
            'ver',
            'view' => new ObjectType(ViewAction::class),
            default => null,
        };
    }

    public function getTypeListenerMethod(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): ?Type
    {
        $parameter = $methodCall->getArgs()[0]->value;
        if (!$parameter instanceof String_) {
            return null;
        }
        $arg = Inflector::camelize($parameter->value);

        $classes = [
            'App\Listener\\' . $arg . 'Listener',
            'Crud\Listener\\' . $arg . 'Listener',
        ];
        foreach ($classes as $class) {
            if (!$this->reflectionProvider->hasClass($class)) {
                continue;
            }

            return new ObjectType($class);
        }

        return null;
    }
}
