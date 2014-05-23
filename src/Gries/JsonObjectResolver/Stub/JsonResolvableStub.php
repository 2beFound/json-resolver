<?php
namespace Gries\JsonObjectResolver\Stub;

class JsonResolvableStub implements \JsonSerializable
{
    public $id;

    protected $privateId;

    /**
     * @codeCoverageIgnore
     * @param mixed $privateId
     */
    public function setPrivateId($privateId)
    {
        $this->privateId = $privateId;
    }

    /**
     * @codeCoverageIgnore
     * @return mixed
     */
    public function getPrivateId()
    {
        return $this->privateId;
    }

    /**
     * Create this object based on array-data.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return ['id' => $this->id];
    }
}
 