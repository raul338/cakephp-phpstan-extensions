<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Cake;

use Cake\Utility\Inflector;
use Crud\Controller\Component\CrudComponent;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class CrudDynamicMethodReturnExtension implements BrokerAwareExtension, DynamicMethodReturnTypeExtension
{
    /**
     * @var \PHPStan\Broker\Broker
     */
    private $broker = null;

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
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

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $funcion = 'getType' . Inflector::camelize($methodReflection->getName()) . 'Method';

        return $this->$funcion($methodReflection, $methodCall, $scope);
    }

    public function getTypeActionMethod(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $name = null;
        if ($scope->getFunction() !== null) {
            $name = $scope->getFunction()->getName();
        }
        switch ($name) {
            case 'index':
                return new ObjectType(\Crud\Action\IndexAction::class);
            case 'agregar':
            case 'add':
                return new ObjectType(\Crud\Action\AddAction::class);
            case 'editar':
            case 'edit':
                return new ObjectType(\Crud\Action\EditAction::class);
            case 'borrar':
            case 'delete':
                return new ObjectType(\Crud\Action\DeleteAction::class);
            case 'ver':
            case 'view':
                return new ObjectType(\Crud\Action\ViewAction::class);
            default:
                return \PHPStan\Reflection\ParametersAcceptorSelector::selectFromArgs(
                    $scope,
                    $methodCall->args,
                    $methodReflection->getVariants()
                )->getReturnType();
        }
    }

    public function getTypeListenerMethod(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $defaultReturn = \PHPStan\Reflection\ParametersAcceptorSelector::selectFromArgs(
            $scope,
            $methodCall->args,
            $methodReflection->getVariants()
        )->getReturnType();
        $parameter = $methodCall->args[0]->value;
        if (!$parameter instanceof \PhpParser\Node\Scalar\String_) {
            return $defaultReturn;
        }
        if (count($methodCall->args) !== 1) {
            return $defaultReturn;
        }
        $arg = Inflector::camelize($parameter->value);

        $classes = [
            'App\Listener\\' . $arg . 'Listener',
            'Crud\Listener\\' . $arg . 'Listener',
        ];
        foreach ($classes as $class) {
            if (!$this->broker->hasClass($class)) {
                continue;
            }

            return new ObjectType($class);
        }
    }
}
