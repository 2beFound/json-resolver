<?php
use Gries\JsonObjectResolver\JsonResolver;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/Book.php';

$book = new \Book();
$book->setTitle('test-title');
$json = json_encode($book);

$resolver = new JsonResolver();
$decodedBook = $resolver->decode($json, '\Book');

var_dump($book);
var_dump($decodedBook);

// =>
// class Book#2 (1) {  protected $title =>  string(10) "test-title" }
// class Book#5 (1) {  protected $title =>  string(10) "test-title" }