JsonResolver
============

JsonResolver is a simple class that enables you to recursively encode / decode a tree of objects to json
without having to deal with stdClasses.

This is being achieved by simply injecting the original class into the json as "json_resolve_class" property.


Installation
=============================
JsonResolver can be installed via. composer:

    {
        "require": {
            "gries/json-resolver": "dev-master"
        },
    }

Usage
=====
Every plain php object is supported the only requirement is that \JsonSerializeable is implemented.

Current features:
- automatically decodes objects back to their previous classes.
- automatically decodes related objects back to their previous classes if they implement the \JsonSerializeable interface.
- automatically decodes arrays / traversable related objects if their values implement the \JsonSerializeable interface.

Book.php
--------
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

example.php
-----------
    <?php
    use Gries\JsonObjectResolver\JsonResolver;

    require_once __DIR__.'/../vendor/autoload.php';

    $book = new \Book();
    $book->setTitle('test-title');

    $resolver = new JsonResolver();
    $json = $resolver->encode($book);

    $decodedBook = $resolver->decode($json);

For further examples see the examples section.
