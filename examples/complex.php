<?php
use Gries\JsonObjectResolver\JsonResolver;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/Author.php';
require_once __DIR__.'/Book.php';


$book = new \Book();
$book->setTitle('test-title-1');

$book2 = new \Book();
$book2->setTitle('test-title-2');

$author = new \Author();
$author->setName('test-author');
$author->setBooks([$book, $book2]);

$resolver = new JsonResolver();
$json = $resolver->encode($author);

$decodedAuthor = $resolver->decode($json);

var_dump($author);
var_dump($decodedAuthor);

//class Author#4 (2) {
//  protected $name =>
//  string(11) "test-author"
//  protected $books =>
//  array(2) {
//    [0] =>
//    class Book#2 (1) {
//      protected $title =>
//      string(12) "test-title-1"
//    }
//    [1] =>
//    class Book#3 (1) {
//      protected $title =>
//      string(12) "test-title-2"
//    }
//  }
//}
//class Author#9 (2) {
//  protected $name =>
//  string(11) "test-author"
//  protected $books =>
//  array(2) {
//    [0] =>
//    class Book#16 (1) {
//      protected $title =>
//      string(12) "test-title-1"
//    }
//    [1] =>
//    class Book#21 (1) {
//      protected $title =>
//      string(12) "test-title-2"
//    }
//  }
//}