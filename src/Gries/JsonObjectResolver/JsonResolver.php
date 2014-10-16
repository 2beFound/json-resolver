<?php

namespace Gries\JsonObjectResolver;

/**
 * Class JsonDecoder
 *
 * @package Gries\JsonObjectResolver
 */
class JsonResolver
{
    /**
     * Decode a json tree.
     *
     * @param $json
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function decode($json, $fallbackClass = null)
    {
        if (!$object = json_decode($json, true)) {
            throw new \InvalidArgumentException('Invalid json given!');
        }

        $object = $this->resolveObject($object, $fallbackClass);

        return $object;
    }

    /**
     * Encode a Object and all its children / related objects.
     *
     * @param \JsonSerializable $object
     * @return string as json
     */
    public function encode($object)
    {
        if (!$object instanceof \JsonSerializable) {
            return json_encode($object);
        }

        $arrayData = $this->createArrayData($object);

        return json_encode($arrayData);
    }

    /**
     * Recursively resolve a object.
     * If the object is has no "json_resolve_class" property it will be returned as is.
     *
     * If the property exists the object will be converted to the configured class
     * and all its properties will be copied.
     *
     * Also all properties that have json_resolve_class set or that are array/traversables
     * will be resolved recursively.
     *
     * @param $object
     * @return mixed
     */
    private function resolveObject($object, $fallbackClass = null)
    {
        if (!isset($object['json_resolve_class'])) {
            if ($fallbackClass) {
                $class = $fallbackClass;
            } else {
                return $object;
            }
        } else {
            $class = $object['json_resolve_class'];
        }


        $ref = new \ReflectionClass($class);
        $newClass = $ref->newInstanceWithoutConstructor();

        return $this->convert($newClass, $object);
    }

    /**
     * Convert an object from a given stdClass to a target-class.
     *
     * @param $target
     * @param \stdClass $jsonObject
     * @return mixed
     */
    private function convert($target, array $jsonData)
    {
        $destinationReflection = new \ReflectionObject($target);

        foreach ($jsonData as $name => $value) {

            if ($name == 'json_resolve_class') {
                continue;
            }

            // resolve related objects
            $value = $this->convertPropertyValue($value);

            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($target, $value);
            } else {
                $target->$name = $value;
            }
        }

        return $target;
    }

    /**
     * Create array-data from an object.
     *
     * @param \JsonSerializable $object
     * @return mixed
     */
    private function createArrayData(\JsonSerializable $object)
    {
        $arrayData = $object->jsonSerialize();

        $arrayData['json_resolve_class'] = $this->getClassForObject($object);

        // recursively resolve JsonResolvableInterfaces
        foreach ($arrayData as $key => $value) {
            $arrayData[$key] = $this->createPropertyData($value);
        }

        return $arrayData;
    }

    private function getClassForObject($object)
    {
        $class = get_class($object);

        return $class;
    }

    /**
     * Create array data for a property.
     *
     * @param $value
     * @return mixed
     */
    private function createPropertyData($value)
    {
        if ($value instanceof \JsonSerializable) {
            return $this->createArrayData($value);
        }

        // recursively resolve properties
        if ($this->isIterable($value)) {
            foreach ($value as $key => $subValue) {
                $value[$key] = $this->createPropertyData($subValue);
            }
        }

        return $value;
    }

    /**
     * Check if a variable is iterable.
     *
     * @param $var
     * @return bool
     */
    private function isIterable($var)
    {
        return (is_array($var) || $var instanceof \Traversable);
    }

    /**
     * Convert a single PropertyValue.
     *
     *
     * @param $value
     * @return mixed
     */
    private function convertPropertyValue($value)
    {
        if (is_array($value) && isset($value['json_resolve_class'])) {
            $value = $this->resolveObject($value);
        }

        // resolve iterables
        if ($this->isIterable($value)) {
            foreach ($value as $key => $subValue) {
                $value[$key] = $this->convertPropertyValue($subValue);
            }
        }

        return $value;
    }
}
