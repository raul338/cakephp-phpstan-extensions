<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Cake;

use Cake\ORM\Table;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

class AssociationTableMixinClassReflectionExtension implements
    MethodsClassReflectionExtension,
    BrokerAwareExtension
{
    /**
     * @var \PHPStan\Broker\Broker
     */
    private $broker;

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    protected function getTableReflection(): ClassReflection
    {
        return $this->broker->getClass(Table::class);
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        // magic findBy* method
        if ($classReflection->isSubclassOf(Table::class)) {
            if (preg_match('/^find(?:\w+)?By/', $methodName) > 0) {
                return true;
            }
        }

        return $this->getTableReflection()->hasNativeMethod($methodName);
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        // magic findBy* method
        if ($classReflection->isSubclassOf(Table::class)) {
            if (preg_match('/^find(?:\w+)?By/', $methodName) > 0) {
                return new TableFindByPropertyMethodReflection($methodName, $classReflection);
            }
        }

        return $this->getTableReflection()->getNativeMethod($methodName);
    }
}
