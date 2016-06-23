<?php namespace text\vcal;

use io\streams\TextWriter;

class VCalOutput {

  public function __construct(TextWriter $writer) {
    $this->writer= $writer;
  }

  public function object($id, $members) {
    $this->writer->writeLine('BEGIN:'.strtoupper($id));
    foreach ($members as $name => $value) {
      $this->pair($name, [], $value);
    }
    $this->writer->writeLine('END:'.strtoupper($id));
  }

  public function pair($name, $attributes, $value) {
    if (is_array($value)) {
      foreach ($value as $element) {
        $this->pair($name, $attributes, $element);
      }
    } else if (is_object($value)) {
      $value->write($this);
    } else if (null !== $value) {
      $key= strtoupper($name);
      foreach ($attributes as $name => $attribute) {
        $key.= ';'.strtoupper($name).'='.$attribute;
      }
      $this->writer->writeLine($key.':'.$value);
    }
  }
}