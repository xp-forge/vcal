<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class TimeZone implements \lang\Value {
  use TimeZone\is\Value;
  use TimeZone\with\Builder;

  private $tzid, $standard, $daylight;

  /**
   * Write this object
   *
   * @param  text.vcal.VCalOutput $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->object('vtimezone', [
      'tzid'     => $this->tzid,
      'standard' => $this->standard,
      'daylight' => $this->daylight
    ]);
  }
}