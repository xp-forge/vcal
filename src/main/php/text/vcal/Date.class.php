<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Date implements \lang\Value {
  use Date\is\Value;
  use Date\with\Builder;

  private $tzid, $value;
}