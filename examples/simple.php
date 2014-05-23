<?php
use Gries\JsonObjectResolver\JsonResolver;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/Book.php';

$book = new \Book();
$book->setTitle('test-title');

$resolver = new JsonResolver();
$json = $resolver->encode($book);

$decodedBook = $resolver->decode($json);

var_dump($book);
var_dump($decodedBook);

// =>
// class Book#2 (1) {  protected $title =>  string(10) "test-title" }
// class Book#5 (1) {  protected $title =>  string(10) "test-title" }