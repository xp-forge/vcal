<?php namespace text\ical;

use lang\IllegalStateException;
use lang\partial\Builder;
use lang\partial\Value;
use text\ical\Date as IDate;
use util\Date as Date;

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

  /** @type text.ical.TimeZone[] */
  private $timezones;

  /** @type [:string] */
  private $properties;

  /** @return text.ical.Events */
  public function events() { return new Events(...(array)$this->events); }

  /**
   * Converts a calendar date value to a date instance
   *
   * @param  text.ical.Date $date
   * @return util.Date
   * @throws lang.IllegalStateException if the date's timezone is not defined
   */
  public function date(IDate $date) {
    if (null === ($tzid= $date->tzid())) return new Date($date->value());

    foreach ($this->timezones as $timezone) {
      if ($tzid === $timezone->tzid()) return $timezone->convert($date->value());
    }

    throw new IllegalStateException('No timezone definition in calendar for "'.$tzid.'"');
  }

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
      'timezone' => $this->timezones
    ]));
  }
}