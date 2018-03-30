<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class TimeZoneInfo implements IObject {
  use TimeZoneInfo\is\Value;
  use TimeZoneInfo\with\Builder;

  /** @type string */
  private $dtstart;

  /** @type string */
  private $tzoffsetfrom;

  /** @type string */
  private $tzoffsetto;

  /** @type string */
  private $rrule;

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->object($name, [
      'dtstart'      => $this->dtstart,
      'tzoffsetfrom' => $this->tzoffsetfrom,
      'tzoffsetto'   => $this->tzoffsetto,
      'rrule'        => $this->rrule
    ]);
  }
}