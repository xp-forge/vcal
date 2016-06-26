<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Text implements Object {
  use Text\is\Value;
  use Text\with\Builder;

  private $language, $value;

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->pair($name, ['language' => $this->language], $this->value);
  }
}