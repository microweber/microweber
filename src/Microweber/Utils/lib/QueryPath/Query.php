<?php
namespace QueryPath;
interface Query {
  public function __construct($document = NULL, $selector = NULL, $options = NULL);
  public function find($selector);
  public function top($selector = NULL);
  public function next($selector = NULL);
  public function prev($selector = NULL);
  public function siblings($selector = NULL);
  public function parent($selector = NULL);
  public function children($selector = NULL);
}
