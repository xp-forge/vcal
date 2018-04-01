<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class IDate implements IObject {
  use IDate\is\Value;
  use IDate\with\Builder;

  /** @type string */
  private $tzid;

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
    $out->pair($name, ['tzid' => $this->tzid], $this->value);
  }
}