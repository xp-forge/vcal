<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Date implements \lang\Value {
  use Date\is\Value;
  use Date\with\Builder;

  private $tzid, $value;

  /**
   * Write this object
   *
   * @param  text.vcal.VCalOutput $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->pair($name, ['tzid' => $this->tzid], $this->value);
  }
}