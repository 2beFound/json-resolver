<?php
class Book implements \JsonSerializable
{
    protected $title;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function jsonSerialize()
    {
        return ['title' => $this->title];
    }
}
 