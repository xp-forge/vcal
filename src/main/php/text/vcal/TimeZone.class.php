<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class TimeZone implements \lang\Value {
  use TimeZone\is\Value;
  use TimeZone\with\Builder;

  private $tzid, $standard, $daylight;
}