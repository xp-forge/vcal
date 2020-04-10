<?php namespace text\ical;

use lang\partial\{Builder, Value};

class Calendar implements IObject {
  use Calendar\is\Value;
  use Calendar\with\Builder;
  use Properties;

  /** @type string */
  private $method;

  /** @type string */
  private $prodid;

  /** @type string */
  private $version;

  /** @type text.ical.Event[] */
  private $events;

  /** @type text.ical.TimeZone */
  private $timezone;

  /** @type [:string] */
  private $properties;

  /** @return text.ical.Events */
  public function events() { return new Events(...(array)$this->events); }

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->object('vcalendar', $this->merge([
      'method'   => $this->method,
      'prodid'   => $this->prodid,
      'version'  => $this->version,
      'event'    => $this->events,
      'timezone' => $this->timezone
    ]));
  }
}