<?php
namespace Gries\JsonObjectResolver\Stub;

class JsonResolvableCollectionStub implements \JsonSerializable
{
    public $id;

    public $children;

    /**
     * Create this object based on array-data.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return ['id' => $this->id, 'children' => $this->children];
    }
}
 