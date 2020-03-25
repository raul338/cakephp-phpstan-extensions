<?php
declare(strict_types=1);

namespace Raul338\Phpstan\Cake;

use Cake\ORM\Table;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

class TableMethodsClassReflectionExtension implements MethodsClassReflectionExtension, BrokerAwareExtension
{
    /**
     * @var \PHPStan\Broker\Broker
     */
    private $broker = null;

    /**
     * @var array<string,\PHPStan\Reflection\MethodReflection>
     */
    private $methods = [];

    private const PATTERN_MIXINS = "/\@mixin ([a-zA-Z0-9_\x7f-\xff\\\\]+Behavior)/";
    private const PATTERN_FINDBY = "/^find(?:\w+)?By/";

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (!$classReflection->isSubclassOf(Table::class)) {
            return false;
        }
        if (preg_match(self::PATTERN_FINDBY, $methodName) > 0) {
            return true;
        }
        $docblock = $classReflection->getNativeReflection()->getDocComment();
        if ($docblock && preg_match(self::PATTERN_MIXINS, $docblock, $behaviors)) {
            foreach ($behaviors as $behavior) {
                if (!$this->broker->hasClass($behavior)) {
                    continue;
                }
                $class = $this->broker->getClass($behavior);
                if ($class->hasMethod($methodName)) {
                    $method = $class->getNativeMethod($methodName);
                    if (!$method->isPublic()) {
                        continue;
                    }
                    $this->methods[$methodName] = $method;

                    return true;
                }
            }
        }

        return false;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        if (preg_match(self::PATTERN_FINDBY, $methodName) > 0) {
            return new TableFindByPropertyMethodReflection($methodName, $classReflection);
        }
        if (array_key_exists($methodName, $this->methods)) {
            return $this->methods[$methodName];
        }

        return $classReflection->getNativeMethod($methodName);
    }
}
