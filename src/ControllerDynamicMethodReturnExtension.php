<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Cake;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Utility\Inflector;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class ControllerDynamicMethodReturnExtension implements BrokerAwareExtension, DynamicMethodReturnTypeExtension
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
        return Controller::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array($methodReflection->getName(), [
            'loadModel',
        ]);
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $funcion = 'getType' . Inflector::camelize($methodReflection->getName()) . 'Method';

        return $this->$funcion($methodReflection, $methodCall, $scope);
    }

    public function getTypeLoadModelMethod(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $arg = $methodCall->args[0]->value;

        if ($arg instanceof \PhpParser\Node\Scalar\String_) {
            $model = $arg->value;
            [$plugin, $name] = pluginSplit($model);
            $plugin = $plugin ?? Configure::read('App.namespace');
            $class = "$plugin\Model\Table\\${name}Table";
            if ($this->broker->hasClass($class)) {
                return new ObjectType($class);
            }
        }

        return \PHPStan\Reflection\ParametersAcceptorSelector::selectFromArgs(
            $scope,
            $methodCall->args,
            $methodReflection->getVariants()
        )->getReturnType();
    }
}
