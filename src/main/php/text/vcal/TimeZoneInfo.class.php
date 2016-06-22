<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class TimeZoneInfo implements \lang\Value {
  use TimeZoneInfo\is\Value;
  use TimeZoneInfo\with\Builder;

  private $dtstart, $tzoffsetfrom, $tzoffsetto, $rrule;
}