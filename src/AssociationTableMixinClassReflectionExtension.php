<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Cake;

use Cake\ORM\Association;
use Cake\ORM\Table;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;

class AssociationTableMixinClassReflectionExtension implements
    PropertiesClassReflectionExtension,
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
        if (!$classReflection->isSubclassOf(Association::class)) {
            return false;
        }

        return $this->getTableReflection()->hasMethod($methodName);
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

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if (!$classReflection->isSubclassOf(Association::class)) {
            return false;
        }

        return $this->getTableReflection()->hasProperty($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        return $this->getTableReflection()->getNativeProperty($propertyName);
    }
}
