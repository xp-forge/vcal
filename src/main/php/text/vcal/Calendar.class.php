<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Calendar implements \lang\Value {
  use Calendar\is\Value;
  use Calendar\with\Builder;

  private $method, $prodid, $version, $event, $timezone;

  /**
   * Write this object
   *
   * @param  text.vcal.VCalOutput $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->object('vcalendar', [
      'method'   => $this->method,
      'prodid'   => $this->prodid,
      'version'  => $this->version,
      'event'    => $this->event,
      'timezone' => $this->timezone
    ]);
  }
}