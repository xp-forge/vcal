<?php namespace text\vcal;

use io\streams\TextWriter;

class VCalOutput {

  public function __construct(TextWriter $writer) {
    $this->writer= $writer;
  }

  /**
   * Begin an object
   *
   * @param  string $id
   * @return void
   */
  public function begin($id) {
    $this->writer->writeLine('BEGIN:'.strtoupper($id));
  }

  /**
   * Ends an object
   *
   * @param  string $id
   * @return void
   */
  public function end($id) {
    $this->writer->writeLine('END:'.strtoupper($id));
  }

  /**
   * Writes a pair
   *
   * @param  string $name
   * @param  [:string] $attributes
   * @param  var $value
   * @return void
   */
  public function pair($name, $attributes, $value) {
    if (is_array($value)) {
      foreach ($value as $element) {
        $this->pair($name, $attributes, $element);
      }
    } else if (is_object($value)) {
      $value->write($this, $name);
    } else if (null !== $value) {
      $key= strtoupper($name);
      foreach ($attributes as $name => $attribute) {
        $key.= ';'.strtoupper($name).'='.$attribute;
      }
      $this->writer->writeLine($key.':'.$value);
    }
  }

  /**
   * Writes a complete object
   *
   * @param  string $id
   * @param  [:var] $members
   * @return void
   */
  public function object($id, $members) {
    $this->begin($id);
    foreach ($members as $name => $value) {
      $this->pair($name, [], $value);
    }
    $this->end($id);
  }
}