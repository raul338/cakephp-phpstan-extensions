<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Cake;

use Cake\Utility\Inflector;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class QueryDynamicMethodReturnTypeExtension implements DynamicMethodReturnTypeExtension, BrokerAwareExtension
{
    /**
     * @var \PHPStan\Broker\Broker
     */
    private $broker;

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    public function getClass(): string
    {
        return \Cake\ORM\Query::class;
    }

    /**
     * @var array<string>
     */
    private $methods = [
        'contain',
        'formatResults',
        'join',
    ];

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array($methodReflection->getName(), $this->methods);
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $funcName = 'getType' . Inflector::camelize($methodReflection->getName()) . 'Method';

        return $this->$funcName($methodReflection, $methodCall, $scope);
    }

    public function getTypeContainMethod(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        if (count($methodCall->args) === 0) {
            $method = $this->broker->getClass(\Cake\ORM\EagerLoader::class)->getNativeMethod('getContain');

            return \PHPStan\Reflection\ParametersAcceptorSelector::selectSingle($method->getVariants())->getReturnType();
        }

        return new ObjectType($methodReflection->getPrototype()->getDeclaringClass()->getName());
    }

    public function getTypeFormatResultsMethod(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        if (count($methodCall->args) === 0) {
            $method = $methodReflection->getDeclaringClass()->getNativeMethod('getResultFormatters');

            return \PHPStan\Reflection\ParametersAcceptorSelector::selectSingle($method->getVariants())->getReturnType();
        }

        return new ObjectType($methodReflection->getPrototype()->getDeclaringClass()->getName());
    }

    public function getTypeJoinMethod(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        if (count($methodCall->args) === 0) {
            $method = $methodReflection->getDeclaringClass()->getNativeMethod('clause');

            return \PHPStan\Reflection\ParametersAcceptorSelector::selectSingle($method->getVariants())->getReturnType();
        }

        return new ObjectType($this->getClass());
    }
}
