<?php namespace text\ical;

interface Source {

  public function messages();

  public function answer($message, $body);
}