<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Calendar implements Object {
  use Calendar\is\Value;
  use Calendar\with\Builder;

  private $method, $prodid, $version, $event, $timezone;

  /** @return text.ical.Event[] */
  public function events() { return (array)$this->event; }

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
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