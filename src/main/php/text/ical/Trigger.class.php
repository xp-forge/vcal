<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Trigger implements Object {
  use Trigger\is\Value;
  use Trigger\with\Builder;

  /** @type string */
  private $related;

  /** @type string */
  private $value;

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->pair($name, ['related' => $this->related], $this->value);
  }
}