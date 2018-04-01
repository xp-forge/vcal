<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;
use util\Date;

class TimeZone implements IObject {
  use TimeZone\is\Value;
  use TimeZone\with\Builder;

  /** @type string */
  private $tzid;

  /** @type text.ical.TimeZoneInfo */
  private $standard;

  /** @type text.ical.TimeZoneInfo */
  private $daylight;

  /**
   * Converts a date
   *
   * @param  string $input `YYYYMMDD"T"HHMMSS`
   * @return util.Date
   */
  public function convert($input) {
    $date= sscanf($input, '%4d%2d%2dT%2d%2d%d');

    $rel= gmmktime($date[3], $date[4], $date[5], $date[1], $date[2], $date[0]);
    $daylight= $this->daylight->start($date[0]);
    $standard= $this->standard->start($date[0]);

    if ($rel >= $standard + $this->standard->adjust() || $rel < $daylight + $this->daylight->adjust()) {
      return new Date(gmdate('Y-m-d H:i:s'.$this->standard->tzoffsetto(), $rel));
    } else {
      return new Date(gmdate('Y-m-d H:i:s'.$this->daylight->tzoffsetto(), $rel));
    }
  }

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
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