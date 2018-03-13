<?php

namespace CCT\Kong\Tests\Helper;

trait ProtectedMethodSetter
{
    /**
     * Sets a protected property on a given object via reflection
     *
     * @param object $object    instance in which protected value is being modified
     * @param string $property  property on instance being modified
     * @param mixed  $value     new value of the property being modified
     *
     * @return void
     */
    protected function setProtectedProperty($object, string $property, $value)
    {
        $reflection = new \ReflectionClass($object);

        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($object, $value);
    }
}
