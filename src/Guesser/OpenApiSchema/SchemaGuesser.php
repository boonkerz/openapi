<?php

namespace Joli\Jane\OpenApi\Guesser\OpenApiSchema;

use Joli\Jane\Guesser\Guess\Property;
use Joli\Jane\Guesser\JsonSchema\ObjectGuesser;
use Joli\Jane\OpenApi\Model\Schema;
use Joli\Jane\Registry;
use Joli\Jane\Runtime\Reference;

class SchemaGuesser extends ObjectGuesser
{
    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (($object instanceof Schema) && ($object->getType() === 'object' || $object->getType() === null || (is_array($object->getType()) && in_array('object', $object->getType()))) && $object->getProperties() !== null);
    }

    /**
     * @return string
     */
    protected function getSchemaClass()
    {
        return Schema::class;
    }

    /**
     * {@inheritdoc}
     */
    public function guessProperties($object, $name, $reference, Registry $registry)
    {
        $properties = [];

        foreach ($object->getProperties() as $key => $property) {
            $propertyObj = $property;

            if ($propertyObj instanceof Reference) {
                $propertyObj = $this->resolve($propertyObj, $this->getSchemaClass());
            }

            $type = $propertyObj->getType();
            $nullable = $type == 'null' || (is_array($type) && in_array('null', $type));
            if(($property instanceof Schema) && is_array($property->getType()) && in_array('null', $property->getType()) && in_array('object', $property->getType()) && count($property->getType()) == 2) {
                $property->setType('object');
            }
            $properties[] = new Property($property, $key, $reference . '/properties/' . $key, $nullable);
        }

        return $properties;
    }

}
