<?php

namespace Joli\Jane\OpenApi\Guesser\OpenApiSchema;

use Joli\Jane\Guesser\JsonSchema\ObjectGuesser;
use Joli\Jane\OpenApi\Model\Schema;

class SchemaGuesser extends ObjectGuesser
{
    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        if(($object instanceof Schema) && is_array($object->getType()) && in_array('null', $object->getType()) && in_array('object', $object->getType()) && count($object->getType()) == 2) {
            $object->setNullable(true);
            $object->setType('object');
        }
        return (($object instanceof Schema) && ($object->getType() === 'object' || $object->getType() === null) && $object->getProperties() !== null);
    }

    /**
     * @return string
     */
    protected function getSchemaClass()
    {
        return Schema::class;
    }
}
