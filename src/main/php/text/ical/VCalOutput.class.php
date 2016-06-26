<?php namespace text\ical;

use io\streams\TextWriter;

class VCalOutput {
  private $writer;

  /**
   * Create a new output instance
   *
   * @param  io.streams.TextWriter $writer
   */
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
        $key.= ';'.strtoupper($name);
        if (strspn($attribute, '=;:')) {
          $key.= '="'.$attribute.'"';
        } else {
          $key.= '='.$attribute;
        }
      }
      $this->writer->writeLine($key.':'.strtr($value, ["\n" => '\n']));
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