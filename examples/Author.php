<?php
class Author implements \JsonSerializable
{
    /**
     * @var mixed
     */
    protected $name;

    /**
     * @var array
     */
    protected $books = array();

    public function setBooks($books)
    {
        $this->books = $books;
    }

    /**
     * @return array
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @codeCoverageIgnore
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @codeCoverageIgnore
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'books' => $this->books
        ];
    }
}
 