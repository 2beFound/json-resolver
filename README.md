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

example usage
-----------
    <?php

    $book = new Book(); // JsonSerializeable
    $author = new Author(); // JsonSerializeable
    $author->addBook($book);    // author has a property books that is a array or TraversableInterface

    $resolver = new JsonResolver();

    $json = $resolver->encode($author);
    $author = $resolver->decode($json);

    get_class($author); // Author
    get_class($author->getBooks()->getFirst()); // Book

For further examples see the <a href="examples/">examples</a> section.
