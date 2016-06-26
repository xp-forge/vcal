<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Date implements Object {
  use Date\is\Value;
  use Date\with\Builder;

  private $tzid, $value;

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