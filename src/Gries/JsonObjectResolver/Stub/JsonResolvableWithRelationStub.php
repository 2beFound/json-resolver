<?php
namespace Gries\JsonObjectResolver\Stub;

class JsonResolvableWithRelationStub implements \JsonSerializable
{
    public $id;

    public $relation;

    /**
     * Create this object based on array-data.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return ['id' => $this->id, 'relation' => $this->relation];
    }
}
 