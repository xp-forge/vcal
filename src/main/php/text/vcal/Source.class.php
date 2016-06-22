<?php namespace text\vcal;

interface Source {

  public function messages();

  public function answer($message, $body);
}