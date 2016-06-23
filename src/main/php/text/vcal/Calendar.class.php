<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Calendar implements \lang\Value {
  use Calendar\is\Value;
  use Calendar\with\Builder;

  private $method, $prodid, $version, $event;

  /**
   * Write this object
   *
   * @param  text.vcal.VCalOutput $out
   * @return void
   */
  public function write($out) {
    $out->object('vcalendar', [
      'method'  => $this->method,
      'prodid'  => $this->prodid,
      'version' => $this->version,
      'event'   => $this->event
    ]);
  }
}