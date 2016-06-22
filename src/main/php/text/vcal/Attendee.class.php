<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Attendee implements \lang\Value {
  use Attendee\is\Value;
  use Attendee\with\Builder;

  private $role, $partstat, $rsvp, $cn, $value;
}